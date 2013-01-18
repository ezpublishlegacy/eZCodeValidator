<?php

class FileLogPrinter extends  LogPrinter {
    protected $defaultOptions = array(
            'log_dir' => '/tmp/',
            'file_prefix' => 'ezcv',
            'override' => true,
            'show_caller' => true
        );
    private $logFileHandle = null;

    public function printLog(Log $log) {
        foreach($log as $message) {
            $this->printMessage($message);
        }
    }

    public function printMessage(LogMessage $message) {
        $handle = $this->getLogFileHandle();

        $line = $message->type . ': ' . $message->message;

        if($this->options['show_caller']) {
            $line .= ' (caller: ' . $message->caller . ')';
        }

        fwrite($handle, $line . PHP_EOL);
    }

    private function getLogFileHandle() {
        if(!$this->logFileHandle) {
            $logFilePath = $this->options['log_dir'] . $this->getLogFileName();

            if(($this->logFileHandle = fopen($logFilePath, 'w')) === FALSE) {
                throw new Exception('Can\'t open log file: ' . $logFilePath);
            }
        }

        return $this->logFileHandle;
    }

    private function getLogFileName() {
        $fileName = $this->options['file_prefix'];

        if(!$this->options['override']) {
            $fileName .= '_' . date('d-m-y_h-i-s');
        }

        $fileName .= '.log';

        return $fileName;
    }

    public function __destruct() {
        if($this->logFileHandle) {
            fclose($this->logFileHandle);
        }
    }
}