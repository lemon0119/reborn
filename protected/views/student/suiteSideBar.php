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
                            <a href="./index.php?r=student/choice"><i class="icon-font"></i> 选 择 题</a>
                        </li>
                        <li id="li-filling">
                                <a href="./index.php?r=student/filling"><i class="icon-text-width"></i> 填 空 题</a>
                        </li>
                        <li id="li-question">
                                <a href="./index.php?r=student/question"><i class="icon-align-left"></i> 简 答 题</a>
                        </li>
                        <li class="nav-header">键位练习</li>
                        <?php foreach ($exercise['key'] as $keyType) :?>
                            <li id="li-key-<?php echo $keyType['exerciseID'];?>">
                                    <a href="./index.php?r=student/keyType">
                                        <i class="icon-th"></i>
                                        <?php echo $keyType['title']?>
                                    </a>
                            </li>
                        <?php endforeach;?>
                        <li class="nav-header">看打练习</li>
                        <?php foreach ($exercise['look'] as $lookType) :?>
                            <li id="li-look-<?php echo $keyType['exerciseID'];?>">
                                    <a href="./index.php?r=student/lookType">
                                        <i class="icon-th"></i>
                                        <?php echo $lookType['title']?>
                                    </a>
                            </li>
                        <?php endforeach;?>
                        <li class="nav-header">听打练习</li>
                        <?php foreach ($exercise['listen'] as $listenType) :?>
                        <li id="li-look-<?php echo $keyType['exerciseID'];?>">
                                <a href="./index.php?r=student/listenType">
                                    <i class="icon-headphones"></i> 
                                    <?php echo $listenType['title']?>
                                </a>
                        </li>
                        <?php endforeach;?>
                </ul>
        </div>
</div>