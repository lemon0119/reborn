<link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
<link href="<?php echo CSS_URL; ?>bootstrap-responsive.min.css" rel="stylesheet">
<?php
    $username = Yii::app()->user->name;
    $role = Yii::app()->session['role_now'];
    $userid = Yii::app()->session['userid_now'];             
    $pptFilePath =$role."/".$userid."/".$classID."/".$on."/ppt/"; 
    $pdir = "./resources/".$pptFilePath;
    
    $courseID    = TbClass::model()->findCourseIDByClassID($classID);
    $dir         = "resources/admin/001/$courseID/$on/ppt/";
?>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>名称</th>
            <th>创建人</th>
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
                                                                        echo $path;?>&&pdir=<?php echo $dir;?>&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>"><img src="<?php echo IMG_URL; ?>detail.png">查看</a>
                    <a href="<?php echo "$dir".iconv("gb2312","UTF-8",$file);?>" download="<?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?>"><img src="<?php echo IMG_URL; ?>edit.png">下载</a>
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
                <td>自己</td>
                <td>
                    <a href="./index.php?r=teacher/lookPpt&&ppt=<?php  $fileName   = iconv("gb2312","UTF-8",$file);
                                                                        $len    = strlen($fileName);
                                                                        $path   = substr($fileName,0,$len-4);
                                                                        echo $path;?>&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>"><img src="<?php echo IMG_URL; ?>detail.png">查看</a>
                    <a href="<?php echo "$pdir".iconv("gb2312","UTF-8",$file);?>" download="<?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?>"><img src="<?php echo IMG_URL; ?>edit.png">下载</a>
                    <a href="./index.php?r=teacher/deletePpt&&ppt=<?php echo iconv("gb2312","UTF-8",$file);?>&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>" id="dele"><img src="<?php echo IMG_URL; ?>delete.png">删除</a>
                </td>
            </tr>
            <?php     
                        } 
                } 
                $mydir->close(); 
            ?>
        </tbody>
</table>
