
# StackElement Object

You will come into contact with the `StackElement` object if you either, [create your own &lt;s:...&gt; tags](create_tags.md) or write a [content loader class](content_loader.md) to retrieve the content of breadcrumbs, social media links, et al.  Upon rendering, Syrus will tokenize the template tags with each token being a `StackElement` object which contains various properties such as tag name, attributes, inner body contents, et al.

Below outlines all methods of the `StackElement` object:


#### GetTag():string

Returns the name of the tag.  For example, if the object is for an instance of `<s:stock_quote...>` tag, this would return "stock_quote".


#### getAttr(string $name):?string

Returns the value of the attribute within the tag, or null if attribute is not present.


#### getAttrAll(:array

Returns an associative array of all attributes passed within the tag.


#### getAttrString():string

Returns the string of all attributes before they were parsed into an associative array.  For example, used within `<s:if ...>` tags to obtain the conditional.


#### getBody():string

Only applicable if the tag has an accompanying closing tag, and returns the body contents between opening and closing tags.  Please note, this returns the tokenized version of the body meaning all &lt;s:...&gt; tags within will be tokenized.  Please refer to the `getChildren()` method below to see how to retrieve `<s:...>` tags within the body.


#### GetReplace:string

Should never need to be used, but same as `getBody()` except it also returns the full contents including the `<s:...>` tags.  This is what Syrus replaces in the tokenized template with the output of the tag.


#### getStack():Stack

Should never need to be used, but returns the full `Stack` object which contains all `StackElement` objects being parsed.


#### getChildren(string $child_tag):array

Returns an array of `StackElement` objects for any child tags within the tag.  For example, if the template code is:

~~~
<s:user_list>
    <h3&gt;User List</h3>
    <s:user username="jsmith">
    <s:user username="mike">
</s:user_list>
~~~

If the current `StackElement` instance is of the `<s:user_list>` tag, you could retrieve its child tags with:

~~~php
// $e = StackElement object
$children = $e->getChildren('user');

// $children array contains two elements, both StackElement objects of the <s:user> tag.
~~~



