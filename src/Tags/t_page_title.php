<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Container\Interfaces\ApexContainerInterface;
use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Interfaces\LoaderInterface;
use Apex\Syrus\Interfaces\TagInterface;

/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_page_title implements TagInterface
{

    #[Inject(ApexContainerInterface::class)]
    private ApexContainerInterface $cntr;

    #[Inject(LoaderInterface::class)]
    private LoaderInterface $loader;

    #[Inject(Tags::class)]
    private Tags $tags;

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Check if page title already exists
        if ($this->cntr->has('syrus.page_title') === true) { 
            return $this->cntr->get('syrus.page_title');
        }

        // Get via content loader
        $title = $this->loader->getPageVar('title');

        // Set in container, and return
        $this->cntr->set('syrus.page_title', $title);
        return $title;
    }

}


