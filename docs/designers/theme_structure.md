
# Designers - File / Directory Structure

Syrus themes have been designed with the understanding that front-end designers have enough to do, and have no need or desire to navigate around back-end software code.  There are three pertinent directories within Syrus:

Directory | Description
------------- |------------- 
views/themes/mysite/ | The theme itself, see below for details.
public/themes/mysite/ | All public assets for the theme (ie. CSS, Javascript, images, et al).
views/html/ | The body contents of pages.


<a name="theme_structure"></a>
## Theme Structure

The theme itself is located within the ~/views/themes/mysite/ directory, and consists of the following:

File / Directory | Description
------------- |------------- 
layouts/ | The supported page layouts (eg. full width, 2 column left sidebar, et al).  One layout is overlayed onto each template rendered.  See below for details.
includes/ | Any includes necessary for the theme, such as header and footer.
tags.txt | Contains all HTML snippets the various `<s:...>` tags are replaced with. 

### Includes

Within the ~/views/themes/mysite/includes directory you will find a default header and footer, which are rather self-exclamatory.  You may add files as desired to this directory, and the contents can be included within any file via `<s:theme>` tags, for example:

`<s:theme include="header.html">`

That's it, and that tag will be replaced with the contents of the ~/views/themes/mysite/includes/header.html file.


### Layouts

One layout is applied to every template rendered, allowing different pages to have different layouts (eg. full width, two column left sidebar, et al).  The /layouts/ sub-directory of the theme contains all layouts, which can be named anything you wish.  

Structure the layout any way as you wish using the includes described above for components such as header and footer.  Within each layout, place the following template tag:

`<s:page_contents>`

The above tag will be replaced with the body contents of the page being viewed.  You may define which layout to use for each page by modifying the `layouts` section within the ~/config/site.yml file as desired, for example:

~~~
layouts:
  services/cleaning.html: 2_col_left_sidebar
    contact.html: 2_col_left_sidebar
    default: default
~~~

In the above example, the services/cleaning.html and contact.html templates will be rendered using the `2_col_left_sidebar.html` layout, while all other templates will use the `default.html` layout.


### tags.txt File

This file contains all the various HTML tags that the `<s:...>` template tags are replaced with, such as tab controls, data tables, callouts, et al.  While the `<s:...>` tags stay the same, their HTML counterparts can vary from theme to theme, and this file allows you to ensure all components of the site are rendered properly for the specific theme being used.

The file itself should be rather straight forward, but each entry is the same, for example:

~~~
[[form_table]]
@default(width=95%, align=left)

<table border="0" cellpadding="6" cellspacing="6" style="width: ~width~; align: ~align~;">
    ~contents~
</table>
~~~

The `<s:form_table>` tag would be replaced with the above snippet, which allows for the attributes "width" and "align" that default to "95%" and "left" respectively.


<a name="body_pages"></a>
## Body Content Pages

The ~views/html/ directory contains all the body content pages for the site, and although not always, are generally named by the URI within the browser (eg. http://domain.com/services displays services.html template).  The proper layout will be applied to the page, with the `<s:page_contents>` tag within the layout being replaced by the contents of the body page.

**NOTE:** Unless auto-extracting page titles is disabled within the ~/config/container.php file, Syrus will automatically remove the first set of `<h1> ... </h1> tags from the body contents, and replace all occurrences of the `&lt;s:page_title&gt;` tag with it.  This allows you to enclose the page title within complex HTML structures, while the back-end developers only have to quickly place the title at the top of each body page.


<a name="public_assets"></a>
## Public Assets

All public assets (ie. images, CSS, Javascript, et al) should be placed within the ~/public/themes/mysite directory.  Within the templates, this directory should always be referenced via the `~syrus.theme_uri~` variable, for example:

`<link href="~syrus.theme_uri~/css/styles.css" rel="stylesheet" type="text/css" />`

The above would link to the ~/public/themes/mysite/css/styles.css file on your system.  All public assets should be linked to in this manner including images, for example:

`<img src="~syrus.theme_uri~/img/logo.png">`


Once comfortable with the theme structure, check out the [Variables, foreach, and if / else Tags](variables.md) page to continue.



