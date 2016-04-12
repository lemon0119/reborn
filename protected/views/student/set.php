<script type="text/javascript">
function test()
{
	 var temp = document.getElementById("input04");
	 //对电子邮件的验证
	 var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
	 if(!myreg.test(temp.value))
	 {
             window.wxc.xcConfirm('请输入有效的email！', window.wxc.xcConfirm.typeEnum.info);
	     temp.value="";
	     myreg.focus();
	     return false;
	 } 
}
function long0(){
    var temp = document.getElementById("input01").value;
    usertipsSpan = document.getElementById("usertips");  
    usertipsSpan.style.color = "red";  
    var reg=/^[A-Za-z0-9]+$/;
    if(!reg.test(temp)||temp.length<3||temp.length>15){
        usertipsSpan.innerHTML='密码必须为3-15位的数字和字母的组合';
        document.getElementById("input01").value="";
    }else {  
        usertipsSpan.innerHTML='';  
    }
}
function long(){
    var temp = document.getElementById("input02").value;
    usertipsSpan = document.getElementById("usertips2");  
    usertipsSpan.style.color = "red";  
    var reg=/^[A-Za-z0-9]+$/;
    if(!reg.test(temp)||temp.length<3||temp.length>15){
        usertipsSpan.innerHTML='密码必须为3-15位的数字和字母的组合';
        document.getElementById("input02").value="";
    }else {  
        usertipsSpan.innerHTML='';  
    }
}
function long2(){
    var temp = document.getElementById("input03").value;
    usertipsSpan = document.getElementById("usertips3");  
    usertipsSpan.style.color = "red";  
    var reg=/^[A-Za-z0-9]+$/;
    if(!reg.test(temp)||temp.length<3||temp.length>15){
        usertipsSpan.innerHTML='密码必须为3-15位的数字和字母的组合';
        document.getElementById("input03").value="";
    }else {  
        usertipsSpan.innerHTML='';  
    }
}
</script>

<div class="span3">
       <div class="well" style="padding: 8px 0;height: 565px;">
           <li class="nav-header"><h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;个人设置</h4></li> 
           <li class="nav-header" id="two">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="cont2" href="./index.php?r=student/stuInformation">个人资料</a></li>   
           <li class="nav-header" id="one"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="cont1" href="./index.php?r=student/set">修改密码</a></li>   
           <li class="nav-header" id="two">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="cont2" href="./index.php?r=student/headPic">修改头像</a></li>   
        </div>
</div>
<div class="span9">
    
    <div class="span_set">
    <h3>设置密码</h3>
    <form id="myForm" method="post" action="./index.php?r=student/set" enctype="multipart/form-data"> 
        <fieldset>
            <legend>填写信息</legend>
            <div class="control-group">
                <label class="control-label" for="input01">旧密码<h style="color:red;">*</h></label>
                <div class="controls">
                        <input name="old" type="password" onblur="long0()" class="input-xlarge" id="input01" style="height: 30px;"/><span id="usertips" style="margin-left: 15px;"></span> 
                </div>
                <label class="control-label" for="input02">新密码<h style="color:red;">*</h></label>
                <div class="controls">
                        <input name="new1" type="password" onblur="long()" class="input-xlarge" id="input02" style="height: 30px;"/><span id="usertips2" style="margin-left: 15px;"></span> 
                </div>
                <label class="control-label" for="input03">确认密码<h style="color:red;">*</h></label>
                <div class="controls">
                        <input name="defnew" type="password" onblur="long2()" class="input-xlarge" id="input03" style="height: 30px;"/><span id="usertips3" style="margin-left: 15px;"></span> 
                </div>
                <label class="control-label" for="input03">邮箱<h style="color:red;">*</h></label>
                <div class="controls">
                    <input name="email" type="text" class="input-xlarge" id="input04"  onblur="test()" style="height: 30px;" value="<?php echo $mail; ?>"/>
                </div>
                <!--
                <label class="control-label" for="input03">上传头像：</label>
                <input type="hidden" name="flag" id="flag" value="1" />
                <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
                <input type="file" name="file"  id="file"/><br>   -->
            
            <div style="margin-top: 30px;">
                <a class="btn btn-primary" href="./index.php?r=student/index">取消</a>
                <a class="btn btn-primary" href="#" name="submit" onclick="judge()">确定</a> 
            </div>
        </fieldset>
    </form>   
    </div>
    
     
</div>

<script>  
$(document).ready(function(){
    var result = '<?php echo $result;?>';
    if(result === '1')
    window.wxc.xcConfirm('密码修改成功！', window.wxc.xcConfirm.typeEnum.success);
    else if(result === '0')
    window.wxc.xcConfirm('密码修改失败！', window.wxc.xcConfirm.typeEnum.error);
    else if(result=='old error')
    window.wxc.xcConfirm('原密码错误！', window.wxc.xcConfirm.typeEnum.warning);
}); 
function judge(){
    var old = $("#input01")[0].value;
    var new1 = $("#input02")[0].value;
    var defnew=$("#input03")[0].value;
    var email=$("#input04")[0].value;
    if(old!="" &&new1!=""&&old==new1){
        window.wxc.xcConfirm('新旧密码不能一样', window.wxc.xcConfirm.typeEnum.info);
        $("#input02")[0].value="";
    	$("#input03")[0].value="";
        return false;
    }
    if(new1===defnew){
    }else
    {
        window.wxc.xcConfirm('新密码和确认密码不一致', window.wxc.xcConfirm.typeEnum.info);
    	$("#input02")[0].value="";
    	$("#input03")[0].value="";
        return false;
    }
    if(new1 === "" ||old === ""||defnew === "" ){
        window.wxc.xcConfirm('密码不能为空', window.wxc.xcConfirm.typeEnum.info);
        return false;
    }
    if(email === "" ){
        window.wxc.xcConfirm('email不能为空', window.wxc.xcConfirm.typeEnum.info);
        return false;
    }
    $('#myForm').submit();
    return false
}
</script>