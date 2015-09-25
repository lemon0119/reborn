<script src="<?php echo JS_URL;?>exerJS/LCS.js"></script>
<script>
    function onSubmit(){
        if(!confirm("确定要提交答案？"))
            return ;
        doSubmit(false);
    }
    function submitSuite(simple){
        var isExam = <?php if($isExam){echo 1;}else {echo 0;}?>;
        if(!simple){
            if(!confirm("提交以后，不能重新进行答题，你确定提交吗？"))
                return ;
        }
        doSubmit(true);
        $.post('index.php?r=student/overSuite&&isExam=<?php echo $isExam;?>',function(){
            if(isExam)
                window.location.href="index.php?r=student/classExam";
            else
                window.location.href="index.php?r=student/classwork";
        });
    }
    function doSubmit(simple,doFunction){
        var obj =  document.getElementById("typeOCX");
        var theString = getContent( obj);
        var text = document.getElementById("content").value;
        document.getElementById("id_answer").value = theString;
        var lcs = new LCS(text, theString);
        if(lcs == null)
            return;
        lcs.doLCS();
        var correct = lcs.getSubString(3).length / lcs.getStrOrg(1).length;
        document.getElementById("id_correct").value = correct;
        var time = getSeconds();
        document.getElementById("id_cost").value = time;
        //console.log("answer = "+theString);\
        $.post($('#id_answer_form').attr('action'),$('#id_answer_form').serialize(),function(result){
            if(!simple){
                alert(result);
            }else{
                doFunction();
            }
        });
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
</form>