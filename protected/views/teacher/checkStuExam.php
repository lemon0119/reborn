<div class="span3">
    <table id="tb" class="table table-bordered table-backgroundcolor" style="border-style:none">       
        <tbody>
            <tr style="border-style:none">
                <th>姓名:</th>    
                <td><?php echo $student['userName'];?></td>
            </tr>
            <tr style="background: gray;">
                <th>学号:</th>
                 <td><?php echo $student['userID']?></td>
            </tr>
            <tr>
                <th>班级:</th>
                 <td><?php echo $class['className']?></td>
            </tr>      
        </tbody>   
    </table> 
    <div class="well" style="padding: 8px 0;">
    
    <a href="./index.php?r=teacher/NextStuExam&&studentID=<?php echo $student['userID']?>&&workID=<?php echo $work['workID']?>&&accomplish=<?php echo $accomplish?>&&classID=<?php echo $class['classID']?>">下一人</a>
   <div>
    <ul class="nav nav-list">
        <li <?php if($type == "choice") echo "class='active'";?>><a href="./index.php?r=teacher/CheckStuExam&&workID=<?php echo $work['workID'];?>&&type=choice&&studentID=<?php echo $student['userID']?>&&accomplish=<?php echo $accomplish?>"><i class="icon-font"></i> 选择</a></li>
        <li <?php if($type == "filling") echo "class='active'";?>><a href="./index.php?r=teacher/CheckStuExam&&workID=<?php echo $work['workID'];?>&&type=filling&&studentID=<?php echo $student['userID']?>&&accomplish=<?php echo $accomplish?>"><i class="icon-text-width"></i> 填空</a></li>
            <li <?php if($type == "question") echo "class='active'";?>><a href="./index.php?r=teacher/CheckStuExam&&workID=<?php echo $work['workID'];?>&&type=question&&studentID=<?php echo $student['userID']?>&&accomplish=<?php echo $accomplish?>"><i class="icon-align-left"></i> 简答</a></li>
            <li <?php if($type == "key") echo "class='active'";?>><a href="./index.php?r=teacher/CheckStuExam&&workID=<?php echo $work['workID'];?>&&type=key&&studentID=<?php echo $student['userID']?>&&accomplish=<?php echo $accomplish?>"><i class="icon-th"></i> 键位练习</a></li>
            <li <?php if($type == "look") echo "class='active'";?>><a href="./index.php?r=teacher/CheckStuExam&&workID=<?php echo $work['workID'];?>&&type=look&&studentID=<?php echo $student['userID']?>&&accomplish=<?php echo $accomplish?>"><i class="icon-eye-open"></i> 看打练习</a></li>
            <li <?php if($type == "listen") echo "class='active'";?>><a href="./index.php?r=teacher/CheckStuExam&&workID=<?php echo $work['workID'];?>&&type=listen&&studentID=<?php echo $student['userID']?>&&accomplish=<?php echo $accomplish?>"><i class="icon-headphones"></i> 听打练习</a></li>   
    </ul>
   </div>
      
    </div>
    <table class="table table-bordered table-striped">       
        <tbody>
            <tr>
                <th>成绩:</th>    
                <td><div id="score"><?php echo $score;?></div></td>
            </tr>    
        </tbody>   
    </table> 
</div>

<div class="span9" id="work">
</div>


<script>
   $(document).ready(function(){
        var user = {
            recordID:<?php if($record != NULL)echo $record['recordID'];else echo 1;?>,
            type:"<?php echo $type;?>",
            examID:<?php echo $work['examID'];?>,
            exerciseID:0      
        };
      $.ajax({
          type:"POST",
          url:"./index.php?r=teacher/ajaxExam",
          data:user,
          dataType:"html",
          success:function(html){
              $("#work").append(html);
          }
      })
    });     
</script>




