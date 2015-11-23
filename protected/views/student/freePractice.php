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
                <?php foreach ($lessons as $less) {?>
                        <li id="<?php echo $less['lessonID']?>">
                            <a href="./index.php?r=student/freePractice&&lessonID=<?php echo $less['lessonID']?>">
                                <img class="act" src="<?php echo IMG_UIStu_URL?>listOf.png"><?php echo $less['lessonName']?>
                            </a>
                        </li>
                          <?php }?>
                
                </ul>
        </div>
</div>
<div class="span9">
    <h3 >自 由 练 习</h3>
    <table class="table table-bordered table-striped">
            <thead>
                    <tr>
                            <th>标　　题</th>
                            <th>状      态</th>
                            
                            <th>操　　作</th>
                    </tr>
            </thead>
            <tbody>
            </tbody>
    </table>
</div>
