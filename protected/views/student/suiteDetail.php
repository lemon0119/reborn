<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if($isExam == false){
require 'suiteSideBar.php';
}
else {
    require 'examSideBar.php';
}
?>
<div clas="span9">
    <?php if($isExam == true){?>
    <center id="time">
    <h6 class="welcome">距离考试开始还有</h6>
    <p  class="welcome"id="timeCounter"></p>
    </center>
    <?php }else { ?>
    <h3 class="welcome" align="center"> 在左侧选择题目开始答题。 </h3>
    <?php } ?>
</div>
<script>
function submitSuite(simple){
        var isExam = <?php if($isExam){echo 1;}else {echo 0;}?>;
        if(!simple){
            if(!confirm("提交以后，不能重新进行答题，你确定提交吗？"))
                return ;
        }
        if(isExam)
            window.location.href="index.php?r=student/classExam";
        else
            window.location.href="index.php?r=student/classwork";
    }
$(document).ready(function(){
    $('.queTitle').bind('click', function(e){
        e.preventDefault();
    });
    $('#leftTime').hide();
    var isExam = <?php if($isExam){echo 1;}else {echo 0;}?>;
    var curtime = <?php echo time();?>;
    var beginTime = <?php if($isExam){ echo strtotime($examInfo['begintime']);}else{echo 0;}?>;
    function endTimer(endID){
        document.getElementById('time').innerHTML = '<h3 class="welcome" align="center"> 考试已经开始，可以开始答题。</h3>';
        $('#leftTime').show();
        $('.queTitle').unbind('click');
    }
    if(isExam){
        tCounter(curtime,beginTime,"timeCounter",endTimer);
    }
});
 function submitSuite(){
    var isExam = <?php if($isExam){echo 1;}else {echo 0;}?>;
    if(confirm("提交以后，不能重新进行答题，你确定提交吗？")){
        formSubmit2();
        $.post($('#klgAnswer').attr('action'),$('#klgAnswer').serialize(),function(result){});
        $.post('index.php?r=student/overSuite&&isExam=<?php echo $isExam;?>',function(){
            if(isExam)
                window.location.href="index.php?r=student/classExam";
            else
                window.location.href="index.php?r=student/classwork";
        });
    }
}    
function formSubmit2(){
  $.post($('#klgAnswer').attr('action'),$('#klgAnswer').serialize(),function(result){
      //alert(result);
      location.reload(); 
  });
  
}
/*
var timeCounter = (function() {
 var int;
 var currtime = "<?php echo date("Y-m-d  H:i:s");?>";
 var time = currtime.substr(11,19).split(":");
 var h2 = time[0];
 var m2 = time[1];
 var s2 = time[2];
 var myDate = new Date();
 var h1 = myDate.getHours();
 var m1 = myDate.getMinutes();
 var s1 = myDate.getSeconds();
var total = (( Number((h2-h1)*60))+ Number(m2)-Number(m1))*60 + Number(s2) - Number(s1);
 return function(elemID) {
  obj = document.getElementById(elemID);
  var s = (total%60) < 10 ? ('0' + total%60) : total%60;
  var h = total/3600 < 10 ? ('0' + parseInt(total/3600)) : parseInt(total/3600);
  var m = (total-h*3600)/60 < 10 ? ('0' + parseInt((total-h*3600)/60)) : parseInt((total-h*3600)/60);
  obj.innerHTML = h + ' : ' + m + ' : ' + s;
  total--;
  int = setTimeout("timeCounter('" + elemID + "')", 1000);
  if(total < 0) clearTimeout(int);
 }
})();
timeCounter('timeCounter');
*/
</script>