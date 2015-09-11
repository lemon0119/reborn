<script src="<?php echo JS_URL;?>exerJS/ocxJS.js"></script>
<link href="<?php echo CSS_URL; ?>ywStyle.css" rel="stylesheet" type="text/css" />
<?php
require 'suiteSideBar.php';
?>
<div class="span9">
    <div class="hero-unit" align="center">
            <?php 
                Yii::app()->session['exerID'] = $exerOne['exerciseID'];
            ?>
        <table border = '0px'>
            <tr>
                <td><h3><?php echo $exerOne['title']?></h3></td>
                
            </tr>
        </table>
        <br/>
        <input id="content" type="hidden" value="<?php echo $exerOne['content'];?>">
        <div id ="templet" class="questionBlock" front-size ="25px" onselectstart="return false"></div>
        <br/>
        <object id="typeOCX" type="application/x-itst-activex" 
                clsid="{ED848B16-B8D3-46c3-8516-E22371CCBC4B}" 
                width ='750' height='350' 
                event_OnChange="onChange">
        </object>
    </div>
</div>

<script>
    
   
    
    $(document).ready(function(){
        //菜单栏变色
        $("li#li-look-<?php echo $exerOne['exerciseID'];?>").attr('class','active');
        //显示题目
        createFont("#000000", document.getElementById("content").value);
    });
    //document.getElementById("templet").style.font_size = "25px";
    function createFont(color, text){
        var father = document.getElementById("templet");
        var f = document.createElement("font");
        f.style = "color:"+color;
        //var t = document.createTextNode(text);
        //f.appendChild(t);
        f.innerHTML = text;
        father.appendChild(f);
    }
    
    
</script>
