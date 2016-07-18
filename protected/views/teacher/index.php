<!--欢迎界面-->
<!--<div >
    <h3 class="welcome" align="center"> 欢 迎 来 到 亚 伟 速 录 教 学 平 台 ！</h3>
<!--  <button onclick="modify()">测试</button>-->
<!--</div>-->

<div class="div_bg" style="min-height: 700px" >
</div> 



<script>
   
//    function modify(){
//      window.location.href="./index.php?r=teacher/index&&modify=1";
//   }
<?php if(isset($array_class)){ ?>
    window.wxc.xcConfirm('该老师未分班！', window.wxc.xcConfirm.typeEnum.error);
<?php }?>
</script>
