<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Container\Di;
use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Interfaces\LoaderInterface;
use Apex\Syrus\Interfaces\TagInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_page_title implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Check if page title already exists
        if (Di::has('syrus.page_title') === true) { 
            return Di::get('syrus.page_title');
        }

        // Get via content loader
        $loader = Di::get(LoaderInterface::class);
        $title = $loader->getPageVar('title');

        // Set in container, and return
        Di::set('syrus.page_title', $title);
        return $title;
    }

}


