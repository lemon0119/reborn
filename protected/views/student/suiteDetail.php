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
    <h6>距离考试开始还有</h6>
    <p id="timeCounter"></p>
    </center>
    <?php }else { ?>
    <h3 align="center"> 考试已经开始，可以开始答题。 </h3>
    <?php } ?>
</div>
<script>
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
    */
function startTime(){
    <?php 
    //$end = $examInfo['endtime'];
    //echo "'$end'"?>;
    var curtime = <?php echo time();?>;
    var endtime = <?php echo strtotime($examInfo['endtime']);?>;
    var hh = parseInt((endtime - curtime) / 3600);
    var mm = parseInt((endtime - curtime) % 3600 / 60);
    var ss = parseInt((endtime - curtime) % 60);
    var strTime = '';
    var timer;
	timer = window.setInterval(function(){
		strTime = "";
		if(--ss == 0)
		{
			if(--mm == 0)
			{
				hh--;
                                if( hh == 0){
                                    clearInterval(timer);
                                    document.getElementById('time').innerHTML = '考试已经开始，可以开始答题。';
                                }
				mm = 59;
			}
			ss = 59;
		}
		strTime+=hh<10?"0"+hh:hh;
		strTime+=":";
		strTime+=mm<10?"0"+mm:mm;
		strTime+=":";
		strTime+=ss<10?"0"+ss:ss;
		document.getElementById('timeCounter').innerHTML = strTime;
	},1000);
};
$(document).ready(function(){
    <?php if($isExam) echo "startTime();";?>
});
</script>