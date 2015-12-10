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
                    <option value="title">题目名</option>
                </select>
            </li>
            <li>
                    <input name="value" type="text" class="search-query span2" placeholder="Search" />
            </li>
            <li style="margin-top:10px">
                    <button type="submit" class="btn_serch"></button>
                    <a href="./index.php?r=teacher/addKey" class="btn_add"></a>
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

<?php 
     $arr = explode("$$", $content);
     $count = count($arr);
             ?>
    
<div class="span9">        
<?php if(!isset($action)) {?>
<legend><h3>编辑键位练习题</h3></legend>
<?php } else if($action == 'look') {?>
<legend><h3>查看键位练习题</h3></legend>
<?php }?>

    <form class="form-horizontal" method="post" action="./index.php?r=teacher/editKeyInfo&&exerciseID=<?php echo $exerciseID;?>" id="myForm"> 
        <fieldset>
        
        <div class="control-group">
            <label class="control-label" for="input01">题目</label>
            <div class="controls">
                <textarea name="title" style="width:450px; height:20px;" id="input" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>><?php echo $title; ?></textarea>
            </div>
        </div>
            
            
         <div class="control-group" > 
        <div class="controls">
            <select  name="category" id="testSelect" style="border-color: #000; color:#000" onchange="changSelect()">
                <option   value="free" <?php if($category == "free") echo "selected='selected'"?>>自由练习</option>
                                        <option  <?php if($category == "speed") echo "selected='selected'"?> value="speed" >速度练习</option>
                                        <option   <?php if($category == "correct") echo "selected='selected'"?> value="correct">准确率练习</option>                                        
            </select>
        </div>
            </div>
            
        <div class="control-group" id="div1" <?php if($category == "speed") echo "style='display:none'"?>>   
            <label class="control-label" >二字词练习次数:</label>
            <div class="controls">                                                        
                <input type="text" name="in1" style="width:40px; height:15px;" id="input1" maxlength="2" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php echo $count;?>">               
            </div>             
        </div>
          
            <div class="control-group"  id="div2" <?php if($category != "speed") echo "style='display:none'"?>>
            <label class="control-label" >速度:</label>
             <div class="controls">
                 <input type="text" name="speed" style="width:40px; height:15px;" id="input2" maxlength="3"  <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php echo $speed;?>">         
             词/分钟
             </div>            
       </div>
        
            <div class="control-group"  id="div3" <?php if($category != "speed") echo "style='display:none'"?>>
             <label class="control-label" >练习时间:</label>
             <div class="controls">
             <input type="text" name="exerciseTime" style="width:40px; height:15px;" id="input3" maxlength="2" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php echo $exerciseTime;?>">         
             分钟
             </div>            
       </div>       
            
            
            
            
        <div class="form-actions">
            <?php if(!isset($action)) {?> 
                <button type="submit" class="btn btn-primary">修改</button>
            <?php }?>
            <a href="./index.php?r=teacher/keyLst" class="btn">返回</a>
        </div>
        </fieldset>
    </form>   
</div>
<script>     
 $(document).ready(function(){
    <?php if(isset($result))
            echo " window.wxc.xcConfirm('$result', window.wxc.xcConfirm.typeEnum.info);";?>sssssd                                           
});
 var divCount = <?php echo $count?>;
 var inputCount = 1;
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
                
                
                
            }else{
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



