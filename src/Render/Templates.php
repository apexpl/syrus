<?php
declare(strict_Types = 1);

namespace Apex\Syrus\Render;

use Apex\Syrus\Syrus;
use Apex\Syrus\Parser\{Common, Parser};
use Apex\Container\Interfaces\ApexContainerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Apex\Syrus\Interfaces\LoaderInterface;
use Apex\Debugger\Interfaces\DebuggerInterface;
use Apex\Cluster\Dispatcher;
use Apex\Cluster\Interfaces\{MessageResponseInterface, FeHandlerInterface};
use Apex\Syrus\Exceptions\{SyrusRpcException, SyrusTemplateNotFoundException};
use Psr\Http\Message\ServerRequestInterface;

/**
 * Renders full page templates.
 */
class Templates
{

    #[Inject(ApexContainerInterface::class)]
    private ApexContainerInterface $cntr;

    #[Inject(Syrus::class)]
    private Syrus $syrus;

    #[Inject(LoaderInterface::class)]
    private LoaderInterface $loader;

    #[Inject(CacheItemPoolInterface::class)]
    private ?CacheItemPoolInterface $cache = null;

    #[Inject(DebuggerInterface::class)]
    private ?DebuggerInterface $debugger = null;

    /**
     * Render template
     */
    public function render():string
    {

        // Send RPC call via cluster, if needed
        if ($this->cntr->get('syrus.use_cluster') === true && class_exists(Dispatcher::class) && $this->syrus->getRpcEnabled() === true) { 
            $this->dispatchRPcCall($this->syrus->getTemplateFile());
        }

        // Execute any needed php class
        $this->executePhpClass($this->syrus->getTemplateFile());
        $file = $this->syrus->getTemplateFile();

        // Check cache
        $item = $this->cache?->getItem('syrus:t:' . $file);
        if ($item !== null && $item?->isHit() === true) { 
            return $item->get();
        }

        // Get tpl code
        $tpl_code = $this->getTplCode();

        // Apply layout
        $layouts = $this->cntr->make(Layouts::class, [
            'tpl_code' => $tpl_code
        ]);
        $tpl_code = $layouts->apply();

        // Merge vars
        $tpl_code = Common::mergeVars($tpl_code, $this->syrus->getVars());

        // Check caching
        $nocache = $this->cache === null || $this->loader->checkNoCachePage($file) === true ? true : false;

        // Render template
        $parser = $this->cntr->make(Parser::class, [
            'tpl_code' => $tpl_code, 
            'vars' => $this->syrus->gatherVars(), 
            'parse_nocache' => $nocache
        ]);

        // Render html
        $html = $parser->render();
        if ($nocache === true) { 
            return Common::mergeVars($html, $this->syrus->getVars());
        }

        // Add to cache, if available
        $item->set($html);
        if (($ttl = $this->cntr->get('syrus.cache_ttl')) !== null) { 
            $item->expiresAfter((int) $ttl);
        }
        $this->cache?->save($item);

        // Return
        return Common::mergeVars($html, $this->syrus->getVars());
    }

    /**
     * Execute php class
     */
    private function executePhpClass(string $file):void
    {

        // Check container config
        if (!$php_nm = $this->cntr->get('syrus.php_namespace')) { 
            return;
        }

        // Get php class
        $php_class = $php_nm . "\\" . str_replace("/", "\\", ltrim(preg_replace("/\..+$/", '', $file), '/'));
        if (!class_exists($php_class)) { 
            return;
        }
        $obj = $this->cntr->make($php_class);

        // Get request method
        if (!$request = $this->cntr->get(ServerRequestInterface::class)) {
            $method = $_SERVER['REQUEST_METHOD'];
        } else {
            $method = $request->getMethod();
        }

        // Check require_http_method if necessary
        //$require_http_method = $this->cntr->get('syrus.require_http_method') ?? false;
        $require_http_method = false;
        if ($require_http_method === true && (!method_exists($obj, strtolower($method))) && (!method_exists($obj, 'render'))) {
            $this->syrus->setTemplateFile('404', true);
        }

        // Check for http specific method
        if (method_exists($obj, strtolower($method))) {
            $this->cntr->call([$obj, strtolower($method)]);
        }

        // Call render method, if needed
        if (method_exists($obj, 'render')) {
            $this->cntr->call([$obj, 'render']);
        }

        // Render again, if template has changed
        if ($file != $this->syrus->getTemplateFile()) { 
            $this->executePhpClass($this->syrus->getTemplateFile());
        }

    }

    /**
     * Dispatch RPC call
     */
    private function dispatchRpcCall(string $file)
    {

        // Ensure message class exists
        $msg_class = $this->cntr->get('syrus.rpc_message_request');
        if (!class_exists(\Apex\Cluster\Cluster::class)) {
            throw new SyrusRpcException("The apex/cluster package is not installed and RPC calls are enabled.  Please either disable RPC calls via the ~/config/container.php file or install the Cluster pacakge with the command 'composer require apex/cluster'");
        } elseif (!class_exists($msg_class)) { 
            throw new SyrusRpcException("Message request class does not exist for RPC call at, $msg_class");
        }

        // Get instance name
        if (!$instance_name = GetEnv('instance_name')) { 
            $instance_name = 'web1';
        }

        // Create message
        $routing_key = 'syrus.parse.' . str_replace('/', '_', preg_replace("/\..+$/", "", ltrim($file, '/')));
        $msg = new $msg_class($routing_key);

        // Dispatch the message
        $dispatcher = new \Apex\Cluster\Dispatcher($instance_name);
        $dispatcher->dispatch($msg, 'rpc', [$this, 'handleClusterResponse']);
    }

    /**
     * Handle cluster callback
     */
    public function handleClusterResponse(FeHandlerInterface $handler):void
    {

        // Go through variables
        $vars = $handler->getVars();
        foreach ($vars as $key => $value) { 
            $this->syrus->assign($key, $value);
        }

        // Add blocks
        $blocks = $handler->getBlocks();
        foreach (array_keys($blocks) as $name) { 
            foreach ($blocks[$name] as $block => $values) { 
                $this->syrus->addBlock($block, $values);
            }
        }

        // Add callouts
        $callouts = $handler->getCallouts();
        foreach ($callouts as $vars) { 
            $this->syrus->addCallout($vars[0], $vars[1]);
        }

        // Go through actions
        $actions = $handler->getActions();
        foreach ($actions as $action_vars) { 
            list($action, $vars) = [$action_vars[0], $action_vars[1]];

            if ($action == 'set_template_file') { 
                $this->syrus->setTemplateFile($vars[0], $vars[1]);
            }
        }

    }

    /**
     * Get tpl code
     */
    private function getTplCode():string
    {

        // Get template file
        $template_dir = rtrim($this->cntr->get('syrus.template_dir'), '/');
        $file = $this->syrus->getTemplateFile();
        $tpl_file = $template_dir . '/html/' . $file;

        // Get template file
        if (file_exists($tpl_file)) { 
            $tpl_code = file_get_contents($tpl_file);
    } elseif (file_exists($tpl_file . '.html')) { 
            $tpl_code = file_get_contents($tpl_file . '.html');
        } else { 
            throw new SyrusTemplateNotFoundException("Template does not exist, $file");
        }

        // Return
        return $tpl_code;
    }


}



