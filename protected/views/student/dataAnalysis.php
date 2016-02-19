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
    <div style="position: relative;top: 10px;">
        
        <div id="div1" style="width:45%;display:inline;float:left;overflow: auto;" >
            
            <ul>
                <table class="tab">
                    <tr>
                        <td>
                            <input name="selectID" id="selectID" type="radio" value="correct" checked/>正确率&nbsp;  &nbsp;
                        </td>
                        <td>
                            <input name="selectID" id="selectID" type="radio" value="speed" />速度&nbsp;  &nbsp;
                        </td>
                        <td>
                            <input name="selectID" id="selectID" type="radio" value="maxSpeed" />最大速度&nbsp;  &nbsp; 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input name="selectID" id="selectID" type="radio" value="backDelete" />回改字数&nbsp;  &nbsp; 
                        </td>
                        <td>
                            <input name="selectID" id="selectID" type="radio" value="maxInternalTime" />最高间隔&nbsp;  &nbsp;
                        </td>
                    </tr>
                </table>
            </ul>


            <ul id="ul1" style="list-style: none;border-radius: 3px;background-color: #AAACAA;position: relative;float:left;overflow: auto;width:300px;color:black;">
            </ul>
        </div>
        
        <div id="div1" style="width:55%; display:inline;float:right;overflow: auto;">
            
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
                            <th id="name">正确率</th>
                        </tr>
                    </thead>
                    <tbody id="bo">
                    </tbody>
                </table>
        </div>
    </div>
    
    <div id="de" style="display:none;">
        <table  class="table table-bordered table-striped" style="overflow: auto;position: relative;top:20px;width:730px;left:20px">
                <thead>
                    <tr style="height:40px;">
                        <th>次数</th>
                        <th>正确率</th>
                        <th>平均速度</th>
                        <th>最高速度</th>
                        <th>回改字数</th>
                        <th>平均击键</th>
                        <th>最高击键</th>
                        <th>间隔</th>
                        <th>总击键数</th>
                        
                    </tr>
                </thead>
                <tbody id="detail" class="detailed">
                </tbody>
            </table>
    </div>
    <div id="sh" style="left:700px;display: none;width: 100px;overflow:auto;height:40px;position: relative;top:20px;">
    </div>
    <div id="main" style="left:15px;display: none;width: 750px;overflow: auto;height:400px;position: relative;top:20px;"></div>
    
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
                   document.getElementById("de").style.display='none';
                   var ul = document.getElementById("ul1");          
                   $('#ul1').children().filter('li').remove();
                   $('#ul2').children().filter('li').remove();
                   $('#ul3').children().filter('li').remove();
                   $('#bo').children().filter('tr').remove();
                   $("#sh").children().remove();
                   $('#main').children().remove();
                   var type;
                   for(var i=0;i<data.length;i++){                                         
                       if(data[i]['type']==1){
                           type='key';
                       }else if(data[i]['type']==2){
                           type='listen';
                       }else if(data[i]['type']==3){
                           type='look';
                       }
                       type=type+": "+data[i][0]['title'];
                      var str = "<a class='bb' onclick='getStudentRanking("+data[i]['workID']+","+"0"+","+data[i][0]['exerciseID']+","+ data[i]['type']+")'>"+type+"</a>";       
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
                 document.getElementById("de").style.display='none';
                   var ul = document.getElementById("ul1");          
                   $('#ul1').children().filter('li').remove();
                   $('#ul2').children().filter('li').remove();
                   
                   $('#ul3').children().filter('li').remove();
                   
                   $('#bo').children().filter('tr').remove();
                   $("#sh").children().remove();
                   $('#main').children().remove();
                   $('#detail').children().filter('tr').remove();
                   //$('#bo').hide();
                   //$('#main').hide();
                   var type;
                   for(var i=0;i<data.length;i++){ 
                       if(data[i]['type']==1){
                           type='key';
                       }else if(data[i]['type']==2){
                           type='listen';
                       }else if(data[i]['type']==3){
                           type='look';
                       }
                       type=type+": "+data[i]['title'];
                      var str = "<a class='bb' onclick='getClassExerRanking("+<?php echo $_GET['classID']?>+","+data[i]['exerciseID']+","+ data[i]['type']+")'>"+type+"</a>";       
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
                 document.getElementById("de").style.display='none';
                   var ul = document.getElementById("ul1");          
                   $('#ul1').children().filter('li').remove();
                   $('#ul2').children().filter('li').remove();
                   $('#ul3').children().filter('li').remove();
                   $('#bo').children().filter('tr').remove();
                   $("#sh").children().remove();
                   $('#main').children().remove();
                   var type;
                   for(var i=0;i<data.length;i++){ 
                       if(data[i]['type']==1){
                           type='key';
                       }else if(data[i]['type']==2){
                           type='listen';
                       }else if(data[i]['type']==3){
                           type='look';
                       }
                       type=type+": "+data[i][0]['title'];
                      var str = "<a class='bb' onclick='getStudentRanking("+data[i]['workID']+","+"1"+","+data[i][0]['exerciseID']+","+ data[i]['type']+")'>"+type+"</a>";       
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
        var province = document.getElementsByName("selectID");
        var a;
        var choice;
        for(a=0; a<province.length; a++){//遍历单选框
            if(province[a].checked){
                  choice = province[a].value;
            }
        }
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
                 document.getElementById("de").style.display='none';
                 document.getElementById("sh").style.display='none';
                   var tbody = document.getElementById("bo");      
                   $('#bo').children().filter('tr').remove();
                   $('#ul2').children().filter('li').remove();
                   $('#ul3').children().filter('li').remove();
                   $("#sh").children().filter('b').remove();
                   $('#main').show();
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
                   document.getElementById("name").innerHTML=choose;
                   document.getElementById("main").style.display='block';
                   var times = new Array();
                   var per=new Array();
                   for(var i=0;i<data[0].length;i++){       
                       var name=data[0][i]['time'];
                      var  str = "<th>"+(i+1)+"</th>"+"<th><a onclick='getStudentRankingAll("+workID+","+isExam+","+exerciseID+","+type+","+'"'+data[0][i]['time']+'"'+")'>"+data[0][i]['studentName']+"</a></th>"+"<th>"+Math.round(data[0][i][choice]*100)/100+"</th>";
                      var tr = document.createElement("tr");               
                      tr.innerHTML= str;
                      tbody.appendChild(tr);
                   }
                   if(data[1].length!=0){
                        for(var j=0;j<data[1][0].length;j++){
                            times[j]=data[1][0][j]['duration'];
                            per[j]=data[1][0][j][choice];
                        }
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
        var province = document.getElementsByName("selectID");
        var a;
        var choice;
        for(a=0; a<province.length; a++){//遍历单选框
            if(province[a].checked){
                  choice = province[a].value;
            }
        }
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
                 document.getElementById("main").style.display='block';
                 document.getElementById("de").style.display='block';
                   var tbody = document.getElementById("bo");      
                   $('#bo').children().filter('tr').remove();
                   $('#ul2').children().filter('li').remove();
                   $('#ul3').children().filter('li').remove();
                   $('#detail').children().filter('tr').remove();
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
                   document.getElementById("name").innerHTML=choose;
                   var times = new Array();
                   var per=new Array();
                   var myTimes = new Array();
                   var myPer=new Array();
                   
                   for(var h=0;h<data[2].length;h++){
                       myTimes[h]=data[2][h]['time'];
                       myPer[h]=data[2][h][choice];
                       
                   }
                   for(var i=0;i<data[0].length;i++){   
                      var  str = "<th>"+(i+1)+"</th>"+"<th><a onclick='getStudentRankingAll("+workID+","+"1"+","+exerciseID+","+type+","+'"'+data[0][i]['time']+'"'+")'>"+data[0][i]['studentName']+"</a></th>"+"<th>"+Math.round(data[0][i][choice]*100)/100+"</th>";
                      var tr = document.createElement("tr");               
                      tr.innerHTML= str;
                      tbody.appendChild(tr);
                   }
                   if(data[1].length!=0){
                        for(var j=0;j<data[1][0].length;j++){
                            times[j]=data[1][0][j]['duration'];
                            per[j]=data[1][0][j][choice];
                        }
                    }
                   for(var h=data[2].length;h<data[1][0].length;h++){
                       myPer[h]=0;
                       
                   }
                   //detail
                    var str =  "<th>"+1+"</th>"+"<th>"+Math.round(data[3]['correct']*100)/100+"</th>"+"<th>"+Math.round(data[3]['speed']*100)/100+"</th>"+"<th>"+Math.round(data[3]['maxSpeed']*100)/100+
                            "</th>"+"<th>"+Math.round(data[3]['backDelete']*100)/100+"</th>"+"<th>"+Math.round(data[3]['averageKeyType']*100)/100+"</th>"+"<th>"+Math.round(data[3]['maxKeyType']*100)/100+"</th>"+"<th>"+Math.round(data[3]['maxInternalTime']*100)/100+
                            "</th>"+"<th>"+Math.round(data[3]['countAllKey']*100)/100+"</th>";
                    var tr = document.createElement("tr");    
                    tr.innerHTML= str;
                    detail.appendChild(tr);
                      
                   
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
function getClassExerRanking(classID,exerciseID,type){
        var province = document.getElementsByName("selectID");
        var a;
        var choice;
        for(a=0; a<province.length; a++){//遍历单选框
            if(province[a].checked){
                  choice = province[a].value;
            }
        }
        $.ajax({
             type: "POST",
             dataType:"json",
             url: "index.php?r=api/getClassExerRanking",
             data: {
                    exerciseID:exerciseID,
                    type:type,
                    choice:choice,
                    classID:classID,
                },
             success: function(data){
                 document.getElementById("sp").style.display='block';
                 document.getElementById("main").style.display='block';
                 document.getElementById("de").style.display='none';
                 document.getElementById("sh").style.display='none';
                   var ul = document.getElementById("ul2");          
                   var tbody = document.getElementById("bo");      
                   $('#bo').children().filter('tr').remove();
                   $('#ul2').children().filter('li').remove();
                   $('#ul3').children().filter('li').remove();
                   $('#detail').children().filter('tr').remove();
                   $("#sh").children().filter('b').remove();
                   //$('#bo').show();
                   //$('#main').show();
                   $('#main').show();
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
                   document.getElementById("name").innerHTML=choose;
                   var times  = new Array();
                   var per=new Array();
                   for(var i=0;i<data[0].length;i++){                                         
                      var str =  "<th>"+(i+1)+"</th>"+"<th><a onclick='getClassExerRankingAll("+"0"+","+classID+","+exerciseID+","+type+","+'"'+data[0][i]['studentID']+'"'+")'>"+data[0][i]['studentName']+"</a></th>"+"<th>"+Math.round(data[0][i][choice]*100)/100+"</th>";
                      var tr = document.createElement("tr");               
                      tr.innerHTML= str;
                      tbody.appendChild(tr);
                   }  
                   if(data[1].length!=0){
                        for(var j=0;j<data[1][0].length;j++){
                            times[j]=data[1][0][j]['duration'];
                            per[j]=data[1][0][j][choice];
                        }
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


function getClassExerRankingAll(seq,classID,exerciseID,type,id){
        if(id==0){
            alert("请选择题目");
            return ;
        }
        var province = document.getElementsByName("selectID");
        var a;
        var choice;
        for(a=0; a<province.length; a++){//遍历单选框
            if(province[a].checked){
                  choice = province[a].value;
            }
        }
        $.ajax({
             type: "POST",
             dataType:"json",
             url: "index.php?r=api/getClassExerRankingAll",
             data: {
                    exerciseID:exerciseID,
                    type:type,
                    choice:choice,
                    id:id,
                    classID:classID,
                    seq:seq,
                },
             success: function(data){
                 document.getElementById("sp").style.display='block';
                 document.getElementById("main").style.display='block';
                   var ul = document.getElementById("ul2");          
                   var tbody = document.getElementById("bo"); 
                   var detail = document.getElementById("detail"); 
                   var sele = document.getElementById("sequence"); 
                   var sh=document.getElementById("sh");
                   $("#sh").children().filter('b').remove();
                   $('#sequence').show();
                   $('#bo').children().filter('tr').remove();
                   document.getElementById("de").style.display='block';
                   $('#ul2').children().filter('li').remove();
                   $('#ul3').children().filter('li').remove();
                   var times = new Array();
                   var per=new Array();
                   var myTimes = new Array();
                   var myPer=new Array();
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
                   document.getElementById("name").innerHTML=choose;
                   for(var h=0;h<data[3].length;h++){
                       myTimes[h]=data[3][h]['time'];
                       myPer[h]=data[3][h][choice];
                   }
                   if(data[0]['sequence']==1){
                        for(var i=0;i<data[1].length;i++){     
                            alert(data[1][i][choice]);
                           var str =  "<th>"+(i+1)+"</th>"+"<th class='sidelist'><a onclick='getClassExerRankingAll("+"0"+","+classID+","+exerciseID+","+type+","+'"'+data[1][i]['studentID']+'"'+")'>"+data[1][i]['studentName']+"</a></th>"+"<th>"+Math.round(data[1][i][choice]*100)/100+"</th>";
                           var tr = document.createElement("tr");    
                           tr.innerHTML= str;
                           tbody.appendChild(tr);
                        }  
                   }else{
                        $("#sh").show();
                        for(var i=0;i<data[1].length;i++){      
                           var str =  "<th>"+(i+1)+"</th>"+"<th class='sidelist'><a onclick='#'>"+data[1][i]['studentName']+"</a></th>"+"<th>"+Math.round(data[1][i][choice]*100)/100+"</th>";
                           var tr = document.createElement("tr");    
                           tr.innerHTML= str;
                           tbody.appendChild(tr);
                        }
                        for(var k=0;k<data[0]['sequence'];k++){
                            var s2="<a onclick='getClassExerRankingAll("+k+","+classID+","+exerciseID+","+type+","+'"'+id+'"'+")'>"+(k+1)+"</a>";
                            var tr2 = document.createElement("li");  
                            tr2.innerHTML= s2;
                            sh.appendChild(tr2);
                        }
                   }
                   if(data[2].length!=0){
                        for(var j=0;j<data[2][0].length;j++){
                            times[j]=data[2][0][j]['duration'];
                            per[j]=data[2][0][j][choice];
                        }
                   }
                   for(var h=data[3].length;h<data[2][0].length;h++){
                       myPer[h]=0;
                   }
                   //detail
                   for(var i=0;i<data[4].length;i++){      
                        var str =  "<th>"+(i+1)+"</th>"+"<th>"+Math.round(data[4][i]['correct']*100)/100+"</th>"+"<th>"+Math.round(data[4][i]['speed']*100)/100+"</th>"+"<th>"+Math.round(data[4][i]['maxSpeed']*100)/100+
                                "</th>"+"<th>"+Math.round(data[4][i]['backDelete']*100)/100+"</th>"+"<th>"+Math.round(data[4][i]['averageKeyType']*100)/100+"</th>"+"<th>"+Math.round(data[4][i]['maxKeyType']*100)/100+"</th>"+"<th>"+Math.round(data[4][i]['maxInternalTime']*100)/100+
                                "</th>"+"<th>"+Math.round(data[4][i]['countAllKey']*100)/100+"</th>";
                        var tr = document.createElement("tr");    
                        tr.innerHTML= str;
                        detail.appendChild(tr);
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