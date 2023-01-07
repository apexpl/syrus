
# Render Templates

Syrus allows easy one-line rendering of both, full page templates and strings / blocks of HTML.

## `syrus.template_dir` Container item

Within the container definitions file (default ~/config/container.php) there is a `syrus.template_dir` item, which is where all your templates, themes, and optionally per-template PHP files are stored.  This directory should contain three sub-directories:

* /html/ 
* /themes/
* /php/ (optional)

All templates are stored within the /html/ sub-directory.  You may change the `syrus.template_dir` location to anything you wish, and it defaults to the ~/views/ directory of the Syrus installation.


## Render Templates

You may easily render full page templates with the `Syrus::render()` method, for example:

~~~php
use Apex\Syrus\Syrus;

$syrus = new Syrus(
    container_file: '/path/to/container.php'
);

## Render services.html
echo $syrus->render('services.html');
~~~

This will render the template file located at ~/views/html/services.html`.  Upon rendering a template, the following occurs:

1. If utilizing the built-in Cluster functionality, an RPC call will be dispatched.  See [Utilizing RPC Calls](rpc.md) page for details.
2. If utilizing the optional per-template PHP files, will look for and execute any PHP file for this template.  See [Executing Per Template PHP Files](execute_php.md) page for details.
3. Appropriate theme is determined, and any necessary page layout is applied.
4. Template code is parsed and returned.


## Rendering Strings / Blocks of HTML

Instead of rendering full template pages, you may render blocks of HTML code using the `Syrus::renderBlock()` method, for example:

~~~php
use Apex\Syrus\Syrus;


// Load Syrus
$syrus = new Syrus();
$html = 'large block of html code';

// Add necessary variables / blocks
$syrus->assign('', $variables);

// Render HTML
$html = $syrus->renderBlock($html);
~~~

Please note, when rendering blocks of HTML code, it will not dispatch the optional RPC call, execute a per-template PHP class, or apply any theme / layout.


<a name="set_template"></a>
## setTemplateFile()

During execution, you may set / change the template file that will be rendered at anytime with the `Syrus::setTemplateFile()` method.  This is useful if utilizing either per-template PHP classes, or RPC calls.  For example, you may need to force an unauthorized or account verification required template to be rendered instead of the requested template.

This method accepts two parameters:

Variable | Required | Type | Description
------------- |------------- |------------- |------------- 
`$template_file` | Yes | string | The template file to render once the `Syrus::render()` method is called.
`$is_locked` | No | bool | Defaults to false, but if set to true, the template file will be locked and future calls to the method will leave the template file unchanged.

For example, within the per-template PHP class for the /search.html template.

~~~php
namespace Apex\Syrus\Views\Php;

use Apex\Syrus\Syrus;

class search
{

    /**
     * Render
     */
    public function render(Syrus $syrus)
    {

        // Check if allowed to search...
        $can_search = false;

        // Set account upgrade required template
        if ($can_search !== true) { 
            $syrus->setTemplateFile('upgrade_account.html', true);
        }
    }
}
~~~

With the above code in place, instead of rendering the /search.html template, Syrus will render the /upgrade_account.html template.


