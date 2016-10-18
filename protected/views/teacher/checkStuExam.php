<?php


require 'examAnsSideBar.php';
?>

<div class="span9" id="work" style="height: 574px">
</div>


<script>
   $(document).ready(function(){
        var user = {
            workID:"<?php echo $_GET['workID'];?>",
            studentID:"<?php echo $_GET['studentID'];?>",
            accomplish:"<?php echo $_GET['accomplish'];?>",
            recordID:<?php if($record != NULL)echo $record['recordID'];else echo 1;?>,
            type:"<?php echo $type;?>",
            examID:<?php echo $work['examID'];?>,
            exerciseID:0
        };
      $.ajax({
          type:"POST",
          url:"./index.php?r=teacher/ajaxExam&&classID=<?php echo $class['classID']?>",
          data:user,
          dataType:"html",
          success:function(html){
              $("#work").append(html);
          },
            error: function(xhr, type, exception){
                //window.wxc.xcConfirm('出错la。。', window.wxc.xcConfirm.typeEnum.error);
                //console.log(xhr.responseText, "Failed");
            }
      })
    });   
</script>




