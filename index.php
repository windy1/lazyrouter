<?php

require_once 'src/lazyrouter/Router.php';

use lazyrouter\Router;

$router = new Router();

$failCallback = function() {
    http_response_code(404);
    die();
};

$router->route($failCallback, function() {});
