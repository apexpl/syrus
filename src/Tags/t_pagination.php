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
class t_pagination implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Get tags instance
        $tags = Di::get(Tags::class);

        // Initialize
        $current_page = $e->getAttr('current_page') ?? 1;
        $total_rows = $e->getAttr('total_rows') ?? 0;
        $rows_per_page = $e->getAttr('rows_per_page') ?? 25;
        $max_items = $e->getAttr('max_items') ?? 10;
        $half_items = (int) floor($max_items / 2);

        // Get href
        $server_uri = $_SERVER['REQUEST_URI'] ?? '/';
        $href = $e->getAttr('pgn_href') ?? $server_uri . '?page=~page~';

        // Return blank, if not enough records
        if ($rows_per_page >= $total_rows) { 
            return '';
        }

        // Get page variables
        $total_pages = ceil($total_rows / $rows_per_page);
        $pages_remaining = ceil(($total_rows - ($current_page * $rows_per_page)) / $rows_per_page);
        $start_page = ($pages_remaining > $half_items && $current_page > $half_items) ? ($current_page - $half_items) : 1;

        // Get end page
        $max_items = ($start_page > $half_items) ? $half_items : ($max_items - $current_page);
        $end_page = ($pages_remaining > $max_items) ? ($current_page + $max_items) : $total_pages;

        // First page
        $display = $start_page > 1 ? 'visible' : 'none';
        $items = $this->paginationItem("&laquo;", $href, 1, $display);

        // Previous page
        $display = $current_page > 1 ? 'visible' : 'none';
        $items .= $this->paginationItem("&lt;", $href, ($current_page - 1), $display);

        // Go through pages
        for ($page_num = $start_page; $page_num <= $end_page; $page_num++) { 

            // Add to pagination items
            if ($page_num == $current_page) { 
                $items .= $tags->getSnippet('pagination.active_item', '', ['page' => (string) $page_num]);
            } else {
                $items .= $this->paginationItem((string) $page_num, $href, (int) $page_num);
            }
        }

        // Next page
        $display = $total_pages > $current_page ? 'visible' : 'none';
        $items .= $this->paginationItem("&gt;", $href, ($current_page + 1), $display);

        // Last page
        $display = $total_pages > $end_page ? 'visible' : 'none';
        $items .= $this->paginationItem("&raquo;", $href, $end_page, $display);

        // Set pagination attributes
        $pgn_attr = [
            '~start_row~' => (($current_page - 1) * $rows_per_page) + 1, 
            '~end_row~' => ($current_page * $rows_per_page) > $total_rows ? $total_rows : ($current_page * $rows_per_page), 
            '~pagination.items~' => $items
        ];

        // Return
        return strtr($html, $pgn_attr);
    }

    /**
     * Get single pagination item
     */
    private function paginationItem(string $name, string $href, int $page, string $display = 'visible'):string
    {

        // Set attributes
        $item_attr = [
            'name' => $name, 
            'href' => str_replace("~page~", (string) $page, $href), 
            'display' => $display, 
            'page' => (string) $page, 
        ];

        // Generate HTML, and return
        $tags = Di::get(Tags::class);
        return $tags->getSnippet('pagination.item', '', $item_attr);
    }

}


