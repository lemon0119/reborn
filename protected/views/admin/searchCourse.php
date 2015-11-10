<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
            <form action="./index.php?r=admin/searchCourse" method="post">
                <li>
                    <select name="type" >
                        <option value="courseID" selected="selected">编号</option>
                        <option value="courseName">科目名</option>
                        <option value="createPerson">创建人</option>
                    </select>
                </li>
                <li>
                    <input name="value" type="text" class="search-query span2" placeholder="Search" />
                </li>
                <li style="margin-top:10px">
                    <button type="submit" class="btn_serch"></button>
                    <a href="./index.php?r=admin/addCourse" class="btn_add"></a>
                </li>
            </form>
            <li class="divider"></li>
            <li ><a href="./index.php?r=admin/courseLst"><i class="icon-align-left"></i> 科目列表</a></li>
        </ul>
    </div>
</div>
<div class="span9">

<?php
//得到老师ID对应的名称
foreach ($teacher as $model):
$teacherID=$model['userID'];
$teachers["$teacherID"]=$model['userName'];
endforeach;
?>
<?php if($courseLst->count()!=0){?>
    <h2>查询结果</h2>
<!-- 科目列表-->
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="font-center">编号</th>
            <th class="font-center">科目名</th>
            <th class="font-center">创建人</th>
            <th class="font-center">创建时间</th>
            <th class="font-center">操作</th>
        </tr>
    </thead>
            <tbody>        
                <?php foreach($courseLst as $model):?>
                <tr>
                    <td class="font-center" style="width: 75px"><?php echo $model['courseID'];?></td>
                    <td class="font-center"><?php echo $model['courseName'];?></td>
                    <td class="font-center"><?php if($model['createPerson']=="0")
                                    echo "管理员";
                                else echo $teachers[$model['createPerson']];
                        ?></td>
                    <td class="font-center"><?php echo $model['createTime'];?></td>
                    <td class="font-center" style="width: 100px">  
                        <a href="./index.php?r=admin/infoCourse&&courseID=<?php echo $model['courseID'];?>&&courseName=<?php echo $model['courseName'];?>&&createPerson=<?php if($model['createPerson']=="0")
                                                                                                                                                                                    echo "管理员";
                                                                                                                                                                                    else echo $teachers[$model['createPerson']];
                                                                                                                                                                                    ?>"><img title="信息" src="<?php echo IMG_URL; ?>detail.png"></a>
                    </td>
                </tr>            
                <?php endforeach;?> 
            </tbody>
</table>
<!-- 学生列表结束 -->
<!-- 显示翻页标签 -->
<div align=center>
<?php   
    $this->widget('CLinkPager',array('pages'=>$pages));
?>
</div>
<!-- 翻页标签结束 -->

<!-- 右侧内容展示结束-->
<?php } else {?>
    <h2>查询结果为空！</h2>
<?php }?>
</div>