
<div style="min-height: 600px" class="span11">
<h2>公告列表</h2>
<!-- 公告列表-->
<table class="table table-bordered table-striped"  style="background: #DDD">
    <thead>
        <tr>
            <th class="font-center">日期</th>
            <th class="font-center">标题</th>  
        </tr>
    </thead>
    <tbody>
        <?php 
               foreach ($noticeRecord as $notice){?>
        <tr>
            <td>
                 <?php echo $notice['noticetime'];?>
            </td>
            <td>
                <a href="./index.php?r=student/noticeContent&&id=<?php echo $notice['id'];?>"><?php echo $notice['noticetitle'];?></a>
            </td>
        </tr>
               <?php }?>
    </tbody>
</table>
<div align=center>
    <?php   
        $this->widget('CLinkPager',array('pages'=>$pages));
    ?>
    </div>
</div>