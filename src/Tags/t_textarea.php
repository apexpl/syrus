<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Interfaces\TagInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_textarea implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Initialize
        $placeholder = $e->getAttr('placeholder') ?? '';

        // Set replace vars
        $replace = [
            '~placeholder~' => $placeholder != '' ? 'placeholder="' . $placeholder . '"' : '', 
            '~value~' => $e->getAttr('value') ?? $e->getBody() 
        ];

        // Return
        return strtr($html, $replace);
    }

}


