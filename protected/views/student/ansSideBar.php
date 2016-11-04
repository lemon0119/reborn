
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="span3">
        <div class="well" style="padding: 8px 0;">
            <div class="well-topnoradius" style="padding: 8px 0;height:594px;overflow:auto;top: 10px">
                <ul class="nav nav-list">
                    <?php if (count($exercise['choice']) != 0 && count($exercise['filling']) != 0 && count($exercise['question']) != 0) { ?>
                        <li class="nav-header">基础知识</li>
                         <?php } if (count($exercise['choice']) != 0) { ?>
                        <li id="li-choice">
                            <a href="./index.php?r=student/ansChoice&&flag=<?php echo $flag; ?>"><i class="icon-font"></i><span style="position: relative;top: 6px"> 选 择 题</span></a>
                        </li>
                        <?php } if (count($exercise['filling']) != 0) { ?>
                        <li id="li-filling">
                            <a href="./index.php?r=student/ansFilling&&flag=<?php echo $flag; ?>"><i class="icon-text-width"></i><span style="position: relative;top: 6px"> 填 空 题</span></a>
                        </li>
                        <?php }  if (count($exercise['question']) != 0) { ?>
                        <li id="li-question">
                            <a href="./index.php?r=student/ansQuestion&&flag=<?php echo $flag; ?>"><i class="icon-align-left"></i><span style="position: relative;top: 6px"> 简 答 题</span></a>
                        </li>
                         <?php }  if (count($exercise['key'])!= 0) { ?>
                        <li class="nav-header">键打练习</li>
                        <?php foreach ($exercise['key'] as $keyType) :?>
                            <li id="li-key-<?php echo $keyType['exerciseID'];?>">
                                <a href="./index.php?r=student/ansKeyType&&exerID=<?php echo $keyType['exerciseID'];?>&&flag=<?php echo $flag; ?>" title="<?php echo $keyType['title']; ?>">
                                        <i class="icon-th"></i>
                                        <span style="position: relative;top: 6px">
                                        <?php if (Tool::clength($keyType['title']) <= 13){
                                                    echo $keyType['title'];
                                               }else{
                                                    echo Tool::csubstr($keyType['title'], 0, 13) . "...";
                                               } ?>
                                        </span>
                                    </a>
                            </li>
                        <?php endforeach;
 } if (count($exercise['look']) != 0) { ?>
                        <li class="nav-header">看打练习</li>
                        <?php foreach ($exercise['look'] as $lookType) :?>
                            <li id="li-look-<?php echo $lookType['exerciseID'];?>">
                                <a href="./index.php?r=student/ansLookType&&type=look&&exerID=<?php echo $lookType['exerciseID'];?>&&flag=<?php echo $flag; ?>" title="<?php echo $lookType['title']; ?>">
                                        <i class="icon-eye-open"></i>
                                        <span style="position: relative;top: 6px">
                                        <?php if (Tool::clength($lookType['title']) <= 13){
                                                    echo $lookType['title'];
                                               }else{
                                                    echo Tool::csubstr($lookType['title'], 0, 13) . "...";
                                               } ?>
                                        </span>
                                    </a>
                            </li>
                        <?php endforeach;
         } if (count($exercise['listen']) != 0) { ?>
                        <li class="nav-header">听打练习</li>
                        <?php foreach ($exercise['listen'] as $listenType) :?>
                        <li id="li-listen-<?php echo $listenType['exerciseID'];?>">
                                <a href="./index.php?r=student/ansLookType&&type=listen&&exerID=<?php echo $listenType['exerciseID'];?>&&flag=<?php echo $flag; ?>" title="<?php echo $listenType['title']; ?>">
                                    <i class="icon-headphones"></i>
                                    <span style="position: relative;top: 6px">
                                    <?php if (Tool::clength($listenType['title']) <= 13){
                                                echo $listenType['title'];
                                           }else{
                                                echo Tool::csubstr($listenType['title'], 0, 13) . "...";
                                           } ?>
                                    </span>
                                </a>
                        </li>
         <?php endforeach; } ?>
                </ul>
            </div>
        </div>
</div>