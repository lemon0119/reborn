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
        
    </div>  
    
     <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <center>
                <table>
                    <tr><td><?php echo $currentClass['className']?></td></tr>
                    <tr><td><?php echo $currentLesson['lessonName']?></td></tr>
                    <tr><td><?php echo $suite['suiteName']?></td></tr>
                </table>                
            </center>                   
        </ul>
    </div>  
</div>


 <div class="span9">        
<h3>编辑听打练习题</h3>


    <form class="form-horizontal" method="post" action="./index.php?r=teacher/editListenInfo&&oldfilename=<?php echo $filename;?>&&exerciseID=<?php echo $exerciseID;?>" id="myForm" enctype="multipart/form-data"> 
        <fieldset>
            <legend>填写题目</legend>
            
        <?php $listenpath = "resources/".$filepath.$filename;?>
            
       <div class="control-group">
            <label class="control-label" for="input01">题目</label>
       <div class="controls">
                <textarea name="title" style="width:450px; height:20px;" id="input01"><?php echo $title; ?></textarea>
            </div>
        </div>
            
          <div class="control-group">
              
              <label class="control-label" ></label>
            <audio  src = "<?php echo $listenpath;?>" preload = "auto" controls></audio>
         </div>
            
            
           <div class="control-group">
               <label class="control-label" for="input02">修改</label>
               <div class="controls">
               <input type="file" name="modifyfile" id="input02">      
               </div>
           </div>
            
        <div class="control-group">
            <label class="control-label" for="input03">内容</label>
            <div class="controls">               
                <textarea name="content" style="width:450px; height:200px;" id="input03"><?php echo $content; ?></textarea>
            </div>
        </div> 
            
        <div class="form-actions">
            <?php if(!isset($action)) {?> 
                <button type="submit" class="btn btn-primary">修改</button>
            <?php }?>
            <a href="./index.php?r=teacher/returnFromAddListen&&page=<?php echo Yii::app()->session['lastPage'];?>" class="btn">取消</a>
        </div>
            
        </fieldset>
    </form>   
</div>
<script>     
$(document).ready(function(){
    <?php if(isset($result))
            echo "alert('$result');";?>
});



$("#myForm").submit(function(){
    var requirements = $("#input01")[0].value;
    if(requirements === ""){
        alert('题目不能为空');
        return false;
    }
    var A = $("#input03")[0].value;
    if(A === ""){
        alert('内容不能为空');
        return false;
    }
});
</script>