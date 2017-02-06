<script src="<?php echo JS_URL; ?>/echarts.min.js"></script>
<style>
    .bb{
        color:black;
        
        margin-top: 10px;
        text-decoration: none;
        
    }
    .bbb{
        color:black;
        text-decoration: none;
    }
    .bb,.bbb:hover{
        margin-top: 8px;
        text-decoration: none;
    }
    .Iconr{
        width:220px;
        background:url(<?php echo IMG_URL; ?>/btn_course_option.png);
        background-size:100%;
    }
    .Iconrc{
    margin-left: 60px;   
    }
    .Iconr:hover{
        background:url(<?php echo IMG_URL; ?>/btn_course_opt.png);
        background-size:100%; 
    }
    .Iconr2{
        width:220px;
        background:url(<?php echo IMG_URL; ?>/btn_course_opt.png);
        background-size:100%; 
    }
</style>
<div class="span3">
     <li class="nav-header"><i class="icon-knowlage"></i>课时列表</li>
     <div class="well-topnoradius" style="padding: 8px 0;height:830px;overflow:auto; top:-40px;">
     <ul class="nav nav-list">   
         
         <li ><div id="id_Diligence"><i class="icon-list"></i>&nbsp;<a href="#" style="position:relative;top:7px;left:" class="ahover">勤奋度</a></div></li> 
         <li ><div id="id_classExercise"><i class="icon-list"></i>&nbsp;<a href="#" class="ahover" style="position:relative;top:7px;left:">练习</a></div></li>
        <div style="display: none" id="id_classExerciseLesson">
              <ul class="nav nav-list"> 
                <?php foreach ($array_lesson as $lesson): ?>
                  <li><div ><i class="icon-knowlage"></i>&nbsp;<a href="#" class="ahover" onclick="getClassExer(<?php echo $lesson['lessonID']; ?>)"style="position:relative;top:7px;left:"><?php echo $lesson['lessonName']; ?></a></div></li>                  
              <?php endforeach; ?> 
              </ul>
        </div>
         
         <li ><div id="id_classWork"><i class="icon-list"></i>&nbsp;<a href="#" class="ahover" style="position:relative;top:7px;left:">作业</a></div></li>  
         <div style="display: none" id="id_classWorkLesson">
             <ul class="nav nav-list"> 
               <?php foreach ($array_lesson as $lesson): ?>
                 <li><div ><i class="icon-knowlage"></i>&nbsp;<a href="#" class="ahover" class="nav nav-list" onclick="showClassWork(<?php echo $lesson['lessonID'];?>)" style="position:relative;top:7px;left:"><?php echo $lesson['lessonName']; ?></a></div></li>                
                 <div style="display: none" <?php echo "id='test".$lesson['lessonID']."'"?>>
                     <ul class="nav nav-list">  
                         <?php foreach ($array_work as $work){
                             if($work['lessonID'] == $lesson['lessonID']){     
                                 foreach($array_suite as $suite)
                                 {
                                     if($suite['suiteID'] == $work['suiteID']) {
                                         $level = $work['level'];
                                         $le = "";
                                         if($level == "初级"){
                                             $le = "(初)";
                                         }
                                         if($level == "中级"){
                                             $le = "(中)";
                                         }
                                         if($level == "高级"){
                                             $le = "(高)";
                                         }
                                         if($level == "未分组"){
                                             $le = "(未)";
                                         }
                                         if($level == ""){
                                             $le = "(全)";
                                         }
                           ?>                      
                         <li><div ><a href="#" class="ahover" onclick="getSuiteExercise(<?php echo $work['suiteID'];?>,<?php echo $work['workID'];?>)"><i class="icon-navsearch" style="margin-top:-4px"></i>&nbsp;<?php echo $suite['suiteName'].$le; ?></a></div></li>                                                                                                                                               
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
        <li ><div id="id_classExam"><i class="icon-list"></i>&nbsp;<a href="#" class="ahover" style="position:relative;top:7px;left:">考试</a></div></li>
        <div style="display: none" id="id_classExamLesson">
             <ul class="nav nav-list">    
                 <?php foreach ($array_examList as $examList)
                 {
                     foreach($array_exam as $exam)
                         if($exam['examID'] == $examList['examID'])
                         {
                         ?>
                 <li><div ><i class="icon-navsearch"></i>&nbsp;<a href="#" class="ahover" onclick="getExamExercise(<?php echo $exam['examID'];?>,<?php echo $examList['workID'];?>)" style="position:relative;top:7px;left:"><?php echo $exam['examName']; ?></a></div></li>
                        <?php 
                              }
                 }
?>                                     
             </ul>                   
         </div>
         <li ><div id="id_classabsence"><i class="icon-list"></i>&nbsp;<a href="#" class="ahover" style="position:relative;top:7px;left:">签到</a></div></li>
      </ul>
     </div>
</div>


<div class="span9" id="sp" style="display: none;height: 758px;">
    <div style="position: relative;top: -15px;">
        <div  style="width:100%;overflow: auto;height:110px;">
            <table id="ul1" class="ul1" style="margin-left: 0px;overflow: auto;height:100px;list-style: none;border-radius: 3px;color: gray;position: relative;float:left;overflow: auto;width:100%;color:black;height:100px;">
            </table>
        </div>
        
        
        <div style="width:100%;">
            <div id="div1" style="width:100%;display:inline;float:left;overflow: auto;margin-top: 20px;" >
                <div ><h3 style="">数据类型 <a onclick="daochuexc()">导出excel</a></h3></div>
                <div>
                <table>
                    <tr>
                        <td  id="bg1" style="width:224px;border-radius: 5px;background-color:rgb(218, 225, 218);height: 36px;color:black;">
                            <input type="text" value="123"  id="id" style="display:none;"/>
                            <input type="text" value="123"  id="classID" style="display:none;"/>
                            <input type="text" value="123"  id="exerciseID"  style="display:none;"/>
                            <input type="text" value="123"  id="workID"  style="display:none;"/>
                            <input type="text" value="123"  id="type" style="display:none;" />
                            <input type="text" value="123"  id="choice"  style="display:none;"/>
                            <input type="text" value="123"  id="isExam"  style="display:none;"/>
                            <input type="text" value="123456"  id="qingchu"  style="display:none;"/>
                            <input type="text" value="123"  id="123456"  style="display:none;"/>
                            <a id="correct" onclick="getClassExerRankingBef('correct','bg1')" style="cursor:pointer;">正确率(%)</a>
                        </td>  
                        <td style=" width: 10px"></td>
                    
                        <td id="bg2" style="width:200px;border-radius: 5px;background-color:rgb(218, 225, 218);height: 36px;color:black;">
                            <a  id="speed" class="bl" onclick="getClassExerRankingBef('speed','bg2')" style="cursor:pointer;">速度(字/分)</a>
                        </td>
                        <td style=" width: 10px"></td>
                        <td id="bg5" style="width:200px;border-radius: 5px;background-color:rgb(218, 225, 218);height: 36px;color:black;">
                            <a  id="missing_Number" class="bl" onclick="getClassExerRankingBef('missing_Number','bg5')" style="cursor:pointer;">少打字数(字)</a>
                        </td> 
                        <td style=" width: 10px"></td>
                        <td id="bg3" style="width:200px;border-radius: 5px;background-color:rgb(218, 225, 218);height: 36px;color:black;">
                            <a  id="redundant_Number" onclick="getClassExerRankingBef('redundant_Number','bg3')" style="cursor:pointer;">多打字数(字)</a>
                        </td>
                        <td style=" width: 10px"></td>

                         <td id="bg4" style="width:200px;border-radius: 5px;background-color:rgb(218, 225, 218);height: 36px;color:black;">
                            <a  id="backDelete" onclick="getClassExerRankingBef('backDelete','bg4')" style="cursor:pointer;">回改字数(字)</a>
                        </td>
                    </tr>
                </table>
                </div>
            </div>
           <div id="main" style="display: none;overflow: auto;height:300px;position: relative;top:10px"></div>   
            
        </div>
        <div  style="width:100%;">
            <div id="div11" style="width:100%; display:inline;float:left;overflow: auto;position: relative;top:30px;height:480px;border-radius: 5px;">
                <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>名次</th>
                                <th>学号</th>
                                <th>姓名</th>
                                <th id="name">速度</th>
                                <th id="name">正确率</th>
                                <th id="name">少打字数</th>
                                <th id="name">多打字数</th>
                                <th id="name">回改字数</th>
                                
                            </tr>
                        </thead>
                        <tbody id="bo">
                        </tbody>
                    </table>
            </div>
            <div style="width:70%;float:right;">
                 
                <div id="export" style="diplay:none;width:50px;border-radius: 5px;background-color: #ddd;height: 30px;position: relative;margin: auto 30px -15px auto;">
                    
                </div>
                <div id="title" style="width:100%;overflow: auto;margin-top: 20px">
                    <table  class="table table-bordered table-striped" style="overflow: auto;position: relative;left:20px;width:480px;">
                        <thead>
                                    <tr style="height:40;">
                                        <th style="min-width:35px;">次数</th>
                                        <th style="min-width:45px;">正确率</th>
                                        <th style="min-width:76px;">平均速度</th>

                                        <th style="min-width:60px;">回改字数</th>
                                        <th style="min-width:30px;">平均击键</th>
                                        <th style="min-width:76px;">完成时间</th>
                                        <th style="min-width:55px;">总击键数</th>

                                    </tr>
                        </thead>
                     </table>
                </div>
                <div id="de" style="display:none;width:100%;overflow: auto;height:300px;margin-top: -20px">
                    
                    <table  class="table table-bordered table-striped" style="overflow: auto;position: relative;left:20px;width:480px;">
                            <tbody id="detail" class="detailed">
                            </tbody>
                        </table>
                </div>
                
            </div>
        </div>
    </div>
    
    
    <div id="sh" style="left:700px;display: none;width: 100px;overflow:auto;height:40px;position: relative;top:20px;">
    </div>
    
    <div id="main2" style="left:15px;display: none;width: 750px;overflow: auto;height:400px;position: relative;top:20px;"></div>
</div>
<div class="span9" id="other" style="display: none;height: 758px;">
    <h3 style="alignment-adjust: center;">没有统计数据！</h3>
</div>
<div class="span9" id="Diligence" style="display: none;height: 758px;">
    <div><h3>导出EXCEL : <a href="./index.php?r=teacher/exportDiligence&&classID=<?php echo $classID; ?>"><img src="<?php echo IMG_URL;?>/daochu.jpg" style="width:40px; "></a></h3></div>
    <table class="table table-bordered table-striped" id="table1">
        <thead><th>名次</th><th>名字</th><th>学号</th><th>勤奋度（万字）</th></thead>
    <tbody id="table1"></tbody>
    </table>

</div>
<script>
    //sunpy: switch camera and bulletin
$(document).ready(function(){
    $("#id_classWork").click(function() {
        $("#id_classWorkLesson").toggle(200);
        $("#id_classExerciseLesson").hide();
        $("#id_classExamLesson").hide();
    });  
    $("#id_classExercise").click(function() {
        $("#id_classExerciseLesson").toggle(200);
        $("#id_classExamLesson").hide();
        $("#id_classWorkLesson").hide();
    });  
        $("#id_classExam").click(function() {
        $("#id_classExamLesson").toggle(200);
        $("#id_classWorkLesson").hide();
        $("#id_classExerciseLesson").hide();
    });  
    $("#id_Diligence").click(function() {
        GetDiligence();
    });  
    $("#id_classabsence").click(function() {
        window.open("./index.php?r=teacher/countAbsence&&classID=<?php echo $classID; ?>", 'newwindow', 'height=500,width=600,top=0,left=0,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no,left=500,top=200,')   
 
    }); 
    
    
    
    $("#sw-chat").click(function() {
        $("#chat-box").toggle(200);
    });

    
});
function che(){
    document.getElementById("div11").style.display='none';
    document.getElementById("main").style.display='none';
    document.getElementById("de").style.display='none';
    document.getElementById("export").style.display='none';
    document.getElementById("title").style.display='none';
    $(".bb").css("color","black");
}
function showClassWork(lessonID){
    var str = "#test" + lessonID;
     $("#test" + lessonID).toggle(200);
}
function getSuiteExercise(suiteID,workID){
    document.getElementById("Diligence").style.display='none'; 
    document.getElementById('correct').style.color="#000";
    document.getElementById('speed').style.color="#000";
    document.getElementById('missing_Number').style.color="#000";
    document.getElementById('backDelete').style.color="#000";
    document.getElementById('redundant_Number').style.color="#000";
    document.getElementById('bg1').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg2').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg3').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg4').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg5').style.backgroundColor="rgb(218, 225, 218)";
    
    document.getElementById('id').value="";
    document.getElementById('classID').value="";
    document.getElementById('exerciseID').value="";
    document.getElementById('type').value="";
    document.getElementById('choice').value="";
    document.getElementById('isExam').value="";
        $.ajax({
             type: "POST",
             dataType:"json",
             url: "index.php?r=api/getExercise",
             data: {suiteID:suiteID,
                    workID:workID
                },
             success: function(data){
                 if(data.length==0){
                     document.getElementById('sp').style.display="none";
                     document.getElementById('other').style.display="block";
                     return;
                 }
                 document.getElementById('other').style.display="none";
                   document.getElementById("sp").style.display='block';
                   document.getElementById("de").style.display='none';
                   document.getElementById("export").style.display='none';
                   document.getElementById("title").style.display='none';
                   document.getElementById("div11").style.display='none';
                   var ul = document.getElementById("ul1");          
                   $('#ul1').children().filter('li').remove();
                   $('#ul1').children().filter('tr').remove();
                   $('#ul2').children().filter('li').remove();
                   $('#ul3').children().filter('li').remove();
                   $('#bo').children().filter('tr').remove();
                   $("#sh").children().remove();
                   $('#main').children().remove();
                   var type;
                   for(var i=0;i<data.length;i++){     
                       if(data[i]['type']==1){
                           type='键打练习';
                       }else if(data[i]['type']==2){
                           type='听打练习';
                       }else if(data[i]['type']==3){
                           type='看打练习';
                       }
                       var content=data[i][0]['title'];
                       var allType=type+": "+content;
                       if(content.length<=10)
                           content=content;
                       else
                           content=content.substr(0,10)+"...";
                       type="<font style='color:#F46401'>"+type+"</font>";
                       type=type+"<br> "+content;
                      var str;
                      if(i%3==0)
                          str="";
                      str += "<td><div class='Iconr' id = '"+i+"'><div class='Iconrc'><a title='"+allType+"' class='bb' id='kk"+i+"'"+" onclick='ShowSuiteRank("+data[i]['workID']+","+data[i][0]['exerciseID']+","+data[i]['type']+"),changeshow("+i+")'>"+type+"</a></div></div></td>";       
                      var li;
                      if(i%3==0){
                         li = document.createElement("tr");  
                      }
                      li.innerHTML= str;
                      if(((i%3-1)==0 && i!=0) || i==data.length-1){
                        ul.appendChild(li);
                         }
                   }  
getClassExerciseRanking(workID,0);
},     
                error: function(xhr, type, exception){
                    window.wxc.xcConfirm('出错了...请重新刷新页面', window.wxc.xcConfirm.typeEnum.error);
                    console.log(xhr, "Failed");
                }
         });    
}
var index;
function getClassExerRankingBefBef(ind,i,classID,exerciseID,type){
    index=ind;
    document.getElementById("id").value=i;
    var ii=i;
    document.getElementById("classID").value=classID;
    document.getElementById("exerciseID").value=exerciseID;
    document.getElementById("type").value=type;
    document.getElementById("type").value=choice;
    var tds = document.getElementsByClassName("bb");
    for( var i=0; i<tds.length; i++ ){
        tds[i].style.color="#000";
    }
    document.getElementById('correct').style.color="#000";
    document.getElementById('speed').style.color="#000";
    document.getElementById('missing_Number').style.color="#000";
    document.getElementById('backDelete').style.color="#000";
    document.getElementById('redundant_Number').style.color="#000";
    document.getElementById('bg1').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg2').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg3').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg4').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg5').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById("de").style.display='none';
    document.getElementById("div11").style.display='none';
    document.getElementById("title").style.display='none';
    document.getElementById("main").style.display='none';
    document.getElementById("export").style.display='none';
    var t='kk'+ii;
    document.getElementById(t).style.color="#F46401";
    getExerciseRanking(exerciseID,2);
}
function getStudentRankingBefBef(ind,i,workID,isExam,exerciseID,type){
    index=ind;
    document.getElementById("id").value=i;
    var ii=i;
    document.getElementById("classID").value=workID;
    document.getElementById("isExam").value=isExam;
    document.getElementById("exerciseID").value=exerciseID;
    document.getElementById("type").value=type;
    document.getElementById("choice").value=choice;
    var tds = document.getElementsByClassName("bb");
    for( var i=0; i<tds.length; i++ ){
        tds[i].style.color="#000";
    }
    document.getElementById('correct').style.color="#000";
    document.getElementById('speed').style.color="#000";
    document.getElementById('maxSpeed').style.color="#000";
    document.getElementById('backDelete').style.color="#000";
    document.getElementById('bg1').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg2').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg3').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg4').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById("de").style.display='none';
    document.getElementById("div11").style.display='none';
    document.getElementById("title").style.display='none';
    document.getElementById("main").style.display='none';
    document.getElementById("export").style.display='none';
    var t='kk'+ii;
    document.getElementById(t).style.color="#F46401";
    getClassExerRankingBef('correct','bg1') 
}
var choice;
function getClassExerRankingBef(choice2,bg){
    if(document.getElementById('exerciseID').value==""){
        window.wxc.xcConfirm("请选择题目！", window.wxc.xcConfirm.typeEnum.confirm);
        return;
   }
    document.getElementById('correct').style.color="#000";
    document.getElementById('speed').style.color="#000";
    document.getElementById('missing_Number').style.color="#000";
    document.getElementById('backDelete').style.color="#000";
    document.getElementById('redundant_Number').style.color="#000";
    document.getElementById('bg1').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg2').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg3').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg4').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg5').style.backgroundColor="rgb(218, 225, 218)";
    
    document.getElementById(choice2).style.color="#fff";
    document.getElementById(bg).style.backgroundColor="#F46401";
    document.getElementById("choice").value=choice2;
    var i=document.getElementById("id").value;
    var isExam=document.getElementById("isExam").value;
    var workID=document.getElementById("workID").value;
    var exerciseID=document.getElementById("exerciseID").value;
    var type=document.getElementById("type").value;
    choice=choice2;
    //getClassExerciseRanking(i,classID,exerciseID,type);
    if(isExam == 1){
    ShowExamRank(workID,exerciseID,type,choice);}
    else if(isExam==2){
     ShowExerciseRank(2,exerciseID,choice)   
    }
    else{
     ShowSuiteRank(workID,exerciseID,type,choice);}
    }
function getClassExer(lessonID){
    document.getElementById('correct').style.color="#000";
    document.getElementById('speed').style.color="#000";
    document.getElementById('missing_Number').style.color="#000";
    document.getElementById('backDelete').style.color="#000";
    document.getElementById('redundant_Number').style.color="#000";
    document.getElementById('bg1').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg2').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg3').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg4').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg5').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('id').value="";
    document.getElementById('classID').value="";
    document.getElementById('exerciseID').value="";
    document.getElementById('type').value="";
    document.getElementById('choice').value="";
    document.getElementById('isExam').value="";
        $.ajax({
             type: "POST",
             dataType:"json",
             url: "index.php?r=api/getClassExer",
             data: {lessonID:lessonID,
                },
             success: function(data){
                 if(data.length==0){
                     document.getElementById('sp').style.display="none";
                     document.getElementById('other').style.display="block";
                     return;
                 }
                 document.getElementById('other').style.display="none";
                 document.getElementById("sp").style.display='block';
                 document.getElementById("de").style.display='none';
                 document.getElementById("div11").style.display='none';
                 $("#export").children().filter('tr').remove();
                 document.getElementById("export").style.display='none';
                 document.getElementById("title").style.display='none';
                   var ul = document.getElementById("ul1");          
                   $('#ul1').children().filter('li').remove();
                   $('#ul1').children().filter('tr').remove();
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
                           type='速度练习';
                       }else if(data[i]['type']==2){
                           type='听打练习';
                       }else if(data[i]['type']==3){
                           type='看打练习';
                       }else if(data[i]['type']==4){
                           type='正确率练习';
                       }else if(data[i]['type']==5){
                           type='自由练习';
                       }
                       var content=data[i]['title'];
                       var allType=type+": "+content;
                       if (content.length <= 10)
                            content=content;
                        else
                            content=content.substr(0,10)+"...";
                       type="<font style='color:#F46401'>"+type+"</font>";
                       type=type+"<br> "+content;
                      var str;
                      if(i%3==0)
                          str="";
                      str+= "<td><div class='Iconr' id='"+i+"'><div class='Iconrc'><a style='cursor:pointer;' class='bb' title='"+allType+"' id='kk"+i+"'"+" onclick='getExerciseRanking("+data[i]['exerciseID']+",2,"+ data[i]['type']+"),ShowExerciseRank("+<?php echo $_GET['classID']?>+","+data[i]['exerciseID']+","+ data[i]['type']+"),changeshow("+i+")'>"+type+"</a></div></div></td>";   
                      var li;
                      if(i%3==0){
                         li = document.createElement("tr");      
                      }
                      li.innerHTML= str;
                      if(((i%3-1)==0 && i!=0) || i==data.length-1)
                        ul.appendChild(li);
                   }  
                 },     
                error: function(xhr, type, exception){
                    window.wxc.xcConfirm('出错了...请重新刷新页面', window.wxc.xcConfirm.typeEnum.error);
                    console.log(xhr, "Failed");
                }
         });    
}
function getExamExercise(examID,workID){
    document.getElementById('correct').style.color="#000";
    document.getElementById('speed').style.color="#000";
    document.getElementById('missing_Number').style.color="#000";
    document.getElementById('backDelete').style.color="#000";
    document.getElementById('redundant_Number').style.color="#000";
    document.getElementById('bg1').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg2').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg3').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg4').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg5').style.backgroundColor="rgb(218, 225, 218)";
    
    document.getElementById('id').value="";
    document.getElementById('classID').value="";
    document.getElementById('exerciseID').value="";
    document.getElementById('type').value="";
    document.getElementById('choice').value="";
    document.getElementById('isExam').value="";
        $.ajax({
             type: "POST",
             dataType:"json",
             url: "index.php?r=api/getExamExercise",
             data: {examID:examID,
                 workID:workID,
                },
             success: function(data){
                 if(data.length==0){
                     document.getElementById('sp').style.display="none";
                     document.getElementById('other').style.display="block";
                     return;
                 }
                 document.getElementById('other').style.display="none";
                 document.getElementById("sp").style.display='block';
                 document.getElementById("de").style.display='none';
                 document.getElementById("div11").style.display='none';
                 $("#export").children().filter('tr').remove();
                 document.getElementById("export").style.display='none';
                 document.getElementById("title").style.display='none';
                   var ul = document.getElementById("ul1");          
                   $('#ul1').children().filter('li').remove();
                   $('#ul1').children().filter('tr').remove();
                   $('#ul2').children().filter('li').remove();
                   $('#ul3').children().filter('li').remove();
                   $('#bo').children().filter('tr').remove();
                   $("#sh").children().remove();
                   $('#main').children().remove();
                   var type;
                   for(var i=0;i<data.length;i++){ 
                      if(data[i]['type']==1){
                           type='键打练习';
                       }else if(data[i]['type']==2){
                           type='听打练习';
                       }else if(data[i]['type']==3){
                           type='看打练习';
                       }
                       var content=data[i][0]['title'];
                       var allType=type+": "+content;
                       if (content.length <= 10)
                            content=content;
                        else
                            content=content.substr(0,10)+"...";
                        type="<font style='color:#F46401'>"+type+"</font>";
                       type=type+"<br> "+content;
                       var str;
                       if(i%3==0)
                           str="";
                       str += "<td><div class='Iconr' id='"+i+"'><div class='Iconrc'><a style='cursor:pointer;'class='bb' title='"+allType+"' id='kk"+i+"'"+" onclick='ShowExamRank("+data[i]['workID']+","+data[i][0]['exerciseID']+","+ data[i]['type']+"),changeshow("+i+")'>"+type+"</a></div></div></td>";       
                      var li ;
                      if(i%3==0){
                         li= document.createElement("tr");   
                      }
                      li.innerHTML= str;
                      if(((i%3-1)==0 && i!=0) || i==data.length-1)
                        ul.appendChild(li);
                   }
                   getClassExerciseRanking(workID,1);
                 },     
                error: function(xhr, type, exception){
                    window.wxc.xcConfirm('出错了...请重新刷新页面', window.wxc.xcConfirm.typeEnum.error);
                    console.log(xhr, "Failed");
                }
         });    
}
function GetDiligence(){
        $.ajax({
             type: "POST",
             dataType:"json",
             url: "index.php?r=api/getDiligence&&classID=<?php echo $classID;?>",
             data: {},
         success: function(data){
         $("#table1").children().filter('tr').remove();
         for(var i=0;i<data['student'].length;i++){ 
         var tbody = document.getElementById('table1').lastChild;  
         var tr = document.createElement('tr');           
         var td = document.createElement("td");
         td.innerHTML = i+1;
         tr.appendChild(td);          
         td = document.createElement("td");   
         td.innerHTML = data['student'][i];
         tr.appendChild(td);         
         td = document.createElement("td");  /*真.js*/
         td.innerHTML = data['SID'][i];
         tr.appendChild(td);
         td = document.createElement("td");  /*真.js*/
         td.innerHTML = (data['Dili'][i]/10000).toFixed(2);
         tr.appendChild(td);
         table1.appendChild(tr);          
        }
                   
                 document.getElementById("Diligence").style.display='block';
                 document.getElementById("sp").style.display='none';
             },
                error: function(xhr, type, exception){
                window.wxc.xcConfirm('出错了...请重新刷新页面', window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr, "Failed");
            }
         });           
}
function getClassExerciseRanking(workID,isexam){
        $.ajax({
             type: "POST",
             dataType:"json",
             url: "index.php?r=api/getClassExerciseRanking",
             data: {
                    workID:workID,
//                    exerciseID:exerciseID,
                    isexam:isexam,
             },
         success: function(data){
                 document.getElementById("Diligence").style.display='none';
             },
                error: function(xhr, type, exception){
                window.wxc.xcConfirm('出错了...请重新刷新页面', window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr, "Failed");
            }
         });   
}
function ShowSuiteRank(workID,exerciseID,type,choice){
        var isexam = 0;   
        $.ajax({
             type: "POST",
             dataType:"json",
             url: "index.php?r=api/ShowSuiteRank",
             data: {
                    workID:workID,
                    exerciseID:exerciseID,
                    isexam:isexam,
                    type:type,
                    choice:choice,
             },
         success: function(data){
         $("#bo").children().filter('tr').remove();
         for(var i=0;i<data['name'].length;i++){ 
         var tbody = document.getElementById('bo').lastChild;  
         var tr = document.createElement('tr');           
         var td = document.createElement("td");
         td.innerHTML = i+1;
         tr.appendChild(td);
         td = document.createElement("td");   
         td.innerHTML = data['id'][i];
         tr.appendChild(td);
         td = document.createElement("td");   
         td.innerHTML = data['name'][i];
         tr.appendChild(td);         
         td = document.createElement("td");  /*真.js*/
         td.innerHTML = data['speed'][i];
         tr.appendChild(td);
         td = document.createElement("td");  /*真.js*/
         td.innerHTML = (data['correct'][i])+"%";
         tr.appendChild(td);
         td = document.createElement("td");  /*真.js*/
         td.innerHTML = (data['missing'][i]);
         tr.appendChild(td);
         td = document.createElement("td");  /*真.js*/
         td.innerHTML = (data['redundant'][i]);
         tr.appendChild(td);
         td = document.createElement("td");  /*真.js*/
         td.innerHTML = (data['backDelete'][i]);
         tr.appendChild(td);
         bo.appendChild(tr);          
        } 
    document.getElementById("exerciseID").value=exerciseID;
    document.getElementById("workID").value=workID;
    document.getElementById("type").value=type;
    document.getElementById("isExam").value=isexam;
//    document.getElementById("type").value=choice;
    document.getElementById("div11").style.display='block';
//                 document.getElementById("Diligence").style.display='block';;
    document.getElementById("Diligence").style.display='none';
             },
                error: function(xhr, type, exception){
                window.wxc.xcConfirm('出错了...请重新刷新页面', window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr, "Failed");
            }
         });   
}
function ShowExamRank(workID,exerciseID,type,choice){
        var isexam = 1;   
        $.ajax({
             type: "POST",
             dataType:"json",
             url: "index.php?r=api/ShowSuiteRank",
             data: {
                    workID:workID,
                    exerciseID:exerciseID,
                    isexam:isexam,
                    type:type,
                    choice:choice,
             },
         success: function(data){
         $("#bo").children().filter('tr').remove();
         for(var i=0;i<data['name'].length;i++){ 
         var tbody = document.getElementById('bo').lastChild;  
         var tr = document.createElement('tr');           
         var td = document.createElement("td");
         td.innerHTML = i+1;
         tr.appendChild(td);
         td = document.createElement("td");   
         td.innerHTML = data['id'][i];
         tr.appendChild(td);
         td = document.createElement("td");   
         td.innerHTML = data['name'][i];
         tr.appendChild(td);         
         td = document.createElement("td");  /*真.js*/
         td.innerHTML = data['speed'][i];
         tr.appendChild(td);
         td = document.createElement("td");  /*真.js*/
         td.innerHTML = (data['correct'][i])+"%";
         tr.appendChild(td);
         td = document.createElement("td");  /*真.js*/
         td.innerHTML = (data['missing'][i]);
         tr.appendChild(td);
         td = document.createElement("td");  /*真.js*/
         td.innerHTML = (data['redundant'][i]);
         tr.appendChild(td);
         td = document.createElement("td");  /*真.js*/
         td.innerHTML = (data['backDelete'][i]);
         tr.appendChild(td);
         bo.appendChild(tr);          
        } 
    document.getElementById("exerciseID").value=exerciseID;
    document.getElementById("workID").value=workID;
    document.getElementById("type").value=type;
     document.getElementById("isExam").value=isexam;
//    document.getElementById("type").value=choice;
    document.getElementById("div11").style.display='block';
    document.getElementById("Diligence").style.display='none';
             },
                error: function(xhr, type, exception){
                window.wxc.xcConfirm('出错了...请重新刷新页面', window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr, "Failed");
            }
         });   
}

function getExerciseRanking(exerciseID,isexam,type){
        $.ajax({
             type: "POST",
             dataType:"json",
             url: "index.php?r=api/getExerciseRanking",
             data: {
                    exerciseID:exerciseID,
                    isexam:isexam,
                    type:type,
             },
         success: function(data){
        ShowExerciseRank("+<?php echo $_GET['classID']?>+",exerciseID,type);     
        document.getElementById("Diligence").style.display='none';
             },
                error: function(xhr, type, exception){
                window.wxc.xcConfirm('出错了...请重新刷新页面', window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr, "Failed");
            }
         });
    document.getElementById("type").value=type;
}

function ShowExerciseRank(workID,exerciseID,choice){
        isexam = 2; 
        $.ajax({
             type: "POST",
             dataType:"json",
             url: "index.php?r=api/ShowExerciseRank",
             data: {
                    workID:workID,
                    exerciseID:exerciseID,
                    isexam:isexam,
                    choice:choice,
             },
         success: function(data){
         $("#bo").children().filter('tr').remove();
         for(var i=0;i<data['name'].length;i++){ 
         var tbody = document.getElementById('bo').lastChild;  
         var tr = document.createElement('tr');           
         var td = document.createElement("td");
         td.innerHTML = i+1;
         tr.appendChild(td); 
         td = document.createElement("td");   
         td.innerHTML = data['id'][i];
         tr.appendChild(td); 
         td = document.createElement("td");   
         td.innerHTML = data['name'][i];
         tr.appendChild(td);         
         td = document.createElement("td");  /*真.js*/
         td.innerHTML = data['speed'][i];
         tr.appendChild(td);
         td = document.createElement("td");  /*真.js*/
         td.innerHTML = (data['correct'][i])+"%";
         tr.appendChild(td);
         td = document.createElement("td");  /*真.js*/
         td.innerHTML = (data['missing'][i]);
         tr.appendChild(td);
         td = document.createElement("td");  /*真.js*/
         td.innerHTML = (data['redundant'][i]);
         tr.appendChild(td);
         td = document.createElement("td");  /*真.js*/
         td.innerHTML = (data['backDelete'][i]);
         tr.appendChild(td);
         bo.appendChild(tr);          
        } 
    document.getElementById("exerciseID").value=exerciseID;
    document.getElementById("workID").value=workID;
     document.getElementById("isExam").value=isexam;
//    document.getElementById("type").value=choice;
    document.getElementById("div11").style.display='block';
    document.getElementById("Diligence").style.display='none';
    document.getElementById("exerciseID").value=exerciseID;
     document.getElementById("isExam").value=isexam;
//    document.getElementById("type").value=choice;
    document.getElementById("div11").style.display='block';
    document.getElementById("Diligence").style.display='none';        
    
    
    },
                error: function(xhr, type, exception){
                window.wxc.xcConfirm('出错了...请重新刷新页面', window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr, "Failed");
            }
         });   
}
function changeshow(i){
   var clean = document.getElementById("qingchu").value;
   document.getElementById(clean).className = 'Iconr';
   document.getElementById(i).className = 'Iconr2';
   document.getElementById("qingchu").value=i;
}
function daochuexc(){
    var exerciseID = document.getElementById("exerciseID").value;
    var isExam = document.getElementById("isExam").value;
    var type = document.getElementById("type").value;
    if (isExam==2){
    window.location.href="./index.php?r=teacher/Guidetable&&workID=<?php echo $work['workID'];?>&&isExam="+isExam+"&&type="+type+"&&exerciseID="+exerciseID;}
    else if(isExam==""){
        window.wxc.xcConfirm("请选择题目！", window.wxc.xcConfirm.typeEnum.confirm);
    }
    else{
    var workID = document.getElementById("workID").value;
    window.location.href="./index.php?r=teacher/Guidetable2&&workID="+workID+"&&isExam="+isExam+"&&type="+type+"&&exerciseID="+exerciseID;    
    }
}
</script>