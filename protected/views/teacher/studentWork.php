<div class="span9">
    <h3>现有习题</h3>
    <!-- 键位习题列表-->
    <table class="table table-bordered table-striped" >
        <thead>
            <tr>
                <th>编号</th>
                <th>班级</th>
                <th>课程</th>   
                <th>作业</th>
                <th>状态</th>      
                <th>操作</th>
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($array_suite as $suite):
                        foreach($array_class as $class)
                            if($class['classID'] == $suite['classID']){
                                $thisClass = $class;
                                break;
                            }
                        foreach($array_lesson as $lesson)
                            if($lesson['lessonID'] == $suite['lessonID']){
                                $thisLesson = $lesson;
                                break;
                            }
                            ?>
                    <tr>
                        <td style="width: 150px">
                            <?php echo $suite['suiteID'];?>
                        </td>
                        <td>
                            <?php  echo $thisClass['className'];?>
                           
                        </td>
                        <td>
                            <?php  echo $thisLesson['lessonName'] ;?>
                            
                        </td>           
                        <td>            
                            <?php  echo $suite['suiteName'];?>                      
                        </td>
                        <td>            
                            <?php  if($suite['open'])
                                    echo "开放";
                                   else
                                       echo "关闭";?>                      
                        </td>
                        <td>            
                            <a href="./index.php?r=teacher/stuWork&&classID=<?php echo $suite['classID'];?>&&lessonID=<?php echo $suite['lessonID'];?>&&suiteID=<?php echo $suite['suiteID'];?>&&page=<?php echo $pages->currentPage+1?>">查看</a>                      
                        </td>
                    </tr>            
                    <?php endforeach;?> 
                </tbody>
    </table>
    <!-- 学生列表结束 -->
    <div align=center id="yiyou">
    <?php   
        $this->widget('CLinkPager',array('pages'=>$pages));
    ?>
    </div>
 </div>

<div class="span9" id="list">
<h3>学生列表</h3>
<div style="overflow-y:auto; height:300px;">
<table width="50%" style="float:left;" >
<tr>
<td><table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>姓名</th>
                <th>学号</th>
                <th>完成情况</th>   
                <th>查看</th>
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($array_accomplished as $student):
                            ?>
                    <tr>
                        <td style="width: 50px">
                            <?php echo $student['userName'];?>
                        </td>
                        <td style="width: 50px">
                            <?php  echo $student['userID'];?>       
                        </td>
                        <td >
                            完成
                        </td>  
                        <td >
                            <a href="#">查看</a>
                        </td>
                    </tr>            
                    <?php endforeach;?> 
                </tbody>
    </table></td>
</tr>
</table>
<table width="50%" >
<tr>
    <td><table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>姓名</th>
                <th>学号</th>
                <th>完成情况</th>   
                <th>查看</th>
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($array_unaccomplished as $student):
                            ?>
                    <tr>
                        <td style="width: 50px">
                           <?php echo $student['userName'];?>
                        </td>
                        <td>
                            <?php  echo $student['userID'];?>          
                        </td>
                        <td>
                            未完成
                        </td>  
                        <td>
                            <a href="#">查看</a>
                        </td> 
                    </tr>            
                    <?php endforeach;?> 
                </tbody>
    </table></td>
</tr>
</table>
    </div>
</div>

<script>
   
//   function refresh(classID,lessonID,suiteID)
//   {
//     $("#list").html("");
//     $.ajax({
//         type:"POST",
//         url:"./index.php?r=teacher/refreshLst&&classID="+classID+"&&lessonID="+lessonID+"&&suiteID="+suiteID,
//         cache:false,        
//     })
//   }
</script>

