<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
        <li class="nav-header"><i class="icon-navsearch" style="position:relative;bottom:7px;left:"></i>搜索</li>
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
                <button type="submit"class="btn_4superbig">搜&nbsp;&nbsp;&nbsp;索</button>
            </li>
        </form>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-knowlage" style="position:relative;bottom:7px;left:"></i>查看</li>
            <li <?php if(isset($_GET['type'])){}else{    echo 'class="active"';} ?> id="stuLst"><a href="./index.php?r=admin/schedule"><i class="icon-list-alt" style="position:relative;bottom:7px;left:"></i> 老师列表</a></li>
            <li <?php if(isset($_GET['type'])){  echo 'class="active"';}else{ } ?> id="classLst"><a href="./index.php?r=admin/schedule&&type=class"><i class="icon-list-alt" style="position:relative;bottom:7px;left:"></i> 班级列表</a></li>
        </ul>
    </div>
</div>

<div class="span9">
    <?php if(isset($teacher_search)){  ?>
        <h3>老师的查询结果</h3>
        <form>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="font-center">工号</th>
                        <th class="font-center">姓名</th>
                        <th class="font-center">所属院系</th>
                        <th class="font-center">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($teacher_search as $t_value){
                        error_log($t_value['userID']);
                        if($t_value['userID']!=="A01"){
                        ?>    
                        <tr>
                            <td class="font-center" ><?php echo $t_value['userID'];?></td>
                            <td class="font-center" ><?php echo $t_value['userName'];?></td>
                            <td class="font-center" ><?php echo $t_value['department'];?></td>
                            <td class="font-center" style="width: 75px" >
                                <a href="./index.php?r=admin/scheduleDetil&&teacherId=<?php echo $t_value['userID']; ?>"><img title="编辑" src="<?php echo IMG_URL; ?>edit.png"></a>
                            </td>
                        </tr>
                   <?php }
                    }
                   ?>
                </tbody>
            </table>
        </form>
    <?php }else if(isset($class_search)){ ?>
        <h3>班级的查询结果</h3>
            <form>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="font-center">班号</th>
                        <th class="font-center">班级名</th>
                        <th class="font-center">科目</th>
                        <th class="font-center">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($class_search as $c_value){ 
                        if($c_value['className']!="速录一班"){
                        ?>    
                        <tr>
                            <td class="font-center" ><?php echo $c_value['classID'];?></td>
                            <td class="font-center" ><?php echo $c_value['className'];?></td>
                            <td class="font-center" ><?php echo $c_value['courseName'];?></td>
                            <td class="font-center" style="width: 75px" >
                                 <a href="./index.php?r=admin/scheduleDetil&&classId=<?php echo $c_value['classID']; ?>"><img title="编辑" src="<?php echo IMG_URL; ?>edit.png"></a>
                            </td>
                        </tr>
                   <?php }
                    }
                   ?>
                </tbody>
            </table>
        </form>
    <?php }else if(isset($noResult)){ ?>
        <h3>没有对应查询结果</h3>
    <?php }else if(isset($_GET['type'])){ ?>
        <h3>班级列表</h3>
        <form>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="font-center">班号</th>
                        <th class="font-center">班级名</th>
                        <th class="font-center">科目</th>
                        <th class="font-center">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($class as $c_value){ 
                        if($c_value['className']!="速录一班"){
                        ?>    
                        <tr>
                            <td class="font-center" ><?php echo $c_value['classID'];?></td>
                            <td class="font-center" ><?php echo $c_value['className'];?></td>
                            <td class="font-center" ><?php echo $c_value['courseName'];?></td>
                            <td class="font-center" style="width: 75px" >
                                 <a href="./index.php?r=admin/scheduleDetil&&classId=<?php echo $c_value['classID']; ?>"><img title="编辑" src="<?php echo IMG_URL; ?>edit.png"></a>
                            </td>
                        </tr>
                   <?php }
                    }
                   ?>
                </tbody>
            </table>
            <div align=center>
        <?php
       $this->widget('CLinkPager', array('pages' => $class_pages));
        ?>
    </div>
        </form>
    <?php }else{?>
        <h3>老师列表</h3>
        <form>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="font-center">工号</th>
                        <th class="font-center">姓名</th>
                        <th class="font-center">所属院系</th>
                        <th class="font-center">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($teacher as $t_value){ 
                        if($t_value['userID']!=="A01"){
                        ?>    
                        <tr>
                            <td class="font-center" ><?php echo $t_value['userID'];?></td>
                            <td class="font-center" ><?php echo $t_value['userName'];?></td>
                            <td class="font-center" ><?php echo $t_value['department']; ?></td>
                            <td class="font-center" style="width: 75px" >
                                 <a href="./index.php?r=admin/scheduleDetil&&teacherId=<?php echo $t_value['userID']; ?>"><img title="编辑" src="<?php echo IMG_URL; ?>edit.png"></a>
                            </td>
                        </tr>
                   <?php }
                    }
                   ?>
                </tbody>
            </table>
            <div align=center>
        <?php
       $this->widget('CLinkPager', array('pages' => $tea_pages));
        ?>
    </div>
        </form>
        
    <?php }?>
</div>