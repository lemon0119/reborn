<link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
<script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js"></script>
<script src="<?php echo XC_Confirm; ?>js/xcConfirm.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo JS_URL; ?>jquery.min.js"></script>
<body style="background-image: none;background-color: #fff">
    <div style="background-color: #fff">
        <div style="height: 100px !important;">
            <table style="width: 100%;position: relative; font-size: 15px" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="font-center">选择</th>
                        <th class="font-center">类型</th>
                        <!--<th class="font-center">科目号</th>-->
                        <th class="font-center">标题</th>
                        <th class="font-center">速度</th>
                        <th class="font-center">等级</th>
                        <th class="font-center">内容</th>
                        <th class="font-center">现在开始</th>
                    </tr>
                </thead>
                
                <tbody>   
                    <?php $mark = 0;
                    foreach ($classExerciseLst as $model): ?>
                        <tr>
                            <td class="font-center" style="width: 30px"> <input type="checkbox" name="checkbox[]" value="<?php echo $model['exerciseID']; ?>" > </td>
                            <td class="font-center" style="width: 75px"><?php
                                switch ($model['type']) {
                                    case 'look': echo '看打练习';
                                        break;
                                    case 'listen': echo '听打练习';
                                        break;
                                    case 'speed': echo '速度练习';
                                        break;
                                    case 'correct': echo '准确率练习';
                                        break;
                                    case 'free': echo '自由练习';
                                        break;
                                }
                                ?></td>
                            <td style="width: 100px" class="font-center" title="<?php echo $model['title']; ?>"><?php
                            if (Tool::clength($model['title']) <= 6)
                                echo $model['title'];
                            else
                                echo Tool::csubstr($model['title'], 0, 6) . "...";
                            ?></td>
                             <td class="font-center" style="width: 35px">
                                 <?php if($model['speed']==0){
                                     echo '-';
                                 }else{
                                 echo $model['speed']; 
                                 }?>
                             </td>
                             <td class="font-center" style="width: 105px">
                                 <?php if($model['now_open']==0){
                                     echo '-';
                                 }else{
                                   $exerciseLevel = ExerciseLevel::model()->findAll("exerciseID=? and lessonID=? and classID=?", array($model['exerciseID'], $model['lessonID'], $model['classID']));
                                   foreach ($exerciseLevel as $level) {
                                       if($level['level'] == ''){
                                           echo '初级 中级 高级';
                                       }else{
                                       echo $level['level'].' ';
                                       }
                                   }
                                 }?>
                             </td>
                            <td class="font-center" style="width: 120px" title="<?php echo Tool::filterKeyContent($model['content']); ?>"><?php
                            if (Tool::clength($model['content']) <= 7)
                                echo Tool::filterKeyContent($model['content']);
                            else
                                echo Tool::csubstr(Tool::filterKeyContent($model['content']), 0, 7) . "...";
                            ?></td>
                            <td><button id="startClassExercise" style="width: 68px" <?php if ($model['now_open'] == 1) {
                                if ($mark === 0) $mark = $model['exerciseID'] ?> class='btn' disabled='disabled' <?php }else { ?> class='btn btn-primary'<?php } ?>   onclick="startClassExercise(<?php echo $model['exerciseID']; ?>)" >开始</button></td>
                        </tr> 
<?php endforeach; ?> 
                </tbody>
            </table>
        </div>
    </div>
</body>
<script>

    function startClassExercise(exerciseID) {
        window.open("./index.php?r=teacher/selectLevel&&exerciseID="+exerciseID+"&&classID=<?php echo $classID;?>&&lessonID=<?php echo $lessonID;?>", 'newwindow', 'height=400,width=400,top=0,left=0,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no,left=500,top=200,');
        window.parent.exitNowOn();
        window.parent.startNow(exerciseID);
    }

    function check_all(obj)
    {
        var checkboxs = document.getElementsByName('checkbox[]');
        for (var i = 0; i < checkboxs.length; i++) {
            checkboxs[i].checked = obj.checked;
        }
    }

    function lookAnalysis() {
        window.parent.exitNowOn();
        if (<?php echo $mark; ?> === 0) {
            window.parent.alertError("没有已开放的练习");
        } else {
            window.parent.startClassExercise(<?php echo $mark; ?>);
        }
    }

    function checkBoxStartExercise() {
        var checkboxs = document.getElementsByName('checkbox[]');
        window.parent.exitNowOn();
        window.parent.startNow4Lot(checkboxs);
    }

</script>