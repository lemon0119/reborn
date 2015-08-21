<div id="fenye1">
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
                    <?php foreach($suiteWork as $work):?>
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
                            <a href="#"  onclick="dele('<?php echo $type?>' ,<?php echo $work['exerciseID'] ?>,<?php echo $suite['suiteID'] ?>)"><img src="<?php echo IMG_URL; ?>delete.png">删除</a>                          
                        </td>
                    </tr>            
                    <?php endforeach;?> 
                </tbody>
    </table>
    <!-- 学生列表结束 -->
    <div align=center id="yiyou">
    <?php   
        $this->widget('CLinkPager',array('pages'=>$page1));
    ?>
    </div>
    </div>

