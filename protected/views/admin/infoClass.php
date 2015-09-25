<?php require 'classLstSideBar.php';?>


    <?php
    $course= Course::model()->find("courseID = '$curCourse'");
    $courseName=$course['courseName'];
    if($curLesson==0)
        $lessonName="尚未开始";
    else{
        $lesson= Lesson::model()->find("classID = '$classID' AND number = '$curLesson'");
        $lessonName=$lesson['lessonName'];
    }
    ?>

<div class="span9">
        <?php
        //得到老师ID对应的名称
        foreach ($teacher as $model):
        $teacherID=$model['userID'];
        $teachers["$teacherID"]=$model['userName'];
        endforeach;
        ?>
            
        <h3><?php echo $classID; echo '&nbsp; &nbsp;'; echo $className;?></h3>
        <p >学生人数：<font class="normal_checked_font"><?php echo $nums; echo '&nbsp; &nbsp;';?></font>课程：<font class="normal_checked_font"> <?php echo $courseName; echo '&nbsp; &nbsp;';?></font> 当前进度：<font class="normal_checked_font"> <?php echo $lessonName; echo '&nbsp; &nbsp;';?></font>   
        </p>
        <h4>任课老师：</h4>
         <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>工号</th>
                <th>老师名</th>
                <th>操作</th>
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($teacherOfClass as $model):?>
                    <tr>
                        <td style="width: 75px"><?php echo $model['teacherID'];?></td>
                        <td><?php echo $teachers[$model['teacherID']];?></td>
                        <td>
                            <a href="./index.php?r=admin/infoTea&&id=<?php echo $model['teacherID']; ?>&&name=<?php echo $teachers[$model['teacherID']]; ?>&&classID=<?php echo $classID; ?>"><img title="查看资料" src="<?php echo IMG_URL; ?>detail.png"></a>
                            <a href="./index.php?r=admin/infoClass&&flag=deleteTea&&id=<?php echo $model['teacherID'];?>&&classID=<?php echo $classID;?>"><img title="删除" src="<?php echo IMG_URL; ?>delete.png"></a>
                        </td>
                    </tr>            
                    <?php endforeach;?> 
                </tbody>
        </table>
        <h4>本班学生：</h4>
        <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>学号</th>
                <th>学生名</th>
                <th>操作</th>
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($stus as $model):?>
                    <tr>
                        <td style="width: 75px"><?php echo $model['userID'];?></td>
                        <td><?php echo $model['userName'];?></td>
                        <td>  
                            <a href="./index.php?r=admin/infoStu&&id=<?php echo $model['userID']; ?>&&name=<?php echo $model['userName']; ?>&&classID=<?php echo $classID; ?>"><img title="详细资料" src="<?php echo IMG_URL; ?>detail.png"></a>
                            <a href="./index.php?r=admin/infoClass&&flag=deleteStu&&id=<?php echo $model['userID'];?>&&classID=<?php echo $model['classID'];?>"><img title="删除" src="<?php echo IMG_URL; ?>delete.png"></a>
                        </td>
                    </tr>            
                    <?php endforeach;?> 
                </tbody>
        </table>
    <!-- 翻页标签结束 -->
        <div class="form-actions">
            <button class="btn btn-primary"  onclick="back()">确定</button>
            <button class="btn" onclick="addStuClass()">添加学生</button>
            <button class="btn" onclick="addTeaClass()">添加老师</button>
        </div>
        <script>
        function back()
        {
             <?php if(Yii::app()->session['lastUrl']=="infoClass"){?>
                window.location.href="./index.php?r=admin/classLst&&page=<?php echo Yii::app()->session['lastPage'];?>";
             <?php } else { ?>
                window.location.href="./index.php?r=admin/searchClass&&page=<?php echo Yii::app()->session['lastPage'];?>";
             <?php }?>
        }
        function addStuClass()
        {
             window.location.href="./index.php?r=admin/addStuClass&&classID=<?php echo $classID;?>";
        }
        function addTeaClass()
        {
            window.location.href="./index.php?r=admin/addTeaClass&&classID=<?php echo $classID;?>";
        }
        </script>
        </div>

    
    <?php
   //显示操作结果
   if(isset($result))
   {
       if(!empty($result))
       {
           echo "<script>var result = '$result';</script>";
       }
   }
    ?>
   <script>
       if(result != null){
           alert(result);
           result = null;
       }
   </script>

