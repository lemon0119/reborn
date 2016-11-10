<?php require 'ansSideBar.php'; ?>
<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<script src="<?php echo JS_URL; ?>exerJS/LCS.js"></script>
<script src="<?php echo JS_URL; ?>exerJS/accounting.js"></script>
<div class="span9" style="height: 570px">
    <div class="hero-unit">
        <table border = '0px' width="100%">
            <h2><?php
                if ($type == 'look')
                    echo '看打练习';
                else
                    echo '听打练习';
                ?></h2>
            <tr>
                <td width = '50%' colspan="6" align='center'>题目：<?php echo $exer['title'] ?></td>
<!--                <td width = '100px' align='center'><td align='center'> 正确率：<span id="correct"><?php
                        //printf('%2.1f', $correct);
                        //echo '%';
                        ?></span></td>-->
            </tr>
            <tr>
                <td align='center'>正确率(%)</td>
                <td align='center'>正确字数</td>
                <td align='center'>打错字数</td>
                <td align='center'>少打字数</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><span id="correct"><?php printf('%2.1f',$correct); ?></span></td>
                <td id="correct_Number"><?php printf($correct_Number); ?></td>
                <td><span id="error_number"></span></td>
                <td><span id="missing_number"></span></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td align='center' >多打字数</td>
                <td align='center'>作答字数</td>
                <td align='center'>标准字数</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><span id="redundant_number"></span></td>
                <td id="answer_number"><?php printf($answer_Number); ?></td>
                <td id="standard_number"><?php printf($standard_Number); ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
             <?php if ($type == 'listen'){ ?>
                 <tr>
                <td align='center' colspan="2">标准文本忽略符号数</td>
                <td align='center' colspan="2">比对文本忽略符号数</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2"><span id="standard_lgnore_symbol"></span></td>
                <td colspan="2"><span id="answer_lgnore_symbol"></span></td>
                <td colspan="2"></td>
            </tr>
             <?php } ?>
            <input id="text" type="hidden" value="<?php
            $str2 = str_replace("\n", "<br/>", $answer);
            $str2 = str_replace("\r", "", $str2);
            $str2 = str_replace(" ", "", $str2);
//            echo $str2;
            ?>"/>
            <input  id="content" type="hidden" style="height: 5px;" value="<?php
            $str = str_replace("\n", "<br/>", $exer['content']);
            $str = str_replace("\r", "", $str);
            $str = str_replace(" ", "", $str);
//            echo $str;
            ?>">
            <tr>
                <td colspan='6'>
                    <br>
                    <div class='answer-tip-text1'>作 答 结 果：</div>
                    <div style="text-align: left;width: 100%" id ="answer" class="answer-question" onselectstart="return false" onscroll="doScrollRight()">
                        <font id="currentContent"></font>
                    </div>
                </td>
            </tr>
<!--            <tr>
                <td colspan='6'>
                    <div class='answer-tip-text2'>正确答案：</div>
                    <div style="text-align: left;width: 100%" id ="templet" class="answer-question" onselectstart="return false" onscroll="doScrollLeft()">
                        <font id="originalContent"></font>
                    </div>
                </td>
            </tr>-->
        </table>

        <div id ="templet" style="text-align: left;height: 260px" class="questionBlock" front-size ="25px" onselectstart="return false">
        </div>
    </div>
</div>
<?php
if (isset(Yii::app()->session['type'])) {
    $type = Yii::app()->session['type'];
    echo "<script>var type = '$type';</script>";
}
?>
<script type="text/javascript">
    if('<?php echo $type;?>'==="look"){
        var currentContent = '<?php echo Tool::filterContentOfInputWithYaweiCode($str2); ?>';
        var originalContent = '<?php echo $str; ?>';
        console.log(currentContent.length);
    }else{
        var currentContent1 = '<?php echo Tool::filterContentOfInputWithYaweiCode($str2); ?>';
        <?php $str1= Tool::filterContentOfInputWithYaweiCode($str2);?>
        var currentContent='<?php echo Tool::removeCharacter($str1) ?>';
        var character_current=currentContent1.length-currentContent.length;
        var originalContent1 = '<?php echo $str; ?>';
        var originalContent='<?php echo Tool::removeCharacter($str) ?>';
        var character_original=originalContent1.length-originalContent.length;
    }
    
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
    if('<?php echo $type;?>'==="listen"){
        
        var more_count=((currentContent.length-originalContent.length)<0) ? 0 : currentContent.length-originalContent.length;
        var correctData=((remove_char_correct-more_count)<0 ? 0 : remove_char_correct-more_count)/originalContent.length;
        correct_rate=Math.round(correctData*100);
        $("#correct").html(correct_rate);
    }
    
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
    
    console.log(currentContent);
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
    if(i==flag[j]){
        currentInnerHTML += '<font style="color:red">' +"(" + right_content[j] + ")" + '</font>';
    }
    
//    var maxlength=currentContent.length < originalContent.length ? currentContent.length : originalContent.length;
//    for (var i = 0; i < maxlength; i++) {
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

    function load() {
        var url = "./index.php?r=student/preExer&&type=classwork";
        $("#cont").load(url);
    }
//    function createAnswerFont(element, color, text){
//        var father = document.getElementById(element);
//        var f = document.createElement("font");
//        f.style = "color:"+color+";word-wrap:break-word;white-space:-moz-pre-wrap;";
//        f.innerHTML = text;
//        father.appendChild(f);
//    }
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
        //var rightKey = '<?php //echo Tool::filterKeyContent($exer['content']);    ?>'.split(" ");
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
                    createFont4Answer('answer', '#000000', answer_right);
                    answer_right = '';
                }
            }
            answerSingle = '';
        }
        createFont4Answer('answer', '#808080', answer_right);
        answer_right = '';
        createFont4Answer('answer', '#ff0000', answer_wrong);

    }
//    function displayTemp(id, temp, modTem) {
//        var flag = false;
//        var j = 0;
//        for (var i = 0; i < modTem.length && i < temp.length; i++) {
//            if (modTem[i] === '*') {
//                if (!flag) {
//                    flag = true;
//                    createAnswerFont(id, '#000000', temp.substring(j, i));
//                    j = i;
//                }
//            } else {
//                if (flag) {
//                    flag = false;
//                    createAnswerFont(id, '#ff0000', temp.substring(j, i));
//                    j = i;
//                }
//            }
//        }
//        if (j < i) {
//            if (!flag)
//                createAnswerFont(id, '#000000', temp.substring(j, i));
//            else
//                createAnswerFont(id, '#ff0000', temp.substring(j, i));
//        }
//        if (i < temp.length)
//            createAnswerFont(id, '#ff0000', temp.substr(i));
//        if (i < modTem.length)
//            createAnswerFont(id, '#ff0000', modTem.substr(i));
//    }
<?php
if (isset($_GET['type'])) {
    if ($_GET['type'] === 'look') {
        ?>
            $(document).ready(function () {
                $("li#li-look-<?php echo $exer['exerciseID']; ?>").attr('class', 'active');
                // start();
            });
    <?php } else if ($_GET['type'] === 'listen') { ?>
            $(document).ready(function () {
                $("li#li-listen-<?php echo $exer['exerciseID']; ?>").attr('class', 'active');
                //start();
            });
        <?php
    }
} else {
    ?>
        // start();
<?php }
?>

</script>