<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Interfaces\TagInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_date_selector implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Initialize
        $required = $e->getAttr('required') ?? 0;
        $start_year = $e->getAttr('start_year') ?? 1940;
        $end_year = $e->getAttr('end_year') ?? (date('Y') + 3);
        $start_num = $required == 1 ? 1 : 0;

        // GEt value
        $value = $e->getAttr('value') ?? '0000-00-00';
        if ($required == 1 && $value == '0000-00-00') { 
            $value = date('Y-m-d');
        }
        list($year, $month, $day) = explode('-', $value);

        // Start replace vars
        $replace = [
            '~month_options~' => '', 
            '~day_options~' => '', 
            '~year_options~' => ''
        ];

        // Month options
        for ($x = $start_num; $x <= 12; $x++) { 
            $chk = $x == (int) $month ? 'selected="selected"' : '';
            $name = $x == 0 ? '----------' : date('F', mktime(0, 0, 0, $x, 1, 2000));
            $replace['~month_options~'] .= "<option value=\"" . str_pad((string) $x, 2, '0', STR_PAD_LEFT) . "\" $chk>$name</option>";
        }

        // Month options
        for ($x = $start_num; $x <= 31; $x++) { 
            $chk = $x == (int) $day ? 'selected="selected"' : '';
            $name = $x == 0 ? '--' : str_pad((string) $x, 2, '0', STR_PAD_LEFT);
            $replace['~day_options~'] .= "<option value=\"" . str_pad((string) $x, 2, '0', STR_PAD_LEFT) . "\" $chk>$name</option>";
        }

        // Add blank option to year options, if not required
        if ($required == 0) { 
            $chk = (int) $year == 0 ? 'selected="selected"' : '';
            $replace['~year_options~'] .= "<option value=\"0000\" $chk>------</option>";
        }

        // Month options
        for ($x = $start_year; $x <= $end_year; $x++) { 
            $chk = $x == (int) $year ? 'selected="selected"' : '';
            $replace['~year_options~'] .= "<option value=\"$x\" $chk>$x</option>";
        }

        // Return
        return strtr($html, $replace);
    }

}


