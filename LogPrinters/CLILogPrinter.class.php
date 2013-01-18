<?php

class CLILogPrinter extends  LogPrinter {
    protected $defaultOptions = array(
            'colors' => true,
            'show_caller' => true
        );

    public function printLog(Log $log) {
        foreach($log as $message) {
            $this->printMessage($message);
        }
    }

    public function printMessage(LogMessage $message) {
        if($this->options['colors']) {
            $colors = new Colors();
            $fgcolor = null;
            $bgcolor = null;

            switch($message->type) {
                case 'error': $fgcolor = 'light_red'; break;
                case 'warning': $fgcolor = 'yellow'; break;
                case 'debug': $fgcolor = 'cyan'; break;
                case 'success': $fgcolor = 'green'; break;
                case 'failure': $fgcolor = 'white'; $bgcolor = 'red'; break;
            }

            echo $colors->getColoredString($message->type, $fgcolor, $bgcolor);
        } else {
            echo $message->type;
        }

        echo ': ' . $message->message;

        if($this->options['show_caller']) {
            echo ' (caller: ' . $message->caller . ')';
        }

        echo PHP_EOL;
    }
}