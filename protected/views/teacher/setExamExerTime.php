
<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">                     
            <li class="nav-header">班级列表</li>
            <?php foreach ($array_class as $class): ?>
                <li <?php if (Yii::app()->session['currentClass'] == $class['classID']) echo "class='active'"; ?> ><a href="./index.php?r=teacher/assignExam&&classID=<?php echo $class['classID']; ?>"><i class="icon-list"></i><?php echo $class['className']; ?></a></li>
            <?php endforeach;
            $host = Yii::app()->request->hostInfo;
            $path = Yii::app()->request->baseUrl;
            $page = '/index.php?r=teacher/saveTimeAll';
            $param = "&&examID=$examID";
            ?>  
            <form id="myForm" action="./index.php?r=teacher/AddExam" method="post" >  
                <li class="divider"></li>
                <li class="nav-header">试卷标题</li>
                <li style="margin-top:10px">
                    <input name= "title" id="title" type="text" class="search-query span2"  placeholder="试卷标题" value="" />
                </li>
                <li style="margin-top:10px">
                <button type="submit" class="btn btn-primary">创建试卷</button>
                </li>
            </form>
        </ul>
    </div>
</div>

<div class="span9" style="max-height: 720px">
    <form id="timeForm" method="post" action="<?php echo $host.$path.$page.$param;?>">
    <div style="float:left"><h2>试卷配置</h2></div>
    <div style="margin-left:300px;position: relative;top: 25px">总分：<span id="score">0</span></div>
    <div></div>
<!--    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="font-center">题型</th> 
                <th class="font-center">题目数量</th>
                <th class="font-center">配置分数</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php 
                    if(isset($examExer['choice'])){               
                ?>
                <td class="font-center">选择题</td>
                <td class="font-center" id="cNum"><?php echo (isset($examExer['choice'])) ? count($examExer['choice']):0;?></td>
                <td class="font-center"><input id="cScore" name= "choiceScore" type="text" class="input-small input_test input_score" value="<?php echo $choiceScore;?>"/>&nbsp;分</td>
                <?php }?>
            </tr>
            <tr>
                <?php 
                    if(isset($examExer['filling'])){               
                ?>
                <td class="font-center">填空题</td>
                <td class="font-center" id="fNum"><?php echo (isset($examExer['filling'])) ? count($examExer['filling']):0;?></td>
                <td class="font-center"><input id="fScore" name= "fillScore" type="text" class="input-small input_test input_score" value="<?php echo $fillingScore;?>"/>&nbsp;分</td>
                <?php }?>
            </tr>
            <tr>
                <?php 
                    if(isset($examExer['question'])){               
                ?>
                <td class="font-center">简答题</td>
                <td class="font-center" id="qNum"><?php echo (isset($examExer['question'])) ? count($examExer['question']):0;?></td>
                <td class="font-center"><input id="qScore" name= "questScore" type="text" class="input-small input_test input_score" value="<?php echo $questScore;?>"/>&nbsp;分</td>
                <?php }?>
            </tr>
        </tbody>
    </table>-->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="font-center">题型</th>
                <th class="font-center">配置分数</th>
<!--                <th class="font-center">配置时间</th>-->
            </tr>
        </thead>
        <tbody>
            <?php 
            if(isset($examExer['key'])){
                foreach ($examExer['key'] as $key):                
            ?>
            <tr>
                <td class="font-center">键打练习：<?php echo$key['title'];?></td>
                <td class="font-center"><input name= "key<?php echo$key['exerciseID'];?>Score" type="text" class="input-small input_test input_score types" value="<?php echo $key['score']?>"/>&nbsp;分</td>
        <input name= "key<?php echo$key['exerciseID'];?>Time" type="text" style="display: none" class="input-small input_test" value="<?php echo $key['time']?>"/>
<!--                <td class="font-center">&nbsp;分钟</td>-->
            </tr>
            <?php endforeach;}?>
            <?php 
            if(isset($examExer['look'])){
                foreach ($examExer['look'] as $look):                
            ?>
            <tr>
                <td class="font-center">看打练习：<?php echo$look['title'];?></td>
                <td class="font-center"><input name= "look<?php echo$look['exerciseID'];?>Score" type="text" class="input-small input_test input_score types" value="<?php echo $look['score']?>"/>&nbsp;分</td>
            <input name= "look<?php echo$look['exerciseID'];?>Time" type="text" class="input-small input_test" style="display: none" value="<?php echo $look['time']?>"/>
<!--                <td class="font-center">&nbsp;分钟</td>-->
            </tr>
            <?php endforeach;}?>
            <?php 
            if(isset($examExer['listen'])){
                //for($i = 0; $i < 10; $i++):
                foreach ($examExer['listen'] as $listen):                
            ?>
            <tr>
                <td class="font-center">听打练习：<?php echo$listen['title'];?></td>
                <td class="font-center"><input name= "listen<?php echo$listen['exerciseID'];?>Score" type="text" class="input-small input_test input_score types" value="<?php echo $listen['score']?>"/>&nbsp;分</td>
            <input name= "listen<?php echo$listen['exerciseID'];?>Time" type="text" style="display: none" class="input-small input_test"  value="<?php echo $listen['time']?>"/>
<!--                <td class="font-center">&nbsp;分钟</td>-->
            </tr>
            <?php endforeach; //endfor;
            }?>
        </tbody>
    </table>
    <table class="table table-bordered table-striped"<?php if($flag==1){ echo "style='display: none'";}?>>
        <thead>
            <tr>
                <th class="font-center">请输入预定考试时长</th>
                <td class="font-center"><input  type="text" class="input-small input_test" id="examTime" />&nbsp;分钟</td>
<!--                <th class="font-center">配置时间</th>-->
            </tr>
        </thead>
    </table>
    
    <center>
        <input type="button" class="btn btn-primary" onclick="savetime()" value="保存"/>
        <a href="./index.php?r=teacher/assignExam" class="btn btn-primary" style="margin-left: 50px">返回</a>
    </center>
    </form>
</div>
<script>
function savetime(){
    //$('#timeForm').submit();
    $.post($('#timeForm').attr('action'),$('#timeForm').serialize(),function(data){
//            window.wxc.xcConfirm(data, window.wxc.xcConfirm.typeEnum.info);
    var v=document.getElementById("examTime").value;
    if(v!=""&&<?php echo $flag;?>==0){
//        window.location.href = "./index.php?r=teacher/ChangeExamClass&&examID=<?php //echo $examID ?>&&duration="+v+"&&beginTime=<?php //echo $beginTime ?>&&isOpen=0&&page=" +<?php //echo $pages->currentPage + 1; ?>;
    begin_now();
    }else if(<?php echo $flag;?>==1){
        window.location.href = "./index.php?r=teacher/ChangeExamClass&&examID=<?php echo $examID ?>&&duration=<?php echo $duration; ?>&&beginTime=<?php echo $beginTime ?>&&isOpen=0&&page=" +<?php echo $pages->currentPage + 1; ?>+"&&flag=1";
    }else{
        window.wxc.xcConfirm("请输入预定考试时长!", window.wxc.xcConfirm.typeEnum.info);
    }

    })
        .error(function(){window.wxc.xcConfirm('不好意思，保存出错了...', window.wxc.xcConfirm.typeEnum.info);});
}


$(document).ready(function(){
    allScore();
    function allScore(){
        var totalScore = 0;
        var num = $('#cNum').html();
        var sc = ($('#cScore').val() === '')?0:$('#cScore').val();
        var intscore = (isNaN(parseInt(sc)))?0:parseInt(sc);
        var intNum = (isNaN(parseInt(num)))?0:parseInt(num);
        totalScore += intNum * intscore;

        num = $('#fNum').html();
        sc = ($('#fScore').val() === '')?0:$('#fScore').val();
        intscore = (isNaN(parseInt(sc)))?0:parseInt(sc);
        intNum = (isNaN(parseInt(num)))?0:parseInt(num);
        totalScore += intNum * intscore;

        num = $('#qNum').html();
        sc = ($('#qScore').val() === '')?0:$('#qScore').val();
        intscore = (isNaN(parseInt(sc)))?0:parseInt(sc);
        intNum = (isNaN(parseInt(num)))?0:parseInt(num);
        totalScore += intNum * intscore;

        $('.types').each(function(){
            sc = ($(this).val() === '')?0:$(this).val();
            intscore = (isNaN(parseInt(sc)))?0:parseInt(sc);
            totalScore += intscore;
        })
        $('#score').html(String(totalScore));
    }
    $('.input_test').bind({
        blur:function(){
            var score = parseInt(this.value);
            if (this.value !== String(score) && this.value !== "" || this.value<0){
					window.wxc.xcConfirm('输入内容必须是正整数！', window.wxc.xcConfirm.typeEnum.info);
                                        this.value=0;
            }
        }
    });
    $('.input_score').bind({
        blur:function(){
            allScore();
        }
    });
});

    function begin_now()
    {
        var v=document.getElementById("examTime").value;
                if (!v.match(/^[0-9]+$/) || v == 0||v>720) {
                    window.wxc.xcConfirm('非法时长！不得超出720分钟且不能为0！', window.wxc.xcConfirm.typeEnum.error);
                    document.getElementById("examTime").value="";
                } else {
                    window.wxc.xcConfirm("你确定要立即开始？", window.wxc.xcConfirm.typeEnum.info, {
                        onOk: function () {
                            $.ajax({
                                type: "POST",
                                url: "index.php?r=api/putNotice2&&class=<?php echo Yii::app()->session['currentClass'] ?>",
                                data: {title: "考试", content: "考试时间已经到了，可以开始考试了"},
                                success: function () {
                                            window.location.href = "./index.php?r=teacher/ChangeExamClass&&examID=<?php echo $examID ?>&&duration="+v+"&&beginTime=<?php echo date("Y-m-d H:i:s", time()); ?>&&isOpen=0&&page=" +<?php echo $pages->currentPage + 1; ?>;
 //                                    window.location.href = "./index.php?r=teacher/ChangeExamClass&&examID=" + examID + "&&duration=" + v + "&&beginTime=" + begin + "&&isOpen=0&&page=" +<?php// echo $pages->currentPage + 1; ?>;
                                },
                                error: function (xhr, type, exception) {
                                    window.wxc.xcConfirm('出错了a...', window.wxc.xcConfirm.typeEnum.error);
                                    console.log(xhr.responseText, "Failed");
                                }
                            });

                        }
                    });
                }
    }
</script>