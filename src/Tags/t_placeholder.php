<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Container\Interfaces\ApexContainerInterface;
use Apex\Syrus\Interfaces\{LoaderInterface, TagInterface};
use Psr\Http\Message\UriInterface;

/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_placeholder implements TagInterface
{

    #[Inject(ApexContainerInterface::class)]
    private ApexContainerInterface $cntr;

    #[Inject(LoaderInterface::class)]
    private LoadterInterface $loader;

    #[Inject(UriInterface::class)]
    private UriInterface $uri;

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        $uri = $this->cntr->get(UriInterface::class);

        // Get contents
        $output = $this->loader->getPlaceholder($e, $uri);
        return $output;
    }

}


