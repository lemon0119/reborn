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
<!--                <th>编号</th>-->
                <th style="width:150px">题目</th>
                <th>内容</th>
                <th style="width:180px">创建时间</th>         
                <th>操作</th>               
            </tr>
        </thead>
                <tbody>        
                    <?php $n=1;foreach($suiteWork as $work):?>
                    <tr>
<!--                        <td class="font-center" style="width: 50px"><?php //echo $n++;?></td>-->
                       
                         <td title="<?php echo $work['title'];?>" class="font-center"><?php  if(Tool::clength($work['title'])<=8)
                                        echo $work['title'];
                                    else
                                        echo Tool::csubstr($work['title'], 0, 8)."...";?></td>
                        
                        <td title="<?php echo Tool::filterKeyContent($work['content']);?>" class="font-center"><?php  if(Tool::clength(Tool::filterKeyContent($work['content']))<=18)
                                        echo Tool::filterKeyContent($work['content']);
                                    else
                                        echo Tool::csubstr(Tool::filterKeyContent($work['content']), 0,18)."...";
                                        ?></td>
                        <td class="font-center">
                            <?php  echo $work['create_time']?>
                        </td>           
                        <td class="font-center" style="width: 50px">            
                            <a href="#"  onclick="dele(<?php echo $work['exerciseID'] ?>)"><img src="<?php echo IMG_URL; ?>delete.png"></a>                          
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
    
    
    
   
 function dele(exerciseID){
     parent.dele(exerciseID);
     
  }
</script>

