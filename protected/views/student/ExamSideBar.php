<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$currtime = $examInfo['endtime'];
?>
<h6>距离考试剩余还有</h6>
<body onLoad="timeCounter('timeCounter')">
<p id="timeCounter"></p>

<div class="span3">
        <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                        <li class="nav-header">基础知识</li>
                        <li id="li-choice">
                            <a href="./index.php?r=student/examchoice"><i class="icon-font"></i> 选 择 题</a>
                        </li>
                        <li id="li-filling">
                                <a href="./index.php?r=student/examfilling"><i class="icon-text-width"></i> 填 空 题</a>
                        </li>
                        <li id="li-question">
                                <a href="./index.php?r=student/examquestion"><i class="icon-align-left"></i> 简 答 题</a>
                        </li>
                        <li class="nav-header">键位练习</li>
                        <?php foreach ($exercise['key'] as $keyType) :?>
                            <li id="li-key-<?php echo $keyType['exerciseID'];?>">
                                    <a href="./index.php?r=student/examkeyType&&exerID=<?php echo $keyType['exerciseID'];?>">
                                        <i class="icon-th"></i>
                                        <?php echo $keyType['title']?>
                                    </a>
                            </li>
                        <?php endforeach;?>
                        <li class="nav-header">看打练习</li>
                        <?php foreach ($exercise['look'] as $lookType) :?>
                            <li id="li-look-<?php echo $lookType['exerciseID'];?>">
                                    <a href="./index.php?r=student/examlookType&&exerID=<?php echo $lookType['exerciseID'];?>">
                                        <i class="icon-eye-open"></i>
                                        <?php echo $lookType['title']?>
                                    </a>
                            </li>
                        <?php endforeach;?>
                        <li class="nav-header">听打练习</li>
                        <?php foreach ($exercise['listen'] as $listenType) :?>
                        <li id="li-listen-<?php echo $listenType['exerciseID'];?>">
                                <a href="./index.php?r=student/examlistenType&&exerID=<?php echo $listenType['exerciseID'];?>">
                                    <i class="icon-headphones"></i> 
                                    <?php echo $listenType['title']?>
                                </a>
                        </li>
                        <?php endforeach;?>
                </ul>
        </div>
</div>


<script>
    //2015-8-4 宋杰 显示考试倒计时
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
})()
</script>