<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li class="nav-header">查询</li>
            <form action="./index.php?r=admin/searchLesson" method="post">
                <li>
                    <select name="type" style="width: 185px">
                        <option value="number" selected="selected">编号</option>
                        <option value="lessonName">课程名</option>
                        <option value="createPerson">创建人</option>
                    </select>
                </li>
                <li>
                    <input name="value" type="text" class="search-query span2" placeholder="Search" />
                </li>
                <li style="margin-top:10px">
                    <button type="submit" class="btn btn-primary">查询</button>
                    <a href="./index.php?r=admin/addLesson" class="btn">添加</a>
                </li>
            </form>
            <li class="divider"></li>
            <li ><a href="./index.php?r=admin/infoCourse&&courseID=<?php echo $courseID;?>&&courseName=<?php echo $courseName;?>&&createPerson=<?php echo $createPerson;?>"><i class="icon-align-left"></i> 课业列表</a></li>
            <li ><a href="./index.php?r=admin/<?php echo Yii::app()->session['lastUrl'];?>&&page=<?php echo Yii::app()->session['lastPage'];?>"><i class="icon-align-left"></i> 返回</a></li>
        </ul>
    </div>
</div>

 <div class="span9">
    <!-- 课程列表-->
    <h3><?php echo $courseID; echo '&nbsp; &nbsp;'; echo $courseName; ?></h3>
    <p>创建人：<?php echo $createPerson;?></p>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>课号</th>
                <th>课名</th>
                <th>创建人</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($posts as $model):?>
                    <tr>
                        <td style="width: 75px"><?php echo $model['number'];?></td>
                        <td><?php echo $model['lessonName'];?></td>
                        <td><?php echo $createPerson;?></td>
                        <td><?php echo $model['createTime'];?></td>
                        <td>  
                            <a href="./index.php?r=admin/lessonBranch&&lessonID=<?php echo $model['lessonID']; ?>&&lessonName=<?php echo $model['lessonName']?>"><img src="<?php echo IMG_URL; ?>edit.png">管理</a>
                        </td>
                    </tr>            
                    <?php endforeach;?> 
                </tbody>
    </table>
    <!-- 课程列表结束 -->
    <!-- 显示翻页标签 -->
    <div align=center>
    <?php   
        $this->widget('CLinkPager',array('pages'=>$pages));
    ?>
    </div>
    <!-- 翻页标签结束 -->
    <!-- 右侧内容展示结束-->
    </div>

