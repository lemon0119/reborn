<script src="<?php echo JS_URL; ?>/My97DatePicker"></script>

<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">                     
            <li class="nav-header"><i class="icon-knowlage"></i>班级列表</li>

            <?php foreach ($array_class as $class): ?>
                <li <?php if (Yii::app()->session['currentClass'] == $class['classID']) echo "class='active'"; ?> ><a href="./index.php?r=teacher/assignExam&&classID=<?php echo $class['classID']; ?>"><i class="icon-list"></i><?php echo $class['className']; ?></a></li>
            <?php endforeach; ?>  
             
                <li class="divider"></li>
                <li class="nav-header"><i class="icon-knowlage"></i>试卷标题</li>
                <li style="margin-top:10px">
                    <input name= "title" id="title" type="text" class="search-query span2"  placeholder="试卷标题" value=""/>
                </li>
                <li style="margin-top:10px">
                    <a href="#"onclick="chkIt()" id="bth_create"></a>
                </li>
             
        </ul>
    </div>

</div>

<div class="span9">
    <h2>现有试卷列表</h2>
    <!-- 键位习题列表-->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="font-center">标题</th> 
                <th class="font-center">开始时间</th>
                <th class="font-center">时长</th>    
                <th class="font-center">状态</th> 
                <th class="font-center">操作</th> 
            </tr>
        </thead>
        <tbody>        
            <?php
            foreach ($array_allexam as $exam):
                $isOpen = false;
                foreach ($array_exam as $exitexam)
                    if ($exam['examID'] == $exitexam['examID']) {
                        $isOpen = true;
                        break;
                    }
                ?>                    
                <tr>
                    <td class="font-center" style="width: 100px"><?php echo $exam['examName']; ?></td>                        

                    <td class="font-center">
                        <?php echo $exam['begintime'] ?>
                    </td>
                    <td class="font-center">
                        <?php echo $exam['duration'] . "分钟" ?>
                    </td>
                    <td class="font-center">
                        <?php if ($isOpen == false) { ?>
                        <a href="#"  onclick="openExam(<?php echo $exam['examID']; ?>,<?php echo $exam['duration']?>,'<?php echo $exam['begintime'] ?>')" style="color: green" >发布</a>
                            <font style="color:grey">关闭</font>
                        <?php } else { ?>
                            <font style="color:grey">发布</font>
                            <a href="./index.php?r=teacher/ChangeExamClass&&examID=<?php echo $exam['examID']; ?>&&duration=<?php echo $exam['duration'];?>&&beginTime=<?php echo $exam['begintime']; ?>&&isOpen=1&&page=<?php echo $pages->currentPage + 1; ?>" style="color: red">关闭</a>
                        <?php } ?>  
                    </td>   

                    <td class="font-center" style="width: 210px">
                        <a href="./index.php?r=teacher/modifyExam&&examID=<?php echo $exam['examID']; ?>&&type=choice"><img title="调整试卷" src="<?php echo IMG_URL; ?>edit.png"></a>
                        <a href="#" onclick="dele(<?php echo $exam['examID']; ?>,<?php echo $pages->currentPage + 1; ?>)"><img title="删除试卷" src="<?php echo IMG_URL; ?>delete.png"></a> 
                        <?php if ($isOpen==false) {?>
                            <a href="#" id ="beginnow" onclick="begin_now(<?php echo $exam['examID']; ?>,<?php echo $exam['duration']?>,'<?php echo date("Y-m-d H:i:s",time());  ?>')"></a> 
                        <?php} ?>
                            <a href="./index.php?r=teacher/setTimeAndScoreExam&&examID=<?php echo $exam['examID']; ?>"><img title="配置分数时间" src="<?php echo IMG_URL; ?>../UI_tea/icon_SETUP.png"></a>
                        <?php }?>
                        
                    </td>
                </tr>            
            <?php endforeach; ?> 
        </tbody>
        
    </table>
    <div align=center>
    <?php
    $this->widget('CLinkPager', array('pages' => $pages));
    ?>
</div>
</div>


<script>
    $(document).ready(function(){
        if(<?php echo $res;?>==1){
            var txt=  "此试卷已经被创建！";
	    window.wxc.xcConfirm(txt, window.wxc.xcConfirm.typeEnum.info);
            document.getElementById("title").value="";
        }
    });
    function dele(examID, currentPage)
    {
        var option = {
						title: "警告",
						btn: parseInt("0011",2),
						onOk: function(){
							 window.location.href = "./index.php?r=teacher/deleteExam&&examID=" + examID + "&&page=" + currentPage;
						}
					}
					window.wxc.xcConfirm("您确定删除吗？", "custom", option);
    }

   
function openExam(examID,duration,begintime)
{
     var duration=prompt("时长",duration);//将输入的内容赋给变量 name ，
     if(duration==0){
         alert("时长不能为0！！！");
         duration=prompt("时长",duration);
     }
     if(duration){
        var beginTime=prompt("开始时间",begintime);     
    }
    //这里需要注意的是，prompt有两个参数，前面是提示的话，后面是当对话框出来后，在对话框里的默认值
   if(beginTime)//如果返回的有内容
    {
         window.location.href = "./index.php?r=teacher/ChangeExamClass&&examID="+examID+"&&duration="+duration+"&&beginTime="+beginTime+"&&isOpen=0&&page="+<?php echo $pages->currentPage + 1; ?>;     
     }
}
function begin_now(examID,d,time,isOpen)
{
    var begin=time;
        if(d==0){
            d=prompt("时长不能为0！！！",d);
        }
        if(confirm("你确定要立即开始？")){
            $.ajax({
                type: "POST",
                url: "index.php?r=api/putNotice2&&class=<?php echo Yii::app()->session['currentClass']?>",
                data: {title:  "考试" , content:  "考试时间已经到了，可以开始考试了"},
                success: function(){   
                    window.location.href="./index.php?r=teacher/ChangeExamClass&&examID="+examID+"&&duration="+d+"&&beginTime="+begin+"&&isOpen=0&&page="+<?php echo $pages->currentPage + 1; ?>;
                
                },
                error: function(xhr, type, exception){
                    window.wxc.xcConfirm('出错了a...', window.wxc.xcConfirm.typeEnum.error);
                    console.log(xhr.responseText, "Failed");
                }
            });
            
        }
}
   

function chkIt(){
    var usernameVal = document.getElementById("title").value;  
    if(usernameVal==""){
        window.wxc.xcConfirm("题目不能为空", window.wxc.xcConfirm.typeEnum.warning);
            return false;
    }
    if(usernameVal.length > 30){ //一个汉字算一个字符  
        window.wxc.xcConfirm("大于30个字符", window.wxc.xcConfirm.typeEnum.warning);
        document.getElementById("title").value="";
    }
    window.location.href="./index.php?r=teacher/AddExam&&title="+usernameVal;
}
</script>


