<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require 'lessonSideBar.php';
?>
<div class="span9">
    <h3 >课 堂 作 业</h3>
    <table class="table table-bordered table-striped">
            <thead>
                    <tr>
                            <th>标　　题</th>
                            <th>状态</th>
                            <th>操　　作</th>
                    </tr>
            </thead>
            <tbody>
                <?php $n=0;
                foreach ($classwork as $work){?>
                    <tr>
                            <td>
                                    <?php echo $work['suiteName'];?>
                            </td>
                           <td>
                                    <?php if ($ratio_accomplish[$n] ==1){
                                    	echo '<font style="color:green">已提交</font>';
                                    }else {
                                    	echo '<font style="color:red">未提交</font>';
                                    }?>
                           </td>
                            <td>
                              <?php if ($ratio_accomplish[$n]==1){
                              //	echo  '答题';
                              }else {?>
                                <a href="./index.php?r=student/clswkOne&&suiteID=<?php echo $work['workID'];?>" class="view-link"><img title="开始答题" src="<?php echo IMG_UIStu_URL; ?>answer.png"></a>
                                <?php }?>
                               <?php if ($ratio_accomplish[$n]==1){?>
                               	<a href="./index.php?r=student/viewAns&&suiteID=<?php echo $work['workID'];?>" class="view-link"><img title="查看答案" src="<?php echo IMG_URL; ?>detail.png"></a>
                               <?php }else {?>
                              <?php } $n++;?>
                                
                               
                            </td>
                    </tr>
                    
                <?php }?>
            </tbody>
    </table>
</div>