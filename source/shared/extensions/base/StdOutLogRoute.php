<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Yurko Fedoriv
 * Date: 2/16/12
 * Time: 2:53 PM
 */
namespace base;
class StdOutLogRoute extends \CLogRoute
{
    /**
     * Processes log messages and sends them to specific destination.
     * Derived child classes must implement this method.
     *
     * @param array $logs list of messages.  Each array elements represents one message
     *                    with the following structure:
     *                    array(
     *   [0] => message (string)
     *   [1] => level (string)
     *   [2] => category (string)
     *   [3] => timestamp (float, obtained by microtime(true));
     */
    protected function processLogs($logs) {
        foreach($logs as $log){
            echo $this->formatLogMessage($log[0], $log[1], $log[2], $log[3]);
        }
    }

}
