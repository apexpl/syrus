<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Container\Interfaces\ApexContainerInterface;
use Apex\Syrus\Interfaces\TagInterface;

/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_hcaptcha implements TagInterface
{

    #[Inject(ApexContainerInterface::class)]
    private ApexContainerInterface $cntr;

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Check if site key defined
        if ($this->cntr->get('syrus.hcaptcha_site_key') == '') {
            return '';
        }
        $site_key = $this->cntr->get('syrus.hcaptcha_site_key');

        // Set html
        $html = "<script src=\"https://js.hcaptcha.com/1/api.js\" async defer></script>\n";
        $html .= "<div class=\"h-captcha\" data-sitekey=\"$site_key\"></div>\n";

        // Return
        return $html;
    }

}




