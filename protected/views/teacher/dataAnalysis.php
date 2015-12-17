 <div class="span3">
     <li class="nav-header"><i class="icon-knowlage"></i>课时列表</li>
     <div class="well-topnoradius" style="padding: 8px 0;height:325px;overflow:auto; top:-40px;">
     <ul class="nav nav-list">       
         <li ><div id="id_classWork"><a href="#"><i class="icon-list"></i>课后作业</a></div></li>  
         <div style="display: none" id="id_classWorkLesson">
             <ul class="nav nav-list"> 
               <?php foreach ($array_lesson as $lesson): ?>
                 <li><div ><a href="#" onclick="showClassWork(<?php echo $lesson['lessonID'];?>)"><i class="icon-list"></i><?php echo $lesson['lessonName']; ?></a></div></li>                
                 <div style="display: none" <?php echo "id='test".$lesson['lessonID']."'"?>>
                     <ul>  
                         <?php foreach ($array_work as $work){
                             if($work['lessonID'] == $lesson['lessonID']){     
                                 foreach($array_suite as $suite)
                                 {
                                     if($suite['suiteID'] == $work['suiteID']) {                                                                       
                           ?>                      
                         <li><a href="#" onclick="getSuiteExercise(<?php echo $work['suiteID'];?>)"><i class="icon-list"></i><?php echo $suite['suiteName']; ?></a></li>                                                                                                                                               
                             <?php        
                                     }
                                 }
                             }                 
                         }
                        ?>                      
                     </ul>                   
                 </div>                   
                     <?php endforeach; ?> 
             </ul>
         </div>
         <li ><div id="id_classExercise"><a href="#"><i class="icon-list"></i>课堂练习</a></div></li>
       <div style="display: none" id="id_classExerciseLesson">
             <ul class="nav nav-list"> 
               <?php foreach ($array_lesson as $lesson): ?>
                 <li><div ><a href="#" ><i class="icon-list"></i><?php echo $lesson['lessonName']; ?></a></div></li>                  
             <?php endforeach; ?> 
             </ul>
         </div>        
         <li ><div id="id_classExam"><a href="#"><i class="icon-list"></i>课堂考试</a></div></li>
         <div style="display: none" id="id_classExamLesson">
             <ul class="nav nav-list">    
                 <?php foreach ($array_examList as $examList)
                 {
                     foreach($array_exam as $exam)
                         if($exam['examID'] == $examList['examID'])
                         {
                             
                         ?>
                              <li><div ><a href="#" ><i class="icon-list"></i><?php echo $exam['examName']; ?></a></div></li>
                        <?php 
                              }
                 }
?>                                     
             </ul>                   
         </div>                                    
      </ul>
     </div>
</div>


<div class="span9">
    
    
</div>



<script>
    //sunpy: switch camera and bulletin
$(document).ready(function(){
    $("#id_classWork").click(function() {
        $("#id_classWorkLesson").toggle(200);
    });  
    $("#id_classExercise").click(function() {
        $("#id_classExerciseLesson").toggle(200);
    });  
        $("#id_classExam").click(function() {
        $("#id_classExamLesson").toggle(200);
    });  
    
    
    
    
    
    $("#sw-chat").click(function() {
        $("#chat-box").toggle(200);
    });
        getBackTime();
});

function showClassWork(lessonID){
    var str = "#test" + lessonID;
     $("#test" + lessonID).toggle(200);
}

function getSuiteExercise(suiteID){
        $.ajax({
             type: "POST",
             dataType:"json",
             url: "index.php?r=teacher/getExercise",
             data: {suiteID:suiteID},
             success: function(data){
                   alert(data[0]['exerciseID']);
    
                 },     
                error: function(xhr, type, exception){
                    window.wxc.xcConfirm('出错了...', window.wxc.xcConfirm.typeEnum.error);
                    console.log(xhr, "Failed");
                }
         });
    
}




</script>