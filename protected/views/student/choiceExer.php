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
    <div class="hero-unit">
        <form id="klgAnswer" name="na_knlgAnswer" method="post" action = "<?php echo $host.$path.$page;?>">
            <input name ="qType" type="hidden" value="knlg"/>
            <?php 
                foreach ($exercise['choice'] as $value) {
                    echo ($SNum+1).'. ';
                    echo $value['requirements'];
                    echo '<br/>';
                    $opt = $value['options'];
                    $optArr = explode("$$",$opt);
                    $mark = 'A';
                    foreach ($optArr as $aOpt) {
                        echo '<input type="radio" value="'.$mark.'" name="choice'.$value["exerciseID"].'">&nbsp'.$mark.'.'.$aOpt.'</input><br/>';
                        $mark++;
                    }
                    echo '<br/>';
                    $SNum++;
                }
            ?>
            <?php if(count($exercise['choice']) > 0){//this.submit()?>
            <a type="button" class="btn btn-primary btn-large" onclick="document.getElementById('klgAnswer').submit();">提交</a><a class="btn btn-large" onclick="">暂存</a>
            <?php }?>
        </form>
    </div>
</div>
<script>
$(document).ready(function(){
    $("li#li-choice").attr('class','active');
});
</script>