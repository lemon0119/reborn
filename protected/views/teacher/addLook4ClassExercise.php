<div class="span3">
        <div class="well" style="padding: 8px 0;">
            <ul class="nav nav-list">
                        <li style="margin-top:10px">
                             <?php if(isset($_GET['nobar'])){ ?>
                            <?php }else{ ?>
                               <button onclick="window.location.href = './index.php?r=teacher/startCourse&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>'" style="height: 35px;top: 1px;" class="btn_bigret"></button>
                            <?php }?>
                        </li>
                </form>
                        <li class="divider"></li>
                        <li class="nav-header"><i class="icon-typing"></i>课堂练习</li>
                         <?php if(isset($_GET['nobar'])){ ?>
                                 <li ><a href="./index.php?r=teacher/classExercise4Type&&nobar=yes&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-th"></i> 键打练习</a></li>
                        <li class="active"><a href="./index.php?r=teacher/classExercise4Look&&nobar=yes&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-eye-open"></i> 看打练习</a></li>
                        <li ><a href="./index.php?r=teacher/classExercise4Listen&&nobar=yes&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-headphones"></i> 听打练习</a></li>
                            <?php }else{ ?>
                                  <li ><a href="./index.php?r=teacher/classExercise4Type&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-th"></i> 键打练习</a></li>
                        <li class="active"><a href="./index.php?r=teacher/classExercise4Look&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-eye-open"></i> 看打练习</a></li>
                        <li ><a href="./index.php?r=teacher/classExercise4Listen&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-headphones"></i> 听打练习</a></li>
                            <?php }?>
                         
                </ul>
        </div>
</div>

    
<div class="span9">        
<?php if(!isset($action)) {?>
<h3>编辑键位练习题</h3>
<?php } else if($action == 'look') {?>
<h3>查看键位练习题</h3>
<?php }?>

    <?php if(isset($_GET['nobar'])){ ?>
                        <form class="form-horizontal" method="post" action="./index.php?r=teacher/addLook4ClassExercise&&nobar=yes&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>" id="myForm"> 
                            <?php }else{ ?>
                              <form class="form-horizontal" method="post" action="./index.php?r=teacher/addLook4ClassExercise&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>" id="myForm"> 
                            <?php }?>
        <fieldset>
        <?php if(!isset($action)) {?>
            <legend>填写题目</legend>
        <?php } else if($action == 'look') {?>
            <legend>查看题目</legend>
        <?php }?>
        <div class="control-group">
            <label class="control-label" for="input01">题目</label>
            <div class="controls">
                <textarea name="title" style="width:450px; height:20px;" id="input01"></textarea>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="input02">看打答案</label>
            <div class="controls">               
                <textarea name="content" style="width:450px; height:200px;"></textarea>
            </div>
        </div> 
        <div class="form-actions">
            <?php if(!isset($action)) {?> 
                <button type="submit" class="btn btn-primary">添加</button>
            <?php }?>
        </div>
        </fieldset>
    </form>   
</div>
<script>     
$(document).ready(function(){
    var result = <?php echo "'$result'";?>;
    if(result === '1')
    window.wxc.xcConfirm('添加看打练习成功！', window.wxc.xcConfirm.typeEnum.success);
    else if(result === '0')
    window.wxc.xcConfirm('添加看打练习失败！', window.wxc.xcConfirm.typeEnum.error);
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
    var A = $("#input02")[0].value;
    if(A === ""){
        window.wxc.xcConfirm('答案不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    
});
</script>

