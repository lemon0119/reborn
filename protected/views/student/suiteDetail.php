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
    <center>
    <h6>距离考试剩余还有</h6>
    <p id="timeCounter"></p>
    </center>
</div>
<script>
var timeCounter = (function() {
 var int;
 var currtime = "<?php echo $currtime?>";
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

</script>