<?php require 'stuSideBar.php';?>
<div class="span9">
    <h3>学生信息</h3>
    <div class="hero-unit">
    <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <td width="30%">学号:</td>
                    <td><?php echo $id;?></td>
                </tr>
                <tr>
                    <td>姓名:</td>
                    <td><?php echo $name;?></td>
                </tr>
                <tr>
                    <td>班级:</td>
                    <td><?php if($class=="0") echo "无"; else echo $class;?></td>
                </tr>
            </tbody>
    </table>
        <?php if(isset($flag)){?>
        <a href="./index.php?r=admin/searchStu" class="btn">返回</a>
        <?php }else{?>
        <a href="./index.php?r=admin/stuLst" class="btn">返回</a>
        <?php }?>
    </div>
</div>

