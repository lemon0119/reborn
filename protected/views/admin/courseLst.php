<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
            <form action="./index.php?r=admin/searchCourse" method="post">
                <li>
                    <select name="type" >
                        <option value="courseID" selected="selected">编号</option>
                        <option value="courseName">课程名</option>
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
            <li ><a href="./index.php?r=admin/courseLst"><i class="icon-align-left"></i> 课程列表</a></li>
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

<!-- 课程列表-->
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>编号</th>
            <th>课程名</th>
            <th>创建人</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
    </thead>
            <tbody>        
                <?php foreach($courseLst as $model):?>
                <tr>
                    <td style="width: 75px"><?php echo $model['courseID'];?></td>
                    <td><?php echo $model['courseName'];?></td>
                    <td><?php if($model['createPerson']=="0")
                                    echo "管理员";
                                else echo $teachers[$model['createPerson']];
                        ?></td>
                    <td><?php echo $model['createTime'];?></td>
                    <td>  
                        <a href="./index.php?r=admin/infoCourse&&courseID=<?php echo $model['courseID'];?>&&courseName=<?php echo $model['courseName'];?>&&createPerson=<?php if($model['createPerson']=="0")
                                                                                                                                                                                    echo "管理员";
                                                                                                                                                                                    else echo $teachers[$model['createPerson']];
                                                                                                                                                                            ?>"><img title="编辑课业" src="<?php echo IMG_URL; ?>edit.png"></a>
                        <a href="#"  onclick="deleteCourse(<?php echo $model['courseID'];?>,'<?php echo $model['courseName'];?>')" ><img title="删除" src="<?php echo IMG_URL; ?>delete.png"></a>
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
</div>

<script>
    
$(document).ready(function(){
    var result = <?php echo "'$result'";?>;
    if(result === '1')
        alert("删除成功！");
    else if(result === '0')
        alert("已有班级进行该课程，无法删除！");  
});
    
    function deleteCourse(id,name){
        if(confirm("确定要删除课程："+name+"?这样做将无法恢复！")){
            window.location.href="./index.php?r=admin/deleteCourse&&courseID="+id+"&&page=<?php echo Yii::app()->session ['lastPage'];?>";
        }else{
            return false;
        };
    }
    
</script>
