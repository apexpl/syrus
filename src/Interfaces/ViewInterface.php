<?php

namespace Apex\Syrus\Interfaces;

use Apex\Syrus\Syrus;


/**
 * View interface for the per-template PHP classes which by default reside within the /views/php/ directory, but can may moved anywhere you wish.
 *
 * Please see the /docs/execute_php.md file within the documentation for details.
 */
class ViewInterface
{

    /**
     * Render
     */
    public function render(Syrus $syrus):void
    {

    }

}


