<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li class="nav-header"><i class="icon-knowlage"></i>班级</li>
           <?php foreach ($array_class as $class):?>
            <li <?php if($class['classID'] == $selectClassID) echo "class='active'";?>> <a href="./index.php?r=teacher/StuWork&&selectClassID=<?php echo $class['classID']?>"><i class="icon-list-alt"></i><?php echo $class['className']?></a></li>
          <?php endforeach;?>  
                        
        </ul>       
    </div>
</div>
<div class="span9">
    <h3>现有习题</h3>
    <!-- 键位习题列表-->
    <table class="table table-bordered table-striped" >
        <thead>
            <tr>
                <th>编号</th>
                <th>班级</th>     
                <th>科目</th>   
                <th>作业</th>
                <th>操作</th>
            </tr>
        </thead>
                <tbody>        
                    <?php $id =1;
                      foreach($array_suiteLessonClass as $suiteLesson):
                        foreach($array_class as $class)
                            if($class['classID'] == $suiteLesson['classID']){
                                $thisClass = $class;                                
                                break;
                            }
                        foreach($array_lesson as $lesson)
                            if($lesson['lessonID'] == $suiteLesson['lessonID']){
                                $thisLesson = $lesson;
                                break;
                            }
                        foreach ($array_suite as $suite)
                            if($suite['suiteID'] == $suiteLesson['suiteID']){
                                $thisSuite = $suite;
                                break;

                            }                     
                            ?>
                    <tr>
                        <td style="width: 100px">
                            <?php echo $id++;?>
                        </td>
                        <td>
                            <?php            
                            echo $thisClass['className'];?>
                           
                        </td>
                        <td style="color: #f">
                            <?php                
                            echo $thisLesson['lessonName'] ;?>
                            
                        </td>           
                        <td>            
                             <?php
                             echo $thisSuite['suiteName']?>                    
                        </td>
                        <td>     
                            <?php if($workID==$suiteLesson['workID']){?>
                                <a href="./index.php?r=teacher/stuWork&&workID=<?php echo $suiteLesson['workID']?>&&classID=<?php echo $suiteLesson['classID']?>&&page=<?php echo $pages->currentPage+1?>&&selectClassID=<?php echo $selectClassID;?>">查看</a>   
                            <?php }else{?>
                                <a href="./index.php?r=teacher/stuWork&&workID=<?php echo $suiteLesson['workID']?>&&classID=<?php echo $suiteLesson['classID']?>&&page=<?php echo $pages->currentPage+1?>&&selectClassID=<?php echo $selectClassID;?>"  style="color:gray">查看</a> 
                            <?php }?>
                            <!--<a href="./index.php?r=teacher/seeWork&&suiteID=<?php echo $thisSuite['suiteID']?>"><img title="查看习题" src="<?php echo IMG_URL;?>detail.png"/></a>   -->                   
                           

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
                <th>批阅</th>
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($array_accomplished as $student):
                            ?>
                    <tr>
                        <td class="font-orange" style="width: 75px">
                            <?php echo $student['userName'];?>
                        </td>
                        <td style="width: 50px">
                            <?php  echo $student['userID'];?>       
                        </td>
                        <td >
                            <font style="color: green">完成</font>
                        </td>  
                        <td >
                            <a href="./index.php?r=teacher/checkStuWork&&classID=<?php echo $suiteLesson['classID']?>&&workID=<?php echo $workID;?>&&studentID=<?php echo $student['userID']?>&&accomplish=1&&type=choice"><img title="批阅" src="<?php echo IMG_URL;?>edit.png"/></a>
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
                        <td class="font-orange" style="width: 75px">
                           <?php echo $student['userName'];?>
                        </td>
                        <td>
                            <?php  echo $student['userID'];?>          
                        </td>
                        <td>
                            <font style="color: red">未完成</font>
                        </td>  
                        <td>
                            <a href="./index.php?r=teacher/checkStuWork&&classID=<?php echo $suiteLesson['classID']?>&&workID=<?php echo $workID;?>&&studentID=<?php echo $student['userID']?>&&accomplish=0&&type=choice"><img title="查看" src="<?php echo IMG_URL;?>detail.png"/></a>
                        </td> 
                    </tr>            
                    <?php endforeach;?> 
                </tbody>
    </table></td>
</tr>
</table>
    </div>
</div>


