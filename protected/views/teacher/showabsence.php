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
            
<div class="span9" style="width: 450px; height: 380px">
    <div class="control-group" id="div1">
            <h3 >缺勤信息</h3>
        <div>    
<table class="table table-bordered table-striped">
    <th>学号</th><th>姓名</th><th>联系电话</th>
            <?php  
foreach ($result as $v)
{
    if($v['userID'] == ''){
        echo"没有缺勤";        
    }else{
        echo '<tr>';
    echo '<td>';
    echo $v['userID'];
    echo '</td>';
    echo '<td>';    
    echo $v['userName'].'</br>';
    echo '</td>';
        echo '<td>';
        if($v['phone_number']== ''){echo '未录入该学生手机号';}
    echo $v['phone_number'].'</br>';
    echo '</td>';
    echo '</tr>';
    }
}

?>
    </table>
                <div align=center>
    <?php   
       // $this->widget('CLinkPager',array('pages'=>$pages));
    ?>
    </div>
            共计
   <?php 
   $totle = count($result);
    echo $totle;
    ?> 学生缺勤
            <button onclick="submit();"  class="btn btn-primary">确定</button>
    </form>
 </div>   
</div>
            </body>
    </html>
<script>
window.onload=isSign();
    function isSign()
    {
       $.ajax({
       type: "GET",
            url: "index.php?r=student/IsReleaseSign&&classID=<?php echo $classID; ?>&&lessonID=<?php echo $lessonID; ?>",
            success: function (data) {
             if (data['TeacherSign_ID'].length === 0){ 
                 document.getElementById("div1").innerHTML = "<h1>该课还没有进行签到。。。</h1>";            
             }   
           },            
                error: function(xhr, type, exception){
document.getElementById("div1").innerHTML = "<h1> 出错了。。。</h1>"; 
            }
       });    
    }   
    function submit(){
         window.close();
    }
</script>