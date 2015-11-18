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
$rout = 'student/saveQuestion';
$page = '/index.php?r='.$rout;
$SNum = 0;
?>
<div class="span9"style="height:480px; overflow:auto; border:1px solid #000000;">
    <form id="klgAnswer" name="na_knlgAnswer" method="post" action = "<?php echo $host.$path.$page;?>">
        <div class="hero-unit">
            <input name ="qType" type="hidden" value="question"/>
            <?php 
                $n=2*($pages->currentPage+1)-1;
                foreach ($questionLst as $value) {
                    echo ($n).'. ';
                    echo $value['requirements'];
                    echo '<br/>';
                    $f=0;
                    if($number!=null){
                        foreach ($number as $s){
                                if($value['exerciseID']==$s['exerciseID']){
                                    $f=1;
                                    echo '<textarea style="width:760px;resize:none; height:200px;max-width: 760px;"  name = "quest'.$value["exerciseID"].'">'.$ansQuest[$s['exerciseID']].'</textarea>';
                                    continue;
                                }
                        }
                    }
                    if($f==0){
                        echo '<textarea style="width:760px; height:200px;resize:none;max-width: 760px;" name = "quest'.$value["exerciseID"].'"></textarea>';
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
    $(function(){
 $("div.span9").find("a").click(function(event) {
     $.post($('#klgAnswer').attr('action'),$('#klgAnswer').serialize(),function(result){
       
     });
     
 });
})

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
    
    $("li#li-question").attr('class','active');
});
function submitSuite(){
    var isExam = <?php if($isExam){echo 1;}else {echo 0;}?>;
   var option = {
						title: "提交",
						btn: parseInt("0011",4),
						onOk: function(){
							formSubmit2();
        $.post($('#klgAnswer').attr('action'),$('#klgAnswer').serialize(),function(result){});
        $.post('index.php?r=student/overSuite&&isExam=<?php echo $isExam;?>',function(){
            if(isExam)
                window.location.href="index.php?r=student/classExam";
            else
                window.location.href="index.php?r=student/classwork";
        });
						}
					};
					window.wxc.xcConfirm("提交以后，不能重新进行答题，你确定提交吗？", "custom", option);
}
function formSubmit(){
  $.post($('#klgAnswer').attr('action'),$('#klgAnswer').serialize(),function(result){
     var option = {
						title: "提示",
						btn: parseInt("001",2),
						onOk: function(){
							location.reload(); 
						}
					};
					window.wxc.xcConfirm(result, "custom", option);
  });
}
function formSubmit2(){
  $.post($('#klgAnswer').attr('action'),$('#klgAnswer').serialize(),function(result){
       var option = {
						title: "提示",
						btn: parseInt("001",2),
						onOk: function(){
							location.reload(); 
						}
					};
					window.wxc.xcConfirm(result, "custom", option);
  });
}
</script>