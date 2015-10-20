<script src="<?php echo JS_URL; ?>/My97DatePicker"></script>
<div class="span11">
<h2>公告列表</h2>
<!-- 公告列表-->
<table class="table table-bordered table-striped"  style="background: #DDD">
    <thead>
        <tr>
            <th class="font-center">日期</th>
            <th class="font-center">内容</th>  
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
                 <?php echo $notice['content'];?>
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