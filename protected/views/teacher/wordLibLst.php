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
    <div class="form-actions"  style="margin: 0px;width: 750px;" >
        <button class="fl btn btn-primary" onclick="clickOK()" style="margin-left: 190px">确定</button>
        
        <button style="position: relative;left: 43px" class="fl btn btn-primary" onclick="clickQX()">返回</button>
        
<!--        <div style="margin-left: 50px" class="fl"><form action="./index.php?r=teacher/SelectWordLib&&libstr=<?php echo $_GET['libstr'];?>&&upload=true" 
          method="post" id="form-upload" enctype="multipart/form-data"><font style="color: #595959;font-family: fantasy;margin-right: 10px">添加私人词库</font><input type="file" name="file" id="file" />-->
                <button style="margin-left: 90px" class="btn btn-primary" onclick="deleteLib()">删除</button></form>
        </div>
   </div>
</div>
        </body>
<script>
     $(document).ready(function () {
         <?php
            if(isset($uploadResult)){
                if($uploadResult=='上传成功'){
                    echo 'window.parent.success("'.$uploadResult.'"); ';
                }else if($uploadResult!=''){
                    echo 'window.parent.error("'.$uploadResult.'"); ';
                }
            }
         ?>
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
    function deleteLib(){
        var checkboxs = document.getElementsByName('checkbox[]');
        var libs=new Array();
        var j=0;
        for (var i = 0; i < checkboxs.length; i++) {
           if(checkboxs[i].checked){
                libs[j] = checkboxs[i].value;
                j++;
           }
        }  
       window.parent.deleteLib(libs);
    }
</script>