<?php

/**
 * Log printer template.
 */
abstract class LogPrinter extends Configurable {

    abstract public function printLog(Log $log);
    abstract public function printMessage(LogMessage $message);
}