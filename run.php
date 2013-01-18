<?php

require('autoloader.php');
require('Core/Helpers/helper_functions.php');

//reading configuration
$config = new Configuration();
$config->load('config.json');

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
$dir = '/var/www/nsc/git/extension/eff/';
$files = allFilesFromDir($dir);

//
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
                $log->success($file . ' passed `' . $validatorName . '` test.');
            } else {
                $log->failure($file . ' failed `' . $validatorName . '` test.');
                $failures++;
            }
        }

    }
}

$log->debug('SCRIPT FINISHED with ' . $failures . ' failures.');

//we use number of failures as an exit status
exit($failures);