<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Render\Tags;
use Apex\Syrus\Interfaces\TagInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_ft_phone implements TagInterface
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
        if (!isset($attr['label'])) { 
            $attr['label'] = ucwords(str_replace('_', ' ', $attr['name']));
        }

        // Get contents
        $contents = $this->tags->phone($e);

        // Get and return form table row
        return $this->tags->getSnippet('ft_twocol', $contents, $attr);
    }

}


