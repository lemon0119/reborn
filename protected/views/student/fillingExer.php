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
<div class="span9"style="height:480px; overflow:auto; border:1px solid #000000;"
    <form id="klgAnswer" name="na_knlgAnswer" method="post" action = "<?php echo $host.$path.$page;?>">
        <div class="hero-unit">
            <input name ="qType" type="hidden" value="filling"/>
            <?php 
               $n=2*($pages->currentPage+1)-1;
                foreach ($fillingLst as $value) {
                    echo ($n).'. ';
                    $str = $value['requirements'];
                    $answer = $value['answer'];
                    $ansArr = explode('$$', $answer);
                    echo $str.'<br/>';
                    $i = 1;
                    $m=0;
                    while($i < count($ansArr)+1){
                        $f=0;
                        
                        echo '('.$i.') ';
                        foreach ($number as $s){
                            if($value['exerciseID']==$s['exerciseID']){
                                $f=1;
                                $str=$ansFilling[$s['exerciseID']];
                                $arr=Array();
                                if(strstr($str,"$$")){
                                    $arr= explode("$$", $str);
                                    echo '<input type="text" value="'.$arr[$m].'" name="'.$i.'filling'.$value["exerciseID"].'"></input><br/>';
                                    $m++;
                                }else{
                                    echo '<input type="text" value="'.$str.'" name="'.$i.'filling'.$value["exerciseID"].'"></input><br/>';
                                }
                            }
                        }
                        if($f==0){
                            echo '<input type="text" name="'.$i.'filling'.$value["exerciseID"].'"></input><br/>';  
                        }
                         
                        
                        $i++;
                    }
                    echo '<br/>';
                  $n++;
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
       
    </form>
</div>
<script>
$(document).ready(function(){
    
    $("div.span9").find("a").click(function() {
        var url=$(this).attr("href");
        if(url.indexOf("index.php")>0){
            $.post($('#klgAnswer').attr('action'),$('#klgAnswer').serialize(),function(result){
                console.log(result);
                window.location.href = url;
            });
            return false;
        }
    });
    
    $("li#li-filling").attr('class','active');
});
function submitSuite(){
    var isExam = <?php if($isExam){echo 1;}else {echo 0;}?>;
    if(confirm("提交以后，不能重新进行答题，你确定提交吗？")){
        $.post($('#klgAnswer').attr('action'),$('#klgAnswer').serialize(),function(result){});
        $.post('index.php?r=student/overSuite&&isExam=<?php echo $isExam;?>',function(){
            if(isExam)
                window.location.href="index.php?r=student/classExam";
            else
                window.location.href="index.php?r=student/classwork";
        });
    }
}
function formSubmit(){
  $.post($('#klgAnswer').attr('action'),$('#klgAnswer').serialize(),function(result){
      alert(result);
       location.reload();  
  });
}
</script>