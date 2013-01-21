<?php

//based on csslint
class CSSValidator extends Validator {
    protected $defaultOptions = array(
        'csslint_path' => 'csslint',
        'errors' => array(),
        'warnings' => array(),
        'ignore' => array()
        );

    public function check($filePath) {
        $valid = true;
        $csslintPath = $this->options['csslint_path'];

        $options = array("--format=compact");

        $listsOfRules = array('errors', 'warnings', 'igonre');

        foreach($listsOfRules as $listName) {
            if(!empty($this->options[$listName])) {
                $options[] = '--' . $listName . '=' . implode(',', $this->options[$listName]);
            }
        }

        $result = shell_exec($csslintPath . ' ' . implode(' ', $options) . ' ' . $filePath . ' 2>&1');

        if( !empty($result) ) {
            $messages = explode(PHP_EOL, $result);

            foreach($messages as $message) {
                $message = trim($message);

                if(!empty($message)) {
                    preg_match('/.+: (?P<position>line [0-9]+, col [0-9]+, )?(?P<type>Warning|Error) - (?P<text>.+)/', $message, $matches);

                    $messageType = isset($matches['type']) ? $matches['type'] : null;
                    $position = isset($matches['position']) ? $matches['position'] : '';
                    $text = isset($matches['text']) ? $matches['text'] : $message;
                    $output = $position . $text;

                    if($messageType == 'Error') {
                        $this->log->error($output);
                        $valid = false;
                    } else if($messageType == 'Warning') {
                        $this->log->warning($output);
                    } else {
                        $this->log->log($output);
                    }
                }
            }
        }

        return $valid;
    }
}