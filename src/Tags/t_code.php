<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Interfaces\TagInterface;

/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_code implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Initialize
        $linenums = $e->getAttr('linenums') ?? 0;
        $lang = $e->getAttr('lang') ?? 'php';

        // Set classes
        $code_tag = "<code class=\"language-" . $lang . "\" data-prismjs-copy=\"Copy to Clipboard\" data-prismjs-copy-error=\"Error Copying to Clipboard\" data-prismjs-copy-success=\"Copied to Clipboard\">";
        $pre_tag = "<pre class=\"line-numbers\" data-start=\"1\">";

        // Format body
        $code = strtr($e->getBody(), [
            "<" => "&lt;",
            ">" => "&gt;",
            "&" => "&amp;"
        ]);

        // Set html
        $html = $pre_tag . $code_tag . "\n" . $code . "\n</code></pre>\n";

        // Return
        return $html;
    }

}


