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
    });
</script>
    <div class="span9">
    <!-- 搜索框 -->
    <div  style="padding: 8px 0;" align=center>
        <form id="myForm" method="post" action="./index.php?r=admin/stuLst" target="cont"> 
        <select name="which" style="width: 75px">
            <option value="userID" selected="selected">学号</option>
            <option value="userName">姓名</option>
            <option value="classID">班级</option>
        </select>
        <input type="text" name="name" style="width: 120px">
    <input type="submit" name="Submit" value="查询"> 
    <input type="button" value="添加学生" onclick="addStu()">
    <script>
        function addStu()
        {
             $("#cont").load("./index.php?r=admin/addStu");
        }
    </script>
    </form>
    </div>
    <!-- 搜索框结束 -->
    
    <!-- 学生列表-->
    <div class="hero-unit">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>学号</th>
                <th>用户名</th>
                <th>班级</th>
                <th>操作</th>
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($posts as $model):?>
                    <tr>
                        <td style="width: 75px"><?php echo $model['userID'];?></td>
                        <td><?php echo $model['userName'];?></td>
                        <td><?php if($model['classID']=="0")
                                        echo "无";
                                    else echo $model['classID'];
                            ?></td>
                        <td>  
                            <a href="./index.php?r=admin/infoStu&&id=<?php echo $model['userID'];?>&&name=<?php echo $model['userName'];?>&&class=<?php echo $model['classID'];?>"><img src="<?php echo IMG_URL; ?>detail.png">资料</a>
                            <a href="./index.php?r=admin/editStu&&id=<?php echo $model['userID'];?>&&name=<?php echo $model['userName'];?>&&class=<?php echo $model['classID'];?>"><img src="<?php echo IMG_URL; ?>edit.png">编辑</a>
                            <a href="./index.php?r=admin/stuLst&&flag=delete&&id=<?php echo $model['userID'];?>"><img src="<?php echo IMG_URL; ?>delete.png">删除</a>
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

    <!-- 右侧内容展示结束-->
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

