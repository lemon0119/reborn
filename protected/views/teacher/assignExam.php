<script src="<?php echo JS_URL; ?>/My97DatePicker"></script>

<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">                     
            <li class="nav-header">班级列表</li>

            <?php foreach ($array_class as $class): ?>
                <li <?php if (Yii::app()->session['currentClass'] == $class['classID']) echo "class='active'"; ?> ><a href="./index.php?r=teacher/assignExam&&classID=<?php echo $class['classID']; ?>"><i class="icon-list"></i><?php echo $class['className']; ?></a></li>
            <?php endforeach; ?>  
            <form id="myForm" action="./index.php?r=teacher/AddExam" method="post" >  
                <li class="divider"></li>
                <li class="nav-header">试卷标题</li>
                <li style="margin-top:10px">
                    <input name= "title" id="title" type="text" class="search-query span2"  placeholder="试卷标题" value="" />
                </li>
                <li style="margin-top:10px">
                <button type="submit" class="btn btn-primary">创建试卷</button>
                </li>
            </form>
        </ul>
    </div>

</div>

<div class="span9">
    <h2>现有试卷列表</h2>
    <!-- 键位习题列表-->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>标题</th> 
                <th>开始时间</th>
                <th>结束时间</th>
                <th>时长</th>    
                <th>状态</th> 
                <th>操作</th> 
            </tr>
        </thead>
        <tbody>        
            <?php
            foreach ($array_allexam as $exam):
                $isOpen = false;
                foreach ($array_exam as $exitexam)
                    if ($exam['examID'] == $exitexam['examID']) {
                        $isOpen = true;
                        break;
                    }
                ?>                    
                <tr>
                    <td style="width: 130px"><?php echo $exam['examName']; ?></td>                        

                    <td>
                        <?php echo $exam['begintime'] ?>
                    </td>
                    <td>
                        <?php echo $exam['endtime'] ?>
                    </td>
                    <td>
                        <?php echo $exam['duration'] . "分钟" ?>
                    </td>
                    <td>
                        <?php if ($isOpen == false) { ?>
                        <a href="./index.php?r=teacher/ChangeExamClass&&examID=<?php echo $exam['examID']; ?>&&isOpen=0&&page=<?php echo $pages->currentPage + 1; ?>" style="color: green">开放</a>
                            <font style="color:red">关闭</font>
                        <?php } else { ?>
                            <font style="color:red">开放</font>
                            <a href="./index.php?r=teacher/ChangeExamClass&&examID=<?php echo $exam['examID']; ?>&&isOpen=1&&page=<?php echo $pages->currentPage + 1; ?>" style="color: green">关闭</a>
                        <?php } ?>  
                    </td>   

                    <td>
                        <a href="./index.php?r=teacher/modifyExam&&examID=<?php echo $exam['examID']; ?>&&type=choice"><img src="<?php echo IMG_URL; ?>edit.png"></a>
                        <a href="#" onclick="dele(<?php echo $exam['examID']; ?>,<?php echo $pages->currentPage + 1; ?>)"><img src="<?php echo IMG_URL; ?>delete.png"></a> 
                        <a href="#" onclick="dele(<?php echo $exam['examID']; ?>,<?php echo $pages->currentPage + 1; ?>)"><img src="<?php echo IMG_URL; ?>edit.png">立即开始</a> 
                    </td>
                </tr>            
            <?php endforeach; ?> 
        </tbody>
        
    </table>
    <div align=center>
    <?php
    $this->widget('CLinkPager', array('pages' => $pages));
    ?>
</div>
</div>


<script>
    function dele(examID, currentPage)
    {
        if (confirm("您确定删除吗？")) {
            window.location.href = "./index.php?r=teacher/deleteExam&&examID=" + examID + "&&page=" + currentPage;
        }
    }

    $("#myForm").submit(function(){
        var title = $("#title")[0].value;
        if (title == "")
        {
            alert("题目不能为空");
            return false;
        }
    });

</script>


