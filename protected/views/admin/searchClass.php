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
    ?>

<div class="span9">
    <?php if($posts->count()!=0){?>
    <h2>班级列表</h2>
    <!-- 班级列表-->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>班号</th>
                <th>班级名</th>
                <th>老师</th>
                <th>人数</th>
                <th>当前进度</th>
                <th>操作</th>
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($posts as $model):?>
                    <tr>
                        <td style="width: 75px"><?php echo $model['classID'];?></td>
                        <td><?php echo $model['className'];?></td>
                        <td><?php 
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
                        <td><?php  if(array_key_exists($model['classID'], $numOfClass))
                                        echo  $numOfClass[$model['classID']];
                                    else echo "0";
                            ?></td>
                        <td><?php echo $model['currentCourse'];?></td>
                        <td>  
                            <a href="./index.php?r=admin/infoClass&&classID=<?php echo $model['classID']; ?>"><img src="<?php echo IMG_URL; ?>detail.png">管理</a>
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
