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
    var reg=/^[A-Za-z0-9]+$/;
    if(!reg.test(temp)||temp.length<3||temp.length>15){
         window.wxc.xcConfirm('密码必须为3-15位的数字和字母的组合', window.wxc.xcConfirm.typeEnum.info);
        document.getElementById("input01").value="";
    }
}
function long(){
    var temp = document.getElementById("input02").value;
    var reg=/^[A-Za-z0-9]+$/;
    if(!reg.test(temp)||temp.length<3||temp.length>15){
         window.wxc.xcConfirm('密码必须为3-15位的数字和字母的组合', window.wxc.xcConfirm.typeEnum.info);
        document.getElementById("input02").value="";
    }
}
function long2(){
    var temp = document.getElementById("input03").value;
    var reg=/^[A-Za-z0-9]+$/;
    if(!reg.test(temp)||temp.length<3||temp.length>15){
         window.wxc.xcConfirm('密码必须为3-15位的数字和字母的组合', window.wxc.xcConfirm.typeEnum.info);
        document.getElementById("input03").value="";
    }
}
</script>
<div class="setB" style="text-align: center;">
    <br>
    <br>
    <h3>设置密码</h3>
    <br>
    <br>
    <div>
    <form id="myForm" method="post" action="./index.php?r=teacher/set"> 
        <table style="margin-left: auto;margin-right: auto; ">
            <tr>
                <td>
                    <label class="control-label" for="input01">旧密码<h style="color:red;">*</h></label>
                </td>
                <td>
                    <input name="old" type="password"  onblur="long0()" class="input-xlarge" id="input01" style="height: 30px;"/>
                </td>
            </tr>
            <tr>
                <td>
                   <label class="control-label" for="input02">新密码<h style="color:red;">*</h></label> 
                </td>
                <td>
                    <input name="new1" type="password"  onblur="long()" class="input-xlarge" id="input02" style="height: 30px;"/>
                </td>
            </tr>
            <tr>
                <td>
                   <label class="control-label" for="input03">确认密码<h style="color:red;">*</h> </label>
                </td>
                <td>
                    <input name="defnew" type="password" onblur="long2()" class="input-xlarge" id="input03" style="height: 30px;"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="control-label" for="input03">邮箱<h style="color:red;">*</h></label>
                </td>
                <td>
                    <input name="email" type="text" class="input-xlarge" id="input04"  onblur="test()" style="height: 30px;" value="<?php echo $mail; ?>"/>
                </td>
            </tr>
            <tr style="height: 30px;"></tr>
            <tr>
                <td>

                </td>
                <td >
                    <a id="stuBack" href="./index.php?r=teacher/index"></a>
                    <a id="DeterMine1" href="#" name="submit" onclick="judge()"></a> 
                </td>
            </tr>
        </table>
    </form>  
    </div>
</div>

<script>  
$(document).ready(function(){
    var result = '<?php echo $result;?>';
    if(result == '1')
    window.wxc.xcConfirm('密码修改成功！', window.wxc.xcConfirm.typeEnum.success);
    else if(result == '0')
    window.wxc.xcConfirm('密码修改失败！', window.wxc.xcConfirm.typeEnum.error);
    else if(result=='old error')
    window.wxc.xcConfirm('原密码错误！', window.wxc.xcConfirm.typeEnum.error);
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
        window.wxc.xcConfirm('新密码和确认密码不一致', window.wxc.xcConfirm.typeEnum.warning);
    	$("#input02")[0].value="";
    	$("#input03")[0].value="";
        return false;
    }
    if(new1 === "" ||old === ""||defnew === "" ){
        window.wxc.xcConfirm('密码不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    if(email === "" ){
        window.wxc.xcConfirm('email不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    $('#myForm').submit();
    return false
}
</script>