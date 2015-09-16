<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="span12">
    <center><h3 >课 堂 考 试</h3></center>
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
                <?php foreach ($classexams as $exam){?>
                    <tr>
                            <td>
                                    <?php echo $exam['examName'];?>
                            </td>
                            <td>
                                    <?php echo $exam['begintime'];?>
                            </td>
                            <td>
                                    <?php echo $exam['endtime'];?>
                            </td>
                            <td>
                                    <?php echo '已完成';?>
                            </td>
                            <td>
                                <?php if(1){?>
                                <a href="./index.php?r=student/clsexamOne&&suiteID=<?php echo $exam['examID'];?>&&workID=<?php echo $exam['workID'];?>" class="view-link"><?php echo '进　　入';?></a>
                                <?php } else { ?>
                                    <?php echo '<font color="#ff0000">已经截止</font>'; ?>
                                  &nbsp;&nbsp;&nbsp|  &nbsp;&nbsp;&nbsp    
                                  <a href="./index.php?r=student/viewAns&&suiteID=<?php echo $exam['examID'];?>&&workID=<?php echo $exam['workID'];?>" class="view-link"><?php echo '查看成绩';?></a>
                                <?php } ?>
                            </td>
                    </tr>
                <?php }?>
            </tbody>
    </table>
</div>