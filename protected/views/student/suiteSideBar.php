<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script src="<?php echo JS_URL; ?>exerJS/AnalysisTool.js"></script>
<div class="span3">
    <div class="well" style="padding: 8px 0;">
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
                            ?>&&lessonID=<?php echo $_GET['lessonID']; ?>"
                        <?php } else { ?>
                            href="./index.php?r=student/choice&&cent=<?php
                            $arg = implode(',', $cent);
                            echo $arg;
                            ?>"
    <?php } ?>
                        ><i class="icon-font"></i> 选 择 题<div id= "container" style="height: 5px;border:1px solid white;">
                            <div id="progress-bar" style="width:<?php echo "$cent[0]"; ?>;background-color:springgreen;height:5px;">
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
                            ?>&&lessonID=<?php echo $_GET['lessonID']; ?>"
                        <?php } else { ?>
                            href="./index.php?r=student/filling&&cent=<?php
                    $arg = implode(',', $cent);
                    echo $arg;
                    ?>"
    <?php } ?>
                        ><i class="icon-text-width"></i> 填 空 题<div id= "container" style="height: 5px;border:1px solid white;">
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
                            ?>&&lessonID=<?php echo $_GET['lessonID']; ?>"
    <?php } else { ?>
                            href="./index.php?r=student/question&&cent=<?php
        $arg = implode(',', $cent);
        echo $arg;
        ?>"
                <?php } ?>
                        ><i class="icon-align-left"></i> 简 答 题<div id= "container" style="height: 5px;border:1px solid white;">
                            <div id="progress-bar" style="width:<?php echo "$cent[2]"; ?>;background-color: springgreen;height:5px;">
                            </div>
                        </div> </a>
                </li>
                    <?php } if (count($exercise['key']) != 0) { ?>
                <li class="nav-header">键打练习</li>
                        <?php foreach ($exercise['key'] as $keyType) : ?>
                    <li id="li-key-<?php echo $keyType['exerciseID']; ?>">
                        <a 
                            <?php if (isset($_GET['lessonID'])) { ?>
                                href="./index.php?r=student/keyType&&exerID=<?php echo $keyType['exerciseID'] ?>&&cent=<?php
                                $arg = implode(',', $cent);
                                echo $arg;
                                ?>&&lessonID=<?php echo $_GET['lessonID']; ?>"
                            <?php } else { ?>
                                href="./index.php?r=student/keyType&&exerID=<?php echo $keyType['exerciseID'] ?>&&cent=<?php
                    $arg = implode(',', $cent);
                    echo $arg;
                                ?>"
                    <?php } ?>
                            >
                            <i class="icon-th"></i>
        <?php echo $keyType['title'] ?>
                        </a>
                    </li>
                       <?php
                       endforeach;
                   } if (count($exercise['look']) != 0) {
                       ?>
                <li class="nav-header">看打练习</li>
                       <?php foreach ($exercise['look'] as $lookType) : ?>
                    <li id="li-look-<?php echo $lookType['exerciseID']; ?>">
                    <a <?php if (isset($_GET['lessonID'])) { ?> href="#" onclick="suiteLookNext(<?php echo $lookType['exerciseID']?>,'<?php $arg= implode(',', $cent);echo $arg;?>',<?php echo $_GET['lessonID']; ?>)"
                    <?php } else { ?>
                               href="#" onclick="suiteLookNext(<?php echo $lookType['exerciseID']?>,'<?php $arg= implode(',', $cent);echo $arg;?>',0)"
        <?php } ?>
                           >
                            <i class="icon-eye-open"></i>
                            <?php echo $lookType['title'] ?>
                        </a>
                    </li>
                        <?php
                        endforeach;
                    } if (count($exercise['listen']) != 0) {
                        ?>
                <li class="nav-header">听打练习</li>
    <?php foreach ($exercise['listen'] as $listenType) : ?>
                    <li id="li-listen-<?php echo $listenType['exerciseID']; ?>">
                        
                        <a <?php if (isset($_GET['lessonID'])) { ?> href="#" onclick="suiteListenNext(<?php echo $listenType['exerciseID']?>,'<?php $arg= implode(',', $cent);echo $arg;?>',<?php echo $_GET['lessonID']; ?>)"
                    <?php } else { ?>
                               href="#" onclick="suiteListenNext(<?php echo $listenType['exerciseID']?>,'<?php $arg= implode(',', $cent);echo $arg;?>',0)"
        <?php } ?>
                           >
                        
                            <i class="icon-headphones"></i> 
                <?php echo $listenType['title'] ?>
                        </a>
                    </li>                       
    <?php
    endforeach;
}
?>
        </ul>
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
    
    function suiteListenNext(exerID,cent,lessonID){
        var option = {
						title: "提示",
						btn: parseInt("0011",2),
						onOk: function(){
							saveToDateBaseNow();
                                                            $.post('index.php?r=student/overSuite&&isExam=<?php echo $isExam; ?>', function () {
                    if (lessonID!=0) {
                        window.location.href = "index.php?r=student/listenType&&exerID="+exerID+"&&cent="+cent+"&&lessonID"+lessonID;
                    }
                    else{
                        window.location.href = "index.php?r=student/listenType&&exerID="+exerID+"&&cent="+cent;}
                });
                    
						}
					};
					window.wxc.xcConfirm("您确定跳转至这题吗？", "custom", option);
    }
    
    function suiteLookNext(exerID,cent,lessonID){
        var option = {
						title: "提示",
						btn: parseInt("0011",2),
						onOk: function(){
							saveToDateBaseNow();
                                                            $.post('index.php?r=student/overSuite&&isExam=<?php echo $isExam; ?>', function () {
                    if (lessonID!=0) {
                        window.location.href = "index.php?r=student/lookType&&exerID="+exerID+"&&cent="+cent+"&&lessonID"+lessonID;
                    }
                    else{
                        window.location.href = "index.php?r=student/lookType&&exerID="+exerID+"&&cent="+cent;}
                });
                    
						}
					};
					window.wxc.xcConfirm("您确定跳转至这题吗？", "custom", option);
    }
    
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
</script>
