<?php

spl_autoload_register(function ($class_name) {

    $namespace = str_replace('\\', '/', $class_name);

    $dir = dirname(__FILE__) . "/src";
    $file = "$dir/$namespace.php";

    if (file_exists($file)) {
        require_once($file);
        return true;
    }

    $dir = dirname(__FILE__) . '/tests';
    $file = "$dir/$namespace.php";

    if (file_exists($file)) {
        require_once($file);
        return true;
    }

    return false;
});
