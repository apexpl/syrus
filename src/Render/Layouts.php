<?php
declare(strict_types = 1);

namespace Apex\Syrus\Render;

use Apex\Syrus\Syrus;
use Apex\Container\Di;
use Apex\Syrus\Parser\Common;
use Apex\Syrus\Interfaces\LoaderInterface;
use Apex\Syrus\Exceptions\SyrusLayoutNotExistsException;


/**
 * Handles theme layouts, mainly applying them to templates
 */
class Layouts
{


    /**
     * Construct
     */
    public function __construct(
        private string $tpl_code, 
        private Syrus $syrus, 
        private LoaderInterface $loader 
    ) { 

    }

    /**
     * Apply theme.
     */
    public function apply():string
    {

        // Determine layout
        $layout = $this->determineLayout();

        // Extract page title, if needed
        $this->extractPageTitle();

        // Add layout
        $html = $this->addLayout($this->tpl_code, $layout);

        // Parse theme tags
        $html = $this->parseThemeTags($html);

        // Return
        return $html;
    }

    /**
     * Determine layout
     */
    private function determineLayout():string
    {

        // Get needed variables
        $theme = $this->syrus->getTheme();
        $file = $this->syrus->getTemplateFile();

        // Get theme
        if ($theme == '') { 
            $theme = $this->loader->getTheme($file);
            $this->syrus->setTheme($theme);
        }
        $this->theme_dir = rtrim(Di::get('syrus.template_dir'), '/') . '/themes/' . $theme;

        // Get layout
        $layout = $this->loader->getPageVar('layouts');
        if (file_exists($this->theme_dir . '/layouts/' . $layout . '.html')) { 
            $layout .= '.html';
        }

        // Ensure layout exists
        if (!file_exists($this->theme_dir . '/layouts/' . $layout)) { 
            throw new SyrusLayoutNotExistsException("Layout does not exist '$layout' within the theme '$theme'");
        }

        // Return
        return $layout;
    }

    /**
     * Extract page title.
     */
    private function extractPageTitle():void
    {

        // Check container config
        if (Di::get('syrus.auto_extract_title') !== true) { 
            return;
        }

        // Check for page title
        if (!preg_match("/<h1(.*?)>(.*?)<\/h1>/si", $this->tpl_code, $match)) { 
            return;
        }

        // Set page title
        Di::set('syrus.page_title', $match[2]);
        $this->tpl_code = str_replace($match[0], '', $this->tpl_code);
    }

    /**
     * Add layout
     */
    private function addLayout(string $tpl_code, string $layout):string
    {

    // Check if layout exists
        $layout_file = $this->theme_dir . '/layouts/' . $layout;
        if (!file_exists($layout_file)) {
            throw new SyrusLayoutNotExistsException("Layout does not exist $layout within the theme $this->theme");
        }

        // Get layout, and replace body contents
        $html = file_get_contents($layout_file);
        $html = str_replace("<s:page_contents>", $tpl_code, $html);

        // Return
        return $html;
    }

    /**
     * Parse theme tags
     */
    private function parseThemeTags(string $html):string
    {

        // Process html
        do {

            // Go through <s:theme> tags
            preg_match_all("/<s:theme(.*?)>/si", $html, $theme_match, PREG_SET_ORDER);
            foreach ($theme_match as $match) { 
                $output = $this->parseSingleThemeTag(Common::parseAttr($match[1]));
                $html = str_replace($match[0], $output, $html);
            }

        } while (preg_match("/<s:theme(.*?)>/si", $html));

        // Return
        return $html;
    }

    /**
     * Parse single theme tag
     */
    private function parseSingleThemeTag(array $attr):string
    {

        // Include file
        if (isset($attr['include']) && $attr['include'] != '') { 

            $inc_file = $this->theme_dir . '/includes/' . $attr['include'];
            if (file_exists($inc_file)) { 
                return file_get_contents($inc_file);
            } else { 
                return "<b>ERROR: </b> The include '$attr[include]' does not exist.";
            }

        }

        // If here, no valid theme attributes found
        return "<b>ERROR:</b> Invalid 's:theme' tag.";

    }

}


