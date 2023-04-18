<?php
spl_autoload_register(function ($class) {
    // Define the base directory for the namespace prefix
    $baseDir = __DIR__ . '/classes/';

    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators, and add ".php" to the file name
    $file = $baseDir . str_replace('\\', '/', $class) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});
