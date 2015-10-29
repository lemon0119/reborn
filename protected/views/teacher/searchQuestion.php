<div class="span3">
        <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
                <form action="./index.php?r=teacher/searchQuestion" method="post">
                        <li>
                                <select name="type" >
                                        <option value="exerciseID" selected="selected">编号</option>
                                        <option value="courseID" >科目号</option>
                                        <option value="createPerson">创建人</option>
                                        <option value="requirements">内容</option>
                                  
                                </select>
                        </li>
                        <li>
                                <input name="value" type="text" class="search-query span2" placeholder="Search" />
                        </li>
                        <li style="margin-top:10px">
                                <button type="submit" class="btn_serch"></button>
                                <a href="./index.php?r=teacher/addQuestion" class="btn_add"></a>
                        </li>
                </form>
                        <li class="divider"></li>
                        <li class="nav-header"><i class="icon-knowlage"></i>基础知识</li>
                        <li ><a href="./index.php?r=teacher/choiceLst"><i class="icon-font"></i> 选择</a></li>
                        <li ><a href="./index.php?r=teacher/fillLst"><i class="icon-text-width"></i> 填空</a></li>
                        <li class="active"><a href="./index.php?r=teacher/questionLst"><i class="icon-align-left"></i> 简答</a></li>
                        <li class="divider"></li>
                        <li class="nav-header"><i class="icon-typing"></i>打字练习</li>
                        <li ><a href="./index.php?r=teacher/keyLst"><i class="icon-th"></i> 键位练习</a></li>
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
<?php if($questionLst->count()!=0){?>
    <h2>查询结果</h2>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>编号</th>
            <th>科目号</th>
            <th>内容</th>
            <th>创建人</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
    </thead>
            <tbody>        
                <?php foreach($questionLst as $model):?>
                <tr>
                    <td style="width: 50px"><?php echo $model['exerciseID'];?></td>
                    <td><?php echo $model['courseID'];?></td>
                    <td><?php  if($searchKey == 'no' || strpos($model['requirements'],$searchKey)===false)
                    {
                             if(Tool::clength($model['requirements'])<=15)
                                    {   
                                        echo $model['requirements'];                               
                                    }else{
                                        echo Tool::csubstr($model['requirements'], 0, 15)."...";
                                    } 
                    }else
                    {
                        $strStart = strpos($model['requirements'], $searchKey);
                        $start = $strStart<=9?0:$strStart-9;            
                        $content =  str_replace($searchKey, '<font color=red>'.$searchKey.'</font>',substr($model['requirements'], $start, 30));
                       if($start >= 9)
                           $content = "...".$content;
                       if(strlen($model['requirements'])>($start + 30))
                           $content = $content."...";
                       echo $content;
                     }
                    ?></td>
                    <td><?php if($model['createPerson']=="0")
                                        echo "管理员";
                                    else echo  $teachers[$model['createPerson']];
                        ?></td>
                    <td><?php echo $model['createTime'];?></td>
                    <td>
                           <a href="./index.php?r=teacher/editQuestion&&exerciseID=<?php echo $model['exerciseID'];?>&&action=look"><img src="<?php echo IMG_URL; ?>detail.png">查看</a>
                          <?php if($model['createPerson'] == Yii::app()->session['userid_now']){?>
                            <a href="./index.php?r=teacher/editQuestion&&exerciseID=<?php echo $model['exerciseID'];?>"><img src="<?php echo IMG_URL; ?>edit.png">编辑</a>
                            <a href="#"  onclick="dele(<?php echo $model['exerciseID'];?>)"><img src="<?php echo IMG_URL; ?>delete.png">删除</a>
                            <?php }else{ ?>
                            <a href="./index.php?r=teacher/copyQuestion&&code=<?php echo $code;?>&&exerciseID=<?php echo $model['exerciseID'];?>"><img src="<?php echo IMG_URL; ?>copy.png">复制</a>
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
    window.wxc.xcConfirm('复制填空题成功！', window.wxc.xcConfirm.typeEnum.success);
    else if(result === '0')
    window.wxc.xcConfirm('复制填空题失败！', window.wxc.xcConfirm.typeEnum.error);
    result = "";
}      
);
   function dele(exerciseID){
      var option = {
						title: "警告",
						btn: parseInt("0011",2),
						onOk: function(){
							window.location.href = "./index.php?r=teacher/deleteQuestion&&exerciseID=" + exerciseID;
						}
					}
					window.wxc.xcConfirm("您确定删除吗？", "custom", option);
  }

</script>