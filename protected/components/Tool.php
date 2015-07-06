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
    function safeEncoding($string,$outEncoding ='UTF-8'){   
        $encoding = "UTF-8";
        for($i=0;$i<strlen($string);$i++) {   
            if(ord($string{$i})<128)   
                continue;
            if((ord($string{$i})&224)==224){   
                //第一个字节判断通过
                $char = $string{++$i};
                if((ord($char)&128)==128) {   
                    //第二个字节判断通过
                    $char = $string{++$i};   
                    if((ord($char)&128)==128) {   
                        $encoding = "UTF-8";   
                        break;
                    }   
                }   
            }   
            if((ord($string{$i})&192)==192) {   
                //第一个字节判断通过
                $char = $string{++$i};
                if((ord($char)&128)==128) {   
                    // 第二个字节判断通过
                    $encoding = "GB2312";
                    break;
                }
            }
        }
        if(strtoupper($encoding) == strtoupper($outEncoding))   
            return $string;   
        else
            return iconv($encoding,$outEncoding,$string);   
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
        echo("tm"."$tm\n");
        echo("cs:$cs\n");
        srand((double)microtime()*1000000);
        $rand_number= rand(0,99);
        $cs = $cs + $rand_number % 10 + (int)$rand_number / 10;
        echo("rand:$rand_number\n");
        echo("cs:$cs\n");
	$tm = $tm + "";
	$id = substr($tm, -7);
        echo("id:$id\n");
        $str_rand = sprintf("%02d",$rand_number);
        $id =$id.$str_rand;
        echo("id:$id\n");
        $cs = $cs % 10;
	$id = $id . ($cs + "");
        echo("id:$id\n");
	return $id;
    }
}