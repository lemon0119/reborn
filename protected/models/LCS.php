<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class LCS{
    //设置字符串长度
    protected $substringLength1;  
    protected $substringLength2;  //具体大小可自行设置
    protected $subLCS;
    protected $opt;
    protected $x = "";
    protected $y = "";
    protected $x_m = "";
    protected $y_m = "";
    
    public function LCS($str1 = '', $str2 = ''){
        $str1 = utf8_encode($str1);
        $str2 = utf8_encode($str2);
        
        $this->x = $str1;
        $this->y = $str2;
        $this->subLCS = '';
        $this->substringLength1 = strlen($str1);
        $this->substringLength2 = strlen($str2);
        $this->opt = array_fill(0,$this->substringLength1 + 1,array_fill(0,$this->substringLength2 + 1,null));  
    }
    
    public function doLCS(){
        // 动态规划计算所有子问题  
        for ($i = $this->substringLength1 - 1; $i >= 0; $i--){  
            for ($j = $this->substringLength2 - 1; $j >= 0; $j--){  
                if ($this->x[$i] == $this->y[$j])  {
                    $this->opt[$i][$j] = $this->opt[$i + 1][$j + 1] + 1;                             
                }
                else {
                    $this->opt[$i][$j] = max($this->opt[$i + 1][$j], $this->opt[$i][$j + 1]);                          
                }
            }
        }
        $i = 0;
        $j = 0;
        while ($i < $this->substringLength1 && $j < $this->substringLength2){  
            if ($this->x[$i] == $this->y[$j]){
                $this->subLCS .= $this->x[$i];
                $this->x_m .= $this->x[$i];
                $this->y_m .= $this->x[$i];
                $i++;
                $j++;
            } else if ($this->opt[$i + 1][$j] >= $this->opt[$i][$j + 1])  {
		$this->x_m .= utf8_encode('*');
                $i++;
            } else{
                $this->y_m .= utf8_encode('*');
                $j++;
            }
        }
    }
    public function getSubString($type = 3){
        switch ($type) {
            case 1 :
                return $this->x_m;
            case 2:
                return $this->y_m;
            default:
                return $this->subLCS;
        }
    }
}