<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
        <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
        <form action="./index.php?r=admin/searchFill" method="post">
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
                    <button type="submit" class="btn_serch"></button>
                    <a href="./index.php?r=admin/addFill" class="btn_add"></a>
            </li>
        </form>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-knowlage"></i>基础知识</li>
            <li ><a href="./index.php?r=admin/choiceLst"><i class="icon-font"></i> 选择</a></li>
            <li class="active"><a href="./index.php?r=admin/fillLst"><i class="icon-text-width"></i> 填空</a></li>
            <li ><a href="./index.php?r=admin/questionLst"><i class="icon-align-left"></i> 简答</a></li>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-typing"></i>打字练习</li>
            <li ><a href="./index.php?r=admin/keyLst"><i class="icon-th"></i> 键打练习</a></li>
            <li ><a href="./index.php?r=admin/lookLst"><i class="icon-eye-open"></i> 看打练习</a></li>
            <li ><a href="./index.php?r=admin/listenLst"><i class="icon-headphones"></i> 听打练习</a></li>
        </ul>
    </div>
</div>


    <script type="text/javascript">
    var count = 2;
    function addIn()
    {
        if(count<6){
            count++;
            $("#input"+count).show();
        }else
        {
            window.wxc.xcConfirm("最多添加五个选项", window.wxc.xcConfirm.typeEnum.info);
        }
    }
    function deleteIn()
    {
        if(count>2){
            $("#input"+count).hide();
            $("#input"+count).val("");
            count--;
        }else
        {
            window.wxc.xcConfirm("必须有一个答案", window.wxc.xcConfirm.typeEnum.info);
        }
    }
    </script>   

<div class="span9">
        <h2>添加填空</h2>
        <form id="myForm" class="form-horizontal" method="post" action="./index.php?r=admin/addFill"> 
            <fieldset>
            <legend>填写题目</legend>
            <div class="control-group">
                <label style="position: relative;left:55px;top: -32px;" class="control-label" for="input1">题目</label>
                <div class="controls">
                    <textarea name="requirements" style="width:450px; height:50px;" id="input1"></textarea>
                </div>
            </div>
            <br/>
            <div class="control-group">
                <label style="position: relative;left:53px;top: -32px;" class="control-label" for="input2">答案</label>
                <div class="controls">
                    <input name="in1" type="text" class="input-xlarge" id="input2" value="" />
                    <a class="btn_insert_admin" onclick="addIn()"></a> <a class="btn_subtract_admin" onclick="deleteIn()"></a>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <input name="in2" style="display:none" type="text" class="input-xlarge" id="input3" value="" />
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <input name="in3" style="display:none" type="text" class="input-xlarge" id="input4" value="" />
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <input name="in4" style="display:none" type="text" class="input-xlarge" id="input5" value="" />
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <input name="in5" style="display:none" type="text" class="input-xlarge" id="input6" value="" />
                </div>
            </div>
            <div  style="text-align: center;">
                <a style="position: relative;right:105px;top: 8px;" href="./index.php?r=admin/returnFromAddFill" class="btn_ret_admin"></a>
                <button style="position: relative;right:105px;top: 6px;" type="submit" class="btn_add_admin"></button>
            </div>
            </fieldset>
        </form>
</div>
       


<script>     
$(document).ready(function(){
    var result = <?php echo "'$result'";?>;
    if(result === '1')
    window.wxc.xcConfirm('添加填空题成功！', window.wxc.xcConfirm.typeEnum.success);
    else if(result === '0')
    window.wxc.xcConfirm('添加填空题失败！', window.wxc.xcConfirm.typeEnum.error);
});
$("#myForm").submit(function(){
    var requirements = $("#input1")[0].value;
    if(requirements === ""||requirements.replace(/\s/g, "").length===0){
        window.wxc.xcConfirm('题目内容不能为空', window.wxc.xcConfirm.typeEnum.info);
        return false;
    }
    var x,y;
    for(x=2;x<=count;x++){
    var an = $("#input"+x)[0].value;
    if(an === ""||an.replace(/\s/g, "").length===0){
        y=x-1;
        window.wxc.xcConfirm("空"+y+"内容不能为空", window.wxc.xcConfirm.typeEnum.info);
        return false;
    }
    }
});
</script>


