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
<div class="span9" style="width: 450px; height: 350px">
    <h2>被禁言学生列表</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="font-center">选择</th>
                <th class="font-center">学号</th>
                <th class="font-center">姓名</th>
                <th class="font-center">班级</th>
            </tr>
        </thead>
        <tbody>
        <form id="deleForm" method="post" action="./index.php?r=teacher/recoverForbidStu&&classID=<?php echo $classID?>" > 
            <input type="hidden" name="flag" value="1" />
            <?php foreach ($stuLst as $model): ?>
                <tr>
                    <td class="font-center" > <input type="checkbox" name="checkbox[]" value="<?php echo $model['userID']; ?>" /> </td>
                    <td class="font-center" ><?php echo $model['userID']; ?></td>
                    <td class="font-center" ><?php echo $model['userName']; ?></td>
                    <td class="font-center" ><?php
                        if ($model['classID'] == "0") {
                            echo "无";
                        } else {
                            $classID = $model['classID'];
                            $sqlClass = TbClass::model()->find("classID = $classID");
                            echo $sqlClass['className'];
                        }
                        ?></td>
                </tr> 
            <?php endforeach; ?> 
        </form>
        </tbody>
    </table>
    <input type="checkbox" name="all" onclick="check_all(this, 'checkbox[]')" style="margin-bottom: 3px"> 全选
    <button onclick="deleCheck()">恢复禁言</button>
    <!-- 学生列表结束 -->
    <!-- 显示翻页标签 -->
    <div align=center>
        <?php
       $this->widget('CLinkPager', array('pages' => $pages));
        ?>
    </div>
    <!-- 翻页标签结束 -->
    <!-- 右侧内容展示结束-->
</div>
<script>
    $(document).ready(function(){
       <?php if(isset($_POST['flag'])){ ?>
            window.opener.location.reload();
            window.close();
        <?php  }?>
    });      
    function check_all(obj, cName)
    {
        var checkboxs = document.getElementsByName(cName);
        for (var i = 0; i < checkboxs.length; i++) {
            checkboxs[i].checked = obj.checked;
        }
    }
    function deleCheck() {
    var checkboxs = document.getElementsByName('checkbox[]');
    var flag = 0;
        for (var i = 0; i < checkboxs.length; i++) {
           if(checkboxs[i].checked){
                flag=1;
                break;
           }
        } 
        if(flag===0){
           window.wxc.xcConfirm('未选中任何学生', window.wxc.xcConfirm.typeEnum.info);
        }else{
             var option = {
						title: "警告",
						btn: parseInt("0011",2),
						onOk: function(){
							$('#deleForm').submit();
						}
					};
					window.wxc.xcConfirm("确定恢复选中学生的禁言吗？", "custom", option);
        }
       
    }
    $(document).ready(function () {
        $("#li-stuLst").attr("class", "active");
    });
</script>
