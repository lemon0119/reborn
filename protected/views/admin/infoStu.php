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
    });
</script>
<div class="span9">
        <div class="hero-unit">
            
        <h3>学生信息</h3>
        
        <p>学号:<?php echo $id;?></p>
        <br>
        <p>姓名:<?php echo $name;?></p>
        <br>
        <p>班级:<?php if($class=="0")
                        echo "无";
                        else echo $class;
               ?></p>
        <br>
        <button type="button" onclick="back()">确定</button>
        <script>
        function back()
        {
             $("#cont").load("./index.php?r=admin/stuLst");
        }
        </script>
        
            
        </div>
        </div>

