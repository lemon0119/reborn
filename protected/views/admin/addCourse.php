<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
            <form action="./index.php?r=admin/searchCourse" method="post">
                <li>
                    <select name="type" >
                        <option value="courseID" selected="selected">编号</option>
                        <option value="courseName">科目名</option>
                        <option value="createPerson">创建人</option>
                    </select>
                </li>
                <li>
                    <input name="value" type="text" class="search-query span2" placeholder="Search" />
                </li>
                <li style="margin-top:10px">
                    <button type="submit" class="btn_serch"></button>
                    <a href="./index.php?r=admin/addCourse" class="btn_add"></a>
                </li>
            </form>
            <li class="divider"></li>
            <li ><a href="./index.php?r=admin/courseLst"><i class="icon-align-left"></i> 科目列表</a></li>
        </ul>
    </div>
</div>
<div class="span9">
    <h3>新建科目</h3>
    <form id="myForm" method="post" action="./index.php?r=admin/addCourse" onkeydown="if (event.keyCode == 13) {
                return false;
            }"> 
        <fieldset>
            <legend>填写信息</legend>
            <div class="control-group">
                <label class="control-label" style="position: relative;left: 288px;top: 25px" for="input02">课程数</label>
                <label class="control-label" for="input01">科目名</label>
                <div class="controls">
                    <input name="courseName" type="text" class="input-xlarge" id="input01"/>
                    <input name="courseNumber" type="number" class="input-mini " id="input02"/>
                </div>

            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">添加</button> 
                <a href="./index.php?r=admin/<?php echo Yii::app()->session['lastUrl']; ?>&&page=<?php echo Yii::app()->session['lastPage']; ?>" class="btn">返回</a>
            </div>
        </fieldset>
    </form>   
</div>

<script>
    $(document).ready(function () {
        var result = <?php echo "'$result'"; ?>;
        if (result === '1')
            window.wxc.xcConfirm('添加科目成功！', window.wxc.xcConfirm.typeEnum.success);
        else if (result === '0')
            window.wxc.xcConfirm('添加科目失败！', window.wxc.xcConfirm.typeEnum.error);
        else if (result === 'have_same_course')
            window.wxc.xcConfirm('科目已存在,添加失败！', window.wxc.xcConfirm.typeEnum.error);
    });
    $("#myForm").submit(function () {
        var userID = $("#input01")[0].value;
        if (userID === "" || userID.replace(/\s/g, "").length === 0) {
            window.wxc.xcConfirm('科目名不能为空', window.wxc.xcConfirm.typeEnum.info);
            return false;
        }
        var classNumber = $("#input02")[0].value;
        if (classNumber === "") {
            window.wxc.xcConfirm('至少有一节课', window.wxc.xcConfirm.typeEnum.info);
            return false;
        } else if (classNumber > 100) {
            window.wxc.xcConfirm('科目数超出上限', window.wxc.xcConfirm.typeEnum.info);
            return false;
        } else if (classNumber < 0) {
            window.wxc.xcConfirm('至少有一节课', window.wxc.xcConfirm.typeEnum.info);
            return false;
        } else if (classNumber === 0) {
            window.wxc.xcConfirm('至少有一节课', window.wxc.xcConfirm.typeEnum.info);
            return false;
        }
    });
</script>

