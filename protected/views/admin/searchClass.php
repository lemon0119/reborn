<?php require 'classLstSideBar.php';?>

    <?php
    //得到每个班级的对应人数
    foreach ($nums as $model):
    $numOfClass[$model['classID']]=$model['count(classID)'];
    endforeach;
    //得到老师ID对应的名称
    foreach ($teacher as $model):
    $teacherID=$model['userID'];
    $teachers["$teacherID"]=$model['userName'];
    endforeach;
    //老师与班级对应的量
    $corr=$teacherOfClass->read();
    $courses= Course::model()->findall();
    foreach ($courses as $key => $value) {
        $couID = $value['courseID'];
        $courseName[$couID]=$value["courseName"];
    }
    ?>

<div class="span9">
    <?php if($posts->count()!=0){?>
    <h2>班级列表</h2>
    <!-- 班级列表-->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="font-center">班号</th>
                <th class="font-center">班级名</th>
                <th class="font-center">老师</th>
                <th class="font-center">人数</th>
                <th class="font-center">科目</th>
                <th class="font-center">操作</th>
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($posts as $model):?>
                    <tr>
                        <td class="font-center" style="width: 75px"><?php echo $model['classID'];?></td>
                        <td class="font-center"><?php echo $model['className'];?></td>
                        <td class="font-center"><?php 
                                while (!empty($corr)&&$corr['classID']<=$model['classID'])
                                {
                                    if($corr['classID']<$model['classID'])
                                    {
                                        $corr=$teacherOfClass->read();
                                    }else if ($corr['classID'] = $model['classID']) {
                                        $teacherID = $corr['teacherID'];
                                        $teacherName = $teachers["$teacherID"];
                                        echo "<a href=\"./index.php?r=admin/infoTea&&id=$teacherID&&name=$teacherName&&flag=2\">$teacherName</a>";
                                        echo '&nbsp;';
                                        $corr = $teacherOfClass->read();
                                    }
                                }
                            ?></td>
                        <td class="font-center"><?php  if(isset($numOfClass) && array_key_exists($model['classID'], $numOfClass))
                                        echo  $numOfClass[$model['classID']];
                                    else echo "0";
                            ?></td>
                        <td class="font-center"><?php $couID = $model['currentCourse']; echo $courseName[$couID];?></td>
                        <td class="font-center" style="width: 100px">  
                            <a href="./index.php?r=admin/infoClass&&classID=<?php echo $model['classID']; ?>"><img title="管理" src="<?php echo IMG_URL; ?>detail.png"></a>
                        </td>
                    </tr>            
                    <?php endforeach;?> 
                </tbody>
    </table>
    <!-- 班级列表结束 -->
    <!-- 显示翻页标签 -->
    <div align=center>
    <?php   
        $this->widget('CLinkPager',array('pages'=>$pages));
    ?>
    <!-- 翻页标签结束 -->
   
    </div>

    <!-- 右侧内容展示结束-->
    <?php } else {?>
    <h2>查询结果为空！</h2>
    <?php }?>
    </div>
