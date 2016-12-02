<script src="<?php echo JS_URL;?>exerJS/LCS.js"></script>
<script src="<?php echo JS_URL;?>exerJS/ocxJS.js"></script>
<link href="<?php echo CSS_URL; ?>ywStyle.css" rel="stylesheet" type="text/css" />
<script src="<?php echo JS_URL;?>exerJS/timep.js"></script>
<script>
    function formSubmit(){
                        var txt=  "是否确认保存答案！！";
					var option = {
						title: "保存答案",
						btn: parseInt("0011",2),
						onOk: function(){
							doSubmit(false);
						} 
					};
					window.wxc.xcConfirm(txt, "custom", option);
        
    }
     function submitSuite2(simple){
       var isExam = <?php if($isExam){echo 1;}else {echo 0;}?>;
       setTimeout("saveToDateBaseNow(),saveData()",100);
       //saveToDateBaseNow();
        doSubmit(true);
        $.post('index.php?r=student/overSuite&&isExam=<?php echo $isExam;?>',function(){
            if(isExam)
                window.location.href="index.php?r=student/classExam";
            else
                window.location.href="index.php?r=student/classwork";
        });
        
					//window.wxc.xcConfirm("提交以后，不能重新进行答题，你确定提交吗？", "custom", option);
       
    }
    function submitSuite(simple){
       var isExam = <?php if($isExam){echo 1;}else {echo 0;}?>;
       var option = {
            title: "提交试卷",
            btn: parseInt("0011",2),
            onOk: function(){
                     doSubmit(true);
                     saveToDateBaseNow();
                     saveData();
                    $.post('index.php?r=student/overSuite&&isExam=<?php echo $isExam;?>',function(){
                        if(isExam)
                            window.location.href="index.php?r=student/classExam";
                        else
                            window.location.href="index.php?r=student/classwork";
                    });
            } 
        };
        window.wxc.xcConfirm("提交以后，不能重新进行答题，你确定提交吗？", "custom", option);
    }
    function doSubmit(simple,doFunction){
        //console.log("answer = "+theString);
        $.post($('#id_answer_form').attr('action'),$('#id_answer_form').serialize(),function(result){
            if(!simple){            
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
    
    
    function writeData(){
        var obj =  document.getElementById("typeOCX");
        var theString = obj.GetContentWithSteno();
        var text = document.getElementById("content").value;
        document.getElementById("id_answer").value = theString;
        var lcs = new LCS(text, theString);
        if(lcs == null)
            return;
        lcs.doLCS();
        var correct = lcs.getSubString(3).length / lcs.getStrOrg(1).length;
        document.getElementById("id_correct").value = (correct*100).toFixed(2);
        window.GA_RightRadio = (correct*100).toFixed(2);
        document.getElementById("id_cost").value = getSeconds();
        document.getElementById("id_AverageSpeed").value = document.getElementById("getAverageSpeed").innerHTML;
        document.getElementById("id_HighstSpeed").value = document.getElementById("getHighstSpeed").innerHTML;
        document.getElementById("id_BackDelete").value = document.getElementById("getBackDelete").innerHTML;
        document.getElementById("id_HighstCountKey").value = document.getElementById("getHighstCountKey").innerHTML;
        document.getElementById("id_AverageKeyType").value = document.getElementById("getAverageKeyType").innerHTML;
        document.getElementById("id_HighIntervarlTime").value = document.getElementById("getHighIntervarlTime").innerHTML;
        document.getElementById("id_countAllKey").value = document.getElementById("getcountAllKey").innerHTML;        
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
     <input name="nm_AverageSpeed" id="id_AverageSpeed" type="hidden">
     <input name="nm_HighstSpeed" id="id_HighstSpeed" type="hidden">
        <input name="nm_BackDelete" id="id_BackDelete" type="hidden">
        <input name="nm_HighstCountKey" id="id_HighstCountKey" type="hidden">
        <input  name="nm_AverageKeyType" id="id_AverageKeyType" type="hidden">
        <input name="nm_HighIntervarlTime" id="id_HighIntervarlTime" type="hidden">
        <input name="nm_countAllKey"  id="id_countAllKey" type="hidden" > 
</form>
