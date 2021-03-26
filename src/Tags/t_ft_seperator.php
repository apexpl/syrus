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
class t_ft_seperator implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Get contents
        $tags = Di::get(Tags::class);
        $contents = $tags->getSnippet('seperator', '', $e->getAttrAll());

        // Return
        return $tags->getSnippet('ft_onecol', $contents, $e->getAttrAll());
    }

}


