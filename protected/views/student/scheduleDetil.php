
</div>
<div class="span9" style="width: 1088px">
            <h3>课程表</h3>
        <p style="color: gray">&nbsp;&nbsp;&nbsp;&nbsp;（鼠标悬浮显示详细信息）</p>
        <br/>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th colspan="2" style="width: 40px"></td >
                        <td style="width: 150px" ><span style="font-weight: bolder">星期一</span></td >
                        <td style="width: 150px"><span style="font-weight: bolder">星期二</span></td >
                        <td  style="width: 150px"><span style="font-weight: bolder">星期三</span></td >
                        <td  style="width: 150px"><span style="font-weight: bolder">星期四</span></td >
                        <td  style="width: 150px"><span style="font-weight: bolder">星期五</span></td >
                        <td  style="width: 150px"><span style="font-weight: bolder">星期六</span></td >
                        <td  style="width: 150px" ><span style="font-weight: bolder">星期日</span></td >
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
                                        <td class="table_schedule" <?php 
                                       echo 'onclick="changeClass('.$s .','.$d.');"';
                                            ?> >
                                            <span  title="<?php
                                        $array_v = explode("&&", $v['courseInfo']);
                                        foreach ($array_v as $value) {
                                            echo $value . "&nbsp;&nbsp;&nbsp;&nbsp;";
                                        }
                                        ?>"  ><?php
                                        $array_v1 = explode("&&", $v['courseInfo']);
                                        foreach ($array_v1 as $value) {
                                            if (Tool::clength($value, 'utf-8')>8) {
                                                echo Tool::csubstr($value, 0, 8, 'UTF-8') . "...<br/>";
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
                                    <td class="table_schedule" <?php 
                                       echo 'onclick="changeClass('.$s.','.$d.');"';
                                            ?>>
                                        <span style="color: #aaa9a9" title="-"  >-</span></td>
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
    function changeClass(s, d) {
        window.open("./index.php?r=student/editSchedule&&sequence=" + s + "&day=" + d + "", 'newwindow', 'height=400,width=600,top=0,left=0,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no,left=500,top=200,');
    }
</script>