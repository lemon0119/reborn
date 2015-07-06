 <script src="<?php echo JS_URL;?>jquery-form.js"></script>
<script>
    $(document).ready(function(){
            $("div.span9").find("a").click(function(){
                var url = $(this).attr("href");
                //$(this).attr("href","#");
                if(url.indexOf("index.php") > 0){
                    $("#cont").load(url);
                    return false;//阻止链接跳转
                }
            });
    });
    
       var options = {   
            target:'#cont',   // 需要刷新的区域 
            //type:'post',
            //dataType:'json',
            //resetForm:false,
           // timeout:10000
        };

    $("#myForm").submit(function(){ 
          $(this).ajaxSubmit(options);   
      // 为了防止普通浏览器进行表单提交和产生页面导航（防止页面刷新？）返回false   
           return false;   
      });  
</script>
<div class="span9">
<!-- 搜索框 -->
<div  style="padding: 8px 0;" align=center>
<form id="myForm" method="post" action="./index.php?r=admin/questionLst"> 
    <select name="which" style="width: 75px">
        <option value="exerciseID" selected="selected">编号</option>
    </select>
    <input type="text" name="name" style="width: 120px">
<input type="submit" name="Submit" value="查询"> 
<input type="button" value="添加题目" onclick="addStu()">
<script>
    function addStu()
    {
        $("#cont").load("./index.php?r=admin/addQuestion")
    }
</script>
</form>
</div>
<!-- 搜索框结束 -->

<?php
    //得到老师ID对应的名称
    foreach ($teacher as $model):
    $teacherID=$model['userID'];
    $teachers["$teacherID"]=$model['userName'];
    endforeach;
?>

<!-- 键位习题列表-->
<div class="hero-unit">
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>编号</th>
            <th>课程号</th>
            <th>内容</th>
            <th>创建人</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
    </thead>
            <tbody>        
                <?php foreach($posts as $model):?>
                <tr>
                    <td style="width: 50px"><?php echo $model['exerciseID'];?></td>
                    <td><?php echo $model['courseID'];?></td>
                    <td><?php  if(strlen($model['requirements'])<=15)
                                    echo $model['requirements'];
                                else
                                    echo substr($model['requirements'], 0, 15)."...";
                    ?></td>
                    <td><?php if($model['createPerson']=="0")
                                    echo "管理员";
                                else echo  $teachers[$model['createPerson']];
                        ?></td>
                    <td><?php echo $model['createTime'];?></td>
                    <td>  
                        <a href="./index.php?r=admin/editQuestion&&exerciseID=<?php echo $model['exerciseID'];?>"><img src="<?php echo IMG_URL; ?>edit.png">编辑</a>
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

</div>
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

