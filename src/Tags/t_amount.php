<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Interfaces\TagInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_amount implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Get currency sign
        $attr = $e->getAttrAll();
        $sign = $attr['sign'] ?? '$';

        // Set replace vars
        $replace = [
            '~placeholder~' => isset($attr['placeholder']) ? 'placeholder="' . $attr['placeholder'] . '"' : '', 
            '~currency_sign~' => $sign
        ];

    // Return
    return strtr($html, $replace);

}

}


