<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Render\Tags;
use Apex\Container\Di;
use Apex\Syrus\Interfaces\TagInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_box implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Initialize
        $tags = Di::get(Tags::class);
        $replace = [
            '~box.header~' => '', 
            '~box.footer~' => ''
        ];

        // Check for header
        $header = $e->getChildren('box_header');
        if (($he = array_shift($header)) !== null) { 
            $replace['~box.header~'] = $tags->getSnippet('box.header', $he->getBody(), $he->getAttrAll());
            $html = str_replace($he->getReplace(), '', $html);
        }

        // Check for footer
        $footer = $e->getChildren('box_footer');
        if (($fe = array_shift($footer)) !== null) { 
            $replace['~box.footer~'] = $tags->getSnippet('box.footer', $fe->getBody(), $fe->getAttrAll());
            $html = str_replace($fe->getReplace(), '', $html);
        }

        // Return
        return strtr($html, $replace);
    }

}


