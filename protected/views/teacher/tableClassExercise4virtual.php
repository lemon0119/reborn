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
                <tbody>   
                     <tr>
                         <td><input type="checkbox" name="all" onclick="check_all(this, 'checkbox[]')" style="margin-bottom: 3px"></td>
                         <td class="font-center">全选</td>
                         <td id="play-classExercise" style="cursor: pointer;height: 30px !important;font-size: 18px ;color: green" colspan="3" class="table_pointer font-center">点击开放选中练习</td>
                    </tr> 
                    <?php foreach($classExerciseLst as $model):?>
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
                        <td><button id="startClassExercise" class="btn btn-primary" onclick="startClassExercise(<?php echo $model['exerciseID'];?>)" >开始</a></td>
                    </tr> 
                    <?php endforeach;?> 
                </tbody>
    </table>
    </div>
</div>
</body>
<script>
    
    function startClassExercise(exerciseID){
        window.parent.startNow(exerciseID);
    }
    
    function check_all(obj, cName)
    {
        var checkboxs = document.getElementsByName(cName);
        for (var i = 0; i < checkboxs.length; i++) {
            checkboxs[i].checked = obj.checked;
        }
    }
    
    $('#play-classExercise').click(function(){
        var checkboxs = document.getElementsByName('checkbox[]');
        var check ="";
        for (var i = 0; i < checkboxs.length; i++) {
            if(checkboxs[i].checked){
                check+=checkboxs[i].value+"&";
            }
        }
        $.ajax({
               type:"POST",
               url:"index.php?r=teacher/openClassExercise4lot",
               data:{check:check},
               success:function(data){
                   alert(data);
               },
               error:function(xhr, type, exception){
                   console.log('GetAverageSpeed error', type);
                   console.log(xhr, "Failed");
                   console.log(exception, "exception");
                   
               }
        });
    });
</script>