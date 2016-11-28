<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$currtime = $examInfo['endtime'];
?>
<script src="<?php echo JS_URL;?>exerJS/timeCounter.js"></script>
<script src="<?php echo JS_URL; ?>exerJS/LCS.js"></script>
<script src="<?php echo JS_URL; ?>exerJS/AnalysisTool.js"></script>
<style type="text/css">
    .queTitle{}
</style>
<div class="span3">
        <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                        <li class="nav-header" id="leftTime">考试剩余：<font id = "sideTime"></font></li>
                </ul>
                <div class="well-topnoradius" style="padding: 8px 0;height:717px;overflow:auto;top: 0px">
                    <ul class="nav nav-list">
                         <?php if (count($exercise['choice']) != 0 && count($exercise['filling']) != 0 && count($exercise['question']) != 0) { ?>
                        <li class="nav-header">基础知识</li>
                        <?php } if (count($exercise['choice']) != 0) { ?>
                        <li id="li-choice">
                            <a class="queTitle" href="./index.php?r=student/examchoice&&cent=<?php $arg= implode(',', $cent);echo $arg;?>"><i class="icon-font"></i> <span style="position: relative;top: 6px">选 择 题</span>
                                <div id= "container" style="height: 5px;border:1px solid white;">
                                    <div id="progress-bar" style="width:<?php echo "$cent[0]";?>;background-color:springgreen;height:5px;">
                                    </div>
                                </div> 
                            </a>
                        </li>
                         <?php } if (count($exercise['filling']) != 0) { ?>
                        <li id="li-filling">
                            <a class="queTitle" href="./index.php?r=student/examfilling&&cent=<?php $arg= implode(',', $cent);echo $arg;?>"><i class="icon-text-width"></i><span style="position: relative;top: 6px"> 填 空 题</span>
                                    <div id= "container" style="height: 5px;border:1px solid white;">
                                        <div id="progress-bar" style="width:<?php echo "$cent[1]";?>;background-color: springgreen;height:5px;">
                                        </div>
                                    </div>
                                </a>
                        </li>
                        <?php } if (count($exercise['question']) != 0) { ?>
                        <li id="li-question">
                            <a class="queTitle" href="./index.php?r=student/examquestion&&cent=<?php $arg= implode(',', $cent);echo $arg;?>"><i class="icon-align-left"></i> <span style="position: relative;top: 6px">简 答 题</span>
                                    <div id= "container" style="height: 5px;border:1px solid white;">
                                        <div id="progress-bar" style="width:<?php echo "$cent[2]";?>;background-color: springgreen;height:5px;">
                                        </div>
                                    </div>
                                </a>
                        </li>
                         <?php } if (count($exercise['key']) != 0) { ?>
                        <li class="nav-header">键打练习</li>
                        <?php foreach ($exercise['key'] as $keyType) :?>
                            <li id="li-key-<?php echo $keyType['exerciseID'];?>">
                                <a href="#" class="queTitle" title="<?php echo $keyType['title']; ?>"  onclick="examKeyNext(<?php echo $keyType['exerciseID']?>,'<?php $arg= implode(',', $cent);echo $arg;?>')">                                
                                        <i class="icon-th"></i>
                                        <span style="position: relative;top: 6px">
                                        <?php if (Tool::clength($keyType['title']) <= 13){
                                                    echo $keyType['title'];
                                               }else{
                                                    echo Tool::csubstr($keyType['title'], 0, 13) . "...";
                                               }?>
                                        </span>
                                    </a>
                            </li>
                        <?php endforeach;
} if (count($exercise['look']) != 0) { ?>
                        <li class="nav-header">看打练习</li>
                        <?php foreach ($exercise['look'] as $lookType) :?>
                            <li id="li-look-<?php echo $lookType['exerciseID'];?>">
                                <?php ?>
<!--                                    <a class="queTitle"href="./index.php?r=student/examlookType&&exerID=<?php// echo $lookType['exerciseID']?>&&cent=<?php// $arg= implode(',', $cent);echo $arg;?>">-->
                                <a href="#" class="queTitle"  title="<?php echo$lookType['title']; ?>" onclick="examLookNext(<?php echo $lookType['exerciseID']?>,'<?php $arg= implode(',', $cent);echo $arg;?>')">
                                        <i class="icon-eye-open"></i>
                                        <span style="position: relative;top: 6px">
                                        <?php if (Tool::clength($lookType['title']) <= 13){
                                                    echo $lookType['title'];
                                               }else{
                                                    echo Tool::csubstr($lookType['title'], 0, 13) . "...";
                                               } ?>
                                        </span>
                                    </a>
                            </li>
                        <?php endforeach;
                                    } if (count($exercise['listen']) != 0) { ?>
                        <li class="nav-header">听打练习</li>
                        <?php foreach ($exercise['listen'] as $listenType) :?>
                        <li id="li-listen-<?php echo $listenType['exerciseID'];?>">
                            <a href="#" class="queTitle"  title="<?php echo $listenType['title'];?>" onclick="examListenNext(<?php echo $listenType['exerciseID']?>,'<?php $arg= implode(',', $cent);echo $arg;?>')">                            
                                    <i class="icon-headphones"></i> 
                                    <span style="position: relative;top: 6px">
                                    <?php if (Tool::clength($listenType['title']) <= 13){
                                                echo $listenType['title'];
                                           }else{
                                                echo Tool::csubstr($listenType['title'], 0, 13) . "...";
                                           } ?>
                                    </span>
                                        
                                </a>
                        </li>
                                    <?php endforeach; }?>
                </ul>
                </div>
            <?php if (count($exercise['choice']) == 0 && count($exercise['filling']) == 0 && count($exercise['question']) == 0 && count($exercise['key']) == 0 && count($exercise['look']) == 0 && count($exercise['listen']) == 0) { ?>
            <li class="nav-header">无内容</li>
<?php } else { ?>
           <li class="nav-header" ><br/></li>
             <li class="nav-header">
                <a type="button" href="#" class="btn btn-primary" style="width: 78%"  onclick="submitSuite();">提交</a>                 
            </li>
        </div>   
    <?php } ?>
</div>
<script>
     function submitSuite2(){
         saveToDateBaseNow();
         saveData();
        $.post('index.php?r=student/overSuite&&isExam=<?php if($isExam){echo 'true';}else{echo 'false';} ?>', function () {
                    if (<?php if($isExam){echo 'true';}else{echo 'false';} ?>){
                        window.location.href = "index.php?r=student/classExam";
                    }
                    else
                        window.location.href = "index.php?r=student/classwork";
                });
    }
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
        //var endTime = <?php echo strtotime($examInfo['endtime']);?>;
        var beginTime = <?php echo strtotime($examInfo['begintime']);?>;
        function endTimer(endID){
            //window.wxc.xcConfirm("考试时间已到，即将交卷。", window.wxc.xcConfirm.typeEnum.warning);
            submitSuite2(true);
        }
        tCounter(curtime,beginTime+60*<?php echo $examInfo['duration']?>,"sideTime", endTimer);
    });
    function examLookNext(exerID,cent){
        var option = {
						title: "提示",
						btn: parseInt("0011",2),
						onOk: function(){
							saveToDateBaseNow();
                                                        saveData();
                                                        $.post($('#klgAnswer').attr('action'), $('#klgAnswer').serialize(),function () {
                    window.location.href = "index.php?r=student/examlookType&&exerID="+exerID+"&&cent="+cent;
                });
						}
					};
					window.wxc.xcConfirm("您确定跳转至这题吗？", "custom", option);
        }
        
        function examListenNext(exerID,cent){
        var option = {
						title: "提示",
						btn: parseInt("0011",2),
						onOk: function(){
							saveToDateBaseNow();
                                                        saveData();
                                                        $.post($('#klgAnswer').attr('action'), $('#klgAnswer').serialize(), function () {
                    window.location.href = "index.php?r=student/examlistenType&&exerID="+exerID+"&&cent="+cent;
                });
						}
					};
					window.wxc.xcConfirm("您确定跳转至这题吗？", "custom", option);
        }
        
        function examKeyNext(exerID,cent){
        var option = {
						title: "提示",
						btn: parseInt("0011",2),
						onOk: function(){
							saveToDateBaseNow();
                                                        saveData();
                                                        $.post($('#klgAnswer').attr('action'), $('#klgAnswer').serialize(), function () {
                    window.location.href = "index.php?r=student/examkeyType&&exerID="+exerID+"&&cent="+cent;
                });
						}
					};
					window.wxc.xcConfirm("您确定跳转至这题吗？", "custom", option);
        }
</script>
