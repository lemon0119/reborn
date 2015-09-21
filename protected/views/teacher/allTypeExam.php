  		<meta charset="utf-8">
                <link rel="stylesheet" type="text/css" href="/reborn/assets/afd5bfab/pager.css"/>
		<title>亚伟速录</title>               
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo CSS_URL; ?>bootstrap-responsive.min.css" rel="stylesheet">
		<link href="<?php echo CSS_URL; ?>site.css" rel="stylesheet">
                <script src="<?php echo JS_URL;?>jquery.min.js"></script>
                <script src="<?php echo JS_URL;?>bootstrap.min.js"></script>
                <script src="<?php echo JS_URL;?>site.js"></script>  
<style>
                    body{
                        background:#fff;
                    }
                </style>
    <?php
        //得到老师ID对应的名称
        foreach ($teacher as $model):
        $teacherID=$model['userID'];
        $teachers["$teacherID"]=$model['userName'];
        endforeach;
        $code = mt_rand(0, 1000000);
    ?>                
                
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
                            <a href="./index.php?r=teacher/AddExamExercise&&examID=<?php echo $exam['examID']?>&&type=<?php echo $type?>&&exerciseID=<?php echo $allwork['exerciseID']?>&&code=<?php echo $code?>&&page=<?php echo $pages->currentPage+1?>"><img src="<?php echo IMG_URL; ?>detail.png">添加</a>
                            <a target="_parent" href="./index.php?r=teacher/ModifyEditWork&&type=<?php echo $type?>&&exerciseID=<?php echo $allwork['exerciseID']?>"><img src="<?php echo IMG_URL; ?>edit.png"></a>                            
                        </td>
                    </tr>            
                    <?php endforeach;?> 
                </tbody>
    </table>
    <!-- 学生列表结束 -->   
   <div align=center>
    <?php   
        $this->widget('CLinkPager',array('pages'=>$pages));
    ?>
    </div>

<script>
    $(document).ready(function(){
        var result = <?php echo "'$maniResult'";?>;
        if(result != "")
            alert(result);
        else
            parent.refresh();
    });  
</script>