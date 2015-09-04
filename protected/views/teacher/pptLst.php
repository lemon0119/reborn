<?php 
    $lessons=Lesson::model()->findall("classID='$classID'");
    foreach ($lessons as $key => $value) {
        $lessonsName[$value['number']]=$value['lessonName'];
    }
    
    $username = Yii::app()->user->name;
    $role = Yii::app()->session['role_now'];
    $userid = Yii::app()->session['userid_now'];             
    $pptFilePath =$role."/".$userid."/".$classID."/".$on."/ppt/"; 
    $pdir = "./resources/".$pptFilePath;
    
?>
<div class="span3">
    <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                <li class="nav-header">当前课程</li>
                <li id="li-<?php echo $progress;?>"><a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $progress;?>"><i class="icon-list-alt"></i> <?php echo $lessonsName[$progress];?></a></li>
                <li class="divider"></li>
                <li class="nav-header">其余课程</li>
                <?php foreach($lessonsName as $key => $value):
                    if($key!=$progress){
                    ?>
                    <li id="li-<?php echo $key;?>"><a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $key;?>"><i class="icon-list-alt"></i> <?php echo $value;?></a></li>
                    <?php
                    } 
                    endforeach;?>
                </ul>
        </div>
</div>
<div class="span9">
    <h2>PPT列表</h2>
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
                            <a href="#"><img src="<?php echo IMG_URL; ?>detail.png">查看</a>
                            <a href="<?php echo "$pdir".iconv("gb2312","UTF-8",$file);?>"><img src="<?php echo IMG_URL; ?>edit.png">下载</a>
                            <a href="./index.php?r=teacher/deletePpt&&ppt=<?php echo iconv("gb2312","UTF-8",$file);?>&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>"><img src="<?php echo IMG_URL; ?>delete.png">删除</a>
                        </td>
                    </tr>
                    <?php     
                                } 
                        } 
                        $mydir->close(); 
                    ?>
                </tbody>
    </table>
    <form class="form-horizontal" method="post" action="./index.php?r=teacher/addPpt&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>" id="myForm" enctype="multipart/form-data"> 
    <div class="control-group">
       <label class="control-label" for="input02">上传</label>
       <div class="controls">
       <input type="file" name="file" id="input02"> 
       <div id="upload" style="display:inline;" hidden="true">
       <img src="./img/default/upload-small.gif"  alt="正在努力上传。。"/>
            正在上传，请稍等...
       </div>
       <button type="submit" class="btn btn-primary">上传</button>
       </div>
    </div>
    </form>
</div>
<script>
    $(document).ready(function(){
    $("#upload").hide();
    <?php 
        if(isset($result)){
            echo '$("#upload").hide();';
            echo "alert('$result');";
        }
    ?>
});
$("#myForm").submit(function(){
    $("#upload").show();
});
    
    $(document).ready(function(){
        $("#li-<?php echo $on;?>").attr("class","active");
    });
</script>
