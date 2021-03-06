 <div class="span3">
        <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                    <li class="nav-header"><i class="icon-navsearch" style="position:relative;bottom:4px;left:"></i>搜索</li>
                <form action="./index.php?r=teacher/searchChoice" method="post">
                        <li>
                            <div class="selectoption">
                                <select  name="type" >
                                    <option  value="exerciseID" >编号</option>
                                        <option  value="createPerson" >创建人</option>
                                        <option  value="requirements" selected="selected">内容</option>
                                </select>
                            </div>
                        </li>
                        <li>
                                <input name="value" type="text" class="search-query span2" placeholder="Search" />
                        </li>
                        <li style="margin-top:10px">
                            <button type="submit" class="btn_4big">搜 索</button>
                            <button onclick="window.location.href='./index.php?r=teacher/addChoice'" type="button" class="btn_4big">添 加</button>
                        </li>
                </form>
                        <li class="divider"></li>
                        <li class="nav-header"><i class="icon-knowlage" style="position:relative;bottom:5px;left:"></i>基础知识</li>
                        <li class="active"><a href="./index.php?r=teacher/choiceLst"><i class="icon-font" style="position:relative;bottom:5px;left:"></i> 选择</a></li>
                        <li ><a href="./index.php?r=teacher/fillLst"><i class="icon-text-width" style="position:relative;bottom:5px;left:"></i> 填空</a></li>
                        <li ><a href="./index.php?r=teacher/questionLst"><i class="icon-align-left" style="position:relative;bottom:5px;left:"></i> 简答</a></li>
                        <li class="divider"></li>
                        <li class="nav-header"><i class="icon-typing" style="position:relative;bottom:5px;left:"></i>打字练习</li>
                        <li ><a href="./index.php?r=teacher/keyLst"><i class="icon-th" style="position:relative;bottom:5px;left:"></i> 键打练习</a></li>
                        <li ><a href="./index.php?r=teacher/lookLst"><i class="icon-eye-open"style="position:relative;bottom:5px;left:"></i> 看打练习</a></li>
                        <li ><a href="./index.php?r=teacher/listenLst"><i class="icon-headphones" style="position:relative;bottom:5px;left:"></i> 听打练习</a></li>
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
    <h2>选择题列表</h2>
    <input type="checkbox" name="all" onclick="check_all(this, 'checkbox[]')" style="margin-bottom: 3px"> 全选　　批量操作：
    <a href="#" onclick="copyCheck()"><img title="批量复制" src="<?php echo IMG_URL; ?>copy.png"></a>
    <!-- 键位习题列表-->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="font-center">选择</th>
                <th class="font-center">编号</th>
                <!--<th class="font-center">科目号</th>-->
                <th class="font-center">内容</th>
                <th class="font-center">创建人</th>
                <th class="font-center">创建时间</th>
                <th class="font-center">操作</th>
            </tr>
        </thead>
                <tbody>   
                <form id="copyForm" method="post" action="./index.php?r=teacher/copyChoice" > 
                    <?php foreach($choiceLst as $model):?>
                    <tr>
                        <td class="font-center" style="width: 50px"> <input type="checkbox" name="checkbox[]" value="<?php echo $model['exerciseID'];?>" /> </td>
                        <td class="font-center" style="width: 50px"><?php echo $model['exerciseID'];?></td>
                        <!--<td class="font-center"><?php echo $model['courseID'];?></td>-->
                        <td class="font-center"><?php  if(Tool::clength($model['requirements'])<=10)
                                   echo  $model['requirements'];
                               else
                                   echo Tool::csubstr($model['requirements'], 0, 10)."...";
                                        ?></td>
                        <td class="font-center"><?php  if($model['createPerson']=="0")
                                        echo "管理员";
                                    else 
                                        if(isset($teachers[$model['createPerson']])){
                                echo $teachers[$model['createPerson']];
                            }else{
                                echo "未知";
                            }
                            
                            ?></td>
                        <td class="font-center"><?php echo $model['createTime'];?></td>
                        <td class="font-center" style="width: 100px">
                            <a href="./index.php?r=teacher/editChoice&&exerciseID=<?php echo $model['exerciseID'];?>&&action=look"><img title="查看" src="<?php echo IMG_URL; ?>detail.png"></a>
                          <?php if($model['createPerson'] == Yii::app()->session['userid_now']){?>
                            <a href="./index.php?r=teacher/editChoice&&exerciseID=<?php echo $model['exerciseID'];?>"><img title="编辑" src="<?php echo IMG_URL; ?>edit.png"></a>
                            <a href="#"  onclick="dele(<?php echo $model['exerciseID'];?>)"><img title="删除" src="<?php echo IMG_URL; ?>delete.png"></a>
                            <?php }else{ ?>
                            <a href="./index.php?r=teacher/copyChoice&&code=<?php echo $code;?>&&exerciseID=<?php echo $model['exerciseID'];?>"><img title="复制" src="<?php echo IMG_URL; ?>copy.png"></a>
                            <?php }?>
                        </td>
                    </tr>            
                    <?php endforeach;?> 
                </form>
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
        var $tip=<?php if(isset($tip)) echo "'$tip'";else echo "''"?>;
        if($tip=='此题目已经被占用!'){
            window.wxc.xcConfirm($tip, window.wxc.xcConfirm.typeEnum.error,{
                onOk:function (){
                     window.location.href="./index.php?r=teacher/choiceLst";  
                }
            });
        }
    var result = <?php  if(isset($result)) echo "'$result'"; else echo'1';?>;
    if(result === '1')
        window.wxc.xcConfirm('复制成功！', window.wxc.xcConfirm.typeEnum.success,{
                onOk:function (){
                     window.location.href="./index.php?r=teacher/choiceLst";  
                }
            });
    else if(result === '0')
        window.wxc.xcConfirm('复制失败！', window.wxc.xcConfirm.typeEnum.error,{
                onOk:function (){
                     window.location.href="./index.php?r=teacher/choiceLst";  
                }
            });
    result = "";
    });
    function check_all(obj, cName)
    {
        var checkboxs = document.getElementsByName(cName);
        for (var i = 0; i < checkboxs.length; i++) {
            checkboxs[i].checked = obj.checked;
        }
    }
    function copyCheck() {
        var checkboxs = document.getElementsByName('checkbox[]');
        var flag = 0;
            for (var i = 0; i < checkboxs.length; i++) {
               if(checkboxs[i].checked){
                    flag=1;
                    break;
               }
            } 
            if(flag===0){
               window.wxc.xcConfirm('未选中任何题目', window.wxc.xcConfirm.typeEnum.info);
            }else{
                 var option = {
                        title: "警告",
                        btn: parseInt("0011",2),
                        onOk: function(){
                                $('#copyForm').submit();
                        }
                };
                window.wxc.xcConfirm("您确定复制题目吗？", "custom", option);
            }
       
    }
   function dele(exerciseID){
     
      var option = {
						title: "警告",
						btn: parseInt("0011",2),
						onOk: function(){
							   window.location.href = "./index.php?r=teacher/deleteChoice&&exerciseID=" + exerciseID;
						}
					}
					window.wxc.xcConfirm("您确定删除吗？", "custom", option);
  }

</script>
