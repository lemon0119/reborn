<link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
 <script src="<?php echo JS_URL; ?>jquery-2.1.3.min.js"></script>
<script src="<?php echo XC_Confirm; ?>js/xcConfirm.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo JS_URL; ?>jquery.min.js"></script>
<body style="background-image: none;background-color: #fff">
    <table id="table_of_analysis" style="width: 100%;position: relative;" class="table table-bordered table-striped">
         <thead>
            <tr>
                <th >学号</th>
                <th >学生</th>
                <th >平均速度</th>
                <th >最高速度</th>
                <th >正确率</th>
                <th >进行时间(秒)</th>
                <th >总字数</th>
            </tr>
        </thead>
        <tbody> 
        </tbody>
    </table>
</body>
<script>
    $(document).ready(function(){
        checkAnalysis();
       setInterval(function () { 
            checkAnalysis();
        }, 2000);
       
    });
    
    function checkAnalysis(){
        var exericseID = <?php echo $_GET['exerciseID'];?>;
        var classID = <?php echo $_GET['classID'];?>;
        $.ajax({
            type:"POST",
               url:"index.php?r=teacher/getVirtualClassAnalysis",
               data:{exerciseID:exericseID,classID:classID},
               success:function(data){
                   for(i=0;i<data.length;i++){
                       $('#option'+i+'').remove(); 
                       var newRow='<tr id="option'+i+'"><td >'+data[i]['studentID']+'</td><td >'+data[i]['studentName']+'</td><td>'+data[i]['speed']+'</td><td>'+data[i]['maxSpeed']+'</td><td>'+data[i]['correct']+'%</td><td>'+data[i]['time']+'</td><td>'+data[i]['allFont']+'</td></tr>';  
                       $('#table_of_analysis').append(newRow); 
                   }
               },
               error:function(xhr, type, exception){
                   console.log('GetAverageSpeed error', type);
                   console.log(xhr, "Failed");
                   console.log(exception, "exception");
                   
               }
        });
        
        
       
    }
     
     
        
</script>