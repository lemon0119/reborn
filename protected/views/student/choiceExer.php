 <?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 if($isExam == FALSE){ 
require 'suiteSideBar.php';
 }else{ 
    require 'examSideBar.php';
 } 
$host = Yii::app()->request->hostInfo;
$path = Yii::app()->request->baseUrl;
$rout = 'student/saveChoice';
$page = '/index.php?r='.$rout;
$SNum = 0;
?>

<div class="span9"style="height:480px; overflow:auto; border:1px solid #000000;">
    <form id="klgAnswer" name="na_knlgAnswer" method="post" action = "<?php echo $host.$path.$page;?>">
        <div class="hero-unit">
        <input name ="qType" type="hidden" value="choice"/>
       <?php 
            $n=2*($pages->currentPage+1)-1;
            foreach ($choiceLst as $value) {
                
                echo ($n).'. ';
                echo $value['requirements'];
                echo '<br/>';
                $opt = $value['options'];
                $optArr = explode("$$",$opt);
                $mark = 'A';
                foreach ($optArr as $aOpt) {
                    $f=0;
                    foreach ($number as $s){
                        if($value['exerciseID']==$s['exerciseID']){
                            if($ansChoice[$s['exerciseID']]==$mark) {
                                $f=1;
                                echo '<input type="radio" checked="true" value="'.$mark.'" name="choice'.$value["exerciseID"].'">&nbsp'.$mark.'.'.$aOpt.'</input><br/>';
                            }
                        }
                    }
                    if($f==0){
                        echo '<input type="radio" value="'.$mark.'" name="choice'.$value["exerciseID"].'">&nbsp'.$mark.'.'.$aOpt.'</input><br/>';
                    }
                    $mark++;
                }
                    
                echo '<br/>';
               $n++;
            }
        ?>

 

        
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
    $("li#li-choice").attr('class','active');
    
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