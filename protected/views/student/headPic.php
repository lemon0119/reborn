<div class="span3">
       <div class="well" style="padding: 8px 0;height: 565px;">
           <li class="nav-header"><h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;个人设置</h4></li> 
           <li class="nav-header" id="two">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="cont2" href="./index.php?r=student/stuInformation">个人资料</a></li>
           <li class="nav-header">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="./index.php?r=student/set">修改密码</a></li>   
           <li class="nav-header">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="./index.php?r=student/headPic">修改头像</a></li>   
        </div>
</div>
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