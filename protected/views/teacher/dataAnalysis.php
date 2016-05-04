<script src="<?php echo JS_URL; ?>/echarts.min.js"></script>
<style>
    .bb{
        color:black;
        margin-left: 20px;
        margin-top: 10px;
        text-decoration: none;
        
    }
    .bbb{
        color:black;
        text-decoration: none;
    }
    .bb,.bbb:hover{
        text-decoration: none;
    }
</style>
<div class="span3">
     <li class="nav-header"><i class="icon-knowlage"></i>课时列表</li>
     <div class="well-topnoradius" style="padding: 8px 0;height:830px;overflow:auto; top:-40px;">
     <ul class="nav nav-list">       
         <li ><div id="id_classExercise"><i class="icon-list"></i><a href="#" style="position:relative;top:6px;left:">练习</a></div></li>
        <div style="display: none" id="id_classExerciseLesson">
              <ul class="nav nav-list"> 
                <?php foreach ($array_lesson as $lesson): ?>
                  <li><div ><a href="#" onclick="getClassExer(<?php echo $lesson['lessonID']; ?>)"><i class="icon-list"></i><?php echo $lesson['lessonName']; ?></a></div></li>                  
              <?php endforeach; ?> 
              </ul>
        </div>
         
         <li ><div id="id_classWork"><i class="icon-list"></i><a href="#" style="position:relative;top:6px;left:">作业</a></div></li>  
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
                 
         <li ><div id="id_classExam"><i class="icon-list"></i><a href="#" style="position:relative;top:6px;left:">考试</a></div></li>
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


<div class="span9" id="sp" style="display: none;height: 830px;">
    <div style="position: relative;top: -15px;">
        <div  style="width:100%;overflow: auto;height:110px;">
            <table id="ul1" class="ul1" style="margin-left: -20px;overflow: auto;height:100px;list-style: none;border-radius: 3px;color: gray;position: relative;float:left;overflow: auto;width:100%;color:black;height:100px;">
            </table>
        </div>
        
        
        <div style="width:100%;">
            <div id="div1" style="width:30%;display:inline;float:left;overflow: auto;margin-top: 40px;" >
                <h3>数据类型</h3>
                <table>
                    <tr>
                        <td  id="bg1" style="width:224px;border-radius: 5px;background-color:rgb(218, 225, 218);height: 36px;color:black;">
                            <input type="text" value="123"  id="id" style="display:none;"/>
                            <input type="text" value="123"  id="classID" style="display:none;"/>
                            <input type="text" value="123"  id="exerciseID"  style="display:none;"/>
                            <input type="text" value="123"  id="type" style="display:none;" />
                            <input type="text" value="123"  id="choice"  style="display:none;"/>
                            <input type="text" value="123"  id="isExam"  style="display:none;"/>
                            <a id="correct" onclick="getClassExerRankingBef('correct','bg1')" style="cursor:pointer;">正确率(%)</a>
                        </td>  
                    </tr>
                    <tr><td style="height:10px;"></td></tr>
                    <tr>
                        <td id="bg2" style="width:200px;border-radius: 5px;background-color:rgb(218, 225, 218);height: 36px;color:black;">
                            <a  id="speed" class="bl" onclick="getClassExerRankingBef('speed','bg2')" style="cursor:pointer;">速度(字/秒)</a>
                        </td>
                    </tr>
                    <tr><td style="height:10px;"></td></tr>
                    <tr>
                        <td id="bg3" style="width:200px;border-radius: 5px;background-color:rgb(218, 225, 218);height: 36px;color:black;">
                            <a  id="maxSpeed" onclick="getClassExerRankingBef('maxSpeed','bg3')" style="cursor:pointer;">最大速度(字/秒)</a>
                        </td>
                    </tr>
                    <tr><td style="height:10px;"></td></tr>
                    <tr>
                        <td id="bg4" style="width:200px;border-radius: 5px;background-color:rgb(218, 225, 218);height: 36px;color:black;">
                            <a  id="backDelete" onclick="getClassExerRankingBef('backDelete','bg4')" style="cursor:pointer;">回改字数(字)</a>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="main" style="display: none;overflow: auto;height:300px;position: relative;top:10px"></div>   
            
        </div>
        <div  style="width:100%;">
            <div id="div11" style="width:30%; display:inline;float:left;overflow: auto;position: relative;top:30px;height:480px;border-radius: 5px;">
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
                       <!--     <thead>
                                <tr style="height:40px;">
                                    <th>成绩</th>
                                    <th>正确率</th>
                                    <th>平均速度</th>
                                    
                                    <th>回改字数</th>
                                    <th>平均击键</th>
                                    <th>完成时间</th>
                                    <th>总击键数</th>

                                </tr>
                            </thead>  -->
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
<div class="span9" id="other" style="display: none;height: 830px;">
    <h3 style="alignment-adjust: center;">没有统计数据！</h3>
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
    document.getElementById('correct').style.color="#000";
    document.getElementById('speed').style.color="#000";
    document.getElementById('maxSpeed').style.color="#000";
    document.getElementById('backDelete').style.color="#000";
    document.getElementById('bg1').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg2').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg3').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg4').style.backgroundColor="rgb(218, 225, 218)";
    
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
                       type=type+": "+content;
                      var str;
                      if(i%3==0)
                          str="";
                      str += "<td><a title='"+allType+"' class='bb' id='kk"+i+"'"+" onclick='getStudentRankingBefBef("+"1"+","+i+","+data[i]['workID']+","+"0"+","+data[i][0]['exerciseID']+","+ data[i]['type']+")'>"+type+"</a></td>";       
                      var li;
                      if(i%3==0){
                         li = document.createElement("tr");  
                      }
                      li.innerHTML= str;
                      if(((i%3-1)==0 && i!=0) || i==data.length-1){
                        ul.appendChild(li);
                         }
                   }  
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
}
var choice;
function getClassExerRankingBef(choice2,bg){
    if(document.getElementById('exerciseID').value==""){
        window.wxc.xcConfirm("请选择题目！", window.wxc.xcConfirm.typeEnum.confirm);
        return;
    }
    document.getElementById('correct').style.color="#000";
    document.getElementById('speed').style.color="#000";
    document.getElementById('maxSpeed').style.color="#000";
    document.getElementById('backDelete').style.color="#000";
    document.getElementById('bg1').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg2').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg3').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg4').style.backgroundColor="rgb(218, 225, 218)";
    
    document.getElementById(choice2).style.color="#fff";
    document.getElementById(bg).style.backgroundColor="#F46401";
    document.getElementById("choice").value=choice2;
    var i=document.getElementById("id").value;
    var classID=document.getElementById("classID").value;
    var isExam=document.getElementById("isExam").value;
    var exerciseID=document.getElementById("exerciseID").value;
    var type=document.getElementById("type").value;
    choice=choice2;
    if(index==0)
        getClassExerRanking(i,classID,exerciseID,type);
    else
        getStudentRanking(i,classID,isExam,exerciseID,type);
        
}
function getClassExer(lessonID){
    document.getElementById('correct').style.color="#000";
    document.getElementById('speed').style.color="#000";
    document.getElementById('maxSpeed').style.color="#000";
    document.getElementById('backDelete').style.color="#000";
    document.getElementById('bg1').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg2').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg3').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg4').style.backgroundColor="rgb(218, 225, 218)";
    
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
                       type=type+": "+content;
                      var str;
                      if(i%3==0)
                          str="";
                      str+= "<td><a style='cursor:pointer;' class='bb' title='"+allType+"' id='kk"+i+"'"+" onclick='getClassExerRankingBefBef("+"0"+","+i+","+<?php echo $_GET['classID']?>+","+data[i]['exerciseID']+","+ data[i]['type']+")'>"+type+"</a></td>";   
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
    document.getElementById('maxSpeed').style.color="#000";
    document.getElementById('backDelete').style.color="#000";
    document.getElementById('bg1').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg2').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg3').style.backgroundColor="rgb(218, 225, 218)";
    document.getElementById('bg4').style.backgroundColor="rgb(218, 225, 218)";
    
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
                       type=type+": "+content;
                       var str;
                       if(i%3==0)
                           str="";
                       str += "<td><a style='cursor:pointer;'class='bb' title='"+allType+"' id='kk"+i+"'"+" onclick='getStudentRankingBefBef("+"1"+","+i+","+data[i]['workID']+","+"1"+","+data[i][0]['exerciseID']+","+ data[i]['type']+")'>"+type+"</a></td>";       
                      var li ;
                      if(i%3==0){
                         li= document.createElement("tr");   
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
function getStudentRanking(ii,workID,isExam,exerciseID,type){
//        var tds = document.getElementsByClassName("bb");
//        for( var i=0; i<tds.length; i++ ){
//            tds[i].style.color="#000";
//        }
//        var t='kk'+ii;
//        document.getElementById(t).style.color="#F46401";
        var province = document.getElementsByName("selectID");
        var a;
//        var choice;
//        for(a=0; a<province.length; a++){//遍历单选框
//            if(province[a].checked){
//                  choice = province[a].value;
//            }
//        }
        
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
                 document.getElementById("div11").style.display='block';
                 $("#export").children().filter('tr').remove();
                 document.getElementById("export").style.display='none';
                 document.getElementById("title").style.display='none';
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
                       if(choice=='correct'){
                           var  str = "<th>"+(i+1)+"</th>"+"<th><a style='cursor:pointer;' class='bbb' id='kkk"+i+"'"+" onclick='getStudentRankingAll("+i+","+workID+","+isExam+","+exerciseID+","+type+","+'"'+data[0][i]['time']+'"'+")'>"+data[0][i]['studentName']+"</a></th>"+"<th>"+Math.round(data[0][i][choice]*100)/100+"%"+"</th>";
                        }else if(choice=='backDelete'){
                            var  str = "<th>"+(i+1)+"</th>"+"<th><a style='cursor:pointer;' class='bbb' id='kkk"+i+"'"+" onclick='getStudentRankingAll("+i+","+workID+","+isExam+","+exerciseID+","+type+","+'"'+data[0][i]['time']+'"'+")'>"+data[0][i]['studentName']+"</a></th>"+"<th>"+Math.round(Math.round(data[0][i][choice]*100)/100)+"</th>";
                        }else{
                            var  str = "<th>"+(i+1)+"</th>"+"<th><a style='cursor:pointer;' class='bbb' id='kkk"+i+"'"+" onclick='getStudentRankingAll("+i+","+workID+","+isExam+","+exerciseID+","+type+","+'"'+data[0][i]['time']+'"'+")'>"+data[0][i]['studentName']+"</a></th>"+"<th>"+Math.round(data[0][i][choice]*100)/100+"</th>";
                        }
                      
                      var tr = document.createElement("tr");               
                      tr.innerHTML= str;
                      tbody.appendChild(tr);
                   }
                   if(data[1].length!=0){
                        for(var j=0;j<data[1][0].length;j++){
                            times[j]=data[1][0][j]['duration'];
                            //per[j]=data[1][0][j][choice];
                            per[j]=Math.round(data[1][0][j][choice]*100)/100;
                        }
                    }
                   
                    var myChart = echarts.init(document.getElementById('main'));
                    var option = {
                        title: {
                            text: ''
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
                                name:"s",
                                boundaryGap : false,
                                data : times
                            }
                        ],
                        yAxis : [
                            {
                                type : 'value',
                                name:choose
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
                window.wxc.xcConfirm('出错了...请重新刷新页面', window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr, "Failed");
            }
         }); 
}
function getStudentRankingAll(ii,workID,isExam,exerciseID,type,name){
        if(name==0){
            alert("请选择题目");
            return ;
        }
        var province = document.getElementsByName("selectID");
        var a;
//        var choice;
//        for(a=0; a<province.length; a++){//遍历单选框
//            if(province[a].checked){
//                  choice = province[a].value;
//            }
//        }
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
                 document.getElementById("export").style.display='block';
                 document.getElementById("title").style.display='block';
                   var tbody = document.getElementById("bo");      
                   $('#bo').children().filter('tr').remove();
                   $('#ul2').children().filter('li').remove();
                   $('#ul3').children().filter('li').remove();
                   $('#detail').children().filter('tr').remove();
                   var expor=document.getElementById("export");
                   $("#export").children().filter('tr').remove();
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
                       //myPer[h]=data[2][h][choice];
                       myPer[h]=Math.round(data[2][h][choice]*100)/100;
                       
                   }
                   for(var i=0;i<data[0].length;i++){  
                      if(choice=='correct'){
                        var  str = "<th>"+(i+1)+"</th>"+"<th><a style='cursor:pointer;' class='bbb' id='kkk"+i+"'"+" onclick='getStudentRankingAll("+i+","+workID+","+isExam+","+exerciseID+","+type+","+'"'+data[0][i]['time']+'"'+")'>"+data[0][i]['studentName']+"</a></th>"+"<th>"+Math.round(data[0][i][choice]*100)/100+"%"+"</th>";
                      }else if(choice=='backDelete'){
                          var  str = "<th>"+(i+1)+"</th>"+"<th><a style='cursor:pointer;' class='bbb' id='kkk"+i+"'"+" onclick='getStudentRankingAll("+i+","+workID+","+isExam+","+exerciseID+","+type+","+'"'+data[0][i]['time']+'"'+")'>"+data[0][i]['studentName']+"</a></th>"+"<th>"+Math.round(Math.round(data[0][i][choice]*100)/100)+"</th>";
                      }else{
                          var  str = "<th>"+(i+1)+"</th>"+"<th><a style='cursor:pointer;' class='bbb' id='kkk"+i+"'"+" onclick='getStudentRankingAll("+i+","+workID+","+isExam+","+exerciseID+","+type+","+'"'+data[0][i]['time']+'"'+")'>"+data[0][i]['studentName']+"</a></th>"+"<th>"+Math.round(data[0][i][choice]*100)/100+"</th>";
                      }
                      var tr = document.createElement("tr");               
                      tr.innerHTML= str;
                      tbody.appendChild(tr);
                   }
                   var tds = document.getElementsByClassName("bbb");
                    for( var i=0; i<tds.length; i++ ){
                        tds[i].style.color="#000";
                    }
                    var t='kkk'+ii;
                    document.getElementById(t).style.color="#F46401";
                   if(data[1].length!=0){
                        for(var j=0;j<data[1][0].length;j++){
                            times[j]=data[1][0][j]['duration'];
                            //per[j]=data[1][0][j][choice];
                            per[j]=Math.round(data[1][0][j][choice]*100)/100;
                        }
                    }
                   for(var h=data[2].length;h<data[1][0].length;h++){
                       myPer[h]=0;
                       
                   }
                   //detail
                    var str =  "<th style='min-width:35px;'>"+1+"</th>"+"<th style='min-width:45px;'>"+Math.round(data[3]['correct']*100)/100+"</th>"+"<th style='min-width:76px;'>"+Math.round(data[3]['speed']*100)/100+"</th>"+"<th style='min-width:60px;'>"+
                            Math.round(data[3]['backDelete']*100)/100+"</th>"+"<th style='min-width:30px;'>"+Math.round(data[3]['averageKeyType']*100)/100+"</th>"+"<th style='min-width:76px;'>"+data[3]['createTime']+"</th>"+
                            "</th>"+"<th style='min-width:55px;'>"+Math.round(data[3]['countAllKey']*100)/100+"</th>";
                    var tr = document.createElement("tr");    
                    tr.innerHTML= str;
                    detail.appendChild(tr);
                    //average
                    var str =  "<th style='color:red'>"+"最高"+"</th>"+"<th style='color:red'>"+Math.round(data[4]['correct']*100)/100+"</th>"+"<th style='color:red'>"+Math.round(data[4]['speed']*100)/100+"</th>"
                            +"<th style='color:red'>"+Math.round(data[4]['backDelete']*100)/100+"</th>"+"<th style='color:red'>"+Math.round(data[4]['averageKeyType']*100)/100+"</th>"+"<th style='color:red'>"+data[4]['createTime']+
                            "</th>"+"<th style='color:red'>"+Math.round(data[4]['countAllKey']*100)/100+"</th>";
                    var tr = document.createElement("tr");  
                    
                    tr.innerHTML= str;
                    detail.appendChild(tr);
                     
                   if(data[4].length!=0){
                           var str = "<a style='position:relative;top:5px;left:10px;color:black;' href='index.php?r=teacher/exports&&choice="+choice+"&&workID="+workID+"&&isExam="+isExam+"&&exerciseID="+exerciseID+"&&type="+type+"&&name="+name+"'>"+"导出"+"</a>";
                           var tr = document.createElement("tr");    
                           tr.innerHTML= str;
                           expor.appendChild(tr);
                    }
                    var myChart = echarts.init(document.getElementById('main'));
                    var option = {
                        title: {
                            text: ''
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
                                name:"s",
                                boundaryGap : false,
                                data : times
                            }
                        ],
                        yAxis : [
                            {
                                type : 'value',
                                name:choose
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
                window.wxc.xcConfirm('出错了...请重新刷新页面', window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr, "Failed");
            }
         }); 
}
function getClassExerRanking(ii,classID,exerciseID,type){
//        var tds = document.getElementsByClassName("bb");
//        for( var i=0; i<tds.length; i++ ){
//            tds[i].style.color="#000";
//        }
//        var t='kk'+ii;
//        document.getElementById(t).style.color="#F46401";
        var province = document.getElementsByName("selectID");
        var a;
//        var choice;
//        for(a=0; a<province.length; a++){//遍历单选框
//            if(province[a].checked){
//                  choice = province[a].value;
//            }
//        }
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
                 document.getElementById("div11").style.display='block';
                 document.getElementById("de").style.display='none';
                 document.getElementById("sh").style.display='none';
                 document.getElementById("title").style.display='none';
                 $("#export").children().filter('tr').remove();
                 document.getElementById("export").style.display='none';
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
                       if(choice=='correct'){
                           var str =  "<th>"+(i+1)+"</th>"+"<th><a style='color:black;cursor:pointer;'  class='bbb' id='kkk"+i+"'"+" onclick='getClassExerRankingAll("+i+","+"0"+","+classID+","+exerciseID+","+'"'+type+'"'+","+'"'+data[0][i]['studentID']+'"'+")'>"+data[0][i]['studentName']+"</a></th>"+"<th>"+Math.round(data[0][i][choice]*100)/100+"%"+"</th>";
                        }else if(choice=='backDelete'){
                            var str =  "<th>"+(i+1)+"</th>"+"<th><a style='color:black;cursor:pointer;'  class='bbb' id='kkk"+i+"'"+" onclick='getClassExerRankingAll("+i+","+"0"+","+classID+","+exerciseID+","+'"'+type+'"'+","+'"'+data[0][i]['studentID']+'"'+")'>"+data[0][i]['studentName']+"</a></th>"+"<th>"+Math.round(Math.round(data[0][i][choice]*100)/100)+"</th>";
                        }else{
                            var str =  "<th>"+(i+1)+"</th>"+"<th><a style='color:black;cursor:pointer;'  class='bbb' id='kkk"+i+"'"+" onclick='getClassExerRankingAll("+i+","+"0"+","+classID+","+exerciseID+","+'"'+type+'"'+","+'"'+data[0][i]['studentID']+'"'+")'>"+data[0][i]['studentName']+"</a></th>"+"<th>"+Math.round(data[0][i][choice]*100)/100+"</th>";
                        }
                       var tr = document.createElement("tr");               
                       tr.innerHTML= str;
                       tbody.appendChild(tr);
                    } 
                   
                   if(data[1].length!=0){
                        for(var j=0;j<data[1][0].length;j++){
                            times[j]=data[1][0][j]['duration'];
                            //per[j]=data[1][0][j][choice];
                            per[j]=Math.round(data[1][0][j][choice]*100)/100;
                        }
                   }
                   
                   var myChart = echarts.init(document.getElementById('main'));
                    var option = {
                        title: {
                            text: ''
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
                                name:"s",
                                boundaryGap : false,
                                data : times
                            }
                        ],
                        yAxis : [
                            {
                                type : 'value',
                                name:choose
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
                window.wxc.xcConfirm('出错了...请重新刷新页面', window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr, "Failed");
            }
         }); 
}
function getClassExerRankingAll(ii,seq,classID,exerciseID,type,id){
        if(id==0){
            alert("请选择题目");
            return ;
        }
//        var province = document.getElementsByName("selectID");
        var a;
//        var choice;
//        for(a=0; a<province.length; a++){//遍历单选框
//            if(province[a].checked){
//                  choice = province[a].value;
//            }
//        }
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
                 document.getElementById("title").style.display='block';
                 
                 document.getElementById("export").style.display='block';
                 
                   var ul = document.getElementById("ul2");          
                   var tbody = document.getElementById("bo"); 
                   var detail = document.getElementById("detail"); 
                   var sele = document.getElementById("sequence"); 
                   var sh=document.getElementById("sh");
                   var expor=document.getElementById("export");
                   
                   $("#sh").children().filter('b').remove();
                   $('#sequence').show();
                   $('#bo').children().filter('tr').remove();
                   document.getElementById("de").style.display='block';
                   $('#ul2').children().filter('li').remove();
                   $('#ul3').children().filter('li').remove();
                   $('#detail').children().filter('tr').remove();
                   $("#export").children().filter('tr').remove();
                   var times = new Array();
                   var per=new Array();
//                   var myTimes = new Array();
//                   var myPer=new Array();
                    var myTimes=new Array();
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
                       myPer[h]=new Array();
                        for(var hh=0;hh<data[3][h].length;hh++){
                            myPer[h][hh]=Array();
                             //myTimes[h][hh]=data[3][h][hh]['time'];
                             //myPer[h][hh]=data[3][h][hh][choice];
                             myPer[h][hh]=Math.round(data[3][h][hh][choice]*100)/100;
                        }
                   }
                   if(data[0]['sequence']==1){
                        for(var i=0;i<data[1].length;i++){  
                            var str;
                            if(choice=='correct'){
                                str =  "<th>"+(i+1)+"</th>"+"<th class='sidelist'><a style='cursor: pointer;' class='bbb' id='kkk"+i+"'"+" onclick='getClassExerRankingAll("+i+","+"0"+","+classID+","+exerciseID+","+'"'+type+'"'+","+'"'+data[1][i]['studentID']+'"'+")'>"+data[1][i]['studentName']+"</a></th>"+"<th>"+Math.round(data[1][i][choice]*100)/100+"%"+"</th>";
                            }else if(choice=='backDelete'){
                                str =  "<th>"+(i+1)+"</th>"+"<th class='sidelist'><a style='cursor: pointer;' class='bbb' id='kkk"+i+"'"+" onclick='getClassExerRankingAll("+i+","+"0"+","+classID+","+exerciseID+","+'"'+type+'"'+","+'"'+data[1][i]['studentID']+'"'+")'>"+data[1][i]['studentName']+"</a></th>"+"<th>"+Math.round(Math.round(data[1][i][choice]*100)/100)+"</th>";
                            }else{
                                str =  "<th>"+(i+1)+"</th>"+"<th class='sidelist'><a style='cursor: pointer;' class='bbb' id='kkk"+i+"'"+" onclick='getClassExerRankingAll("+i+","+"0"+","+classID+","+exerciseID+","+'"'+type+'"'+","+'"'+data[1][i]['studentID']+'"'+")'>"+data[1][i]['studentName']+"</a></th>"+"<th>"+Math.round(data[1][i][choice]*100)/100+"</th>";
                            }
                           var tr = document.createElement("tr");    
                           tr.innerHTML= str;
                           tbody.appendChild(tr);
                        }  
                   }else{
                        $("#sh").show();
                        for(var i=0;i<data[1].length;i++){   
                        var str;
                            if(choice=='correct'){
                                str =  "<th>"+(i+1)+"</th>"+"<th class='sidelist'><a style='cursor: pointer;' class='bbb' id='kkk"+i+"'"+" onclick='getClassExerRankingAll("+i+","+"0"+","+classID+","+exerciseID+","+'"'+type+'"'+","+'"'+data[1][i]['studentID']+'"'+")'>"+data[1][i]['studentName']+"</a></th>"+"<th>"+Math.round(data[1][i][choice]*100)/100+"%"+"</th>";
                            }else if(choice=='backDelete'){
                                str =  "<th>"+(i+1)+"</th>"+"<th class='sidelist'><a style='cursor: pointer;' class='bbb' id='kkk"+i+"'"+" onclick='getClassExerRankingAll("+i+","+"0"+","+classID+","+exerciseID+","+'"'+type+'"'+","+'"'+data[1][i]['studentID']+'"'+")'>"+data[1][i]['studentName']+"</a></th>"+"<th>"+Math.round(Math.round(data[1][i][choice]*100)/100)+"</th>";
                            
                            }else{
                                str =  "<th>"+(i+1)+"</th>"+"<th class='sidelist'><a style='cursor: pointer;' class='bbb' id='kkk"+i+"'"+" onclick='getClassExerRankingAll("+i+","+"0"+","+classID+","+exerciseID+","+'"'+type+'"'+","+'"'+data[1][i]['studentID']+'"'+")'>"+data[1][i]['studentName']+"</a></th>"+"<th>"+Math.round(data[1][i][choice]*100)/100+"</th>";
                            }
                           var tr = document.createElement("tr");    
                           tr.innerHTML= str;
                           tbody.appendChild(tr);
                        }
//                        for(var k=0;k<data[0]['sequence'];k++){
//                            var s2="<a onclick='getClassExerRankingAll("+ii+","+k+","+classID+","+exerciseID+","+type+","+'"'+id+'"'+")'>"+(k+1)+"</a>";
//                            var tr2 = document.createElement("li");  
//                            tr2.innerHTML= s2;
//                            sh.appendChild(tr2);
//                        }
                   }
                   var tds = document.getElementsByClassName("bbb");
                    for( var o=0; o<tds.length; o++ ){
                        tds[o].style.color="#000";
                    }
                    var t='kkk'+ii;
                    document.getElementById(t).style.color="#F46401";
                   if(data[2].length!=0){
                        for(var j=0;j<data[2][0].length;j++){
                            times[j]=data[2][0][j]['duration'];
                            //per[j]=data[2][0][j][choice];
                            per[j]=Math.round(data[2][0][j][choice]*100)/100;
                        }
                   }
                   for(var h=0;h<data[3].length;h++){
                       for(var hh=data[3][h].length;hh<data[2][0].length;hh++){
                            myPer[h][hh]=0;
                        }
                    }
                   //detail
                   var len=data[4].length-1;
                   var str1,str2,str3,str4,str5,str6,str7,str8;
                   for(var i=0;i<data[4].length;i++){      
                       if(i+1==data[4][len]['icon1']){
                           str1="<th style='color:blue;min-width:45px;'>"+Math.round(data[4][i]['correct']*100)/100+"</th>";
                        }else{
                            str1="<th style='min-width:45px;'>"+Math.round(data[4][i]['correct']*100)/100+"</th>";
                        }
                        if(i+1==data[4][len]['icon2']){
                           str2="<th style='color:blue;min-width:76px;'>"+Math.round(data[4][i]['speed']*100)/100+"</th>";
                        }else{
                            str2="<th style='min-width:76px;'>"+Math.round(data[4][i]['speed']*100)/100+"</th>";
                        }
//                        if(i+1==data[4][len]['icon3']){
//                           str3="<th style='color:blue;'>"+Math.round(data[4][i]['maxSpeed']*100)/100+"</th>";
//                        }else{
//                            str3="<th>"+Math.round(data[4][i]['maxSpeed']*100)/100+"</th>";
//                        }
                        if(i+1==data[4][len]['icon4']){
                           str4="<th style='color:blue;min-width:60px;'>"+Math.round(data[4][i]['backDelete']*100)/100+"</th>";
                        }else{
                            str4="<th style='min-width:60px;'>"+Math.round(data[4][i]['backDelete']*100)/100+"</th>";
                        }
                        if(i+1==data[4][len]['icon6']){
                           str5="<th style='color:blue;min-width:30px;'>"+Math.round(data[4][i]['averageKeyType']*100)/100+"</th>";
                        }else{
                            str5="<th style='min-width:30px;'>"+Math.round(data[4][i]['averageKeyType']*100)/100+"</th>";
                        }
//                        if(i+1==data[4][len]['icon7']){
//                           str6="<th style='color:blue;'>"+Math.round(data[4][i]['maxKeyType']*100)/100+"</th>";
//                        }else{
//                            str6="<th>"+Math.round(data[4][i]['maxKeyType']*100)/100+"</th>";
//                        }
                        if(i+1==data[4][len]['icon9']){
                           str7="<th style='color:blue;min-width:76px;'>"+data[4][i]['finishDate']+"</th>";
                        }else{
                            
                            str7="<th style='min-width:76px;'>"+data[4][i]['finishDate']+"</th>";
                        }
                        if(i+1==data[4][len]['icon8']){
                           str8="<th style='color:blue;min-width:55px;'>"+Math.round(data[4][i]['countAllKey']*100)/100+"</th>";
                        }else{
                            str8="<th style='min-width:55px;'>"+Math.round(data[4][i]['countAllKey']*100)/100+"</th>";
                        }
                        var str =  "<th style='min-width:35px;'>"+(i+1)+"</th>"+str1+str2+str4+str5+str7+str8;
                        var tr = document.createElement("tr");    
                        tr.innerHTML= str;
                        detail.appendChild(tr);
                     }  
                     //max
                     
                    var str =  "<th style='color:red'>"+"最高"+"</th>"+"<th style='color:red'>"+Math.round(data[5]['correct']*100)/100+"</th>"+"<th style='color:red'>"+Math.round(data[5]['speed']*100)/100+"</th>"+"<th style='color:red'>"+Math.round(data[5]['backDelete']*100)/100+"</th>"+"<th style='color:red'>"+Math.round(data[5]['averageKeyType']*100)/100+"</th>"+"<th style='color:red'>"+data[5]['finishDate']+
                   "</th>"+"<th style='color:red'>"+Math.round(data[5]['countAllKey']*100)/100+"</th>";
                   var tr = document.createElement("tr");  
                   tr.innerHTML= str;
                   detail.appendChild(tr);
                    if(data[4].length!=0){
//                       var ab=document.createElement("a");
//                        ab.href="index.php?r=teacher/export&&ii="+ii+"seq="+seq+"classID="+classID+"exerciseID"+exerciseID+"type="+type+"id="+id;
//                        ab.title="export";                    
//                        expor.appendChild(ab);
                           var str = "<a style='position:relative;top:5px;left:10px;color:black;' href='index.php?r=teacher/export&&choice="+choice+"&&seq="+seq+"&&classID="+classID+"&&exerciseID="+exerciseID+"&&type="+type+"&&id="+id+"'>"+"导出"+"</a>";
                           var tr = document.createElement("tr");    
                           tr.innerHTML= str;
                           expor.appendChild(tr);
                    }
                   
                   
//                   if(data[4].length!=0){
//                       var ab=document.createElement("a");
//                       var b=data[4].join("-");
//                       ab.title="export";                    
//                       expor.appendChild(ab);
//                    }
                     
                     var myChart = echarts.init(document.getElementById('main'));
                        var a=new Array();
                        if(myPer.length>=2){
                           a=['平均成绩','最高成绩','最低成绩'];
                         }else{
                           a=['平均成绩','学生成绩'];
                         }
                         var option = {
                             title: {
                                 text: ''
                             },
                             tooltip : {
                                 trigger: 'axis'
                             },
                             legend: {
                                  data:a
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
                                     name:"s",
                                     boundaryGap : false,
                                     data : times
                                 }
                             ],
                             yAxis : [
                                 {
                                     type : 'value',
                                     name:choose
                                 }
                             ],
                             series : function(){
                                 var s=[];
                                 var item={
                                     name:'平均成绩',
                                     type:'line',
                                     data:per
                                 };
                                 s.push(item);
                                 
                                 for(var d=0;d<myPer.length;d++){
                                     
                                     if(myPer.length>=2)
                                         if(d==0)
                                            var n='最高成绩';
                                         else 
                                            var n='最低成绩';
                                     else{
                                         var n='学生成绩';
                                         
                                     }
                                     var item={
                                        name:n,
                                        type:'line',
                                        data:myPer[d]
                                    };
                                    s.push(item);
                                 };
                                 return s;
                             }()
                               
                         };
                         myChart.setOption(option);
                 },     
            error: function(xhr, type, exception){
                window.wxc.xcConfirm('出错了...请重新刷新页面', window.wxc.xcConfirm.typeEnum.error);
                console.log(xhr, "Failed");
            }
         }); 
}

</script>