<?php

abstract class LogPrinter {
    use Configurable;

    abstract public function printLog(Log $log);
    abstract public function printMessage(LogMessage $message);
}