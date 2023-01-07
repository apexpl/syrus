
# Yield / Var Tags

As stated within the [File / Directory Structure](theme_structure.md) page, all page layouts are stored within the /views/themes/ALIAS/layouts/ directory, and you may define which layout is utilized for each template by adding the `<s:layout FILENAME>` tag anywhere within the template body page.

Within the layout pages you may add `<s:yield ALIAS>` tags anywhere you wish, then within the body template files, you may place the corresponding `<s:var ALIAS ... </s:var>` tags, the contents of which the yield tags will be replaced with.  This allows you to place customizable blocks within the layout files, the contents of which are then defined within the template body pages themselves.


## Example

For example, you may have a layout file named cool-layout.html with the contents:

~~~html
<s:include header>

<div class="some-wrapper">
    <s:yield headline>
</div>

<s:page_contents>

<s:include footer>
~~~


Within a body template file within the /views/html/ directory, you could then have for example:

~~~
<s:layout cool-layout>

<s:var headline>Some Cool Headline</s:var>

<p>The rest of the page contents...</p>
~~~

With the above example, when the page is rendered, the `<s:yield>` tag within the layout file will be replaced with "Some Cool Headline".  Again, this simply allows you to place placeholders within the layout files, and allow the contents to be specific to each page.



