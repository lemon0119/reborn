<link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
<link href="<?php echo CSS_URL; ?>bootstrap-responsive.min.css" rel="stylesheet">
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>名称</th>
            <th>操作</th>
        </tr>
    </thead>
            <tbody>        
                <?php
                    $mydir = dir($vdir); 
                    while($file = $mydir->read())
                    { 
                            if((!is_dir("$vdir/$file")) AND ($file!=".") AND ($file!="..")) 
                            {
                ?>
                <tr>
                    <td>
                        <?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?>
                    </td>
                    <td>
                        <a href="./index.php?r=admin/lookVideo&&video=<?php echo iconv("gb2312","UTF-8",$file);?>&&vdir=<?php echo $vdir;?>"><img src="<?php echo IMG_URL; ?>detail.png">查看</a>
                        <a href="<?php echo "$vdir".iconv("gb2312","UTF-8",$file);?>" target="_blank"  download="<?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?>"><img src="<?php echo IMG_URL; ?>edit.png">下载</a>
                        <a href="./index.php?r=admin/deleteVideo&&video=<?php echo iconv("gb2312","UTF-8",$file);?>&&vdir=<?php echo $vdir;?>" id="dele"><img src="<?php echo IMG_URL; ?>delete.png">删除</a>
                    </td>
                </tr>
                <?php     
                            } 
                    } 
                    $mydir->close(); 
                ?>
            </tbody>
</table>
<script>  
    $(document).ready(function(){
        $('#dele').bind('click', function(e){
            e.preventDefault();
        });
        $("#dele").click(function(){
            $.get($(this).attr("href"),function(data,status){
                $("#video-table").load("./index.php?r=admin/videoTable&&vdir=<?php echo $vdir;?>");
                window.wxc.xcConfirm(data, window.wxc.xcConfirm.typeEnum.confirm);
            });  
            return false;     
        });
    });
</script>