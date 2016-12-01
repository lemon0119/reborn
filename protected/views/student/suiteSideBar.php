<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
$sqlClassExerciseRecord = null;
$studentID = Yii::app()->session['userid_now']; 
?>
<script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js"></script>
<script src="<?php echo JS_URL; ?>exerJS/LCS.js"></script>
<script src="<?php echo JS_URL; ?>exerJS/AnalysisTool.js"></script>

<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <div class="well-topnoradius" style="padding: 8px 0;height:740px;overflow:auto;top: 20px">
        <ul class="nav nav-list">
            <?php if (count($exercise['choice']) != 0 && count($exercise['filling']) != 0 && count($exercise['question']) != 0) { ?>
                <li class="nav-header">基础知识</li>
            <?php } if (count($exercise['choice']) != 0) { ?>
                <li id="li-choice">
                    <a 
                    <?php if (isset($_GET['lessonID'])) { ?>
                            href="./index.php?r=student/choice&&cent=<?php
                            $arg = implode(',', $cent);
                            echo $arg;
                            ?>&&lessonID=<?php echo $_GET['lessonID']; ?>&&type=1"
                        <?php } else { ?>
                            href="./index.php?r=student/choice&&cent=<?php
                            $arg = implode(',', $cent);
                            echo $arg;
                            ?>&&type=1"
    <?php } ?>
                            ><i class="icon-font"></i> <span style="position: relative;top: 6px">选 择 题</span>
                        <div id= "container" style="height: 5px;border:1px solid white;">
                            <div id="progress-bar" style="width:<?php echo "$cent[0]"; ?>;background-color:springgreen;height:5px">
                            </div>
                        </div> </a>                           
                </li>
                <?php } if (count($exercise['filling']) != 0) { ?>
                <li id="li-filling">
                    <a 
                        <?php if (isset($_GET['lessonID'])) { ?>
                            href="./index.php?r=student/filling&&cent=<?php
                            $arg = implode(',', $cent);
                            echo $arg;
                            ?>&&lessonID=<?php echo $_GET['lessonID']; ?>&&type=2"
                        <?php } else { ?>
                            href="./index.php?r=student/filling&&cent=<?php
                    $arg = implode(',', $cent);
                    echo $arg;
                    ?>&&type=2"
    <?php } ?>
                    ><i class="icon-text-width"></i><span style="position: relative;top: 6px"> 填 空 题</span><div id= "container" style="height: 5px;border:1px solid white;">
                            <div id="progress-bar" style="width:<?php echo "$cent[1]"; ?>;background-color: springgreen;height:5px;">
                            </div>
                        </div> </a>
                </li>
                    <?php } if (count($exercise['question']) != 0) { ?>
                <li id="li-question">
                    <a 
                        <?php if (isset($_GET['lessonID'])) { ?>
                            href="./index.php?r=student/question&&cent=<?php
                            $arg = implode(',', $cent);
                            echo $arg;
                            ?>&&lessonID=<?php echo $_GET['lessonID']; ?>&&type=3"
    <?php } else { ?>
                            href="./index.php?r=student/question&&cent=<?php
        $arg = implode(',', $cent);
        echo $arg;
        ?>&&type=3"
                <?php } ?>
        ><i class="icon-align-left"></i><span style="position: relative;top: 6px"> 简 答 题</span><div id= "container" style="height: 5px;border:1px solid white;">
                            <div id="progress-bar" style="width:<?php echo "$cent[2]"; ?>;background-color: springgreen;height:5px;">
                            </div>
                        </div> </a>
                </li>
                    <?php } if (count($exercise['key']) != 0) { ?>
                <li class="nav-header">键打练习</li>
                        <?php foreach ($exercise['key'] as $keyType) : 
                        if($type_name==""){
                            $type_name="key";
                        }
                            $sqlClassExerciseRecord = AnswerRecord::model()->find("recordID = '$recordID' AND exerciseID = '$exer_id' AND type = '$type_name' AND createPerson LIKE '$studentID'");
                            ?>
                    <li id="li-key-<?php echo $keyType['exerciseID']; ?>">
                        
                        <a <?php if (isset($_GET['lessonID'])) { ?> href="#" onclick="suiteKeyNext(<?php echo $keyType['exerciseID']?>,'<?php $arg= implode(',', $cent);echo $arg;?>',<?php echo $_GET['lessonID']; ?>,<?php if(isset($_GET['type'])){echo $_GET['type'];}else{echo 0;} ?>,<?php if($sqlClassExerciseRecord==null){echo 1;}else{echo 0;}?>)"
                    <?php } else { ?>
                               href="#" onclick="suiteKeyNext(<?php echo $keyType['exerciseID']?>,'<?php $arg= implode(',', $cent);echo $arg;?>',0,<?php if(isset($_GET['type'])){echo $_GET['type'];}else{echo 0;} ?>,<?php if($sqlClassExerciseRecord==null){echo 1;}else{echo 0;}?>)"
        <?php } ?>
                   title="<?php echo $keyType['title']; ?>"         >
                            <i class="icon-th"></i>
                            <span style="position: relative;top: 6px">
        <?php if (Tool::clength($keyType['title']) <= 13){
                echo $keyType['title'];
        }else{
            echo Tool::csubstr($keyType['title'], 0, 13) . "...";
        }
         ?>
                            </span>
                        </a>
                    </li>
                       <?php
                       endforeach;
                   } if (count($exercise['look']) != 0) {
                       ?>
                <li class="nav-header">看打练习</li>
                       <?php foreach ($exercise['look'] as $lookType) : 
                           if($type_name==""){
                            $type_name="look";
                            }

                           $sqlClassExerciseRecord = AnswerRecord::model()->find("recordID = '$recordID' AND exerciseID = '$exer_id' AND type = '$type_name' AND createPerson LIKE '$studentID'");
                           ?>
                    <li id="li-look-<?php echo $lookType['exerciseID']; ?>">
                    <a <?php if (isset($_GET['lessonID'])) { ?> href="#" onclick="suiteLookNext(<?php echo $lookType['exerciseID']?>,'<?php $arg= implode(',', $cent);echo $arg;?>',<?php echo $_GET['lessonID']; ?>,<?php if(isset($_GET['type'])){echo $_GET['type'];}else{echo 0;} ?>,<?php if($sqlClassExerciseRecord==null){echo 1;}else{echo 0;}?>)"
                    <?php } else { ?>
                               href="#" onclick="suiteLookNext(<?php echo $lookType['exerciseID']?>,'<?php $arg= implode(',', $cent);echo $arg;?>',0,<?php if(isset($_GET['type'])){echo $_GET['type'];}else{echo 0;} ?>,<?php if($sqlClassExerciseRecord==null){echo 1;}else{echo 0;}?>)"
        <?php } ?>  title="<?php echo $lookType['title']; ?>"
                           >
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
                        <?php
                        endforeach;
                    } if (count($exercise['listen']) != 0) {
                        ?>
                <li class="nav-header">听打练习</li>
    <?php foreach ($exercise['listen'] as $listenType) : 
        if($type_name==""){
            $type_name="listen";
        }

        $sqlClassExerciseRecord = AnswerRecord::model()->find("recordID = '$recordID' AND exerciseID = '$exer_id' AND type = '$type_name' AND createPerson LIKE '$studentID'");
        ?>
                    <li id="li-listen-<?php echo $listenType['exerciseID']; ?>">
                        
                        <a <?php if (isset($_GET['lessonID'])) { ?> href="#" onclick="suiteListenNext(<?php echo $listenType['exerciseID']?>,'<?php $arg= implode(',', $cent);echo $arg;?>',<?php echo $_GET['lessonID']; ?>,<?php if(isset($_GET['type'])){echo $_GET['type'];}else{echo 0;} ?>,<?php if($sqlClassExerciseRecord==null){echo 1;}else{echo 0;}?>)"
                    <?php } else { ?>
                               href="#" onclick="suiteListenNext(<?php echo $listenType['exerciseID']?>,'<?php $arg= implode(',', $cent);echo $arg;?>',0,<?php if(isset($_GET['type'])){echo $_GET['type'];}else{echo 0;} ?>,<?php if($sqlClassExerciseRecord==null){echo 1;}else{echo 0;}?>)"
        <?php } ?>      title="<?php echo $listenType['title']; ?>"
                           >
                        
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
    <?php
    endforeach;
}
?>
        </ul>
    </div>
<?php if (count($exercise['choice']) == 0 && count($exercise['filling']) == 0 && count($exercise['question']) == 0 && count($exercise['key']) == 0 && count($exercise['look']) == 0 && count($exercise['listen']) == 0) { ?>
            <li class="nav-header">无内容</li>
<?php } else { ?>
            <li class="nav-header" ><br/></li>
            <li class="nav-header">
                <a type="button" href="#" class="btn btn-primary" style="width: 78%"  onclick="submitSuite();">提交</a>                 
            </li>
<?php } ?>
    </div>
</div>
<script>
    $(document).ready(function () {

        $("div.span3 div.well ul li").find("a").click(function () {
            var url = $(this).attr("href");
            if (url.indexOf("index.php") > 0) {
                $.post($('#klgAnswer').attr('action'), $('#klgAnswer').serialize(),
                        function (result) {
                            console.log(result);
                            window.location.href = url;
                        });
                return false;
            }
        });
    });
    
    function suiteKeyNext(exerID,cent,lessonID,flag,sqlClassExerciseRecord){
        var option = {
						title: "提示",
						btn: parseInt("0011",2),
						onOk: function(){
                                                    if(sqlClassExerciseRecord===1){
							saveToDateBaseNow();
                                                        saveData();
                                                    }
                                                        if(flag==1 || flag==2 || flag==3){
                                                           $.post($('#klgAnswer').attr('action'), $('#klgAnswer').serialize(), function () {
                    if (lessonID!=0) {
                        window.location.href = "./index.php?r=student/keyType&&exerID="+exerID+"&&cent="+cent+"&&lessonID="+lessonID+"&&type=4";
                    }
                    else{
                        window.location.href = "./index.php?r=student/keyType&&exerID="+exerID+"&&cent="+cent+"&&type=4";}
                });
                                                        }else{
                                                            if (lessonID!=0) {
                        window.location.href = "./index.php?r=student/keyType&&exerID="+exerID+"&&cent="+cent+"&&lessonID="+lessonID+"&&type=4";
                    }
                    else{
                        window.location.href = "./index.php?r=student/keyType&&exerID="+exerID+"&&cent="+cent+"&&type=4";}
                                                        }
                                                            
                    
						}
					};
					window.wxc.xcConfirm("您确定跳转至这题吗？", "custom", option);
    }
    
    function suiteLookNext(exerID,cent,lessonID,flag,sqlClassExerciseRecord){
        var option = {
						title: "提示",
						btn: parseInt("0011",2),
						onOk: function(){
                                                    if(sqlClassExerciseRecord===1){
							saveToDateBaseNow();
                                                        saveData();
                                                    }
                                                        if(flag==1 || flag==2 || flag==3){
                                                            $.post($('#klgAnswer').attr('action'), $('#klgAnswer').serialize(), function () {
                    if (lessonID!=0) {
                        window.location.href = "./index.php?r=student/lookType&&exerID="+exerID+"&&cent="+cent+"&&lessonID="+lessonID+"&&type=5";
                    }
                    else{
                        window.location.href = "./index.php?r=student/lookType&&exerID="+exerID+"&&cent="+cent+"&&type=5";}
                });
                                                        }else{
                                                            if (lessonID!=0) {
                        window.location.href = "./index.php?r=student/lookType&&exerID="+exerID+"&&cent="+cent+"&&lessonID="+lessonID+"&&type=5";
                    }
                    else{
                        window.location.href = "./index.php?r=student/lookType&&exerID="+exerID+"&&cent="+cent+"&&type=5";}
                                                        }
                                                            
                    
						}
					};
					window.wxc.xcConfirm("您确定跳转至这题吗？", "custom", option);
    }
    
    
    function suiteListenNext(exerID,cent,lessonID,flag,sqlClassExerciseRecord){
        var option = {
						title: "提示",
						btn: parseInt("0011",2),
						onOk: function(){
                                                    if(sqlClassExerciseRecord===1){
                                                	saveToDateBaseNow();
                                                        saveData();
                                                    }
                                                        if(flag==1 || flag==2 || flag==3){
                                                            $.post($('#klgAnswer').attr('action'), $('#klgAnswer').serialize(), function () {
                    if (lessonID!=0) {
                        window.location.href = "./index.php?r=student/listenType&&exerID="+exerID+"&&cent="+cent+"&&lessonID="+lessonID+"&&type=6";
                    }
                    else{
                        window.location.href = "./index.php?r=student/listenType&&exerID="+exerID+"&&cent="+cent+"&&type=6";}
                });
                                                        }else{
                                                            if (lessonID!=0) {
                        window.location.href = "./index.php?r=student/listenType&&exerID="+exerID+"&&cent="+cent+"&&lessonID="+lessonID+"&&type=6";
                    }
                    else{
                        window.location.href = "./index.php?r=student/listenType&&exerID="+exerID+"&&cent="+cent+"&&type=6";}
                                                        }
						}
					};
					window.wxc.xcConfirm("您确定跳转至这题吗？", "custom", option);
    }
</script>
