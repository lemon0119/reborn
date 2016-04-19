<div class="span3">
        <div class="well" style="padding: 8px 0;">
            <ul class="nav nav-list">
                        <li style="margin-top:10px">
                                <button onclick="window.location.href = './index.php?r=teacher/classExercise4Look&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>'" style="height: 35px;top: 1px;" class="btn_4superbig">返&nbsp;&nbsp;&nbsp;回</button>
                        </li>
                </form>
                        <li class="divider"></li>
                        <li class="nav-header"><i class="icon-typing"></i>课堂练习</li>
                         <li ><a href="./index.php?r=teacher/classExercise4Type&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-th"></i> 键打练习</a></li>
                        <li class="active"><a href="./index.php?r=teacher/classExercise4Look&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-eye-open"></i> 看打练习</a></li>
                        <li ><a href="./index.php?r=teacher/classExercise4Listen&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-headphones"></i> 听打练习</a></li>
                </ul>
        </div>
</div>

    
<div class="span9">        
<?php  if(!isset($_GET['action'])) {?>
<legend><h3>编辑看打练习题</h3><span style="color: red;font-size: 15px">(内容不可超出4000字)</span></legend>
<?php } else if($_GET['action'] == 'look') {?>
<legend><h3>查看看打练习题</h3><span style="color: red;font-size: 15px">(内容不可超出4000字)</span></legend>
<?php }?>

    <form class="form-horizontal" method="post" action="./index.php?r=teacher/editLook4ClassExercise&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>&&exerciseID=<?php echo $exerciseID;?>" id="myForm"> 
        <fieldset>
        <div class="control-group">
            <label class="control-label" for="input01">题目</label>
            <div class="controls">
                <textarea name="title" style="width:450px; height:20px;" id="input01" <?php if(isset($_GET['action'])){ if($_GET['action']=='look'){echo 'disabled="disabled"'; } }?>><?php echo $title; ?></textarea>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="input02">看打答案</label>
            <div class="controls">               
                <textarea name="content" style="width:450px; height:200px;"<?php if(isset($_GET['action'])){ if($_GET['action']=='look'){echo 'disabled="disabled"'; } }?> ><?php echo $content; ?></textarea>
                <br>字数：<span id="wordCount">0</span> 字
            </div>
        </div> 
        <div class="form-actions">
            <?php if(!isset($_GET['action'])) {?> 
                <button type="submit" class="btn btn-primary">修改</button>
            <?php }?>
                 <?php if(isset($_GET['nobar'])){ ?>
                <a class="btn btn-primary" href="./index.php?r=teacher/classExercise4Look&&nobar=yes&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>">返回</a>
                            <?php }else{ ?>
                                  <a class="btn btn-primary" href="./index.php?r=teacher/classExercise4Look&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>">返回</a>
                            <?php }?>
        </div>
        </fieldset>
    </form>   
</div>
<script>     
$(document).ready(function(){
    <?php if(isset($result))
            echo "window.wxc.xcConfirm('$result', window.wxc.xcConfirm.typeEnum.info);";?>
    var v=<?php echo Tool::clength($content);?>;
    $("#wordCount").text(v);
});
$("#myForm").submit(function(){
    var requirements = $("#input01")[0].value;
    if(requirements === ""){
        window.wxc.xcConfirm('题目内容不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    var A = $("#input02")[0].value;
    if(A === ""){
        window.wxc.xcConfirm('答案不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
});
</script>



