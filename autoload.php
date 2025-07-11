<?php
spl_autoload_register(function ($className) {
    $file = __DIR__ . '/EasyHTTP/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
spl_autoload_register();