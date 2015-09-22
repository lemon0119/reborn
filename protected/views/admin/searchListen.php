<div class="span3">
        <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
                <form action="./index.php?r=admin/searchListen" method="post">
                        <li>
                                <select name="type" style="width: 185px">
                                        <option value="exerciseID" selected="selected">编号</option>
                                        <option value="courseID" >课程号</option>
                                        <option value="title" >题目名</option>
                                        <option value="createPerson" >创建人</option>
                                        <option value="content">内容</option>
                                </select>
                        </li>
                        <li>
                                <input name="value" type="text" class="search-query span2" placeholder="Search" />
                        </li>
                        <li style="margin-top:10px">
                                <button type="submit" class="btn_serch"></button>
                                <a href="./index.php?r=admin/addListen" class="btn_add"></a>
                        </li>
                </form>
                        <li class="divider"></li>
                        <li class="nav-header"><i class="icon-knowlage"></i>基础知识</li>
                        <li ><a href="./index.php?r=admin/choiceLst"><i class="icon-font"></i> 选择</a></li>
                        <li ><a href="./index.php?r=admin/fillLst"><i class="icon-text-width"></i> 填空</a></li>
                        <li ><a href="./index.php?r=admin/questionLst"><i class="icon-align-left"></i> 简答</a></li>
                        <li class="divider"></li>
                        <li class="nav-header"><i class="icon-typing"></i>打字练习</li>
                        <li ><a href="./index.php?r=admin/keyLst"><i class="icon-th"></i> 键位练习</a></li>
                        <li ><a href="./index.php?r=admin/lookLst"><i class="icon-eye-open"></i> 看打练习</a></li>
                        <li class="active"><a href="./index.php?r=admin/listenLst"><i class="icon-headphones"></i> 听打练习</a></li>
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
    <h2>听打练习列表</h2>
    <!-- 键位习题列表-->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>编号</th>
                <th>课程号</th>
                <th>题目名</th>
                <th>内容</th>
                <th>创建人</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($listenLst as $model):?>
                    <tr>
                        <td style="width: 50px"><?php echo $model['exerciseID'];?></td>
                        <td><?php echo $model['courseID'];?></td>
                        <td><?php echo $model['title'];?></td>
                        <td><?php if($searchKey == 'no' || strpos($model['content'],$searchKey)===false)
                    {
                              
                               if(strlen($model['content'])<=30)
                                    echo $model['content'];
                                else
                                    echo substr($model['content'], 0, 30)."...";
                    }else
                    {
                        
                        $strStart = strpos($model['content'], $searchKey);
                        $start = $strStart<=9?0:$strStart-9;            
                       $showContent =  str_replace($searchKey, '<font color=red>'.$searchKey.'</font>',substr($model['content'], $start, 30));
                       if($start >= 9)
                           $showContent = "...".$showContent;
                       if(strlen($model['content'])>($start + 30))
                           $showContent = $showContent."...";
                       echo $showContent;
                     }
                                        ?></td>
                        <td><?php if($model['createPerson']=="0")
                                        echo "管理员";
                                    else echo  $teachers[$model['createPerson']];
                            ?></td>
                        <td><?php echo $model['createTime'];?></td>
                        <td>
                            <a href="./index.php?r=admin/editListen&&exerciseID=<?php echo $model['exerciseID'];?>&&action=look"><img src="<?php echo IMG_URL; ?>detail.png">查看</a>
                            <a href="./index.php?r=admin/editlisten&&exerciseID=<?php echo $model['exerciseID'];?>"><img src="<?php echo IMG_URL; ?>edit.png">编辑</a>
                        <a href="#"  onclick="dele(<?php echo $model['exerciseID'];?>)"><img src="<?php echo IMG_URL; ?>delete.png">删除</a>
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
  function dele(exerciseID){
      if(confirm("您确定删除吗？")){
          window.location.href = "./index.php?r=admin/deleteListen&&exerciseID=" + exerciseID;
      }
  }
</script>