<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Container\Di;
use Apex\Syrus\Interfaces\{LoaderInterface, TagInterface};
use Psr\Http\Message\UriInterface;

/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_placeholder implements TagInterface
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
        $uri = Di::get(UriInterface::class);

        // Get contents
        $output = $loader->getPlaceholder($e, $uri);
        return $output;
    }

}


