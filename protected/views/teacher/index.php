<!--欢迎界面-->
<script src="<?php echo JS_URL;?>exerJS/AnalysisTool.js"></script>
<div >
    <h3 class="welcome" align="center"> 欢 迎 来 到 亚 伟 速 录 教 学 平 台 ！</h3>
    <button id="b1">test</button>
    <a id="b2">123</a>
</div>

<script>
    $(document).ready(function (){
       var time = <?php echo time();?>;
       var content = "asdfasdfasdfasdfasdf";
       $("#b1").click(function(){
           AjaxGetAverageSpeed("b2",time,content);
           });
       });
    
</script>
