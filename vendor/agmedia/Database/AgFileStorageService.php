<?php
/**
 * Created by fj.agmedia.hr
 * Date: 01/07/2017
 * Time: 00:08
 */

namespace Agmedia\Database;


use Agmedia\Log\Log;

class AgFileStorageService
{


    public static function fetchPath($path, $data)
    {
        $data = json_decode($data, true);
        $arr = explode('.', str_replace(['/', ' '], '.', $path));
        $res = array();

        foreach ($data as $key => $value) {
            if ($key == $arr[0]) {
                foreach ($value as $k => $v) {
                    ($k == $arr[1]) ? $res = $v : $res = 'NO DATA';
                }
            } else {
                $res = 'NO DATA';
            }
        }

        if (empty($res)) { $res = 'NO DATA'; }

        return json_encode($res);
    }





    public static function structureData($path, $data)
    {
        if (empty($path[1])) {
            return array(
                $path[0] => $data
            );
        } else {
            return array(
                $path[0] => array(
                    $path[1] => $data
                )
            );
        }
    }
}