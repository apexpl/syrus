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
class t_social_links implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {
        // Get loader
        if (!$loader = Di::get(LoaderInterface::class)) { 
            return '';
        }

        // Get items from container
        $uri = Di::get(UriInterface::class);
        $tags = Di::get(Tags::class);

        // Get social networks
        $networks = $loader->getSocialLinks($e, $uri);

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


