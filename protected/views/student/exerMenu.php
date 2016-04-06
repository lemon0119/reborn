<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
    $host = Yii::app()->request->hostInfo;
    $path = Yii::app()->request->baseUrl;
?>
<script src="<?php echo JS_URL;?>jquery-form.js"></script>
<script src="<?php echo EXER_JS_URL;?>jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $("div.span3").find("a").click(function(){
            var url = $(this).attr("href");
            //$(this).attr("href","#");
            if(url.indexOf("index.php") > 0){
                $("#cont").load(url);
                return false;//阻止链接跳转
            }
        });
    });
    
    function formSubmit(){
        var options = {   target:'#cont'};
        $("#suite_form_id").ajaxSubmit(options);
    }
    
    
</script>
<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li class="nav-header">选择习题</li>
            <li><br/></li>
            <?php $model = new Suite();?>
            <?php 
                $course_now = Yii::app()->session['courseID'];
                $suiteList = (new Course())->getExerciseListByLessonID($course_now);
            ?>
            <?php $page = '/index.php?r=student/Exer';?>
            <form name='suite_form' id='suite_form_id' method="post" action="<?php echo $host.$path.$page;?>">
            <select name= "suite" id = "suite"onchange ="formSubmit();">
                <option value=''>--请选择--</option>
                <?php
                    $suiteID = Yii::app()->session["suiteID"];
                    foreach ($suiteList as $key => $value) {
                        echo "<option value='$key'";
                        if($suiteID == $key)
                            echo "selected=\"selected\"";
                        echo ">$value</option>";
                }?>
            </select>
            </form>
            <li class="nav-header">选择题型</li>
            <?php 
                $exerType = '';
                if(isset(Yii::app()->session['exerType'])) 
                    $exerType = Yii::app()->session['exerType'];
                ?>
            <li <?php if($exerType == 'listenExer') echo 'class="active"';?>>
                <?php $param = '&exerType='.'listenExer'?>
                <a href="<?php echo $host.$path.$page.$param;?>"><i class="icon-white icon-home"></i>听打练习</a>
            </li>
            <li <?php if($exerType == 'lookExer') echo 'class="active"';?>>
                <?php $param = '&exerType='.'lookExer'?>
                <a href="<?php echo $host.$path.$page.$param;?>"><i class="icon-folder-open"></i>看打练习</a>
            </li>
            <li <?php if($exerType == 'keyExer') echo 'class="active"';?>>
                <?php $param = '&exerType='.'keyExer'?>
                <a href="<?php echo $host.$path.$page.$param;?>"><i class="icon-check"></i>键打练习</a>
            </li>
            <li <?php if($exerType == 'knlgExer') echo 'class="active"';?>>
                <?php $param = '&exerType='.'knlgExer'?>
                <a href="<?php echo $host.$path.$page.$param;?>"><i class="icon-envelope"></i>基础知识</a>
            </li>
        </ul>
    </div>
</div>