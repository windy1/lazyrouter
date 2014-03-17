<?php

/**
 * Example class for routing.
 *
 * @author Walker Crouse
 */
class hello {
    /**
     * Called when 'hello/world' is requested.
     */
    public function world() {
        echo 'hello, world';
    }

    /**
     * Notice how this function will never be called because it is private.
     */
    private function foo() {
        echo 'unreachable';
    }

    /**
     * This function will also never be called because it is static.
     */
    public static function bar() {
        echo 'unreachable';
    }
}