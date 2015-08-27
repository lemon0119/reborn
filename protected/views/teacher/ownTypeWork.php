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
                            <a href="#"  onclick="dele('<?php echo $type?>' ,<?php echo $work['exerciseID'] ?>,<?php echo $suite['suiteID'] ?>,<?php echo $pages->currentPage+1?>)"><img src="<?php echo IMG_URL; ?>delete.png">删除</a>                          
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
