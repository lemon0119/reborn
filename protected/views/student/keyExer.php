<script src="<?php echo JS_URL;?>exerJS/time.js"></script>
<?php  if($isExam == false){ 
require 'suiteSideBar.php';
 }else{ 
    require 'examSideBar.php';
 } ?>
<div class="span9">
    <div class="hero-unit"  align="center">
        <?php Yii::app()->session['exerID'] = $exerOne['exerciseID'];?>
        <table border = '0px'>
            <tr>
                <td width = '200px'><h3><?php echo $exerOne['title']?></h3></td>
                <td width = '200px'>时间：<span id="time">00:00:00</span></td>
                <td width = '200px'>速度：<span id="wordps">0</span> 字/分</td>
            </tr>
        </table>
        <br/>
        <div id ="templet" class ="questionBlock" onselectstart="return false">
            <font id="id_right"style="color:#808080"> </font><font id="id_wrong" style="color:#ff0000"> </font><font id="id_new" style="color:#000000"> </font>
        </div>
        <br/><br/>
        <div style="width: 750px; height: 350px;">
        <?php require  Yii::app()->basePath."\\views\\student\\keyboard.php";?>
        </div>
        <?php
            $host = Yii::app()->request->hostInfo;
            $path = Yii::app()->request->baseUrl;
            $page = '/index.php?r=student/saveAnswer';
            if(isset($_GET['page']))
                $index = $_GET['page'];
            else 
                $index = 1;
            $param = '&page='.$index;
            if(isset(Yii::app()->session['type']))
                $param = $param.'&&type='.Yii::app()->session['type'];
        ?>
    </div>
    <form name='nm_answer_form' id='id_answer_form' method="post" action="<?php echo $host.$path.$page.$param;?>">
        <input id="id_content" type="hidden" value="<?php echo $exerOne['content'];?>">
        <input name="nm_answer" id="id_answer" type="hidden">
        <input name="nm_cost" id="id_cost" type="hidden">
        <input name="nm_correct" id="id_correct" type="hidden">
        <a aline="center" type="button" class="btn btn-primary btn-large" onclick="onSubmit()" style="margin-left: 250px">提交</a>
        <a class="btn btn-large" onclick="restart();" style="margin-left: 250px">重新计时</a>
    </form>
</div>

<script>
    $(document).ready(function(){
        $("li#li-key-<?php echo $exerOne['exerciseID'];?>").attr('class','active');
    });
    
    function getWordLength(){
        var input = document.getElementById("id_answer");
        var answer = input.value;
        console.log(answer);
        var reg = new RegExp(":", "g");
        var res = answer.match(reg);
        var length = res === null ? 0 : res.length;
        console.log('length:'+length);
        return length;
    }
    
    function getCorrect(pattern , answer){
        var ap = pattern.split(' ');
        var aa = answer.split(' ');
        var tl = ap.length;
        var al = aa.length;
        var i = 0 , j = 0;
        var cnum = 0;
        while(i < tl && j < al){
            if(ap[i] == aa[j]){
                cnum++;
                i++;
                j++;
            } else{
                i++;
            }
        }
        return cnum / tl;
    }
    function onSubmit(){
        if(!confirm("确定要提交答案？"))
            return ;
        var answer = document.getElementById("id_answer").value;
        var modtext = document.getElementById("id_content").value;
        var correct = getCorrect(answer , modtext);
        document.getElementById("id_correct").value = correct;
        var time = getSeconds();
        document.getElementById("id_cost").value = time;
        //$('#id_answer_form').submit();
        $.post($('#id_answer_form').attr('action'),$('#id_answer_form').serialize(),function(result){
            alert(result);
        });
    }
    document.getElementById("id_new").firstChild.nodeValue = document.getElementById("id_content").value;
    function restart(){
        var obj =  document.getElementById("typeOCX");
        if(confirm("这将会清除您输入的所有内容并重新计时，你确定这样做吗？")){
            clearContent(obj);
            reloadTime();
            keyReSet();
            clearWord();
            clearTemplate();
        }
    }
</script>