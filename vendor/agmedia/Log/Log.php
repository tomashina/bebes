<?php
/**
 * User: fj.agmedia.hr
 * Date: 16/06/2017
 * Time: 14:36
 */

namespace Agmedia\Log;


class Log {

    /**
     * @param $message
     * @param string $filename
     */
    public static function write($message, $filename = 'error') {
        $handle = fopen(DIR_LOGS . $filename . '.log', 'a');
        fwrite($handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true) . "\n");
        fclose($handle);
    }
}