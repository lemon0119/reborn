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

<h3>已有习题</h3>
    <!-- 键位习题列表-->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>编号</th>
                <th>内容</th>
                <th>创建时间</th>
                <th>分值</th>
                <th>操作</th>               
            </tr>
        </thead>
                <tbody>        
                    <?php foreach($examWork as $work):
                        
                           foreach ($examExercise as $exam)
                          {
                         if($exam['exerciseID'] == $work['exerciseID'])
                             $thisExam = $exam;
                           }
                              
                        
                        ?>
                       
                    <tr>
                        <td style="width: 150px"><?php echo $work['exerciseID'];?></td>
                        <td>
                            <?php  if(strlen($work['requirements'])<=15)
                                        echo $work['requirements'];
                                    else
                                        echo substr($work['requirements'], 0, 15)."...";
                                        ?>
                        </td>
                        <td>
                            <?php  echo $work['createTime']?>
                        </td>      
                        <td>
                            <?php  echo $thisExam['score']?>
                        </td>
                        <td>            
                            <a href="#"  onclick="dele('<?php echo $type?>' ,<?php echo $work['exerciseID'] ?>,<?php echo $exam['examID']; ?>,<?php echo $pages->currentPage+1?>)"><img src="<?php echo IMG_URL; ?>delete.png">删除</a>  
                            <a href="#"  onclick="configScore('<?php echo $type?>' ,<?php echo $work['exerciseID'] ?>,<?php echo $exam['examID']; ?>,<?php echo $pages->currentPage+1?>)"><img src="<?php echo IMG_URL; ?>delete.png">配分</a>
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
        
     parent.setCurrentPage1(<?php echo $pages->currentPage+1?>);
});
    
   
function dele( type ,exerciseID,examID,page){
      if(confirm("您确定删除吗？")){
          window.location.href = "./index.php?r=teacher/deleteExamExercise&&exerciseID=" + exerciseID + "&&type=" + type + "&&examID=" + examID + "&&page=" + page;
      }
  }
  
function configScore(type,exerciseID,examID,page)
{
     var score=prompt("分值","");//将输入的内容赋给变量 name ，
 
    //这里需要注意的是，prompt有两个参数，前面是提示的话，后面是当对话框出来后，在对话框里的默认值
    $("#totalScore",window.parent.document).innerHTML = 100;
    if(score)//如果返回的有内容
 
    {
         window.location.href = "./index.php?r=teacher/configScore&&exerciseID=" + exerciseID + "&&type=" + type + "&&examID=" + examID + "&&page=" + page +"&&score=" + score;
         
     }
    
}
</script>
    