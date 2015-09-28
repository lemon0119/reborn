<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">                     
            <li class="nav-header">班级列表</li>

            <?php foreach ($array_class as $class): ?>
                <li <?php if (Yii::app()->session['currentClass'] == $class['classID']) echo "class='active'"; ?> ><a href="./index.php?r=teacher/assignWork&&classID=<?php echo $class['classID']; ?>"><i class="icon-list"></i><?php echo $class['className']; ?></a></li>
            <?php endforeach; ?>

            <li class="divider"></li>
            <li class="nav-header">课程列表</li>

            <?php foreach ($array_lesson as $lesson): ?>
                <li <?php if (Yii::app()->session['currentLesson'] == $lesson['lessonID']) echo "class='active'"; ?> ><a href="./index.php?r=teacher/assignWork&&classID=<?php echo Yii::app()->session['currentClass']; ?>&&lessonID=<?php echo $lesson['lessonID']; ?>"><i class="icon-list"></i><?php echo $lesson['lessonName']; ?></a></li>
            <?php endforeach; ?>   
            <li class="divider"></li>
            <form id="myForm" action="./index.php?r=teacher/AddSuite" method="post" >  
                <li class="nav-header" >作业题目</li>
                <input name= "title" type="text" class="search-query span2" placeholder="作业题目" id="title" value="" />
                <li style="margin-top:10px">
                    <button type="submit" class="btn btn-primary">创建作业</button>
                </li>
            </form>
        </ul>
    </div>

</div>

<div class="span9">
    <h2>现有作业</h2>
    <!-- 键位习题列表-->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>标题</th>
                <th>状态</th>
                <th>操作</th>               
            </tr>
        </thead>
        <tbody>        
            <?php
            foreach ($array_allsuite as $suite):
                $isOpen = false;
                foreach ($array_suite as $exitsuite)
                    if ($suite['suiteID'] == $exitsuite['suiteID']) {
                        $isOpen = true;
                        break;
                    }
                ?>                    
                <tr>
                    <td style="width: 150px"><?php echo $suite['suiteName']; ?></td>
                    <td>
                        <?php if ($isOpen == false) { ?>
                            <a href="./index.php?r=teacher/ChangeSuiteClass&&suiteID=<?php echo $suite['suiteID']; ?>&&isOpen=0&&page=<?php echo $pages->currentPage + 1; ?>" style="color:green">开放</a>
                            <font style="color:grey">关闭</font>
                        <?php } else { ?>
                            <font style="color:grey">开放</font>
                            <a href="./index.php?r=teacher/ChangeSuiteClass&&suiteID=<?php echo $suite['suiteID']; ?>&&isOpen=1&&page=<?php echo $pages->currentPage + 1;  ?>" style="color:red">关闭</a>
                        <?php } ?>
                    </td>             
                    <td>
                        <a href="./index.php?r=teacher/seeWork&&suiteID=<?php echo $suite['suiteID']; ?>"><img title="查看" src="<?php echo IMG_URL; ?>detail.png"></a>

                        <a href="./index.php?r=teacher/modifyWork&&suiteID=<?php echo $suite['suiteID']; ?>&&type=choice"><img title="修改作业内容" src="<?php echo IMG_URL; ?>edit.png"></a>
                        <a href="#" onclick="dele(<?php echo $suite['suiteID']; ?>,<?php echo $pages->currentPage + 1; ?>)"><img title="删除" src="<?php echo IMG_URL; ?>delete.png"></a>

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
    function dele(suiteID, currentPage)
    {
        if (confirm("您确定删除吗？")) {
            window.location.href = "./index.php?r=teacher/deleteSuite&&suiteID=" + suiteID + "&&page=" + currentPage;
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


