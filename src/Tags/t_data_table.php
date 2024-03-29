<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\{StackElement, Common};
use Apex\Syrus\Render\Tags;
use Apex\Syrus\Interfaces\TagInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_data_table implements TagInterface
{

    #[Inject(Tags::class)]
    private Tags $tags;

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Initialize
        $attr = $e->getAttrAll();
        if (!isset($attr['id'])) { 
            $attr['id'] = 'tblMain';
        }

        // Get total columns
        if (preg_match("/<thead>(.*)<tr(.*?)>(.*?)<\/tr>(.*?)<\/thead>/si", $html, $match)) { 
            $attr['total_columns'] = substr_count($match[3], '</th>');

            // Parse header columns
            $html = $this->parseHeaderColumns($html, $match[3], $attr);

        } else { 
            $attr['total_columns'] = substr_count($html, '</th>');
        }

        // Get search bar, if needed
        if (isset($attr['has_search']) && $attr['has_search'] == 1) { 
            $html = $this->addSearchBar($html, $attr);
        } else { 
            $attr['search_bar'] = '';
        }

        // Get table footer
    $html = $this->applyFooter($html, $attr, $e);

        // Return
        return $html;
    }

    /**
     * Parse header columns
     */
    private function parseHeaderColumns(string $html, string $replace, array $attr):string
    {

        // Initialize
        $new_header = $replace;

        // Get last header row
        if (!preg_match("/^(.*)<tr>(.*?)<\/tr>(.*?)<\/thead>/si", $html, $m)) { 
            return $html;
        }
        $header_html = $m[2];

        // GO through headers
        preg_match_all("/<th(.*?)>(.*?)<\/th>/si", $header_html, $th_match, PREG_SET_ORDER);
        foreach ($th_match as $match) { 

            // Parse attr
            $th_attr = Common::parseAttr($match[1]);
            $sort_attr = [
                'sort_href' => $attr['sort_href'] ?? '#', 
                'name' => $match[2], 
                'col_alias' => $th_attr['alias'] ?? strtolower(str_replace(' ', '_', $match[2])),
                'sort_dir' => 'asc',
                'sort_asc' => '', 
                'sort_desc' => ''
            ];

            // Check can sort
            $has_sort = $th_attr['has_sort'] ?? 0;
            if ($has_sort == 1) { 
                $sort_attr['sort_asc'] = $this->tags->getSnippet('data_table.sort_asc', '', $sort_attr);
                $sort_attr['sort_dir'] = 'desc';
                $sort_attr['sort_desc'] = $this->tags->getSnippet('data_table.sort_desc', '', $sort_attr);
            }

            // Get th html
            $th = $this->tags->getSnippet('data_table.th', '', $sort_attr);
            $new_header = str_replace($match[0], $th, $new_header);
        }

        // Finish and return
        return str_replace($header_html, $new_header, $html);
    }

    /**
     * Add search bar
     */
    private function addSearchBar(string $html, array $attr):string
    {

        // Initialize
        $search_bar = $this->tags->getSnippet('data_table.search_bar', '', $attr);

        // Add search bar to html
        if (str_contains($html, '~search_bar~')) {
            $html = str_replace('~search_bar~', $search_bar, $html);
        } else { 
            $html = preg_replace("/<thead(.*?)>/i", "\$0" . $search_bar, $html, 1);
        }

        // Return
        return $html;
    }

    /**
     * Get footer
     */
    private function applyFooter(string $html, array $attr, StackElement $e):string
    {

        // Initialize
        $total_rows = $e->getAttr('total_rows') ?? 0;
        $buttons = $e->getChildren('button_group');

        // Start footer attr
        $footer_attr = [
            'total_columns' => $attr['total_columns'], 
            'button_group' => '', 
            'pagination' => ''
        ];

        // Return blank, if no footer
        if ($total_rows == 0 && count($buttons) == 0) { 
            return str_replace('~table.footer~', '', $html);
        }

        // Check button group
        if (($be = array_shift($buttons)) !== null) {
            $footer_attr['button_group'] = $be->getBody();
            $html = str_replace($be->getReplace(), '', $html);
        }

        // Get pagination, if needed
        if ($total_rows > 0) { 

            // Set attributes
            $pgn_attr = $attr;
            $pgn_attr['id'] = preg_replace("/^tbl/", "", $pgn_attr['id']);

            // Create pagination html
            $pgn_e = new StackElement($e->getId(), 'pagination', $pgn_attr, '', '', '', $e->getStack()); 
            $footer_attr['pagination'] = $this->tags->pagination($pgn_e);
        }

        // Get and return html
        $footer = $this->tags->getSnippet('data_table.footer', '', $footer_attr);
        return str_replace('~table.footer~', $footer, $html);
    }


}


