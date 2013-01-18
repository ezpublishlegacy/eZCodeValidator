<?php

class LogPrinterManager {
    private $log = null;
    private $printers = array();

    public function __construct(Log $log) {
        $this->log = $log;
        $this->printers = array();
    }

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