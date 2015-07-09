<div class="span3">
        <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                <li class="nav-header">查询</li>
                <form action="./index.php?r=admin/searchTea" method="post">
                        <li>
                                <select name="type" style="width: 185px">
                                        <option value="userID" selected="selected">工号</option>
                                        <option value="userName">姓名</option>
                                </select>
                        </li>
                        <li>
                                <input name="value" type="text" class="search-query span2" placeholder="Search" />
                        </li>
                        <li style="margin-top:10px">
                                <button type="submit" class="btn btn-primary">查询</button>
                        </li>
                </form>
                        <li class="divider"></li>
                        <li class="nav-header">管理</li>
                        <li id="li-stuLst"><a href="./index.php?r=admin/teaLst"><i class="icon-list-alt"></i> 老师列表</a></li>
                        <li id="li-addStu"><a href="./index.php?r=admin/addTea"><i class="icon-plus"></i> 添加老师</a></li>
                        <li id="li-recycleStu"><a href="./index.php?r=admin/recycleStu"><i class="icon-trash"></i> 回收站</a></li>
                </ul>
        </div>
</div>
