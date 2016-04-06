<div class="span3">
        <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
                <form action="./index.php?r=admin/searchQuestion" method="post">
                        <li>
                                <select name="type" >
                                        <option value="exerciseID" selected="selected">编号</option>
                                        <option value="courseID" >科目号</option>
                                        <option value="createPerson" >创建人</option>
                                        <option value="requirements">内容</option>
                                </select>
                        </li>
                        <li>
                                <input name="value" type="text" class="search-query span2" placeholder="Search" />
                        </li>
                        <li style="margin-top:10px">
                                <button type="submit" class="btn_serch"></button>
                                <a href="./index.php?r=admin/addQuestion" class="btn_add"></a>
                        </li>
                </form>
                        <li class="divider"></li>
                        <li class="nav-header"><i class="icon-knowlage"></i>基础知识</li>
                        <li ><a href="./index.php?r=admin/choiceLst"><i class="icon-font"></i> 选择</a></li>
                        <li ><a href="./index.php?r=admin/fillLst"><i class="icon-text-width"></i> 填空</a></li>
                        <li class="active"><a href="./index.php?r=admin/questionLst"><i class="icon-align-left"></i> 简答</a></li>
                        <li class="divider"></li>
                        <li class="nav-header"><i class="icon-typing"></i>打字练习</li>
                        <li ><a href="./index.php?r=admin/keyLst"><i class="icon-th"></i> 键打练习</a></li>
                        <li ><a href="./index.php?r=admin/lookLst"><i class="icon-eye-open"></i> 看打练习</a></li>
                        <li ><a href="./index.php?r=admin/listenLst"><i class="icon-headphones"></i> 听打练习</a></li>
                </ul>
        </div>
</div>


<?php
    //得到老师ID对应的名称
    foreach ($teacher as $model):
    $teacherID=$model['userID'];
    $teachers["$teacherID"]=$model['userName'];
    endforeach;
?>
<div class="span9">
<?php if($questionLst->count()!=0){?>
    <h2>查询结果</h2>
     <input type="checkbox" name="all" onclick="check_all(this, 'checkbox[]')" style="margin-bottom: 3px"> 全选　　批量操作：
    <a href="#" onclick="deleCheck()"><img title="批量删除" src="<?php echo IMG_URL; ?>delete.png"></a>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="font-center">选择</th>
            <th class="font-center">编号</th>
            <th class="font-center">科目号</th>
            <th class="font-center">内容</th>
            <th class="font-center">创建人</th>
            <th class="font-center">创建时间</th>
            <th class="font-center">操作</th>
        </tr>
    </thead>
            <tbody>        
                <form id="deleForm" method="post" action="./index.php?r=admin/deleteQuestion" > 
                <?php foreach($questionLst as $model):?>
                <tr>
                     <td class="font-center" style="width: 50px"> <input type="checkbox" name="checkbox[]" value="<?php echo $model['exerciseID']; ?>" /> </td>
                    <td class="font-center" style="width: 50px"><?php echo $model['exerciseID'];?></td>
                    <td class="font-center"><?php echo $model['courseID'];?></td>
                    <td class="font-center"><?php if($searchKey == 'no' || strpos($model['requirements'],$searchKey)===false)
                    {
                               if(strlen($model['requirements'])<=30)
                                    echo $model['requirements'];
                                else
                                    echo substr($model['requirements'], 0, 30)."...";
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
                    <td class="font-center"><?php if($model['createPerson']=="0")
                                    echo "管理员";
                                else echo  $teachers[$model['createPerson']];
                        ?></td>
                    <td class="font-center"><?php echo $model['createTime'];?></td>
                    <td class="font-center"style="width: 100px" >
                        <a href="./index.php?r=admin/editQuestion&&exerciseID=<?php echo $model['exerciseID'];?>&&action=look"><img src="<?php echo IMG_URL; ?>detail.png"></a>
                        <a href="./index.php?r=admin/editQuestion&&exerciseID=<?php echo $model['exerciseID'];?>"><img src="<?php echo IMG_URL; ?>edit.png"></a>
                   <a href="#"  onclick="dele(<?php echo $model['exerciseID'];?>)"><img src="<?php echo IMG_URL; ?>delete.png"></a>
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
<?php } else {?>
    <h2>查询结果为空！</h2>
<?php }?>
</div>

<script>
            function check_all(obj, cName)
    {
        var checkboxs = document.getElementsByName(cName);
        for (var i = 0; i < checkboxs.length; i++) {
            checkboxs[i].checked = obj.checked;
        }
    }
    
  function dele(exerciseID){
      if(confirm("您确定删除吗？")){
          window.location.href = "./index.php?r=admin/deleteQuestion&&exerciseID=" + exerciseID;
      }
  }
  
     function deleCheck() {
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
							$('#deleForm').submit();
						}
					};
					window.wxc.xcConfirm("确定删除选中的题目吗？", "custom", option);
        }
       
    }
    $(document).ready(function () {
        $("#li-stuLst").attr("class", "active");
    });
</script>
