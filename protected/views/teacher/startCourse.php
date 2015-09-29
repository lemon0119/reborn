<?php
$lessons = Lesson::model()->findall("classID='$classID'");
foreach ($lessons as $key => $value) {
    $lessonsName[$value['number']] = $value['lessonName'];
}
?>
<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li class="nav-header">当前课程</li>
            <li id="li-<?php echo $progress; ?>"><a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress; ?>&&on=<?php echo $progress; ?>"><i class="icon-list-alt"></i> <?php echo $lessonsName[$progress]; ?></a></li>
            <li class="divider"></li>
            <li class="nav-header">其余课程</li>
            <?php
            foreach ($lessonsName as $key => $value):
                if ($key != $progress) {
                    ?>
                    <li id="li-<?php echo $key; ?>"><a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress; ?>&&on=<?php echo $key; ?>"><i class="icon-list-alt"></i> <?php echo $value; ?></a></li>
                    <?php
                }
            endforeach;
            ?>
        </ul>
    </div>
</div>
<div class="span9">
    <h1>
<?php echo $lessonsName[$progress]; ?>
    </h1>
    <div class="hero-unit table-bordered" style="height:200px; width: 770px">
        <p class="font-startcourse">
            点击虚拟课堂开始本课.
        </p>
        <div style="height:200px; width: 780px">
            <?php if ($on == $progress) { ?>
                <a href="./index.php?r=teacher/virtualClass&&classID=<?php echo $classID; ?>&&on=<?php echo $on; ?>" class="startcourse-virtualclass">虚拟课堂</a> 
            <?php } else { ?>
                <a href="./index.php?r=teacher/changeProgress&&classID=<?php echo $classID; ?>&&on=<?php echo $on; ?>" class="startcourse-virtualclass">开始本课</a> 
               <?php } ?>
            <a href="./index.php?r=teacher/assignWork&&classID=<?php echo $classID; ?>&&lessonID=<?php $less = Lesson::model()->find('classID=? and number=?', array($classID, $on));
               echo $less['lessonID'];
               ?>" class="startcourse-startclass">课堂作业</a>
        </div>
        </div>
        <h1>
            本课资源
        </h1>
        <?php
        $typename = Yii::app()->session['role_now'];
        $userid = Yii::app()->session['userid_now'];
        $videoFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/video/";
        $vdir = "resources/" . $videoFilePath;
        if (!is_dir($vdir)) {//true表示可以创建多级目录
            mkdir($vdir, 0777, true);
        }
        $pptFilePath = $typename . "/" . $userid . "/" . $classID . "/" . $on . "/ppt/";
        $pdir = "resources/" . $pptFilePath;
        if (!is_dir($pdir)) {//true表示可以创建多级目录
            mkdir($pdir, 0777, true);
        }

        $courseID = TbClass::model()->findCourseIDByClassID($classID);
        $adminPdir = "resources/admin/001/$courseID/$on/ppt/";
        $adminVdir = "resources/admin/001/$courseID/$on/video/";
        if (!is_dir($adminPdir)) {//true表示可以创建多级目录
            mkdir($adminPdir, 0777, true);
        }
        if (!is_dir($adminVdir)) {//true表示可以创建多级目录
            mkdir($adminVdir, 0777, true);
        }
        ?>
        <div class="table-bordered summary">
            <ul>
                <li>
                    <a  id="ppt" href="./index.php?r=teacher/videoLst&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress; ?>&&on=<?php echo $on; ?>"><span class="count"><?php
        $num1 = sizeof(scandir($vdir));
        $num1 = ($num1 > 2) ? ($num1 - 2) : 0;
        $num2 = sizeof(scandir($adminVdir));
        $num2 = ($num2 > 2) ? ($num2 - 2) : 0;
        echo $num1 + $num2;
        ?></span > <font style="color:#000">视频</font></a>
                </li>
                <li class="last">
                    <a href="./index.php?r=teacher/pptLst&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress; ?>&&on=<?php echo $on; ?>" id="ppt">
                        <span class="count"><?php
                            $num1 = sizeof(scandir($pdir));
                            $num1 = ($num1 > 2) ? ($num1 - 2) / 2 : 0;
                            $num2 = sizeof(scandir($adminPdir));
                            $num2 = ($num2 > 2) ? ($num2 - 2) / 2 : 0;
                            echo $num1 + $num2;
                            ?></span> <font style="color:#000">PPT</font></a>
            </ul>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $("#li-<?php echo $on; ?>").attr("class", "active");
        });
    </script>

