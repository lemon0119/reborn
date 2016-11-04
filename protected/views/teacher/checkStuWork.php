<?php
require 'workAnsSideBar.php';
?>

<div class="span9" id="work" style="height: 574px">
</div>

<script>
   $(document).ready(function(){
       
        var user = {           
            recordID:<?php if($record != NULL)echo $record['recordID'];else echo 1;?>,
            type:"<?php echo $type;?>",
            suiteID:<?php echo $work['suiteID'];?>,
            exerciseID:0 
        };
      $.ajax({
          type:"POST",
          url:"./index.php?r=teacher/ajaxChoice",
          data:user,
          dataType:"html",
          success:function(html){
              $("#work").append(html);
          }
      });
    }); 
    
</script>




