<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\{StackElement, Common};
use Apex\Syrus\Interfaces\TagInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_if implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Check for else
        $else = $e->getChildren('else');
        if (count($else) > 0 && preg_match("/^(.*?)<syrus" . $else[0]->getId() . ">(.*)$/si", $e->getBody(), $match)) { 
            $if_html = $match[1];
            $else_html = $match[2];
        } else { 
            $if_html = $e->getBody();
            $else_html = '';
        }

        // Merge condition
        $condition = str_replace("&gt;", ">", $e->getAttrString());
        $condition = Common::mergeVars($condition, $e->getStack()->getMergeVars());

        // Check condition
        $output = eval( "return " . $condition . ";" ) === true ? $if_html : $else_html;

        // Return
        return $output;
    }

}


