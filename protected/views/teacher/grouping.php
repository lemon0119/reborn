<div class="span9" style="width: 1089px;" align ="center" >
    <form id="group" action="./index.php?r=teacher/groupStudent&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>" method="post">
    <table class="table table-bordered table-striped" style="width: 800px">
        <thead>
            <tr>
                
                <th class="font-center">学号</th>
                <th class="font-center">姓名</th>
                <th class="font-center">等级</th>
                <th class="font-center">操作</th>
            </tr>
        </thead>
        <tbody>
        
            <?php foreach ($stu as $model){
                ?>
                <tr>
                    <td class="font-center" style="width: 125px"><?php echo $model['userID']; ?></td>
                    <td class="font-center" style="width: 125px"><?php echo $model['userName']; ?></td>
                    <td class="font-center" style="width: 100px"><?php echo $model['level'];?></td>
                    <td class="font-center">  
                        <input   type="radio"   value="初级"    name="level<?php echo $model['userID'];?>" <?php if($model['level']=='初级'){echo 'checked="true"';} ?> ><span style="position: relative;top: 4px">&nbsp;初级</span>&nbsp;&nbsp;&nbsp;&nbsp;
                        <input   type="radio"   value="中级"    name="level<?php echo $model['userID'];?>" <?php if($model['level']=='中级'){echo 'checked="true"';} ?>  ><span style="position: relative;top: 4px">&nbsp;中级</span>&nbsp;&nbsp;&nbsp;&nbsp;
                        <input   type="radio"   value="高级"    name="level<?php echo $model['userID'];?>" <?php if($model['level']=='高级'){echo 'checked="true"';} ?>  ><span style="position: relative;top: 4px">&nbsp;高级</span>&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                </tr> 
            <?php }?> 
      
        </tbody>
    </table>
    <button type="submit" class="btn btn-primary" style="width: 11%">提交</button>
    <a href="./index.php?r=teacher/startCourse&&classID=<?php echo $classID; ?>&&progress=<?php echo $progress;?>&&on=<?php echo $on;?>" class="btn btn-primary" style="width: 8%">返回</a>
  </form>
</div>
<script>
    $(document).ready(function(){
    <?php if(isset($result))
            echo " window.wxc.xcConfirm('$result', window.wxc.xcConfirm.typeEnum.success);";?>
    });
    
</script>