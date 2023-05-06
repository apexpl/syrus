<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Render\Tags;
use Apex\Syrus\Interfaces\TagInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_callouts implements TagInterface
{

    #[Inject(Tags::class)]
    private Tags $tags;

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Check for callout in attributes
        if (($message = $e->getAttr('message') ?? '') != '') { 
            $type = $e->getAttr('type') ?? 'success';
            $messages = [$message];
        } else { 
            $type = $e->getStack()->getCalloutType();
            $messages = $e->getStack()->getCallouts();
        }

        // Return blank, if no messages
        if (count($messages) == 0) { 
            return '';
        }

        // Get CSS / icon arrays
        $css_aliases = json_decode($this->tags->getSnippet('callouts.css', '', []), true);
        $icons = json_decode($this->tags->getSnippet('callouts.icon', '', []), true);

        // Set replace
        $replace = [
            '~css_alias~' => $css_aliases[$type] ?? $css_aliases['info'], 
            '~icon~' => $icons[$type] ?? 'info',
            '~callout.messages~' => ''
        ];

        // Parse messages
        foreach ($messages as $msg) { 
            $replace['~callout.messages~'] .= $this->tags->getSnippet('callouts.message', '', ['message' => $msg]);
        }
        $replace['~callout.messages~'] = strtr($replace['~callout.messages~'], $replace);

        // Return
        return strtr($html, $replace);
    }

}


