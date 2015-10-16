<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
        <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
        <form action="./index.php?r=admin/schedule" method="post">
            <li>
                <select name="which" >
                        <option value="className" selected="selected" >班级名</option>
                        <option value="teaName" >老师</option>
                </select>
            </li>
            <li>
                <input name="value" type="text" class="search-query span2" placeholder="Search" />
            </li>
            <li style="margin-top:10px">
                <button type="submit"class="btn_bigserch"></button>
            </li>
        </form>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-knowlage"></i>查看</li>
            <li class="active"  ><a href="#"><i class="icon-list-alt"></i> 课程列表</a></li>
        </ul>
    </div>
</div>
<div class="span9">
    <?php if(isset($_GET['teacherId'])){ ?>
         <h3><font style="color: #f46500"><?php echo $teacher['userName'];?></font>&nbsp;&nbsp;老师的课程安排</h3>
    <br/>
    <form action="./index.php?r=admin/scheduleDetil&&teacherId=<?php echo  Yii::app()->session['teacherId'];?>" method="POST">
        
    <?php }else{ ?>
         <h3><font style="color: #f46500"><?php echo $class;?></font>&nbsp;&nbsp;的课程安排</h3>
    <br/>
    <form action="./index.php?r=admin/scheduleDetil&&classId=<?php echo  Yii::app()->session['teacherId'];?>" method="POST">
    <?php }?>
        <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th colspan="2" style="width: 40px"></th>
                        <th >星期一</th>
                        <th >星期二</th>
                        <th >星期三</th>
                        <th >星期四</th>
                        <th >星期五</th>
                        <th >星期六</th>
                        <th >星期日</th>
                    </tr>
                </thead>
            <tbody>
                <?php for($s=0;$s<6;$s++){ ?>
                <tr>
                    <?php switch ($s){case 0: echo '<td  rowspan="2">';break;case 2:echo '<td rowspan="2">';break;case 4:echo '<td rowspan="2">';break;}?><?php switch ($s){case 0: echo '上午';break;case 2:echo '下午';break;case 4:echo '晚上';break;}?></td>
                    <td ><?php switch ($s){case 0: echo '一';break;case 1: echo '二';break;case 2: echo '三';break;case 3: echo '四';break;case 4: echo '五';break;case 5: echo '六';break;}?></td>
                        <?php for($d=0;$d<7;$d++ ){ ?>
                        <td>
                            <select name="<?php echo "$d/$s" ?>" class="course_option">
                                <option  value="">-</option> 
                            <?php foreach ($courseName as $v){ ?>
                                <option  value="<?php echo $v;?>"><?php echo $v;?></option>  
                            <?php }?>
                            </select>
                        </td>
                        <?php }?>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">确定修改</button>
        <a href="./index.php?r=admin/schedule" class="btn">返回</a>
    </form>
</div>
