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
                <th >进行时间</th>
                <th >总字数</th>
            </tr>
        </thead>
        <tbody> 
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</body>
<script>
    $(document).ready(function(){
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
                       console.log(data[i]);
                   }
//                    var newRow='<tr id="option'+rowCount+'"><td class="oz-form-topLabel">选项'+rowCount+'：</td><td class="oz-property" ><input type="text"  style="width:300px"></td><td><a href="#" onclick=delRow('+rowCount+')>删除</a></td></tr>';  
//                   $('#table_of_analysis').append(newRow);  
               },
               error:function(xhr, type, exception){
                   console.log('GetAverageSpeed error', type);
                   console.log(xhr, "Failed");
                   console.log(exception, "exception");
                   
               }
        });
        
        
       
    }
     
     
        
</script>