<?php
declare(strict_types = 1);

namespace Apex\Syrus\Parser;

use Apex\Syrus\Parser\{Common, Stack};
use Apex\Syrus\Render\Tags;
use Apex\Container\Di;
use Apex\Debugger\Interfaces\DebuggerInterface;
use Apex\Syrus\Interfaces\LoaderInterface;

/**
 * Handles rendering of blocks of tpl code.
 */
class Parser
{

    /**
    * Constructor
     */
    public function __construct(
        private LoaderInterface $loader,
        private ?DebuggerInterface $debugger = null,  
        private string $tpl_code = '',
        private array $vars = [], 
        private bool $parse_nocache = true
    ) { 

    }

    /**
     * Render a block of code
     */
    public function render():string
    {

        // Tokenize tpl code
        $stack = $this->tokenize($this->tpl_code);

        // Process stack
        $html = $this->processStack($stack);

        // Return code
        return $html;
    }

    /**
     * Tokenize block of tpl code
     */
    private function tokenize(string $tpl_code):Stack
    {

        // Instantiate stack
        $stack = new Stack($this->vars);

        // GO through all tags
        preg_match_all("/<([\/]?)s:(.+?)>/si", $tpl_code, $tag_match, PREG_SET_ORDER);
        foreach ($tag_match as $match) { 

            // Get tag name and attributes
            $parts = explode(' ', $match[2], 2);
            $tag = strtolower($parts[0]);
            $search = '/' . preg_quote($match[0], '/') . '/';

            // Get attributes
            $attr_string = $parts[1] ?? '';
            $attr = Common::parseAttr($attr_string);

            // Check cache processing
        if ($this->checkDoParse($tag, $attr) !== true) { 
            continue;
        }

            // Closing tag
            if ($match[1] == '/' && $tag_id = $stack->closeTag($tag)) {
                $tpl_code = preg_replace($search, '</syrus' . $tag_id . '>', $tpl_code, 1);

            // Opening tag
            } elseif ($match[1] != '/') { 
                $tag_id = $stack->push($tag, $attr_string);
                $tpl_code = preg_replace($search, '<syrus' . $tag_id . '>', $tpl_code, 1);
            }
        }

        // Finish, and return
        $stack->setTplCode($tpl_code);
        return $stack;
    }

    /**
     * Process stack
     */
    private function processStack(Stack $stack):string
    {

        // Initialize
        $html = $stack->getTplCode();
        $tags = Di::makeset(Tags::class);

        // Go through stack
        while ($e = $stack->pull()) { 

            // Parse tag
            $tag_name = $e->getTag();
            $output = $tags->$tag_name($e);

            // Replace tag output
            $html = str_replace($e->getReplace(), $output, $html);
        }

        // Return
        return $html;
    }

    /**
     * Check whether or not to parse tag, depending on cache settings.
     */
    private function checkDoParse(string $tag_name, array $attr):bool
    {

        // Check 'parse_nocache' setting.
        if ($this->parse_nocache === true) { 
            return true;
        }

        // Check for attributes
        if (isset($attr['cache']) && $attr['cache'] == 1) { 
            return true;
        } elseif (isset($attr['cache']) && $attr['cache'] == 0) { 
            return false;
        }

        // Check loader
        return $this->loader->checkNoCacheTag($tag_name);
    }

}

