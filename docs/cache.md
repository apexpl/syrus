
# Caching

Syrus supports any PSR-6 cache implementation, along with no-cache configuration available for pages and tags, and an optional `cache` attribute within template tags for granular caching.  To enable caching, within the container definitions file (default ~/config/container.php), simply update the `CacheItemPoolInterface::class` item to any PSR-6 implementation.

Once enabled, Syrus will begin automatically parsing all templates rendered, along with the tags.txt file and the [site.yml configuration file](site_yml.md).  Please note, Syrus only caches templates rendered, and not strings of HTML code rendered via the `Syrus::renderBlock()` method, or any variables.  Instead, all variables are replaced within the templates for each page load.


## no-cache Pages / Tags

If necessary, you may mark specific pages and tags to never be cached through the site.yml configuration file.  For full details, please view the [site.yml Configuration File](site_yml.md) page of this documentation.


## cache Attribute

All `<s:...>` tags also support an optional `cache` attribute which can have a value of either 1 or 0 which override any page / tag settings defined.  For example, if a page is being cached but you wish for a specific tag to not be cached and parsed each time, you can simply add a `cache="0"` attribute such as:

~~~
<s:some_tag var1="val1" cache="0">
    body contents
</s:some_tag>
~~~



