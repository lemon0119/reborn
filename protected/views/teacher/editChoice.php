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
            
        <h3>修改单选题</h3>
        <form id="myForm" method="post" action="./index.php?r=admin/choiceLst&&action=edit&&exerciseID=<?php echo $exerciseID;?>"> 
        题目：    <textarea name="requirements" style="width:600px; height:50px;"><?php echo $requirements;?></textarea>
        <br>
        <?php
                    $optArr = explode("$$",$options);
                    $mark = 'A';
                    foreach ($optArr as $aOpt) {
                        if($mark==$answer)
                        {
                             echo '<input type="radio" value="'.$mark.'" name="answer" checked="checked">&nbsp'.$mark.'</input>&nbsp&nbsp';
                             echo '<input type="text" name="'.$mark .'" style="width: 120px" value="'.$aOpt.'">';
                        }
                        else{
                            echo '<input type="radio" value="'.$mark.'" name="answer">&nbsp'.$mark.'</input>&nbsp&nbsp';
                            echo '<input type="text" name="'.$mark.'" style="width: 120px" value="'.$aOpt.'">';
                        }
                        echo '<br/>';
                        $mark++;
                    }
                    echo '<br/>';
                    ?>
        <input type="submit" name="submit" value="提交"> 
        </form>   

        </div>
        </div>

