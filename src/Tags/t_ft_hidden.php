<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Interfaces\TagInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_ft_hidden implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Initialize
        $name = $e->getAttr('name') ?? '';
        $value = $e->getAttr('value') ?? '';

        // Check
        if ($name == '') {
            return "<b>ERROR:</b> The hidden form field does not contain a 'name' attribute.";
        } elseif ($value == '') {
            return "<b>ERROR:</b> The hidden form field does not contain a 'value' attribute.";
        }

        // Create html
        $html = "<input type=\"hidden\" name=\"$name\" value=\"$value\" />";
        return $html;
    }

}


