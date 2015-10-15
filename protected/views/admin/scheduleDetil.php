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
            <li <?php if(isset($_GET['type'])){}else{    echo 'class="active"';} ?> id="stuLst"><a href="./index.php?r=admin/schedule"><i class="icon-list-alt"></i> 老师列表</a></li>
            <li <?php if(isset($_GET['type'])){  echo 'class="active"';}else{ } ?> id="classLst"><a href="./index.php?r=admin/schedule&&type=class"><i class="icon-list-alt"></i> 班级列表</a></li>
        </ul>
    </div>
</div>
<div class="span9">
    <h3><font style="color: #f46500"><?php echo $teacher['userName'];?></font>&nbsp;&nbsp;老师的课程安排</h3>
    <br/>
    <form>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="font-center" style="width: 40px"></th>
                    <th class="font-center" style="width: 40px"></th>
                    <th class="font-center">星期一</th>
                    <th class="font-center">星期二</th>
                    <th class="font-center">星期三</th>
                    <th class="font-center">星期四</th>
                    <th class="font-center">星期五</th>
                    <th class="font-center">星期六</th>
                    <th class="font-center">星期日</th>
                </tr>
                <tr>
                    <th rowspan="2" class="font-center" >上午</th>
                    <th class="font-center">一</th>
                </tr>
                <tr>
                    <th class="font-center">二</th>
                </tr>
                <tr>
                    <th rowspan="2" class="font-center" >下午</th>
                    <th class="font-center">三</th>
                </tr>
                <tr>
                    <th class="font-center">四</th>
                </tr>
                <tr>
                    <th rowspan="2" class="font-center" >晚上</th>
                    <th class="font-center">五</th>
                </tr>
                <tr>
                    <th class="font-center">六</th>
                </tr>
            </thead>
            
        </table>
    </form>
</div>
