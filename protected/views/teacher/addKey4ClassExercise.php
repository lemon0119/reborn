<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li style="margin-top:10px">
                <?php if (isset($_GET['nobar'])) { ?>
                <?php } else { ?>
                    <button class="btn_4superbig"  onclick="window.location.href = './index.php?r=teacher/startCourse&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>'"  >返&nbsp;&nbsp;&nbsp;回</button>
                <?php } ?>
            </li>
            </form>
            <li class="divider"></li>
            <li class="nav-header"><i class="icon-typing" style="position:relative;bottom:5px;left:"></i>课堂练习</li>
            <?php if (isset($_GET['nobar'])) { ?>
                <li class="active"><a href="./index.php?r=teacher/classExercise4Type&&nobar=yes&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>"><i class="icon-th" style="position:relative;bottom:5px;left:"></i> 键打练习</a></li>
                <li ><a href="./index.php?r=teacher/classExercise4Look&&nobar=yes&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>"><i class="icon-eye-open" style="position:relative;bottom:5px;left:"></i> 看打练习</a></li>
                <li ><a href="./index.php?r=teacher/classExercise4Listen&&nobar=yes&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>"><i class="icon-headphones" style="position:relative;bottom:5px;left:"></i> 听打练习</a></li>
            <?php } else { ?>
                <li class="active"><a href="./index.php?r=teacher/classExercise4Type&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>"><i class="icon-th" style="position:relative;bottom:5px;left:"></i> 键打练习</a></li>
                <li ><a href="./index.php?r=teacher/classExercise4Look&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>"><i class="icon-eye-open" style="position:relative;bottom:5px;left:"></i> 看打练习</a></li>
                <li ><a href="./index.php?r=teacher/classExercise4Listen&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>"><i class="icon-headphones" style="position:relative;bottom:5px;left:"></i> 听打练习</a></li>
            <?php } ?>

        </ul>
    </div>
</div>


<div class="span9" id="addKey" >        
    <?php if (!isset($action)) { ?>
        <h3>编辑键打练习题</h3>
    <?php } else if ($action == 'look') { ?>
        <h3>查看键打练习题</h3>
    <?php } ?>

    <?php if (isset($_GET['nobar'])) { ?>
        <form class="form-horizontal" method="post" action="./index.php?r=teacher/addKey4ClassExercise&&nobar=yes&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>" id="myForm"> 
        <?php } else { ?>
            <form class="form-horizontal" method="post" action="./index.php?r=teacher/addKey4ClassExercise&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>" id="myForm"> 
            <?php } ?>

            <fieldset>
                <?php if (!isset($action)) { ?>
                    <legend>填写题目</legend>
                <?php } else if ($action == 'look') { ?>
                    <legend>查看题目</legend>
                <?php } ?>
                <div class="control-group">
                    <label class="control-label" for="input">题目</label>
                    <div class="controls">
                        <textarea name="title" style="width:450px; height:20px;" id="input"></textarea>
                    </div>
                </div>

                <div class="control-group" > 
                    <label class="control-label" for="input">练习词库</label>
                    <div class="controls" style="padding-top:5px" >  
<!--                        <table id="lib"  style="align:left;">
                        </table>    -->
                        <a href="#" onclick="selectWordLib()" style="float:left;margin-right: 10px">预置词库选择</a>
                        <table id="lib"  style="align:left;float:left">
                        </table> 
                    </div>
                </div>   

<!--                <div class="control-group" > 
                    <label class="control-label" for="input">所有二字词库</label>
                    <div class="controls"  > 

                        <input type="checkbox" onclick="checkAll()" id="all">添加所有二字词库
                    </div>
                </div>         -->

                <div class="control-group" > 
                    <label class="control-label" for="input">练习类型</label>
                    <div class="controls">
                        <input id="free" type="checkbox" value="free" name="free" />
                    <span style=" position: relative;top: 4px">自由练习</span>
                    <input id="speed" type="checkbox" value="speed" name="speed" onchange ="changSelect(this)"/>
                    <span style=" position: relative;top: 4px">速度练习</span>
                    <input id="correct" type="checkbox" value="correct" name="correct"/>
                    <span style=" position: relative;top: 4px">准确率练习</span>
<!--                        <select  name="category" id="testSelect" style="border-color: #000; color:#000" onchange="changSelect()">
                            <option  value="free" selected="selected">自由练习</option>
                            <option  value="speed" >速度练习</option>
                            <option  value="correct">准确率练习</option>                                        
                        </select>-->
                    </div>
                </div>

                <div class="control-group" id="div3">   
                    <label class="control-label" >练习循环次数</label>
                    <div class="controls">                                                        
                        <input type="text" name="in3" style="width:40px; height:15px;" id="input3" maxlength="2" value="1">               
                    </div>             
                </div>            
                <div class="control-group" style="display: none" id="div1">   
                    <label class="control-label" >二字词个数</label>
                    <div class="controls">                                                        
                        <input type="text" name="in1" style="width:40px; height:15px;" id="input1" maxlength="2" value="0">               
                    </div>             
                </div>

                <div class="control-group" style="display: none" id="div2">
                    <label class="control-label" >速度:</label>
                    <div class="controls">
                        <input type="text" name="speed1" style="width:40px; height:15px;" id="input2" maxlength="3"  value="10">         
                        词/分钟
                    </div>            
                </div>

                <div class="form-actions">
                    <?php if (!isset($action)) { ?> 
                        <button type="submit" class="btn btn-primary">添加</button>
                    <?php } ?>
                    <?php if (isset($_GET['nobar'])) { ?>
                        <a class="btn btn-primary" href="./index.php?r=teacher/classExercise4Type&&nobar=yes&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>">返回</a>
                    <?php } else { ?>
                        <a class="btn btn-primary" href="./index.php?r=teacher/classExercise4Type&&classID=<?php echo $_GET['classID']; ?>&&progress=<?php echo $_GET['progress']; ?>&&on=<?php echo $_GET['on']; ?>">返回</a>
                    <?php } ?>
                </div>
            </fieldset>
            <input id="libstr" style="display: none;" name="libstr">
        </form>   
</div>
<div class="span9" id="choiceLib" style="display: none">
    <iframe style="height: 100%;width: 100%;border: 0px;" id="iframe4choiceLib"></iframe>
</div>
<script>
    var inputCount = 1;
    var hasChooseLib = 0;
    $(document).ready(function () {
        var result = <?php echo "'$result'"; ?>;
        if (result === '1')
            window.wxc.xcConfirm('添加键打练习成功！', window.wxc.xcConfirm.typeEnum.success);
        else if (result === '0')
            window.wxc.xcConfirm('添加键打练习失败！', window.wxc.xcConfirm.typeEnum.error);
    });
    $("#myForm").submit(function () {
        <?php if(isset($_GET['nobar'])){?>
        opener.iframReload();
        opener.iframReload();
        <?php }?>
        var requirements = $("#input")[0].value;
        if (requirements === "") {
            window.wxc.xcConfirm('题目内容不能为空', window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }

        var i;
        var numpatrn = /^[1-9][0-9]|[1-9]$/;
        var numpatrn1 = /^1|[0-9]{3}$/;

        var input1 = $("#input1")[0].value;
        var input2 = $("#input2")[0].value;
        var input3 = $("#input3")[0].value;

        if ($("#all").is(':checked')) {
            hasChooseLib = 1;
            if (document.getElementById("libstr").value == "")
                document.getElementById("libstr").value = "lib";
            else
                document.getElementById("libstr").value += "$$lib";

            if (!numpatrn.exec(input1))
            {
                window.wxc.xcConfirm('二字词应设为1-100', window.wxc.xcConfirm.typeEnum.warning);
                return false;
            }
        }

        if (hasChooseLib == 0)
        {
            window.wxc.xcConfirm('请选择词库', window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }
        if (!numpatrn.exec(input3))
        {
            window.wxc.xcConfirm('循环次数应设为1-100', window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }
        var free_input = document.getElementById("free");
        var correct_input = document.getElementById("correct");
        var speed_input = document.getElementById("speed");
        if(free_input.checked == false && correct_input.checked == false && speed_input.checked == false) {
            window.wxc.xcConfirm('至少选中一种模式', window.wxc.xcConfirm.typeEnum.warning);
            return false;
        }
      
            if (!numpatrn.exec(input2))
            {
                window.wxc.xcConfirm('速度应设为1-1000', window.wxc.xcConfirm.typeEnum.warning);
                return false;
            }
    }
    );

    function changSelect(obj) {
        if(obj.checked == true) {
          document.getElementById("div2").style.display = "";
        }else {
          document.getElementById("div2").style.display = "none";
        }
    }

    function checkAll() {
        if ($("#all").is(':checked')) {
            document.getElementById("div1").style.display = "";
        } else
        {
            document.getElementById("div1").style.display = "none";
        }
    }

    function selectWordLib() {

        $("#addKey").attr("style", "display:none");
        $("#choiceLib").removeAttr("style");
        $("#iframe4choiceLib").attr("src", "./index.php?r=teacher/SelectWordLib&&libstr=" + document.getElementById("libstr").value);
    }

    function getDivAddKeyBack() {
        $("#choiceLib").attr("style", "display:none");
        $("#addKey").removeAttr("style");
        $("#iframe4choiceLib").removeAttr("src");
    }

    function getContent(libs) {
        var Table = document.getElementById("lib");
        hasChooseLib = 0;
        var rowNum = Table.rows.length;
        for (i = 0; i < rowNum; i++)
        {
            Table.deleteRow(i);
        }
        var libstr = "";
        for (var i = 0; i < libs.length; i++) {
            if (i == 0)
                libstr += libs[i];
            else
                libstr += "$$" + libs[i];

            hasChooseLib = 1;
            if (i % 3 == 0)
            {
                var NewRow = Table.insertRow();
            }
            var NewCell = NewRow.insertCell();
            NewCell.style.width = "250px";
            NewCell.innerHTML = "<span style='float:left'>" + libs[i] + "</span>";
        }
        document.getElementById("libstr").value = libstr;
        document.getElementById("input").value = libs[0];
    }
    function deleteLib(libs){
        window.wxc.xcConfirm("确定要删除吗？", window.wxc.xcConfirm.typeEnum.warning,{
            onOk:function(){
                 $.ajax({
                    type:"POST",
                    async:false,
                    url:"./index.php?r=teacher/deleteWordLib",
                    data:{libs:libs},
                    success:function(data){
                        if(data==1){
                            window.wxc.xcConfirm("删除成功！", window.wxc.xcConfirm.typeEnum.success,{
                                onOk:function(){
                                    $("#iframe4choiceLib").attr("src", "./index.php?r=teacher/SelectWordLib&&libstr=" + document.getElementById("libstr").value);
                                }
                            });
                        }else if(data==0){
                            window.wxc.xcConfirm("只能针对个人词库删除！", window.wxc.xcConfirm.typeEnum.error);
                        }
                    }
                });
            }
        });
    }
    
    function error(result){
            window.wxc.xcConfirm(result, window.wxc.xcConfirm.typeEnum.error);
    }
     function success(result){
            window.wxc.xcConfirm(result, window.wxc.xcConfirm.typeEnum.success,{
                onOk:function(){
                    $("#iframe4choiceLib").attr("src", "./index.php?r=teacher/SelectWordLib&&libstr=" + document.getElementById("libstr").value);
                }
            });
    }
</script>

