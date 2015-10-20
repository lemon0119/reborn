<script src="<?php echo JS_URL; ?>/My97DatePicker"></script>
<div class="span11">
<h2>公告列表</h2>
<!-- 公告列表-->
<table class="table table-bordered table-striped"  style="background: #DDD">
    <thead>
        <tr>
            <th class="font-center">日期</th>
            <th class="font-center">内容</th>
            <th class="font-center">操作</th>
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
            <td>
                <a href="#" onclick="dele('<?php echo $notice['id'];?>')"><img title="删除" src="<?php echo IMG_URL; ?>delete.png"></a>
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
<div class="span11">
<h2>发布公告</h2>
<!-- 发布公告-->
    <div style="margin:0 auto;border:0px solid #000;width:800px;height:100px">
        <textarea  id="notice-textarea" style="width: 80%;color: red;margin-left:auto;margin-right:auto;"oninput="this.style.color='red'" name="content" rows="6" cols="80" onpropertychange="if(this.scrollHeight>80) this.style.posHeight=this.scrollHeight+5"></textarea><br/>
        <button id="postnotice" style="margin-left: 280px;font-size: 20px">发布</button>
    </div>
</div>
<script>
$(document).ready(function(){
    var current_date = new Date();
    var current_time = current_date.toLocaleTimeString();

    $("#postnotice").click(function() {
        var text = $("#notice-textarea").val();
        $.ajax({
            type: "POST",
            url: "index.php?r=api/putNotice",
            data: {notice: '"' + text + '"'},
            success: function(){   
               
            window.wxc.xcConfirm('公告发布成功！', window.wxc.xcConfirm.typeEnum.success);
             window.location.reload();
            },
            error: function(xhr, type, exception){
                window.wxc.xcConfirm('出错了...', window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr.responseText, "Failed");
            }
        });
    });
});
function dele(noticeId)
    {
        var option = {
                title: "警告",
                btn: parseInt("0011",2),
                onOk: function(){
                         window.location.href = "./index.php?r=admin/deleteNotice&&id=" + noticeId ;
                }
        }
        window.wxc.xcConfirm("您确定删除吗？", "custom", option);
    }
</script>

