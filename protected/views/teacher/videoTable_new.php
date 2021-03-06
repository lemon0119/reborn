<script src="<?php echo JS_URL;?>jquery-form.js"></script>
<link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
<link href="<?php echo CSS_URL; ?>site.css" rel="stylesheet">
<script src="<?php echo JS_URL; ?>jquery.min.js" ></script>
<script src="<?php echo JS_URL; ?>bootstrap.min.js" ></script>
<script src="<?php echo JS_URL; ?>site.js" ></script>
<!--            改变alter样式-- extensions/xcConfirm 工具包下-- --> 
    <link rel="stylesheet" type="text/css" href="<?php echo XC_Confirm; ?>css/xcConfirm.css"/>
    <script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js" ></script>
    <script src="<?php echo XC_Confirm; ?>js/xcConfirm.js"></script>
<link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
<link href="<?php echo CSS_URL; ?>bootstrap-responsive.min.css" rel="stylesheet">
<?php
    $username = Yii::app()->user->name;
    $role = Yii::app()->session['role_now'];
    $userid = Yii::app()->session['userid_now'];             
    $videoFilePath =$role."/".$userid."/".$classID."/".$on."/video/"; 
    $vdir = "./resources/".$videoFilePath;
    
    $courseID    = TbClass::model()->findCourseIDByClassID($classID);
    $dir         = "resources/admin/001/$courseID/$on/video/";
    $publicdir   = "resources/public/video";
                      if (!is_dir($publicdir)) {
                            mkdir($publicdir, 0777);
                           }
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
                <a href="./index.php?r=teacher/lookVideo&&video=<?php echo iconv("gb2312","UTF-8",$file);?>&&vdir=<?php echo $dir;?>&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>&&ispublic=0&&isnew=1" title="查看"><img src="<?php echo IMG_URL; ?>detail.png"></a>
                <a href="<?php echo "$dir".iconv("gb2312","UTF-8",$file);?>" target="_blank" download="<?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?>" title="下载"><img src="<?php echo IMG_URL; ?>icon_download.png"></a>
            </td>
        </tr>
        <?php     
                    } 
            } 
            $mydir->close(); 
        ?>
        
        
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
            <td>自己</td>
            <td>
                <a href="./index.php?r=teacher/lookVideo&&video=<?php echo iconv("gb2312","UTF-8",$file);?>&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>&&ispublic=0&&isnew=1"><img src="<?php echo IMG_URL; ?>detail.png" title="查看"></a>
                <a href="<?php echo "$vdir".iconv("gb2312","UTF-8",$file);?>" target="_blank" download="<?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?>"><img src="<?php echo IMG_URL; ?>icon_download.png" title="下载"></a>
                 <a href="#" onclick="del('<?php echo iconv("gb2312","UTF-8",$file);?>','<?php echo $classID;?>','<?php echo $progress;?>','<?php echo $on;?>',0)" id="dele"><img src="<?php echo IMG_URL; ?>delete.png" title="删除"></a>
            </td>
        </tr>
        <?php     
                    } 
            } 
            $mydir->close(); 
        ?>
        
       <?php
            $mydir = dir($publicdir); 
            while($file = $mydir->read())
            { 
                    if((!is_dir("$vdir/$file")) AND ($file!=".") AND ($file!="..")) 
                    {
        ?>
        <tr>
            <td>
                <?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?>
            </td>
            <td>公共</td>
            <td>
                <a href="./index.php?r=teacher/lookVideo&&video=<?php echo iconv("gb2312","UTF-8",$file);?>&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>&&ispublic=1&&isnew=1"><img src="<?php echo IMG_URL; ?>detail.png" title="查看"></a>
                <a href="<?php echo "$vdir".iconv("gb2312","UTF-8",$file);?>" target="_blank" download="<?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?>"><img src="<?php echo IMG_URL; ?>icon_download.png" title="下载"></a>
                <a href="#" onclick="del('<?php echo iconv("gb2312","UTF-8",$file);?>','<?php echo $classID;?>','<?php echo $progress;?>','<?php echo $on;?>',1)" id="dele"><img src="<?php echo IMG_URL; ?>delete.png" title="删除"></a>
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
    function del(video2,classID2,progress2,on2,ispublic){  
        var option = {
                title: "警告",
                btn: parseInt("0011",2),
                onOk: function(){
                        window.location.href="./index.php?r=teacher/deleteVideo&&video="+video2+"&&classID="+classID2+"&&progress="+progress2+"&&on="+on2+"&&ispublic="+ispublic+"&&isnew="+'1';
                }
        }
        window.wxc.xcConfirm("是否确定删除？", "custom", option);        
    }
</script>