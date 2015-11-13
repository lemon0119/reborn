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
                <a href="./index.php?r=teacher/lookVideo&&video=<?php echo iconv("gb2312","UTF-8",$file);?>&&vdir=<?php echo $dir;?>&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>" title="查看"><img src="<?php echo IMG_URL; ?>detail.png"></a>
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
                <a href="./index.php?r=teacher/lookVideo&&video=<?php echo iconv("gb2312","UTF-8",$file);?>&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>"><img src="<?php echo IMG_URL; ?>detail.png" title="查看"></a>
                <a href="<?php echo "$vdir".iconv("gb2312","UTF-8",$file);?>" target="_blank" download="<?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?>"><img src="<?php echo IMG_URL; ?>edit.png" title="下载"></a>
                <a href="#" onclick="del('<?php echo iconv("gb2312","UTF-8",$file);?>')" id="dele"><img src="<?php echo IMG_URL; ?>delete.png" title="删除"></a>
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
//    $(document).ready(function(){
//        $('#dele').bind('click', function(e){
//            e.preventDefault();
//        });
//        $("#dele").click(function(){
//            $.get($(this).attr("href"),function(data,status){
//                $("#video-table").load("./index.php?r=teacher/videoTable&&classID=<?php //echo $classID;?>&&progress=<?php //echo $progress;?>&&on=<?php //echo $on;?>");
//                window.wxc.xcConfirm(data, window.wxc.xcConfirm.typeEnum.info);
//            });  
//            return false;     
//        });
//    });
    function del(video){
        var option = {
                title: "警告",
                btn: parseInt("0011",2),
                onOk: function(){
                        window.location.href="./index.php?r=teacher/deleteVideo&&video="+video+"&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>";
                }
        }
        window.wxc.xcConfirm("是否确定删除？", "custom", option);
        
    }
</script>