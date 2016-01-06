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
<body style="background-image: none;background-color: #fff">
    <h2>词库列表</h2>
<div  style=" overflow: hidden;width: 750px; height: 490px">
    <div  style="overflow: auto;width: 750px; height: 420px" >
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
                    <td class="font-center" >
                         <?php 
                         $n=0;
                             foreach ($libstr  as $v) {
                                 if($v==$l['name']){
                                     $n=1;
                                     break;
                                 } 
                              }
                              if($n==1){?>
                                    <input type="checkbox"  checked="true" name="checkbox[]" value="<?php echo $l['name']; ?>" />
                                  <?php }else{?>
                                    <input type="checkbox"  name="checkbox[]" value="<?php echo $l['name']; ?>" />
                                    <?php }?>
                    </td>
                    <td class="font-center" ><?php echo $l['list']; ?></td>
                    <td class="font-center" ><?php echo $l['name']; ?></td>
                </tr> 
            <?php endforeach; ?> 
        </tbody>
    </table>
   </div>
    <!-- 学生列表结束 -->
    <div class="form-actions"  style="width: 750px;" >
        <button class="btn btn-primary" onclick="clickOK()">确定</button>
        
        <button style="position: relative;left: 2px" class="btn" onclick="clickQX()">返回</button>
   </div>
</div>

        </body>
<script>
$(document).ready(function(){
    //点击选择词库在新界面中之前选中的进行勾选，没写完-_-
//        var libstr = window.opener.getLibs(); 
//        var words = libstr.split("$$");
//        var checkboxs = document.getElementByName("checkbox[]");
//        for(var i=0;i<checkboxs.length;i++){
//            for(var j=0;j<words.length;j++){
//                if(checkboxs[i] == words[j])
//                    checkboxs[i].checked = true;
//            }
//        }
});
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
      window.parent.getContent(libs); 
      window.parent.getDivAddKeyBack();
    }
    
    function clickQX(){
        window.parent.getDivAddKeyBack();
    }
</script>