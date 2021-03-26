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
class t_ft_date_selector implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Initialize
        $attr = $e->getAttrAll();
        if (!isset($attr['label'])) { 
            $attr['label'] = ucwords(str_replace('_', ' ', $attr['name']));
        }

        // Get contents
        $tags = Di::get(Tags::class);
        //$contents = $tags->getSnippet('date_selector', '', $attr);
        $contents = $tags->date_selector($e);

        // Get and return form table row
        return $tags->getSnippet('ft_twocol', $contents, $attr);
    }

}


