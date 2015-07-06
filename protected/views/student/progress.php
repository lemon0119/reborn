<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<!-- BEGIN CONTAINER -->
<?php require 'proMenu.php';?>
<div class="hero-unit">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>作业名称</th>
                <th>完成度</th>
                <th>正确率</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $suiteID => $suite){?>
            <tr>
                <td style="width: 300px"><a href="./index.php?r=student/proDetail&&suiteID=<?php echo $suiteID;?>&&recordID=<?php echo $suite['recordID'];?>"><?php echo $suite['suiteName'];?></a></td>
                <td><?php Tool::printprobar($suite['accomplish']);?></td>
                <td><?php Tool::printprobar($suite['correct']);?></td>
            </tr>
            <?php }?>
        </tbody>
    </table>
</div>
<!-- END CONTAINER -->