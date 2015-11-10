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
                
<h3>题库</h3>
    <!-- 键位习题列表-->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="font-center">编号</th>
                <th class="font-center">科目</th>
                <th class="font-center">题目</th>
                <th class="font-center">内容</th>
                <th class="font-center">创建人</th>
                <th class="font-center">创建时间</th>         
                <th class="font-center">操作</th>               
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($workLst as $allwork):?>
                    <tr>
                        <td class="font-center" style="width: 50px"><?php echo $allwork['exerciseID'];?></td>
                        <td class="font-center"><?php echo $allwork['courseID'];
                            ?></td>
                         <td title="<?php echo $allwork['title'];?>" class="font-center"><?php  if(Tool::clength($allwork['title'])<=5)
                                        echo $allwork['title'];
                                    else
                                        echo Tool::csubstr($allwork['title'], 0, 5)."...";?></td>
                        
                        <td title="<?php echo $allwork['content'];?>" class="font-center"><?php  if(Tool::clength($allwork['content'])<=8)
                                        echo $allwork['content'];
                                    else
                                        echo Tool::csubstr($allwork['content'], 0,8)."...";
                                        ?></td>
                        <td class="font-center">
                            <?php  if($allwork['createPerson']=="0")
                                        echo "管理员";
                                    else echo  $teachers[$allwork['createPerson']];                         
                            ?>
                        </td>     
                        <td class="font-center">
                            <?php  echo $allwork['createTime']?>
                        </td>     
                        
                        <td class="font-center" style="width: 100px">
                            <?php 
                            if($allwork['createPerson'] == Yii::app()->session['userid_now']){ ?>
                            <a target="_parent" href="./index.php?r=teacher/ModifyEditWork&&suiteID=<?php echo $suite['suiteID'];?>&&type=<?php echo $type?>&&action=look&&exerciseID=<?php echo $allwork['exerciseID']?>"><img src="<?php echo IMG_URL; ?>detail.png"></a>
                            <a href="./index.php?r=teacher/AddWork&&suiteID=<?php echo $suite['suiteID']?>&&type=<?php echo $type?>&&exerciseID=<?php echo $allwork['exerciseID']?>&&code=<?php echo $code?>&&page=<?php echo $pages->currentPage+1?>"><img src="<?php echo IMG_URL; ?>icon_add.png" title="添加"></a>
                            <a target="_parent" href="./index.php?r=teacher/ModifyEditWork&&suiteID=<?php echo $suite['suiteID']?>&&type=<?php echo $type?>&&exerciseID=<?php echo $allwork['exerciseID']?>"><img src="<?php echo IMG_URL; ?>edit.png"></a>                            
                            <?php }else{ ?>
                            <a target="_parent" href="./index.php?r=teacher/ModifyEditWork&&suiteID=<?php echo $suite['suiteID'];?>&&type=<?php echo $type?>&&action=look&&exerciseID=<?php echo $allwork['exerciseID']?>"><img src="<?php echo IMG_URL; ?>detail.png"></a>
                            <a href="./index.php?r=teacher/AddWork&&suiteID=<?php echo $suite['suiteID']?>&&type=<?php echo $type?>&&exerciseID=<?php echo $allwork['exerciseID']?>&&code=<?php echo $code?>&&page=<?php echo $pages->currentPage+1?>"><img src="<?php echo IMG_URL; ?>icon_add.png" title="添加"></a>
                            <?php }?>
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
        window.wxc.xcConfirm(result, window.wxc.xcConfirm.typeEnum.info);
        else
            parent.refresh();
    });
   
   function edit(exerciseID)
   {
       window.parent.href = "./index.php?r=teacher/ModifyEditWork&&suiteID=<?php echo $suite['suiteID']?>&&type=<?php echo $type?>&&exerciseID="+exerciseID;
    return fasle;
   }
    
</script>