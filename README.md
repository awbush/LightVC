
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

- Quickstart Guide
- Configuration
  - Routes
  - Environments
  - Web Server Config
- Controllers
- Views
  - Elements
  - Layouts
- Errors

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
