<?php

require('autoloader.php');
require('Core/Helpers/helper_functions.php');

//reading console arguments
$cliArguments = new CLIArguments($argv);

//reading configuration
$defaultConfigLocation = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.default.json';
$configPath = isset($cliArguments['config']) ? $cliArguments['config'][0] : $defaultConfigLocation;

$config = new Configuration();
$config->load($configPath);

//setting up Log and LogPrinters
$log = new Log();
$logPrinterManager = new LogPrinterManager($log);

foreach($config['log_printers'] as $logPrinterConfig) {
    $printerClass = $logPrinterConfig['name']->raw();

    $printer = new $printerClass();
    $printer->configure($logPrinterConfig['options']);

    $logPrinterManager->addPrinter($printer, $logPrinterConfig['message_types']->raw());
}

//building list of files to validate
$log->debug('BUILDING LIST OF FILES');
$files = array();

if(isset($cliArguments['files'])) {
    $files = $cliArguments['files'];
}

if(isset($cliArguments['dirs'])) {
    $recursive = isset($cliArguments['recursive']);

    foreach($cliArguments['dirs'] as $dir) {
        $files = array_merge($files, allFilesFromDir($dir, $recursive));
    }
}

//validating
$log->debug('RUNNING VALIDATORS');

$failures = 0;
foreach($files as $file) {

    foreach($config['validators'] as $validatorConfig) {
        $extInclude = $validatorConfig['extensions.whitelist']->raw();
        $extExclude = $validatorConfig['extensions.blacklist']->raw();

        if(endsWithAnyOf($file, $extInclude) && !endsWithAnyOf($file, $extExclude)) {
            $validatorName = $validatorConfig['name']->raw();
            $log->debug('Running "' . $validatorName . '" validator on file '. $file);

            $validator = new $validatorName($log);
            $validator->configure($validatorConfig['options']);

            if($validator->check($file)) {
                $log->success($file . ' passed `' . $validatorName . '` validation.');
            } else {
                $log->failure($file . ' failed `' . $validatorName . '` validation.');
                $failures++;
            }
        }

    }
}

$log->debug('SCRIPT FINISHED with ' . $failures . ' failures.');

//we use number of failures as an exit status
exit($failures);