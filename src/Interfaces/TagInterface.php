<?php
declare(strict_types = 1);

namespace Apex\Syrus\Interfaces;

use Apex\Syrus\Parser\StackElement;

/**
 * Tag interface.  Used for <s:tag_name> tags, and must be named t_tag_name.php.
 */
interface TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string;


}

