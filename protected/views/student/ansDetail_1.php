<?php require 'ansSideBar.php';?>
<link href="<?php echo CSS_URL; ?>../answer-style.css" rel="stylesheet">
<script src="<?php echo JS_URL;?>exerJS/LCS.js"></script>
<script src="<?php echo JS_URL;?>exerJS/accounting.js"></script>
<div class="span9">
    <div class="hero-unit">
        <table border = '0px' width="100%">
            <tr>
                <td width = '100px' align='center'><?php echo $exer['title']?></td>
                <td width = '100px' align='center'><td align='center'> 正确率：<span id="correct"><?php printf('%2.1f',$correct * 100);echo '%';?></span></td>
            </tr>
            <tr>
                <td colspan='3'>
                    <div class='answer-tip-text1'>作答结果：</div>
                    <div id ="answer" class="answer-question" onselectstart="return false" onscroll="doScrollRight()"></div>
                </td>
            </tr>
            <tr>
                <td colspan='3'>
                    <div class='answer-tip-text2'>正确答案：</div>
                    <div id ="templet" class="answer-question" onselectstart="return false" onscroll="doScrollLeft()"></div>
                </td>
            </tr>
        </table>
    </div>
</div>
<?php
    if(isset(Yii::app()->session['type'])){
        $type = Yii::app()->session['type'];
        echo "<script>var type = '$type';</script>"; 
    }
?>
<script type="text/javascript">
    function load(){
            var url = "./index.php?r=student/preExer&&type=classwork";
        $("#cont").load(url);
    }
    function createFont(element, color, text){
        var father = document.getElementById(element);
        var f = document.createElement("font");
        f.style = "color:"+color+";word-wrap:break-word;white-space:-moz-pre-wrap;";
        f.innerHTML = text;
        father.appendChild(f);
    }
    function doScrollLeft(){
        var divleft = document.getElementById('templet');
        var divright = document.getElementById('answer');
        divright.scrollTop = divleft.scrollTop;
    }
    function doScrollRight(){
        var divleft = document.getElementById('templet');
        var divright = document.getElementById('answer');
        divleft.scrollTop = divright.scrollTop;
    }
    function start(){
        var lcs = new LCS('<?php echo ($exer['content']);?>', '<?php echo ($answer);?>');        
        if(lcs == null)
            return;
        lcs.doLCS();
        var tem = lcs.getStrOrg(1);
        var ans = lcs.getStrOrg(2);
        var modTem = lcs.getSubString(1);
        var modAns = lcs.getSubString(2);
        var correct = lcs.getSubString(3).length / lcs.getStrOrg(1).length;
        displayTemp('templet', tem, modTem);
        displayTemp('answer', ans, modAns);
    }
    function displayTemp(id, temp, modTem){
        var flag = false;
        var j = 0;
        for(var i = 0; i < modTem.length && i < temp.length; i++){
            if(modTem[i] === '*'){
                if(!flag){
                    flag = true;
                    createFont(id,'#000000',temp.substring(j, i));
                    j = i;
                }
            } else {
                if(flag){
                    flag = false;
                    createFont(id,'#ff0000',temp.substring(j, i));
                    j = i;
                }
            }
        }
        if(j < i){
            if(!flag)
                createFont(id,'#000000',temp.substring(j, i));
            else
                createFont(id,'#ff0000',temp.substring(j, i));
        }
        if(i < temp.length)
            createFont(id,'#ff0000',temp.substr(i));
        if(i < modTem.length)
            createFont(id,'#ff0000',modTem.substr(i));
    }
    start();
</script>