<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 if($isExam == false){ 
require 'suiteSideBar.php';
 }else{ 
    require 'examSideBar.php';
 } 
$host = Yii::app()->request->hostInfo;
$path = Yii::app()->request->baseUrl;
$rout = 'student/saveFilling';
$page = '/index.php?r='.$rout;
$SNum = 0;
?>
 <h3 >课 堂 作 业</h3>
<div class="span9">
    <form id="klgAnswer" name="na_knlgAnswer" method="post" action = "<?php echo $host.$path.$page;?>">
        <div class="hero-unit">
            <input name ="qType" type="hidden" value="filling"/>
            <?php 
               $n=2*($pages->currentPage+1)-1;
                foreach ($fillingLst as $value) {
                    echo ($n++).'. ';
                    $str = $value['requirements'];
                    $answer = $value['answer'];
                    $ansArr = explode('$$', $answer);
                    echo $str.'<br/>';
                    $i = 1;
                    while($i < count($ansArr)+1){
                        echo '('.$i.') ';
                        echo '<input type="text" name="'.$i.'filling'.$value["exerciseID"].'"></input><br/>';
                        $i++;
                    }
                    echo '<br/>';
                  
                }
            ?>
             <!-- 显示翻页标签 -->
    <div align=center>
        <?php
        $this->widget('CLinkPager', array('pages' => $pages));
        ?>
    </div>
    <!-- 翻页标签结束 -->
        </div>
        <?php if(count($exercise['filling']) > 0){?>
        <a type="button" class="btn btn-primary btn-large" onclick="formSubmit();" style="margin-left: 150px">保存</a>
        <a href="./index.php?r=student/classwork"type="button" class="btn btn-primary btn-large"  style="margin-left: 350px">退出</a>
        <!--<a class="btn btn-large" style="margin-left: 200px">暂存</a>-->
        <?php }?>
    </form>
</div>
<script>
$(document).ready(function(){
    $("li#li-filling").attr('class','active');
});
function formSubmit(){
  $.post($('#klgAnswer').attr('action'),$('#klgAnswer').serialize(),function(result){
      alert(result);
       window.location.href = './index.php?r=student/clswkOne&&suiteID=<?php echo $workID;?>';   
  });
}
</script>