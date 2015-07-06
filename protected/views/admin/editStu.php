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
            
        <h3>更改学生信息</h3>
        <p>学号:<?php echo $id;?></p>
        <p>姓名:<?php echo $name;?></p>
        <p>班级:<?php if($class=="0")
                        echo "无";
                        else echo $class;?></p>
        <br>
        <form id="myForm" method="post" action="./index.php?r=admin/stuLst&&action=edit&&id=<?php echo $id;?>&&name=<?php echo $name;?>&&class=<?php echo $class;?>" onkeydown="if(event.keyCode==13){return false;}"> 
        密码：<input type="text" name="password">
        <br>
        <input type="submit" name="submit" value="提交"> 
        </form>   
        <?php
        if(isset($shao))
        {
            if($shao=='null')
            {
                echo "输入全不能为空";
            }
        }
        ?>
        
        </div>
        </div>

