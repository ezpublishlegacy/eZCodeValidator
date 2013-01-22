<?php

function autoloader($class) {
    $validFolders = array(
        'Core',
        'Core/Helpers',
        'Core/Log',
        'LogPrinters',
        'Validators'
        );

    foreach($validFolders as $folder) {
        $file = dirname(__FILE__) . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $class . '.class.php';

        if(file_exists($file) && is_readable($file)) {
            include($file);
            return;
        }
    }
}

spl_autoload_register('autoloader');