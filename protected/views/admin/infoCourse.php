<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li ><a href="./index.php?r=admin/infoCourse&&courseID=<?php echo $courseID; ?>&&courseName=<?php echo $courseName; ?>&&createPerson=<?php echo $createPerson; ?>"><i class="icon-align-left"></i> 课程列表</a></li>
        </ul>
    </div>
    <a href="./index.php?r=admin/addLesson&&courseID=<?php echo $courseID; ?>&&courseName=<?php echo $courseName; ?>&&createPerson=<?php echo $createPerson; ?>" class="btn btn-primary">添加课程</a>
    <a href="./index.php?r=admin/<?php echo Yii::app()->session['lastUrl']; ?>&&page=<?php echo Yii::app()->session['lastPage']; ?>" class="btn">返回</a>
</div>
<?php
$dir = "resources/admin/001/$courseID/";
?>
<div class="span9">
    <!-- 课程列表-->
    <h3><?php echo $courseID;
echo '&nbsp; &nbsp;';
echo $courseName; ?></h3>
    <p>创建人：<?php echo $createPerson; ?></p>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>课号</th>
                <th>课名</th>
                <th>创建人</th>
                <th>创建时间</th>
                <th>ppt</th>
                <th>视频</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>        
                <?php foreach ($posts as $model): ?>
                <tr>
                    <?php
                    $pdir = $dir . $model['number'] . "/ppt/";
                    if (!is_dir($pdir)) {//true表示可以创建多级目录
                        mkdir($pdir, 0777, true);
                    }
                    $vdir = $dir . $model['number'] . "/video/";
                    if (!is_dir($vdir)) {//true表示可以创建多级目录
                        mkdir($vdir, 0777, true);
                    }
                    ?>
                    <td style="width: 75px"><?php echo $model['number']; ?></td>
                    <td><?php echo $model['lessonName']; ?></td>
                    <td><?php echo $createPerson; ?></td>
                    <td><?php echo $model['createTime']; ?></td>
                    <td><a href="./index.php?r=admin/pptLst&&pdir=<?php echo $pdir; ?>&&courseID=<?php echo $courseID; ?>&&courseName=<?php echo $courseName; ?>&&createPerson=<?php echo $createPerson; ?>"><img src="<?php echo IMG_URL; ?>ppt.png"><?php
                            $num = 0;
                            $mydir = dir($pdir);
                            while ($file = $mydir->read()) {
                                if ((!is_dir("$pdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                                    $num = $num + 1;
                                }
                            }
                            $mydir->close();
                            echo $num;
                            ?></a></td>
                    <td><a href="./index.php?r=admin/videoLst&&vdir=<?php echo $vdir; ?>&&courseID=<?php echo $courseID; ?>&&courseName=<?php echo $courseName; ?>&&createPerson=<?php echo $createPerson; ?>"><img src="<?php echo IMG_URL; ?>video.png"><?php
                        $num = sizeof(scandir($vdir));
                        $num = ($num > 2) ? ($num - 2) : 0;
                        echo $num;
                            ?></a></td>
                    <td style="width: 35px"> <a onclick="deleteLesson( '<?php echo $model['lessonName']; ?>');"  href="#" ><img title="删除" src="<?php echo IMG_URL; ?>delete.png"></a></td>
                </tr>   
            <?php endforeach; ?> 
        </tbody>
    </table>
    <!-- 课程列表结束 -->
    <!-- 显示翻页标签 -->
    <div align=center>
        <?php
        $this->widget('CLinkPager', array('pages' => $pages));
        ?>
    </div>
    <!-- 翻页标签结束 -->
    <!-- 右侧内容展示结束-->
</div>
<script>
    $(document).ready(function () {
        var deleteReslut = <?php echo "'$deleteResult'";?>;
        if(deleteReslut==='1'){
            window.wxc.xcConfirm("删除成功！", window.wxc.xcConfirm.typeEnum.success,{
                onOk:function(){
		 window.location.href = "./index.php?r=admin/infoCourse&&courseID=<?php echo Yii::app()->session['courseID']; ?>&&courseName=<?php echo Yii::app()->session['courseName']; ?>&&createPerson=<?php echo Yii::app()->session['createPerson']; ?>";
						}
            });
        }else if(deleteReslut ==='0'){
            window.wxc.xcConfirm("删除失败！", window.wxc.xcConfirm.typeEnum.error);
        }
    });

    function deleteLesson(name) {
        var option = {
            title: "警告",
            btn: parseInt("0011", 2),
            onOk: function () {
                window.location.href = "./index.php?r=admin/infoCourse&&courseID=<?php echo Yii::app()->session['courseID']; ?>&&courseName=<?php echo Yii::app()->session['courseName']; ?>&&createPerson=<?php echo Yii::app()->session['createPerson']; ?>&&lessonName="+name;
            }
        };
        window.wxc.xcConfirm("您将删除课程:" + name + "，这样做将无法恢复！", "custom", option);
    }
    ;


</script>
