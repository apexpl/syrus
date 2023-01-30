<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Render\Tags;
use Apex\Container\Interfaces\ApexContainerInterface;
use Apex\Syrus\Interfaces\{LoaderInterface, TagInterface};
use Psr\Http\Message\UriInterface;

/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_social_links implements TagInterface
{

    #[Inject(ApexContainerInterface::class)]
    private ApexContainerInterface $cntr;

    #[Inject(Tags::class)]
    private Tags $tags;

    #[Inject(LoaderInterface::class)]
    private LoaderInterface $loader;

    #[Inject(UriInterface::class)]
    private UriInterface $uri;

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Get social networks
        $networks = $this->loader->getSocialLinks($e, $this->uri);

        // Create items
        $items = '';
        foreach ($networks as $alias => $url) {
            $name = ucwords($alias);
            $items .= "<a href=\"$url\" target=\"_blank\" title=\"Visit $name\"><span class=\"fa fa-" . $alias . "\"></span></a> ";
        }

        // Return
        return $items;
    }

}


