 <html lang="zh-cn"><!--<![endif]--> 
        <head>
            <meta charset="utf-8">
            <title>亚伟速录</title>
            <link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
            <link href="<?php echo CSS_URL; ?>site.css" rel="stylesheet">
            <script src="<?php echo JS_URL; ?>jquery.min.js"></script>
            <script src="<?php echo JS_URL; ?>bootstrap.min.js"></script>
            <script src="<?php echo JS_URL; ?>site.js"></script>
<!--            改变alter样式-- extensions/xcConfirm 工具包下-- --> 
                <link rel="stylesheet" type="text/css" href="<?php echo XC_Confirm; ?>css/xcConfirm.css"/>
		<script src="<?php echo XC_Confirm; ?>js/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
		<script src="<?php echo XC_Confirm; ?>js/xcConfirm.js" type="text/javascript" charset="utf-8"></script>
<!--            -->
             <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        </head>
        <body>
            
<div class="span9" style="width: 450px;height: 280px">
    <?php if(isset($_GET['teacherID'])){ ?>
        <form id="form-editSchedule" method="POST" action="./index.php?r=admin/editScheduleOne&&sequence=<?php echo $_GET['sequence'];?>&day=<?php echo $_GET['day'];?>&teacherID=<?php echo Yii::app()->session['teacherId'];?>">
    <?php }else if(isset($_GET['classID'])){ ?>
        <form id="form-editSchedule" method="POST" action="./index.php?r=admin/editScheduleOne&&sequence=<?php echo $_GET['sequence'];?>&day=<?php echo $_GET['day'];?>&classID=<?php echo Yii::app()->session['classId'];?>">
    <?php }?>
        <input type="hidden" name="flag" value="1" />
    <div class="control-group" id="div1">
            <h3 >科目时间</h3>
            <div class="controls">               
<!--                <span>1.&nbsp;</span><input type="text"  name="in1" style="width:280px; height:30px;" id="input1" maxlength="15" value="<?php// $courseInfo = explode("&&", $result['courseInfo']); echo $courseInfo[0];?>">                -->
                <select name="hour1" style="width:46px;height:30px" id="input1" >
                    <option value="" >时</option>
                    <?php
                    $courseInfo1 = explode("&&", $result['courseInfo']);
                    $courseInfoh1=explode(":",$courseInfo1[0]);
                    for($i=1;$i<=24;$i++){ 
                        $j=$i;
                        if(strlen($i)==1){
                            $j="0".$i;
                        }
                        ?>
                    <option value="<?php echo $j; ?>" 
                            <?php if($courseInfoh1[0]==$j){ ?>
                            selected="selected"<?php } ?> style="color:black"><?php echo $j; ?></option>
                    <?php } ?>
                </select>&nbsp;&nbsp;:&nbsp;&nbsp;
                <select name="min1" style="width:46px;height:30px" id="input2">
                    <option value="" >分</option>
                    <?php for($i=0;$i<60;$i++){ 
                         $j=$i;
                        if(strlen($i)==1){
                            $j="0".$i;
                        }
                        ?>
                    <option value="<?php echo $j; ?>" 
                            <?php 
                            if(isset($courseInfoh1[1])){
                            if($courseInfoh1[1]==$j){ ?>
                            selected="selected" <?php } }?> style="color:black"><?php echo $j; ?></option>
                    <?php } ?>
                </select>
            </div>             
        </div>
        <div class="control-group"  id="div2">           
            <div class="controls">               
<!--                <span>&nbsp;至</span><input type="text"  name="in2" style="width:280px; height:30px;display:none" id="input2" maxlength="15" value="<?php //if(isset($courseInfo[3])){echo $courseInfo[3];} ?>">                -->
            </div>             
        </div>
        <div class="control-group"   id="div3">           
            <div class="controls">               
                <select name="hour2" style="width:46px;height:30px" id="input3">
                    <option value="" >时</option>
                    <?php
                    if(isset($courseInfo1[2])){
                     $courseInfoh2=explode(":",$courseInfo1[2]);
                    }
                    for($i=1;$i<=24;$i++){ 
                         $j=$i;
                        if(strlen($i)==1){
                            $j="0".$i;
                        }
                        ?>
                    <option value="<?php echo $j; ?>" 
                            <?php 
                            if(isset($courseInfoh2[0])){
                                if($courseInfoh2[0]==$j){ ?>
                                selected="selected"<?php } } ?>
                            style="color:black" ><?php echo $j; ?></option>
                    <?php } ?>
                </select>&nbsp;&nbsp;:&nbsp;&nbsp;
                <select name="min2" style="width:46px;height:30px" id="input4">
                    <option value="" >分</option>
                    <?php for($i=0;$i<60;$i++){ 
                         $j=$i;
                        if(strlen($i)==1){
                            $j="0".$i;
                        }
                        ?>
                    <option value="<?php echo $j; ?>" 
                            <?php if(isset($courseInfoh2[1])){ 
                                if($courseInfoh2[1]==$j){?>
                            selected="selected" <?php } } ?> style="color:black" ><?php echo $j; ?></option>
                    <?php } ?>
                </select>            </div>             
        </div>
        <button type="submit"  class="btn btn-primary">确定</button>
        <a onclick="deleteAll();" class="btn btn-primary">清空</a>
    </form>
    
</div>
            </body>
    </html>
<script>
    $(document).ready(function(){
       <?php if(isset($_POST['flag'])){ ?>
            window.opener.location.reload();
            window.close();
        <?php  }?>
    });
    
    function deleteAll(){
        $("#input4")[0].value = "";
        $("#input3")[0].value = "";
        $("#input2")[0].value = "";
        $("#input1")[0].value = "";
    }
    
</script>
