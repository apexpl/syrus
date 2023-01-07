
# Variables, foreach, and if / else Tags

Below explains how to utilize variables, foreach blocks, and if / else statements within the templates;


## Variables

Variables are simply strings surrounded by ~ tilda marks, for example:

~~~
~full_name~
~total_amount~
~~~

Above are examples of variables, and upon the template being rendered, will be replaced with their corresponding value.  Every site is different, and please consult with the back-end developers regarding which variables the templates will support.

Arrays are also supported and are separated by a period.  For example:

~~~
~user.email~
~user.full_name~
~~~

The above would be replaced with the "username" and "full_name" elements of the "user" array.  Again, please consult with the back-end developers as to exactly which variables the templates will support.

**NOTE:** You may want to request a tags.php file from the back-end developers which you may place at ~/public/tags.php.  This will load dummy data while viewing the site, so you have the appropriate variables and blocks to work with while designing the site.


## foreach Loops

You may iterate over blocks of HTML code when displaying lists of information such as rows within a data table by using the `<s:foreach>` tag, for example:

~~~html
<s:foreach name="users" item="user">
<tr>
    <td>~user.username~</td>
    <td>~user.full_name~</td>
    <td>~user.email~</td>
</tr>
</s:foreach>
~~~

The above will loop through all items within the "users" block, and copy the table row of HTML while replacing the necessary variables.  Again, please consult with the back-end developers for the exact block names and variables the templates will support.

The `<s:foreach>` tag supports two attributes:

Attribute | Required | Description
------------- |------------- |------------- 
name | Yes | The block name as provided by the back-end developers.
item | No | Optional name of the array name used within the merge fields.  If left blank, defaults to the "name" attribute.


## if / else Tags

You may add conditional logic to the templates through the use of `<s:if condition>` / `<s:else>` tags, for example:

~~~html
<s:if ~user.id~ == 0>
    You are not logged in.
<s:else>
    Hello ~user.username~
</s:if>
~~~

The above will check whether or not the `~user.id~` variable is equal to 0, and if so display a message saying the user is not logged in, otherwise will display a greeting.  A few notes regarding the `<s:if>` tag:

* You may use any variables desired within the condition, and they will be replaced with their corresponding value before the condition is evaluated.
* To use the greater than sign within the condition, use `&gt;`, and it will be evaluated as the greater than sign.
* There is no need to include `<s:else>` within the block, and its use is optional.


Once ready, continue to the [&lt;s:...&gt; Template Tags](tags.md) page of the documentation.

