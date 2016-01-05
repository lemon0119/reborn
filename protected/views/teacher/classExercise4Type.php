<div class="span3">
        <div class="well" style="padding: 8px 0;">
            <ul class="nav nav-list">
                        <li style="margin-top:10px">
                                <?php if(isset($_GET['nobar'])){ ?>
                            <button onclick="window.close()" style="height: 35px;top: 1px;left: 10px" class="btn_ret_admin"></button>
                            <button  style="height: 35px;right: 5px" onclick="location.href='./index.php?r=teacher/addKey4ClassExercise&&nobar=yes&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>'" class="btn_add fr"></button>
                            <?php }else{ ?>
                                <button onclick="window.location.href = './index.php?r=teacher/startCourse&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>'" style="height: 35px;top: 1px;left: 10px" class="btn_ret_admin"></button>
                                <button  style="height: 35px;right: 5px" onclick="location.href='./index.php?r=teacher/addKey4ClassExercise&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>'" class="btn_add fr"></button>
                            <?php }?>
                                
                        </li>
                </form>
                        <li class="divider"></li>
                        <li class="nav-header"><i class="icon-typing"></i>课堂练习</li>
                        <?php if(isset($_GET['nobar'])){ ?>
                                  <li class="active"><a href="./index.php?r=teacher/classExercise4Type&&nobar=yes&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-th"></i> 键打练习</a></li>
                                <li ><a href="./index.php?r=teacher/classExercise4Look&&nobar=yes&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-eye-open"></i> 看打练习</a></li>
                                <li ><a href="./index.php?r=teacher/classExercise4Listen&&nobar=yes&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-headphones"></i> 听打练习</a></li>
                            <?php }else{ ?>
                                  <li class="active"><a href="./index.php?r=teacher/classExercise4Type&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-th"></i> 键打练习</a></li>
                        <li ><a href="./index.php?r=teacher/classExercise4Look&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-eye-open"></i> 看打练习</a></li>
                        <li ><a href="./index.php?r=teacher/classExercise4Listen&&classID=<?php echo $_GET['classID'];?>&&progress=<?php echo $_GET['progress'];?>&&on=<?php echo $_GET['on'];?>"><i class="icon-headphones"></i> 听打练习</a></li>
                            <?php }?>
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
    <h2>键打练习列表</h2>
    <!-- 键位习题列表-->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="font-center">编号</th>
                
                <th class="font-center">题目</th>
                <th class="font-center">内容</th>
                <th class="font-center">创建人</th>
                <th class="font-center">创建时间</th>
                <?php if(isset($_GET['nobar'])){ ?>
                            <?php }else{ ?>
                 <th class="font-center">操作</th>
                            <?php }?>
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($keyLst as $model):?>
                    <tr>
                        <td class="font-center" style="width: 50px"><?php echo $model['exerciseID'];?></td>
                      
                        <td class="font-center"><?php  if(Tool::clength($model['title'])<=7)
                                        echo $model['title'];
                                    else
                                        echo Tool::csubstr($model['title'], 0, 7)."...";?></td>
                        <td class="font-center"><?php  if(Tool::clength($model['content'])<=10)
                                        echo $model['content'];
                                   else
                                        echo Tool::csubstr($model['content'], 0,10)."...";
                                        ?></td>
                        <td class="font-center"><?php  echo  $teachers[$model['create_person']];
                            ?></td>
                        <td class="font-center"><?php echo $model['create_time'];?></td>
                        <?php if(isset($_GET['nobar'])){ ?>
                            <?php }else{ ?>
                  <td class="font-center" style="width: 100px">
                             <a href="./index.php?r=teacher/editListen&&exerciseID=<?php echo $model['exerciseID'];?>&&action=look"><img title="查看" src="<?php echo IMG_URL; ?>detail.png"></a>
                            <a href="./index.php?r=teacher/editListen&&exerciseID=<?php echo $model['exerciseID'];?>"><img title="编辑" src="<?php echo IMG_URL; ?>edit.png"></a>
                            <a href="#"  onclick="dele(<?php echo $model['exerciseID'];?>)"><img title="删除" src="<?php echo IMG_URL; ?>delete.png"></a>
                        </td>
                            <?php }?>
                       
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
}      
);
   function dele(exerciseID){
     
      var option = {
						title: "警告",
						btn: parseInt("0011",2),
						onOk: function(){
							 window.location.href = "./index.php?r=teacher/deleteListen&&exerciseID=" + exerciseID;
						}
					}
					window.wxc.xcConfirm("您确定删除吗？", "custom", option);
  }

</script>