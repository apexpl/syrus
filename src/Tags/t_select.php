<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Render\Tags;
use Apex\Container\Di;
use Apex\Syrus\Interfaces\TagInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_select implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Initialize
        $onchange = $e->getAttr('onchange') ?? '';
        $width = $e->getAttr('width') ?? '';

        // Set replace
        $replace = [
            '~onchange~' => $onchange != '' ? 'onchange="' . $onchange . '"' : '', 
            '~width~' => $width != '' ? 'style="width: ' . $width . ';"' : ''
        ];

        // Return
        return strtr($html, $replace);
    }

}



