<?php

namespace lazy;

use ReflectionMethod;

class Router {
    private $controllerDir;

    public function __construct($controllerDir = 'controllers') {
        $this->controllerDir = $controllerDir;
    }

    public function getControllerDir() {
        return $this->controllerDir;
    }

    public function route($failCallback, $succeedCallback) {
        $request = self::parse_request();
        if (!$this->call_method($request['class'], $request['method'])) {
            $failCallback();
        } else {
            $succeedCallback();
        }
    }

    private function call_method($class, $method) {
        // include the file
        $file = $this->controllerDir."/$class.php";
        if (!file_exists($file)) return false;
        require_once $file;

        // check if the class exists
        if (!class_exists($class)) return false;
        $controller = new $class;

        // check if the method exists and is public
        if (!method_exists($controller, $method)) return false;
        $reflection = new ReflectionMethod($controller, $method);
        if (!$reflection->isPublic()) return false;

        // call the method and return true
        $controller->$method();
        return true;
    }

    private static function parse_request() {
        $requestUri = $_SERVER['REQUEST_URI'];
        $scriptName = $_SERVER['SCRIPT_NAME'];

        // put URI elements into arrays
        $requestComponents = explode('/', $requestUri);
        $scriptComponents = explode('/', $scriptName);

        // make the request path relative to the script name
        for ($i = 0; $i < sizeof($scriptComponents); $i++) {
            if ($requestComponents[$i] == $scriptComponents[$i]) {
                unset($requestComponents[$i]);
            }
        }

        // remove query string
        $request = implode('/', $requestComponents);
        $request = strtok($request, '?');

        // chop off '/' if request ends with one
        if (substr($request, -strlen('/')) == '/') $request = substr($request, 0, strlen($request)-1);
        $requestComponents = explode('/', $request);

        // re-index
        $requestComponents = array_values($requestComponents);

        $method = $requestComponents[sizeof($requestComponents)-1];
        unset($requestComponents[sizeof($requestComponents)-1]);
        $class = implode('\\', $requestComponents);
        return compact("method", "class");
    }
} 