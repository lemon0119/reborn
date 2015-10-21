
<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">       
            <li class="nav-header"><i class="icon-knowlage"></i>操作</li>
            <li <?php if(isset($_GET['classID'])){}else{echo "class='active'";}?>  ><a href="./index.php?r=teacher/scheduleDetil"><i class="icon-list-alt"></i> 您的课表</a></li>
            <li class="nav-header">任课班级</li>
            <?php foreach ($array_class as $class): ?>
            <li <?php if (Yii::app()->session['currentClass'] == $class['classID']&&(isset($_GET['classID']))) echo "class='active'"; ?> ><a href="./index.php?r=teacher/scheduleDetil&&classID=<?php echo $class['classID']; ?>"><i class="icon-list"></i><?php echo $class['className']; ?></a></li>
            <?php endforeach; ?>

            <li class="divider"></li>
            <li class="nav-header">课程列表</li>

            <?php foreach ($array_lesson as $lesson): ?>
                <li <?php if (Yii::app()->session['currentLesson'] == $lesson['lessonID']) echo "class='active'"; ?> ><a href="./index.php?r=teacher/scheduleDetil&&classID=<?php echo Yii::app()->session['currentClass']; ?>&&lessonID=<?php echo $lesson['lessonID']; ?>"><i class="icon-list"></i><?php echo $lesson['lessonName']; ?></a></li>
            <?php endforeach; ?>   
            
            
        </ul>
    </div>

</div>
<div class="span9">
        <?php if (isset($_GET['classID'])) { ?>
        <h3><font style="color: #f46500"><?php echo $sqlcurrentClass['className']; ?></font>&nbsp;&nbsp;的课程安排</h3>
            <?php } else { ?>
            <h3><font style="color: #f46500">您</font>的课程安排</h3>
<?php } ?>
        <p style="color: gray">&nbsp;&nbsp;&nbsp;&nbsp;（鼠标悬浮显示详细信息）</p>
        <br/>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th colspan="2" style="width: 40px"></td >
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
                            switch ($s) {
                                case 1: echo '<td  rowspan="4">';
                                    break;
                                case 5:echo '<td rowspan="4">';
                                    break;
                                case 9:echo '<td rowspan="4">';
                                    break;
                            }
                            ?><?php
                            switch ($s) {
                                case 1: echo '<span style="font-weight: bolder">上午</span>';
                                    break;
                                case 5:echo '<span style="font-weight: bolder">下午</span>';
                                    break;
                                case 9:echo '<span style="font-weight: bolder">晚上</span>';
                                    break;
                            }
                            ?></td>
                            <td style="height: 62px" ><?php
                                switch ($s) {
                                    case 1: echo '<span style="font-weight: bolder">一</span>';
                                        break;
                                    case 2: echo '<span style="font-weight: bolder">二</span>';
                                        break;
                                    case 3: echo '<span style="font-weight: bolder">三</span>';
                                        break;
                                    case 4: echo '<span style="font-weight: bolder">四</span>';
                                        break;
                                    case 5: echo '<span style="font-weight: bolder">五</span>';
                                        break;
                                    case 6: echo '<span style="font-weight: bolder">六</span>';
                                        break;
                                    case 7: echo '<span style="font-weight: bolder">七</span>';
                                        break;
                                    case 8: echo '<span style="font-weight: bolder">八</span>';
                                        break;
                                    case 9: echo '<span style="font-weight: bolder">九</span>';
                                        break;
                                    case 10: echo '<span style="font-weight: bolder">十</span>';
                                        break;
                                }
                                ?></td>
                                <?php
                                for ($d = 1; $d < 8; $d++) {
                                    $flag = 1;
                                    foreach ($result as $v) {
                                        if (($v['sequence'] == $s) && ($v['day'] == $d)) {
                                            ?>
                                        <td class="table_schedule" <?php if (isset($_GET['classID'])) {
                                       echo 'onclick="changeClass('.$s.','.$d.');"';
                                   }else{
                                       echo 'onclick="change('.$s .','.$d.');"';
                                   }
                                            ?> >
                                            <span  title="<?php
                                        $array_v = explode("&&", $v['courseInfo']);
                                        foreach ($array_v as $value) {
                                            echo $value . "&nbsp;&nbsp;&nbsp;&nbsp;";
                                        }
                                        ?>"  ><?php
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
                                    <td class="table_schedule" <?php if (isset($_GET['classID'])) {
                                       echo 'onclick="changeClass('.$s.','.$d.');"';
                                   }else{
                                       echo 'onclick="change('.$s .','.$d.');"';
                                   }
                                            ?>>
                                        <span  style="color: #aaa9a9" title="-"  >-</span></td>
        <?php
        }
    }
    ?>
                        </tr>
<?php } ?>
                </tbody>
            </table>
</div>
<script>
    function change(s, d) {
        window.open("./index.php?r=teacher/editSchedule&&sequence=" + s + "&day=" + d + "&teacherID=<?php echo Yii::app()->session['userid_now']; ?>", 'newwindow', 'height=400,width=600,top=0,left=0,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no,left=500,top=200,');
    }
    function changeClass(s, d) {
        window.open("./index.php?r=teacher/editSchedule&&sequence=" + s + "&day=" + d + "&classID=<?php echo Yii::app()->session['currentClass']; ?>", 'newwindow', 'height=400,width=600,top=0,left=0,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no,left=500,top=200,');
    }
</script>