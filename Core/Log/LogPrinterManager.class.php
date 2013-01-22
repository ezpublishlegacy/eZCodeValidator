<?php

/**
 * Manages multipe log printers. Hooks printers to the log.
 */
class LogPrinterManager {
    private $log = null;
    private $printers = array();

    public function __construct(Log $log) {
        $this->log = $log;
        $this->printers = array();
    }

    /**
     * Makes sure that new messages from a Log go to the printer.
     * @param LogPrinter $printer
     * @param array      $messageTypes list of messages that given printer accepts
     */
    public function addPrinter(LogPrinter $printer, $messageTypes = array()) {
        $this->printers[] = $printer;

        //calls logPrinter whenever new message is registered by Log
        $this->log->onMessage(function($message) use($printer, $messageTypes){
            if( in_array($message->type, $messageTypes) ) {
                $printer->printMessage($message);
            }
        });
    }

}