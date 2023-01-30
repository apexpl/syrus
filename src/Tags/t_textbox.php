<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Interfaces\TagInterface;

/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_textbox implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Initialize
        $width = $e->getAttr('width') ?? '';
        $placeholder = $e->getAttr('placeholder') ?? '';
        $attr = $e->getAttrAll();

        // Set replace vars
        $replace = [
            '~width~' => $width != '' ? 'style="width: ' . $width . ';"' : '', 
            '~placeholder~' => $placeholder != '' ? 'placeholder="' . $placeholder . '"' : '', 
            '~actions~' => ''
        ];

        // Get actions
        foreach (array('onfocus','onblur','onkeyup') as $action) { 
            if (!$event = $e->getAttr($action)) { 
                continue;
            }
            $replace['~actions~'] .= $action . "=\"$event\" ";
        }

        // Return
        return strtr($html, $replace);
    }

}


