<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li class="nav-header"><i class="icon-knowlage" style="position:relative;bottom:5px;left:"></i>班级</li>
           <?php foreach ($array_class as $class):?>
            <li <?php if($class['classID'] == $selectClassID) echo "class='active'";?>><a href="./index.php?r=teacher/StuExam&&selectClassID=<?php echo $class['classID']?>"><i class="icon-list" style="position:relative;bottom:5px;left:"></i><?php echo $class['className']?></a></li>
          <?php endforeach;?>  
                        
        </ul>       
    </div>
</div>


<div class="span9">
    <h3>现有试卷</h3>
    <!-- 键位习题列表-->
    <table class="table table-bordered table-striped" >
        <thead>
            <tr>
                <th>试卷名称</th>                  
                <th>开始时间</th>   
                <th>结束时间</th>
                <th>时长</th>
                <th>操作</th>
            </tr>
        </thead>
                <tbody>        
                    <?php $id =1;
                      foreach($array_classExam as $classexam):
                        foreach($array_class as $class)
                            if($class['classID'] == $classexam['classID']){
                                $thisClass = $class;                                
                                break;
                            }
                        foreach ($array_exam as $exam)
                            if($exam['examID'] == $classexam['examID']){
                                $thisExam = $exam;
                                break;
                            }                     
                            ?>
                    <?php if(isset($thisExam)){?>
                    <tr>
                        <td>
                            <?php            
                            echo $thisExam['examName'];?>
                           
                        </td>        
                        <td>            
                             <?php
                             echo $thisExam['begintime']?>                    
                        </td>
                        <td>            
                             <?php
                             echo $thisExam['endtime']?>                    
                        </td>
                        <td>            
                             <?php
                             echo $thisExam['duration']?>                    
                        </td>
                        <td>        
                            <?php if($workID==$classexam['workID']){?>
                                <a href="./index.php?r=teacher/stuExam&&workID=<?php echo $classexam['workID']?>&&classID=<?php echo $classexam['classID']?>&&page=<?php echo $pages->currentPage+1?>&&selectClassID=<?php echo $selectClassID?>" style="color:#ff0000">查看</a>      
                            <?php }else{?>
                                <a href="./index.php?r=teacher/stuExam&&workID=<?php echo $classexam['workID']?>&&classID=<?php echo $classexam['classID']?>&&page=<?php echo $pages->currentPage+1?>&&selectClassID=<?php echo $selectClassID?>" style="color:gray">查看</a> 
                            <?php }?>
                        </td>

                    </tr>      
                    <?php }?>
                    <?php endforeach;?> 
                </tbody>
    </table>
    <!-- 学生列表结束 -->
    <div align=center id="yiyou">
    <?php   
        $this->widget('CLinkPager',array('pages'=>$pages));
    ?>
    </div>

    <h3 style="float: left">学生列表</h3>  <div style="position:relative;right: 393px;top:8px"><button class="fr btn btn-primary" id="mark" onclick="mark()">一键判卷</button></div><br><br><br>
<div style="overflow-y:auto; height:300px;">
<table width="50%" style="float:left;" >
<tr>
<td><table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>姓名</th>
                <th>学号</th>
                <th>完成情况</th> 
                <th>成绩</th>
                <th>查看</th>
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
                        <td style="width: 50px">
                            <?php  echo $student['score'];?>   
                        </td> 
                        <td >
                            <a href="./index.php?r=teacher/checkStuExam&&classID=<?php echo $classexam['classID']?>&&workID=<?php echo $workID;?>&&studentID=<?php echo $student['userID']?>&&accomplish=1&&type=key">批阅</a>
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
                        <td style="width: 75px">
                           <?php echo $student['userName'];?>
                        </td>
                        <td>
                            <?php  echo $student['userID'];?>          
                        </td>
                        <td>
                           <font style="color: red">未完成</font>
                        </td>  
                        <td>
                            <font style="color: gray">批阅</font>
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
function mark(){
     <?php foreach($array_accomplished as $student):
            $recordID = $student['recordID'];
            $userID = $student['userID'];
            $examID = ClassExam::model()->find("workID = '$workID'")['examID'];
     ?>
        var user = {
            userID:"<?php echo $userID;?>",
            recordID:<?php  echo $recordID;?>,
            examID:<?php echo $examID;?>,
        };
        $.ajax({
          type:"POST",
          url:"./index.php?r=teacher/oneMark",
          async: false,
          data:user,
          success:function(data){             
          },
          error: function(xhr, type, exception){        
          }
      });      
  <?php endforeach;   ?>
   location.reload();
   }
//       $("#classSelect").change(function(){
//           var url = "./index.php?r=teacher/stuWork";
//           var value = $(this).options[$(this).selectedIndex].value
//           alert(value);
//           if(value != "")
//           url += "&&classID="+ value;
//       alert(url);
//       window.location.href = url;
//       });
       
       function changeClass(value)
       {   var url = "./index.php?r=teacher/stuWork";
           if(value != -1)
           url += "&&selectClassID="+ value;  
           window.location.href = url;
       }
       function examdata(workID)
      {
           window.open("./index.php?r=teacher/WatchExamData&&workID="+workID+"&&classID=<?php if(isset( $classexam['classID'])){echo $classexam['classID'];}?>", 'newwindow', 'height=600,width=700,top=0,left=0,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no,left=500,top=200,')      
      }
</script>

