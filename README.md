
# About LightVC

LightVC is a lightweight model-view-controller (MVC) framework without the model. This decoupling allows any model or object relation mapping (ORM) tool to be used, including none at all if one is not needed.

LightVC is comparable to, although unlike, CakePHP, Code Igniter, symfony, Solar, and Zend Framework to name a few. It's major difference is that it does not try to be a full "Web framework" and instead tries to solve the need for an MVC that isn't coupled to other tools.

As such, LightVC does not couple itself to any non-VC related classes either, such as those for managing sessions, form helpers, and so on. This promotes code reuse by allowing existing code for such tasks be used.

LightVC has been in use in production since early 2007, and is now being released to the public under the FreeBSD license.

## LightVC Features
- Lightweight single-file view-controller framework.
- Allows usage of any model or ORM.
- Promotes code re-use.
- Highly configurable.
- Fast. Benchmarks coming soon.
- PHP5 Strict.


## LightVC Documentation

- [Quickstart Guide](#quickstart-guide)
- [Configuration](#configuration)
  - [Routes](#routes)
  - [Web Server Config](#web-server-configuration-examples)
- [Controllers](#controllers)
- [Views](#views-and-elements)
  - [Elements](#views-and-elements)
  - [Layouts](#layouts)
- [Errors](#errors)

## Quickstart Guide

### Installation

1. Make sure your environment meets the requirements (PHP5).
2. Download latest LightVC release.
3. Unzip/extract the download.
4. Point the web server's document root to the webroot folder. Need help with this step? Check out the web server config examples.
5. Start Building

Add a controller and action (`controllers/test_lightvc.php`):

```
<?php
class TestLightvcController extends AppController {
    public function actionTestAction($one = null, $two = null) {
        if (is_null($one)) {
            $one = 'NULL';
        }
        if (is_null($two)) {
            $two = 'NULL';
        }
        $this->setVar('one', $one);
        $this->setVar('two', $two);
    }
}
?>
```

Add a view (`views/test_lightvc/test_action.php`):

```
<h1>Test LightVC</h1>
<p>One = "<?php echo htmlentities($one) ?>" and two = "<?php echo htmlentities($two) ?>."</p>
Visit /test_lightvc/test_action/ on your server.
```

## Configuration

Most things in LightVC are customizable. For example:

Paths for controllers, views, elements, and layouts.
File extensions.
Whether or not to pass parameters to controller actions as function arguments or an array.
Default controller/actions to use.
Customizing LightVC is easy, and is done through the Lvc_Config static methods.

### Customizing Paths

Many paths can be added for each part of the application by calling addControllerPath(), addControllerViewPath(), addLayoutViewPath(), and addElementViewPath().

For example, the following could be placed in an application's app/config/application.php file to setup a single directory for each piece:

```
<?php
define('APP_PATH', dirname(dirname(__FILE__)));
Lvc_Config::addControllerPath(APP_PATH . 'controllers/');
Lvc_Config::addControllerViewPath(APP_PATH . 'views/');
Lvc_Config::addLayoutViewPath(APP_PATH . 'views/layouts/');
Lvc_Config::addElementViewPath(APP_PATH . 'views/elements/');
?>
```
### Customizing File Suffixes/Extensions

The default suffix for all items is .php. This can be changed as shown in the following example:

```
<?php
Lvc_Config::setControllerSuffix('_controller.php');
Lvc_Config::setControllerViewSuffix('.thml');
Lvc_Config::setLayoutViewSuffix('_layout.thml');
Lvc_Config::setElementViewSuffix('_element.thml');
?>
```

### Customizing Action Parameter Passing

This option is controlled through setSendActionParamsAsArray(), and defaults to false. To change it, use:

```
<?php
Lvc_Config::setSendActionParamsAsArray(true);
?>
```
When set to true, controller actions need to accept only one parameter, like so:
```
<?php
class ExampleController extends AppController {
    public function actionTest($params) {
    }
}
?>
```
The `$params` will contain an array of the arguments passed to the controller.

If left off (recommended if mod_rewrite is available), controller actions should be coded like so:

```
<?php
class ExampleController extends AppController {
    public function actionTest($paramOne = null, $paramTwo = null /*, and so on... */) {
    }
}
?>
```

### Customizing Default Controller/Action

If all routes fail while processing a request, LightVC will try one last time using the defaults specified in Lvc_Config. These can be customized like so:

```
<?php
// The controller name to use if no controller name can be gathered from the
// request.
Lvc_Config::setDefaultControllerName('page');

// The action name to call on the defaultControllerName if no controller name can
// be gathered from the request.
Lvc_Config::setDefaultControllerActionName('view');

// The action params to use when calling defaultControllerActionName if no
// controller name can be gathered from the request.
Lvc_Config::setDefaultControllerActionParams(array('page_name' => 'home'));
?>
```

It's possible that the route was able to map a controller to use but not the action. The default action to invoke can be specified like so:

```
<?php
// The default action name to call on a controller if the controller name was
// gathered from the request, but the action name couldn't be.
Lvc_Config::setDefaultActionName('index');
?>
```
### Use an AppController

This requires no config calls to LightVC. Just write an AppController class that extends Lvc_PageController:

```
<?php
class AppController extends Lvc_PageController {
    protected $layout = 'default';
}
?>
```
Then make all controllers extend `AppController` rather than `Lvc_PageController`.

### Use an AppView

To add custom functionality to the View layer, write an AppView class that extends Lvc_View:

```
<?php
class AppView extends Lvc_View {
    public function requireCss($cssFile) {
        $this->controller->requireCss($cssFile);
    }
}
?>
```

Then tell LightVC about it:

```
<?php
Lvc_Config::setViewClassName('AppView');
?>
```

### Customizing Layout Variable Names

The only layout variable hard-coded into LightVC is layoutContent, which contains the output from the controller's view. Even this can be changed, as in the following example:

```
<?php
Lvc_Config::setLayoutContentVarName('content_for_layout');
?>
```

## Routes

LightVC has extremely powerful routing options and the ability to take customized routers. LightVC comes with Lvc_GetRouter, Lvc_RewriteRouter, and Lvc_RegexRewriteRouter.

If mod_rewrite is available, the regex rewrite router is likely to be all that's needed.

The `webroot/index.php` file might contain:

```
$fc = new Lvc_FrontController();
$fc->addRouter(new Lvc_RegexRewriteRouter($regexRoutes));
$fc->processRequest(new Lvc_HttpRequest());
```

All that's missing is the contents of `$regexRoutes`. Here are the routes the LightVC website uses:

```
<?php

// Format of regex => parseInfo
$regexRoutes = array(

    // Map nothing to the home page.
    '#^$#' => array(
        'controller' => 'page',
        'action' => 'view',
        'action_params' => array(
            'page_name' => 'home',
        ),
    ),

    // Allow direct access to all pages via a "/page/page_name" URL.
    '#^page/(.*)$#' => array(
        'controller' => 'page',
        'action' => 'view',
        'action_params' => array(
            'page_name' => 1,
        ),
    ),

    // Allow direct access to all docs via a "/docs/doc_name" URL.
    '#^docs/(.*)$#' => array(
        'controller' => 'docs',
        'action' => 'view',
        'action_params' => array(
            'doc_name' => 1,
        ),
    ),

    // Map controler/action/params
    '#^([^/]+)/([^/]+)/?(.*)$#' => array(
        'controller' => 1,
        'action' => 2,
        'additional_params' => 3,
    ),

    // Map controllers to a default action (not needed if you use the
    // Lvc_Config static setters for default controller name, action
    // name, and action params.)
    '#^([^/]+)/?$#' => array(
        'controller' => 1,
        'action' => 'index',
    ),

);

?>
```
As of version 1.0.4, the Lvc_RegexRewriteRouter allows a redirect parameter instead of the controller/action parameters. This allows a route to redirect to another page instead of loading up a controller/action.

### Static Redirect Examples:
```
'#^test/?$#' => array(
    'redirect' => '/some/other/page/'
),
'#^test2/?$#' => array(
    'redirect' => 'http://lightvc.org/'
),
```

### Dynamic Redirect Example:

```
'#^test/([^/]*)/?$#' => array(
    'redirect' => '/faq/$1'
),
```
Basically, the value of the redirect option is used as the replacement variable for PHP's preg_replace function. That is how the dynamic example works.

The above examples should be enough to explain how to add routes for the Lvc_RegexRewriteRouter, but to be clear:

- When specifying the parse info, use a string to use a specific value, or an integer to use the value from the regex match.
- controller specifies the controller name to use.
- action specifies the action name to invoke.
- action_params specifies an array of parameter names and values to use.
- Parameter names do not have to be included; They are only useful if Lvc_Config::setSendActionParamsAsArray(true); is used.
- additional_params should be an integer specifying which regex match to use for parsing additional parameters out of the URL.
- Instead of any of the above, redirect can be specified to have the browser redirected to another page.

Those unfamiliar with regex might want to look at the pcre pattern syntax.

## Web Server Configuration Examples

### Apache (1 & 2) Example

To enable the pretty URLs, the .htaccess file inside the webroot directory must be read by Apache. You need only set AllowOverride All in the Apache configuration file like so:

```
<Directory "/path/to/lightvc-skeleton-app/webroot">
    AllowOverride All
</Directory>
```
I prefer to setup port-based virtual hosts on my development machine. The following example sets up a port-based virtual host, with `.htacess` support, for port 8000. This means if the site is setup on the local machine it can be accessed by visiting http://localhost:8000/.

```
Listen 8000
NameVirtualHost *:8000
<VirtualHost *:8000>
    DocumentRoot "/path/to/lightvc-skeleton-app/webroot"
</VirtualHost>
<Directory "/path/to/lightvc-skeleton-app/webroot">
    Options Indexes FollowSymLinks
    # Allow .htaccess files
    AllowOverride All
    Order allow,deny
    Allow from all
</Directory>
```
References
- [Apache 2.2 AllowOverride Documentation](http://httpd.apache.org/docs/2.2/mod/core.html#allowoverride)

### Lighttpd Example

Lighttpd will not read the .htacess file packaged with LightVC's skeleton app, so you'll have to add to the configuration file. The tricky part is that you'll have to manually specify which files and directories are not supposed to be parsed by LightVC. This is different from the Apache/.htaccess solution which automatically loads files that exist in the file system, otherwise control is passed to LightVC.

The following example shows how to ensure common files and folders like favicon.ico, robots.txt, images, css, and js work correctly in Lighttpd.

```
# /etc/lighttpd/lighttpd.conf
url.rewrite-once = (
    "^/(css|images|files|js)/(.*)$" => "/$1/$2",
    "^/(robots\.txt|favicon\.ico)$" => "/$1",
    "^/([^?]*)(\?(.*))?$" => "/index.php?url=$1&$3"
)
```

References
- [Lighttpd mod_rewrite Documentation](http://trac.lighttpd.net/trac/wiki/Docs%3AModRewrite)

## Controllers

- Access Get/Post Data
- Manually invoke (or disable) a view
- Changing/Disabling the Layout
- Passing Variables to the View
- Setting Layout Variables
- Redirecting
- Requesting a sub action
- Execute code before/after an action

### Access Get/Post Data

In the controller:

```
$exampleGet = $this->get['example'];
$examplePost = $this->post['example'];
```

There is also a postData attribute that is set to the contents of $_POST['data'] and $_FILES['data'], with some remapping of the $_FILES['data'] so that it matches what's in $_POST['data'].


Example usage of postData:

```
if (!empty($this->postData)) {
    // User submitted form
    $book = new Book();
    $book->setFields($this->postData['book']);
    if ($book->save()) {
        Messenger::addFlash('message', 'Book saved successfully.');
        $this->redirect('/book/view/' . $book->getBookId());
        exit();
    } else {
        $this->setVar('errors', $book->getValidationErrors());
    }
}
```
### Manually invoke (or disable) a view

The view corresponding to the current action is automatically invoked by default.

You can force a view to load at any time (thus disabling the automatic invocation):
```
$this->loadView($this->controllerName . '/custom_view');
```
You can also disable any view from loading:
```
$this->loadDefaultView = false;
```

### Changing/Disabling the Layout

You can change the layout in the controller at any time with:

`$this->setLayout('new_layout');`

You can disable it by setting it to null (the default):

`$this->setLayout(null);`

It probably makes most sense to specify a default layout in an AppController and then override it on an as-needed basis:

```
<?php
class AppController extends Lvc_Controller {
    protected $layout = 'default';
}
?>
```

### Passing Variables to the View

In the controller:

`$this->setVar('message', 'Weeeee!');`

In the view:

`<?php echo $message ?>`

You can also build an array of variables and set them en masse:

```
$data = array();
$data['error'] = '';
$data['message'] = 'Weeeeeeeeeeee!';
$data['userName'] = 'Nobody';
$this->setVars($data);
```

### Setting Layout Variables

In the controller:

`$this->setLayoutVar('layoutVarName', 'value');`

In the layout:

`<?php echo $layoutVarName ?>`

### Redirecting

##### In the controller:

```
$this->redirect($url);
exit(); // redirect does not exit automatically so that post script can be run.
```
### Requesting a sub action

##### Inside a controller action method:
```
$request = new Lvc_Request();
$request->setControllerName($this->controllerName);
$request->setControllerParams($this->params);
$request->setActionName('some_other_action');
$request->setActionParams($params); // Set optional params
$output = $this->getRequestOutput($request);
```
Or, you can pass the request attributes to the requestAction() method:
```
$output = $this->requestAction($actionName);
$output = $this->requestAction($actionName, $actionParams, $controllerName, $controllerParams);
```
### Execute code before/after an action

##### To execute code before an action, override the beforeAction() method:
```
protected function beforeAction() {
    parent::beforeAction(); // chain to parent
    $this->setLayoutVar('pageTitle', 'Default Title');
}
```
##### To execute code after an action, override the afterAction() method:
```
protected function afterAction() {
    parent::afterAction(); // chain to parent
    // do some stuff
}
```

### Views and Elements

##### Render an element (re-usable sub-view):

```
<?php $this->renderElement('foo'); ?>
```

##### Render an element with data:

```
<?php $this->renderElement('foo', array('varName' => 'value')); ?>
```
### Layouts

Layouts are special views that get wrapped around the controller’s view output. They don’t have to be used, but are the best way to make several pages use the same “layout.”

An example layout might contain:

```
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo htmlentities($pageTitle); ?></title>
</head>
<body>
	<?php echo $layoutContent; ?>
</body>
</html>
```
Where `$layoutContent` will be set to the output from a controller action’s view. 

##### Setting Layout Variables

In the view/element:

`$this->setLayoutVar('layoutVarName', 'value');`

In the layout:

`<?php echo $layoutVarName ?>`

## Errors

LightVC will throw an `Lvc_Exception` when it runs into trouble:

- The controller can not be found.
- The action can not be run.
- The controller view can not be found.
- The layout view can not be found.

The only error that does not result in a thrown exception is when an element can not be loaded. Instead, LightVC logs the error and continues execution.
