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
            
       
        <script type="text/javascript">
        $(document).ready(function(){
            $("#file").hide();
            $("#label").hide();
            $("#qiehuan").click(function(){
            $("#file").toggle(500);
            $("#label").toggle(500);
         });
        });
        </script>   

        
        <h3>修改<?php echo $title;?></h3>
        <form id="myForm" method="post" action="./index.php?r=admin/listenLst&&action=edit&&exerciseID=<?php echo $exerciseID;?>" enctype="multipart/form-data"> 
        现有音频：<?php echo $filename;?>
        <br/>
        <input id="qiehuan" type="checkbox" name="checkbox[]" value="yes" />替换MP3文件
        <label id="label" for="file">Filename:</label>
        <input type="file" name="file" id="file" /> 
        <br />
        <br>
        内容：
        <br>
        <textarea name="content" style="width:600px; height:300px;" ><?php echo $content;?></textarea>
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

