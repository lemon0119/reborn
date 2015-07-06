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
            
        <h3>添加看打练习</h3>
        <form id="myForm" method="post" action="./index.php?r=admin/lookLst&&action=add"> 
        名称：<input type="text" name="title">
        <br>
        内容：
        <br>
        <textarea name="content" style="width:600px; height:300px;" ></textarea>
        <br>
        <input type="submit" name="submit" value="提交"> 
        </form>   
        <?php
        if(isset($shao))
        {
             echo $shao;
        }
        ?>
        
        </div>
        </div>

    
    <?php
       //显示操作结果
       if(isset($result))
       {
           if(!empty($result))
           {
               echo "<script>var result = '$result';</script>";
           }
       }
    ?>
    <script>
        if(result != null){
            alert(result);
            result = null;
        }
    </script>


