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
class t_ft_label implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Initialize
        $attr = $e->getAttrAll();
        if (!isset($attr['label'])) { 
            return "<b>ERROR:</b> No 'label' attribute found in 'ft_label' tag.";
        }
        $value = $attr['value'] ?? '';

        // Get HTML
        $html = '<tr><td><b>' . $attr['label'] . ':</b></td><td>' . $value . '</td></tr>';
        return $html;
    }

}


