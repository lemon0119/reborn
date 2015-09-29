<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$currtime = $examInfo['endtime'];
?>
<script src="<?php echo JS_URL;?>exerJS/timeCounter.js"></script>
<style type="text/css">
    .queTitle{}
</style>
<div class="span3">
        <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                        <li class="nav-header" id="leftTime">考试剩余：<font id = "sideTime"></font></li>
                        <li class="nav-header">基础知识</li>
                        <li id="li-choice">
                            <a class="queTitle" href="./index.php?r=student/examchoice&&cent=<?php $arg= implode(',', $cent);echo $arg;?>"><i class="icon-font"></i> 选 择 题
                                <div id= "container" style="height: 5px;border:1px solid white;">
                                    <div id="progress-bar" style="width:<?php echo "$cent[0]";?>;background-color:springgreen;height:5px;">
                                    </div>
                                </div> 
                            </a>
                        </li>
                        <li id="li-filling">
                                <a class="queTitle" href="./index.php?r=student/examfilling&&cent=<?php $arg= implode(',', $cent);echo $arg;?>"><i class="icon-text-width"></i> 填 空 题
                                    <div id= "container" style="height: 5px;border:1px solid white;">
                                        <div id="progress-bar" style="width:<?php echo "$cent[1]";?>;background-color: springgreen;height:5px;">
                                        </div>
                                    </div>
                                </a>
                        </li>
                        <li id="li-question">
                                <a class="queTitle" href="./index.php?r=student/examquestion&&cent=<?php $arg= implode(',', $cent);echo $arg;?>"><i class="icon-align-left"></i> 简 答 题
                                    <div id= "container" style="height: 5px;border:1px solid white;">
                                        <div id="progress-bar" style="width:<?php echo "$cent[2]";?>;background-color: springgreen;height:5px;">
                                        </div>
                                    </div>
                                </a>
                        </li>
                        <li class="nav-header">键位练习</li>
                        <?php foreach ($exercise['key'] as $keyType) :?>
                            <li id="li-key-<?php echo $keyType['exerciseID'];?>">
                                    <a class="queTitle" href="./index.php?r=student/examkeyType&&exerID=<?php echo $keyType['exerciseID']?>&&cent=<?php $arg= implode(',', $cent);echo $arg;?>">
                                        <i class="icon-th"></i>
                                        <?php echo $keyType['title']?>
                                    </a>
                            </li>
                        <?php endforeach;?>
                        <li class="nav-header">看打练习</li>
                        <?php foreach ($exercise['look'] as $lookType) :?>
                            <li id="li-look-<?php echo $lookType['exerciseID'];?>">
                                    <a class="queTitle"href="./index.php?r=student/examlookType&&exerID=<?php echo $lookType['exerciseID']?>&&cent=<?php $arg= implode(',', $cent);echo $arg;?>">
                                        <i class="icon-eye-open"></i>
                                        <?php echo $lookType['title']?>
                                    </a>
                            </li>
                        <?php endforeach;?>
                        <li class="nav-header">听打练习</li>
                        <?php foreach ($exercise['listen'] as $listenType) :?>
                        <li id="li-listen-<?php echo $listenType['exerciseID'];?>">
                                <a class="queTitle" href="./index.php?r=student/examlistenType&&exerID=<?php echo $listenType['exerciseID']?>&&cent=<?php $arg= implode(',', $cent);echo $arg;?>">
                                    <i class="icon-headphones"></i> 
                                    <?php echo $listenType['title']?>
                                </a>
                        </li>
                        <?php endforeach;?>
                </ul>
            <li>
                 <a type="button" class="btn btn-large" style="width: 34%"  onclick="submitSuite();">提交</a>    
                 <a type="button" style="width:34%" class="btn btn-primary btn-large" onclick="formSubmit();">保存</a>
             </li>
        </div>      
</div>
<script>
    $(document).ready(function(){
        $("div.span3 div.well ul li").find("a").click(function() {
            var url=$(this).attr("href");
            if(url.indexOf("index.php")>0){
                $.post($('#klgAnswer').attr('action'),$('#klgAnswer').serialize(),
                function(result){
                    console.log(result);
                    window.location.href = url;
                });
                return false;
            }
        });
        var curtime = <?php echo time();?>;
        var endTime = <?php echo strtotime($examInfo['endtime']);?>;
        function endTimer(endID){
            alert("考试时间已到，即将交卷。");
            submitSuite(true);
        }
        tCounter(curtime,endTime,"sideTime", endTimer);
    }); 
        
</script>
