<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require 'suiteSideBar.php';
$host = Yii::app()->request->hostInfo;
$path = Yii::app()->request->baseUrl;
$rout = $_REQUEST['r'];
$page = '/index.php?r='.$rout;
$SNum = 0;
?>
<div class="span9">
    <form id="klgAnswer" name="na_knlgAnswer" method="post" action = "<?php echo $host.$path.$page;?>">
        <div class="hero-unit">
            <input name ="qType" type="hidden" value="knlg"/>
            <?php 
                $SNum = 0;
                foreach ($exercise['filling'] as $value) {
                    echo ($SNum+1).'. ';
                    $str = $value['requirements'];
                    $strArry = explode("$$",$str);
                    echo $strArry[0];
                    $i = 1;
                    while($i < count($strArry)){
                        echo '<input type="text" name="'.$i.'filling'.$value["exerciseID"].'"></input>';
                        echo $strArry[$i];
                        $i++;
                    }
                    echo '<br/>';
                    $SNum++;
                }
            ?>
        </div>
        <?php if(count($exercise['filling']) > 0){?>
        <a type="button" class="btn btn-primary btn-large" onclick="document.getElementById('klgAnswer').submit();" style="margin-left: 200px">提交</a>
        <a class="btn btn-large" style="margin-left: 200px">暂存</a>
        <?php }?>
    </form>
</div>
<script>
$(document).ready(function(){
    $("li#li-filling").attr('class','active');
});
</script>