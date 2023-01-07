<?php
declare(strict_types = 1);

namespace Apex\Syrus\Views\Php\tags;

use Apex\Syrus\Syrus;

/**
 * Render the template.
 */
class social_links
{

    /**
     * Render
     */
    public function render(Syrus $syrus)
    {
        $syrus->assign('current_time', date('M-d Y H:i'));
        $syrus->setTemplateFile('tags/data_table.html');
    }

}


