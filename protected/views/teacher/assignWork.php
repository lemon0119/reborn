 <div class="span3">
        <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">                     
                        <li class="nav-header">班级列表</li>
                        
                        <?php foreach($array_class as $class):?>
                        <li <?php if(Yii::app()->session['currentClass'] == $class['classID']) echo "class='active'";?> ><a href="./index.php?r=teacher/assignWork&&classID=<?php echo $class['classID'];?>"><i class="icon-list"></i><?php echo $class['className'];?></a></li>
                        <?php endforeach;?>
                        
                        <li class="divider"></li>
                        <li class="nav-header">课程列表</li>
                       
                        <?php foreach($array_lesson as $lesson):?>
                        <li <?php if(Yii::app()->session['currentLesson'] == $lesson['lessonID']) echo "class='active'";?> ><a href="./index.php?r=teacher/assignWork&&classID=<?php echo Yii::app()->session['currentLesson'];?>&&lessonID=<?php echo $lesson['lessonID'];?>"><i class="icon-list"></i><?php echo $lesson['lessonName'];?></a></li>
                        <?php endforeach;?>                        
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
                <th>班级</th>
                <th>课程</th>
                <th>状态</th>
                <th>操作</th>               
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($array_suite as $suite):?>
                    <tr>
                        <td style="width: 150px"><?php echo $suite['suiteName'];?></td>
                        <td><?php foreach ($array_class as $class) if(Yii::app()->session['currentClass'] == $class['classID']) echo $class['className'];
                            ?></td>
                        <td><?php  foreach ($array_lesson as $lesson) if(Yii::app()->session['currentLesson'] == $lesson['lessonID']) echo $lesson['lessonName'];?></td>
                        <td>
                             <a href="#">开放</a>
                              <a href="#">关闭</a>
                        </td>             
                        <td>
                            <a href="./index.php?r=teacher/modifyWork&&suiteID=<?php echo $suite['suiteID'];?>&&type=choice"><img src="<?php echo IMG_URL; ?>detail.png">修改</a>
                            <a href="./index.php?r=teacher/deleteWork&&suiteID=<?php echo $suite['suiteID'];?>"><img src="<?php echo IMG_URL; ?>edit.png">删除</a>                            
                        </td>
                    </tr>            
                    <?php endforeach;?> 
                </tbody>
    </table>
    <!-- 学生列表结束 -->
 
    </div>




