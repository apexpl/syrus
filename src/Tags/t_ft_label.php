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
        $href = $attr['href'] ?? '';
        $bold = $attr['bold'] ?? 1;

        // Check value
        if ($value == '') {
            $value = $e->getBody();
        }

        // Add hyperlink, if needed
        if ($href != '') { 
            $value = "<a href=\"$href\">$value</a>";
        }

        // Add bold, if needed
        $attr['label'] .= ':';
        if ($bold == 1) { 
            $attr['label'] = '<b>' . $attr['label'] . '</b>';
        }

// Return
        $tags = Di::make(Tags::class);
        return $tags->getSnippet('ft_twocol', $value, $attr);
    }

}


