<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\{StackElement, Common};
use Apex\Syrus\Interfaces\TagInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_foreach implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Get name
        if (!$name = $e->getAttr('name')) { 
            return "<b>ERROR:</b> The 'foreach' tag does not contin a 'name' attribute, which is required.";
        }
        $item = $e->getAttr('item') ?? $name;

        // Get block values
        $block = $e->getStack()->getBlock($name);

        // Create html
        $html = '';
        foreach ($block as $vars) { 
            $html .= Common::mergeVars($e->getBody(), [$item => $vars]);
        }

        // Return
        return $html;
    }

}


