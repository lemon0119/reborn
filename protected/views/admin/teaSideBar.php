<div class="span3">
        <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
                <form action="./index.php?r=admin/searchTea" method="post">
                        <li>
                                <select name="type" >
                                        <option value="userID" selected="selected">工号</option>
                                        <option value="userName">姓名</option>
                                        <option value="department">所属部门</option>
                                </select>
                        </li>
                        <li>
                                <input name="value" type="text" class="search-query span2" placeholder="Search" />
                        </li>
                        <li style="margin-top:10px">
                                <button type="submit" class="btn_4superbig">搜&nbsp;&nbsp;&nbsp;索</button>
                        </li>
                </form>
                        <li class="divider"></li>
                        <li class="nav-header"><i class="icon-knowlage"></i>管理</li>
                        <li id="li-stuLst"><a href="./index.php?r=admin/teaLst"><i class="icon-list-alt"></i> 老师列表</a></li>
                        <li id="li-addTea"><a href="./index.php?r=admin/addTea"><i class="icon-plus"></i> 添加老师</a></li>
                        <li id="li-recycleStu"><a href="./index.php?r=admin/recycleTea"><i class="icon-trash"></i> 回收站</a></li>
                </ul>
        </div>
</div>
