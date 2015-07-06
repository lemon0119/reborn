 <script src="<?php echo JS_URL;?>jquery-form.js"></script>
<script>
    $(document).ready(function(){
            $("div.span9").find("a").click(function(){
                var url = $(this).attr("href");
                //$(this).attr("href","#");
                if(url.indexOf("index.php") > 0){
                    $("#cont").load(url);
                    return false;//阻止链接跳转
                }
            });

    var options = {   
            target:'#cont',   // 需要刷新的区域 
            //type:'post',
            //dataType:'json',
            //resetForm:false,
           // timeout:10000
        };

    $("#myForm").submit(function(){ 
          $(this).ajaxSubmit(options);   
      // 为了防止普通浏览器进行表单提交和产生页面导航（防止页面刷新？）返回false   
           return false;   
      });  
    });
</script>
<div class="span9">
<div class="hero-unit">

<h3>向<?php echo $classID;?>班添加学生</h3>
<form id="myForm" method="post" action="./index.php?r=admin/infoClass&&classID=<?php echo $classID;?>&&action=addStu" onkeydown="if(event.keyCode==13){return false;}"> 
<input type="checkbox" name="all" onclick="check_all(this,'checkbox[]')" />全选/全不选</p> 
<table class="table table-bordered table-striped">
<thead>
    <tr>
        <th>选择</th>
        <th>学号</th>
        <th>用户名</th>
        <th>班级</th>
    </tr>
</thead>
        <tbody>        
            <?php foreach($posts as $model):?>
            <tr>
                <td style="width: 75px"> <input type="checkbox" name="checkbox[]" value=<?php echo $model['userID'];?> /> </td>                     
                <td style="width: 75px"><?php echo $model['userID'];?></td>
                <td><?php echo $model['userName'];?></td>
                <td>无</td>
            </tr>            
            <?php endforeach;?> 
        </tbody>
</table>
<input type="submit" name="submit" value="提交"> 
</form>   

<!-- js控制全选/取消全选  -->    
<script type="text/javascript"> 
function check_all(obj,cName) 
{    
var checkboxs = document.getElementsByName(cName); 
for(var i=0;i<checkboxs.length;i++){checkboxs[i].checked = obj.checked;} 
} 
</script> 

</div>
</div>

