<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="span3">
        <div class="well" style="padding: 8px 0;">
            <div class="well-topnoradius" style="padding: 8px 0;height:600px;overflow:auto;top: 20px">
                <ul class="nav nav-list">
                        <li class="nav-header">基础知识</li>
                        <li id="li-choice">
                            <a href="./index.php?r=teacher/choice"><i class="icon-font"></i> <span style="position: relative;top: 6px">选 择 题</span></a>
                        </li>
                        <li id="li-filling">
                            <a href="./index.php?r=teacher/filling"><i class="icon-text-width"></i><span style="position: relative;top: 6px"> 填 空 题</span></a>
                        </li>
                        <li id="li-question">
                            <a href="./index.php?r=teacher/question"><i class="icon-align-left"></i> <span style="position: relative;top: 6px">简 答 题</span></a>
                        </li>
                        <li class="nav-header">键打练习</li>
                        <?php foreach ($exercise['key'] as $keyType) :?>
                            <li id="li-key-<?php echo $keyType['exerciseID'];?>">
                                <a title="<?php echo $keyType['title']; ?>" href="./index.php?r=teacher/keyType&&exerID=<?php echo $keyType['exerciseID'];?>">
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
                        <?php endforeach;?>
                        <li class="nav-header">看打练习</li>
                        <?php foreach ($exercise['look'] as $lookType) :?>
                            <li id="li-look-<?php echo $lookType['exerciseID'];?>">
                                <a title="<?php echo $lookType['title'];?>" href="./index.php?r=teacher/lookType&&exerID=<?php echo $lookType['exerciseID'];?>">
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
                        <?php endforeach;?>
                        <li class="nav-header">听打练习</li>
                        <?php foreach ($exercise['listen'] as $listenType) :?>
                        <li id="li-listen-<?php echo $listenType['exerciseID'];?>">
                            <a title="<?php echo $listenType['title']; ?>" href="./index.php?r=teacher/listenType&&exerID=<?php echo $listenType['exerciseID'];?>">
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
                        <?php endforeach;?>
                </ul>
            </div>
        </div>
    <a href="./index.php?r=teacher/assignWork"  class="btn btn-primary">返回</a>
</div>
