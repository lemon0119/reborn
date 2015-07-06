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
        <form id="myForm" method="post" action="./index.php?r=admin/infoCourse&&courseID=<?php echo $courseID;?>&&courseName=<?php echo $courseName;?>&&createPerson=<?php echo $createPerson;?>"> 
        <select name="which" style="width: 75px">
            <option value="number" selected="selected">课号</option>
            <option value="lessonName">课名</option>
        </select>
        <input type="text" name="name" style="width: 120px">
    <input type="submit" name="Submit" value="查询"> 
    <input type="button" value="添加一课" onclick="addStu()">
    <script>
        function addStu()
        {
             $("#cont").load("./index.php?r=admin/addLesson&&courseID=<?php echo $courseID;?>&&courseName=<?php echo $courseName;?>&&createPerson=<?php echo $createPerson;?>");
        }
    </script>
    </form>
    </div>
    <!-- 搜索框结束 -->
    <!-- 课程列表-->
    <div class="hero-unit">
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
                        <td><?php if($model['createPerson']=="0")
                                        echo "管理员";
                                    else echo $teachers[$model['createPerson']];
                            ?></td>
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

