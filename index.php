<?php

require_once 'src/Router.php';

use lazy\Router;

$router = new Router();

$failCallback = function() {
    http_response_code(404);
    die();
};

$router->route($failCallback, function() {});