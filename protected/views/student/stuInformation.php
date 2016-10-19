
<div class="span3">
       <div class="well" style="padding: 8px 0;height: 636px;">
           <li class="nav-header"><h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;个人设置</h4></li> 
           <ul class="nav nav-list">
           <li class="active" id="two"><a class="cont2" href="./index.php?r=student/stuInformation">个人资料</a></li>   
           <li id="one"><a class="cont1" href="./index.php?r=student/set">修改密码</a></li>   
           <li id="two"><a class="cont2" href="./index.php?r=student/headPic">修改头像</a></li>   
           </ul>
        </div>
</div>
<div class="span9" style="height: 574px">
    <h3>个人信息</h3>
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
                    <td>性别:</td>
                    <td><?php echo $sex;?></td>
                </tr>
                 <tr>
                    <td>年龄:</td>
                    <td><?php echo $age;?></td>
                </tr>
                <tr>
                    <td>班级:</td>
                    <td><?php $sqlClass = TbClass::model()->find("classID = $class");
          echo $sqlClass['className'];
    ?></td>
                </tr>
                <tr>
                    <td>联系电话:</td>
                    <td><?php if($phone_number=="") echo "无"; else echo $phone_number;?></td>
                </tr>
                <tr>
                    <td>联系邮箱:</td>
                    <td><?php if($mail_address=="") echo "无"; else echo $mail_address;?></td>
                </tr>
                
            </tbody>
    </table>
    </div>
</div>
<script>
    $(document).ready(function(){
       <?php if(Yii::app()->session['lastUrl']=="stuDontHaveClass") {?>
        $("#stuLst").attr("class","active");
       <?php }?>
    });
</script>
