<?php
class Tool {
    public static $EXER_TYPE = [
        'listen',
        'look',
        'key',
        'choice',
        'filling',
        'question'
        ];
    public function alertInfo($info,$url){
       return  "<script type='text/javascript'>alert('$info');location.href='$url';</script>";
    }
    public static function printprobar($value){
        $sv = sprintf('%2.1f',$value * 100);
        $pro = '<div class="progress">';
        $bar = '<div class="bar" style="width:'.$sv.'%;">';
        $barend = '</div>';
        $proend = '</div>';
        $bw = "<font size=\"2\" color=\"#0d8fd1\">$sv%</font>";
        if($sv > 10)
            echo $pro.$bar.$sv.'%'.$barend.$proend;
        else
            echo $pro.$bar.$barend.$bw.$proend;
    }
    public static function jsLog($info) {
        return "<script>console.log('"+$info+"');</script>";
    }
    public static function arrayMerge($a1,$a2){
        foreach ($a2 as $key => $value) {
            $a1[$key] = $value;
        }
        return $a1;
    }

    public static function clength($str, $charset="utf-8")
    {
        /**
        * 可以统计中文字符串长度的函数
        * @param $str 要计算长度的字符串,一个中文算一个字符
        */
        $re['utf-8']="/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312']="/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']= "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']="/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        return count($match[0]);
    }
    
    public static function csubstr($str, $start=0, $length, $charset="utf-8")
    {
        /* *
        * 中文截取，支持gb2312,gbk,utf-8,big5
        * @param string $str 要截取的字串 
        * @param int $start 截取起始位置 
        * @param int $length 截取长度 
        * @param string $charset utf-8|gb2312|gbk|big5 编码 
        * @param $suffix 是否加尾缀 
        */
        if(function_exists("mb_substr"))
        {
            if(mb_strlen($str, $charset) <= $length) return $str;
            $slice = mb_substr($str, $start, $length, $charset);
         }else{
            $re['utf-8']="/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312']="/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk']= "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5']="/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            if(count($match[0]) <= $length) return $str;
            $slice = join("",array_slice($match[0], $start, $length));
         }
        return $slice;
    }
    
    public static function createID() {
        //7位系统时间，2位随机数，1位校验和
	$tm = time();
	$cs = 0;
	$i = 0;
	$tmp = $tm;
	for($i = 0; $i < 7; $i++){
		$cs += $tmp % 10;
		$tmp = (int) $tmp / 10;
	}
        //echo("tm"."$tm\n");
        //echo("cs:$cs\n");
        srand((double)microtime()*1000000);
        $rand_number= rand(0,99);
        $cs = $cs + $rand_number % 10 + (int)$rand_number / 10;
        //echo("rand:$rand_number\n");
        //echo("cs:$cs\n");
	$tm = $tm + "";
	$id = substr($tm, -7);
        //echo("id:$id\n");
        $str_rand = sprintf("%02d",$rand_number);
        $id =$id.$str_rand;
        //echo("id:$id\n");
        $cs = $cs % 10;
	$id = $id . ($cs + "");
        //echo("id:$id\n");
	return $id;
    }
}