<?php

namespace foo;

/**
 * Namespaced classes are also permitted. This class would be accessed via the request 'foo/bar'.
 *
 * @author Walker Crouse
 * @package foo
 */
class bar {
    /**
     * This method would be called via 'foo/bar/baz'.
     */
    public function baz() {
        echo 'qux';
    }
} 