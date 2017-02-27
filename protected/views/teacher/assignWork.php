<div class="span3">
    <div class="well-bottomnoradius" style="padding: 8px 0;">
	<li class="nav-header"><i class="icon-knowlage" style="position:relative;bottom:6px;left:"></i>班级列表</li>
	
        <ul class="nav nav-list" style = "overflow:auto;height:93px;margin-top:10px">                     
            

            <?php foreach ($array_class as $class): ?>
                <li <?php if (Yii::app()->session['currentClass'] == $class['classID']) echo "class='active'"; ?> ><a href="./index.php?r=teacher/assignWork&&classID=<?php echo $class['classID']; ?>"><i class="icon-list" style="position:relative;bottom:6px;left:"></i><?php echo $class['className']; ?></a></li>
            <?php endforeach; ?>

        </ul></div>
    <div class="well-topnoradius" style="padding: 8px 0;border-bottom-left-radius:0px;border-bottom-right-radius:0px;">
        <ul class="nav nav-list">    
            <li class="divider"></li>
            <?php //if (Yii::app()->session['currentClass'] && Yii::app()->session['currentLesson']) { ?>

                <li class="nav-header" ><i class="icon-knowlage" style="position:relative;bottom:6px;left:"></i>新建作业</li>
                <input name= "title" type="text" class="search-query span2" placeholder="作业题目" id="title" value="" />
                <li style="margin-top:10px">
                    <button onclick="chkIt()" class="btn_4superbig">创&nbsp;&nbsp;&nbsp;建</button>
                </li>
            <?php //} ?>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-knowlage" style="position:relative;bottom:6px;left:"></i>课时列表</li>
        </ul>
    </div>
    <div class="well-topnoradius" style="padding:0;height:303px;overflow:auto; top:-40px;">
        <ul class="nav nav-list">
            <?php foreach ($array_lesson as $lesson): ?>
                <li <?php if (Yii::app()->session['currentLesson'] == $lesson['lessonID']) echo "class='active'"; ?> ><a href="./index.php?r=teacher/assignWork&&classID=<?php echo Yii::app()->session['currentClass']; ?>&&lessonID=<?php echo $lesson['lessonID']; ?>"><i class="icon-list" style="position:relative;bottom:6px;left:"></i><?php echo $lesson['lessonName']; ?></a></li>
            <?php endforeach; ?> 
    </div>


</div>

<div class="span9" style="height: 574px">
    <h2>现有作业</h2>
    <input type="checkbox" name="all" onclick="check_all(this, 'checkbox[]')" style="margin-bottom: 3px"> 全选　　批量操作：
    <a href="#" onclick="deleCheck()"><img title="批量删除" src="<?php echo IMG_URL; ?>delete.png"></a>
    <!-- 键位习题列表-->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="font-center">选择</th>
                <th class="font-center">标题</th>
                <th class="font-center">键打</th>
                <th class="font-center">看打</th>
                <th class="font-center">听打</th>
                <th class="font-center">等级</th>
                <th class="font-center">状态</th>
                <th class="font-center">操作</th>               
            </tr>
        </thead>
        <tbody>     
        <form id="deleForm" method="post" action="./index.php?r=teacher/deleteSuite" > 
            <?php
            foreach ($array_allsuite as $suite):
                $classwork = array();
                $suitID = $suite['suiteID'];
                foreach (Tool::$EXER_TYPE as $type) {
                    $classwork[$type] = Suite::model()->getSuiteExerByType($suitID, $type);
                }

                $isOpen = false;
                
                
                
                $allOpen=false;
                if ($arrayall_suite) {
                    foreach ($arrayall_suite as $exitsuiteall)
                        if ($suite['suiteID'] == $exitsuiteall['suiteID'] && $exitsuiteall['open'] == 1) {
                            $allOpen = true;
                            break;
                        }
                }
                
                $all_suite_open=false;
                if($array_all_suite){
                    foreach($array_all_suite as $all_suite){
                        if($suite['suiteID']==$all_suite['suiteID']){
                            $all_suite_open=true;
                        }
                    }
                }
                
                ?>                    
                <tr>
                    <td class="font-center" style="width: 25px"> <?php if($all_suite_open == false){?> <input type="checkbox" name="checkbox[]" value="<?php echo $suite['suiteID']; ?>" /><?php }?> </td>
                    <td class="font-center  table_schedule" style="cursor: pointer;width: 100px" onclick="changeWorkName(<?php echo $suite['suiteID']; ?>, '<?php echo $suite['suiteName'] ?>')"><?php
                        if (Tool::clength($suite['suiteName']) <= 10)
                            echo $suite['suiteName'];
                        else
                            echo Tool::csubstr($suite['suiteName'], 0, 6) . "...";
                        ?></td>
                    <td class="font-center" style="width: 25px"><?php
                        if (count($classwork['key']) == 0) {
                            echo '-';
                        } else {
                            echo count($classwork['key']);
                        }
                        ?></td>
                    <td class="font-center" style="width: 25px"><?php
                        if (count($classwork['look']) == 0) {
                            echo '-';
                        } else {
                            echo count($classwork['look']);
                        }
                        ?></td>
                    <td class="font-center" style="width: 25px"><?php
                        if (count($classwork['listen']) == 0) {
                            echo '-';
                        } else {
                            echo count($classwork['listen']);
                        }
                        ?></td>
                    <td class="font-center" style="width: 104px">
                        <?php $suiteId = $suite['suiteID'];
                $classSuite = ClassLessonSuite::model()->findAll("suiteID = '$suiteId' and lessonID = '$thisLesson' and open='1'");
                $suiteCount = count($classSuite);
                $level = '';
                        if ($array_suite) {
                    foreach ($array_suite as $exitsuite){
                        if ($suite['suiteID'] == $exitsuite['suiteID'] && $exitsuite['open'] == 1) {
                            $isOpen = true;
                            if($suiteCount>1){
                                foreach ($classSuite as $su) {
                                    echo $su['level'].' ';
                                }
                            }else {
                                $level = $exitsuite['level'];
                                if($level == ''){
                                    echo '初级 中级 高级 未分组';
                                }else {
                                    echo $level;
                                }
                            }
                            break;
                        }
                }
               } ?>
                    </td>
                    <td class="font-center" style="width: 70px">
                        <?php if ($isOpen == false) { ?>
                        <a href="#" onclick="release(<?php echo $suite['suiteID']; ?>)" style="color:green">发布</a>
                            <font style="color:grey">关闭</font>
                        <?php } else { ?>
                            <font style="color:grey">发布</font>
                            <a href="#" style="color:red" onclick="changeSuite(<?php echo $suite['suiteID']; ?>)">关闭</a>
    <?php } ?>
                    </td>             
                    <td class="font-center" style="width: 85px">
                        <a href="./index.php?r=teacher/seeWork&&suiteID=<?php echo $suite['suiteID']; ?>"><img title="查看" src="<?php echo IMG_URL; ?>detail.png"></a>


            <?php if ($all_suite_open == false) { ?>
                            <a href="./index.php?r=teacher/modifyWork&&suiteID=<?php echo $suite['suiteID']; ?>&&type=key"><img title="修改作业内容" src="<?php echo IMG_URL; ?>edit.png"></a>
                            <a href="#" onclick="dele(<?php echo $suite['suiteID']; ?>,<?php echo $pages->currentPage + 1; ?>)"><img title="删除" src="<?php echo IMG_URL; ?>delete.png"></a>
            <?php } ?>


                    </td>
                </tr>            
<?php endforeach; ?> 
        </form>
        </tbody>
    </table>
    <div align=center>
<?php
$this->widget('CLinkPager', array('pages' => $pages));
?>
    </div>
</div>


<script>
    function changeSuite(suiteID){
           var thisLessonID = <?php echo $thisLesson;?>;
           var thisClassID = <?php echo $thisClass;?>;
           console.log(suiteID+"--"+thisLessonID+"----"+thisClassID)
          $.ajax({
            type: "POST",
            url: "index.php?r=api/changeSuiteType",
            async: false,
            data: { thisSuiteId: suiteID , thisLessonId :thisLessonID,thisClassId : thisClassID},
            success: function (data) {
            },
            error: function (xhr, type, exception) {
                console.log('GetAverageSpeed error', type);
                console.log(xhr, "Failed");
                console.log(exception, "exception");
            }
        });
        location.reload();
    }
    function release (suiteID) {
        window.open("./index.php?r=teacher/changeSuiteClassIn&&suiteID="+suiteID+"&&page=<?php echo $pages->currentPage + 1; ?>", 'newwindow', 'height=400,width=400,top=0,left=0,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no,left=500,top=200,');
    }
    function changeWorkName(workID, workName) {
        window.wxc.xcConfirm("原作业名为“" + workName + "”请重新命名：", window.wxc.xcConfirm.typeEnum.input, {
            onOk: function (v) {
                $.ajax({
                    type: 'POST',
                    url: './index.php?r=teacher/changeWorkName',
                    data: {workID: workID, newName: v},
                    success: function (data, textStatus, jqXHR) {
                        if (data != 0 && data !== '') {
                            window.wxc.xcConfirm('修改成功！', window.wxc.xcConfirm.typeEnum.success, {
                                onOk: function () {
                                    window.location.href = './index.php?r=teacher/assignWork&&classID=<?php echo Yii::app()->session['currentClass'];?>&&lessonID=<?php echo Yii::app()->session['currentLesson'];?>';
                                }
                            });

                        } else if (data == 0) {
                            window.wxc.xcConfirm('存在重名或非法名称', window.wxc.xcConfirm.typeEnum.error, {
                                onOk: function () {
                                    window.location.href = './index.php?r=teacher/assignWork&&classID=<?php echo Yii::app()->session['currentClass'];?>&&lessonID=<?php echo Yii::app()->session['currentLesson'];?>';
                                }
                            });
                        }
                        console.log('textStatus', textStatus);
                        console.log('jqXHR', jqXHR);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log('jqXHR', jqXHR);
                        console.log('textStatus', textStatus);
                        console.log('errorThrown', errorThrown);
                    }
                });
            }
        });
    }
    $(document).ready(function () {
        if (<?php echo $res; ?> == 1) {
            var txt = "此作业已经被创建！";
            document.getElementById("title").value = "";
            window.wxc.xcConfirm(txt, window.wxc.xcConfirm.typeEnum.info,{
                                onOk: function () {
                                    window.location.href = './index.php?r=teacher/assignWork&&classID=<?php echo Yii::app()->session['currentClass'];?>&&on=<?php echo Yii::app()->session['on'];?>';
                                }
                            });
            
        }
    });
    function check_all(obj, cName)
    {
        var checkboxs = document.getElementsByName(cName);
        for (var i = 0; i < checkboxs.length; i++) {
            checkboxs[i].checked = obj.checked;
        }
    }
    function deleCheck() {
        var checkboxs = document.getElementsByName('checkbox[]');
        var flag = 0;
        for (var i = 0; i < checkboxs.length; i++) {
            if (checkboxs[i].checked) {
                flag = 1;
                break;
            }
        }
        if (flag === 0) {
            window.wxc.xcConfirm('未选中任何作业', window.wxc.xcConfirm.typeEnum.info);
        } else {
            var option = {
                title: "警告",
                btn: parseInt("0011", 2),
                onOk: function () {
                    $('#deleForm').submit();
                }
            };
            window.wxc.xcConfirm("这将会删除此作业，您确定这样吗？", "custom", option);
        }
    }
    function dele(suiteID, currentPage)
    {

        var option = {
            title: "警告",
            btn: parseInt("0011", 2),
            onOk: function () {
                window.location.href = "./index.php?r=teacher/deleteSuite&&suiteID=" + suiteID + "&&page=" + currentPage;
            }
        }
        window.wxc.xcConfirm("您确定删除吗？", "custom", option);
    }


    function chkIt() {
        var usernameVal = document.getElementById("title").value;
        if (usernameVal == "") {
            window.wxc.xcConfirm("题目不能为空", window.wxc.xcConfirm.typeEnum.info);
            return false;
        }
        if (usernameVal.length > 30) { //一个汉字算一个字符  
            window.wxc.xcConfirm("大于30个字符", window.wxc.xcConfirm.typeEnum.info);
            document.getElementById("title").value = "";
            return false;
        }
        window.location.href = "./index.php?r=teacher/AddSuite&&title=" + usernameVal+"&&classID=<?php echo Yii::app()->session['currentClass'];?>&&on=<?php echo Yii::app()->session['on'];?>";
    }
</script>


