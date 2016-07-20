<div class="span3">
    <div <?php if(isset($allClass)){echo 'class="well-bottomnoradius"';}else{ echo'class="well"';} ?> style="padding: 8px 0;">
        <ul class="nav nav-list">
        <li class="nav-header"><i class="icon-navsearch" style="position:relative;bottom:5px;left:"></i>搜索</li>
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
                <button type="submit" class="btn_4big">搜 索</button>
                <button onclick="window.location.href = './index.php?r=admin/addClass'" type="button" class="btn_4big">添 加</button>
            </li>
        </form>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-knowlage" style="position:relative;bottom:5px;left:"></i>查看</li>
            <li id="classLst"><a href="./index.php?r=admin/classLst"><i class="icon-list-alt" style="position:relative;bottom:5px;left:"></i> 班级列表</a></li>
            <li id="stuLst"><a href="./index.php?r=admin/stuDontHaveClass"><i class="icon-list-alt" style="position:relative;bottom:5px;left:"></i> 未分班学生</a></li>
            <?php if(isset($allClass)){ ?>  
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-knowlage" style="position:relative;bottom:5px;left:"></i>已有班级</li>
            <?php } ?>
        </ul>
    </div>
    <?php if(isset($allClass)){ ?>  
        <div class="well-topnoradius" style="padding: 8px 0;height:315px;overflow:auto;top: -20px;border-top-left-radius:0px; " >
            <ul class="nav nav-list">
            <?php foreach ($allClass as $class): ?>
                        <li style="pointer-events: none;" ><a <?php if(Yii::app()->session['insert_class']==$class['className']){
                        echo 'style="color:#f46500"';Yii::app()->session['insert_class']="";}else{echo 'style="color: #aaa9a9"';}?>><i class="icon-list" style="position:relative;bottom:5px;left:"></i><?php echo $class['className']; ?></a></li>
                <?php endforeach; ?>   
            </ul>
        </div>
    <?php } ?>
    
</div>

