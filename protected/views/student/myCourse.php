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
                <li class="nav-header"><img src="<?php echo IMG_UIStu_URL; ?>keyb.png">课 时 列 表</li>
                </ul>
                 <div class="well-topnoradius" style="padding: 8px 0;height:585px;overflow:auto;top: 20px">
                     <ul class="nav nav-list">
                <?php foreach ($lessons as $less) {?>
                        <li id="<?php echo $less['lessonID']?>" <?php 
                            if($less['lessonID'] == $currentLesn) echo 'class=\'active\'';?>>
                            <?php if($less['lessonID'] == $currentLesn){?>
                                <a href="./index.php?r=student/myCourse&&lessonID=<?php echo $less['lessonID']?>">
                                    <img class="act" src="<?php echo IMG_UIStu_URL?>listOfH.png"><?php echo $less['lessonName']?>
                                </a>
                            <?php }else{?>
                            <a href="./index.php?r=student/myCourse&&lessonID=<?php echo $less['lessonID']?>">
                                <img class="act" src="<?php echo IMG_UIStu_URL?>listOf.png"><?php echo $less['lessonName']?>
                            </a>
                            <?php }?>
                        </li>
                          <?php }?>
                
                </ul>
                 </div>
        </div>
</div>
 
<div class="span9">
    <h3 >课 堂 作 业</h3>
    <table class="table table-bordered table-striped">
            <thead>
                    <tr>
                            <th>标　　题</th>
                            <th>状      态</th>
                            
                            <th>操　　作</th>
                    </tr>
            </thead>
            <tbody>
                <?php $n=0;
                foreach ($myCourse as $work){?>
                    <tr>
                            <td>
                                    <?php echo $work['suiteName'];?>
                            </td>
                            <td>
                                   <?php if ($ratio_accomplish[$n]==1){
                                    	echo '<font style="color:green">已提交</font>';
                                    }else {
                                    	echo '<font style="color:red">未提交</font>';
                                    }?>
                            </td>
                            <td>
                                <?php if(isset($_GET['lessonID'])){ ?>
                                     <?php if ($ratio_accomplish[$n]==1){
//                              	echo  '答题';
                              }else {?>
                                <a href="./index.php?r=student/clswkOne&&suiteID=<?php echo $work['workID'];?>&&lessonID=<?php echo $_GET['lessonID'];?>" class="view-link"><img title="开始答题" src="<?php echo IMG_UIStu_URL; ?>answer.png"></a>
                                <?php }?>
                               <?php if ($ratio_accomplish[$n]==1){?>
                                <a href="./index.php?r=student/viewAns&&suiteID=<?php echo $work['workID'];?>&&lessonID=<?php echo $_GET['lessonID'];?>" class="view-link"><img title="查看答案" src="<?php echo IMG_URL; ?>detail.png"></a>
                                <?php }else {?>
                               <?php }$n++;?>
                                    
                               <?php }else{ ?>
                                    <?php if ($ratio_accomplish[$n]==1){
//                              	echo  '答题';
                              }else {?>
                                <a href="./index.php?r=student/clswkOne&&suiteID=<?php echo $work['workID'];?>" class="view-link"><img title="开始答题" src="<?php echo IMG_UIStu_URL; ?>answer.png"></a>
                                <?php }?>
                               <?php if ($ratio_accomplish[$n]==1){?>
                                <a href="./index.php?r=student/viewAns&&suiteID=<?php echo $work['workID'];?>" class="view-link"><img title="查看答案" src="<?php echo IMG_URL; ?>detail.png"></a>
                                <?php }else {?>
                               <?php }$n++;?>
                              <?php }?>
                            </td>
                    </tr>
                <?php }?>
            </tbody>
    </table>
</div>
