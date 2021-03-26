
# Variables, Blocks and Callouts

All methods to personalize templates are available within the main `Syrus` class, and are explained below.


## Assigning Variables

Scalar variables and arrays can be assigned via the `Syrus::assign()` method, for example:

~~~php
use Apex\Syrus\Syrus;

// Start
$syrus = new Syrus();


// Assign scalar
$syrus->assign('username', 'jsmith', 
$syrus->assign('email', 'jsmith@domain.com');

// Assign array
$vars = [
    'city' => 'Toronto', 
    'province' => 'Ontario', 
    'country' => 'Canada'
];
$syrus->assign('location', $vars);
~~~

Within templates, the above variables can be represented with for example:

~~~php
Hello ~username~ with e-mail ~email~

Your location is ~location.city~, ~location.province~, ~location.country~
~~~

As you can see, variables are represented within templates by surrounding their names with tilda ~ marks, and array elements are separated by periods.


#### Assign Array of Scalars

If desired, you may leave the first argument blank and pass an array as the second argument, and all array elements will be added as single scalars.  For example:

~~~php
use Apex\Syrus\Syrus;

$syrus = new Syrus();

$vars = [
    'username' => 'jsmith', 
    'full_name' => 'John Smith', 
    'email' => 'jsmith@domain.com'
];
$syrus->assign('', $vars);
~~~

The above would allow the following variables within the templates:

~~~
Hello ~full_name~ (~username~) with e-mail ~email~
~~~


## Adding foreach Blocks

You may add blocks to be looped over with the `Syrus::addBlock()` method, for example:

~~~php
use Apex\Syrus\Syrus;

// Start
$syrus = new Syrus();

// Add blocks
$syrus->addBlock('users', ['username' => 'jsmith', 'email' => 'jsmith@domain.com']);
$syrus->addBlock('users', ['username' => 'mike' => 'email' => 'mike @domain.com']);
$user->addBlock('users', ['username' => 'leanne', 'email' => 'leanne@gmail.com']);
~~~

The first argument is the name of the block / loop, and the second argument is an associative array of key value pairs for that iteration.  Within the templates a `foreach` block can then be placed such as:

~~~
<s:foreach name="users" item="user">
    The user ~user.username~ has the e-mail ~user.email~
</s:foreach>
~~~



## Adding Callouts

Callouts are dynamic messages that generally appear on the top of pages contained within colored boxes to show success, error or other informational messages.  You may add callouts via the `Syrus::addCallout()` method, for example:

~~~php
use Apex\Syrus\Syrus;


// Start
$syrus = new Syrus();

// Success callout
$syrus->addCallout('successfully performed an action.');

// Error callout
$syrus->addCallout('Uh oh, an error occured.', 'error');
~~~

The first argument is the contents of the message itself, and the second argument is the type of callout which defaults to "success".  The following callout types are supported:  success, error, warning, info.




