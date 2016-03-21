<link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
 <script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js"></script>
<script src="<?php echo XC_Confirm; ?>js/xcConfirm.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo JS_URL; ?>jquery.min.js"></script>
<body style="background-image: none;background-color: #fff">
<div style="background-color: #fff">
    <div style="height: 100px !important;">
<table style="width: 98%;position: relative;" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="font-center">选择</th>
                <th class="font-center">类型</th>
                <!--<th class="font-center">科目号</th>-->
                <th class="font-center">标题</th>
                <th class="font-center">内容</th>
                <th class="font-center">现在开始</th>
            </tr>
        </thead>
        <input type="checkbox" name="all" onclick="check_all(this, 'checkbox[]')" style="position: relative;left: 10px;top: 12px" /><span style="position: relative;left: 15px;top:15px;font-size: 20px">全选</span>
        <button style="margin-bottom: 3px;margin-left: 20px" class="btn btn-primary" id="play-classExercise">批量开放</button>
        <button style="margin-bottom: 3px;margin-right: 20px" class="fr btn btn-primary" id="look-Analysis">学生信息反馈</button>
                <tbody>   
                    <?php $mark=0; foreach($classExerciseLst as $model):?>
                    <tr>
                        <td class="font-center" style="width: 50px"> <input type="checkbox" name="checkbox[]" value="<?php echo $model['exerciseID'];?>" > </td>
                        <td class="font-center" style="width: 100px"><?php switch ($model['type']){
                            case 'look': echo '看打练习'; break;
                            case 'listen': echo '听打练习'; break;
                            case 'speed': echo '速度练习'; break;
                            case 'correct': echo '准确率练习'; break;
                            case 'free': echo '自由练习'; break;
                        }?></td>
                        <td class="font-center" title="<?php echo $model['title'];?>"><?php  if(Tool::clength($model['title'])<=10)
                                        echo $model['title'];
                                    else
                                       echo Tool::csubstr($model['title'], 0, 10)."...";?></td>
                        <td class="font-center" title="<?php echo Tool::filterKeyContent($model['content']);?>"><?php  if(Tool::clength($model['content'])<=10)
                                        echo Tool::filterKeyContent($model['content']);
                                   else
                                        echo Tool::csubstr(Tool::filterKeyContent($model['content']),0,12)."...";
                                        ?></td>
                        <td><button id="startClassExercise" <?php if($model['now_open']==1){if($mark===0)$mark = $model['exerciseID']?> class='btn' disabled='disabled' <?php }else{ ?> class='btn btn-primary'<?php }?>   onclick="startClassExercise(<?php echo $model['exerciseID'];?>)" >开始</button></td>
                    </tr> 
                    <?php endforeach;?> 
                </tbody>
    </table>
    </div>
</div>
</body>
<script>
    
    function startClassExercise(exerciseID){
        window.parent.exitNowOn();
        window.parent.startNow(exerciseID);
    }
    
    function check_all(obj, cName)
    {
        var checkboxs = document.getElementsByName(cName);
        for (var i = 0; i < checkboxs.length; i++) {
            checkboxs[i].checked = obj.checked;
        }
    }
    $('#look-Analysis').click(function(){
        window.parent.exitNowOn();
        if(<?php echo $mark;?>===0){
          window.parent.alertError("没有已开放的练习");
        }else{
          window.parent.startClassExercise(<?php echo $mark;?>);  
        }
    });
    
    $('#play-classExercise').click(function(){
        var checkboxs = document.getElementsByName('checkbox[]');
        window.parent.exitNowOn();
        window.parent.startNow4Lot(checkboxs);
    });
</script>