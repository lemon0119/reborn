<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class LCS {
    public static function main($args)
    {
        $s1= "abcbdab";
        $s2 = "bdcaba";
        LCS.getLCSLength(s1, s2);
    }
    public static function getLCSLength($str1, $str2)
    {
        for($i=1; $i<$x.length+1; ++$i)
                for($j=1; $j<y.length+1; ++$j)
                {
                        if($x[$i-1] == $y[$j-1])
                                $c[i][j] = $c[$i-1][$j-1]+1;
                        else if($c[$i-1][$j]>=$c[$i][$j-1])
                                $c[$i][$j] = $c[$i-1][$j];
                        else
                                $c[$i][$j] = $c[$i][$j-1];
                }
        printLCS($c, $x, $y,  $x.length, $y.length);
    }
    public static function printLCS($c, $x, $y, $i, $j)
    {
            if($i==0||$j==0)
                    return;
            if($x[$i-1] == $y[$j-1])
            {
                    printLCS($c, $x, $y, $i-1, $j-1);
                    echo ($x[$i-1]);
            }
            else if($c[$i-1][$j] >= $c[$i][$j-1])
                    printLCS($c, $x, $y, $i-1, $j);
            else
                    printLCS($c, $x, $y, $i, $j-1);
    }
}

LCS.main();
echo "ssss";