<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Interfaces\TagInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_boolean implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Set is_checked variables
        $value = $e->getAttr('value') ?? 0;
        $replace = [
            '~chk_yes~' => $value == 1 ? 'checked="checked"' : '', 
            '~chk_no~' => $value == 0 ? 'checked="checked"' : ''
        ];

        // Return
        return strtr($html, $replace);
    }

}


