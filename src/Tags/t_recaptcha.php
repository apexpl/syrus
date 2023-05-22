<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Container\Interfaces\ApexContainerInterface;
use Apex\Syrus\Interfaces\TagInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_recaptcha implements TagInterface
{

    #[Inject(ApexContainerInterface::class)]
    private ApexContainerInterface $cntr;

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Check if site key defined
        if ($this->cntr->get('syrus.recaptcha_site_key') == '') {
            return '';
        }

        // Return
        $html = "<div class=\"g-recaptcha\" data-sitekey=\"" . $this->cntr->get('syrus.recaptcha_site_key') . "\"></div>\n";
        return $html;
    }

}


