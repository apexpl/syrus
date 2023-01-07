
# Executing Per Template PHP Files

Optionally, you may have a per-template PHP class executed each time the template is rendered helping provide cleanliness and organization, plus a bridge between the templates and actual back-end application.  Within the container file (defaults to ~/config/container.php) there is a `syrus.php_namespace` item, which defaults to `Apex\Syrus\Views\Php` namespace which is mapped to the ~/views/php/ directory.

With every template rendered, Syrus will check this namespace (if defined) for the PHP class corresponding to the template being rendered.  For example, if rendering the `products/phone.html` template, Syrus will look for a `Apex\Syrus\Views\Php\products\phone` class, or in other words, file located at ~/views/php/products/phone.php.

If the PHP class exists, it will be instantiated and the `render()` method will be called.  Here's an example class:

~~~php
use Apex\Syrus\Syrus

namespace Apex\Syrus\Views\Php\products;

class phone
{

    /**
     * Render
     */ 
    public function render(Syrus $syrus)
    {

        // Do anything necessary, assign some variables / blocks if desired
        $syrus->assign('key', 'value');
    }

}
~~~

This allows you to easily execute any PHP code specific to the template, and provides a bridge between the templates and the back-end application.  Please note, this class is instantiated and method called via the DI container, meaning it does also fully support any desired constructor / method / attribute injection.

For full information on the dependency injection container used, please see the [Apex Container](https://github.com/apexpl/container) package on Github.





