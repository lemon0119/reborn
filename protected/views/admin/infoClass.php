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
   
</script>
<div class="span9">
        <div class="hero-unit">
            
        <?php
        //得到老师ID对应的名称
        foreach ($teacher as $model):
        $teacherID=$model['userID'];
        $teachers["$teacherID"]=$model['userName'];
        endforeach;
        ?>
            
        <h3><?php echo $classID; echo '&nbsp; &nbsp;'; echo $className;?></h3>
        <p>人数：<?php echo $nums; echo '&nbsp; &nbsp;';?> 当前课程： <?php echo $curCourse; echo '&nbsp; &nbsp;';?>
        </p>
         <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>工号</th>
                <th>老师名</th>
                <th>操作</th>
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($teacherOfClass as $model):?>
                    <tr>
                        <td style="width: 75px"><?php echo $model['teacherID'];?></td>
                        <td><?php echo $teachers[$model['teacherID']];?></td>
                        <td>
                            <a href="./index.php?r=admin/infoTea&&id=<?php echo $model['teacherID']; ?>&&name=<?php echo $teachers[$model['teacherID']]; ?>&&flag=3&&classID=<?php echo $classID; ?>"><img src="<?php echo IMG_URL; ?>detail.png">资料</a>
                            <a href="./index.php?r=admin/infoClass&&flag=deleteTea&&id=<?php echo $model['teacherID'];?>&&classID=<?php echo $classID;?>"><img src="<?php echo IMG_URL; ?>delete.png">删除</a>
                        </td>
                    </tr>            
                    <?php endforeach;?> 
                </tbody>
        </table>
        
        <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>学号</th>
                <th>学生名</th>
                <th>操作</th>
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($posts as $model):?>
                    <tr>
                        <td style="width: 75px"><?php echo $model['userID'];?></td>
                        <td><?php echo $model['userName'];?></td>
                        <td>  
                            <a href="./index.php?r=admin/infoClass&&flag=deleteStu&&id=<?php echo $model['userID'];?>&&classID=<?php echo $model['classID'];?>"><img src="<?php echo IMG_URL; ?>delete.png">删除</a>
                        </td>
                    </tr>            
                    <?php endforeach;?> 
                </tbody>
        </table>
        <div align=center>
        <?php   
        $this->widget('CLinkPager',array('pages'=>$pages));
        ?>
        </div>
    <!-- 翻页标签结束 -->
        <button type="button" onclick="back()">确定</button>
        <button type="button" onclick="addStuClass()">添加学生</button>
        <button type="button" onclick="addTeaClass()">添加老师</button>
        <script>
        function back()
        {
             $("#cont").load("./index.php?r=admin/classLst");
        }
        function addStuClass()
        {
             $("#cont").load("./index.php?r=admin/addStuClass&&classID=<?php echo $classID;?>");
        }
        function addTeaClass()
        {
             $("#cont").load("./index.php?r=admin/addTeaClass&&classID=<?php echo $classID;?>");
        }
        </script>
        
            
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

