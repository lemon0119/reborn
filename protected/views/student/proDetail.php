<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<!-- BEGIN CONTAINER -->
        <!-- 右侧内容展示开始-->
            <?php require 'proMenu.php';?>
            <div class="hero-unit">
                <h3><?php echo $suiteName;?></h3>
                <?php if(isset($result['listen'])){?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>听打练习</th>
                            <th>完成度</th>
                            <th>正确率</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result['listen'] as $exerID => $exer){
                            $goDtl = './index.php?r=student/gotoDetail&&exerID='.$exerID.'&&exerType=listen';
                        ?>
                        <tr>
                            <td style="width: 300px"><a href="<?php echo $goDtl;?>"><?php echo $exer['title'];?></a></td>
                            <td><?php Tool::printprobar($exer['accomplish'])?></td>
                            <td><?php Tool::printprobar($exer['correct'])?></td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
                <?php 
                    }
                    if(isset($result['look'])){
                ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>看打练习</th>
                            <th>完成度</th>
                            <th>正确率</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result['look'] as $exerID => $exer){
                            $goDtl = './index.php?r=student/gotoDetail&&exerID='.$exerID.'&&exerType=look';?>
                        <tr>
                            <td style="width: 300px"><a href="<?php echo $goDtl;?>"><?php echo $exer['title'];?></a></td>
                            <td><?php Tool::printprobar($exer['accomplish']);?></td>
                            <td><?php Tool::printprobar($exer['correct']);?></td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
                <?php 
                    }
                    if(isset($result['key'])){
                ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>键位练习</th>
                            <th>完成度</th>
                            <th>正确率</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result['key'] as $exerID => $exer){
                            $goDtl = './index.php?r=student/gotoDetail&&exerID='.$exerID.'&&exerType=key';?>
                        <tr>
                            <td style="width: 300px"><a href="<?php echo $goDtl;?>"><?php echo $exer['title'];?></a></td>
                            <td><?php Tool::printprobar($exer['accomplish']);?></td>
                            <td><?php Tool::printprobar($exer['correct']);?></td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
                <?php }?>
                <table class="table table-bordered table-striped">
                        <thead>
                            <?php //foreach ($result['listen'] as $exerID => $exer){?>
                            <tr>
                                <th>基础知识 </th>
                                <th>完成度</th>
                                <th>正确率</th>
                            </tr>
                            <?php //}?>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width: 300px"><a href="<?php echo '#';//$goDtl;?>">基础知识练习</a></td>
                                <td><?php Tool::printprobar(0.9);?></td>
                                <td><?php Tool::printprobar(0.8);?></td>
                            </tr>
                        </tbody>
                </table>
            </div>
        <!-- 右侧内容展示结束-->
<!-- END CONTAINER -->