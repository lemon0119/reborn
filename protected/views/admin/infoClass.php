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
        <p >学生人数：<font class="normal_checked_font"><?php echo $nums; echo '&nbsp; &nbsp;';?></font>科目：<font class="normal_checked_font"> <?php echo $courseName; echo '&nbsp; &nbsp;';?></font> 当前进度：<font class="normal_checked_font"> <?php echo $lessonName; echo '&nbsp; &nbsp;';?></font>   
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
                        <td style="width: 120px"><?php echo $model['teacherID'];?></td>
                        <td><?php echo $teachers[$model['teacherID']];?></td>
                        <td style="width: 120px">
                            <a href="./index.php?r=admin/infoTea&&id=<?php echo $model['teacherID']; ?>&&name=<?php echo $teachers[$model['teacherID']]; ?>&&classID=<?php echo $classID; ?>"><img title="查看资料" src="<?php echo IMG_URL; ?>detail.png"></a>
                            <a href="./index.php?r=admin/infoClass&&flag=deleteTea&&id=<?php echo $model['teacherID'];?>&&classID=<?php echo $classID;?>"><img title="删除" src="<?php echo IMG_URL; ?>delete.png"></a>
                        </td>
                    </tr>            
                    <?php endforeach;?> 
                </tbody>
        </table>
        <h4>本班学生：</h4>
        <input type="checkbox" name="all" onclick="check_all(this, 'checkbox[]')" style="margin-bottom: 3px"> 全选　　批量操作：
        <a href="#" onclick="deleCheck()"><img title="批量删除" src="<?php echo IMG_URL; ?>delete.png"></a>
        <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="font-center">选择</th>
                <th>学号</th>
                <th>学生名</th>
                <th>操作</th>
            </tr>
        </thead>
                <tbody>     
                    <form id="deleForm" method="post" action="./index.php?r=admin/deleteStuInClass&&classID=<?php echo $model['classID'];?>">
                    <?php foreach($stus as $model):?>
                    <tr>
                         <td class="font-center" style="width: 50px"> <input type="checkbox" name="checkbox[]" value="<?php echo $model['userID']; ?>" /> </td>
                        <td style="width: 120px"><?php echo $model['userID'];?></td>
                        <td><?php echo $model['userName'];?></td>
                        <td style="width: 120px">  
                            <a href="./index.php?r=admin/infoStu&&id=<?php echo $model['userID']; ?>&&name=<?php echo $model['userName']; ?>&&classID=<?php echo $classID; ?>"><img title="详细资料" src="<?php echo IMG_URL; ?>detail.png"></a>
                            <a href="./index.php?r=admin/infoClass&&flag=deleteStu&&id=<?php echo $model['userID'];?>&&classID=<?php echo $model['classID'];?>"><img title="删除" src="<?php echo IMG_URL; ?>delete.png"></a>
                        </td>
                    </tr>            
                    <?php endforeach;?> 
                    </form>
                </tbody>
        </table>
        <div align=center>
        <?php
       $this->widget('CLinkPager', array('pages' => $pages_stu));
        ?>
    </div>
        <br/>
    <!-- 翻页标签结束 -->
    <div style="text-align: center">
        <button style="padding: 18px 37px;" class="btn_ret_admin"  onclick="back()"></button>
            <button class="btn_add_student_admin" onclick="addStuClass()"></button>
            <button class="btn_add_teacher_admin" onclick="addTeaClass()"></button>
        </div>
    <br/>
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
           window.wxc.xcConfirm(result, window.wxc.xcConfirm.typeEnum.success);
           result = null;
       }
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
           if(checkboxs[i].checked){
                flag=1;
                break;
           }
        } 
        if(flag===0){
           window.wxc.xcConfirm('未选中任何题目', window.wxc.xcConfirm.typeEnum.info);
        }else{
             var option = {
						title: "警告",
						btn: parseInt("0011",2),
						onOk: function(){
							$('#deleForm').submit();
						}
					};
					window.wxc.xcConfirm("确定删除选中的科目吗？", "custom", option);
        }
       
    }
       
   </script>

