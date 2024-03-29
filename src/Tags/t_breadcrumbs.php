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
class t_breadcrumbs implements TagInterface
{

    #[Inject(ApexContainerInterface::class)]
    private ApexContainerInterface $cntr;

    #[Inject(Tags::class)]
    private Tags $tags;

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Check for loader
        if (!$loader = $this->cntr->get(LoaderInterface::class)) { 
            return '';
        }

        // Get items from container
        $uri = $this->cntr->get(UriInterface::class);
        $tags = $this->cntr->get(Tags::class);

        // Get crumbs
        $crumbs = $loader->getBreadcrumbs($e, $uri);

        // GO through crumbs
        $items = '';
        foreach ($crumbs as $name => $href) { 
            $tag_name = $href == '' ? 'breadcrumbs.active_item' : 'breadcrumbs.item';
            $items .= $this->tags->getSnippet($tag_name, '', ['name' => $name, 'href' => $href]);
        }

        // Replace and return
        return str_replace('~breadcrumbs.items~', $items, $html);
    }

}


