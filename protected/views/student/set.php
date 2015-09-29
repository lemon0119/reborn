<script type="text/javascript">
function test()
{
	 var temp = document.getElementById("input04");
	 //对电子邮件的验证
	 var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
	 if(!myreg.test(temp.value))
	 {
	     alert('请输入有效的email！');
	     temp.value="";
	     myreg.focus();
	     return false;
	 } 
}
</script>
<div class="span9">
    
    <div class="span_set">
    <h3>设置密码</h3>
    <form id="myForm" method="post" action="./index.php?r=student/set" enctype="multipart/form-data"> 
        <fieldset>
            <legend>填写信息</legend>
            <div class="control-group">
                <label class="control-label" for="input01">旧密码</label>
                <div class="controls">
                        <input name="old" type="password" class="input-xlarge" id="input01" style="height: 30px;"/>
                </div>
                <label class="control-label" for="input02">新密码</label>
                <div class="controls">
                        <input name="new1" type="password" class="input-xlarge" id="input02" style="height: 30px;"/>
                </div>
                <label class="control-label" for="input03">确认密码</label>
                <div class="controls">
                        <input name="defnew" type="password" class="input-xlarge" id="input03" style="height: 30px;"/>
                </div>
                <label class="control-label" for="input03">邮箱</label>
                <div class="controls">
                    <input name="email" type="text" class="input-xlarge" id="input04"  onblur="test()" style="height: 30px;" value="<?php echo $mail; ?>"/>
                </div>
                
                <label class="control-label" for="input03">上传头像：</label>
                <input type="hidden" name="flag" id="flag" value="1" />
                <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
                <input type="file" name="file"  id="file"/><br>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">确认</button> 
                <a href="./index.php?r=student/index" class="btn">返回</a>
            </div>
        </fieldset>
    </form>   
    </div>
    <div class="span_set fl">
        <div class="controls" style="border:1px solid blue;">
                    <?php if($picAddress=='0') {}else{?>
                    
                    <img style="width:200px;height:100px;" src="<?php echo $picAddress; ?>">
                    <?php }?>
                </div>
        
    </div>
</div>

<script>  
$(document).ready(function(){
    var result = '<?php echo $result;?>';
    if(result === '1')
        alert('密码修改成功！');
    else if(result === '0')
        alert('密码修改失败！'); 
    else if(result=='old error')
        alert('原密码错误！'); 
}); 
$("#myForm").submit(function(){
    var old = $("#input01")[0].value;
    var new1 = $("#input02")[0].value;
    var defnew=$("#input03")[0].value;
    var email=$("#input04")[0].value;
    if(new1===defnew){
    }else
    {
    	alert('不一致');
        alert(new1+','+defnew);
    	$("#input02")[0].value="";
    	$("#input03")[0].value="";
        return false;
    }
    if(new1 === "" ||old === ""||defnew === "" ){
        alert('密码不能为空');
        return false;
    }
    if(email === "" ){
        alert('email不能为空');
        return false;
    }
        
});
</script>