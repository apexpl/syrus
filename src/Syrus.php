<?php
declare(strict_types = 1);

namespace Apex\Syrus;

use Apex\Syrus\Bootloader;
use Apex\Syrus\Render\Templates;
use Apex\Syrus\Parser\{Parser, VarContainer, Common};
use Apex\Container\Di;
use Psr\Http\Message\UriInterface;
use Apex\Syrus\Exceptions\SyrusTemplateNotFoundException;


/**
 * Syrus template engine.
 */
class Syrus extends VarContainer
{

    /**
     * Constructor
     */
    public function __construct(
        private ?string $container_file = ''
    ) {

        // Setup container
        Bootloader::init($this, $this->container_file);
        $this->template_dir = rtrim(Di::get('syrus.template_dir'), '/');

    }

    /**
     * Render template
     */ 
    public function render(string $file = ''):string
    {

        // Check auto-routing
        if (Di::get('syrus.enable_autorouting') === true && $file == '') { 
            $file = $this->doAutoRouting();
        }
        if ($file != '') { 
            $this->setTemplateFile($file);
        }

        // Render the template
        $tparser = Di::make(Templates::class);
        $html = $tparser->render();

        // Render again, if no-cache items
        if (preg_match("/<s:(.+?)>/", $html)) { 
            $html = $this->renderBlock($html);
        }

        // Return
        return $html;
    }

    /**
     * Render block
     */
    public function renderBlock(string $html):string
    {

        // Merge
        $html = Common::mergeVars($html, $this->getVars());

        // Render html
        $parser = Di::make(Parser::class, [
            'tpl_code' => $html, 
            'vars' => $this->gatherVars(), 
            'parse_nocache' => true
        ]);

        // Render
        $html = $parser->render();

        // merge and return
        return Common::mergeVars($html, $this->getVars());
    }

    /**
     * Do auto-routing
     */
    public function doAutoRouting(string $path = '', string $ext = '.html'):string
    {

        // Get URI
        if ($path == '') { 
            $uri = Di::get(UriInterface::class);
            $path = $uri->getPath();
        }
        $file = $path == '/' ? 'index' : trim($path, '/');

        // Get filename
        $filename = match(true) { 
            file_exists($this->template_dir . '/html/' . $file . $ext) ? true : false => $file . $ext, 
            file_exists($this->template_dir . '/html/' . $file . '/index' . $ext) ? true : false => $file . '/index' . $ext, 
            file_exists($this->template_dir . '/html/' . $file . '/404' . $ext) ? true : false => $file . '/404.html', 
            default => '404' . $ext
        };

        // Check file
        if (!file_exists($this->template_dir . '/html/' . $filename)) { 
        throw new SyrusTemplateNotFoundException("No template found at this URI, and no 404.html template exists.");
        }

        // Return
        return $filename;
    }

}


