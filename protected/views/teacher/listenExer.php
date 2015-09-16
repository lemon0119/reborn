<!--声音播放-->
<script src="<?php echo JS_URL;?>audioplayer.js"></script>
<!--打字控件-->
<script src="<?php echo JS_URL;?>exerJS/ocxJS.js"></script>

<?php
//2015-8-3 宋杰 判断加载suitesidebar还是examsiderbar

require 'suiteSideBar.php';
 ;?>
<div class="span9">
        <div class="hero-unit"  align="center">
            <table border = '0px'>
                <tr>
                    <td width = '250px'><h3><?php echo $exerOne['title']?></h3></td>
                </tr>
            </table>
            <?php 
                $listenpath = EXER_LISTEN_URL.$exerOne['filePath'].$exerOne['fileName'];
                Yii::app()->session['exerID'] = $exerOne['exerciseID'];
            ?>
            <div align="left">
                <audio src = "<?php echo $listenpath;?>" preload = "auto" controls></audio>
            </div>
            <input id="content" type="hidden" value="<?php echo $exerOne['content'];?>">
            <object id="typeOCX" type="application/x-itst-activex" 
                    clsid="{ED848B16-B8D3-46c3-8516-E22371CCBC4B}" 
                    width ='750' height='350' 
                    event_OnStenoPress="onStenoPress">
            </object>
            <br/>
        </div>
</div>
<script>
    $(document).ready(function(){
        //菜单栏变色
        $("li#li-listen-<?php echo $exerOne['exerciseID'];?>").attr('class','active');
    });
    
</script>