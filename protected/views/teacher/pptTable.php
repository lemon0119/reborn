<?php
    $username = Yii::app()->user->name;
    $role = Yii::app()->session['role_now'];
    $userid = Yii::app()->session['userid_now'];             
    $pptFilePath =$role."/".$userid."/".$classID."/".$on."/ppt/"; 
    $pdir = "./resources/".$pptFilePath;
    
    $courseID    = TbClass::model()->findCourseIDByClassID($classID);
    $dir         = "resources/admin/001/$courseID/$on/ppt/";
    $publicdir   = "resources/public/ppt";
                  if (!is_dir($publicdir)) {
                            mkdir($publicdir, 0777);
                           }
    
?>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>名称</th>
            <th>上传者</th>
            <th>操作</th>
        </tr>
    </thead>
        <tbody>
            <?php
                $mydir = dir($dir); 
                while($file = $mydir->read())
                { 
                        if((!is_dir("$dir/$file")) AND ($file!=".") AND ($file!="..")) 
                        {
            ?>
            <tr>
                <td>
                    <?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?>
                </td>
                <td>管理员</td>
                <td>
                    <a href="./index.php?r=teacher/lookPpt&&ppt=<?php  $fileName   = iconv("gb2312","UTF-8",$file);
                                                                        $len    = strlen($fileName);
                                                                        $path   = substr($fileName,0,$len-4);
                                                                        echo $path;?>&&pdir=<?php echo $dir;?>&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>&&ispublic=0&&isnew=0"><img src="<?php echo IMG_URL; ?>detail.png" title="查看"></a>
                    <a href="<?php echo "$dir".iconv("gb2312","UTF-8",$file);?>" download="<?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?>"><img src="<?php echo IMG_URL; ?>icon_download.png" title="下载"></a>

                </td>
            </tr>
            <?php     
                        } 
                } 
                $mydir->close(); 
            ?>
            
            
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
                <td><?php echo Yii::app()->session['userName'];?></td>
                <td>
                    <a href="./index.php?r=teacher/lookPpt&&ppt=<?php  $fileName   = iconv("gb2312","UTF-8",$file);
                                                                        $len    = strlen($fileName);
                                                                        $path   = substr($fileName,0,$len-4);

                                                                        echo $path;?>&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>&&ispublic=0&&isnew=0"><img title="查看" src="<?php echo IMG_URL; ?>detail.png"></a>
                    <a href="<?php echo "$pdir".iconv("gb2312","UTF-8",$file);?>" download="<?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?>"><img title="下载" src="<?php echo IMG_URL; ?>icon_download.png"></a>
                    <a href="#" onclick="dele('<?php echo iconv("gb2312","UTF-8",$file);?>','<?php echo $classID;?>','<?php echo $progress;?>','<?php echo $on;?>',0)"><img title="删除"  src="<?php echo IMG_URL; ?>delete.png"></a>

                </td>
            </tr>
            
            
            <?php     
//                        } 
//                } 
//                $mydir->close(); 
//            ?>
                   <?php
//            $mydir = dir($publicdir); 
//            while($file = $mydir->read())
//            { 
//                    if((!is_dir("$publicdir/$file")) AND ($file!=".") AND ($file!="..")) 
//                    {
//        ?>
<!--        <tr>
            <td>
                //<?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?>
            </td>
            <td>公共</td>
            <td>
                <a href="./index.php?r=teacher/lookPpt&&ppt=<?php  $fileName   = iconv("gb2312","UTF-8",$file);
                                                                        $len    = strlen($fileName);
                                                                        $path   = substr($fileName,0,$len-4);
                                                                        echo $path;?>&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>&&ispublic=1&&isnew=0"><img src="<?php echo IMG_URL; ?>detail.png" title="查看"></a>
                <a href="<?php echo "$publicdir/".iconv("gb2312","UTF-8",$file);?>" target="_blank" download="<?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?>"><img src="<?php echo IMG_URL; ?>icon_download.png" title="下载"></a>
                <a href="#" onclick="dele('<?php// echo iconv("gb2312","UTF-8",$file);?>','<?php// echo $classID;?>','<?php// echo $progress;?>','<?php //echo $on;?>',1)" id="dele"><img src="<?php// echo IMG_URL; ?>delete.png" title="删除"></a>
            </td>
        </tr>-->
        <?php     
                    } 
            } 
            $mydir->close(); 
        ?>     
        </tbody>
</table>
<script>
function dele(ppt2,classID2,progress2,on2,ispublic) {
    console.log("y");
        var option = {
                title: "警告",
                btn: parseInt("0011",2),
                onOk: function(){
                        window.location.href="./index.php?r=teacher/deletePpt&&ppt="+ppt2+"&&classID="+classID2+"&&progress="+progress2+"&&on="+on2+"&&ispublic="+ispublic+"&&isnew="+'0';
                }
        }
        window.wxc.xcConfirm("您确定删除吗？", "custom", option);
    }
</script>
