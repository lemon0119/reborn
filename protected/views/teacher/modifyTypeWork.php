<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li <?php if($type == "choice") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=choice"><i class="icon-font"></i> 选择</a></li>
                        <li <?php if($type == "filling") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=filling"><i class="icon-text-width"></i> 填空</a></li>
                        <li <?php if($type == "question") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=question"><i class="icon-align-left"></i> 简答</a></li>
                        <li <?php if($type == "key") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=key"><i class="icon-th"></i> 键位练习</a></li>
                        <li <?php if($type == "look") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=look"><i class="icon-eye-open"></i> 看打练习</a></li>
                        <li <?php if($type == "listen") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=listen"><i class="icon-headphones"></i> 听打练习</a></li> 
                        
                       
                        
        </ul>
        <ul>
             <br>
                        <li > <?php if($type == "key") { ?> <button class='btn btn-primary' onclick="window.location.href = './index.php?r=teacher/AddKey'">添加新题</button><?php }
                        else if($type == "look"){ ?> <button class='btn btn-primary' onclick="window.location.href = './index.php?r=teacher/AddLook'">添加新题</button> <?php }
                        else if($type == "listen"){ ?> <button class='btn btn-primary' onclick="window.location.href = './index.php?r=teacher/addListen'">添加新题</button><?php }?> </li>
        </ul>
        
    </div>  
    
     <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <center>
                <table style="margin-left: -60px;">
                    <tr><td><font class="fl" style="position: relative;right: 20px; color: #fff">课程:&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #f46500"><?php echo $currentClass['className']?></span></font></td></tr>
                    <tr><td><font class="fl" style="color: #fff">课时:&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #f46500"><?php echo $currentLesson['lessonName']?></span></font></td></tr>
                    <tr><td><font class="fl" style="position: relative;left: 20px;color: #fff">作业:&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #f46500"><?php  if(Tool::clength($suite['suiteName'])<=7)
                                        $a= $suite['suiteName'];
                                    else
                                        $a= Tool::csubstr($suite['suiteName'], 0, 7)."...";?>
                                <a  title="<?php echo $suite['suiteName']?>" style="text-decoration:none;"><?php echo $a?></a></span></font>
                        </td></tr>
                </table>                
            </center>                   
        </ul>
    </div>  
    <a href="./index.php?r=teacher/AssignWork&&classID=<?php echo $currentClass['classID'];?>&&lessonID=<?php echo $currentLesson['lessonID'];?>"  class="btn btn-primary">返回</a>
</div>

<div class="span9">
    <iframe src="./index.php?r=teacher/ToOwnTypeWork&&type=<?php echo $type;?>&&suiteID=<?php echo $suite['suiteID'];?>" id="iframe1" frameborder="0" scrolling="no" width="770px" height="400px" name="iframe1"></iframe>
    
    <iframe src="./index.php?r=teacher/ToAllTypeWork&&type=<?php echo $type;?>&&suiteID=<?php echo $suite['suiteID'];?>" id="iframe2" frameborder="0" scrolling="no" width="770px" height="400px" name="iframe2"></iframe>

</div >



<script>
    var currentPage1;  
    function refresh()
    {     
    $('#iframe1').attr("src","./index.php?r=teacher/ToOwnTypeWork&&type=<?php echo $type;?>&&suiteID=<?php echo $suite['suiteID'];?>&&page="+currentPage1);   
   }
   
   function setCurrentPage1(page)
   {  
       currentPage1 = page;    
   }
</script>



