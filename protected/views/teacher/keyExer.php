<script src="<?php echo JS_URL;?>exerJS/timep.js"></script>
<?php require 'suiteSideBar.php'; ?>

<div class="span9">
    <div class="hero-unit"  align="center">
        <?php Yii::app()->session['exerID'] = $exerOne['exerciseID'];?>
        <table border = '0px'>
            <tr>
                <td width = '200px'><h3 ><?php echo $exerOne['title']?></h3></td>
            </tr>
        </table>
        <br/>
        <div id ="templet" class ="questionBlock" onselectstart="return false">
            <font id="id_right"style="color:#808080"> </font>
            <font id="id_wrong" style="color:#ff0000"> </font>
            <font id="id_new" style="color:#000000"> </font>
        </div>
        <br/><br/>
        <div style="width: 750px; height: 350px;">
           
        </div>
        
    </div>
    <input id="id_content" type="hidden" value="<?php echo $exerOne['content'];?>"> 
</div>

<script>
    $(document).ready(function(){
        $("li#li-key-<?php echo $exerOne['exerciseID'];?>").attr('class','active');
    });
    document.getElementById("id_new").firstChild.nodeValue = document.getElementById("id_content").value;
    
</script>