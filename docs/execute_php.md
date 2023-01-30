
# Executing Per Template PHP Files

Optionally, you may have a per-template PHP class executed each time the template is rendered, providing a bridge between the templates and actual back-end application.  Within the container file (defaults to ~/config/container.php) there is a `syrus.php_namespace` item, which defaults to `Apex\Syrus\Views\Php` namespace which is mapped to the ~/views/php/ directory.

With every template rendered, Syrus will check this namespace (if defined) for the PHP class corresponding to the template being rendered.  For example, if rendering the `products/phone.html` template, Syrus will look for a `Apex\Syrus\Views\Php\products\phone` class, or in other words, file located at ~/views/php/products/phone.php.

## Methods

Upon the view being rendered, Apex will load the PHP class and initially look for a method named the same as the HTTP method of the request (eg. post(), get()).  If one exists, that method will be executed.  On top of this, the PHP class will also be checked for a `render()` method.  If one exists, it will be executed regardless of what the HTTP method of the request is.

Please note, all methods within the PHP class are optional, as is the entire PHP class itself.


### Dependency Injection

Aside from the standard constructor and attribute injection, all methods within the view PHP classes also support method based injection.  This means you may place any depencies desired within the method parameters, and they will be automatically injected as necessary.  For example:

~~~php
namespace Apex\Syrus\Views\Php\products;

use Apex\Svc\{App, Db};
use Apex\Syrus\Syrus
use App\MyPackage\AbcController;

/**
 * Demo page
 */
class phone
{

    /**
     * GET HTTP method
     */
    public function get(Syrus $syrus, App $app, Db $db, AbcController $abc):void
    {

        // Do anything necessary, assign some variables / blocks if desired
        $syrus->assign('key', 'value');

    }

}
~~~

As you can see, the above `get()` method takes four parameters, all of which will be injected into as necessary.




