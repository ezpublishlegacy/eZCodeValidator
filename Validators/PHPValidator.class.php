<?php

/**
 * PHP validation with PHP build in linter (php -l).
 */
class PHPValidator extends Validator {
    protected $defaultOptions = array(
        'php_path' => 'php'
        );

    public function check($filePath) {
        $valid = true;
        $phpPath = $this->options['php_path'];
        $result = shell_exec($phpPath . ' -l ' . $filePath . ' 2>&1');

        if( !preg_match('/^No syntax errors detected in /', trim($result)) ) {
            $messages = explode(PHP_EOL, $result);

            foreach($messages as $message) {
                $message = trim($message);

                if(!empty($message)) {
                    $this->log->error($message);
                    $valid = false;
                }
            }
        }

        return $valid;
    }
}