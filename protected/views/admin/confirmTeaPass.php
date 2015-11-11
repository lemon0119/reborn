<?php require 'teaSideBar.php';?>
<!-- 学生列表-->
<div class="span9">
    <h3><font color="#ff0000">删除后数据将无法恢复，请谨慎操作!</font></h3>
    <form action="./index.php?r=admin/hardDeleteTea" class="form-horizontal" method="post" id="form-addStu">
        <fieldset>
            <legend>请输入您的管理员密码</legend>
            <div class="control-group">
                <label  class="control-label" for="password">密码</label>
                    <div class="controls">
                        <input name="password" type="password" class="input-xlarge" id="password" value="" />
                    </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">确认删除</button>　　　　　　　　
                <a href="./index.php?r=admin/recycleTea" class="btn" onclick="">取　　消</a>
            </div>
        </fieldset>
    </form>
</div>
<script>
$(document).ready(function(){
      document.getElementById("password").focus();
    //侧边菜单选中变色
    $("#li-recycleStu").attr("class","active");
});
<?php if(isset($wrong)){
    echo "alert('$wrong');";
    unset($wrong);
}?>
</script>
