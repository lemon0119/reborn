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
                <th style="width:50px">编号</th>
                <th style="width:70px">班级</th>     
                <th style="width:100px">课时</th>   
                <th style="width:120px">作业</th>
                <th style="width:50px">操作</th>
            </tr>
        </thead>
    </table>
    <div style="position: relative;top: -20px; overflow-y:auto; height:180px;">
    <table class="table table-bordered table-striped" >
        
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
                            if(isset($thisClass)&&isset($thisLesson)&&isset($thisSuite)&&$thisClass&&$thisLesson&&$thisSuite){
                            ?>
                    <tr>
                        <td style="width: 50px">
                            <?php echo $id++;?>
                        </td>
                        <td style="width:70px">
                            <?php            
                            echo $thisClass['className'];?>
                           
                        </td>
                        <td style="width:100px">
                            <?php                
                            echo $thisLesson['lessonName'] ;?>
                            
                        </td>           
                        <td style="width:120px">            
                             <?php
                             echo $thisSuite['suiteName'];?>                    
                        </td>
                        <td style="width:39px">     
                            <?php if($workID==$suiteLesson['workID']){?>
                                <a href="./index.php?r=teacher/stuWork&&workID=<?php echo $suiteLesson['workID']?>&&classID=<?php echo $suiteLesson['classID']?>&&page=<?php echo $pages->currentPage+1?>&&selectClassID=<?php echo $selectClassID;?>">查看</a>   
                            <?php }else{?>
                                <a href="./index.php?r=teacher/stuWork&&workID=<?php echo $suiteLesson['workID']?>&&classID=<?php echo $suiteLesson['classID']?>&&page=<?php echo $pages->currentPage+1?>&&selectClassID=<?php echo $selectClassID;?>"  style="color:gray">查看</a> 
                            <?php }?>
                            <!--<a href="./index.php?r=teacher/seeWork&&suiteID=<?php //echo $thisSuite['suiteID']?>"><img title="查看习题" src="<?php //echo IMG_URL;?>detail.png"/></a>   -->                   
                           

                        </td>
                    </tr>            
                            <?php } endforeach;?> 
                </tbody>
    </table>
    </div>
    <!-- 学生列表结束 -->
<!--    <div align=center id="yiyou">
    <?php   
        //$this->widget('CLinkPager',array('pages'=>$pages));
    ?>
    </div>-->


<h3>学生列表</h3>
<div>
<table width="50%" style="float:left;" >
<tr>
<td><table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th style="width:50px">姓名</th>
                <th style="width:50px">学号</th>
                <th style="width:50px">完成情况</th>   
                <th style="width:30px">批阅</th>
            </tr>
        </thead>
              
    </table></td>
</tr>
</table>
<table width="50%" >
<tr>
    <td><table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th style="width:50px">姓名</th>
                <th style="width:50px">学号</th>
                <th style="width:50px">完成情况</th>   
                <th style="width:34px">查看</th>
            </tr>
        </thead>
               
    </table></td>
</tr>
</table>
    </div>
<div style="position: relative;top: -22px;overflow-y:auto; height:210px;">
<table width="51%" style="float:left;" >
<tr>
<td><table class="table table-bordered table-striped">
                <tbody>        
                    <?php foreach($array_accomplished as $student):
                            ?>
                    <tr>
                        <td class="font-orange" style="width: 50px">
                            <?php echo $student['userName'];?>
                        </td>
                        <td style="width: 50px">
                            <?php  echo $student['userID'];?>       
                        </td>
                        <td style="width: 50px" >
                            <font style="color: green;">完成</font>
                        </td>  
                        <td style="width:30px" >
                            <a href="./index.php?r=teacher/checkStuWork&&classID=<?php echo $suiteLesson['classID']?>&&workID=<?php echo $workID;?>&&studentID=<?php echo $student['userID']?>&&accomplish=1&&type=choice">批阅</a>
                        </td>
                    </tr>            
                    <?php endforeach;?> 
                </tbody>
    </table></td>
</tr>
</table>
<table width="49%" >
<tr>
    <td><table style="position:relative;left: 2px"  class="table table-bordered table-striped">
                <tbody>        
                    <?php foreach($array_unaccomplished as $student):
                            ?>
                    <tr>
                        <td class="font-orange" style="width: 50px">
                           <?php echo $student['userName'];?>
                        </td>
                        <td style="width: 50px">
                            <?php  echo $student['userID'];?>          
                        </td>
                        <td style="width:50px">
                            <font style="color: red;">未完成</font>
                        </td>  
                        <td style="width: 20px">
                            <font style="color: gray">查看</font>
                        </td> 
                    </tr>            
                    <?php endforeach;?> 
                </tbody>
    </table></td>
</tr>
</table>
    </div>
</div>


