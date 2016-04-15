<?php require 'stuSideBar.php'; ?>
<div class="span9">
    <h2>添 加 学 生</h2>
    <form action="./index.php?r=admin/exlAddStu" class="form-horizontal"
          method="post" id="form-exlAddStu" enctype="multipart/form-data">
        <fieldset>
            <legend>批量添加</legend>
            <div class="control-group">
                <label class="control-label" for="input01">请选择Excel文件
                </label>
                <div class="controls">
                    <input type="file" name="file" id="file" />
                </div>
            </div>
            <h3>异常添加列表</h3>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="font-center">学号</th>
                        <th class="font-center">姓名</th>
                        <th class="font-center">异常原因</th>         
                        <th class="font-center">是否自动解决</th>               
                    </tr>
                </thead>
                <tbody> 
                    <?php if(isset($array_fail)){ foreach ($array_fail as $stu_fail){?>
                    <tr>
                      <td class="font-center" style="width: 125px"><?php echo $stu_fail[1]; ?></td>   
                      <td class="font-center"><?php echo $stu_fail[2]; ?></td>  
                      <td class="font-center" style="color: #f46500"><?php echo $stu_fail[0]; ?></td> 
                      <td class="font-center"  <?php if($stu_fail[3]=="需手动添加"){
  echo 'style="color: red"';}else{echo 'style="color: green"';}?>><?php echo $stu_fail[3]; ?></td>  
                    <tr>
                   <?php } }?>
                </tbody>
            </table>
            <div class="form-actions">
                <input type="submit" class="btn btn-primary" value="添加" />&nbsp;&nbsp;<a
                    href="./index.php?r=admin/addStu" class="btn btn-primary">逐个添加</a>&nbsp;&nbsp;<a
                    href="./index.php?r=admin/stuLst" class="btn btn-primary">返回</a>
            </div>
        </fieldset>
    </form>
</div>

<script>
    $(document).ready(function () {
         <?php
            if(isset($result)){
                if($result==='不是Excel文件'){
                    echo 'window.wxc.xcConfirm("'.$result.'", window.wxc.xcConfirm.typeEnum.warning)';
                }else  if($result==='上传失败'){
                    echo 'window.wxc.xcConfirm("'.$result.'",window.wxc.xcConfirm.typeEnum.error)';
                }else if(is_numeric($result)){
                     echo 'window.wxc.xcConfirm("成功导入了'.$result.'个学生,'.$count_fail.'个学生没有导入", window.wxc.xcConfirm.typeEnum.success)';
                }else {
                    echo 'window.wxc.xcConfirm("'.$result.'", window.wxc.xcConfirm.typeEnum.info)';
                }
            }
         ?>
    });



</script>
