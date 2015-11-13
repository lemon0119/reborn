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

<?php $optArr = explode(":",$content);
      $count = round(count($optArr)/3);?>
    
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
            <div class="control-group" id="div1">
            <label class="control-label" >键位码</label>
            <div class="controls">               
                <input type="text" name="in1" style="width:150px; height:15px;" id="input1" maxlength="12" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>0) echo $optArr[0];?>"> <span> ：</span>                 
                <input type="text" name="in2" style="width:150px; height:15px;" id="input2" maxlength="12" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?> value="<?php if ($count>0)echo $optArr[1];?>">
                <input type="text" name="in3" style="width:40px; height:15px;" id="input3" maxlength="2"  <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>0)echo $optArr[2];?>">
                <?php if(!isset($action)) {?> 
                <a class="btn btn-primary"  <?php if(!isset($action)){ echo 'onclick="addIn()"';  }?>><i class="icon-plus-editwork icon-white" ></i></a> <a class="btn btn-primary" <?php if(!isset($action)){ echo 'onclick="deleteIn()" '; }?>><i class="icon-minus icon-white"></i></a>
                <?php }?>
            </div>             
        </div>
            <div class="control-group" id="div2" style="<?php if ($count<2) echo "display:none";?>">           
            <div class="controls">               
                <input type="text"  name="in4" style="width:150px; height:15px;" id="input4" maxlength="12" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?> value="<?php if ($count>1)echo $optArr[3];?>"> <span> ：</span>                 
                <input type="text"  name="in5" style="width:150px; height:15px;" id="input5"  maxlength="12" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>1)echo $optArr[4];?>">
                <input type="text"  name="in6" style="width:40px; height:15px;" id="input6" maxlength="2" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>1)echo $optArr[5];?>">              
            </div>             
        </div>
        <div class="control-group" id="div3" style="<?php if ($count<3) echo "display:none";?>">           
            <div class="controls">               
                <input type="text" name="in7" style="width:150px; height:15px;" id="input7" maxlength="12" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>2)echo $optArr[6];?>"> <span> ：</span>                 
                <input type="text" name="in8" style="width:150px; height:15px;" id="input8" maxlength="12" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>2)echo $optArr[7];?>">
                <input type="text" name="in9" style="width:40px; height:15px;" id="input9"  maxlength="2" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>2)echo $optArr[8];?>">              
            </div>             
        </div>
        <div class="control-group" id="div4" style="<?php if ($count<4) echo "display:none";?>">           
            <div class="controls">               
                <input type="text" name="in10" style="width:150px; height:15px;" id="input10" maxlength="12" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>3)echo $optArr[9];?>"> <span> ：</span>                 
                <input type="text" name="in11" style="width:150px; height:15px;" id="input11"maxlength="12" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>3) echo $optArr[10];?>">
                <input type="text" name="in12" style="width:40px; height:15px;"id="input12" maxlength="2" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>3) echo $optArr[11];?>">              
            </div>             
        </div>
        <div class="control-group" id="div5" style="<?php if ($count<5) echo "display:none";?>">            
            <div class="controls">               
                <input type="text" name="in13" style="width:150px; height:15px;" id="input13" maxlength="12" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>4) echo $optArr[12];?>"> <span> ：</span>                 
                <input type="text" name="in14" style="width:150px; height:15px;" id="input14" maxlength="12" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>4) echo $optArr[13];?>">
                <input type="text" name="in15" style="width:40px; height:15px;" id="input15" maxlength="2" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>4) echo $optArr[14];?>">              
            </div>             
        </div>
    
    <div class="control-group" style="display: none"  id="div6" style="<?php if ($count<6) echo "display:none";?>">           
            <div class="controls">               
                <input type="text" name="in16" style="width:150px; height:15px;" id="input13" maxlength="12" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>5) echo $optArr[15];?>"> <span> ：</span>                 
                <input type="text" name="in17" style="width:150px; height:15px;" id="input14" maxlength="12" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>5) echo $optArr[16];?>">
                <input type="text" name="in18" style="width:40px; height:15px;" id="input15" maxlength="2" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>5) echo $optArr[17];?>">              
            </div>             
    </div>
    <div class="control-group" style="display: none"  id="div7" style="<?php if ($count<7) echo "display:none";?>">           
            <div class="controls">               
                <input type="text" name="in19" style="width:150px; height:15px;" id="input13" maxlength="12" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>6)echo $optArr[18];?>"> <span> ：</span>                
                <input type="text" name="in20" style="width:150px; height:15px;" id="input14" maxlength="12" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>6) echo $optArr[19];?>">
                <input type="text" name="in21" style="width:40px; height:15px;" id="input15" maxlength="2" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>6) echo $optArr[20];?>">              
            </div>             
    </div>
    <div class="control-group" style="display: none"  id="div8" style="<?php if ($count<8) echo "display:none";?>">           
            <div class="controls">               
                <input type="text" name="in22" style="width:150px; height:15px;" id="input13" maxlength="12" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>7)echo $optArr[21];?>"> <span> ：</span>                 
                <input type="text" name="in23" style="width:150px; height:15px;" id="input14" maxlength="12" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>7) echo $optArr[22];?>">
                <input type="text" name="in24" style="width:40px; height:15px;" id="input15" maxlength="2" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>7)echo $optArr[23];?>">              
            </div>             
    </div>
    <div class="control-group"  style="display: none" id="div9" style="<?php if ($count<9) echo "display:none";?>">           
            <div class="controls">               
                <input type="text" name="in25" style="width:150px; height:15px;" id="input13" maxlength="12" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>8)echo $optArr[24];?>"> <span> ：</span>                 
                <input type="text" name="in26" style="width:150px; height:15px;" id="input14" maxlength="12" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>8)echo $optArr[25];?>">
                <input type="text" name="in27" style="width:40px; height:15px;" id="input15" maxlength="2" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>8)echo $optArr[26];?>">              
            </div>             
    </div>
    <div class="control-group"  style="display: none" id="div10" style="<?php if ($count<10) echo "display:none";?>">           
            <div class="controls">               
                <input type="text" name="in28" style="width:150px; height:15px;" id="input13" maxlength="12" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>9)echo $optArr[27];?>"> <span> ：</span>                 
                <input type="text" name="in29" style="width:150px; height:15px;" id="input14" maxlength="12" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>9) echo $optArr[28];?>">
                <input type="text" name="in30" style="width:40px; height:15px;" id="input15" maxlength="2" <?php if(isset($action)){ if($action=='look'){echo 'disabled="disabled"'; } }?>value="<?php if ($count>9) echo $optArr[29];?>">              
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
            echo " window.wxc.xcConfirm('$result', window.wxc.xcConfirm.typeEnum.info);";?>
});
 var divCount = <?php echo $count?>;
 var inputCount = 1;
$("#myForm").submit(function(){
    var requirements = $("#input")[0].value;
    if(requirements === ""){
        	window.wxc.xcConfirm('题目内容不能为空', window.wxc.xcConfirm.typeEnum.warning);
        return false;
    }
    var i ,j ,k, y = 3*divCount;
    var patrn = /^[A-Z]{1,12}$/;
    var numpatrn =/^[0-9]{1,2}$/;
    for(i = 1 ; i <=y ; i++)
    {
        var input = $("#input" + i)[0].value;
        if(i%3 == 0)
        {
            if(!numpatrn.exec(input))
            {
                j = Math.floor(i/3);               
                	window.wxc.xcConfirm('第'+ j +'行第三空循环次数应设为0-100', window.wxc.xcConfirm.typeEnum.warning);
               return false;
           }
        }else{
            if(!patrn.exec(input))
            {
                j = Math.floor(i/3)+1;
                k = i%3;
                	window.wxc.xcConfirm('第' + j + '行第' + k + '空应输入0-12个A-Z的字母', window.wxc.xcConfirm.typeEnum.warning);
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
            	window.wxc.xcConfirm("最多添加十个选项", window.wxc.xcConfirm.typeEnum.warning);
        }
    }
    function deleteIn()
    {
        if(divCount>1){
            $("#div"+divCount).hide();
            var num = 1;
            divCount--;
            for(;num<=3;num++)
            {
                count = 3*divCount + num;
                $("#input"+count).val("");
            }
        }else
        {
            	window.wxc.xcConfirm("必须有一个答案", window.wxc.xcConfirm.typeEnum.warning);
        }
    }

</script>



