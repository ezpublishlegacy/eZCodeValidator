<?php

//based on eZPublish template validator
class eZTemplateValidator extends Validator {
    protected $defaultOptions = array(
        'ezpublish_root' => '/var/www/ezpublish/'
        );

    public function check($filePath) {
        $projectBase = $this->options['ezpublish_root'];

        $currentDir = getcwd();

        //Setting timezone to make PHP happy (prevents warnings)
        date_default_timezone_set('Europe/Warsaw');

        //Loading eZPublish, setting paths
        ini_set('include_path', $projectBase);
        chdir($projectBase);

        require_once('autoload.php');
        require_once('lib/eztemplate/classes/eztemplate.php');

        $script = eZScript::instance( array( 'description' => ( "" ),
                                             'use-session' => false,
                                             'use-modules' => true,
                                             'use-extensions' => true ) );

        $script->startup();
        $script->initialize();

        $GLOBALS['eZDebugEnabled'] = true;

        eZDebug::instance()->setIsGlobalLogFileEnabled(false);

        $template = eZtemplate::factory();

        //catch all eZPublish mumbo jumbo
        ob_start();

        // ONLY PARSE
        $validationResult = $template->validateTemplateFile( $filePath );

        $script->shutdown();

        $validationOutput = ob_get_contents();
        ob_end_clean();        

        if( !$validationResult ) {
            $validationOutputArray = explode(PHP_EOL, $validationOutput);

            //filter empty lines and stats from output
            $validationOutputArray = array_filter($validationOutputArray, function($element){

                return strncmp($element, "Total runtime:", strlen("Total runtime:")) && 
                        strncmp($element, "Peak memory usage:", strlen("Peak memory usage:")) &&
                        strlen($element) > 0;
            });


            $this->log->error('Validation of "' . $filePath . '" failed: ' . PHP_EOL . implode(PHP_EOL, $validationOutputArray));
        }

        //back to normal execution
        ini_set('include_path', $currentDir);
        chdir($currentDir);

        return $validationResult;
    }
}