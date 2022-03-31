<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

function logger($message, $level = 'info')
{
    // create a log channel
    $log = new Logger('sampleblog');
    $log->setTimezone(new \DateTimeZone('UTC'));
    $log->pushHandler(new StreamHandler(WRITEPATH . 'logs/' . LOGGER_FILE, Logger::WARNING));

    $level = strtolower($level);

    // add records to the log
    switch ($level) {
        case 'debug':
            $log->debug($message);
            break;

        case 'info':
            $log->info($message);
            break;

        case 'notice':
            $log->notice($message);
            break;

        case 'warning':
            $log->warning($message);
            break;

        case 'error':
            $log->error($message);
            break;

        case 'critical':
            $log->critical($message);
            break;

        case 'alert':
            $log->alert($message);
            break;

        case 'emergency':
            $log->emergency($message);
            break;

        default:
            $log->info($message);
            break;
    }
}
