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
        $this->setTemplateFile($file);

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
    private function doAutoRouting():string
    {

        // Get URI
        $uri = Di::get(UriInterface::class);
        $file = $uri->getPath() == '/' ? 'index.html' : ltrim($uri->getPath(), '/') . '.html';

        // Check file
        if (!file_exists($this->template_dir . '/html/' . $file)) { 

            // Check for 404.html page
            if (!file_exists($this->template_dir . '/html/404.html')) { 
                throw new SyrusTemplateNotFoundException("No template found at this URI, and no 404.html template exists.");
            }
            $file = '404.html';
        }

        // Return
        return $file;
    }

}


