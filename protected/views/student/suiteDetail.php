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
<script src="<?php echo JS_URL;?>exerJS/timeCounter.js"></script>
<div clas="span9">
    <?php if($isExam == true){?>
    <center id="time">
    <h6>距离考试开始还有</h6>
    <p id="timeCounter"></p>
    </center>
    <?php }else { ?>
    <h3 align="center"> 考试已经开始，可以开始答题。 </h3>
    <?php } ?>
</div>
<script>
$(document).ready(function(){
    $('.queTitle').bind('click', function(e){
        e.preventDefault();
    });
    $('#leftTime').hide();
    var isExam = <?php if($isExam){echo 1;}else {echo 0;}?>;
    var curtime = <?php echo time();?>;
    var beginTime = <?php if($isExam) echo strtotime($examInfo['begintime']);?>;
    function endTimer(endID){
        document.getElementById('time').innerHTML = '考试已经开始，可以开始答题。';
        $('#leftTime').show();
        $('.queTitle').unbind('click');
    }
    if(isExam){
        tCounter(0,3,"timeCounter",endTimer);
    }
});
</script>