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
class t_tab_control implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Initialize
        list($tab_num, $nav_html, $page_html) = [1, '', ''];
        $active_tab = $e->getAttr('active_tab') ?? 1;
        $tags = Di::get(Tags::class);

        // Go through tab pages
        $tab_pages = $e->getChildren('tab_page');
        foreach ($tab_pages as $pg) { 

            // Get nav html
            $nav_attr = [
                'num' => $tab_num, 
                'name' => $pg->getAttr('name') ?? "Unnamed $tab_num"
            ];
            $nav_html .= $tags->getSnippet('tab_control.nav_item', '', $nav_attr);

            // Get page html
            $page_attr = [
                'num' => $tab_num, 
                'active' => $active_tab == $tab_num ? $tags->getSnippet('tab_control.css_active', '', []) : ''
            ];
            $page_html .= $tags->getSnippet('tab_control.page', $pg->getBody(), $page_attr);

        $tab_num++; } 

        // Set replace
        $replace = [
            '~nav_items~' => $nav_html, 
            '~tab_pages~' => $page_html
        ];

        // Return
        return strtr($html, $replace);
    }

}

