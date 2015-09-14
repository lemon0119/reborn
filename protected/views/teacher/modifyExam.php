<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li <?php if($type == "choice") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyExam&&examID=<?php echo $exam['examID']?>&&type=choice"><i class="icon-font"></i> 选择</a></li>
                        <li <?php if($type == "filling") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyExam&&examID=<?php echo $exam['examID']?>&&type=filling"><i class="icon-text-width"></i> 填空</a></li>
                        <li <?php if($type == "question") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyExam&&examID=<?php echo $exam['examID']?>&&type=question"><i class="icon-align-left"></i> 简答</a></li>
                        <li <?php if($type == "key") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyExam&&examID=<?php echo $exam['examID']?>&&type=key"><i class="icon-th"></i> 键位练习</a></li>
                        <li <?php if($type == "look") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyExam&&examID=<?php echo $exam['examID']?>&&type=look"><i class="icon-eye-open"></i> 看打练习</a></li>
                        <li <?php if($type == "listen") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyExam&&examID=<?php echo $exam['examID']?>&&type=listen"><i class="icon-headphones"></i> 听打练习</a></li>                           
        </ul>
        
  </div>  
    
    
     <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <center>
                <table>
                    <tr><td>试卷名称:<?php echo $exam['examName']?></td></tr>
                    <tr><td>开始时间:<?php echo $exam['begintime']?></td></tr>
                    <tr><td>结束时间:<?php echo $exam['endtime']?></td></tr>
                    <tr><td>时长:<?php echo $exam['duration']?>分钟</td></tr>
                    <tr><td><div id="totalScore">总分:<?php echo $totalScore?></div></td></tr>
                </table>                
            </center>                   
        </ul>
    </div>   
       
    <a href="./index.php?r=teacher/AssignExam&&classID=<?php echo Yii::app()->session['currentClass'];?>"  class="btn btn-primary">返回布置作业</a>
</div>

<div class="span9">
    <iframe src="./index.php?r=teacher/ToOwnExam&&type=<?php echo $type;?>&&examID=<?php echo $exam['examID'];?>" id="iframe1" frameborder="0"  scrolling="no" width="900px" height="350px" name="iframe1"></iframe>
    
    <iframe src="./index.php?r=teacher/ToAllExam&&type=<?php echo $type;?>&&examID=<?php echo $exam['examID'];?>" id="iframe2" frameborder="0" scrolling="no" width="900px" height="400px" name="iframe2"></iframe>

<div >
    
    
<script>
    var currentPage1;  
    function refresh()
    {     
    $('#iframe1').attr("src","./index.php?r=teacher/ToOwnExam&&type=<?php echo $type;?>&&examID=<?php echo $exam['examID'];?>&&page="+currentPage1);   
   }
   
   function setCurrentPage1(page)
   {  
       currentPage1 = page;    
   }
   
   
</script>
    