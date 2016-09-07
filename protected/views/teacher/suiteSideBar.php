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
                                    <a href="./index.php?r=teacher/keyType&&exerID=<?php echo $keyType['exerciseID'];?>">
                                        <i class="icon-th"></i>
                                        <span style="position: relative;top: 6px">
                                        <?php echo $keyType['title']?>
                                        </span>
                                    </a>
                            </li>
                        <?php endforeach;?>
                        <li class="nav-header">看打练习</li>
                        <?php foreach ($exercise['look'] as $lookType) :?>
                            <li id="li-look-<?php echo $lookType['exerciseID'];?>">
                                    <a href="./index.php?r=teacher/lookType&&exerID=<?php echo $lookType['exerciseID'];?>">
                                        <i class="icon-eye-open"></i>
                                        <span style="position: relative;top: 6px">
                                        <?php echo $lookType['title']?>
                                        </span>
                                    </a>
                            </li>
                        <?php endforeach;?>
                        <li class="nav-header">听打练习</li>
                        <?php foreach ($exercise['listen'] as $listenType) :?>
                        <li id="li-listen-<?php echo $listenType['exerciseID'];?>">
                                <a href="./index.php?r=teacher/listenType&&exerID=<?php echo $listenType['exerciseID'];?>">
                                    <i class="icon-headphones"></i> 
                                    <span style="position: relative;top: 6px">
                                    <?php echo $listenType['title']?>
                                    </span>
                                </a>
                        </li>
                        <?php endforeach;?>
                </ul>
        </div>
    <a href="./index.php?r=teacher/assignWork"  class="btn btn-primary">返回</a>
</div>
