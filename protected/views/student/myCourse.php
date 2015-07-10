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
                            <a href="./index.php?r=student/classwork&&lessonID=<?php echo $less['lessonID']?>">
                                <i class="icon-list-alt"></i> <?php echo $less['lessonName']?>
                            </a>
                        </li>
                <?php }?>
                </ul>
        </div>
</div>