<?php

namespace lazyrouter;

use ReflectionMethod;

/**
 * Class for routing requests to their appropriate class methods. Classes should be defined in the specified
 * controller directory and should have their file name named the same as the class.
 *
 * For example, a request such as 'hello/world' would call a method called 'world()' in a class called 'hello' in the
 * controller directory. The method will be called if and only if the method is both public and not static.
 *
 * @author Walker Crouse
 * @example ../../index.php
 * @package lazy
 */
class Router {
    private $controllerDir;

    /**
     * Creates a new Router with the specified directory for containing the controllers.
     *
     * @param string $controllerDir container for controllers
     */
    public function __construct($controllerDir = 'controllers') {
        $this->controllerDir = $controllerDir;
    }

    /**
     * Returns the containing directory for controllers.
     *
     * @return string controller directory
     */
    public function getControllerDir() {
        return $this->controllerDir;
    }

    /**
     * Routes the current $_SERVER['REQUEST_URI'] to its appropriate method call.
     *
     * @param $failCallback callback for if the current REQUEST_URI could not be routed
     * @param $succeedCallback callback for after the method was called
     * @param $requestUri string optional request uri to parse, if this is not specified it will be grabbed from the
     *        server.
     */
    public function route(callable $failCallback, callable $succeedCallback, $requestUri = null) {
        $request = self::parse_request();
        if (!$this->call_method($request['class'], $request['method'])) {
            $failCallback();
        } else {
            $succeedCallback();
        }
    }

    private function call_method($class, $method) {
        // include the file
        $name = explode('\\', $class);
        $base = $name[sizeof($name)-1];
        $file = $this->controllerDir."/$base.php";
        if (!file_exists($file)) return false;
        require_once $file;

        // check if the class exists
        if (!class_exists($class)) return false;
        $controller = new $class;

        // check if the method exists and is public
        if (!method_exists($controller, $method)) return false;
        $reflection = new ReflectionMethod($controller, $method);
        if (!$reflection->isPublic() || $reflection->isStatic()) return false;

        // call the method and return true
        $controller->$method();
        return true;
    }

    private static function parse_request($requestUri = null) {
        if ($requestUri == null) $requestUri = $_SERVER['REQUEST_URI'];
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