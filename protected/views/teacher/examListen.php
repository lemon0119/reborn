<link href="@import<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<script src="<?php echo JS_URL; ?>exerJS/LCS.js"></script>
<script src="<?php echo JS_URL; ?>exerJS/accounting.js"></script>
<style type="text/css">
td{ padding-left:0px;}
</style>
<?php
require 'examAnsSideBar.php';
?>
<div class="span9" style="height: 574px">
    <div id="ziji">
        <div class="hero-unit">
            <h2>听打练习</h2> 
            <?php
            if ($ansWork['answer'] == "") {
                echo "<font color=red>未作答</font>";
                echo '</br>';
            }
            ?>
            <input id="text" type="hidden" value="<?php
            $str2 = str_replace("\n", "<br/>", $ansWork['answer']);
            $str2 = str_replace("\r", "", $str2);
            $str2 = str_replace(" ", "", $str2);
//            echo $str2;
            ?>"/>
            <input id="content" type="hidden" style="height: 5px;" value="<?php
            $str = str_replace("\n", "<br/>", $exer['content']);
            $str = str_replace("\r", "", $str);
            $str = str_replace(" ", "", $str);
//            echo $str;
            ?>">

            <table border = '0px' width="100%" >
                <tr>
                    <td colspan='4'> <?php echo $exer['title'] ?></td>
<!--                    <td width = '100px' align='center'><td align='center'> 正确率：<span id="correct"><?php// printf('%2.1f', $correct);
            //echo '%'; ?></span></td>-->
                </tr>
                <tr>
                    <td>正确率(%)</td>
                <td>正确字数</td>
                <td>打错字数</td>
                <td>少打字数</td>
                </tr>
                <tr>
                <td><span id="correct"><?php printf('%2.1f',$correct); ?></span></td>
                <td id="correct_Number"><?php printf($correct_Number); ?></td>
                <td><span id="error_number"></span></td>
                <td><span id="missing_number"></span></td>
                </tr>
                <tr>
                <td >多打字数</td>
                <td>作答字数</td>
                <td>标准字数</td>
                <td></td>
                </tr>
                 <tr>
                <td><span id="redundant_number"></span></td>
                <td id="answer_number"><?php printf($answer_Number); ?></td>
                <td align="left" id="standard_number"><div style="position:absolute;left:0px;"><?php printf($standard_Number); ?></div></td>
                <td></td>
                </tr>
                <tr>
                <td colspan="2">标准文本忽略符号数</td>
                <td colspan="2">比对文本忽略符号数</td>
                </tr>
                <tr>
                <td colspan="2"><span id="standard_lgnore_symbol"></span></td>
                <td colspan="2"><span id="answer_lgnore_symbol"></span></td>
                 </tr>
                <tr>
                    <td colspan='4'>
                        <div class='answer-tip-text1'>作答结果：</div>
                        <div id ="answer" style="text-align: left;min-width: 99%"  class="answer-question" onselectstart="return false" onscroll="doScrollRight()">
                            <font id="currentContent"></font>
                        </div>
                    </td>
                </tr>
<!--                <tr>
                    <td colspan='6'>
                        <div class='answer-tip-text2'>正确答案：</div>
                        <div id ="templet" style="text-align: left;min-width: 99%"  class="answer-question" onselectstart="return false" onscroll="doScrollLeft()">
                            <font id="originalContent"></font>
                        </div>
                    </td>
                </tr>-->
            </table>
            <div id ="templet" style="text-align: left;height: 260px" class="questionBlock" front-size ="25px" onselectstart="return false">
            </div>
        </div>
分数:<?php echo $exam_exercise['score'];?><br/>
   得分:<input type="text" id="input" style="width: 50px" value ="" disabled="disabled">     
    </div>
<!--
手动打分
<button onclick="saveScore(<?php// echo $ansWork['score']?>,<?php// if($ansWork['answerID'] != "") echo $ansWork['answerID'];else echo 1;?>,<?php// if($ansWork['recordID'] != "") echo $ansWork['recordID'];else echo 1;?>,<?php// echo $exam_exercise['examID'];?>,<?php// echo $exerID;?>)" class="btn btn-primary">保存</button>-->
</div>

<textarea id="text1" style="display: none"><?php echo ($exer['content']); ?></textarea>
<textarea id="text2" style="display: none"><?php echo ($ansWork['answer']); ?></textarea>
<?php
if (isset(Yii::app()->session['type'])) {
    $type = Yii::app()->session['type'];
    echo "<script>var type = '$type';</script>";
}
?>
<script type="text/javascript">
     var currentContent1 = '<?php echo Tool::filterContentOfInputWithYaweiCode($str2); ?>';
     <?php $str1= Tool::filterContentOfInputWithYaweiCode($str2);?>
     var currentContent='<?php echo Tool::removeCharacter($str1) ?>';
     var character_current=currentContent1.length-currentContent.length;
     var originalContent1 = '<?php echo $str; ?>';
     var originalContent='<?php echo Tool::removeCharacter($str) ?>';
     var character_original=originalContent1.length-originalContent.length;
    
    var lcs = new LCS(currentContent, originalContent);
    lcs.doLCS();
    var currentFont = document.getElementById('currentContent');
    var originalFont = document.getElementById('originalContent');
    var currentLCS = lcs.getSubString(1);
    var originalLCS = lcs.getSubString(2);
    var correct_rate=0;
    var standard_number=originalContent.length;
    var answer_number=currentContent.length;
    var remove_char_correct=lcs.getSubString(3).length;
    var more_count=((currentContent.length-originalContent.length)<0) ? 0 : currentContent.length-originalContent.length;
    var correctData=((remove_char_correct-more_count)<0 ? 0 : remove_char_correct-more_count)/originalContent.length;
    correct_rate=Math.round(correctData*100);
    $("#correct").html(correct_rate);
    
    var currentInnerHTML='';
    var originalInnerHTML='';
    
    var right_content=[];
    var flag=[];
    var j=0;
    var k=0;
    var right_length=[];
    var error_flag=[];
    var e=0;
    var error_number=0;
    var missing_number=0;
    var redundant_number=0;
    right_length[e]=0;
    for (var l = 0; l < currentContent.length; l++) {
        if (typeof (currentContent[l]) !== 'undefined') {
            if (currentContent[l] !== currentLCS[l] && currentLCS[l]!=='`') {
                right_length[e]++;
                if(currentContent[l-1] === currentLCS[l-1]){
                    error_flag[e]=l;
                }
                if(currentContent[l+1] === currentLCS[l+1]){
                    right_length[++e]=0;
                }
            }
        }
    }
    for (var i = 0; i < originalContent.length; i++) {
        if (typeof (originalContent[i]) !== 'undefined') {
            if (originalContent[i] !== originalLCS[i]) {
                originalInnerHTML += '<font style="color:#f44336">' + originalContent[i] + '</font>';
                if(originalLCS[i+1] === "`" || originalLCS[i] === "`"){
                    flag[j]=window.G_a[j];
                    j++;
                }
                if(originalContent[i-1] === originalLCS[i-1]){
                    right_content[k] =originalContent[i];
                    if(originalLCS[i] === "`"){
                        k++;
                    }
                }else if(originalContent[i-1] !== originalLCS[i-1] && originalContent[i+1] !== originalLCS[i+1]){
                    right_content[k] +=originalContent[i];
                }else{
                    right_content[k] +=originalContent[i];
                    k++;
                }
            } else {
                originalInnerHTML += '<font style="color:#727272">' + originalContent[i] + '</font>';
            }
        }
    }
    j=0;
    e=0;
    var e_flag=0;
    for (var i = 0; i < currentContent.length; i++) {
        if (typeof (currentContent[i]) !== 'undefined') {
            if (currentContent[i] !== currentLCS[i] && currentLCS[i]!=='`') {
                if(currentLCS[i] === '~'){
                    redundant_number++;
                    currentInnerHTML += '<font style="color:blue"><s>' + currentContent[i] + '</s></font>';
                }else if(typeof (right_content[j-1 ]) !== 'undefined' && i-right_content[j-1 ].length === error_flag[e_flag-1] ){
                    while(currentContent[i] !== currentLCS[i]){
                        redundant_number++;
                        currentInnerHTML += '<font style="color:blue"><s>' + currentContent[i] + '</s></font>';
                        i++;
                    }
                    i--;
                }else{
                    currentInnerHTML += '<font style="color:#f44336">' + currentContent[i] + '</font>';
                }
            } else if(currentLCS[i]==='`'){
                redundant_number++;
                currentInnerHTML += '<font style="color:blue"><s>' + currentContent[i] + '</s></font>';
            }else if(flag[j]===0){
                for(var miss=0;miss<right_content[j].length;miss++){
                    missing_number++;
                }
                currentInnerHTML += '<font style="color:green"><u>' + right_content[j] +  '</u></font>';
                currentInnerHTML += '<font style="color:#727272">' + currentContent[i] + '</font>';
                j++;
            }else{
                currentInnerHTML += '<font style="color:#727272">' + currentContent[i] + '</font>';
            }
        }
        if(typeof (right_content[j]) !== 'undefined'){
        if(i+1===flag[j] && currentContent[i] !== currentLCS[i] || i-right_content[j].length+1 === error_flag[e_flag] && i-right_content[j].length+1>=0){
            if(right_content[j].length > right_length[e]){
                //少打
                currentInnerHTML += '<font style="color:red">'+"("+'</font>';

                for(var err=0; err < right_length[e];err++){
                    error_number++;
                    currentInnerHTML += '<font style="color:red">'+ right_content[j][err] +'</font>';
                }
                currentInnerHTML += '<font style="color:red">'+")"+'</font>';
                while(err < right_content[j].length){
                    missing_number++;
                    currentInnerHTML += '<font style="color:green"><u>' + right_content[j][err++] + '</u></font>';
                }
            }else if(right_content[j].length < right_length[e] ){
                //多打
                currentInnerHTML += '<font style="color:red">'+"("+'</font>';
                for(err=0;err < right_content[j].length;err++){
                    error_number++;
                    currentInnerHTML += '<font style="color:#f44336">' + right_content[j][err] + '</font>';
                }
                currentInnerHTML += '<font style="color:red">'+")"+'</font>';
            }else{
                for(err=0;err<right_content[j].length;err++){
                    error_number++;
                }
                currentInnerHTML += '<font style="color:red">'+"(" + right_content[j] + ")"+'</font>';
            }
            e++;
            e_flag++;
            j++;
        }else if(i+1===flag[j] && typeof (right_content[j]) !== 'undefined'){
            for(err=0;err<right_content[j].length;err++){
                missing_number++;
            }
            currentInnerHTML += '<font style="color:green"><u>' + right_content[j] + '</u></font>';
            j++;
        }
        }
    }
    $("#error_number").html(error_number);
    $("#missing_number").html(missing_number);
    $("#redundant_number").html(redundant_number);
        standard_number=originalContent.length;
        answer_number=currentContent.length;
        $("#standard_lgnore_symbol").html(character_original);
        $("#answer_lgnore_symbol").html(character_current);
        $("#standard_number").html(standard_number);
        $("#answer_number").html(answer_number);
        $("#correct_Number").html(remove_char_correct);
//    for (var i = 0; i < currentContent.length; i++) {
//        if (typeof (currentContent[i]) !== 'undefined') {
//            if (currentContent[i] !== currentLCS[i]) {
//                currentInnerHTML += '<font style="color:#f44336">' + currentContent[i] + '</font>';
//            } else {
//                currentInnerHTML += '<font style="color:#727272">' + currentContent[i] + '</font>';
//            }
//        }
//    }
//    for (var i = 0; i < originalContent.length; i++) {
//        if (typeof (originalContent[i]) !== 'undefined') {
//            if (originalContent[i] !== originalLCS[i]) {
//                originalInnerHTML += '<font style="color:#f44336">' + originalContent[i] + '</font>';
//            } else {
//                originalInnerHTML += '<font style="color:#727272">' + originalContent[i] + '</font>';
//            }
//        }
//    }
    currentFont.innerHTML = currentInnerHTML;
//    originalFont.innerHTML = originalInnerHTML;
    
    $(document).ready(function(){
        $.ajax({
            type:"POST",
            dataType:"json",
            url:"index.php?r=api/answerDataSave",
            data:{error_Number:error_number,missing_Number:missing_number,redundant_Number:redundant_number,answerID:<?php echo $answer_id;?>,
            standard_lgnore_symbol:character_original,answer_lgnore_symbol:character_current,correct_Answer:correct_rate},
            success:function(){
            },
            error: function (xhr) {
                console.log(xhr, "Failed");
            }
        });
    });
    window.onload=function(){
   ssss();
}
    
    $(document).ready(function () {
        $("li#li-listen-<?php echo $exer['exerciseID']; ?>").attr('class', 'active');
        $("#score").html(<?php echo $score; ?>);
        saveScore(<?php  if($ansWork['answerID'] != "") echo $ansWork['answerID'];else echo 1; ?>);
        //start();
    });
    function load() {
        var url = "./index.php?r=student/preExer&&type=classwork";
        $("#cont").load(url);
    }

    var briefCode = "";
    var briefOriginalYaweiCode = "";
    var briefType = "";
    $(document).ready(function () {
        window.G_isLook = 1;
        $.ajax({
            type: "POST",
            url: "index.php?r=api/getBrief",
            async: false,
            data: {},
            success: function (data) {
                briefCode = (data.split("$")[0]).split("&");
                briefOriginalYaweiCode = (data.split("$")[1]).split("&");
                briefType = (data.split("$")[2]).split("&");
            },
            error: function (xhr, type, exception) {
                console.log('GetAverageSpeed error', type);
                console.log(xhr, "Failed");
                console.log(exception, "exception");
            }
        });
    });

    function checkYaweiCode(content) {
        for (var i = 0; i < briefCode.length; i++) {
            if (content.content.indexOf(briefCode[i]) >= 0) {
                var re = new RegExp(briefCode[i], "g");
                if (briefCode[i].length === 2) {
                    if (briefType[i] == 'X') {
                        content.content = content.content.replace(re, "<span style='border-bottom:1px solid blue'>" + briefCode[i] + "</span>");
                    } else if (briefType[i] == 'W') {
                        content.content = content.content.replace(re, "<span style='border-bottom:3px solid blue'>" + briefCode[i] + "</span>");
                    } else {
                        content.content = content.content.replace(re, "<span style='border-bottom:2px solid green'>" + briefCode[i] + "</span>");
                    }
                } else if (briefCode[i].length === 3) {
                    content.content = content.content.replace(re, "<span style='border-bottom:3px solid #0090b0'>" + briefCode[i] + "</span>");
                } else if (briefCode[i].length === 4) {
                    content.content = content.content.replace(re, "<span style='border-bottom:5px solid green'>" + briefCode[i] + "</span>");
                } else if (briefCode[i].length > 4) {
                    content.content = content.content.replace(re, "<span style='border-bottom:5px solid #FF84BA'>" + briefCode[i] + "</span>");
                }
            }
        }
    }


//    function createFont(element, color, text){
//        var father = document.getElementById(element);
//        var f = document.createElement("font");
//        f.style = "color:"+color;
//        f.innerHTML = text;
//        father.appendChild(f);
//    }
    function doScrollLeft() {
        var divleft = document.getElementById('templet');
        var divright = document.getElementById('answer');
        divright.scrollTop = divleft.scrollTop;
    }
    function doScrollRight() {
        var divleft = document.getElementById('templet');
        var divright = document.getElementById('answer');
        divleft.scrollTop = divright.scrollTop;
    }

    function createFont4Answer(element, color, text) {
        var father = document.getElementById(element);
        var f = document.createElement("font");
        f.style = "color:" + color;
        f.innerHTML = text;
        father.appendChild(f);
    }

    function createFont(color, text, code) {
        var father = document.getElementById("templet");
        var f = document.createElement("font");
        var content = {content: ""};
        var isBrief = 0;
        if (color == "#808080") {
            for (var i = 0; i < text.length; i++) {
                if (text[i].length < 3) {
                    for (var j = 0; j < briefOriginalYaweiCode.length; j++) {
                        if (text[i] == briefCode[j]) {
                            isBrief++;
                            if (code[i] == briefOriginalYaweiCode[j].replace(":0", "") || (code[i] == "W:X")) {
                                isBrief--;
                            }
                        }
                    }
                } else {
                    isBrief++;
                }
                if (isBrief === 0) {
                    content.content += text[i];
                } else {
                    content.content += "<span style='color:blue'>" + text[i] + "</span>";
                    isBrief--;
                }
            }
            f.style = "color:" + color;
            content.content = content.content.replace(/`/g, "<br/>").replace(/}/g, "&nbsp;");
            checkYaweiCode(content);
            f.innerHTML = content.content;
            father.appendChild(f);
        } else {
            for (var i = 0; i < text.length; i++) {
                content.content += text[i];
            }
            f.style = "color:" + color;
            //var t = document.createTextNode(text);
            //f.appendChild(t);
            if (color === "#ff0000") {
                content.content = content.content.replace(/`/g, "↓<br/>").replace(/}/g, "█");
                checkYaweiCode(content);
            } else {
                content.content = content.content.replace(/`/g, "<br/>").replace(/}/g, "&nbsp;");
                checkYaweiCode(content);
            }
            f.innerHTML = content.content;
            father.appendChild(f);
        }
    }

    function start() {
        var text_old = '<?php echo $str; ?>';
        var input = "";
        var contentAllArray = $('#text').val().substring(1, $('#text').val().length - 1).split('>,<');
        for (var i = 0; i < contentAllArray.length; i++) {
            var content = contentAllArray[i].substring(0, contentAllArray[i].indexOf('><'));
            input += content;
        }
        var text = text_old.split("");
        var allInput2 = $('#text').val().replace(/\r\n/g, "`").replace(/ /g, "}").split(">,");
        var longIsAgo = 0;
        var old = new Array();
        var oldCode = new Array();
        var isWrong = false;
        var wrong = new Array();
        var div = document.getElementById("templet");
        while (div.hasChildNodes()) {//当div下还存在子节点时 循环继续
            div.removeChild(div.firstChild);
        }
        var length = allInput2.length;
        var countLength = 0;
        for (var i = 0; i < length; i++) {
            if (allInput2[i] !== undefined) {
                var num = allInput2[i].indexOf(">");
                var content = allInput2[i].substring(1, num);
                var yaweiCode = allInput2[i].substring(num + 2, allInput2[i].length).replace(">", "");
                var long = content.length;
                countLength += content.length;
                if (countLength >= text.length) {
                    length = i;
                }
                longIsAgo += long;
                if (text[longIsAgo - long] != undefined) {
                    var stringText = text[longIsAgo - long];
                }
                for (var j = 1; j < long; j++) {
                    if (text[longIsAgo - long + j] != undefined) {
                        stringText += text[longIsAgo - long + j];
                    }
                }
                if (content == stringText) {
                    if (isWrong == true) {
                        isWrong = false;
                        createFont("#ff0000", wrong, "");
                        wrong = new Array();
                        old = new Array();
                        old.push(stringText);
                        oldCode = new Array();
                        oldCode.push(yaweiCode);
                    } else {
                        old.push(stringText);
                        oldCode.push(yaweiCode);
                    }
                } else {
                    if (isWrong == true)
                        wrong.push(stringText);
                    else {
                        isWrong = true;
                        createFont("#808080", old, oldCode);
                        old = new Array();
                        oldCode = new Array();
                        wrong = new Array();
                        wrong.push(stringText);
                    }
                }
            }
        }

        if (countLength !== 0) {
            createFont("#808080", old, oldCode);
            createFont("#ff0000", wrong, "");
        }
        if (input.length < text.length) {
            var left = document.getElementById("content").value.substr(0 - (text.length - longIsAgo));
            createFont("#000000", left, "");
        }

        var right = text_old.split("");
        //var rightKey = '<?php //echo Tool::filterKeyContent($exer['content']);  ?>'.split(" ");
        var answer = input.split("");
        var i, j, sright;
        i = 0;
        sright = false;
        var answer_right = '';
        var answer_wrong = '';
        var answerSingle = '';
        var rightkey = '';
        var right_text = '';
        while (i < answer.length) {
            rightkey = right[i];
            //console.log(rightkey);
            answerSingle = answer[i];
//        if(i===0){
//            console.log(rightkey ===answer[i].substring(0,answer[i].indexOf('><')) );
//            console.log(rightkey.charCodeAt());
//            console.log("懊恼".charCodeAt());
//            console.log(answer[i].substring(0,answer[i].indexOf('><')).charCodeAt());
//            console.log('!'+answer[i].substring(0,answer[i].indexOf('><'))+"!");
//            //console.log("步步"===answer[i].substring(0,answer[i].indexOf('><')));
//        }
            if (rightkey === answerSingle) {
                right_text += (rightkey + '');
                answer_right += (answerSingle + '');
                i++;
                if (sright === false) {
                    sright = true;
                    createFont4Answer('answer', '#ff0000', answer_wrong);
                    answer_wrong = '';
                }
            } else {
                answer_wrong += (answerSingle + '');
                i++;
                if (sright === true) {
                    sright = false;
                    createFont4Answer('answer', '#808080', answer_right);
                    answer_right = '';
                }
            }
            answerSingle = '';
        }
        createFont4Answer('answer', '#808080', answer_right);
        answer_right = '';
        createFont4Answer('answer', '#ff0000', answer_wrong);

    }
//    function start(){
//        var text1 = $('#text1').val();
//        var text2 = $('#text2').val();
//        var lcs = new LCS(text1, text2);
//        if(lcs == null)
//            return;
//        lcs.doLCS();
//        var tem = lcs.getStrOrg(1);
//        var ans = lcs.getStrOrg(2);
//        var modTem = lcs.getSubString(1);
//        var modAns = lcs.getSubString(2);
//        var correct = lcs.getSubString(3).length / lcs.getStrOrg(1).length;
//        displayTemp('templet', tem, modTem);
//        displayTemp('answer', ans, modAns);
//    }
//    start();
//    function displayTemp(id, temp, modTem){
//        var flag = false;
//        var j = 0;
//        for(var i = 0; i < modTem.length && i < temp.length; i++){
//            if(modTem[i] === '*'){
//                if(!flag){
//                    flag = true;
//                    createFont(id,'#000000',temp.substring(j, i));
//                    j = i;
//                }
//            } else {
//                if(flag){
//                    flag = false;
//                    createFont(id,'#ff0000',temp.substring(j, i));
//                    j = i;
//                }
//            }
//        }
//        if(j < i){
//            if(!flag)
//                createFont(id,'#000000',temp.substring(j, i));
//            else
//                createFont(id,'#ff0000',temp.substring(j, i));
//        }
//        if(i < temp.length)
//            createFont(id,'#ff0000',temp.substr(i));
//        if(i < modTem.length)
//            createFont(id,'#ff0000',modTem.substr(i));
//    }  
//   
    
    function saveScore(answerID){     
        var value1 = $("#input")[0].value;
        //alert(value1);
            var user = {
            type:"listen",
            workID:"<?php echo $workID;?>",
            studentID:"<?php echo $studentID;?>",
            accomplish:"<?php echo $accomplish;?>",
            examID:<?php echo $examID;?>,
            score:value1,
            answerID:answerID
            };
            $.ajax({
                type:"POST",
                url:"./index.php?r=teacher/ajaxExam3&&classID=<?php echo $classID?>",
                data:user,
                dataType:"html",
                success:function(html){    
                     $("#ziji").html(html);
                }
            });
    }

//function saveScore(scoreOld, answerID, recordID, examID, exerciseID) {
//        var value1 = $("#input")[0].value;
//        var re = /^([1-9]\d*|[0]{1,1})$/;
//        if (!re.test(value1)) {
//            window.wxc.xcConfirm("分值只能为0、正整数！", window.wxc.xcConfirm.typeEnum.error);
//            $("#input")[0].value = 0;
//            return false;
//        }
//        var totalscore = <?php echo $exam_exercise['score']; ?>;
//        if (value1 > totalscore) {
//            window.wxc.xcConfirm("超过配分上限！", window.wxc.xcConfirm.typeEnum.error);
//        } else {
//            var user = {
//                recordID: recordID,
//                type: "listen",
//                workID: "<?php echo $workID; ?>",
//                studentID: "<?php echo $studentID; ?>",
//                accomplish: "<?php echo $accomplish; ?>",
//                examID: examID,
//                exerciseID: exerciseID,
//                score: $("#input")[0].value,
//                answerID: answerID
//            };
//            $.ajax({
//                type: "POST",
//                url: "./index.php?r=teacher/ajaxExam2&&classID=<?php echo $classID ?>&&accomplish=<?php echo $_GET['accomplish'] ?>",
//                data: user,
//                dataType: "html",
//                success: function (html) {
//
//                    window.wxc.xcConfirm('打分成功！', window.wxc.xcConfirm.typeEnum.success, {
//                        onOk: function () {
//                            location.reload();
//                        }
//                    });
//                },
//                error: function (xhr, type, exception) {
//                    window.wxc.xcConfirm(xhr.responseText, window.wxc.xcConfirm.typeEnum.error);
//                    console.log(xhr, "Failed");
//                }
//            });
//        }
//    }
function ssss(){
        $.ajax({
            type:"POST",
            dataType:"json",
            url:"index.php?r=teacher/getDataCorrect",
            data:{
                answerID:<?php echo $answer_id;?>,
            },
            success:function(data){
            if(data['dataCorrect'].length!=0){
                var exam_score=<?php echo $exam_exercise['score']; ?>;
                var correct_data=(exam_score*data['dataCorrect']*0.01).toFixed(0);
                document.getElementById("input").value=correct_data;
                saveScore(<?php echo $answer_id;?>);
            }
            },
            error: function (xhr) {
                console.log(xhr, "Failed");
            }
        });
}

</script>

