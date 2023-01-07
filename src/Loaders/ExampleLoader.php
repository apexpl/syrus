<?php
declare(strict_types = 1);

namespace Apex\Syrus\Loaders;

use Apex\Syrus\Parser\StackElement;
use Apex\Syrus\Interfaces\LoaderInterface;
use Psr\Http\Message\UriInterface;


/**
 * Example loader class.  Copy this class, and modify as necessary for your 
 * own specific implementation.
 */
class ExampleLoader extends AbstractLoader implements LoaderInterface
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->loadYamlConfig();
    }

    /**
     * Get breadcrumbs
     *
     * Returns associative array, keys being the name displayed within the web browser, and values being the href to link to.  
     * If value is blank, element will not contain a hyperlink.
     */
    public function getBreadCrumbs(StackElement $e, UriInterface $uri):array
    {

        // Set array, two links, one text-only element
        $crumbs = [
            'Home' => '/index', 
            'Template Tags' => '/tags', 
            'Breadcrumbs' => ''
        ];

        // Return
        return $crumbs;
    }

    /**
     * Get social media links.
     *
     * Returns associative array, keys being the icon aliase (ie. FontAwesome) to display, and values being the URL to link to.
     */
    public function getSocialLinks(StackElement $e, UriInterface $uri):array
    {

        // Set social media links
        $links = [
            'twitter' => 'https://twitter.com/mdizak1', 
            'facebook' => 'https://facebook.com', 
            'youtube' => 'https://youtube.com'
        ];

        // Return
        return $links;
    }

    /**
     * Get value of placeholder
     *
     * Returns contents to replace <s:placeholder> tag with.
     */
    public function getPlaceholder(StackElement $e, UriInterface $uri):string
    {

        // Get contents
        $contents = '<example Placeholder';

        // Return
        return $contents;
    }

}


