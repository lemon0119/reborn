
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