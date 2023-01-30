<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Render\Tags;
use Apex\Container\Interfaces\ApexContainerInterface;
use Apex\Syrus\Interfaces\TagInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_ft_submit implements TagInterface
{

    #[Inject(ApexContainerInterface::class)]
    private ApexContainerInterface $cntr;

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Set is_checked variables
        $align = $e->getAttr('align') ?? 'center';

        // Get submit
        $tags = $this->cntr->get(Tags::class);
        $contents = $tags->submit($e);

        // Get and return form table row
        return $tags->getSnippet('ft_onecol', $contents, ['align' => $align]);
    }

}


