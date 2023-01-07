
# Syrus class / Container Definitions

The central class within Syrus, and facilitates all functionality.  This class must be instantiated to use Syrus, and the constructor accepts only one argument:

Variable | Required | Type | Description
------------- |------------- |------------- |------------- 
`$container_file` | No | string | Container definitions file to load.  Defaults to ~/config/container.php

For example:

~~~php
use Apex\Syrus\Syrus;

// Start Syrus
$syrus = new Syrus(
    container_file: /path/to/container.php
);

// Render template via auto-routing based on URI being viewed
echo $syrus->render();
~~~


## Container Definitions File

The default location of the container definitions file is at ~/config/container.php, although a different location can be defined via the `$container_file` variable as the first and only argument when instantiating the Syrus class.

The below table describes all items found within this file, and modify accordingly to your specific needs.

Item | Description
------------- |------------- 
template_dir | Location of the ~/views/ directory where all templates and themes are stored.  This directory must contain /html/ and /themes/ sub-directories, plus optionally a /php/ sub-directory.
site_yml | Location of the ~/config.site.yml file.  See the [site.yml Configuration File](site_yml.md) file for details.
theme_uri | The URI where the ~/public/themes/ directory can be found via web browser.  This is used to generate the `~syrus.theme_uri~` global variable to link public assets.
php_namespace | Only applicable if you wish Syrus to check for a per-template PHP class to execute, and the name of the namespace to look in.  See the [Executing Per Template PHP Files](execute_php.md) page for details.
enable_autorouting | If enabled and no template filename is supplied to the `Syrus::render()` method, this will render the template that corresponds to the URI being viewed within the web browser.  See the [Auto-Routing](auto_routing.md) page for details. 
auto_extract_title | If enabled, the first set of `<h1> ... </h1>` tags will be removed from the body contents of the page, and used as the value of the `<s:page_title>` tag.  This allows designers to surround the page header in complex HTML structures, while developers only need to place the page title at the top of the body.
use_cluster | If enabled and Cluster package is installed, will send a RPC call for every template rendered allowing for horizontal scaling and external packages to affect change on the site.  Please see the [Utilizing RPC Calls](rpc.md) page for details. 
rpc_message_request | Only applicable if `use_cluster` is set to true, and is the class name of the `MessageRequestInterface` class to dispatch as an RPC call, in cases where your own implementation of the message request has been written.  Defaults to the message request interface that comes packaged with Cluster.
tag_namespaces | Only applicable if you wish to develop your own functional `<s:...>` tags.  These are the namespaces which Syrus will look in for a PHP class for all template tags encountered.  See the [Creating New `<s:...>` Functional Tags](create_tags.md) page for details.
CacheItemPoolInterface | Any PSR6 compliant cache.
DebuggerInterface | Only applicable if you have the [Apex Debugger](https://github.com/apexpl/debugger) installed, and wish to add debugging information to each template rendered.  Otherwise, leave commented out.  
LoaderInterface | The content loader used to retrieve the values of breadcrums, social media links, and place holders.  See the [Content Loader (breadcrumbs, social media links, placeholders).](content_loader.md) page for details.



