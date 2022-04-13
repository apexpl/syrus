
# Syrus Template Engine

A light weight, extensible template engine designed with separation of duties in mind, allowing both back-end developers and designers to complete their work independently.  It supports:

* Standardized `<s:...)` template tags providing short hand for larger HTML snippets, interopability across themes, plus additional PHP functionality.
* Easily develop your own functional <s:...> template tags in addition to default set.
* Easy loading of dynamic content such as breadcrumbs, social media links, and placeholders.
* Multiple themes based on template file location (eg. different themes for public site, admin panel, and client area).
* PSR6 caching, including configuration for no-cache pages and tags, and support for optional "cache" attribute within tags.
* Optional auto-routing, automatically renders the appropriate template corresponding to the URI being viewed. 
* Optional per-template PHP class, which is automatically executed upon rendering the template providing bridge between templates and back-end application.
* Developed with designers in mind, allowing them to work independently and without the need to navigate back-end software code.
* Built-in support for Cluster, which makes an RPC call for every template rendered providing support for horizontal scaling including parameter based routing.
* Optional built-in support for [Apex Debugger](https://github.com/apexpl/debugger) to gather debugging information on templates rendered.


## Installation

Install via Composer with:

> `composer require apex/syrus`


## Table of Contents

1. Designers
    1. [Getting Started](https://github.com/apexpl/syrus/blob/master/docs/designers/getting_started.md)
    2. [File / Directory Structure](https://github.com/apexpl/syrus/blob/master/docs/designers/theme_structure.md)
        1. [Theme Structure](https://github.com/apexpl/syrus/blob/master/docs/designers/theme_structure.md#theme_structure)
        2. [Body Content Pages](https://github.com/apexpl/syrus/blob/master/docs/designers/theme_structure.md#body_pages)
        3. [Public Assets](https://github.com/apexpl/syrus/blob/master/docs/designers/theme_structure.md#public_assets)
    3. [Yield / Var Tags](https://github.com/apexpl/syrus/blob/master/docs/designers/yield.md)
    4. [Variables, foreach, and if / else Tags](https://github.com/apexpl/syrus/blob/master/docs/designers/variables.md)
    5. [&lt;s:...&gt; Template Tags](https://github.com/apexpl/syrus/blob/master/docs/designers/tags.md)
    6. [Using Multiple Themes](https://github.com/apexpl/syrus/blob/master/docs/designers/multiple_themes.md)
2. Developers
    1. [Syrus class / Container Definitions](https://github.com/apexpl/syrus/blob/master/docs/syrus.md)
        1. [site.yml Configuration File](https://github.com/apexpl/syrus/blob/master/docs/site_yml.md)
    2. [Render Templates](https://github.com/apexpl/syrus/blob/master/docs/render.md)
        1. [Auto-Routing](https://github.com/apexpl/syrus/blob/master/docs/autorouting.md)
        2. [Executing Per Template PHP Files](https://github.com/apexpl/syrus/blob/master/docs/execute_php.md)
        3. [Utilizing RPC Calls](https://github.com/apexpl/syrus/blob/master/docs/rpc.md)
    3. [Variables, Blocks and Callouts](https://github.com/apexpl/syrus/blob/master/docs/variables.md)
    4. [Content Loader (breadcrumbs, social media links, placeholders)](https://github.com/apexpl/syrus/blob/master/docs/content_loader.md)
    5. [Creating New `<s...>` Functional Tags](https://github.com/apexpl/syrus/blob/master/docs/create_tags.md)
    6. [StackElement Object](https://github.com/apexpl/syrus/blob/master/docs/stackelement.md)
    7. [Dummy Data for Designers](https://github.com/apexpl/syrus/blob/master/docs/dummy_data.md)
    7. [Caching](https://github.com/apexpl/syrus/blob/master/docs/cache.md)


## Local Server

Get Syrus up and running with the default web site by running the following command within the ~/public/ directory:

> `php -S 127.0.0.1:8180`

The default site including tag examples will now be available at http://127.0.0.1:8180/.  Alternatively, if you have docker-compose installed you may achieve the same by running the following command:

> `sudo docker-compose up -d`


## Basic Usage

~~~php
use Apex\Syrus\Syrus;

// Start
$syrus = new Syrus();

// Assign some variables
$syrus->assign('name', 'value');

// Assign array
$location = [
    'city' => 'Toronto', 
    'province' => 'Ontario', 
    'country' => 'Canada'
];
$syrus->assign('loc', $location);

// Add foreach blocks
$syrus->addBlock('users', ['username' => 'jsmith', 'email' => 'jsmith@domain.com']);
$syrus->addBlock('users', ['username' => 'mike', 'email' => 'mike@domain.com']);

// ADd error callout
$this->addCallout('Uh oh, there was a problem.', 'error');

// Render template
echo $syrus->render('contact.html');

// Or, use auto-routing and render template based on URI being viewed.
echo $syrus->render();
~~~


## Support

If you have any questions, issues or feedback for Syrus, please feel free to drop a note on the <a href="https://reddit.com/r/apexpl/">ApexPl Reddit sub</a> for a prompt and helpful response.


## Follow Apex

Loads of good things coming in the near future including new quality open source packages, more advanced articles / tutorials that go over down to earth useful topics, et al.  Stay informed by joining the <a href="https://apexpl.io/">mailing list</a> on our web site, or follow along on Twitter at <a href="https://twitter.com/mdizak1">@mdizak1</a>.



