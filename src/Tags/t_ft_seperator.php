<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Render\Tags;
use Apex\Syrus\Interfaces\TagInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_ft_seperator implements TagInterface
{

    #[Inject(Tags::class)]
    private Tags $tags;

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Get contents
        $contents = $e->getAttr('label') ?? 'No Label';

        // Return
        return $this->tags->getSnippet('ft_onecol', $contents, $e->getAttrAll());
    }

}


