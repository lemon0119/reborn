
<div class="span3">
       <div class="well" style="padding: 8px 0;height: 565px;">
           <li class="nav-header"><h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;个人设置</h4></li> 
           <li class="nav-header" id="two">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="cont2" href="./index.php?r=teacher/teaInformation">个人资料</a></li>   
           <li class="nav-header" id="one"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="cont1" href="./index.php?r=teacher/set">修改密码</a></li>   
        </div>
</div>
<div class="span9">
    <h3>个人信息</h3>
    <div class="hero-unit">
    <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <td width="30%">工号:</td>
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
                    <td>部门:</td>
                    <td><?php echo $department; ?></td>
                </tr>
                <tr>
                    <td>院校:</td>
                    <td><?php echo $school; ?></td>
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

