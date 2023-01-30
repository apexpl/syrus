<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Interfaces\TagInterface;

/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_date_interval_selector implements TagInterface
{

    // Periods
    private array $periods = [
        'I' => 'Minutes', 
        'H' => 'Hours', 
        'D' => 'Days', 
        'W' => 'Weeks', 
        'M' => 'Months', 
        'Y' => 'Years'
    ];
    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Initialize
        $add_time = $e->getAttr('add_time') ?? 0;
        $value = $e->getAttr('value') ?? '';
        $required = $e->getAttr('required') ?? 0;

        // Parse value
        if (preg_match("/^(\w)(\d+)$/", $value, $m)) { 
            list($period, $length) = [$m[1], $m[2]];
        } else { 
            list($period, $length) = ['', ''];
        }
        $show_periods = $add_time == 1 ? ['I','H','D','W','M','Y'] : ['D','W','M','Y'];

        // Start period options
        $options = '';
        if ($required == 0) { 
            $chk = $value == '' ? 'selected="selected"' : '';
            $options .= "<option value=\"\" $chk>-----</option>";
        }

        // Create period options
        foreach ($show_periods as $abbr) { 
            $chk = $abbr == $period ? 'selected="selected"' : '';
            $options .= "<option value=\"$abbr\" $chk>" . $this->periods[$abbr] . "</option>";
        }

        // Set replace
        $replace = [
            '~length~' => $length, 
            '~period_options~' => $options
        ];

        // Return
        return strtr($html, $replace);
    }

}


