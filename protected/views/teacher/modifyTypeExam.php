<script src="<?php echo JS_URL; ?>laydate-master/laydate.js"></script>
<link href="<?php echo CSS_URL; ?>laydate-master/need/laydate.css" rel="stylesheet">
<script type="text/javascript">
//function test()
//{
//         var startTime = document.getElementById("startTime");
//	 var endTime = document.getElementById("endTime");
//         SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss"); 
//         Date d1 = df.parse(startTime);  //后的时间  
//            Date d2 = df.parse(endTime); //前的时间  
//            var diff = d1.getTime() - d2.getTime();   //两时间差，精确到毫秒   
//  
//            Long day = diff / (1000 * 60 * 60 * 24);          //以天数为单位取整  
//            Long hour=(diff/(60*60*1000)-day*24);             //以小时为单位取整   
//            Long min=((diff/(60*1000))-day*24*60-hour*60);    //以分钟为单位取整   
//            var secone=(diff/1000-day*24*60*60-hour*60*60-min*60);  
//              
//              
//              
//            alert("---diff的值---->" +diff);  
//            alert("---days的值---->" +day);  
//            alert("---hour的值---->" +hour);  
//            alert("---min的值---->"  +min);  
//            alert("---secone的值---->"  +secone);  
//              
//            alert("---两时间差---> " +day+"天"+hour+"小时"+min+"分"+secone+"秒");  
//	 //对电子邮件的验证
//	 var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
//	 if(!myreg.test(temp.value))
//	 {
//             window.wxc.xcConfirm('请输入有效的email！', window.wxc.xcConfirm.typeEnum.info);
//	     temp.value="";
//	     myreg.focus();
//	     return false;
//	 } 
//}
</script>

<div class="span3">
    <div class="well" style="padding: 8px 0;">
        <ul class="nav nav-list">
            <li <?php if($type == "choice") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyExam&&examID=<?php echo $exam['examID']?>&&type=choice"><i class="icon-font"></i> 选择</a></li>
                        <li <?php if($type == "filling") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyExam&&examID=<?php echo $exam['examID']?>&&type=filling"><i class="icon-text-width"></i> 填空</a></li>
                        <li <?php if($type == "question") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyExam&&examID=<?php echo $exam['examID']?>&&type=question"><i class="icon-align-left"></i> 简答</a></li>
                        <li <?php if($type == "key") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyExam&&examID=<?php echo $exam['examID']?>&&type=key"><i class="icon-th"></i> 键位练习</a></li>
                        <li <?php if($type == "look") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyExam&&examID=<?php echo $exam['examID']?>&&type=look"><i class="icon-eye-open"></i> 看打练习</a></li>
                        <li <?php if($type == "listen") echo "class='active'" ?>><a href="./index.php?r=teacher/ModifyExam&&examID=<?php echo $exam['examID']?>&&type=listen"><i class="icon-headphones"></i> 听打练习</a></li>                           
        </ul>
        
    </div> 
    <!--
    <form id="updateForm" action="./index.php?r=teacher/UpdateTime&&examID=<?php echo $examID; ?>&&type=<?php echo $type; ?>" method="post" >  -->
        <div class="well" style="padding: 8px 0;">
            <ul class="nav nav-list" style="margin-left: -80px;">
                <center>
                    <table>
                        <tr><td>试卷名称:<?php  if(Tool::clength($exam['examName'])<=7)
                                        $a= $exam['examName'];
                                    else
                                        $a= Tool::csubstr($exam['examName'], 0, 7)."...";?>
                                <a href="#" title="<?php echo $exam['examName']?>" style="text-decoration:none;"><?php echo $a?></a>
                            </td></tr>
                        <!--
                        <tr><td>开始时间: 
                                <div class="inline layinput"><input style="width:180px;color:white;" class="search-query span2" placeholder="选择开始时间" name="startTime" id="startTime" value="<?php echo $exam['begintime'] ?> " onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" ></div>       
                            </td></tr>
                        <tr><td>结束时间:                                <input style="width:180px;color:white;" class="search-query span2" placeholder="选择结束时间" name="endTime" id="endTime" value="<?php echo $exam['endtime'] ?>" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" onblur="test()">
                            </td></tr>
                        <tr><td>时长:<?php echo $exam['duration'] ?>秒</td></tr>   -->
                        <tr><td><div id="totalScore">总分:<?php echo $totalScore?></div></td></tr>
                    </table>                
                </center>                   
            </ul>
        </div>   
   <!--     <button type="submit" class="btn btn-primary">更新时间</button>

    </form>  -->
     
    <a href="./index.php?r=teacher/AssignExam&&classID=<?php echo Yii::app()->session['currentClass'];?>"  class="btn btn-primary">返回</a>
</div>

<div class="span9">
    <iframe src="./index.php?r=teacher/ToOwnTypeExam&&type=<?php echo $type;?>&&examID=<?php echo $exam['examID'];?>" id="iframe1" frameborder="0"  scrolling="no" width="770px" height="400px" name="iframe1"></iframe>
    
    <iframe src="./index.php?r=teacher/ToAllTypeExam&&type=<?php echo $type;?>&&examID=<?php echo $exam['examID'];?>" id="iframe2" frameborder="0" scrolling="no" width="770px" height="400px" name="iframe2"></iframe>

<div >



<script>
    var currentPage1;  
    function refresh()
    {     
    $('#iframe1').attr("src","./index.php?r=teacher/ToOwnTypeExam&&type=<?php echo $type;?>&&examID=<?php echo $exam['examID'];?>&&page="+currentPage1);   
   }
   
   function setCurrentPage1(page)
   {  
       currentPage1 = page;    
   }
   
               $('#basic_example_1').click(function () {
                $('#basic_example_1').datetimepicker();
            });
            $("#updateForm").submit(function(){
                var startTime= document.getElementById("startTime").value;
                var endTime= document.getElementById("endTime").value;
                
                var newstr = startTime.replace(/-/g,'/'); 
                var date =  new Date(newstr); 
                startTime = date.getTime().toString().substr(0, 10);
                
                var newstr = endTime.replace(/-/g,'/'); 
                var date =  new Date(newstr); 
                endTime = date.getTime().toString().substr(0, 10);
                
               if(endTime<startTime){
                   window.wxc.xcConfirm('开始时间>结束时间', window.wxc.xcConfirm.typeEnum.info);
                   document.getElementById("startTime").value="<?php echo $exam['begintime'] ?>";
                   document.getElementById("endTime").value="<?php echo $exam['endtime'] ?>";
                   return false;
               }

            });
</script>



