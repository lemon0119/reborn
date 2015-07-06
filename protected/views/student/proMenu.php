<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    $type = Yii::app()->session['type'];
?>
<script>
    $(document).ready(function(){
        $("a").click(function(){
            var url = $(this).attr("href");
            //$(this).attr("href","#");
            if(url.indexOf("index.php") > 0){
                $("#cont").load(url);
                return false;//阻止链接跳转
            }
        });
    });
</script>
<div class="navbar">
    <div class="navbar-inner">
        <ul class="nav">
                <li <?php if($type == 'classwork') echo 'class="active"'?>>
                        <a href="./index.php?r=student/progress&&type=classwork">课堂作业进度 </a>
                </li>
                <li <?php if($type == 'exercise') echo 'class="active"'?>>
                        <a href="./index.php?r=student/progress&&type=exercise">自我练习进度</a>
                </li>
        </ul>
    </div>
</div>