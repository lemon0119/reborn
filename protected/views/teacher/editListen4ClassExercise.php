<div class="span3">
        <div class="well" style="padding: 8px 0;">
            <ul class="nav nav-list">
                        <li style="margin-top:10px">
                                <button onclick="window.location.href = './index.php?r=teacher/classExercise4Listen&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>'" style="height: 35px;top: 1px;" class="btn_4superbig">返&nbsp;&nbsp;&nbsp;回</button>
                        </li>
                </form>
                        <li class="divider"></li>
                        <li class="nav-header"><i class="icon-typing" style="position:relative;bottom:7px;left:"></i>课堂练习</li>
                         <li ><a href="./index.php?r=teacher/classExercise4Type&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-th" style="position:relative;bottom:7px;left:"></i> 键打练习</a></li>
                        <li><a href="./index.php?r=teacher/classExercise4Look&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-eye-open" style="position:relative;bottom:7px;left:"></i> 看打练习</a></li>
                        <li  class="active" ><a href="./index.php?r=teacher/classExercise4Listen&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-headphones" style="position:relative;bottom:7px;left:"></i> 听打练习</a></li>
                </ul>
        </div>
</div>
    
<div class="span9">        
<?php if(!isset($_GET['action'])) {?>
<legend><h3 style="display:inline-block;">编辑听打练习题</h3>
<span>(支持mp3及wav格式,最大1G)</span></legend>
<?php } else if($_GET['action'] == 'look') {?>
<legend><h3>查看听打练习题</h3></legend>
<?php }?>

    <form class="form-horizontal" method="post" action="./index.php?r=teacher/editListen4ClassExercise&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>&&oldfilename=<?php echo $filename;?>&&exerciseID=<?php echo $exerciseID;?>" id="myForm" enctype="multipart/form-data"> 
        <fieldset>
        <?php $listenpath = "resources/".$filepath.$filename;?>
            
       <div class="control-group">
            <label class="control-label" for="input01">题目</label>
       <div class="controls">
                <textarea name="title" style="width:450px; height:20px;" id="input01" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>><?php echo $title; ?></textarea>
            </div>
        </div>
            
<div class="control-group">
        <label class="control-label" ></label>
        <?php if(file_exists($listenpath)){?>
            <audio  src = "<?php echo $listenpath;?>" preload = "auto" controls></audio>
        <?php }else {?>
            <p style="color: red">原音频文件丢失或损坏！</p>
        <?php } ?>
</div>
            
        <?php if(!isset($_GET['action'])) {?>   
            <div class="control-group">
                <label class="control-label" for="input02">修改</label>
                <div class="controls">
                    (音频）<input type="file" name="modifyfile" id="input02"> 
                   （答案）<input type="file" name="modifytxtfile" id="txtfile"> 
                </div>
            </div>
            <div class="control-group" id="upload" >
                <div class="controls">
                        <img src="./img/default/upload-small.gif"  alt="正在努力上传。。"/>
                        正在上传，请稍等...
                        <div id="number">0%</div>
                </div>

            </div>
        <?php } else if($_GET['action'] == 'look') {?>
        <?php }?>     
<!--         <div class="control-group" id="div2">
                <label class="control-label" >速度</label>
                <div class="controls">
                    <input type="text" name="speed" style="width:40px; height:15px;" id="input2" maxlength="3"  value="<?php// echo $speed; ?>" <?php// if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>>         
                    词/分钟
                </div>            
            </div>   -->
        <div class="control-group">
            <label class="control-label" for="input03">听打答案</label>
            <div class="controls">               
                <textarea name="content" style="width:450px; height:200px;" id="input03" <?php if(isset($_GET['action'])){ if($_GET['action']=='look'){echo 'disabled="disabled"'; } }?>><?php echo $content; ?></textarea>
                <br>字数：<span id="wordCount">0</span> 字
            </div>
        </div> 
            
        <div class="form-actions">
            <?php if(!isset($_GET['action'])) {?> 
                <button type="submit" class="btn btn-primary">修改</button>
            <?php }?>
                 <?php if(isset($_GET['nobar'])){ ?>
                <a class="btn btn-primary" href="./index.php?r=teacher/classExercise4Listen&&nobar=yes&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>">返回</a>
                            <?php }else{ ?>
                                  <a class="btn btn-primary" href="./index.php?r=teacher/classExercise4Listen&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>">返回</a>
                            <?php }?>
        </div>
            
        </fieldset>
    </form>   
</div>
<script>     
$(document).ready(function(){
    var v=<?php echo Tool::clength($content);?>;
    $("#wordCount").text(v);
    $("#upload").hide();
    <?php if($result!=="")
            echo " window.wxc.xcConfirm('$result', window.wxc.xcConfirm.typeEnum.info);";?>
});

function fetch_progress(){
        $.get('./index.php?r=teacher/getProgress',{ '<?php echo ini_get("session.upload_progress.name"); ?>' : 'test'}, function(data){
                var progress = parseInt(data);   
                $('#number').html(progress + '%');
                if(progress < 100){
                        setTimeout('fetch_progress()', 100);
                }else{           
        }
        }, 'html');
    }


$("#myForm").submit(function(){
    var requirements = $("#input01")[0].value;
    if(requirements === ""){
        window.wxc.xcConfirm('题目不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    var A = $("#input03")[0].value;
    if(A === ""){
        window.wxc.xcConfirm('内容不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    var uploadFile = $("#input02")[0].value;
    if(uploadFile != ""){
       $("#upload").show();
       setTimeout('fetch_progress()', 1000);
     }
});
</script>
