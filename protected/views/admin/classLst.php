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
    <form id="myForm" method="post" action="./index.php?r=admin/classLst"> 
        <select name="which" style="width: 90px">
            <option value="classID" selected="selected">班级号</option>
            <option value="teaName">老师名</option>
            <option value="teaID">老师工号</option>
        </select>
        <input type="text" name="name" style="width: 120px">
    <input type="submit" name="Submit" value="查询"> 
    <input type="button" value="添加班级" onclick="addClass()">
    <script>
        function addClass()
        {
            $("#cont").load("./index.php?r=admin/addClass");
        }
    </script>
    </form>
    </div>
    <!-- 搜索框结束 -->
    
    <!-- 班级列表-->
    <div class="hero-unit">
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
    
