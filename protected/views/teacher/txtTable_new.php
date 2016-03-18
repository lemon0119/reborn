<link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
<link href="<?php echo CSS_URL; ?>bootstrap-responsive.min.css" rel="stylesheet">
<?php
    $username = Yii::app()->user->name;
    $role = Yii::app()->session['role_now'];
    $userid = Yii::app()->session['userid_now'];             
    $txtFilePath =$role."/".$userid."/".$classID."/".$on."/txt/"; 
    $tdir = "./resources/".$txtFilePath;
    
    $courseID    = TbClass::model()->findCourseIDByClassID($classID);
    $dir         = "resources/admin/001/$courseID/$on/txt/";
    $publicdir   = "resources/public/txt";
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
                <a href="./index.php?r=teacher/lookTxt&&txt=<?php echo iconv("gb2312","UTF-8",$file);?>&&vdir=<?php echo $dir;?>&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>&&ispublic=0" title="查看"><img src="<?php echo IMG_URL; ?>detail.png"></a>
                <a href="<?php echo "$dir".iconv("gb2312","UTF-8",$file);?>" target="_blank" download="<?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?>" title="下载"><img src="<?php echo IMG_URL; ?>icon_download.png"></a>
            </td>
        </tr>
        <?php     
                    } 
            } 
            $mydir->close(); 
        ?>
        <?php
            $mydir = dir($tdir); 
            while($file = $mydir->read())
            { 
                    if((!is_dir("$tdir/$file")) AND ($file!=".") AND ($file!="..")) 
                    {
        ?>
        <tr>
            <td>
                <?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?>
            </td>
            <td>自己</td>
            <td>
                <a href="./index.php?r=teacher/looktxt&&txt=<?php echo iconv("gb2312","UTF-8",$file);?>&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>&&ispublic=0&&isnew=1"><img src="<?php echo IMG_URL; ?>detail.png" title="查看"></a>
                <a href="<?php echo "$tdir".iconv("gb2312","UTF-8",$file);?>" target="_blank" download="<?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?>"><img src="<?php echo IMG_URL; ?>icon_download.png" title="下载"></a>
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
                    if((!is_dir("$publicdir/$file")) AND ($file!=".") AND ($file!="..")) 
                    {
        ?>
        <tr>
            <td>
                <?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?>
            </td>
            <td>公共</td>
            <td>
                <a href="./index.php?r=teacher/looktxt&&txt=<?php echo iconv("gb2312","UTF-8",$file);?>&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>&&ispublic=1"><img src="<?php echo IMG_URL; ?>detail.png" title="查看"></a>
                <a href="<?php echo "$publicdir".iconv("gb2312","UTF-8",$file);?>" target="_blank" download="<?php echo Resourse::model()->getOriName(iconv("gb2312","UTF-8",$file));?>"><img src="<?php echo IMG_URL; ?>icon_download.png" title="下载"></a>
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
    function del(txt2,classID2,progress2,on2,ispublic){
        var option = {
                title: "警告",
                btn: parseInt("0011",2),
                onOk: function(){
                        window.location.href="./index.php?r=teacher/deleteTxt&&txt="+txt2+"&&classID="+classID2+"&&progress="+progress2+"&&on="+on2+"&&ispublic="+ispublic+"&&isnew="+'1';
                }
        }
        window.wxc.xcConfirm("是否确定删除？", "custom", option);
        
    }
</script>