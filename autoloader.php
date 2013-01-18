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
		$file = $folder . '/' . $class . '.class.php';

		if(file_exists($file) && is_readable($file)) {
			include($file);
			return;
		}
	}
}

spl_autoload_register('autoloader');