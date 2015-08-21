<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li <?php if($type == "choice") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=choice"><i class="icon-font"></i> 选择</a></li>
                        <li <?php if($type == "filling") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=filling"><i class="icon-text-width"></i> 填空</a></li>
                        <li <?php if($type == "question") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=question"><i class="icon-align-left"></i> 简答</a></li>
                        <li <?php if($type == "key") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=key"><i class="icon-th"></i> 键位练习</a></li>
                        <li <?php if($type == "look") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=look"><i class="icon-eye-open"></i> 看打练习</a></li>
                        <li <?php if($type == "listen") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyWork&&suiteID=<?php echo $suite['suiteID']?>&&type=listen"><i class="icon-headphones"></i> 听打练习</a></li>                           
        </ul>
        
    </div>  
    
     <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <center>
                <table>
                    <tr><td><?php echo $currentClass['className']?></td></tr>
                    <tr><td><?php echo $currentLesson['lessonName']?></td></tr>
                    <tr><td><?php echo $suite['suiteName']?></td></tr>
                </table>                
            </center>                   
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
<div >
    <h3>已有习题</h3>
    <!-- 键位习题列表-->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>编号</th>
                <th>题目</th>
                <th>内容</th>
                <th>创建时间</th>         
                <th>操作</th>               
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($suiteWork as $work):?>
                    <tr>
                        <td style="width: 150px"><?php echo $work['exerciseID'];?></td>
                        <td>
                            <?php  if(strlen($work['title'])<=15)
                                        echo $work['title'];
                                    else
                                        echo substr($work['title'], 0, 15)."...";
                                        ?>
                        </td>
                        <td>
                            <?php  if(strlen($work['content'])<=15)
                                        echo $work['content'];
                                    else
                                        echo substr($work['content'], 0, 15)."...";
                                        ?>
                        </td>
                        <td>
                            <?php  echo $work['createTime']?>
                        </td>           
                        <td>            
                            <a href="#"  onclick="dele('<?php echo $type?>' ,<?php echo $work['exerciseID'] ?>,<?php echo $suite['suiteID'] ?>)"><img src="<?php echo IMG_URL; ?>delete.png">删除</a>                          
                        </td>
                    </tr>            
                    <?php endforeach;?> 
                </tbody>
    </table>
    <!-- 学生列表结束 -->
    <div align=center>
    <?php   
        $this->widget('CLinkPager',array('pages'=>$page1));
    ?>
    </div>
    </div>


<div >
    <h3>自有习题</h3>
    <!-- 键位习题列表-->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>编号</th>
                <th>课程</th>
                <th>题目</th>
                <th>内容</th>
                <th>创建人</th>
                <th>创建时间</th>         
                <th>操作</th>               
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($workLst as $allwork):?>
                    <tr>
                        <td style="width: 150px"><?php echo $allwork['exerciseID'];?></td>
                        <td><?php echo $allwork['courseID'];
                            ?></td>
                        <td> <?php  if(strlen($allwork['title'])<=15)
                                        echo $allwork['title'];
                                    else
                                        echo substr($allwork['title'], 0, 15)."...";
                                        ?>
                        </td>
                         <td> <?php  if(strlen($allwork['content'])<=15)
                                        echo $allwork['content'];
                                    else
                                        echo substr($allwork['content'], 0, 15)."...";
                                        ?>
                        </td>
                        <td>
                            <?php  if($allwork['createPerson']=="0")
                                        echo "管理员";
                                    else echo  $teachers[$allwork['createPerson']];                         
                            ?>
                        </td>     
                        <td>
                            <?php  echo $allwork['createTime']?>
                        </td>     
                        
                        <td>
                            <a href="./index.php?r=teacher/AddWork&&suiteID=<?php echo $suite['suiteID']?>&&type=<?php echo $type?>&&exerciseID=<?php echo $allwork['exerciseID']?>&&code=<?php echo $code?>"><img src="<?php echo IMG_URL; ?>detail.png">添加</a>
                            <a href="./index.php?r=teacher/ModifyEditWork&&suiteID=<?php echo $suite['suiteID']?>&&type=<?php echo $type?>&&exerciseID=<?php echo $allwork['exerciseID']?>"><img src="<?php echo IMG_URL; ?>edit.png">编辑</a>                            
                        </td>
                    </tr>            
                    <?php endforeach;?> 
                </tbody>
    </table>
    <!-- 学生列表结束 -->   
   <div align=center>
    <?php   
        $this->widget('CLinkPager',array('pages'=>$page2));
    ?>
    </div>
    </div>   
</div>

<script>
function dele( type ,exerciseID,suiteID){
      if(confirm("您确定删除吗？")){
          window.location.href = "./index.php?r=teacher/deleteSuiteExercise&&exerciseID=" + exerciseID + "&&type=" + type + "&&suiteID=" + suiteID;
      }
  }
  
$(document).ready(function(){
    var result = <?php echo "'$maniResult'";?>;
    if(result != "")
        alert(result);
});
  
</script>



