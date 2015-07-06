<!--声音播放-->
<script src="<?php echo EXER_JS_URL;?>jquery.min.js"></script>
<script src="<?php echo EXER_JS_URL;?>audioplayer.js"></script>
<script src="<?php echo EXER_JS_URL;?>jquery.min.js"></script>
<script src="<?php echo EXER_JS_URL;?>bootstrap.min.js"></script>
<script src="<?php echo EXER_JS_URL;?>site.js"></script>

<script src="<?php echo EXER_JS_URL;?>ocxJS.js"></script>
<script src="<?php echo EXER_JS_URL;?>time.js"></script>
<script>
    $(document).ready(function(){	
        $("div.span9").find("a").click(function(){
            var url = $(this).attr("href");
            //确实是连接跳转
            if(url.indexOf("index.php") > 0){
                $("#cont").load(url);
                return false;//阻止链接跳转
            }
        });

    });
</script>

<div class="span9">
        <div class="hero-unit">
            <?php 
                $this->widget('CLinkPager',array('pages'=>$pages));
                $i = 0;
            ?>
            
            <?php foreach($exercise as $row):?>
            <table border = '0px'>
                <tr>
                    <td width = '200px'><h3><?php echo $row['title']?></h3></td>
                    <td width = '200px'>时间：<span id="time">00:00:00</span></td>
                    <td width = '200px'>速度：<span id="wordps">0</span> 字/分</td>
                </tr>
            </table>
            <?php 
                $i++;
                $listenpath = EXER_LISTEN_URL.$row['filePath'].$row['fileName'];
                Yii::app()->session['exerID'] = $row['exerciseID'];
            ?>
            <audio src = "<?php echo $listenpath;?>" preload = "auto" controls></audio>
            <input id="content" type="hidden" value="<?php echo $row['content'];?>">
            <object id="typeOCX" type="application/x-itst-activex" 
                    clsid="{ED848B16-B8D3-46c3-8516-E22371CCBC4B}" 
                    width ='600' height='350' 
                    event_OnStenoPress="onStenoPress">
            </object>
            <br/>
            <?php require  Yii::app()->basePath."\\views\\student\\submitAnswer.php";?>
            <?php 
                endforeach;
                if($i == 0)
                    echo '<h3>没有听打练习！</h3>';
            ?>
        </div>
</div>
<script type="text/javascript">
    addEvent(typeOCX, "OnStenoPress", onStenoPress);
</script>