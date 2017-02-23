<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li class="nav-header"><i class="icon-navsearch" style="position:relative;bottom:5px;left:"></i>搜索</li>
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
                    <button type="submit" class="btn_4big">搜 索</button>
                    <button onclick="window.location.href = './index.php?r=admin/addCourse'" type="button" class="btn_4big">添 加</button>
                </li>
            </form>
            <li class="divider"></li>
            <li class="active" ><a href="./index.php?r=admin/courseLst"><i class="icon-align-left" style="position:relative;bottom:7px;left:"></i> 科目列表</a></li>
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
<h2>科目列表</h2>
<!-- 科目列表-->
   <input type="checkbox" name="all" onclick="check_all(this, 'checkbox[]')" style="margin-bottom: 3px"> 全选　　批量操作：
   <a href="#" onclick="deleCheck()"><img title="批量删除" src="<?php echo IMG_URL; ?>delete.png" style="position:relative;bottom:3px;left:"></a>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="font-center">编号</th>
            <th class="font-center">科目名</th>
            <th class="font-center">创建人</th>
            <th class="font-center">课时数</th>
            <th class="font-center">创建时间</th>
        </tr>
    </thead>
            <tbody>        
                <form id="deleForm" method="post" action="./index.php?r=admin/deleteCourse">
                <?php   foreach($courseLst as $k=>$model):
                    ?>
                <tr>
                    <td class="font-center" style="width: 75px"><?php echo $model['courseID'];?></td>
                    <td class="font-center"><?php echo $model['courseName'];?></td>
                    <td class="font-center"><?php if($model['createPerson']=="0")
                                    echo "管理员";
                                else echo $teachers[$model['createPerson']];
                        ?></td>
                    <td class="font-center"><?php echo $courseNumber[$k];?></td>
                    <td class="font-center"><?php echo $model['createTime'];?></td>
                </tr>            
                <?php 
                endforeach;?> 
                </form>
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
    window.wxc.xcConfirm("删除成功！", window.wxc.xcConfirm.typeEnum.success,{
       onOk:function(){
		 window.location.href = "./index.php?r=admin/courseLst";
						}
    });
    else if(result==='0'){
    window.wxc.xcConfirm("已有班级进行需要删除的科目，无法删除！", window.wxc.xcConfirm.typeEnum.error,{
       onOk:function(){
		 window.location.href = "./index.php?r=admin/courseLst";
						}
    });
    }
});

    $(document).ready(function () {
        $("#li-stuLst").attr("class", "active");
    });
</script>
