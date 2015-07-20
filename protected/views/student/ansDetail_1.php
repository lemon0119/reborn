<?php require 'ansSideBar.php';?>
<div class="span9">
    <div class="hero-unit" style="width: 900px">
        <table border = '0px' width="900px">
            <tr>
                <td width = '100px' align='center'><h3><?php echo $exer['title']?></h3></td>
                <td>　</td>
                <td width = '100px' align='center'><h3>您的答案</h3></td>
            </tr>
            <tr>
                <td><div id ="templet" class="compareBlock" onselectstart="return false" onscroll="doScrollLeft()"></div></td>
                <td>　</td>
                <td><div id ="answer" class="compareBlock" onselectstart="return false" onscroll="doScrollRight()"></div></td>
            </tr>
            <tr>
                <td align='center'> 正确率：<span id="correct"></span></td> 
                <td > </td>
                <td align='center'> <button class="btn btn-primary btn-cont" onclick="load()"> 确定</button></td>
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
        f.style = "color:"+color;
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
        lcs.doLCS();
        var tem = lcs.getStrOrg(1);
        var ans = lcs.getStrOrg(2);
        var modTem = lcs.getSubString(1);
        var modAns = lcs.getSubString(2);
        var correct = lcs.getSubString(3).length / lcs.getStrOrg(1).length;
        createFont('correct','#000000',100 * accounting.toFixed(correct, 3) + '%');
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