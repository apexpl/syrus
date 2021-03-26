<?php
declare(strict_types = 1);

namespace Apex\Syrus\Tags;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Interfaces\TagInterface;


/**
 * Renders a specific template tag.  Please see developer documentation for details.
 */
class t_form implements TagInterface
{

    /**
     * Render
     */
    public function render(string $html, StackElement $e):string
    {

        // Set variables
        $action = $e->getAttr('action') ?? $_SERVER['REQUEST_URI'];
        $file_upload = $e->getAttr('file_upload') ?? 0;

        // Set vars
        $replace = [
            '~action~' => '/' . trim($action, '/'), 
            '~enctype~' => $e->getAttr('enctype') ?? 'application/x-www-form-urlencoded'
        ];

        // Check for file upload
        if ($file_upload == 1) { 
            $replace['~enctype~'] = 'multipart/form-data';
        }

        // Return
        return strtr($html, $replace);
    }

}


