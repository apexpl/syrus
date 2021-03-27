<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Interfaces\TagInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_button_group implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {
        return $e->getBody();
    }

}



