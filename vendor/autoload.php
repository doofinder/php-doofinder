<?php
spl_autoload_register(function ($class) {

    $prefix = 'Doofinder\\Api\\';
    $base_dir = __DIR__ . '/../src/';

    // not my namespace
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';


 if (file_exists($file)) {
        require $file;
    }

});

