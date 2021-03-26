<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Interfaces\TagInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_time_selector implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Initialize
        $required = $e->getAttr('required') ?? 0;
        $value = $e->getAttr('value') ?? '00:00';

        // Parse value
        $vars = explode(':', $value);
        $hour = $vars[0] ?? 0;
        $min = $vars[1] ?? 0;

        // Start replace vars
        $replace = [
            '~hour_options~' => '', 
            '~minute_options~' => '' 
        ];

        // Start hour options
        if ($required == 0) { 
            $chk = $hour == 0 ? 'selected="selected"' : '';
            $replace['~hour_options~'] .= "<option value=\"00\" $chk>-----</option>";
        }

        // Hour options
        for ($x=0; $x <= 23; $x++) { 
            $chk = (int) $hour == $x ? 'selected="selected"' : '';
            $val = str_pad((string) $x, 2, '0', STR_PAD_LEFT);
            $replace['~hour_options~'] .= "<option value=\"$val\" $chk>$val</option>";
        }

        // Start minute options
        if ($required == 0) { 
            $chk = $min == 0 ? 'selected="selected"' : '';
            $replace['~minute_options~'] .= "<option value=\"00\" $chk>-----</option>";
        }

        // Minute options
        foreach (array('00', '15', '30', '45') as $x) { 
            $chk = $x == $min ? 'selected="selected"' : '';
            $replace['~minute_options~'] .= "<option value=\"$x\" $chk>$x</option>";
        }

        // Return
        return strtr($html, $replace);
    }

}


