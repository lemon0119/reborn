<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
        <li class="nav-header">查询</li>
        <form action="./index.php?r=teacher/searchChoice" method="post">
            <li>
                    <select name="type" style="width: 185px">
                            <option value="exerciseID" selected="selected">编号</option>
                            <option value="courseID" >课程号</option>
                            <option value="createPerson">创建人</option>
                            <option value="requirements">内容</option>
                    </select>
            </li>
            <li>
                    <input name="value" type="text" class="search-query span2" placeholder="Search" />
            </li>
            <li style="margin-top:10px">
                    <button type="submit" class="btn btn-primary">查询</button>
                    <a href="./index.php?r=teacher/addChoice" class="btn">添加</a>
            </li>
        </form>
            <li class="divider"></li>
            <li class="nav-header">基础知识</li>
            <li class="active"><a href="./index.php?r=teacher/choiceLst"><i class="icon-font"></i> 选择</a></li>
            <li ><a href="./index.php?r=teacher/fillLst"><i class="icon-text-width"></i> 填空</a></li>
            <li ><a href="./index.php?r=teacher/questionLst"><i class="icon-align-left"></i> 简答</a></li>
            <li class="divider"></li>
            <li class="nav-header">打字练习</li>
            <li ><a href="./index.php?r=teacher/keyLst"><i class="icon-th"></i> 键位练习</a></li>
            <li ><a href="./index.php?r=teacher/lookLst"><i class="icon-eye-open"></i> 看打练习</a></li>
            <li ><a href="./index.php?r=teacher/listenLst"><i class="icon-headphones"></i> 听打练习</a></li>
        </ul>
    </div>
</div>

<div class="span9">
    <h2>添加看打练习</h2>
    <form class="form-horizontal" method="post" action="./index.php?r=teacher/addChoice" id="myForm"> 
        <fieldset>
        <legend>填写题目</legend>
        <div class="control-group">
            <label class="control-label" for="input01">题目</label>
            <div class="controls">
                <textarea name="requirements" style="width:450px; height:50px;" id="input01"></textarea>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="input02">A</label>
            <div class="controls">
                <input type="radio"  value="A" name="answer"></input>
                <input name="A" type="text" class="input-xlarge" id="input02" value="" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="input03">B</label>
            <div class="controls">
                <input type="radio"  value="B" name="answer"></input>
                <input name="B" type="text" class="input-xlarge" id="input03" value="" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="input04">C</label>
            <div class="controls">
                <input type="radio"  value="C" name="answer"></input>
                <input name="C" type="text" class="input-xlarge" id="input04" value="" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="input05">D</label>
            <div class="controls">
                <input type="radio"  value="D" name="answer"></input>
                <input name="D" type="text" class="input-xlarge" id="input05" value="" />
            </div>
        </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">添加</button> <a href="./index.php?r=teacher/returnFromAddChoice" class="btn">取消</a>
    </div>
    </fieldset>
</form>   
</div>

<script>     
$(document).ready(function(){
    var result = <?php echo "'$result'";?>;
    if(result === '1')
        alert('添加选择题成功！');
    else if(result === '0')
        alert('添加选择题失败！');  
});

$("#myForm").submit(function(){
    var requirements = $("#input01")[0].value;
    if(requirements === ""){
        alert('题目内容不能为空');
        return false;
    }
    var A = $("#input02")[0].value;
    if(A === ""){
        alert('选项A内容不能为空');
        return false;
    }
    var B = $("#input03")[0].value;
    if(B === ""){
        alert('选项B内容不能为空');
        return false;
    }
    var C = $("#input04")[0].value;
    if(C === ""){
        alert('选项C内容不能为空');
        return false;
    }
    var D = $("#input05")[0].value;
    if(D === ""){
        alert('选项D内容不能为空');
        return false;
    }
    var answer = $('input:radio[name="answer"]:checked').val();
    if(answer == null){
        alert('请选择一个答案选项');
        return false;
    }

});
</script>



