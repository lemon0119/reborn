<div class="span3">
        <div class="well" style="padding: 8px 0;">
                <ul class="nav nav-list">
                <li class="nav-header"><i class="icon-navsearch"style="position:relative;bottom:5px;left:"></i>搜索</li>
                <form action="./index.php?r=admin/searchStu&&page=1" method="post">
                        <li>
                                <select name="type">
                                        <option value="userID" selected="selected">学号</option>
                                        <option value="userName">姓名</option>
                                        <option value="classID">班级</option>
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
                        <li class="nav-header"><i class="icon-knowlage" style="position:relative;bottom:6px;left:"></i>管理</li>
                        <li id="li-stuLst"><a href="./index.php?r=admin/stuLst"><i class="icon-list-alt" style="position:relative;bottom:7px;left:"></i> 学生列表</a></li>
                        <li id="li-addStu"><a href="./index.php?r=admin/addStu"><i class="icon-plus" style="position:relative;bottom:7px;left:"></i> 添加学生</a></li>
                        <li id="li-recycleStu"><a href="./index.php?r=admin/recycleStu"><i class="icon-trash" style="position:relative;bottom:6px;left:"></i> 回收站</a></li>
                </ul>
        </div>
</div>
