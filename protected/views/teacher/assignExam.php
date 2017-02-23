<!--<script src="<?php //echo JS_URL; ?>/My97DatePicker"></script>-->

<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">                     
            <li class="nav-header"><i class="icon-knowlage" style="position:relative;bottom:5px;left:"></i>班级列表</li>

            <?php foreach ($array_class as $class): ?>
                <li <?php if (Yii::app()->session['currentClass'] == $class['classID']) echo "class='active'"; ?> ><a href="./index.php?r=teacher/assignExam&&classID=<?php echo $class['classID']; ?>"><i class="icon-list" style="position:relative;bottom:7px;left:"></i><?php echo $class['className']; ?></a></li>
            <?php endforeach; ?>  

            <li class="divider"></li>
            <li class="nav-header"><i class="icon-knowlage" style="position:relative;bottom:6px;left:"></i>试卷标题</li>
            <li style="margin-top:10px">
                <input name= "title" id="title" type="text" class="search-query span2"  placeholder="试卷标题" value=""/>
            </li>
            <li style="margin-top:10px">
                <button onclick="chkIt()" class="btn_4superbig">创&nbsp;&nbsp;&nbsp;建</button>
            </li>
        </ul>
    </div>
</div>
<div class="span9">
    <h2>现有试卷列表</h2>
    <input type="checkbox" name="all" onclick="check_all(this, 'checkbox[]')" style="margin-bottom: 3px"> 全选　　批量操作：
    <a href="#" onclick="deleCheck()"><img title="批量删除" src="<?php echo IMG_URL; ?>delete.png"></a>
    <!-- 键位习题列表-->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="font-center">选择</th>
                <th class="font-center">标题</th> 
                <th class="font-center">开始时间</th>
                <th class="font-center">时长</th>
                <th class="font-center">题量</th>
                <th class="font-center">状态</th> 
                <th class="font-center">操作</th> 
            </tr>
        </thead>
        <tbody>     
        <form id="deleForm" method="post" action="./index.php?r=teacher/deleteExam" > 
            <?php
            foreach ($array_allexam as $exam):
                $isOpen = false;
                $exam_is_open=false;
                foreach ($array_exam as $exitexam)
                    if ($exam['examID'] == $exitexam['examID']) {
                        $isOpen = true;
                        break;
                    }
                    
                foreach($array_exam_open as $examOpen){
                    if($exam['examID']==$examOpen['examID']){
                        $exam_is_open=true;
                    }
                }
                ?>                    
                <tr>
                    <td class="font-center" style="width: 40px"><?php if($exam_is_open==false){?><input type="checkbox" name="checkbox[]" value="<?php echo $exam['examID']; ?>" /><?php } ?> </td>
                    <td class="font-center table_schedule" style="cursor: pointer;width: 100px" onclick="changeExameName(<?php echo $exam['examID']; ?>, '<?php echo str_replace('"',"’",str_replace("'","’",$exam['examName'])); ?>')"><?php echo $exam['examName']; ?></td>                        
                    <td class="font-center">
                        <?php
                        if ($isOpen == false) {
                            echo "-";
                        } else {
                            echo $exam['begintime'];
                        }
                        ?>
                    </td>
                    <td class="font-center">
                        <?php echo $exam['duration'] . "分钟" ?>
                    </td>
                    <td class="font-center"><?php
                        $num = ExamExercise::model()->getCountExercise($exam['examID']);
                        echo $num;
                        ?></td>
                    <td class="font-center">
    <?php if ($isOpen == false) { ?>
                            <a href="#"  onclick="openExam(<?php echo $exam['examID']; ?>,<?php echo $exam['duration'] ?>, '<?php echo date("Y-m-d H:i:s", time()); ?>')" style="color: green" >预约</a>
                            <font style="color:grey">关闭</font>
    <?php } else { ?>
                            <font style="color:grey">发布</font>
                            <a href="./index.php?r=teacher/ChangeExamClass&&examID=<?php echo $exam['examID']; ?>&&duration=<?php echo $exam['duration']; ?>&&beginTime=<?php echo $exam['begintime']; ?>&&isOpen=1&&page=<?php echo $pages->currentPage + 1; ?>" style="color: red">关闭</a>
    <?php } ?>  
                    </td>   
                    <td class="font-center" style="width: 170px">
    <?php if ($exam_is_open == false) { ?>
                            <a href="./index.php?r=teacher/modifyExam&&examID=<?php echo $exam['examID']; ?>&&type=key"><img title="调整试卷" src="<?php echo IMG_URL; ?>edit.png"></a>
                            <a href="#" onclick="dele(<?php echo $exam['examID']; ?>,<?php echo $pages->currentPage + 1; ?>,<?php echo Yii::app()->session['currentClass']; ?>)"><img title="删除试卷" src="<?php echo IMG_URL; ?>delete.png"></a> 
                            <?php } ?>
                        <?php if ($isOpen == false) { ?>
                            <a href="./index.php?r=teacher/setTimeAndScoreExam&&examID=<?php echo $exam['examID']; ?>&&duration=<?php echo $exam['duration'] ?>&&beginTime='<?php echo date("Y-m-d H:i:s", time()); ?>'&&isOpen=0&&page=<?php echo $pages->currentPage + 1; ?>&&flag=<?php echo $flag; ?>" id ="beginnow" ></a> 
                        <?php } ?>
<!--                        <a href="./index.php?r=teacher/setTimeAndScoreExam&&examID=<?php// echo $exam['examID']; ?>"><img title="配置分数时间" src="<?php //echo IMG_URL; ?>../UI_tea/icon_SETUP.png"></a>-->
    <?php ?>
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
    function changeExameName(examID,examName) {
        window.wxc.xcConfirm("原题目名为“" + examName + "”请重新命名：", window.wxc.xcConfirm.typeEnum.input, {
            onOk: function (v) {
                $.ajax({
                    type: 'POST',
                    url: './index.php?r=teacher/changeExamName',
                    data: {examID: examID, newName: v},
                    success: function (data, textStatus, jqXHR) {
                        if (data != 0 && data !== '') {
                            window.wxc.xcConfirm('修改成功！', window.wxc.xcConfirm.typeEnum.success, {
                                onOk: function () {
                                    window.location.href = './index.php?r=teacher/assignExam';
                                }
                            });

                        } else if (data == 0) {
                            window.wxc.xcConfirm('存在重名或非法名称', window.wxc.xcConfirm.typeEnum.error, {
                                onOk: function () {
                                    window.location.href = './index.php?r=teacher/assignExam';
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
            var txt = "此试卷已经被创建！";
            window.wxc.xcConfirm(txt, window.wxc.xcConfirm.typeEnum.info, {
                                onOk: function () {
                                    window.location.href = './index.php?r=teacher/assignExam';
                                }
                            });
            document.getElementById("title").value = "";
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
            window.wxc.xcConfirm('未选中任何试卷', window.wxc.xcConfirm.typeEnum.info);
        } else {
            var option = {
                title: "警告",
                btn: parseInt("0011", 2),
                onOk: function () {
                    $('#deleForm').submit();
                }
            };
            window.wxc.xcConfirm("这将会删除此试卷，您确定这样吗？", "custom", option);
        }
    }
    function dele(examID, currentPage, classID)
    {
        var option = {
            title: "警告",
            btn: parseInt("0011", 2),
            onOk: function () {
                window.location.href = "./index.php?r=teacher/deleteExam&&examID=" + examID + "&&classID=" + classID + "&&page=" + currentPage;
            }
        }
        window.wxc.xcConfirm("您确定删除吗？", "custom", option);
    }


    function openExam(examID, duration, begintime)
    {
        var begin = begintime;
        var txt = "请输入预定考试时长...";
        var totalAudioTime = 0;
        window.wxc.xcConfirm(txt, window.wxc.xcConfirm.typeEnum.input, {
            onOk: function (v) {
                if(!v.match(/^[0-9]+$/) || v == 0 ||v>720) {
                    window.wxc.xcConfirm('非法时长！不得超出720分钟！', window.wxc.xcConfirm.typeEnum.error, {
                        onOk: function () {
                            openExam(examID, duration, begintime);
                        }
                    });
                } else {
                    var beginTime = prompt("开始时间", begintime);
                    if (beginTime)
                    {
                        if (beginTime < begin) {
                            window.wxc.xcConfirm("开始时间不能小于当前时间！", window.wxc.xcConfirm.typeEnum.confirm);
                            return;
                        } else {
                              window.location.href = "./index.php?r=teacher/setTimeAndScoreExam&&examID="+examID+ "&&duration=" + v + "&&beginTime=" + beginTime + "&&isOpen=0&&page=" +<?php echo $pages->currentPage + 1; ?>+"&&flag=1";  
//                            window.location.href = "./index.php?r=teacher/ChangeExamClass&&examID=" + examID + "&&duration=" + v + "&&beginTime=" + beginTime + "&&isOpen=0&&page=" +<?php //echo $pages->currentPage + 1; ?>;
                        }
                    } else {
                        return;
                    }
                }
            }
        });
    }

    function begin_now(examID, d, time, isOpen)
    {
        var begin = time;
        var txt = "请输入预定考试时长...";
        window.wxc.xcConfirm(txt, window.wxc.xcConfirm.typeEnum.input, {
            onOk: function (v) {
                if (!v.match(/^[0-9]+$/) || v == 0||v>720) {
                    window.wxc.xcConfirm('非法时长！不得超出720分钟！', window.wxc.xcConfirm.typeEnum.error);
                } else {
                    window.wxc.xcConfirm("你确定要立即开始？", window.wxc.xcConfirm.typeEnum.info, {
                        onOk: function () {
                            $.ajax({
                                type: "POST",
                                url: "index.php?r=api/putNotice2&&class=<?php echo Yii::app()->session['currentClass'] ?>",
                                data: {title: "考试", content: "考试时间已经到了，可以开始考试了"},
                                success: function () {
                                      window.location.href = "./index.php?r=teacher/setTimeAndScoreExam&&examID="+examID+ "&&duration=" + v + "&&beginTime=" + begin + "&&isOpen=0&&page=" +<?php echo $pages->currentPage + 1; ?>;  
//                                    window.location.href = "./index.php?r=teacher/ChangeExamClass&&examID=" + examID + "&&duration=" + v + "&&beginTime=" + begin + "&&isOpen=0&&page=" +<?php// echo $pages->currentPage + 1; ?>;
                                },
                                error: function (xhr, type, exception) {
                                    window.wxc.xcConfirm('出错了a...', window.wxc.xcConfirm.typeEnum.error);
                                    console.log(xhr.responseText, "Failed");
                                }
                            });

                        }
                    });
                }
            }
        });
    }


    function chkIt() {
        var usernameVal = document.getElementById("title").value;
        if (usernameVal == "") {
            window.wxc.xcConfirm("题目不能为空", window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }
        if (usernameVal.length > 30) { //一个汉字算一个字符  
            window.wxc.xcConfirm("大于30个字符", window.wxc.xcConfirm.typeEnum.warning);
            document.getElementById("title").value = "";
            return false;
        }
        window.location.href = "./index.php?r=teacher/AddExam&&title=" + usernameVal;
    }
</script>


