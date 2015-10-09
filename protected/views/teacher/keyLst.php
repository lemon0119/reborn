<div class="span3">
        <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
                <form action="./index.php?r=teacher/searchKey" method="post">
                        <li>
                                <select name="type" >
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
                                <button type="submit" class="btn_serch"></button>
                                <a href="./index.php?r=teacher/AddKey" class="btn_add"></a>
                        </li>
                </form>
                        <li class="divider"></li>
                        <li class="nav-header"><i class="icon-knowlage"></i>基础知识</li>
                        <li ><a href="./index.php?r=teacher/choiceLst"><i class="icon-font"></i> 选择</a></li>
                        <li ><a href="./index.php?r=teacher/fillLst"><i class="icon-text-width"></i> 填空</a></li>
                        <li ><a href="./index.php?r=teacher/questionLst"><i class="icon-align-left"></i> 简答</a></li>
                        <li class="divider"></li>
                        <li class="nav-header"><i class="icon-typing"></i>打字练习</li>
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
    <h2>键位练习列表</h2>
    <!-- 键位习题列表-->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="font-center">编号</th>
                <th class="font-center">课程号</th>
                <th class="font-center">题目</th>
                <th class="font-center">键位码</th>
                <th class="font-center">创建人</th>
                <th class="font-center">创建时间</th>
                <th class="font-center">操作</th>
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($keyLst as $model):?>
                    <tr>
                        <td class="font-center" style="width: 50px"><?php echo $model['exerciseID'];?></td>
                        <td class="font-center"><?php echo $model['courseID'];?></td>
                        <td class="font-center"><?php  if(Tool::clength($model['title'])<=7)
                                        echo $model['title'];
                                    else
                                        echo Tool::csubstr($model['title'], 0, 7)."...";?></td>
                        <td class="font-center"><?php  if(Tool::clength($model['content'])<=10)
                                   echo  str_replace("$",":",$model['content']);
                               else
                                   echo str_replace("$",":",Tool::csubstr($model['content'], 0, 10)."...");
                                        ?>
                                        </td>
                        <td class="font-center"><?php if($model['createPerson']=="0")
                                        echo "管理员";
                                    else echo  $teachers[$model['createPerson']];
                            ?></td>
                        <td class="font-center"><?php echo $model['createTime'];?></td>
                        <td class="font-center" style="width: 100px">
                            <a href="./index.php?r=teacher/editKey&&exerciseID=<?php echo $model['exerciseID'];?>&&action=look"><img title="查看" src="<?php echo IMG_URL; ?>detail.png"></a>
                          <?php if($model['createPerson'] == Yii::app()->session['userid_now']){?>
                            <a href="./index.php?r=teacher/editKey&&exerciseID=<?php echo $model['exerciseID'];?>"><img title="编辑" src="<?php echo IMG_URL; ?>edit.png"></a>
                            <a href="#"  onclick="dele(<?php echo $model['exerciseID'];?>)"><img title="删除" src="<?php echo IMG_URL; ?>delete.png"></a>
                            <?php }else{ ?>
                            <a href="./index.php?r=teacher/copyKey&&code=<?php echo $code;?>&&exerciseID=<?php echo $model['exerciseID'];?>"><img title="复制" src="<?php echo IMG_URL; ?>copy.png"></a>
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
   
    </div>

<script>
    $(document).ready(function(){
    var result = <?php  if(isset($result)) echo "'$result'"; else echo'1';?>;
    if(result === '1')
    window.wxc.xcConfirm('复制选择题成功！', window.wxc.xcConfirm.typeEnum.success);
    else if(result === '0')
    window.wxc.xcConfirm('复制选择题失败！', window.wxc.xcConfirm.typeEnum.error);
    result = "";
}      
);
   function dele(exerciseID){
     
      var option = {
						title: "警告",
						btn: parseInt("0011",2),
						onOk: function(){
							window.location.href = "./index.php?r=teacher/deleteKey&&exerciseID=" + exerciseID;
						}
					}
					window.wxc.xcConfirm("您确定删除吗？", "custom", option);
  }

</script>

