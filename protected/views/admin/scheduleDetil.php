<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li class="nav-header"><i class="icon-navsearch" style="position:relative;bottom:7px;left:"></i>搜索</li>
            <form action="./index.php?r=admin/schedule" method="post">
                <li>
                    <select name="which" >
                        <option value="className" selected="selected" >班级名</option>
                        <option value="teaName" >老师</option>
                    </select>
                </li>
                <li>
                    <input name="value" type="text" class="search-query span2" placeholder="Search" />
                </li>
                <li style="margin-top:10px">
                    <button type="submit"class="btn_4superbig">搜&nbsp;&nbsp;&nbsp;索</button>
                </li>
            </form>
            <li class="divider"></li>
            <li>&nbsp;</li>
            <a <?php
                if (isset($_GET['teacherId'])) {
                    echo 'href="./index.php?r=admin/schedule"';
                } else {
                    echo 'href="./index.php?r=admin/schedule&&type=class"';
                }
                ?> ><button  class="btn_4superbig">返&nbsp;&nbsp;&nbsp;回</button></a>
            <li>&nbsp;</li>
        </ul>
    </div>
</div>
<div class="span9">
        <?php if (isset($_GET['teacherId'])) { ?>
        <h3><font style="color: #f46500"><?php echo $teacher['userName']; ?></font>&nbsp;&nbsp;老师的科目安排</h3>
        <br/>
            <?php } else { ?>
            <h3><font style="color: #f46500"><?php echo $class['className']; ?></font>&nbsp;&nbsp;的科目安排</h3>
            <br/>
<?php } ?>
            <p style="color: gray">&nbsp;&nbsp;&nbsp;&nbsp;（鼠标悬浮显示详细信息）</p>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
<!--                        <th colspan="2" style="width: 40px"></td >-->
                        <th style="width: 40px">时间</td >
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
//                                case 1: echo '<td colspan="2" rowspan="4">';
//                                    break;
//                                case 5:echo '<td rowspan="4">';
//                                    break;
//                                case 9:echo '<td rowspan="4">';
//                                    break;
//                            }
//                            ?><?php
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
//                            ?>
                            <td style="height: 62px;cursor:pointer" class="table_schedule" title="<?php
                                        $array_v = explode("&&", $v['courseInfo']);
                                        foreach ($array_v as $value) {
                                            echo $value . "&nbsp;&nbsp;&nbsp;&nbsp;";
                                        }
                                        ?>" <?php if (isset($_GET['teacherId'])) {
                                       echo 'onclick="changeOne('.$s .','."0".');"';
                                   } else {
                                       echo 'onclick="changeClassOne('.$s.','."0".');"';
                                   }?>>
                                        
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
            ?> <td style="height: 62px;cursor:pointer" title="-" class="table_schedule" <?php if (isset($_GET['teacherId'])) {
                                       echo 'onclick="changeOne('.$s .','."0".');"';
                                   } else {
                                       echo 'onclick="changeClassOne('.$s.','."0".');"';
                                   }
                                            ?> >
                              <?php  switch ($s) {
                                    case 1: echo '<span>一</span>';
                                        break;
                                    case 2: echo '<span>二</span>';
                                        break;
                                    case 3: echo '<span >三</span>';
                                        break;
                                    case 4: echo '<span>四</span>';
                                        break;
                                    case 5: echo '<span>五</span>';
                                        break;
                                    case 6: echo '<span>六</span>';
                                        break;
                                    case 7: echo '<span>七</span>';
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
                            <td style="height: 62px;cursor:pointer" title="<?php
                                        $array_v = explode("&&", $v['courseInfo']);
                                        foreach ($array_v as $value) {
                                            echo $value . "&nbsp;&nbsp;&nbsp;&nbsp;";
                                        }
                                        ?>" class="table_schedule" <?php if (isset($_GET['teacherId'])) {
                                       echo 'onclick="change('.$s .','.$d.');"';
                                   }else{
                                       echo 'onclick="changeClass('.$s.','.$d.');"';
                                   }
                                            ?> >
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
                <?php
                $flag = 0;
            }
        }
        if ($flag == 1) {
            ?>
                                    <td style="height: 62px;cursor:pointer" title="-" class="table_schedule" <?php if (isset($_GET['teacherId'])) {
                                       echo 'onclick="change('.$s .','.$d.');"';
                                   } else {
                                       echo 'onclick="changeClass('.$s.','.$d.');"';
                                   }
                                            ?> >
                                        <span style="color: #aaa9a9"   >-</span></td>
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
        var obj = new Object();
        obj.name="new2"; 
        window.showModalDialog("./index.php?r=admin/editSchedule&&sequence=" + s + "&day=" + d + "&teacherID=<?php echo Yii::app()->session['teacherId']; ?>", obj, 'dialogHeight =400px;dialogWidth = 600px;dialogLeft=500px;dialogTop =200px;');
    }
    function changeClass(s, d) {
        var obj = new Object();
        obj.name="new3";
        window.showModalDialog("./index.php?r=admin/editSchedule&&sequence=" + s + "&day=" + d + "&classID=<?php echo Yii::app()->session['classId']; ?>", obj, 'dialogHeight =400px;dialogWidth = 600px;dialogLeft=500px;dialogTop =200px;');
    }
    function changeOne(s,d){
        var obj = new Object();
        obj.name="new4";
        window.showModalDialog("./index.php?r=admin/editScheduleOne&&sequence=" + s + "&day=" + d + "&teacherID=<?php echo Yii::app()->session['teacherId']; ?>", obj, 'dialogHeight =400px;dialogWidth = 600px;dialogLeft=500px;dialogTop =200px;');
    }
    function changeClassOne(s,d){
        var obj = new Object();
        obj.name="new5";
        window.showModalDialog("./index.php?r=admin/editScheduleOne&&sequence=" + s + "&day=" + d + "&classID=<?php echo Yii::app()->session['classId']; ?>", obj, 'dialogHeight =400px;dialogWidth = 600px;dialogLeft=500px;dialogTop =200px;');        
    }
</script>