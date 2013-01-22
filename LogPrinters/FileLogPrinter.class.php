<?php

/**
 * Outputs LogMessages to a file.
 */
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

    /**
     * Prints single LogMessage to a file.
     */
    public function printMessage(LogMessage $message) {
        $handle = $this->getLogFileHandle();

        $line = $message->type . ': ' . $message->message;

        if($this->options['show_caller']) {
            $line .= ' (caller: ' . $message->caller . ')';
        }

        fwrite($handle, $line . PHP_EOL);
    }

    /**
     * Creates a new log file.
     * @return file handle
     */
    private function getLogFileHandle() {
        if(!$this->logFileHandle) {
            $logFilePath = $this->options['log_dir'] . $this->getLogFileName();

            if(($this->logFileHandle = fopen($logFilePath, 'w')) === FALSE) {
                throw new Exception('Can\'t open log file: ' . $logFilePath);
            }
        }

        return $this->logFileHandle;
    }

    /**
     * Generates a log file name.
     * @return string file name
     */
    private function getLogFileName() {
        $fileName = $this->options['file_prefix'];

        //if 'override' is not set, create a unique file name
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