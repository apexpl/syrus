<?php
declare(strict_types = 1);

namespace APex\Syrus\Render;

use Apex\Syrus\Syrus;
use Apex\Syrus\Parser\StackElement;
use Apex\Container\Di;
use Apex\Syrus\Exceptions\{SyrusInvalidTagMethodExceptionSyrusYamlException};
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Psr\Cache\{CacheItemPoolInterface, CacheItemInterface};


/**
 * Handles rendering of individual special HTML tags (<s:...> tags).>)
 */
class Tags
{

    // Properties
    private array $snippets = [];
    private array $required = [];
    private array $default_attributes = [];


    /**
     * Constructor
     */
    public function __construct(
        private Syrus $syrus, 
        private ?CacheItemPoolInterface $cache = null 
    ) { 

        // Set variables
        $this->theme = $syrus->getTheme();
        $this->theme_dir = rtrim(Di::get('syrus.template_dir'), '/') . '/themes/' . $this->theme;
        $this->tag_namespaces = Di::get('syrus.tag_namespaces');

    }

    /**
     * Call a tag
     */
    public function __call(string $tag_name, array $vars = []):?string
    {

        // Initialize
        $e = array_shift($vars) ?? '';
        if (!$e instanceof StackElement) { 
            throw new SyrusInvalidTagMethodException("Invalid tag method called, '$tag_name'.  Did not receive StackElement");
        }
        $attr = $e->getAttrAll();

        // Get tag snippet
        $html = $this->getSnippet($tag_name, $e->getBody(), $attr);

        // Check for functional tag within namespaces
        $has_func = false;
        foreach ($this->tag_namespaces as $nm) { 
            $tag_class = $nm . "\\t_" . $tag_name;
            if (!class_exists($tag_class)) { 
                continue;
            }

            // Render html via tag specific class
            $tag = Di::make($tag_class);
            $html = $tag->render($html, $e);

            // Break
            $has_func = true;
            break;
        }

        // Error, if empty tag
        if ($has_func === false && $html == '') { 
            $html = "<b>ERROR:</b> The template tag '$tag_name' does not exist.<br />";
        }

        // Return
        return $html;
    }

    /**
     * Get tag snippet
     */
    public function getSnippet(string $tag_name, string $body = '', array $attr = []):string
    {

        // Read tags if not already loaded
        if (count($this->snippets) == 0) { 
            $this->readTags();
        }

        // Check for tag in defaults
        $html = $this->snippets[$tag_name] ?? '';
        if ($html == '') {
            return '';
        }

        // Get default attributes
        $def_attr = $this->default_attributes[$tag_name] ?? [];
        $required = $this->required[$tag_name] ?? [];

        // Check required attributes
        foreach ($required as $key) { 
            if (!isset($attr[$key])) { 
                return "<b>ERROR:</b> The '$tag_name' template tag requires a '$key' attribute.<br />";
            }
        }

        // Check default attributes
        $attr = array_merge($def_attr, $attr);
        foreach ($attr as $key => $value) { 
            if ($key == '_required') { continue; }
            $html = str_replace("~$key~", (string) $value, $html);
        }
        $html = str_replace("~contents~", $body, $html);

        // Return
        return $html;
    }

    /**
     * Read the /tags.txt file for the theme.
     */
    private function readTags():void
    {

        // Get filename and cache key to check
        if ($this->theme != '' && file_exists($this->theme_dir . '/tags.txt')) { 
            $cache_key = 'theme.' . $this->theme . '.tags';
            $tag_file = $this->theme_dir . '/tags.txt';
        } else { 
            $cache_key = 'global.default_tags';
            $tag_file = __DIR__ . '/../../config/default_tags.txt';
        }

        // Check cache
        $item = $this->cache?->getItem($cache_key);
        if ($item !== null && $item?->isHit() === true) {  
            $this->snippets = $item->get();
            return;
        }

        // Check if tag file exists
        if (!file_exists($tag_file)) { 
            return;
        }

        // Read tags.txt file
        $tag_match = preg_split("/\[\[(.+?)\]\]/si", file_get_contents($tag_file), 0, PREG_SPLIT_DELIM_CAPTURE);
        array_shift($tag_match);

        // Format all entries in tags.txt file
        while ($tag_match) { 
            $key = array_shift($tag_match);
            $lines = explode("\n", array_shift($tag_match));

            $lines = array_filter($lines, function ($line) { 
                return (trim($line) == '/**' || str_starts_with(trim($line), '*')) ? false : true;
            });

            // Set tag
            $this->snippets[$key] = trim(implode("\n", $lines));
            $this->default_attributes[$key] = [];

            // Check for default / required
            preg_match_all("/\@(required|default|)\((.*?)\)/si", $this->snippets[$key], $attr_match, PREG_SET_ORDER);
            foreach ($attr_match as $m) { 
                $vals = array_map(function($v) { return trim($v); }, explode(',', $m[2]));
                if ($m[1] == 'required') { 
                    $this->required[$key] = $vals;
                } elseif ($m[1] == 'default') { 
                    foreach ($vals as $val) { 
                        list($k, $v) = explode('=', $val, 2);
                        $this->default_attributes[$key][$k] = $v;
                    }
                }
                $this->snippets[$key] = str_replace($m[0], '', $this->snippets[$key]);
            }
            $this->snippets[$key] = trim($this->snippets[$key]);    
        }

        // Save cache item
        if ($item !== null) { 
            $item->set($this->snippets);
            $this->cache->save($item);
        }

    }

}

