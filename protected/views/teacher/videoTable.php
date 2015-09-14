<link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
<link href="<?php echo CSS_URL; ?>bootstrap-responsive.min.css" rel="stylesheet">
<?php
    $username = Yii::app()->user->name;
    $role = Yii::app()->session['role_now'];
    $userid = Yii::app()->session['userid_now'];             
    $pptFilePath =$role."/".$userid."/".$classID."/".$on."/video/"; 
    $pdir = "./resources/".$pptFilePath;
?>
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
                            if((!is_dir("$pdir/$file")) AND ($file!=".") AND ($file!="..")) 
                            {
                ?>
                <tr>
                    <td>
                        <?php echo iconv("gb2312","UTF-8",$file);?>
                    </td>
                    <td>
                        <a href="./index.php?r=teacher/lookVideo&&video=<?php echo iconv("gb2312","UTF-8",$file);?>&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>"><img src="<?php echo IMG_URL; ?>detail.png">查看</a>
                        <a href="<?php echo "$pdir".iconv("gb2312","UTF-8",$file);?>" target="_blank"><img src="<?php echo IMG_URL; ?>edit.png">右键另存为</a>
                        <a href="./index.php?r=teacher/deleteVideo&&video=<?php echo iconv("gb2312","UTF-8",$file);?>&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>" id="dele"><img src="<?php echo IMG_URL; ?>delete.png">删除</a>
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
                $("#video-table").load("./index.php?r=teacher/videoTable&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>");
                alert(data);
            });  
            return false;     
        });
    });
</script>