<link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
 <script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js"></script>
<script src="<?php echo XC_Confirm; ?>js/xcConfirm.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo JS_URL; ?>jquery.min.js"></script>
<body style="background-image: none;background-color: #fff">
    <div style="overflow: hidden" id="title"><h3 id="hh" class="fl"><?php echo $ClassExercise['title'];?></h3><button style="margin: 10px"  class="fr btn" id="back">返回上级</button><button onclick="next()" style="margin: 10px" id="next" class="fr btn btn-primary" <?php if(count($AllIsOpen)<2){echo 'disabled="disabled"';}?>>下一题</button><button class="fr btn btn-primary" onclick="last()" id="last" style="margin: 10px;" disabled="disabled">上一题</button></div>
    <div style="overflow: auto;width: 98%;height: 420px;position: relative;">
        <table id="table_of_analysis"  class="table table-bordered table-striped">
         <thead>
            <tr>
                <th >排名</th>
                <th >学号</th>
                <th >学生</th>
                <th >平均速度</th>
                <th >最高速度</th>
                <th >正确率</th>
                <th >进行时间(秒)</th>
                <th >总击键</th>
            </tr>
        </thead>
        <tbody> 
        </tbody>
    </table>
    </div>
</body>
<script>
    var Htitle = "";
    var count = 1;
    var  exericseID= <?php echo $_GET['exerciseID'];?>;
    var  allExercise = new Array();
    <?php foreach ($AllIsOpen as $k=>$v){ ?>
            allExercise[0] = exericseID;               
            allExercise[<?php echo $k;?>] = <?php echo $v['exerciseID'];?>;
    <?php } ?>
    
    $(document).ready(function(){
       checkAnalysis(exericseID);
       setInterval(function () { 
            checkAnalysis(exericseID);
        }, 2000);
    });
    
    $("#back").click(function(){
        window.parent.backToTableClassExercise4virtual();
    });
    
    function checkAnalysis(exericseID){
        var classID = <?php echo $_GET['classID'];?>;
        $.ajax({
            type:"POST",
               url:"index.php?r=teacher/getVirtualClassAnalysis",
               data:{exerciseID:exericseID,classID:classID},
               success:function(data){
                   for(i=0;i<data.length;i++){
                       $('#option'+i+'').remove(); 
                       var newRow='<tr id="option'+i+'"><td >'+(i+1)+'</td><td >'+data[i]['studentID']+'</td><td >'+data[i]['studentName']+'</td><td>'+data[i]['speed']+'</td><td>'+data[i]['maxSpeed']+'</td><td>'+data[i]['correct']+'%</td><td>'+data[i]['time']+'</td><td>'+data[i]['allKey']+'</td></tr>';  
                       $('#table_of_analysis').append(newRow); 
                   }
                  Htitle = data[0]['title'];   
               },
               error:function(xhr, type, exception){
                   console.log('GetAverageSpeed error', type);
                   console.log(xhr, "Failed");
                   console.log(exception, "exception");
               }
        });
        
        
       
    }
    
    function next(){
        var h = document.getElementById("hh");
        var father = document.getElementById("title");
        father.removeChild(h);
        var back = document.getElementById("back");
        var title = Htitle;
        var hh = document.createElement("h3");
        hh.setAttribute("id","hh");
        hh.setAttribute("class","fl");
        hh.innerHTML = title;
        father.insertBefore(hh,back);
        if(count<allExercise.length){
            count++;
        }
        exericseID = allExercise[count];
        checkAnalysis(exericseID);
        if(count == allExercise.length){
            $("#next").attr("disabled","disabled"); 
        }
        if(exericseID!=<?php echo $_GET['exerciseID'];?>){
           $("#last").removeAttr("disabled");
        }else{
           $("#last").attr("disabled","disabled"); 
        }
    }   
     
        
</script>