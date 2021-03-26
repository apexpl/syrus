<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Render\Tags;
use Apex\Container\Di;
use Apex\Syrus\Interfaces\{LoaderInterface, TagInterface};
use Psr\Http\Message\UriInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_breadcrumbs implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Check for loader
        if (!$loader = Di::get(LoaderInterface::class)) { 
            return '';
        }

        // Get items from container
        $uri = Di::get(UriInterface::class);
        $tags = Di::get(Tags::class);

        // Get crumbs
        $crumbs = $loader->getBreadcrumbs($e, $uri);

        // GO through crumbs
        $items = '';
        foreach ($crumbs as $name => $href) { 
            $tag_name = $href == '' ? 'breadcrumbs.active_item' : 'breadcrumbs.item';
            $items .= $tags->getSnippet($tag_name, '', ['name' => $name, 'href' => $href]);
        }

        // Replace and return
        return str_replace('~breadcrumbs.items~', $items, $html);
    }

}


