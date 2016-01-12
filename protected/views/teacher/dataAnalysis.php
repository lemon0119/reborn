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
                         <li><a href="#" onclick="getSuiteExercise(<?php echo $work['suiteID'];?>,<?php echo $work['workID'];?>)"><i class="icon-list"></i><?php echo $suite['suiteName']; ?></a></li>                                                                                                                                               
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
                 <li><div ><a href="#" onclick="getClassExer(<?php echo $lesson['lessonID']; ?>)"><i class="icon-list"></i><?php echo $lesson['lessonName']; ?></a></div></li>                  
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
                 <li><div ><a href="#" onclick="getExamExercise(<?php echo $exam['examID'];?>)"><i class="icon-list"></i><?php echo $exam['examName']; ?></a></div></li>
                        <?php 
                              }
                 }
?>                                     
             </ul>                   
         </div>                                    
      </ul>
     </div>
</div>


<div class="span9" id="sp" style="display: none;">
     <select name = "selectID" id="selectID"  style="width:13%; height:auto;display:inline;float:left;">
        <option value = "correct" > 正确率 </option >
        <option value = "speed" >速度 </option >
        <option value = "maxSpeed">maxSpeed</option >
        <option value = "allKey"> allKey</option >
     </select > 
    <div id="div1" style="width:33%; height:auto;display:inline;float:left;" >
        <ul>题目：</ul>
        <ul id="ul1" style="list-style: none;">
        </ul>
    </div>
    <div id="div1" style="width:33%; height:auto;display:inline;float:left;">
        <ul>排名：</ul>
        <ul id="ul2" style="list-style: none;">
            <li>练习一</li>
            <li>练习二</li>
            <li>练习三</li>          
        </ul>
    </div>
        <div id="div1" style="width:33%; height:auto;display:inline;float:left;">
            <ul id="ul3" style="list-style: none;">
            <li>练习一</li>
            <li>练习二</li>
            <li>练习三</li>          
        </ul>
    </div>
  
    
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

function getSuiteExercise(suiteID,workID){
        $.ajax({
             type: "POST",
             dataType:"json",
             url: "index.php?r=api/getExercise",
             data: {suiteID:suiteID,
                    workID:workID
                },
             success: function(data){
                   document.getElementById("sp").style.display='block';
                   var ul = document.getElementById("ul1");          
                   $('#ul1').children().filter('li').remove();
                   $('#ul2').children().filter('li').remove();
                   $('#ul3').children().filter('li').remove();
                   for(var i=0;i<data.length;i++){                                         
                      var str = "<a href='#' onclick='getStudentRanking("+"0"+","+data[i][0]['exerciseID']+","+ data[i]['type']+")'>"+data[i][0]['title']+"</a>";       
                      var li = document.createElement("li");               
                      li.innerHTML= str;
                      ul.appendChild(li);
                   }  
                 },     
                error: function(xhr, type, exception){
                    window.wxc.xcConfirm('出错了...', window.wxc.xcConfirm.typeEnum.error);
                    console.log(xhr, "Failed");
                }
         });    
}
function getClassExer(lessonID){
        $.ajax({
             type: "POST",
             dataType:"json",
             url: "index.php?r=api/getClassExer",
             data: {lessonID:lessonID,
                },
             success: function(data){
                 document.getElementById("sp").style.display='block';
                   var ul = document.getElementById("ul1");          
                   $('#ul1').children().filter('li').remove();
                   $('#ul2').children().filter('li').remove();
                   $('#ul3').children().filter('li').remove();
                   for(var i=0;i<data.length;i++){       
                      var ab=document.getElementById("selectID").value;
                      var str = "<a href='#' onclick='getClassExerRanking("+data[i]['exerciseID']+","+ data[i]['type']+")'>"+data[i]['title']+"</a>";       
                      var li = document.createElement("li");               
                      li.innerHTML= str;
                      ul.appendChild(li);
                   }  
                 },     
                error: function(xhr, type, exception){
                    window.wxc.xcConfirm('出错了...', window.wxc.xcConfirm.typeEnum.error);
                    console.log(xhr, "Failed");
                }
         });    
}
function getExamExercise(examID){
        $.ajax({
             type: "POST",
             dataType:"json",
             url: "index.php?r=api/getExamExercise",
             data: {examID:examID,
                },
             success: function(data){
                 document.getElementById("sp").style.display='block';
                   var ul = document.getElementById("ul1");          
                   $('#ul1').children().filter('li').remove();
                   $('#ul2').children().filter('li').remove();
                   $('#ul3').children().filter('li').remove();
                   for(var i=0;i<data.length;i++){                                         
                      var str = "<a href='#' onclick='getStudentRanking("+"1"+","+data[i][0]['exerciseID']+","+ data[i]['type']+")'>"+data[i][0]['title']+"</a>";       
                      var li = document.createElement("li");               
                      li.innerHTML= str;
                      ul.appendChild(li);
                   }  
                 },     
                error: function(xhr, type, exception){
                    window.wxc.xcConfirm('出错了...', window.wxc.xcConfirm.typeEnum.error);
                    console.log(xhr, "Failed");
                }
         });    
}

function getStudentRanking(isExam,exerciseID,type){
        var choice=document.getElementById("selectID").value;
        $.ajax({
             type: "POST",
             dataType:"json",
             url: "index.php?r=api/getStudentRanking",
             data: {
                    exerciseID:exerciseID,
                    type:type,
                    isExam:isExam,
                    choice:choice,
                },
             success: function(data){
                 document.getElementById("sp").style.display='block';
                   var ul = document.getElementById("ul2");          
                   //$('#ul1').children().filter('li').remove();
                   $('#ul2').children().filter('li').remove();
                   $('#ul3').children().filter('li').remove();
                   for(var i=0;i<data.length;i++){                                         
                      var str = "<a href='#'>"+data[i]['studentName']+"</a>";       
                      var li = document.createElement("li");               
                      li.innerHTML= str;
                      ul.appendChild(li);
                   }  
                 },     
            error: function(xhr, type, exception){
                window.wxc.xcConfirm('出错了...', window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr, "Failed");
            }
         }); 
}
function getClassExerRanking(exerciseID,type){
        var choice=document.getElementById("selectID").value;
        $.ajax({
             type: "POST",
             dataType:"json",
             url: "index.php?r=api/getClassExerRanking",
             data: {
                    exerciseID:exerciseID,
                    type:type,
                    choice:choice,
                },
             success: function(data){
                 document.getElementById("sp").style.display='block';
                   var ul = document.getElementById("ul2");          
                   //$('#ul1').children().filter('li').remove();
                   $('#ul2').children().filter('li').remove();
                   $('#ul3').children().filter('li').remove();
                   for(var i=0;i<data.length;i++){                                         
                      var str = "<a href='#'>"+data[i]['studentName']+"</a>";       
                      var li = document.createElement("li");               
                      li.innerHTML= str;
                      ul.appendChild(li);
                   }  
                 },     
            error: function(xhr, type, exception){
                window.wxc.xcConfirm('出错了...', window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr, "Failed");
            }
         }); 
}


</script>