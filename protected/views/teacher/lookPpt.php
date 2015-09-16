<?php 
    $lessons=Lesson::model()->findall("classID='$classID'");
    foreach ($lessons as $key => $value) {
        $lessonsName[$value['number']]=$value['lessonName'];
    }   
?>

<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
        <li class="nav-header">当前课程</li>
        <li id="li-<?php echo $progress;?>"><a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $progress;?>"><i class="icon-list-alt"></i> <?php echo $lessonsName[$progress];?></a></li>
        <li class="divider"></li>
        <li class="nav-header">其余课程</li>
        <?php foreach($lessonsName as $key => $value):
            if($key!=$progress){
            ?>
            <li id="li-<?php echo $key;?>"><a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $key;?>"><i class="icon-list-alt"></i> <?php echo $value;?></a></li>
            <?php
            } 
            endforeach;?>
        </ul>
    </div>
    <a href="./index.php?r=teacher/pptLst&&classID=<?php echo $classID;?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>" class="btn btn-primary">返回</a>
</div>
<div class="span9">
    <div id="scroll-page" style="display:inline;">
      <button id="page-up" class="btn btn-primary">上页</button>
      <input id="yeshu" style="width:50px;" value="1">
      /<input id="all-yeshu" style="width:50px;" readOnly="true">
      <button id="page-go" class="btn btn-primary">跳转</button>
      <button id="page-down" class="btn btn-primary">下页</button>
    </div>
    <div id="ppt-container" align="center" style="height: 100%; width: 100% ; margin-top:0px">
        <img id="ppt-img" src="" style="width: 100%;"/>
    </div>
</div>
<script>
    
    var cur_ppt     = -1;
    var ppt_pages   = -1;
    var ppt_dir     = null;
    $(document).ready(function(){
        $("#li-<?php echo $on;?>").attr("class","active");
      
            cur_ppt = 1;
            ppt_dir= "<?php echo $dir;?>";
            ppt_pages =<?php   $num = sizeof(scandir(iconv("UTF-8","gb2312",$dir))); 
                                $num = ($num>2)?($num-2):0; 
                                echo $num;?>;
            $("#all-yeshu").val(ppt_pages);
            goCurPage();

        $("#page-up").click(function(){
            if(cur_ppt<=1){
                cur_ppt=1;
                alert("已到第一页！");
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
                alert("请输入合适范围的页数！");
            }
        });
        $("#page-down").click(function(){
            if(cur_ppt>=ppt_pages){
                cur_ppt=ppt_pages;
                alert("已到最后页！");
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

