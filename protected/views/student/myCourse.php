<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<div class="span3">
        <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                <li class="nav-header">章 节 列 表</li>
                <?php foreach ($lessons as $less) {?>
                        <li id="<?php echo $less['lessonID']?>" <?php if($less['lessonID'] === $currentLesn) echo 'class=\'active\''?>>
                            <a href="./index.php?r=student/myCourse&&lessonID=<?php echo $less['lessonID']?>">
                                <i class="icon-list-alt"></i> <?php echo $less['lessonName']?>
                            </a>
                        </li>
                <?php }?>
                </ul>
        </div>
</div>
<div class="span9">
    <h3 >课 堂 作 业</h3>
    <table class="table table-bordered table-striped">
            <thead>
                    <tr>
                            <th>标　　题</th>
                            <th>开始时间</th>
                            <th>截止时间</th>
                            <th>是否完成</th>
                            <th>操　　作</th>
                    </tr>
            </thead>
            <tbody>
                <?php foreach ($myCourse as $work){?>
                    <tr>
                            <td>
                                    <?php echo $work['suiteName'];?>
                            </td>
                            <td>
                                    <?php echo $work['begintime'];?>
                            </td>
                            <td>
                                    <?php echo $work['endtime'];?>
                            </td>
                            <td>
                                    <?php echo '已完成';?>
                            </td>
                            <td>
                                <?php //if(time() > strtotime($work['begintime']) && time() < strtotime($work['endtime'])){?>
                                <a href="./index.php?r=student/clswkOne&&suiteID=<?php echo $work['suiteID'];?>" class="view-link"><?php echo '进　　入';?></a>
                                <?php //} else { ?>
                                    <?php //echo '<font color="#ff0000">已经截止</span>'; ?>
                                <a href="./index.php?r=student/viewAns&&suiteID=<?php echo $work['suiteID'];?>" class="view-link"><?php echo '查看答案';?></a>
                                <?php //} ?>
                            </td>
                    </tr>
                <?php }?>
            </tbody>
    </table>
</div>
