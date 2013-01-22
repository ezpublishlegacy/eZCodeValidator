<?php

/**
 * JavaScript validation based on jshint (http://www.jshint.com/).
 */
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
                    preg_match('/.+: (?P<position>line [0-9]+, col [0-9]+, )?(?P<text>.+)/', $message, $matches);

                    $position = isset($matches['position']) ? $matches['position'] : '';
                    $text = isset($matches['text']) ? $matches['text'] : $message;
                    $output = $position . $text;

                    $this->log->error($output);
                    $valid = false;
                }
            }
        }

        return $valid;
    }
}