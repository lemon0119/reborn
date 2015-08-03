 <div class="span3">
        <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                <li class="nav-header">查询</li>
                <form action="./index.php?r=teacher/searchChoice" method="post">
                        <li>
                                <select name="type" style="width: 185px">
                                        <option value="exerciseID" selected="selected">编号</option>
                                        <option value="courseID" >课程号</option>
                                        
                                </select>
                        </li>
                        <li>
                                <input name="value" type="text" class="search-query span2" placeholder="Search" />
                        </li>
                        <li style="margin-top:10px">
                                <button type="submit" class="btn btn-primary">查询</button>
                                <a href="./index.php?r=teacher/addChoice" class="btn">添加</a>
                        </li>
                </form>
                        <li class="divider"></li>
                        <li class="nav-header">基础知识</li>
                        <li class="active"><a href="./index.php?r=teacher/choiceLst"><i class="icon-font"></i> 选择</a></li>
                        <li ><a href="./index.php?r=teacher/fillLst"><i class="icon-text-width"></i> 填空</a></li>
                        <li ><a href="./index.php?r=teacher/questionLst"><i class="icon-align-left"></i> 简答</a></li>
                        <li class="divider"></li>
                        <li class="nav-header">打字练习</li>
                        <li ><a href="./index.php?r=teacher/keyLst"><i class="icon-th"></i> 键位练习</a></li>
                        <li ><a href="./index.php?r=teacher/lookLst"><i class="icon-eye-open"></i> 看打练习</a></li>
                        <li ><a href="./index.php?r=teacher/listenLst"><i class="icon-headphones"></i> 听打练习</a></li>
                </ul>
        </div>
</div>


    
<div class="span9">
    <h2>选择题列表</h2>
    <!-- 键位习题列表-->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>编号</th>
                <th>课程号</th>
                <th>内容</th>
                <th>创建人</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($choiceLst as $model):?>
                    <tr>
                        <td style="width: 50px"><?php echo $model['exerciseID'];?></td>
                        <td><?php echo $model['courseID'];?></td>
                        <td><?php  if(strlen($model['requirements'])<=15)
                                        echo $model['requirements'];
                                    else
                                        echo substr($model['requirements'], 0, 15)."...";
                                        ?></td>
                        <td><?php echo Yii::app()->user->name;
                            ?></td>
                        <td><?php echo $model['createTime'];?></td>
                        <td>
                            <a href="./index.php?r=teacher/editChoice&&exerciseID=<?php echo $model['exerciseID'];?>&&action=look"><img src="<?php echo IMG_URL; ?>detail.png">查看</a>
                            <a href="./index.php?r=teacher/editChoice&&exerciseID=<?php echo $model['exerciseID'];?>"><img src="<?php echo IMG_URL; ?>edit.png">编辑</a>
                        </td>
                    </tr>            
                    <?php endforeach;?> 
                </tbody>
    </table>
    <!-- 学生列表结束 -->
    <!-- 显示翻页标签 -->
    <div align=center>
    <?php   
        $this->widget('CLinkPager',array('pages'=>$pages));
    ?>
    </div>
    <!-- 翻页标签结束 -->
   
    </div>
