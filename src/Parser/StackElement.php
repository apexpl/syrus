<?php
declare(strict_types = 1);

namespace Apex\Syrus\Parser;


/**
 * Single element from tag stack.
 */
final class StackElement
{

    /**
     * Constructor
     */
    public function __construct(
        private string $id, 
        private string $tag,  
        private array $attr, 
        private string $attr_string, 
        private string $body, 
        private string $replace, 
        private Stack $stack
    ) { 

    }

    /**
     * Get ID
     */
    public function getId():string
    {
        return $this->id;
    }

    /**
     * Get tag
     */
    public function getTag():string
    {
        return $this->tag;
    }

    /**
     * Get attrributes
     */
    public function getAttr(string $name):?string
    {
        return $this->attr[$name] ?? null;
    }

    /**
     * Get all attributes
     */
    public function getAttrAll():array
    {
        return $this->attr;
    }

    /**
     * Get attr string
     */
    public function getAttrString():string
    {
        return $this->attr_string;
    }

    /**
     * Get body
     */
    public function getBody():string
    {
        return $this->body;
    }

    /**
     * Get replace
     */
    public function getReplace():string
    {
        return $this->replace;
    }

    /**
     * Get stack
     */
    public function getStack():Stack
    {
        return $this->stack;
    }

    /**
     * Get children
     */
    public function getChildren(string $child_tag):array
    {

        // Go through all tags within html
        $children = [];
        preg_match_all("/<syrus(\d+?)>/", $this->body, $tag_match, PREG_SET_ORDER);
        foreach ($tag_match as $match) { 

            // Skip if needed
            if (!$this->stack->checkTag($match[1], $child_tag)) { 
                continue;
            }

            // Add to children
            array_push($children, $this->stack->fetch($match[1]));
        }

        // Return
        return $children;
    }


}


