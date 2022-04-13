<?php
declare(strict_types = 1);

namespace Apex\Syrus\Parser;


/**
 * Commonly used functions, such as parsing a string for attributes, merging variables, etc.
 */
class Common
{


    /**
     * Parse attributes
     */
    public static function parseAttr(string $string):array
    {

        // Initialize
        $attr = [
            '_orig' => trim($string)
        ];


        // Parse attributes
        preg_match_all("/(.+?)=\"(.+?)(\"|$)/", $string, $attr_match, PREG_SET_ORDER);
        foreach ($attr_match as $match) { 
            $attr[trim($match[1])] = trim($match[2], '"');
        }

        // Return
        return $attr;
    }

    /**
     * Merge vars
     */
    public static function mergeVars(string $html, array $vars):string
    { 

        // Go through vars
        foreach ($vars as $key => $value) { 

            // Check if scalar
            if (is_scalar($value)) {
                $html = str_ireplace("~$key~", $value, $html);
                continue;
            }
            if (!is_array($value)) { continue; }

            // Go through array elements
            foreach ($value as $child_key => $child_value) { 
                if (!is_scalar($child_value)) { continue; }
                $html = str_ireplace("~$key.$child_key~", (string) $child_value, $html);
            }
        }

        // Return
        return $html;
    }

}

