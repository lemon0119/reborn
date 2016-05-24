<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class coreConfig {

    public static function start() {
        $MacAddInfo = new MacAddInfo('');
        $dateNow = date('Ymd');
        $datas = json_decode(file_get_contents(__DIR__ . "/data.txt"));
        if (count($datas) === 0) {
            return FALSE;
        } else if (md5($MacAddInfo->MacAddInfo('')) !== $datas[0]) {
            return FALSE;
        } else if ($dateNow > base_convert($datas[1], 8, 10)) {
            return FALSE;
        } else if ($dateNow < base_convert($datas[2], 16, 10)) {
            return FALSE;
        } else {
            $datas[2] = base_convert($dateNow, 10, 16);
            file_put_contents(__DIR__ . "/data.txt", json_encode($datas));
            return TRUE;
        }
    }

    public static function register($cdKey) {
        $cdKey = str_replace(" ", "", $cdKey);
        $cdKeyArray = explode("-", $cdKey);
        $dateNow = date('Ymd');
        $MAC = "";
        $LimitDate = "";
        if(isset($cdKeyArray[1])){
            $MAC = $cdKeyArray[0];
            $LimitDate = $cdKeyArray[1];
        }
        $data[0] = $MAC;
        $data[1] = $LimitDate;
        $data[2] = base_convert($dateNow, 10, 16);
        file_put_contents(__DIR__ . "/data.txt", json_encode($data));
    }

}
