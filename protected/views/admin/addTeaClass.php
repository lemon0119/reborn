<?php require 'classLstSideBar.php';?>
<div class="span9">

<?php
//得到老师ID对应的名称
$teacher=$teachers->read();
//有班级的老师
$corr=$posts->read();
?>

<h3>向<?php echo $classID;?>班添加老师</h3>
<form id="myForm" method="post" action="./index.php?r=admin/infoClass&&classID=<?php echo $classID;?>&&action=addTea" onkeydown="if(event.keyCode==13){return false;}"> 
<input type="checkbox" name="all" onclick="check_all(this,'checkbox[]')" />全选/全不选</p> 
<table class="table table-bordered table-striped">
<thead>
    <tr>
        <th>选择</th>
        <th>工号</th>
        <th>姓名</th>
        <th>所属部门</th>
    </tr>
</thead>
        <tbody> 
            <?php $i=0; ?>
            <?php 
            while (!empty($teacher))
            {
               
                if(empty($corr)|| ($teacher['userID']<$corr['teacherID'])){
                        echo "<tr>";
                        echo  "<td style=\"width: 75px\"> <input type=\"checkbox\" name=\"checkbox[]\" value= " . $teacher['userID'] ."  /> </td>";                     
                        echo  "<td style=width: 75px>". $teacher['userID'] ."</td>";
                        echo  "<td>" .  $teacher['userName']  . "</td>";
                        echo  "<td>" .  $teacher['department']  . "</td>";
                        echo  "</tr> ";
                     $i++;
                 $teacher=$teachers->read();   
                }else if($teacher['userID']==$corr['teacherID']) {
                     $teacher=$teachers->read();
                }else if($teacher['userID']>$corr['teacherID'])
                    $corr=$posts->read();
            }
        ?>
        </tbody>
</table>
<div align=center>
        <?php
       $this->widget('CLinkPager', array('pages' => $pages));
        ?>
    </div>
<br/>
    <div style="text-align: center">
        <?php if($i == 0){ ?>
        <a class="btn btn-primary" href="./index.php?r=admin/addTea" >添加</a><?php }else{ ?>
        <button type="submit"  class="btn btn-primary" >添加</button><?php } ?>
        <a class="btn btn-primary" href="./index.php?r=admin/infoClass&&classID=<?php echo $classID;?>" >返回</a>
    </div>
</form>   

<!-- js控制全选/取消全选  -->    
<script type="text/javascript"> 
        $("#myForm").submit(function(){
    var checkboxs = document.getElementsByName('checkbox[]');
    var flag = 0;
        for (var i = 0; i < checkboxs.length; i++) {
           if(checkboxs[i].checked){
                flag=1;
                break;
           }
        } 
        if(flag==0){
           window.wxc.xcConfirm('未选中任何老师', window.wxc.xcConfirm.typeEnum.info);
           return false;
        }
});    

function check_all(obj,cName) 
{    
var checkboxs = document.getElementsByName(cName); 
for(var i=0;i<checkboxs.length;i++){checkboxs[i].checked = obj.checked;} 
} 
</script> 

</div>
