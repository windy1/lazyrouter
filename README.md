# lazyrouter
Translate requests into method calls.

## Composer

```json
{
    "require": {
        "windy/lazyrouter": "dev-master"
    }
}
```

## Example

**Note: Make sure you redirect all requests to a single index.php.**

*An example index.php*

```php
<?php
require_once 'Router.php';
use lazy\Router;

$router = new Router();
$router->route(function() {}, function() {});
```

*A request such as 'hello/world' would be interpreted as.*

```php
require_once 'controllers/hello.php';
$controller = new hello();
$controller->world();
```

*Namespaces are permitted as well. Something like 'foo/bar/baz' would be interpreted as.*

```php
require_once 'controllers/bar.php';
$controller = new foo\bar();
$controller->baz();
```