 <div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
        <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
        <form action="./index.php?r=admin/searchKey" method="post">
            <li>
                <select name="type" >
                    <option value="exerciseID" selected="selected">编号</option>
                    <option value="courseID" >课程号</option>
                    <option value="createPerson" >创建人</option>
                </select>
            </li>
            <li>
                    <input name="value" type="text" class="search-query span2" placeholder="Search" />
            </li>
            <li style="margin-top:10px">
                    <button type="submit" class="btn_serch"></button>
                    <a href="./index.php?r=admin/AddKey" class="btn_add"></a>
            </li>
        </form>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-knowlage"></i>基础知识</li>
            <li ><a href="./index.php?r=admin/choiceLst"><i class="icon-font"></i> 选择</a></li>
            <li ><a href="./index.php?r=admin/fillLst"><i class="icon-text-width"></i> 填空</a></li>
            <li ><a href="./index.php?r=admin/questionLst"><i class="icon-align-left"></i> 简答</a></li>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-typing"></i>打字练习</li>
            <li class="active"><a href="./index.php?r=admin/keyLst"><i class="icon-th"></i> 键位练习</a></li>
            <li ><a href="./index.php?r=admin/lookLst"><i class="icon-eye-open"></i> 看打练习</a></li>
            <li ><a href="./index.php?r=admin/listenLst"><i class="icon-headphones"></i> 听打练习</a></li>
        </ul>
    </div>
</div>

    
<div class="span9" >        
<?php if(!isset($action)) {?>
<h3>编辑键位练习题</h3>
<?php } else if($action == 'look') {?>
<h3>查看键位练习题</h3>
<?php }?>

    <form class="form-horizontal" method="post" action="./index.php?r=admin/AddKey" id="myForm"> 
        <fieldset>
        <?php if(!isset($action)) {?>
            <legend>填写题目</legend>
        <?php } else if($action == 'look') {?>
            <legend>查看题目</legend>
        <?php }?>
        <div class="control-group">
            <label class="control-label" for="input">题目</label>
            <div class="controls">
                <textarea name="title" style="width:450px; height:20px;" id="input"></textarea>
            </div>
        </div>
            <div class="control-group" id="div1">
            <label class="control-label" >键位码</label>
            <div class="controls">               
                <input type="text" name="in1" style="width:150px; height:15px;" id="input1" maxlength="12">                
                <input type="text" name="in2" style="width:150px; height:15px;" id="input2" maxlength="12">
                <input type="text" name="in3" style="width:40px; height:15px;" id="input3" maxlength="2">
                <a class="btn btn-primary" onclick="addIn()"><i class="icon-plus-editwork icon-white"></i></a> <a class="btn btn-primary" onclick="deleteIn()"><i class="icon-minus icon-white"></i></a>
            </div>             
        </div>
        <div class="control-group" id="div2">           
            <div class="controls">               
                <input type="text"  name="in4" style="width:150px; height:15px;" id="input4" maxlength="12">                
                <input type="text"  name="in5" style="width:150px; height:15px;" id="input5"  maxlength="12">
                <input type="text"  name="in6" style="width:40px; height:15px;" id="input6" maxlength="2">              
            </div>             
        </div>
        <div class="control-group" id="div3">           
            <div class="controls">               
                <input type="text" name="in7" style="width:150px; height:15px;" id="input7" maxlength="12">                
                <input type="text" name="in8" style="width:150px; height:15px;" id="input8" maxlength="12">
                <input type="text" name="in9" style="width:40px; height:15px;" id="input9"  maxlength="2">              
            </div>             
        </div>
        <div class="control-group" id="div4">           
            <div class="controls">               
                <input type="text" name="in10" style="width:150px; height:15px;" id="input10" maxlength="12">                
                <input type="text" name="in11" style="width:150px; height:15px;" id="input11"maxlength="12">
                <input type="text" name="in12" style="width:40px; height:15px;"id="input12" maxlength="2">              
            </div>             
        </div>
        <div class="control-group" id="div5">           
            <div class="controls">               
                <input type="text" name="in13" style="width:150px; height:15px;" id="input13" maxlength="12">                
                <input type="text" name="in14" style="width:150px; height:15px;" id="input14" maxlength="12">
                <input type="text" name="in15" style="width:40px; height:15px;" id="input15" maxlength="2">              
            </div>             
        </div>
    
    <div class="control-group" style="display: none"  id="div6">           
            <div class="controls">               
                <input type="text" name="in16" style="width:150px; height:15px;" id="input13" maxlength="12">                
                <input type="text" name="in17" style="width:150px; height:15px;" id="input14" maxlength="12">
                <input type="text" name="in18" style="width:40px; height:15px;" id="input15" maxlength="2">              
            </div>             
    </div>
                <div class="control-group" style="display: none"  id="div7">           
            <div class="controls">               
                <input type="text" name="in19" style="width:150px; height:15px;" id="input13" maxlength="12">                
                <input type="text" name="in20" style="width:150px; height:15px;" id="input14" maxlength="12">
                <input type="text" name="in21" style="width:40px; height:15px;" id="input15" maxlength="2">              
            </div>             
    </div>
    <div class="control-group" style="display: none"  id="div8">           
            <div class="controls">               
                <input type="text" name="in22" style="width:150px; height:15px;" id="input13" maxlength="12">                
                <input type="text" name="in23" style="width:150px; height:15px;" id="input14" maxlength="12">
                <input type="text" name="in24" style="width:40px; height:15px;" id="input15" maxlength="2">              
            </div>             
    </div>
    <div class="control-group"  style="display: none" id="div9">           
            <div class="controls">               
                <input type="text" name="in25" style="width:150px; height:15px;" id="input13" maxlength="12">                
                <input type="text" name="in26" style="width:150px; height:15px;" id="input14" maxlength="12">
                <input type="text" name="in27" style="width:40px; height:15px;" id="input15" maxlength="2">              
            </div>             
    </div>
            <div class="control-group"  style="display: none" id="div10">           
            <div class="controls">               
                <input type="text" name="in28" style="width:150px; height:15px;" id="input13" maxlength="12">                
                <input type="text" name="in29" style="width:150px; height:15px;" id="input14" maxlength="12">
                <input type="text" name="in30" style="width:40px; height:15px;" id="input15" maxlength="2">              
            </div>             
    </div>
        <div class="form-actions">
            <?php if(!isset($action)) {?> 
                <button type="submit" class="btn btn-primary">添加</button>
            <?php }?>
            <a href="./index.php?r=admin/returnFromAddKey&&page=<?php echo Yii::app()->session['lastPage'];?>" class="btn">返回</a>
        </div>
        </fieldset>
    </form>   
</div>


<script>     
 var divCount = 5;
 var inputCount = 1;
$(document).ready(function(){
    var result = <?php echo "'$result'";?>;
    if(result === '1')
    window.wxc.xcConfirm('添加键位练习成功！', window.wxc.xcConfirm.typeEnum.success);
    else if(result === '0')
    window.wxc.xcConfirm('添加键位练习失败！', window.wxc.xcConfirm.typeEnum.error);
});
$("#myForm").submit(function(){
    var requirements = $("#input")[0].value;
    if(requirements === ""){
        window.wxc.xcConfirm('题目内容不能为空', window.wxc.xcConfirm.typeEnum.info);
        return false;
    }
    var i ,j ,k, y = 3*divCount;
    var patrn = /^[ANIGDZWUEOXB]{1,12}$/;
    var numpatrn =/^[0-9]{1,2}$/;
    for(i = 1 ; i <=y ; i++)
    {
        var input = $("#input" + i)[0].value;
        if(i%3 == 0)
        {
            if(!numpatrn.exec(input))
            {
                j = Math.floor(i/3);               
                window.wxc.xcConfirm('第'+ j +'行第三空循环次数应设为0-100', window.wxc.xcConfirm.typeEnum.info);
               return false;
           }
        }else{
            if(!patrn.exec(input))
            {
                j = Math.floor(i/3)+1;
                k = i%3;
                window.wxc.xcConfirm('第' + j + '行第' + k + '空应输入0-12个A-Z的字母', window.wxc.xcConfirm.typeEnum.info);
                return false;
            }
        }           
    }
    }
);
    function addIn()
    {
        if(divCount<11){
            divCount++;
            $("#div"+divCount).show();
        }else
        {
            window.wxc.xcConfirm("最多添加十个选项", window.wxc.xcConfirm.typeEnum.info);
        }
    }
    function deleteIn()
    {
        if(divCount>1){
            $("#div"+divCount).hide();           
            divCount--;
        }else
        {
            window.wxc.xcConfirm("必须有一个答案", window.wxc.xcConfirm.typeEnum.info);
        }
    }

</script>


