<div class="span3">
        <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                <li class="nav-header">查询</li>
                <form action="./index.php?r=teacher/searchKey" method="post">
                        <li>
                                <select name="type" style="width: 185px">
                                        <option value="exerciseID" selected="selected">编号</option>
                                        <option value="courseID" >课程号</option>
                                        <option value="createPerson" >创建人</option>
                                        <option value="title">题目名</option>
                                </select>
                        </li>
                        <li>
                                <input name="value" type="text" class="search-query span2" placeholder="Search" />
                        </li>
                        <li style="margin-top:10px">
                                <button type="submit" class="btn btn-primary">查询</button>
                                <a href="./index.php?r=teacher/addKey" class="btn">添加</a>
                        </li>
                </form>
                        <li class="divider"></li>
                        <li class="nav-header">基础知识</li>
                        <li ><a href="./index.php?r=teacher/choiceLst"><i class="icon-font"></i> 选择</a></li>
                        <li ><a href="./index.php?r=teacher/fillLst"><i class="icon-text-width"></i> 填空</a></li>
                        <li ><a href="./index.php?r=teacher/questionLst"><i class="icon-align-left"></i> 简答</a></li>
                        <li class="divider"></li>
                        <li class="nav-header">打字练习</li>
                        <li class="active"><a href="./index.php?r=teacher/keyLst"><i class="icon-th"></i> 键位练习</a></li>
                        <li ><a href="./index.php?r=teacher/lookLst"><i class="icon-eye-open"></i> 看打练习</a></li>
                        <li ><a href="./index.php?r=teacher/listenLst"><i class="icon-headphones"></i> 听打练习</a></li>
                </ul>
        </div>
</div>


<?php
    //得到老师ID对应的名称
    foreach ($teacher as $model):
    $teacherID=$model['userID'];
    $teachers["$teacherID"]=$model['userName'];
    endforeach;
    $code = mt_rand(0, 1000000);
?>
<div class="span9">
<?php if($keyLst->count()!=0){?>
    <h2>查询结果</h2>
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
                    <?php foreach($keyLst as $model):?>
                    <tr>
                        <td style="width: 50px"><?php echo $model['exerciseID'];?></td>
                        <td><?php echo $model['courseID'];?></td>
                        <td><?php echo $model['title']?></td>
                        <td><?php echo $model['courseID'];?></td>
                        
                        <td><?php  if(strlen($model['content'])<=30)
                                        echo $model['content'];
                                    else
                                        echo substr($model['content'], 0, 30)."...";
                                        ?></td>
                        <td><?php if($model['createPerson']=="0")
                                        echo "管理员";
                                    else echo  $teachers[$model['createPerson']];
                            ?></td>
                        <td><?php echo $model['createTime'];?></td>
                        <td>
                            <a href="./index.php?r=teacher/editKey&&exerciseID=<?php echo $model['exerciseID'];?>&&action=look"><img src="<?php echo IMG_URL; ?>detail.png">查看</a>
                          <?php if($model['createPerson'] == Yii::app()->session['userid_now']){?>
                            <a href="./index.php?r=teacher/editKey&&exerciseID=<?php echo $model['exerciseID'];?>"><img src="<?php echo IMG_URL; ?>edit.png">编辑</a>
                            <a href="#"  onclick="dele(<?php echo $model['exerciseID'];?>)"><img src="<?php echo IMG_URL; ?>delete.png">删除</a>
                            <?php }else{ ?>
                            <a href="./index.php?r=teacher/copyKey&&code=<?php echo $code;?>&&exerciseID=<?php echo $model['exerciseID'];?>"><img src="<?php echo IMG_URL; ?>copy.png">复制</a>
                            <?php }?>
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
<?php } else {?>
    <h2>查询结果为空！</h2>
<?php }?>
</div>

<script>
    $(document).ready(function(){
    var result = <?php  if(isset($result)) echo "'$result'"; else echo'1';?>;
    if(result === '1')
        alert('复制选择题成功！');
    else if(result === '0')
        alert('复制选择题失败！');
    result = "";
}      
);
   function dele(exerciseID){
      if(confirm("您确定删除吗？")){
          window.location.href = "./index.php?r=teacher/deleteKey&&exerciseID=" + exerciseID;
      }
  }

</script>