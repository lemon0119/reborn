<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class AnalysisTool{
    
    //统计瞬时速度 
    //@param $setTime:设置瞬时速度统计的时间区间
    //@param $contentlength：区间内输入的字符长度，需要前端js计算
    //@return $momentSpeed: 获取瞬时速度  字/分钟
    public static function getMomentSpeed($setTime,$contentlength){
        $momentSpeed = ($contentlength/$setTime)*60;
        return $momentSpeed;
    }
    
    //统计平均速度 
    //@param $startTime:设置平均速度统计的开始时间
    //@param $content：内容
    //@return $momentSpeed: 获取平均速度 字/分钟
    public static function getAverageSpeed($startTime,$content){
        $nowTime  = time();
        $goneTime = $nowTime-$startTime;
        if($goneTime>0){
            $contentLength = strlen($content);
            $averageSpead  = ($contentLength/$goneTime)*60;
            return $averageSpead;
        }else{
            $averageSpead = 0;
            return $averageSpead;
        }
        
    }

}
