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
     8 Theme directory - Contains all theme related files including layouts, includes, etc.  Generally defaults 
     * to tetemplate_dir/themes.
     */
    'syrus.theme_dir' => realpath(__DIR__ . '/../views/themes'), 

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
     * Whether or not to use multiple themes, or just one single theme.
     */
    'syrus.use_multiple_themes' => true,

    /**
     * Whether or not to require the PHP method within __template_dir__/php/uri.php to contain the 
     * HTTP method function (eg. get(), post(), etc.) in order to display the .html template.  If true and the 
     * PHP class does not contain the appropriate method, a 404 page will be displayed.
     */
    'syrus.require_php_method' => false,

    /**
     * Enable auto-routing.  If enabled, template file to render will be automatically determined based on request URI.
     */
    'syrus.enable_autorouting' => true, 

    /**
     * If ture and corresponding .php file to view exists, it must contain either 
     * render() method or HTTP method specific function (eg. get(), post(), etc.).  Otherwise, 
     * will give 404 page not found error.
     */
    'syrus.require_http_method' => false,

    /**
     * Auto-extract page titles.  If enabled, will remote the first set of <h1> ... </h1> tags from each template, and use them as page header within layout.
     */
    'syrus.auto_extract_title' => true, 

    /**
     * If using <s:recaptch> tag, the reCaptcha site and secret keys.
     */
    'syrus.recaptcha_site_key' => '',
    'syrus.recaptcha_secret_key' => '',

    /**
     * If using <s:hcaptch> tag, the reCaptcha site and secret keys.
     */
    'syrus.hcaptcha_site_key' => '',
    'syrus.hcaptcha_secret_key' => '',


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



