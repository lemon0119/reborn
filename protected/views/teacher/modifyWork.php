<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li <?php if($type == "choice") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=choice"><i class="icon-font"></i> <span style="position: relative;top: 6px">选择</span></a></li>
            <li <?php if($type == "filling") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=filling"><i class="icon-text-width"></i><span style="position: relative;top: 6px"> 填空</span></a></li>
            <li <?php if($type == "question") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=question"><i class="icon-align-left"></i><span style="position: relative;top: 6px"> 简答 </span></a></li>
            <li <?php if($type == "key") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=key"><i class="icon-th"></i><span style="position: relative;top: 6px"> 键打练习</span></a></li>
            <li <?php if($type == "look") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=look"><i class="icon-eye-open"></i><span style="position: relative;top: 6px"> 看打练习</span></a></li>
            <li <?php if($type == "listen") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=listen"><i class="icon-headphones"></i><span style="position: relative;top: 6px"> 听打练习</span></a></li>
                       
                       
                        
        </ul>
        <br>
        <ul >
             <li > <?php if($type == "choice") { ?> <button class='btn btn-primary' onclick="window.location.href = './index.php?r=teacher/addChoice'">添加新题</button><?php }
                        else if($type == "filling"){ ?> <button class='btn btn-primary' onclick="window.location.href = './index.php?r=teacher/addFill'">添加新题</button> <?php }
                        else if($type == "question"){ ?> <button class='btn btn-primary' onclick="window.location.href = './index.php?r=teacher/addQuestion'">添加新题</button><?php }?> </li>
        </ul>
        
    </div>  
    
     <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <center>
                <table style="margin-left: -60px;">
                    <tr><td><font class="fl" style="position: relative;right: 20px; color: #fff">课程:&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #f46500"><?php echo $currentClass['className']?></font></td></tr>
                    <tr><td><font class="fl" style="color: #fff">课时:&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #f46500"><?php echo $currentLesson['lessonName']?></font></td></tr>
                    <tr><td><font class="fl" style="position: relative;left: 20px;color: #fff">作业:&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #f46500">
                            <?php  if(Tool::clength($suite['suiteName'])<=7)
                                        $a= $suite['suiteName'];
                                    else
                                        $a= Tool::csubstr($suite['suiteName'], 0, 7)."...";?>
                            <a  title="<?php echo $suite['suiteName']?>" style="text-decoration:none;"><?php echo $a?></font></a>
                        </td></tr>
                </table>                
            </center>                   
        </ul>
    </div>      
    <a href="./index.php?r=teacher/AssignWork&&classID=<?php echo $currentClass['classID'];?>&&lessonID=<?php echo $currentLesson['lessonID'];?>"  class="btn btn-primary">返回</a>
</div>

<div class="span9" style="height: 800px">
    <iframe  src="./index.php?r=teacher/ToOwnWork&&type=<?php echo $type;?>&&suiteID=<?php echo $suite['suiteID'];?>" id="iframe1" frameborder="0" scrolling="no" width="770px" height="400px" name="iframe1"></iframe>
    
    <iframe  src="./index.php?r=teacher/ToAllWork&&type=<?php echo $type;?>&&suiteID=<?php echo $suite['suiteID'];?>" id="iframe2" frameborder="0" scrolling="no" width="770px" height="400px" name="iframe2"></iframe>

</div >
    
    
<script>
    var currentPage1;  
    function refresh()
    {     
    $('#iframe1').attr("src","./index.php?r=teacher/ToOwnWork&&type=<?php echo $type;?>&&suiteID=<?php echo $suite['suiteID'];?>&&page="+currentPage1);   
   }
   
   function setCurrentPage1(page)
   {  
       currentPage1 = page;    
   }
   
   function repeatAdd(result){
       window.wxc.xcConfirm(result, window.wxc.xcConfirm.typeEnum.info);
    }
   
</script>
    