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
            
<div class="span9" style="width: 450px;">
    <?php if(isset($_GET['teacherID'])){ ?>
        <form id="form-editSchedule" method="POST" action="./index.php?r=admin/editSchedule&&sequence=<?php echo $_GET['sequence'];?>&day=<?php echo $_GET['day'];?>&teacherID=<?php echo Yii::app()->session['teacherId'];?>">
    <?php }else if(isset($_GET['classID'])){ ?>
        <form id="form-editSchedule" method="POST" action="./index.php?r=admin/editSchedule&&sequence=<?php echo $_GET['sequence'];?>&day=<?php echo $_GET['day'];?>&classID=<?php echo Yii::app()->session['classId'];?>">
    <?php }?>
        <input type="hidden" name="flag" value="1" />
    <div class="control-group" id="div1">
            <h3 >课程信息</h3>
            <div class="controls">               
                <span>1.&nbsp;</span><input type="text" name="in1" style="width:280px; height:30px;" id="input1" maxlength="12" value="<?php $courseInfo = explode("&&", $result['courseInfo']); echo $courseInfo[0];?>">                
                <a class="btn btn-primary" onclick="addIn()"><i class="icon-plus-editwork icon-white"></i></a> <a class="btn btn-primary" onclick="deleteIn()"><i class="icon-minus icon-white"></i></a>
            </div>             
        </div>
        <div class="control-group"  id="div2">           
            <div class="controls">               
                <span>2.&nbsp;</span><input type="text"  name="in2" style="width:280px; height:30px;" id="input2" maxlength="12" value="<?php if(isset($courseInfo[1])){echo $courseInfo[1];} ?>">                
            </div>             
        </div>
        <div class="control-group" <?php if(isset($courseInfo[2])){   echo '';}else{  echo 'style="display: none"';}?>  id="div3">           
            <div class="controls">               
                <span>3.&nbsp;</span><input type="text" name="in3" style="width:280px; height:30px;"  id="input3" maxlength="12" value="<?php if(isset($courseInfo[2])){echo $courseInfo[2];}?>">                
            </div>             
        </div>
        <button type="submit"  class="btn btn-primary">确定</button>
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
    
    var divCount = 2;
    function addIn()
    {
        if (divCount < 4) {
            divCount++;
            $("#div" + divCount).show();
        } else
        {
            window.wxc.xcConfirm("最多添加三个选项", window.wxc.xcConfirm.typeEnum.info);
        }
    }

 function deleteIn()
    {
        if(divCount>1){
            $("#div"+divCount).hide();           
            divCount--;
        }else
        {
            window.wxc.xcConfirm("当前仅剩一项！", window.wxc.xcConfirm.typeEnum.info);
        }
    }
    

</script>
