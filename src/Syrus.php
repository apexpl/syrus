<?php
declare(strict_types = 1);

namespace Apex\Syrus;

use Apex\Syrus\Bootloader;
use Apex\Syrus\Render\Templates;
use Apex\Syrus\Parser\{Parser, VarContainer, Common};
use Apex\Container\Container;
use Psr\Http\Message\UriInterface;
use Apex\Container\Interfaces\ApexContainerInterface;
use Apex\Syrus\Exceptions\SyrusTemplateNotFoundException;

/**
 * Syrus Template Engine.
 */
class Syrus extends VarContainer
{

    /**
     * Instantiate new instance of Syrus Template Engine.
     */
    public function __construct(
        private ?string $container_file = '',
        private ?ApexContainerInterface $container = null,
        public bool $require_http_method = false
    ) {

        // Setup container
        $this->cntr = Bootloader::init($this, $this->container_file, $container);
        $this->template_dir = rtrim($this->cntr->get('syrus.template_dir'), '/');

    }

    /**
     * Render a template file.
     */ 
    public function render(string $file = ''):string
    {

        // Check auto-routing
        if ($this->cntr->get('syrus.enable_autorouting') === true && $file == '') { 
            $file = $this->doAutoRouting();
        }
        if ($file != '') { 
            $this->setTemplateFile($file);
        }

        // Render the template
        $tparser = $this->cntr->make(Templates::class);
        $html = $tparser->render();

        // Render again, if no-cache items
        if (preg_match("/<s:(.+?)>/", $html)) { 
            $html = $this->renderBlock($html);
        }

        // Return
        return $html;
    }

    /**
     * Render a block of text.
     */
    public function renderBlock(string $html):string
    {

        // Merge
        $html = Common::mergeVars($html, $this->getVars());

        // Render html
        $parser = $this->cntr->make(Parser::class, [
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
     * Auto-routing.  Determine template file to display based on URI being viewed.
     */
    public function doAutoRouting(string $path = '', string $ext = '.html'):string
    {

        // Get URI
        if ($path == '') { 
            $uri = $this->cntr->get(UriInterface::class);
            $path = $uri->getPath();
        }
        $file = $path == '/' ? 'index' : trim($path, '/');

        // Get filename
        $filename = match(true) { 
            file_exists($this->template_dir . '/html/' . $file . $ext) ? true : false => $file . $ext, 
            file_exists($this->template_dir . '/html/' . $file . '/index' . $ext) ? true : false => $file . '/index' . $ext, 
            file_exists($this->template_dir . '/html/' . $file . '/404.html') ? true : false => $file . '/404.html', 
            default => '404.html'
        };

        // Check directories for 404.html, if needed
        if ($filename == '404.html') {
            $parts = explode('/', trim($path, '/'));
            while (count($parts) > 0) {
                $tmp_filename = implode('/', $parts) . '/404.html';
                if (file_exists($this->template_dir . '/html/' . $tmp_filename)) {
                    $filename = $tmp_filename;
                    break;
                }
                array_pop($parts);
            }
        }

        // Check file
        if (!file_exists($this->template_dir . '/html/' . $filename)) { 
        throw new SyrusTemplateNotFoundException("No template found at this URI, and no 404.html template exists.");
        }

        // Return
        return $filename;
    }

/**
     * Check reCaptcha
     */
    public function checkRecaptcha():bool
    {

        // Check if reCaptcha enabled
        if ($this->cntr->get('syrus.recaptcha_site_key') == '') {
            return true;
        }

        // Set request
        $request = [
            'secret' => $this->cntr->get('syrus.recaptcha_secret_key'),
            'response' => $_POST['g-recaptcha-response'],
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
        ];

        // Send http request
        $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type' => 'application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request));

        // Send http request
        $response = curl_exec($ch);
        curl_close($ch);

        // Decode JSON  
        if (!$vars = json_decode($response, true)) { 
        return false;
        }

        // Check response
        if (isset($vars['success']) && $vars['success'] == true) {
            return true;
        }

        return false;
    }

}


