<?php

require_once 'src/lazyrouter/Router.php';

use lazyrouter\Router;

$router = new Router();
$router->route(function() {
    http_response_code(404);
    die();
}, function() {});
