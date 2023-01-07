
# Utilizing RPC Calls

Syrus has built-in support for [Cluster](https://github.com/apexpl/cluster) allowing an optional RPC call to be made for every template rendered, providing built-in support for both, horizontal scaling and extensibility by allowing additional packages / extensions to affect change on templates being rendered.


## Enabling RPC

Within the container definitions file (default ~/config/container.php), there are two items:

Variable | Type | Description
------------- |------------- |------------- 
`syrus.use_cluster` | boolean | Whether or not Cluster support is enabled.  If set to true, Syrus will dispatch an RPC call for every template rendered.
`syrus.rpc_message_request` | MessageRequestInterface | Location of the `MessageRequestInterface` class to instantiate as the RPC message to dispatch.  This defaults to the default location within Cluster, but may be different if you wrote your own implementation of the class.  See the [Message Requests](https://github.com/apexpl/cluster/blob/master/docs/message_requests.md) page within the Cluster documentation for details.


#### Routing Key

If enabled, every template rendered will dispatch an RPC call to the routing key `syrus.parse.TEMPLATE` where `TEMPLATE` is the template file being parsed without file extension, in lowercase with forward slashes replaced by underscores.

For example, if parsing the template file products/phone.html, an RPC call would be made to the routing key:  `syrus.parse.products_phone`.  This means within Cluster you need a route that handles the `syrus.parse.*` routing key, and within the consuming PHP class it would call the method `products_phone()`.


## Accepting RPC Calls

First, within the consumer instance(s) running cluster you must specify Syrus as the front-end handler.  Open the container file within Cluster (defaults to ~/config/container.php), look for the `FeHandlerInterface::class` item, and change the line to:

~~~php
FeHandlerInterface::class => Apex\Cluster\FeHandlers\Syrus::class,
~~~

You must also add a route to Cluster to accept incoming messages to the `syrus.parse.*` routing key.  Please review the [Cluster documentation](https://github.com/apexpl/cluster) for full details, but for example, you may add a route via PHP with:

~~~php
use APex\Cluster\Cluster;

$cluster = new cluster('app1');
$cluster->addRoute('syrus.parse.*', \MyApp\Templates\Rpc::class);
~~~

Alternatively, if using YAML based configuration you can add a route to the routes.yml file such as:

~~~
routes:

  syrus_templates:
    type: rpc
    instances: all
    routing_keys:
      syrus.parse: MyApp\Templates\Rpc
~~~

That's it, and all RPC calls dispatched from Syrus will now be routed to the `MyApp\Templates\Rpc` class of the consumer instances where the method corresponding to the template file being parsed will be called (eg. parsing the file products/phone.html will call the method `products_phone()`).


#### Parameter Based Routing

Cluster's parameter based routing may be useful for this implementation as it allows only RPC calls that meet specific criteria to be accepted.  For example, within the YAML routes file:

~~~
routes:

  login_form_posted:
    type: rpc
    params:
      method: "== POST"
      post.submit: "== user_login"
    routing_keys:
      syrus.parse.login: MyApp\Templates\Rpc
~~~

The above will only accept RPC calls when the user is POSTing to the /login URI and the value of the "submit" form field is "user_login".  The RPC calls will be routed to the `MyApp\Templates\Rpc::login()` method.  Again, if you are unfamiliar with adding routes into Cluster, please refer to the [Router Overview](https://github.com/apexpl/cluster/blob/master/docs/router.md) page within the Cluster documentation.


## Processing RPC Calls

Using the above example, when the products/phone.html template is parsed, the `\MyApp\Templates\Rpc::products_phone()` method on the consumer instance will be called, for example:

~~~
namespace MyApp\Templates;

use Apex\Cluster\Interfaces\{MessageRequestInterface, FeHandlerInterface};

class Rpc
{

    /**
     * products/phone.html page
     */
    public function products_phone(MessageRequestinterface $msg, FeHandlerInterface $syrus)
    {

        // Get request information
        $req = $msg->getRequest();

            // Assign variables
        $syrus->assign('myvar', 'some_value');
        $syrus->addBlock('users', ['username' => 'jsmith', 'name' => 'John Smith']);

        // Add callout
        $syrus->addCallout('Successfully performed an action', 'success');

        // Change template file being rendered
        $syrus->setTemplateFile(verify_account.html');
    }
}
~~~

The `$syrus` object passed is a front-end handler interface, and supports all methods listed on the [Variables, Blocks and Callouts](variables.md) page of the documentation, allowing you to assign variables and blocks which will reflect on the rendered template within the front-end instance.  You may also call the [setTemplateFile()](render.md#set_template) method on the front-end handler.




