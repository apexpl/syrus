<?php
declare(strict_types = 1);

namespace Apex\Syrus\Views\Php;

use Apex\Syrus\Syrus;

/**
 * Render the template.
 */
class index
{

    /**
     * Render
     */
    public function render(Syrus $syrus)
    {
        $syrus->assign('current_time', date('M-d Y H:i'));
    }

}


