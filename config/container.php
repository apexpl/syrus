<?php

use Psr\Cache\CacheItemPoolInterface;
use Apex\Debugger\Interfaces\DebuggerInterface;
use Apex\Syrus\Interfaces\LoaderInterface;
use Apex\Syrus\Parser\StackElement;

/**
 * Below are the DI container definitions for Syrus, which you 
 * may modify as desired.  Please refer to the documentation for details on below items.
 */
return [

    /**
     * Template directory.  This must contain a /html/ sub-directory where your templates are stored, 
     * and optionally /themes/ and /php/ sub-directories as well.
     */
    'syrus.template_dir' => realpath(__DIR__ . '/../views'), 

    /**
     * site.yaml file location.  See docs for details on this file, and it's makeup.
     */
    'syrus.site_yml' => __DIR__ . '/site.yml', 

    /**
     * Theme URI, in other words URI to /public/themes/ directory on your server.
     * Used to generate ~syrus.theme_uri~ variable, which should be used to link to all public assets (CSS, images, et al).
     */ 
    'syrus.theme_uri' => '/themes', 

    /**
     * Optional PHP namespace.  While rendering a template, Syrus will check this namespace for a class that corresponds to the template file / uri being rendered, 
     * and automatically execute the render() method of that class.  See docs for details.
     */
    'syrus.php_namespace' => "\\Apex\\Syrus\\Views\\Php", 

    /**
     * Enable auto-routing.  If enabled, template file to render will be automatically determined based on request URI.
     */
    'syrus.enable_autorouting' => true, 

    /**
     * Auto-extract page titles.  If enabled, will remote the first set of <h1> ... </h1> tags from each template, and use them as page header within layout.
     */
    'syrus.auto_extract_title' => true, 

    /**
     * Use Cluster.  If enabled and if apex/cluster is installed, will dispatch a RPC call for every template parsed, providing horizontal scaling.
     */
    'syrus.use_cluster' => false, 
    'syrus.rpc_message_request' => \Apex\Cluster\Message\MessageRequest::class, 

    /**
     * Ordered list of namespaces to check for functional template tag (ie. <s:tag_name>) classes.  This only needs 
     * to be modified if you wish to add onto the default tags contained within /src/Tags directory.
     */
    'syrus.tag_namespaces' => [
        "\\Apex\\Syrus\\Tags"
    ], 

    /**
     * Any PSR6 compliant cache, such as Symphony Cache.  Please note, there is no separate setting to disable 
     * cache, and whether or not this item is null determines whether cache is enabled / disabled.
     */
    CacheItemPoolInterface::class => null, 
    'syrus.cache_ttl' => 300, 

    /**
     * Deugger.  Only applicable if using the APex Debugger.
     */
    DebuggerInterface::class => [\Apex\Debugger\Debugger::class, ['debug_level' => 3]], 

    /**
     * Content loader, used to retrieve dynamic content such as breadcrumbs, social media links, and placeholder values.
     * Defaults to ExampleLoader so tag examples work on default site.  Change to your own if desired, or change 
     * to AbstractLoader::class to blank out examples.
     */
    LoaderInterface::class => Apex\Syrus\Loaders\ExampleLoader::class

];



