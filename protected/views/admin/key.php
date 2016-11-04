<div class="span9" style="width: 89.30939226519337%;">
    <h2>一键启用</h2>
    <form action="./index.php?r=admin/key" method="post"
          id="form-key" enctype="multipart/form-data">
        
        <fieldset>
           <input type="hidden" name="<?php echo ini_get("session.upload_progress.name"); ?>" value="test" /> 
            <div class="controls">
                <label class="control-label">请选择Excel文件</label><br/>
                <div class="controls" style="text-align: center;background-color: #efefef;padding:50px;border-radius:5px;">
                    <input type="file" name="file" id="file"/>
                    <span style="position: relative;left: 10px; top:4px">
                 <input type="checkbox" name="checkbox"  value="" style="position: relative;bottom: 4px"/>
               是否使用模板
                </span>
                </div>
            </div>
            <div>
                <br/>
                <input type="submit" class="btn btn-primary" value="添加" style="float:right"/>&nbsp;&nbsp;                    
            </div>
            <div id="progress" class="progress"  style="display:none;height: 60px;margin-left: auto;text-align: center;background: #FFFFFF" >
                <img src="./img/progress.gif" style="width: 50px"/>
</div>
            </fieldset>
    </form>
             <?php if(isset($array_failTea)){ ?>
                           
            <h3>异常添加列表</h3>
            <h4>老师</h4>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="font-center" style="width:20%;">工号</th>
                        <th class="font-center" style="width:20%;">姓名</th>
                        <th class="font-center" style="width:30%;">异常原因</th>
                        <th class="font-center" style="width:30%;">是否自动解决</th>
                    </tr>
                </thead>
                <tbody>
                   <?php foreach($array_failTea as $stu_fail){ ?>
                    <tr>
                        <td><?php echo $stu_fail[1];?></td>
                        <td><?php echo $stu_fail[2];?></td>
                        <td><?php echo $stu_fail[0];?></td>
                        <td <?php if($stu_fail[3]=="需手动添加"){
                        echo 'style="color:red"';}else{echo 'style="color:green"';}
                        ?>><?php echo $stu_fail[3];?></td>
                    </tr>
                    <?php }  ?>
                </tbody>
            </table>
            
             <?php } if(isset($array_fail)){ ?>
                            
            <h4>学生</h4>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="font-center" style="width:20%;">学号</th>
                        <th class="font-center" style="width:20%;">姓名</th>
                        <th class="font-center" style="width:30%;">异常原因</th>
                        <th class="font-center" style="width:30%;">是否自动解决</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($array_fail as $stu_fail){ ?>
                    <tr>
                        <td><?php echo $stu_fail[1];?></td>
                        <td><?php echo $stu_fail[2];?></td>
                        <td><?php echo $stu_fail[0];?></td>
                        <td <?php if($stu_fail[3]=="需手动添加"){
                        echo 'style="color:red"';}else{echo 'style="color:green"';}
                        ?>><?php echo $stu_fail[3];?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php }  ?>
        
</div>

<script>
$('#form-key').submit(function(){
        $('#progress').show();
});
    $(document).ready(function(){
        <?php
        
        if(isset($result)){
            if($result==='不是Excel文件'){
                echo 'window.wxc.xcConfirm("'.$result.'",window.wxc.xcConfirm.typeEnum.warning)';
            }else if($result==='上传失败'){
                echo 'window.wxc.xcConfirm("'.$result.'",window.wxc.xcConfirm.typeEnum.error)';
            }else if(is_numeric($result)){ ?>
                 $('#progress').hide();
                 <?php
                if($flagTea==1){
                    if($flagClass==1){
                        echo 'window.wxc.xcConfirm("成功创建科目：'.$courseName.'，课时数为：'.$courseNumber.'，班级名为：'.$className.'，导入了'.$result.'个学生和'.$result1.'个老师,'.$count_fail.'个学生没有导入",window.wxc.xcConfirm.typeEnum.success)';                        
                    }else{
                        echo 'window.wxc.xcConfirm("成功创建科目：'.$courseName.'，课时数为：'.$courseNumber.'，班级：'.$className.'已存在导入了'.$result.'个学生和'.$result1.'个老师,'.$count_fail.'个学生没有导入",window.wxc.xcConfirm.typeEnum.success)';                        
                    }
                }
                else{
                    if($flagClass==1){
                        echo 'window.wxc.xcConfirm("科目：'.$courseName.'已存在，成功新建班级：'.$className.'，导入了'.$result.'个学生和'.$result1.'个老师,'.$count_fail.'个学生没有导入",window.wxc.xcConfirm.typeEnum.success)';                        
                    }else{
                        echo 'window.wxc.xcConfirm("科目：'.$courseName.'，班级：'.$className.'已存在，成功导入了'.$result.'个学生和'.$result1.'个老师,'.$count_fail.'个学生没有导入",window.wxc.xcConfirm.typeEnum.success)';
                    }
                }
            }else{
                echo 'window.wxc.xcConfirm("'.$result.'",window.wxc.xcConfirm.typeEnum.info)';
            }
        }
        ?>
    });
</script>