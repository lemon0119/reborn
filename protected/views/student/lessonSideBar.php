<div class="span3">
        <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                <li class="nav-header"><img src="<?php echo IMG_UIStu_URL; ?>keyb.png">章 节 列 表</li>
                <?php foreach ($lessons as $less) {?>
                        <li id="<?php echo $less['lessonID']?>" <?php if($less['lessonID'] === $currentLesn){echo 'class=\'active\''?>>
                            <a href="./index.php?r=student/classwork&&lessonID=<?php echo $less['lessonID']?>">
                                <img src="<?php echo IMG_UIStu_URL?>listOfH.png"> <?php echo $less['lessonName']?>
                            </a>
                        </li>
                        <?php }?>
                <?php }?>
                </ul>
        </div>
</div>