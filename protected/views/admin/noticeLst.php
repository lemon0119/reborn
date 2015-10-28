<script src="<?php echo JS_URL; ?>/My97DatePicker"></script>
<div class="span9"
     style="height: 250px;width: 1080px;">
    <center><h2>发布公告</h2></center>
<!-- 发布公告-->
    <div style="margin:0 auto;border:0px solid #000;width:800px;height:100px;text-align: center">
        <input id="notice-input" style="width: 60%;" oninput="this.style.color='red'" name="title" placeholder="标题...."><br/><br/>
        <textarea  id="notice-textarea" placeholder="内容...." style="width: 60%;height: 90px;"name="content"></textarea><br/>
        
        <a id="postnotice" style="text-align: right;margin-left: 360px"></a>
       
    </div>
</div>
<div class="span9" style="margin-top: 25px;width: 1080px;">
    <center><h2>查看公告</h2></center>
<!-- 公告列表-->
<table class="table table-bordered table-striped"  style="background: #DDD">
    <thead>
        <tr>
            <th class="font-center" style="width:200px">日期</th>
            <th class="font-center">标题</th>
            <th class="font-center" style="width:200px">操作</th>
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
                <a href="./index.php?r=admin/noticeContent&&id=<?php echo $notice['id'];?>"><?php echo $notice['noticetitle'];?></a>
            </td>
            <td>
                <a href="./index.php?r=admin/noticeContent&&id=<?php echo $notice['id'];?>&&action=edit"><img title="编辑" src="<?php echo IMG_URL; ?>edit.png"></a>
                <a href="#" onclick="dele('<?php echo $notice['id'];?>')"><img title="删除" src="<?php echo IMG_URL; ?>delete.png"></a>
            </td>
        </tr>
               <?php }?>
    </tbody>
</table>
<div align=right>
    <?php   
        $this->widget('CLinkPager',array('pages'=>$pages));
    ?>
    </div>
</div>
<script>
$(document).ready(function(){
    var current_date = new Date();
    var current_time = current_date.toLocaleTimeString();

    $("#postnotice").click(function() {
        var text1 = $("#notice-input").val();
        var text2 = $("#notice-textarea").val();
        if(text1==""||text2==""){
           window.wxc.xcConfirm("标题或内容不能为空", window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }
         if(text1.length > 40){ 
          window.wxc.xcConfirm("标题过长！！！", window.wxc.xcConfirm.typeEnum.warning);
        document.getElementById("title").value="";
        }
        $.ajax({
            type: "POST",
            url: "index.php?r=api/putNotice",
            data: {title:  text1 , content:  text2},
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

