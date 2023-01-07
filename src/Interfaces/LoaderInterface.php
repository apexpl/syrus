<?php
declare(strict_types = 1);

namespace Apex\Syrus\Interfaces;

use Apex\Syrus\Parser\StackElement;
use Psr\Http\Message\UriInterface;


/**
 * Content loader interface
 */
interface LoaderInterface extends AbstractLoaderInterface
{

    /**
     * Get breadcrumbs
     */
    public function getBreadCrumbs(StackElement $e, UriInterface $uri):array;


    /**
     * Get social media links.
     */
    public function getSocialLinks(StackElement $e, UriInterface $uri):array;


    /**
     * Get value of placeholder
     */
    public function getPlaceholder(StackElement $e, UriInterface $uri):string;

}



