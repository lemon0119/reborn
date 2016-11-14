<?php 
    $lessons=Lesson::model()->findall("classID='$classID'");
    foreach ($lessons as $key => $value) {
        $lessonsName[$value['number']]=$value['lessonName'];
    }   
?>
<?php if($new!=1){?>
<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
        <li class="nav-header">当前科目</li>
        <li id="li-<?php echo $progress;?>"><a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $progress;?>"><i class="icon-list-alt" style="position:relative;bottom:7px;left:"></i> <?php echo $lessonsName[$progress];?></a></li>
        <li class="divider"></li>
        <li class="nav-header">其余科目</li>
        </ul>
        <div class="well-bottomnoradius" style="padding: 8px 0;height:496px;overflow:auto;top: 0px;border-top-left-radius:0px; ">
        <ul class="nav nav-list">
        <?php foreach($lessonsName as $key => $value):
            if($key!=$progress){
            ?>
            <li id="li-<?php echo $key;?>"><a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $key;?>"><i class="icon-list-alt" style="position:relative;bottom:7px;left:"></i> <?php echo $value;?></a></li>
            <?php
            } 
            endforeach;?>
        </ul>
        </div>
    </div>
    <a href="./index.php?r=teacher/pptLst&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>" class="btn btn-primary">返回</a>
</div>
<?php }?>
<div class="span9" style=" height: 574px">
    <div id="scroll-page" style="display:inline;">
      <button id="page-up" class="btn btn-primary">上页</button>
      <input id="yeshu" style="width:50px;" value="1">
      /<input id="all-yeshu" style="width:50px;" readOnly="true">
      <button id="page-go" class="btn btn-primary">跳转</button>
      <button id="page-down" class="btn btn-primary">下页</button>
    </div>
    <div id="ppt-container" align="center" style="height: 100%; width: 100% ; margin-top:0px ; display:none">
        <img id="ppt-img" src="" style="width: 100%;"/>
    </div>
</div>
<script>
    var flag        = 0;
    var cur_ppt     = -1;
    var ppt_pages   = -1;
    var ppt_dir     = null;
    $(document).ready(function(){
        $("#li-<?php echo $on;?>").attr("class","active");    
        cur_ppt = 1;
        ppt_dir= "<?php echo $dir;?>";
        ppt_pages =<?php   if(is_dir(iconv("UTF-8","gb2312",$dir)))
                            {
                                $num = sizeof(scandir(iconv("UTF-8","gb2312",$dir))); 
                                $num = ($num>2)?($num-2):0; 
                                echo $num;
                            }else {
                                echo 0;
                            }?>;
        $("#all-yeshu").val(ppt_pages);
        if(ppt_pages >0){
            goCurPage();
            $("#ppt-container").show();
            flag = 1;
        }
        setInterval(function() {
             $.get("index.php?r=api/getDirFileNums&&dirName=<?php echo iconv("UTF-8","gb2312",$dir);?>",function(data){
                ppt_pages = data - 1 + 1;
                $("#all-yeshu").val(ppt_pages);
                if(ppt_pages >0&&flag==0){
                    goCurPage();
                    $("#ppt-container").show();
                    flag = 1;
                }
              });
        }, 3000);

        $("#page-up").click(function(){
            if(cur_ppt<=1){
                cur_ppt=1;
                window.wxc.xcConfirm("已到第一页！", window.wxc.xcConfirm.typeEnum.info);
            }else{
                cur_ppt = cur_ppt -1;
            }
            goCurPage();
        });
        $("#page-go").click(function(){
            var input_page =$("#yeshu").val();
            input_page =input_page - 1 + 1;
            if((input_page>=1)&&(input_page<=ppt_pages))
            {
                cur_ppt=input_page;
                goCurPage();
            }else{
                window.wxc.xcConfirm("请输入合适范围的页数！", window.wxc.xcConfirm.typeEnum.info);
            }
        });
        $("#page-down").click(function(){
            if(cur_ppt>=ppt_pages){
                cur_ppt=ppt_pages;
                window.wxc.xcConfirm("已到最后页！", window.wxc.xcConfirm.typeEnum.info);
            }else{
                cur_ppt = cur_ppt +1;
            }
            goCurPage();
        });
});

function goCurPage(){
    $("#yeshu").val(cur_ppt);
    $("#ppt-img").attr("src", ppt_dir+"/幻灯片"+cur_ppt+".JPG");
}
    
    
</script>

