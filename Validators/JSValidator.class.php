<?php

//based on jshint
class JSValidator extends Validator {
    protected $defaultOptions = array(
        'jshint_path' => 'jshint'
        );

    public function check($filePath) {
        $valid = true;
        $jshintPath = $this->options['jshint_path'];
        $result = shell_exec($jshintPath . ' ' . $filePath . ' 2>&1');

        if( !empty($result) ) {
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