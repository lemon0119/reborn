<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
            <form action="./index.php?r=teacher/searchQuestion" method="post">
                <li>
                    <select name="type" >
                        <option value="exerciseID" selected="selected">编号</option>
                        <option value="courseID" >科目号</option>
                        <option value="createPerson" >创建人</option>    
                        <option value="requirements">内容</option>
                    </select>
                </li>
                <li>
                    <input name="value" type="text" class="search-query span2" placeholder="Search" />
                </li>
                <li style="margin-top:10px">
                    <button type="submit" class="btn_4big">搜 索</button>
                    <button onclick="window.location.href = './index.php?r=teacher/addQuestion'" type="button" class="btn_4big">添 加</button>
                </li>
            </form>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-knowlage"></i>基础知识</li>
            <li ><a href="./index.php?r=teacher/choiceLst"><i class="icon-font"></i> 选择</a></li>
            <li ><a href="./index.php?r=teacher/fillLst"><i class="icon-text-width"></i> 填空</a></li>
            <li class="active"><a href="./index.php?r=teacher/questionLst"><i class="icon-align-left"></i> 简答</a></li>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-typing"></i>打字练习</li>
            <li ><a href="./index.php?r=teacher/keyLst"><i class="icon-th"></i> 键打练习</a></li>
            <li ><a href="./index.php?r=teacher/lookLst"><i class="icon-eye-open"></i> 看打练习</a></li>
            <li ><a href="./index.php?r=teacher/listenLst"><i class="icon-headphones"></i> 听打练习</a></li>
        </ul>
    </div>
</div>

<div class="span9">          
    <?php if (!isset($action)) { ?>
        <legend><h3>修改简答题</h3></legend>
    <?php } else if ($action == 'look') { ?>
        <legend><h3>查看简答题</h3></legend>
    <?php } ?>
    <form id="myForm" class="form-horizontal" method="post" action="./index.php?r=teacher/editQuestionInfo&&exerciseID=<?php echo $exerciseID; ?>">
        <fieldset>
            <div class="control-group">
                <label class="control-label" for="input01">题目</label>
                <div class="controls">
                    <textarea name="requirements" style="width:450px; height:50px;" id="input01" <?php if (isset($action)) { ?> readOnly="true"<?php } ?>><?php echo $requirements; ?></textarea>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="input02">答案</label>
                <div class="controls">               
                    <textarea name="answer" style="width:450px; height:200px;" <?php if (isset($action)) { ?> readOnly="true"<?php } ?>><?php echo $answer; ?></textarea>
                </div>
            </div> 
            <div class="form-actions">
                <?php if (!isset($action)) { ?> 
                    <button type="submit" class="btn btn-primary">修改</button>
                <?php } ?>
                <a href="./index.php?r=teacher/questionLst" class="btn btn-primary">返回</a>
            </div>
        </fieldset>
    </form>   
</div>
<script>
    $(document).ready(function () {
<?php if (isset($result))
    echo "window.wxc.xcConfirm('$result', window.wxc.xcConfirm.typeEnum.info);";
?>
    });
    $("#myForm").submit(function () {
        var requirements = $("#input01")[0].value;
        if (requirements === "") {
            window.wxc.xcConfirm('题目内容不能为空', window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }
        var A = $("#input02")[0].value;
        if (A === "") {
            window.wxc.xcConfirm('答案不能为空', window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }
    });
</script>
