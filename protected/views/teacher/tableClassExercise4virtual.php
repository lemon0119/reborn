<link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
 <script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js"></script>
<script src="<?php echo XC_Confirm; ?>js/xcConfirm.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo JS_URL; ?>jquery.min.js"></script>
<body style="background-image: none;background-color: #fff">
<div style="background-color: #fff">
    <div style="height: 100px !important;">
<table style="width: 100%;position: relative;" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="font-center">选择</th>
                <th class="font-center">类型</th>
                <!--<th class="font-center">科目号</th>-->
                <th class="font-center">标题</th>
                <th class="font-center">内容</th>
            </tr>
        </thead>
                <tbody>   
                <form id="submitForm" method="post" action="./index.php?r=teacher/copyChoice" > 
                     <tr>
                         <td><input type="checkbox" name="all" onclick="check_all(this, 'checkbox[]')" style="margin-bottom: 3px"></td>
                         <td class="font-center">全选</td>
                         <td id="play-classExercise" style="cursor: pointer;height: 30px !important;font-size: 18px ;color: green" colspan="2" class="table_pointer font-center">点击开放练习</td>
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
                        <td class="font-center"><?php  if(Tool::clength($model['title'])<=10)
                                        echo $model['title'];
                                    else
                                       echo Tool::csubstr($model['title'], 0, 10)."...";?></td>
                        <td class="font-center"><?php  if(Tool::clength($model['content'])<=10)
                                        echo $model['content'];
                                   else
                                        echo Tool::csubstr($model['content'], 0,10)."...";
                                        ?></td>
                    </tr>       
                    <?php endforeach;?> 
                </form>
                </tbody>
    </table>
    </div>
</div>
</body>
<script>

    function check_all(obj, cName)
    {
        var checkboxs = document.getElementsByName(cName);
        for (var i = 0; i < checkboxs.length; i++) {
            checkboxs[i].checked = obj.checked;
        }
    }
    
</script>