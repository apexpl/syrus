<?php
declare(strict_types = 1);

namespace Apex\Syrus\Parser;

use Apex\Syrus\Parser\{Common, StackElement};
use Apex\Syrus\Exceptions\SyrusOutOfBoundsException;


/**
 * Queue that handles the tokenized tag stack while parsing a template.
 */
class Stack
{

    // Properties
    private int $tag_id = 0;
    private string $parent_id = '0';
    public array $stack = [];
    public array $meta = [];
    private array $depth = [];
    private string $position = '0';

    // Variable properties
    private array $merge_vars;
    private array $blocks;
    private array $callouts;
    private string $callout_type;


    /**
     * Constructor
     */
    public function __construct(
        array $vars, 
        private string $tpl_code = '' 
    ) { 

        // Set variables
        $this->merge_vars = $vars['vars'] ?? [];
        $this->blocks = $vars['blocks'] ?? [];
        $this->callouts = $vars['callouts'] ?? [];
        $this->callout_type = $vars['callout_type'] ?? 'success';

    }

    /**
     * Push onto stack
     */
    public function push(string $tag, string $attr_string):string
    {

        // Initialize
        $id = (string) ++$this->tag_id;
        if (!isset($this->depth[$tag])) { 
            $this->depth[$tag] = [];
        }
        $this->depth[$tag][] = $id;

        // Add to meta
        $this->meta[$id] = [
            'id' => $id, 
            'parent_id' => $this->parent_id, 
            'tag' => $tag, 
            'is_closed' => false, 
            'attr_string' => $attr_string, 
            'attr' => Common::parseAttr($attr_string)
        ];

        // Add to stack
        if (!isset($this->stack[$this->parent_id])) { 
            $this->stack[$this->parent_id] = [];
        }
        $this->stack[$this->parent_id][] = $id;

        // Return
        $this->parent_id = $id;
        return $id;
    }

    /**
     * Close tag
     */
    public function closeTag(string $tag):?string
    {

        // Ensure we have an opening tag
        if ((!isset($this->depth[$tag])) || count($this->depth[$tag]) == 0) { 
            return null;
        }
        $tag_id = array_pop($this->depth[$tag]);

        // Close tag
        $this->meta[$tag_id]['is_closed'] = true;
        $this->parent_id = $this->meta[$tag_id]['parent_id'];

        // Return
        return $tag_id;
    }

    /**
     * Pull from stack.
     */
    public function pull():?StackElement
    {

        // Check stack
        $is_empty = isset($this->stack[$this->position]) && count($this->stack[$this->position]) > 0 ? false : true;
        if ($is_empty === true && $this->position == '0') { 
            return null;
        } elseif ($is_empty === true) { 
            $this->position = $this->meta[$this->position]['parent_id'];
            return $this->pull();
        }

        // Get next
        $this->position = array_shift($this->stack[$this->position]);

        // Fetch and return
        return $this->fetch($this->position);
    }
    /**
     * Fetch and return StackElement
     */
    public function fetch(string $id):StackElement
    {

        // Check if exists
        if (!isset($this->meta[$id])) { 
            throw new SyrusOutOfBoundsException("Position does not exist within stack, $id");
        }

        // Get body contents, if exists
        if ($this->meta[$id]['is_closed'] === true && preg_match("/<syrus" . $id . ">(.*?)<\/syrus" . $id . ">/si", $this->tpl_code, $match)) {
            $this->meta[$id]['body'] = $match[1];
            $this->meta[$id]['replace'] = $match[0];
        } else {
            $this->meta[$id]['body'] = '';
            $this->meta[$id]['replace'] = '<syrus' . $id . '>';
        }
        $tag = $this->meta[$id];

        // Return new StackElement
        return new StackElement(
            $id, 
            $tag['tag'], 
            $tag['attr'], 
            $tag['attr_string'], 
            $tag['body'], 
            $tag['replace'], 
            $this
        );

    }

    /**
     * Set tpl code
     */
    public function setTplCode(string $code):void
    {
        $this->tpl_code = $code;
    }

    /**
     * Get tpl code
     */
    public function getTplCode():string 
    {
        return $this->tpl_code;
    }

    /**
     * Get merge vars
     */
    public function getMergeVars():array
    {
        return $this->merge_vars;
    }

    /**
     * Get blocks
     */
    public function getBlocks():array
    {
        return $this->blocks;
    }

    /**
     * Get single block
     */
    public function getBlock(string $name):array
    {
        return $this->blocks[$name] ?? [];
    }

    /**
     * Get callouts
     */
    public function getCallouts():array
    {
        return $this->callouts;
    }

    /**
     * Get callout type
     */
    public function getCalloutType():string
    {
        return $this->callout_type;
    }

    /**
     * Check tag
     */
    public function checkTag(string $id, string $tag):bool
    {

        // Chekc
        if (!isset($this->meta[$id])) { 
            return false;
        } elseif ($this->meta[$id]['tag'] != $tag) { 
            return false;
        } elseif ($this->meta[$id]['tag'] != $tag) { 
            return false;
        } 

        // Return
        return true;
    }

}



