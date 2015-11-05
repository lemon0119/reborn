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
<h3>已有习题</h3>
    <!-- 键位习题列表-->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>编号</th>
                <th>内容</th>
                <th>创建时间</th>         
                <th>操作</th>               
            </tr>
        </thead>
                <tbody>        
                    <?php $n=1;
                    foreach($suiteWork as $work):?>
                    <tr>
                        <td title="<?php echo $work['exerciseID'];?>" class="font-center" style="width: 50px"><?php echo $n++;?></td>
                        <td title="<?php echo $work['requirements'];?>" class="font-center">
                            <?php  if(Tool::clength($work['requirements'])<=8)
                                        echo $work['requirements'];
                                    else
                                        echo Tool::csubstr($work['requirements'], 0, 8)."...";
                                        ?>
                        </td>
                        <td  class="font-center">
                            <?php  echo $work['createTime']?>
                        </td>           
                        <td class="font-center" style="width: 100px">            
                            <a href="#"  onclick="dele('<?php echo $type?>' ,<?php echo $work['exerciseID'] ?>,<?php echo $suite['suiteID'] ?>,<?php echo $pages->currentPage+1?>)"><img src="<?php echo IMG_URL; ?>delete.png"></a>                          
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
    
   
function dele( type ,exerciseID,suiteID,page){
      if(confirm("您确定删除吗？")){
          window.location.href = "./index.php?r=teacher/deleteSuiteExercise&&exerciseID=" + exerciseID + "&&type=" + type + "&&suiteID=" + suiteID + "&&page=" + page;
      }
  }
</script>
    