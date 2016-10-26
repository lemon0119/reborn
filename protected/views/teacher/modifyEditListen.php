
 <div class="span9">      
<?php if(!isset($action)) {?>
        <h3>编辑听打练习题</h3>
    <?php } else if($action == 'look') {?>
        <h3>查看听打练习题</h3>
    <?php }?>

    <form class="form-horizontal" method="post" action="./index.php?r=teacher/editListenInfo&&oldfilename=<?php echo $filename;?>&&exerciseID=<?php echo $exerciseID;?>" id="myForm" enctype="multipart/form-data"> 
       <fieldset>
    <?php if(!isset($action)) {?>
        <legend>编辑题目</legend>
    <?php } else if($action == 'look') {?>
        <legend>查看题目</legend>
    <?php }?>
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
               (音频）<input type="file" name="modifyfile" id="input02"> 
              （答案）<input type="file" name="modifytxtfile" id="txtfile">       
               </div>
           </div>
        <div class="control-group" id="div2">
                <label class="control-label" >速度</label>
                <div class="controls">
                    <input type="text" name="speed" style="width:40px; height:15px;" id="input2" maxlength="3"  value="<?php echo $speed; ?>" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>>         
                    词/分钟
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
            <a href="./index.php?r=teacher/returnFromAddListen&&page=<?php echo Yii::app()->session['lastPage'];?>" class="btn btn-primary">返回</a>
        </div>
          
        </fieldset>
    </form>   
</div>
<script>     
$(document).ready(function(){
    <?php if(isset($result))
            echo " window.wxc.xcConfirm('$result', window.wxc.xcConfirm.typeEnum.success);";?>
});



$("#myForm").submit(function(){
    var requirements = $("#input01")[0].value;
    if(requirements === ""){
        window.wxc.xcConfirm('题目不能为空', window.wxc.xcConfirm.typeEnum.info);
        return false;
    }
    var A = $("#input03")[0].value;
    if(A === ""){
        window.wxc.xcConfirm('内容不能为空', window.wxc.xcConfirm.typeEnum.info);
        return false;
    }
});
</script>