
# Content Loader (breadcrumbs, social media links, placeholders).

Syrus utilizes a separate class / interface to dynamically retrieve data for breadcrumbs, social media links, and values of placeholders helping provide easy implementation into your back-end application.  

The container definitions file (default ~/config/container.php) contains a `LoaderInterface::class` item, which is the location of your content loader class.  This defaults to the `ExampleLoader` class, which is meant to be just that, an example.  It's recommended you either change this to the `BlankLoader` class, or write your own class using the /src/loaders/ExampleLoader.php class as an example.

Below explains all methods within this class, each of which take two arguments:

Variable | Type | Description
------------- |------------- |------------- 
`$e` | StackElement | The `StackElement` object of the tag being parsed.  See the [StackElement Object](stackelement.md) page for details.
`$uri` | UriInterface | A PSR-7 compliant UriInterface object, created using the global $_SERVER params.


##getBreadCrumbs():array

Called when Syrus encounters the `<s:breadcrumbs>` tag, and used to obtain the breadcrumbs to display on the page being rendered.  Returns an associative array, the keys being the name of the breadcrumb item to display within the browser, and the value being the URI to link the item to.  Array elements with an empty value will be considered active and will not include a hyperlink.  For example:

~~~php
public function getBreadCrumbs(StackElement $e, UriInterface $uri):array
{

    // Set breadcrumbs
    $crumbs = [
        'Home' => '/index', 
        'Products' => '/products', 
        'Phone' => ''
    ];

    // Return
    return $crumbs;
}
~~~

The above would produce breadcrumbs with the items "Home &gt; Products &gt; Phone" with the first two items linking to their corresponding URIs, and the third item being text only.


## getSocialLinks():array

Called when the `<s:social_links>` tag is encountered, and obtains the social media links / icons with their respective URLs to display on the page being rendered.  Useful if for example, you're hosting a blog and wish to display the author's social media links to be displayed on articles they've written.

This method returns an associative array, the keys being the Font Awesome icon alias of the icon (eg. twitter, facebook, youtube, et al), and the values being the URL to link each icon to.  For example:

~~~php
public function getSocialLinks(StackElement $e, UriInterface $uri):array
{

    // Get links
    $links = [
        'twitter' => 'https://twitter.com/tusername', 
        'facebook' => 'https://facebook.com/profile_uri'
    ];

    // Return
    return $links;
}
~~~


## getPlaceholder():string

Called when an `<s:placeholder>` tag is encountered, and designed mainly for distributable software.  This allows both, non-technical site administrators to modify text blocks on various pages, and helps ensure when templates are included in an upgrade any textual modifications made by the site administrator are preserved and not overwritten.

This is an open-ended implementation, and is expected you'll define your own required / optional attributes to use throughout the templates, which are then checked within this method to retrieve the appropriate placeholder value.  For example:

~~~php
public function getPlaceholder(StackElement $e, UriInterface $uri):?string
{

    // Get attributes
    $alias = $e->getAttr('alias') ?? '';

    // Get page being displayed
    $path = $uri->getPath();

        // Retrieve placeholder value based on URI / alias
    $value = 'some_value';

    // Return
    return $value;
}
~~~



