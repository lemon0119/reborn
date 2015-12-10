 <div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
        <li class="nav-header"><i class="icon-navsearch"></i>搜索</li>
        <form action="./index.php?r=teacher/searchKey" method="post">
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
                    <a href="./index.php?r=teacher/AddKey" class="btn_add"></a>
            </li>
        </form>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-knowlage"></i>基础知识</li>
            <li ><a href="./index.php?r=teacher/choiceLst"><i class="icon-font"></i> 选择</a></li>
            <li ><a href="./index.php?r=teacher/fillLst"><i class="icon-text-width"></i> 填空</a></li>
            <li ><a href="./index.php?r=teacher/questionLst"><i class="icon-align-left"></i> 简答</a></li>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-typing"></i>打字练习</li>
            <li class="active"><a href="./index.php?r=teacher/keyLst"><i class="icon-th"></i> 键位练习</a></li>
            <li ><a href="./index.php?r=teacher/lookLst"><i class="icon-eye-open"></i> 看打练习</a></li>
            <li ><a href="./index.php?r=teacher/listenLst"><i class="icon-headphones"></i> 听打练习</a></li>
        </ul>
    </div>
</div>

    
<div class="span9">        
<?php if(!isset($action)) {?>
<h3>编辑键位练习题</h3>
<?php } else if($action == 'look') {?>
<h3>查看键位练习题</h3>
<?php }?>


    <form class="form-horizontal" method="post" action="./index.php?r=teacher/AddKey" id="myForm"> 
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
            
            
        <div class="control-group" > 
        <div class="controls">
            <select  name="category" id="testSelect" style="border-color: #000; color:#000" onchange="changSelect()">
                <option   value="free" selected="selected">自由练习</option>
                                        <option value="speed" >速度练习</option>
                                        <option  value="correct">准确率练习</option>                                        
            </select>
        </div>
            </div>
            
        <div class="control-group" id="div1">   
            <label class="control-label" >二字词练习次数:</label>
            <div class="controls">                                                        
                <input type="text" name="in1" style="width:40px; height:15px;" id="input1" maxlength="2" value="0">               
            </div>             
        </div>
      

            
            <div class="control-group" style="display: none" id="div2">
            <label class="control-label" >速度:</label>
             <div class="controls">
                 <input type="text" name="speed" style="width:40px; height:15px;" id="input2" maxlength="3"  value="0">         
             词/分钟
             </div>            
       </div>
        
            <div class="control-group" style="display: none" id="div3">
             <label class="control-label" >练习时间:</label>
             <div class="controls">
             <input type="text" name="exerciseTime" style="width:40px; height:15px;" id="input3" maxlength="2" value="0">         
             分钟
             </div>            
       </div>       
            
            
         
        <div class="form-actions">
            <?php if(!isset($action)) {?> 
                <button type="submit" class="btn btn-primary">添加</button>
            <?php }?>
            <a href="./index.php?r=teacher/returnFromAddKey&&page=<?php echo Yii::app()->session['lastPage'];?>" class="btn">返回</a>
        </div>
        </fieldset>
    </form>   
</div>
<script>     
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
        window.wxc.xcConfirm('题目内容不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    var i;
    var numpatrn =/^[0-9]{1,2}$/;    
    var numpatrn1 =/^[0-9]{1,3}$/; 
            if($("#testSelect").find("option:selected").val() == "speed"){
                var input2 = $("#input2")[0].value;
                var input3 = $("#input3")[0].value;
            if(input2 == 0)
               {                 
                window.wxc.xcConfirm('速度不能设置为0', window.wxc.xcConfirm.typeEnum.warning);
                return false;
                }
               if(input3 == 0)
               {                 
                window.wxc.xcConfirm('时间不能设置为0', window.wxc.xcConfirm.typeEnum.warning);
                return false;
                } 
                
            if(!numpatrn1.exec(input2))
            {                 
                window.wxc.xcConfirm('速度应设为1-1000', window.wxc.xcConfirm.typeEnum.warning);
                return false;
            } 
         
            if(!numpatrn.exec(input3))
            {                 
                window.wxc.xcConfirm('时间应设为1-100', window.wxc.xcConfirm.typeEnum.warning);
                return false;
            } 
                
                
                
            }else
            {
            var input = $("#input1")[0].value;
            if(input == 0)
            {                 
                window.wxc.xcConfirm('次数不能为0', window.wxc.xcConfirm.typeEnum.warning);
                return false;
            }   
              
            if(!numpatrn.exec(input))
            {                 
                window.wxc.xcConfirm('次数应设为1-100', window.wxc.xcConfirm.typeEnum.warning);
                return false;
            }  
        }            
    }    
);

function changSelect(){
    if($("#testSelect").find("option:selected").val() == "speed"){
        document.getElementById("div1").style.display = "none";
        document.getElementById("div2").style.display = "";
        document.getElementById("div3").style.display = "";
    }else{
        document.getElementById("div1").style.display = "";
        document.getElementById("div2").style.display = "none";
        document.getElementById("div3").style.display = "none";
    }        
}



</script>


