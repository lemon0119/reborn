<div class="span3">
        <div class="well" style="padding: 8px 0;">
            <ul class="nav nav-list">
                        <li style="margin-top:10px">
                                <?php if(isset($_GET['nobar'])){ ?>
                            <?php }else{ ?>
                               <button onclick="window.location.href = './index.php?r=teacher/startCourse&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>'" style="height: 35px;top: 1px;" class="btn_4superbig">返&nbsp;&nbsp;&nbsp;回</button>
                            <?php }?>
                        </li>
                </form>
                        <li class="divider"></li>
                        <li class="nav-header"><i class="icon-typing"></i>课堂练习</li>
                         <?php if(isset($_GET['nobar'])){ ?>
                                 <li ><a href="./index.php?r=teacher/classExercise4Type&&nobar=yes&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-th"></i> 键打练习</a></li>
                        <li ><a href="./index.php?r=teacher/classExercise4Look&&nobar=yes&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-eye-open"></i> 看打练习</a></li>
                        <li class="active"><a href="./index.php?r=teacher/classExercise4Listen&&nobar=yes&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-headphones"></i> 听打练习</a></li>
                            <?php }else{ ?>
                                  <li ><a href="./index.php?r=teacher/classExercise4Type&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-th"></i> 键打练习</a></li>
                        <li ><a href="./index.php?r=teacher/classExercise4Look&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-eye-open"></i> 看打练习</a></li>
                        <li class="active"><a href="./index.php?r=teacher/classExercise4Listen&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-headphones"></i> 听打练习</a></li>
                            <?php }?>
                </ul>
        </div>
</div>

<div class="span9">        
<h3 style="display:inline-block;">添加听打练习题</h3>
<span>(支持mp3及wav格式,最大1G)</span>
<?php if(isset($_GET['nobar'])){ ?>
                        <form class="form-horizontal" method="post" action="./index.php?r=teacher/addListen4ClassExercise&&nobar=yes&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>" id="myForm" enctype="multipart/form-data"> 
                            <?php }else{ ?>
                              <form class="form-horizontal" method="post" action="./index.php?r=teacher/addListen4ClassExercise&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>" id="myForm" enctype="multipart/form-data"> 
                            <?php }?>
        <fieldset>
           <legend>填写题目</legend>
        <div class="control-group">
            <label class="control-label" for="input01">题目</label>
            <div class="controls">
                <textarea name="title" style="width:450px; height:20px;" id="input01"><?php echo $title;?></textarea>
            </div>
        </div>
        <div class="control-group">
                <label class="control-label" for="input02">文件</label>
                <div class="controls">
                    <input type="file" name="file" id="input02">      
                    <div id="upload" style="display:inline;" hidden="true">
                    <img src="./img/default/upload-small.gif"  alt="正在努力上传。。"/>
                        正在上传，请稍等...
                    </div>
                </div>
           </div>
        <div class="control-group">
            <label class="control-label" for="input03">听打答案</label>
            <div class="controls">               
                <textarea name="content" style="width:450px; height:200px;" id="input03"><?php echo $content;?></textarea>
            </div>
        </div> 
        <div class="form-actions">
            <?php if(!isset($action)) {?> 
                <button type="submit" class="btn btn-primary">添加</button>
            <?php }?>
                <?php if(isset($_GET['nobar'])){ ?>
                <a class="btn" href="./index.php?r=teacher/classExercise4Listen&&nobar=yes&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>">返回</a>
                            <?php }else{ ?>
                                  <a class="btn" href="./index.php?r=teacher/classExercise4Listen&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>">返回</a>
                            <?php }?>
        </div>
        </fieldset>
    </form>   
</div>
<script>     
$(document).ready(function(){
    $("#upload").hide();
    
    var result = <?php echo "'$result'";?>;
    if(result === '1')
    window.wxc.xcConfirm('添加听打练习成功！', window.wxc.xcConfirm.typeEnum.success,{
        onOk:function(){
             window.location.href="./index.php?r=teacher/addListen4ClassExercise&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>";
        }
    });
    else if(result === '0')
    window.wxc.xcConfirm('添加听打练习失败！', window.wxc.xcConfirm.typeEnum.error);
    else if(result != 'no')
    {      
        window.wxc.xcConfirm(result, window.wxc.xcConfirm.typeEnum.info);
    }
        
});
$("#myForm").submit(function(){
    <?php if(isset($_GET['nobar'])){?>
        opener.iframReload();
        <?php }?>
    var requirements = $("#input01")[0].value;
    if(requirements === ""){
        window.wxc.xcConfirm('题目内容不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    var uploadFile = $("#input02")[0].value;
    if(uploadFile === "")
    {
        window.wxc.xcConfirm('上传文件不能为空', window.wxc.xcConfirm.typeEnum.warning);
    return false;
    }
    
    var A = $("#input03")[0].value;
        if(A === ""){
        window.wxc.xcConfirm('内容不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    $("#upload").show();
});
</script>
  
    


