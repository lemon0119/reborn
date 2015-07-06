<script src="<?php echo EXER_JS_URL;?>LCS.js"></script>
<script src="<?php echo JS_URL;?>jquery-form.js"></script>
<script>
    function onSubmit(){
        if(!confirm("确定要提交答案？"))
            return ;
        var obj =  document.getElementById("typeOCX");
        var theString = getContent( obj);
        var text = document.getElementById("content").value;
        document.getElementById("id_answer").value = theString;
        var lcs = new LCS(text, theString);
        lcs.doLCS();
        var correct = lcs.getSubString(3).length / lcs.getStrOrg(1).length;
        document.getElementById("id_correct").value = correct;
        
        var time = getSeconds();
        document.getElementById("id_cost").value = time;
        //console.log("answer = "+theString);
        var options = {target:'#cont'};
        $("#id_answer_form").ajaxSubmit(options);
    }
    function restart(){
        var obj =  document.getElementById("typeOCX");
        if(confirm("这将会清除您输入的所有内容并重新计时，你确定这样做吗？")){
            clearContent(obj);
            reloadTime();
        }
    }
</script>
<?php
    $host = Yii::app()->request->hostInfo;
    $path = Yii::app()->request->baseUrl;
    $rout = 'student/saveAnswer';
    $page = '/index.php?r='.$rout;
    if(isset($_GET['page']))
        $index = $_GET['page'];
    else 
        $index = 1;
    $param = '&page='.$index;
?>
<form name='nm_answer_form' id='id_answer_form' method="post" action="<?php echo $host.$path.$page.$param;?>">
    <input name="nm_answer" id="id_answer" type="hidden">
    <input name="nm_cost" id="id_cost" type="hidden">
    <input name="nm_correct" id="id_correct" type="hidden">
    <a type="button" class="btn btn-primary btn-large" onclick="onSubmit();">提交</a>
　　　　　　　　　　<a class="btn btn-large" onclick="restart();">重新计时</a>
</form>