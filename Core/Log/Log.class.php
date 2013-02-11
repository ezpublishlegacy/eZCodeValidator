<?php

class Log implements IteratorAggregate {
    private $allowedTypes = array('error', 'warning', 'log', 'debug', 'success', 'failure');
    private $messages = array();
    private $logPrinterManager = null;

    public function __construct(LogPrinterManager $logPrinterManager) {
        $this->logPrinterManager = $logPrinterManager;
    }

    //insetead of multipe functions (error, warning, log, debug etc.)
    public function __call($name, $arguments) {
        if( in_array($name, $this->allowedTypes) ) {
            $this->addMessage($name, implode('; ', $arguments));
        } else {
            throw new Exception('Unknown message type: ' . $name . '. Use one of allowed types: ' . implode(', ', $this->allowedTypes));
        }
    }

    private function addMessage($type, $message) {
        $message = new LogMessage(array(
            'type' => $type,
            'caller' => $this->getCaller(),
            'message' => $message,
            'date' => time()
        ));

        $this->messages[] = $message;
        $this->logPrinterManager->newMessage($message);
    }

    private function getCaller() {
        $debug = debug_backtrace(false);
        $caller = isset($debug[4]) ? $debug[4] : array();

        return isset($caller['class']) ? $caller['class'] : '<unknown caller>';
    }

    public function getIterator() {
        return new ArrayIterator($this->messages);
    }
}