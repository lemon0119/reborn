<script src="https://localhost/reborn/js/echarts.min.js"></script>
<style>
    .bb{
        color:black;
        margin-left: 20px;
        margin-top: 10px;
        text-decoration: none;
        
    }
    .bb:hover{
        text-decoration: none;
    }
</style>
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
                 <li><div ><a href="#" onclick="getExamExercise(<?php echo $exam['examID'];?>,<?php echo $examList['workID'];?>)"><i class="icon-list"></i><?php echo $exam['examName']; ?></a></div></li>
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
    
    <div id="div1" style="width:43%; height:346px;display:inline;float:left;" >
        <ul style="font-size: 23px;margin-top: 10px;">题目</ul>
        <ul>
            <select name = "selectID" id="selectID"  style="height:36px;width:300px; display:inline;float:left;">
            <option value = "correct" > 正确率 </option >
            <option value = "speed" >速度 </option >
            <option value = "maxSpeed">最大速度</option >
            <option value = "backDelete"> 回改字数</option >
            <option value="maxInternalTime">最高间隔</option>
            </select > 
        </ul>
        <ul id="ul1" style="list-style: none;border-radius: 3px;background-color: #AAACAA;position: relative;float:left;height:250px;overflow: auto;width:300px;color:black;">
        </ul>
    </div>
    <div id="div1" style="width:55%; height:346px;display:inline;float:right;margin-top: 8px;">
        <ul style="font-size: 23px;">排名</ul>
        <ul  id="ul2" style="list-style: none;margin-left: 20px;">
            <li>练习一</li>
            <li>练习二</li>
            <li>练习三</li>          
        </ul>
        <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>名次</th>
                        <th>姓名</th>
                    </tr>
                </thead>
                <tbody id="bo">
                </tbody>
            </table>
    </div>
    <div id="main" style="width: 800px;height:400px;position: absolute;left:60px;top:380px;"></div>
    
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
                   $('#bo').hide();
                   for(var i=0;i<data.length;i++){                                         
                      var str = "<a class='bb' onclick='getStudentRanking("+data[i]['workID']+","+"0"+","+data[i][0]['exerciseID']+","+ data[i]['type']+")'>"+data[i][0]['title']+"</a>";       
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
                   //$('#bo').hide();
                   //$('#main').hide();
                   
                   for(var i=0;i<data.length;i++){       
                      var ab=document.getElementById("selectID").value;
                      var str = "<a class='bb' onclick='getClassExerRanking("+data[i]['exerciseID']+","+ data[i]['type']+")'>"+data[i]['title']+"</a>";       
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
function getExamExercise(examID,workID){
        $.ajax({
             type: "POST",
             dataType:"json",
             url: "index.php?r=api/getExamExercise",
             data: {examID:examID,
                 workID:workID,
                },
             success: function(data){
                 document.getElementById("sp").style.display='block';
                   var ul = document.getElementById("ul1");          
                   $('#ul1').children().filter('li').remove();
                   $('#ul2').children().filter('li').remove();
                   $('#ul3').children().filter('li').remove();
                   $('#bo').hide();
                   for(var i=0;i<data.length;i++){ 
                      var str = "<a class='bb' onclick='getStudentRanking("+data[i]['workID']+","+"1"+","+data[i][0]['exerciseID']+","+ data[i]['type']+")'>"+data[i][0]['title']+"</a>";       
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


function getStudentRanking(workID,isExam,exerciseID,type){
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
                    workID:workID,
                },
             success: function(data){
                 document.getElementById("sp").style.display='block';
                   var tbody = document.getElementById("bo");      
                   $('#bo').children().filter('tr').remove();
                   $('#ul2').children().filter('li').remove();
                   $('#ul3').children().filter('li').remove();
                   $('#bo').show();
                   var times = new Array();
                   var per=new Array();
                   for(var i=0;i<data[0].length;i++){       
                       var name=data[0][i]['time'];
                      var  str = "<th>"+(i+1)+"</th>"+"<th><a onclick='getStudentRankingAll("+workID+","+isExam+","+exerciseID+","+type+","+'"'+data[0][i]['time']+'"'+")'>"+data[0][i]['studentName']+"</a></th>";
                      var tr = document.createElement("tr");               
                      tr.innerHTML= str;
                      tbody.appendChild(tr);
                   }
                   for(var j=0;j<data[1][0].length;j++){
                       times[j]=data[1][0][j]['duration'];
                       per[j]=data[1][0][j][choice];
                   }
                   var choose;
                   if(choice=='correct'){
                       choose='正确率';
                   }else if(choice=='speed'){
                       choose='速度';
                   }else if(choice=='maxSpeed'){
                       choose='最大速度';
                   }else if(choice=='backDelete'){
                       choose='回改字数';
                   }else if(choice=='maxInternalTime'){
                       choose='最高间隔';
                   }
                    var myChart = echarts.init(document.getElementById('main'));
                    var option = {
                        title: {
                            text: '折线图'
                        },
                        tooltip : {
                            trigger: 'axis'
                        },
                        legend: {
                            data:['平均成绩']
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        xAxis : [
                            {
                                type : 'category',
                                boundaryGap : false,
                                data : times
                            }
                        ],
                        yAxis : [
                            {
                                type : 'value'
                            }
                        ],
                        series : [
                            {
                                name:'平均成绩',
                                type:'line',
                                data:per
                            },

                        ]
                    };
                    myChart.setOption(option);
                    
                 },     
            error: function(xhr, type, exception){
                window.wxc.xcConfirm('出错了...', window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr, "Failed");
            }
         }); 
}
function getStudentRankingAll(workID,isExam,exerciseID,type,name){
        if(name==0){
            alert("请选择题目");
            return ;
        }
        var choice=document.getElementById("selectID").value;
        console.log(name);
        $.ajax({
             type: "POST",
             dataType:"json",
             url: "index.php?r=api/getStudentRankingAll",
             data: {
                    exerciseID:exerciseID,
                    type:type,
                    isExam:isExam,
                    choice:choice,
                    workID:workID,
                    name:name,
                },
             success: function(data){
                 document.getElementById("sp").style.display='block';
                   var tbody = document.getElementById("bo");      
                   $('#bo').children().filter('tr').remove();
                   $('#ul2').children().filter('li').remove();
                   $('#ul3').children().filter('li').remove();
                   var times = new Array();
                   var per=new Array();
                   var myTimes = new Array();
                   var myPer=new Array();
                   
                   for(var h=0;h<data[2].length;h++){
                       myTimes[h]=data[2][h]['time'];
                       myPer[h]=data[2][h][choice];
                       
                   }
                   for(var i=0;i<data[0].length;i++){   
                      var  str = "<th>"+(i+1)+"</th>"+"<th><a onclick='getStudentRankingAll("+workID+","+"1"+","+exerciseID+","+type+","+'"'+data[0][i]['time']+'"'+")'>"+data[0][i]['studentName']+"</a></th>";
                      var tr = document.createElement("tr");               
                      tr.innerHTML= str;
                      tbody.appendChild(tr);
                   }
                   for(var j=0;j<data[1][0].length;j++){
                       times[j]=data[1][0][j]['duration'];
                       per[j]=data[1][0][j][choice];
                   }
                   for(var h=data[2].length;h<data[1][0].length;h++){
                       myPer[h]=0;
                       
                   }
                   var choose;
                   if(choice=='correct'){
                       choose='正确率';
                   }else if(choice=='speed'){
                       choose='速度';
                   }else if(choice=='maxSpeed'){
                       choose='最大速度';
                   }else if(choice=='backDelete'){
                       choose='回改字数';
                   }else if(choice=='maxInternalTime'){
                       choose='最高间隔';
                   }
                    var myChart = echarts.init(document.getElementById('main'));
                    var option = {
                        title: {
                            text: '折线图'
                        },
                        tooltip : {
                            trigger: 'axis'
                        },
                        legend: {
                            data:['平均成绩','学生成绩']
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        xAxis : [
                            {
                                type : 'category',
                                boundaryGap : false,
                                data : times
                            }
                        ],
                        yAxis : [
                            {
                                type : 'value'
                            }
                        ],
                        series : [
                            {
                                name:'平均成绩',
                                type:'line',
                                data:per
                            },
                            {
                                name:'学生成绩',
                                type:'line',
                                data:myPer
                            },

                        ]
                    };
                    myChart.setOption(option);
                    
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
                   var tbody = document.getElementById("bo");      
                   $('#bo').children().filter('tr').remove();
                   $('#ul2').children().filter('li').remove();
                   $('#ul3').children().filter('li').remove();
                   //$('#bo').show();
                   //$('#main').show();
                   var times  = new Array();
                   var per=new Array();
                   for(var i=0;i<data[0].length;i++){                                         
                      var str =  "<th>"+(i+1)+"</th>"+"<th><a onclick='getClassExerRankingAll("+exerciseID+","+type+","+'"'+data[0][i]['time']+'"'+")'>"+data[0][i]['studentName']+"</a></th>";
                      var tr = document.createElement("tr");               
                      tr.innerHTML= str;
                      tbody.appendChild(tr);
                   }  
                   for(var j=0;j<data[1][0].length;j++){
                       times[j]=data[1][0][j]['duration'];
                       per[j]=data[1][0][j][choice];
                   }
                   var choose;
                   if(choice=='correct'){
                       choose='正确率';
                   }else if(choice=='speed'){
                       choose='速度';
                   }else if(choice=='maxSpeed'){
                       choose='最大速度';
                   }else if(choice=='backDelete'){
                       choose='回改字数';
                   }else if(choice=='maxInternalTime'){
                       choose='最高间隔';
                   }
                   var myChart = echarts.init(document.getElementById('main'));
                    var option = {
                        title: {
                            text: '折线图'
                        },
                        tooltip : {
                            trigger: 'axis'
                        },
                        legend: {
                            data:['平均成绩']
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        xAxis : [
                            {
                                type : 'category',
                                boundaryGap : false,
                                data : times
                            }
                        ],
                        yAxis : [
                            {
                                type : 'value'
                            }
                        ],
                        series : [
                            {
                                name:'平均成绩',
                                type:'line',
                                data:per
                            },

                        ]
                    };
                    myChart.setOption(option);
                 },     
            error: function(xhr, type, exception){
                window.wxc.xcConfirm('出错了...', window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr, "Failed");
            }
         }); 
}
function getClassExerRankingAll(exerciseID,type,name){
        if(name==0){
            alert("请选择题目");
            return ;
        }
        var choice=document.getElementById("selectID").value;
        $.ajax({
             type: "POST",
             dataType:"json",
             url: "index.php?r=api/getClassExerRankingAll",
             data: {
                    exerciseID:exerciseID,
                    type:type,
                    choice:choice,
                    name:name,
                },
             success: function(data){
                 document.getElementById("sp").style.display='block';
                   var ul = document.getElementById("ul2");          
                   var tbody = document.getElementById("bo");      
                   $('#bo').children().filter('tr').remove();
                   $('#ul2').children().filter('li').remove();
                   $('#ul3').children().filter('li').remove();
                   var times = new Array();
                   var per=new Array();
                   var myTimes = new Array();
                   var myPer=new Array();
                   for(var h=0;h<data[2].length;h++){
                       myTimes[h]=data[2][h]['time'];
                       myPer[h]=data[2][h][choice];
                   }
                   for(var i=0;i<data[0].length;i++){                                         
                      var str =  "<th>"+(i+1)+"</th>"+"<th><a onclick='getClassExerRankingAll("+exerciseID+","+type+","+'"'+data[0][i]['time']+'"'+")'>"+data[0][i]['studentName']+"</a></th>";
                      var tr = document.createElement("tr");               
                      tr.innerHTML= str;
                      tbody.appendChild(tr);
                   }  
                   for(var j=0;j<data[1][0].length;j++){
                       times[j]=data[1][0][j]['duration'];
                       per[j]=data[1][0][j][choice];
                   }
                   for(var h=data[2].length;h<data[1][0].length;h++){
                       myPer[h]=0;
                   }
                   var choose;
                   if(choice=='correct'){
                       choose='正确率';
                   }else if(choice=='speed'){
                       choose='速度';
                   }else if(choice=='maxSpeed'){
                       choose='最大速度';
                   }else if(choice=='backDelete'){
                       choose='回改字数';
                   }else if(choice=='maxInternalTime'){
                       choose='最高间隔';
                   }
                   var myChart = echarts.init(document.getElementById('main'));
                    var option = {
                        title: {
                            text: '折线图'
                        },
                        tooltip : {
                            trigger: 'axis'
                        },
                        legend: {
                            data:['平均成绩','学生成绩']
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        xAxis : [
                            {
                                type : 'category',
                                boundaryGap : false,
                                data : times
                            }
                        ],
                        yAxis : [
                            {
                                type : 'value'
                            }
                        ],
                        series : [
                            {
                                name:'平均成绩',
                                type:'line',
                                data:per
                            },
                            {
                                name:'学生成绩',
                                type:'line',
                                data:myPer
                            },

                        ]
                    };
                    myChart.setOption(option);
                 },     
            error: function(xhr, type, exception){
                window.wxc.xcConfirm('出错了...', window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr, "Failed");
            }
         }); 
}

</script>