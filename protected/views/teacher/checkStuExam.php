<div class="span3">
    <?php  $count = count($workLst);
            ?>
    <table class="table table-bordered table-striped">       
        <tbody>
            <tr>
                <th>姓名:</th>    
                <td><?php echo $student['userName'];?></td>
            </tr>
            <tr>
                <th>学号:</th>
                 <td><?php echo $student['userID']?></td>
            </tr>
            <tr>
                <th>班级:</th>
                 <td><?php echo $class['className']?></td>
            </tr>      
        </tbody>   
    </table> 
    <a href="./index.php?r=teacher/NextStuWork&&studentID=<?php echo $student['userID']?>&&workID=<?php echo $workID?>&&accomplish=<?php echo $accomplish?>&&classID=<?php echo $class['classID']?>">下一人</a>
   <div>
    <ul class="nav nav-list">
        <li <?php if($type == "choice") echo "class='active'";?>><a href="./index.php?r=teacher/CheckStuExam&&workID=<?php echo $workID;?>&&type=choice&&studentID=<?php echo $student['userID']?>&&accomplish=<?php echo $accomplish?>"><i class="icon-font"></i> 选择</a></li>
        <li <?php if($type == "filling") echo "class='active'";?>><a href="./index.php?r=teacher/CheckStuExam&&workID=<?php echo $workID;?>&&type=filling&&studentID=<?php echo $student['userID']?>&&accomplish=<?php echo $accomplish?>"><i class="icon-text-width"></i> 填空</a></li>
            <li <?php if($type == "question") echo "class='active'";?>><a href="./index.php?r=teacher/CheckStuExam&&workID=<?php echo $workID;?>&&type=question&&studentID=<?php echo $student['userID']?>&&accomplish=<?php echo $accomplish?>"><i class="icon-align-left"></i> 简答</a></li>
            <li <?php if($type == "key") echo "class='active'";?>><a href="./index.php?r=teacher/CheckStuExam&&workID=<?php echo $workID;?>&&type=key&&studentID=<?php echo $student['userID']?>&&accomplish=<?php echo $accomplish?>"><i class="icon-th"></i> 键位练习</a></li>
            <li <?php if($type == "look") echo "class='active'";?>><a href="./index.php?r=teacher/CheckStuExam&&workID=<?php echo $workID;?>&&type=look&&studentID=<?php echo $student['userID']?>&&accomplish=<?php echo $accomplish?>"><i class="icon-eye-open"></i> 看打练习</a></li>
            <li <?php if($type == "listen") echo "class='active'";?>><a href="./index.php?r=teacher/CheckStuExam&&workID=<?php echo $workID;?>&&type=listen&&studentID=<?php echo $student['userID']?>&&accomplish=<?php echo $accomplish?>"><i class="icon-headphones"></i> 听打练习</a></li>   
    </ul>
   </div>
</div>

<div class="span9">
<div id="work" >

</div>
<button id = 'next' onclick="nextWork()" class="btn btn-primary">保存/下一题</button> 

</div>





<script>
   $(document).ready(function(){
        var recordID1 = <?php if($recordID != NULL)echo $recordID;else echo 1;?>;
        var type1 = "<?php echo $type;?>";
        var suiteID1 = <?php echo $examID;?>;
        var count = <?php echo $count?>;
        var user = {
            recordID:recordID1,
            type:type1,
            suiteID:suiteID1,
            count:count
        };
      $.ajax({
          type:"POST",
          url:"./index.php?r=teacher/ajaxChoice",
          data:user,
          dataType:"html",
          success:function(html){
              $("#work").append(html);
          }
      })
    }); 
    
    
    function nextWork(){     
        var recordID1 = <?php if($recordID != NULL)echo $recordID;else echo 1;?>;
        var type1 = "<?php echo $type;?>";
        var suiteID1 = <?php echo $examID;?>;
        var count = <?php echo $count?>;
        var user = {
            recordID:recordID1,
            type:type1,
            suiteID:suiteID1,
            count:count
        };
      $.ajax({
          type:"POST",
          url:"./index.php?r=teacher/ajaxChoice",
          data:user,
          dataType:"html",
          success:function(html){
              $("#work").html(html);
          }
      })
    }
</script>




