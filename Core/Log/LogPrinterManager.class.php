<?php

/**
 * Manages multipe log printers. Hooks printers to the log.
 */
class LogPrinterManager {
    private $configuration = null;

    public function __construct() {
        $this->configuration = array();
    }

    /**
     * Collects information about printer.
     * @param LogPrinter $printer
     * @param array      $messageTypes list of messages that given printer accepts
     */
    public function addPrinter(LogPrinter $printer, $messageTypes = array()) {
        $this->configuration[] = array(
            'printer' => $printer,
            'messageTypes' => $messageTypes
            );
    }

    /**
     * Calls logPrinter whenever new message is registered by Log.
     * @param  LogMessage $message message to be printed by printers
     */
    public function newMessage($message) {
        foreach($this->configuration as $config) {
            $printer = $config['printer'];
            $messageTypes = $config['messageTypes'];

            if( in_array($message->type, $messageTypes) ) {
                $printer->printMessage($message);
            }
        }
    }

}