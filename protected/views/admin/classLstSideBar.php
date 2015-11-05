<div class="span3">
    <div <?php if(isset($allClass)){echo 'class="well-bottomnoradius"';}else{ echo'class="well"';} ?> style="padding: 8px 0;">
        <ul class="nav nav-list">
        <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
        <form action="./index.php?r=admin/searchCLass" method="post">
            <li>
                <select name="which" >
                        <option value="classID" selected="selected">班号</option>
                        <option value="className" >班级名</option>
                        <option value="courseName" >科目</option>
                        <option value="teaName" >老师</option>
                </select>
            </li>
            <li>
                <input name="value" type="text" class="search-query span2" placeholder="Search" />
            </li>
            <li style="margin-top:10px">
                <button type="submit" class="btn_serch"></button>
                <a href="./index.php?r=admin/addClass" class="btn_add"></a>
            </li>
        </form>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-knowlage"></i>查看</li>
            <li id="classLst"><a href="./index.php?r=admin/classLst"><i class="icon-list-alt"></i> 班级列表</a></li>
            <li id="stuLst"><a href="./index.php?r=admin/stuDontHaveClass"><i class="icon-list-alt"></i> 未分班学生</a></li>
            <?php if(isset($allClass)){ ?>  
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-knowlage"></i>已有班级</li>
            <?php } ?>
        </ul>
    </div>
    <?php if(isset($allClass)){ ?>  
        <div class="well-topnoradius" style="padding: 8px 0;height:315px;overflow:auto;top: -20px;border-top-left-radius:0px; " >
            <ul class="nav nav-list">
            <?php foreach ($allClass as $class): ?>
                        <li <?php if(Yii::app()->session['insert_class']==$class['className']){
    echo 'class="active"';Yii::app()->session['insert_class']="";}?> ><a><i class="icon-list"></i><?php echo $class['className']; ?></a></li>
                <?php endforeach; ?>   
            </ul>
        </div>
    <?php } ?>
    
</div>

