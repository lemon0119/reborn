
<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">       
            <li class="nav-header"><i class="icon-knowlage" style="position:relative;bottom:6px;left:"></i>操作</li>
            <li <?php if(isset($_GET['classID'])||isset($_GET['courseID'])){}else{echo "class='active'";}?>  ><a href="./index.php?r=teacher/scheduleDetil"><i class="icon-list-alt" style="position:relative;bottom:6px;left:"></i> 您的课表</a></li>
            <?php if($sqlcurrentClass=="none"){}else{ ?>
            <li class="nav-header"><i class="icon-knowlage" style="position:relative;bottom:6px;left:"></i>任课班级</li>
            <?php foreach ($array_class as $class): ?>
            <li <?php if(isset($_GET['classID'])){ if (Yii::app()->session['currentClass'] == $class['classID']&&(isset($_GET['classID']))) echo "class='active'";} ?> ><a href="./index.php?r=teacher/scheduleDetil&&classID=<?php echo $class['classID']; ?>"><i class="icon-list" style="position:relative;bottom:6px;left:"></i><?php echo $class['className']; ?></a></li>
            <?php endforeach; ?>

            <li class="divider"></li>
            <li class="nav-header"><i class="icon-knowlage" style="position:relative;bottom:6px;left:"></i>任课科目</li>

            <?php foreach ($array_class as $class): 
                $array_courseID=$class['currentCourse'];
                $array_course = Course::model()->find("courseID = '$array_courseID'");
                ?>
            <li <?php if(isset($_GET['classID'])&&isset($_GET['courseID'])){ if (Yii::app()->session['currentClass'] == $class['classID']&&(isset($_GET['classID']))&&Yii::app()->session['currentCourse'] == $array_course['courseID']) echo "class='active'";$classID=$_GET['classID'];} ?> ><a href="./index.php?r=teacher/scheduleDetil&&classID=<?php echo $class['classID']?>&&courseID=<?php echo $array_course['courseID']; ?>"><i class="icon-list" style="position:relative;bottom:6px;left:"></i><?php echo $array_course['courseName']; ?></a></li>
            <?php endforeach; ?>
            
            <?php// foreach ($array_course as $course): ?>
<!--            <li <?php// if(isset($_GET['courseID'])){if(Yii::app()->session['currentCourse'] == $course['courseID']) echo "class='active'"; }?>  ><a href="./index.php?r=teacher/scheduleDetil&&courseID=<?php// echo $course['courseID']; ?>"><i class="icon-list" style="position:relative;bottom:5px;left:"></i><?php// echo $course['courseName']; ?></a></li>-->
            <?php// endforeach; ?>   
            <?php } ?>
            
        </ul>
    </div>

</div>
<div class="span9" style="height: 100%">
    <!-- 显示课程列表   -->
   
    <?php if(isset($_GET['courseID'])){ ?>
    <!-- 课程列表-->
     <?php
$dir = "resources/admin/001/$courseID/";
?>
    <h3><?php 
echo $courseName; ?></h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>课号</th>
                <th>课名</th>
                <th>创建人</th>
                <th>创建时间</th>
<!--                <th>ppt</th>
                <th>视频</th>-->
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
                    <td style="width: 50px"><?php echo $model['number']; ?></td>
                    <td  title="<?php echo $model['lessonName'];?>" style="width: 200px" class="table_schedule cursor_pointer" onclick="changeCourseName('<?php echo $model['lessonName']; ?>',<?php echo $model['number']; ?>,<?php echo $courseID; ?>,<?php echo $classID;?>)"><?php if(Tool::clength($model['lessonName'], 'utf-8')>12){echo Tool::csubstr($model['lessonName'], 0, 11, 'UTF-8') . "..."; }else{ echo $model['lessonName'];} ?></td>
                    <td><?php if($createPerson=="0")
                                    echo "管理员";
                         ?></td>
                    <td><?php echo $model['createTime']; ?></td>
<!--                    <td><a href="./index.php?r=teacher/pptLst&&classID=<?php //echo Yii::app()->session['currentClass']; ?>&&progress=<?php echo Yii::app()->session['progress']; ?>&&on=<?php echo Yii::app()->session['on']; ?>&&url=schedule"><img src="<?php echo IMG_URL; ?>ppt.png"><?php
                            //$num = 0;
                           // $mydir = dir($pdir);
                           // while ($file = $mydir->read()) {
                           //     if ((!is_dir("$pdir/$file")) AND ( $file != ".") AND ( $file != "..")) {
                           //         $num = $num + 1;
                           //     }
                           // }
                           // $mydir->close();
                           // echo $num;
                            ?></a></td>
                    <td><a href="./index.php?r=teacher/videoLst&&classID=<?php //echo Yii::app()->session['currentClass']; ?>&&progress=<?php echo Yii::app()->session['progress']; ?>&&on=<?php echo Yii::app()->session['on']; ?>&&url=schedule"><img src="<?php echo IMG_URL; ?>video.png"><?php
                       // $num = sizeof(scandir($vdir));
                       // $num = ($num > 2) ? ($num - 2) : 0;
                       // echo $num;
                            ?></a></td>-->
                </tr>   
            <?php endforeach; ?> 
        </tbody>
    </table>
     <div id="p_tag" style="float: right;">
     <p style="color: gray" >（课名可点击修改）</p>
     </div>
    <!-- 课程列表结束 -->
    <!-- 显示翻页标签 -->
    <div align=center>
        <?php
        $this->widget('CLinkPager', array('pages' => $pages));
        ?>
    </div>
    <!-- 翻页标签结束 -->
    
    
    
    <?php }else{ ?>
        <!-- 控制在 isset($_GET['courseID'])之外 显示任课班级课程表和个人课程表   -->
        <?php if (isset($_GET['classID'])) { ?>
        <h3><font style="color: #f46500"><?php echo $sqlcurrentClass['className']; ?></font>&nbsp;&nbsp;的课程安排</h3>
            <?php } else { ?>
            <h3><font style="color: #f46500">您</font>的课程安排</h3>
<?php } ?>
        <br/>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
<!--                        <th colspan="2" style="width: 40px"></td >-->
                        <th style="width: 40px">时间</th>
                        <td style="width: 100px" ><span style="font-weight: bolder">星期一</span></td >
                        <td style="width: 100px"><span style="font-weight: bolder">星期二</span></td >
                        <td  style="width: 100px"><span style="font-weight: bolder">星期三</span></td >
                        <td  style="width: 100px"><span style="font-weight: bolder">星期四</span></td >
                        <td  style="width: 100px"><span style="font-weight: bolder">星期五</span></td >
                        <td  style="width: 100px"><span style="font-weight: bolder">星期六</span></td >
                        <td  style="width: 100px" ><span style="font-weight: bolder">星期日</span></td >
                    </tr>
                </thead>
                <tbody>
                        <?php for ($s = 1; $s < 11; $s++) { ?>
                        <tr >
                            <?php
//                            switch ($s) {
//                                case 1: echo '<td  rowspan="4">';
//                                    break;
//                                case 5:echo '<td rowspan="4">';
//                                    break;
//                                case 9:echo '<td rowspan="4">';
//                                    break;
//                            }
                            ?><?php
                            $flag = 1;
                                foreach ($result as $v) {
                                        if (($v['sequence'] == $s) && ($v['day'] == 0)) {
//                            switch ($s) {
//                                case 1: echo '<span style="font-weight: bolder">上午</span>';
//                                    break;
//                                case 5:echo '<span style="font-weight: bolder">下午</span>';
//                                    break;
//                                case 9:echo '<span style="font-weight: bolder">晚上</span>';
//                                    break;
//                            }
                            ?>
                            <td style="height: 62px" class="table_schedule" title="<?php
                                        $array_v = explode("&&", $v['courseInfo']);
                                        foreach ($array_v as $value) {
                                            echo $value . "&nbsp;&nbsp;&nbsp;&nbsp;";
                                        }
                                        ?>" >
                                       <span   ><?php
                                        $array_v1 = explode("&&", $v['courseInfo']);
                                        foreach ($array_v1 as $value) {
                                            if (Tool::clength($value, 'utf-8')>5) {
                                                echo Tool::csubstr($value, 0, 4, 'UTF-8') . "...<br/>";
                                            } else {
                                                echo $value . "<br/>";
                                            }
                                        }
                                        ?></span></td>
                            <?php  $flag = 0;
                                   } 
                                   }
                                   if ($flag == 1) {
            ?><td style="height: 62px" title="-" class="table_schedule" >
                                       <?php
                                switch ($s) {
                                    case 1: echo '<span >一</span>';
                                        break;
                                    case 2: echo '<span >二</span>';
                                        break;
                                    case 3: echo '<span >三</span>';
                                        break;
                                    case 4: echo '<span >四</span>';
                                        break;
                                    case 5: echo '<span >五</span>';
                                        break;
                                    case 6: echo '<span>六</span>';
                                        break;
                                    case 7: echo '<span >七</span>';
                                        break;
                                    case 8: echo '<span >八</span>';
                                        break;
                                    case 9: echo '<span>九</span>';
                                        break;
                                    case 10: echo '<span>十</span>';
                                        break;
                                }
                                ?></td><?php }?>
                                <?php
                                for ($d = 1; $d < 8; $d++) {
                                    $flag = 1;
                                    foreach ($result as $v) {
                                        if (($v['sequence'] == $s) && ($v['day'] == $d)) {
                                            ?>
                                        <td style="cursor:pointer"  title="<?php
                                        $array_v = explode("&&", $v['courseInfo']);
                                        foreach ($array_v as $value) {
                                            echo $value . "&nbsp;&nbsp;&nbsp;&nbsp;";
                                        }
                                        ?>" class="table_schedule" <?php if (isset($_GET['classID'])) {
                                       echo 'onclick="changeClass('.$s.','.$d.');"';
                                   }else{
                                       echo 'onclick="change('.$s .','.$d.');"';
                                   }
                                            ?> >
                                            <span style="cursor:pointer"   ><?php
                                        $array_v1 = explode("&&", $v['courseInfo']);
                                        foreach ($array_v1 as $value) {
                                            if (Tool::clength($value, 'utf-8')>5) {
                                                echo Tool::csubstr($value, 0, 4, 'UTF-8') . "...<br/>";
                                            } else {
                                                echo $value . "<br/>";
                                            }
                                        }
                                        ?></span></td>
                <?php
                $flag = 0;
            }
        }
        if ($flag == 1) {
            ?>
                                    <td style="cursor:pointer" class="table_schedule" <?php if (isset($_GET['classID'])) {
                                       echo 'onclick="changeClass('.$s.','.$d.');"';
                                   }else{
                                       echo 'onclick="change('.$s .','.$d.');"';
                                   }
                                            ?>>
                                        <span   style="color: #aaa9a9;cursor:pointer" title="-"  >-</span></td>
        <?php
        }
    }
    ?>
                        </tr>
<?php } ?>
                </tbody>
            </table>
   <?php } ?>
    
</div>
<script>
    function change(s, d) {
        var obj = new Object();
        obj.name="new1";
        window.showModalDialog("./index.php?r=teacher/editSchedule&&sequence=" + s + "&day=" + d + "&teacherID=<?php echo Yii::app()->session['userid_now']; ?>", obj, 'dialogHeight =400px;dialogWidth = 600px;dialogLeft=500px;dialogTop =200px;');
    }
    function changeClass(s, d) {
        var obj = new Object();
        obj.name="new1";
        window.showModalDialog("./index.php?r=teacher/editSchedule&&sequence=" + s + "&day=" + d + "&classID=<?php echo Yii::app()->session['currentClass']; ?>", obj, 'dialogHeight =400px;dialogWidth = 600px;dialogLeft=500px;dialogTop =200px;');
    }    
    function changeCourseName(courseName,number,courseID,classID){
        var txt=  "原课名:"+courseName;
					window.wxc.xcConfirm(txt, window.wxc.xcConfirm.typeEnum.input,{
						onOk:function(v){
                                                    if(v==""){
                                                        window.wxc.xcConfirm('请填入新课名！', window.wxc.xcConfirm.typeEnum.warning);
                                                    }else{
                                                    window.location.href="./index.php?r=teacher/scheduleDetil&&classID="+classID+"&&courseID="+courseID+"&&number="+number+"&&newName="+v;
						}
                                            }
					});
    }
</script>