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

        // Parse include tags
        $html = $this->processIncludeTags($html);

        // Parse yield tags
        $html = $this->processYieldTags($html);

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

        // Check for <s:layout> tag
        $layout = 'default';
        if (preg_match("/<s:layout (.+?)>/i", $this->tpl_code, $match)) {
            $this->tpl_code = str_replace($match[0], '', $this->tpl_code);
            $layout = trim($match[1]);
        }

        // Get layout
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

        // Get html, and replace body
        $html = file_get_contents($layout_file);
        $html = str_replace("<s:page_contents>", $tpl_code, $html);

        // Return
        return $html;
    }

    /**
     * Parse theme tags
     */
    private function processIncludeTags(string $html):string
    {

        // Process html
        do {

            // Go through <s:theme> tags
            preg_match_all("/<s:(theme|include) (.*?)>/si", $html, $theme_match, PREG_SET_ORDER);
            foreach ($theme_match as $match) {
                $output = $this->processSingleInclude(trim($match[1]), Common::parseAttr($match[2]));
                $html = str_replace($match[0], $output, $html);
            }

        } while (preg_match("/<s:theme(.*?)>/si", $html));

        // Return
        return $html;
    }

    /**
     * Parse single theme tag
     */
    private function processSingleInclude(string $tag, array $attr):string
    {

        // Get filename
        if ($tag == 'include') {
            $filename = $attr['_orig'];
        } elseif (isset($attr['include']) && $attr['include'] != '') {
            $filename = $attr['include'];
        }
        $inc_file = $this->theme_dir . '/includes/' . $filename;

        // Check for missing .html extension
        if (file_exists($inc_file . '.html')) {
            $inc_file .= '.html';
        }

        // Get replacement value
        if (file_exists($inc_file)) { 
            $html = file_get_contents($inc_file);
        } else { 
            $html = "<b>ERROR: </b> The include '$filename' does not exist.";
        }

        // Return
        return $html;
    }

    /**
     * Parse yield tags
     */
    private function processYieldTags(string $html):string
    {

        // Go through <s:var> tags
        $vars = [];
        preg_match_all("/<s:var (.+?)>(.*?)<\/s:var>/si", $html, $var_match, PREG_SET_ORDER);
        foreach ($var_match as $m) {
            $alias = strtolower(trim($m[1]));
            $vars[$alias] = $m[2];
            $html = str_replace($m[0], '', $html); 
        }

        // Go through yield tags
        preg_match_all("/<s:yield (.+?)>/i", $html, $yield_match, PREG_SET_ORDER);
        foreach ($yield_match as $m) {
            $alias = strtolower(trim($m[1]));

            // Get value
            $value = $vars[$alias] ?? null;
            if ($value === null) {
                $value = "No 's:var' tag found for the '$alias' yield tag.";
            }
            $html = str_replace($match[0], $value, $html);
        }

        // Return
        return $html;
    }


}


