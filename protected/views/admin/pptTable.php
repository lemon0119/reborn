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
                    $mydir = dir($pdir); 
                    while($file = $mydir->read())
                    { 
                            if((!is_dir("$pdir/$file")) AND ($file!=".") AND ($file!="..")AND(substr($file,0,2)!="~$")) 
                            {
                ?>
                <tr>
                    <td>
                        <?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?>
                    </td>
                    <td>
                        <a href="./index.php?r=admin/lookPpt&&pdir=<?php echo $pdir;?>&&ppt=<?php  
                                                                            $fileName   = iconv("gb2312","UTF-8",$file);
                                                                            $len        = strlen($fileName);
                                                                            $path       = substr($fileName,0,$len-4);
                                                                            echo $path;?>"><img title="查看" src="<?php echo IMG_URL; ?>detail.png"
                               onclick=""></a>
                        <a href="<?php echo "$pdir".iconv("gb2312","UTF-8",$file);?>" download="<?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?>" ><img title="下载" src="<?php echo IMG_URL; ?>icon_download.png"></a>
                        <a href="#" onclick="deletePpt('<?php echo $pdir;?>','<?php echo iconv("gb2312","UTF-8",$file);?>')" id="dele"><img title="删除" src="<?php echo IMG_URL; ?>delete.png"></a>
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
function deletePpt(pdir,file) {
        var option = {
                title: "警告",
                btn: parseInt("0011",2),
                onOk: function(){
                        window.location.href="./index.php?r=admin/deletePpt&&pdir="+pdir+"&&ppt="+file;
                }
        }
        window.wxc.xcConfirm("您确定删除吗？", "custom", option);
    }
</script>    