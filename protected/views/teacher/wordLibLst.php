        <head>
            <meta charset="utf-8">
            <title>亚伟速录</title>
            <link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
            <link href="<?php echo CSS_URL; ?>site.css" rel="stylesheet">
            <link rel="stylesheet" type="text/css" href="/reborn/assets/afd5bfab/pager.css"/>
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
<!-- 学生列表-->
<div class="span9" style=" overflow: hidden;width: 600px; height: 800px">
    <div  style="overflow: auto;width: 600px; height: 750px" >
    <h2>词库列表</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="font-center">选择</th>
                <th class="font-center">目录</th>
                <th class="font-center">词库名称</th>
            </tr>
        </thead>
        <tbody>            
            <?php foreach ($list as $l): ?>
                <tr>
                    <td class="font-center" > <input type="checkbox" name="checkbox[]" value="<?php echo $l['name']; ?>" /> </td>
                    <td class="font-center" ><?php echo $l['list']; ?></td>
                    <td class="font-center" ><?php echo $l['name']; ?></td>
                </tr> 
            <?php endforeach; ?> 
        </tbody>
    </table>
   </div>
    <!-- 学生列表结束 -->
    <div  style="width: 600px;" >
    <button onclick="clickOK()">确定</button>
    <button onclick="clickQX()">取消</button>
   </div>
</div>


<script>
    function clickOK(){
    var checkboxs = document.getElementsByName('checkbox[]');
    var libs=new Array();
    var j =0;
    var flag = 0;
        for (var i = 0; i < checkboxs.length; i++) {
           if(checkboxs[i].checked){
                libs[j] = checkboxs[i].value;
                j++;
           }
        }  
      window.opener.getContent(libs); 
      window.close();
    }
    
    function clickQX(){
        window.close();       
    }
</script>