<div class="span9">
    <h2>头像</h2>
    <div id ="ppt-table"></div>
    <form class="form-horizontal" method="post" action="./index.php?r=student/addHeadPic" id="myForm"  enctype="multipart/form-data"> 
        <div  class="control-group" style="margin-left: 100px;">
            <?php if($picAddress=='0'||$picAddress==null) {}else{?>
                <img style="width:200px;height:100px;" src="<?php echo $picAddress; ?>">
            <?php }?>
        </div>
        <div style="margin-left: 100px;">
            <label >上传头像：(*仅限gif,png,jpeg,pjpeg)</label>
                <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
                <input type="file" name="file"  id="file"/>
                <button type="submit" class="btn btn-primary">上传</button>
           </div>
        </div>
    </form>
</div>

<script>  
$(document).ready(function(){
    <?php if(isset($result)){?>
    var result = <?php echo "'$result'";?>;
    if(result !== '0')
    window.wxc.xcConfirm(result, window.wxc.xcConfirm.typeEnum.confirm);
    <?php }?>
        
    
}); 
    </script>