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
            <h3 >导出月份</h3>
        <div>    
<table class="table table-bordered table-striped">
            <?php  
foreach ($result as $v)
{
    if($v["(substring(time,1,7))"]  == ''){
        echo"没有人签到";        
    }else{
       $time = $v["(substring(time,1,7))"];
       echo"<a href = 'index.php?r=teacher/exportabsence&&time=$time'>";echo $time;echo"</a>"; echo '&nbsp&nbsp&nbsp&nbsp&nbsp';
}
}
?>
    </table>
                <div align=center>
    <?php   
       // $this->widget('CLinkPager',array('pages'=>$pages));
    ?>
    </div>
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
   
    }   
    function submit(){
         window.close();
    }
</script>