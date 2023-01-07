
# Auto-Routing

Optional auto-routing is available, which will automatically display the appropriate template that corresponds to the URI being viewed.  Within the container definitions file (defaults to ~/config/container.php) there is a `syrus.enable_autorouting` boolean item available, which defaults to true.  If set to true, you can utilize auto-routing by simply not passing a template file to the `Syrus::render()` method, for example:

~~~php
use Apex\Syrus\Syrus;

// Load Syrus
$syrus= new Syrus();

// Assign variables
$syrus->assign('', $vars);

// Render template
echo $syrus->render();
~~~

That's it.  Syrus will display the appropriate template based on the URI being viewed within the web browser.  For example, if viewing http://domain.com/services, the `services.html` template will be displayed.  If visiting the URL http://domain.com/products/phones, the `products/phones.html` template will be displayed.



