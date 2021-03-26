<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Interfaces\TagInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_checkbox implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Set is_checked variables
        $is_checked = $e->getAttr('is_checked') ?? 0;
        $onclick = $e->getAttr('onclick') ?? '';

        // Set replace
        $replace = [
            '~chk~' => $is_checked == 1 ? 'checked="checked"' : '', 
            'onclick' => $onclick != '' ? "onclick=\"$onclick\"" : ''
        ];

        // Replace and return
        return strtr($html, $replace);
    }

}



