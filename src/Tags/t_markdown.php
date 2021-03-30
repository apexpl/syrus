<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Interfaces\TagInterface;
use michelf\markdown;
use Michelf\MarkdownExtra;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_markdown implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Apply markdown formatting
        $md = MarkdownExtra::defaultTransform($e->getBody());
        $md = preg_replace("/<code (.*?)>/", "<code class=\"prettyprint\">", $md);

        // Return
    return $md;
    }

}



