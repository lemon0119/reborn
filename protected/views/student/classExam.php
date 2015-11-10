                                                                                                                                                                                                                <?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script src="<?php echo JS_URL;?>exerJS/timeCounter.js"></script>
<div class="span11" style="min-height: 500px">
    <center><h3 style="color: white;">课 堂 考 试</h3></center>
    <table class="table table-bordered table-striped"  style="background: #DDD">
            <thead>
                    <tr>
                            <th>标　　题</th>
                            <th>开始时间</th>
                            <th>开考倒计时</th>
                            <th>是否完成</th>
                            <th>操　　作</th>
                            <th>分    数</th>
                    </tr>
            </thead>
            <tbody>
                <?php 
                $n=0;
                foreach ($classexams as $exam){?>
                    <tr>
                            <td>
                                    <?php echo $exam['examName'];?>
                            </td>
                            <td>
                                    <?php echo $exam['begintime'];?>
                            </td>
                            <td>
                                <font id = "sideTime-<?php echo $n;?>">计时结束</font>
                                <font id = "sideTime2-<?php echo $n;?>" style="display: none;">计时结束</font>
                            </td>
                             <td>
                                    <?php if ($ratio_accomplish[$n] ==1){
                                    	echo '已完成';
                                    }else {
                                    	echo '未完成';
                                    }?>
                           </td>
                            <td>
                               <?php if(time()>=strtotime($exam['begintime'])&&(time() <strtotime($exam['begintime'])+60*$exam['duration'])&& $ratio_accomplish[$n] ==0){?>
                                <a href="./index.php?r=student/clsexamOne&&suiteID=<?php echo $exam['examID'];?>&&workID=<?php echo $exam['workID'];?>" class="view-link"><?php echo '进　　入';?></a>
                                <?php } else if(time()>strtotime($exam['begintime'])+60*$exam['duration']||$ratio_accomplish[$n] ==1){ ?>
                                                <?php echo '<font color="#ff0000">已经截止</font>'?>
                                                |  &nbsp;&nbsp;&nbsp    
                                                 <a href="./index.php?r=student/viewAns&&suiteID=<?php echo $exam['examID'];?>&&workID=<?php echo $exam['workID'];?>" class="view-link"><?php echo '查看答案';?></a>
                                <?php }else { echo '<font color="#ff0000">时间未到</font>';}?>
                            </td>
                            <td>
                                 <?php if($score[$n]==null){
                                         echo "未批阅";
                                 } else {
                                 echo $score[$n];
                                 }?> 
                            </td>
                    </tr>
                <?php $n++;}?>
            </tbody>
    </table>
</div>
<script language="JavaScript">
    $(document).ready(function(){
            //这个就是定时器
        setTimeout(function(){
            window.location.reload(); 
        },5000); 
        var curtime = <?php echo time();?>;   
        function endTimer(endID){}
        <?php $n=0;$classexam=Array();foreach ($classexams as $exam){array_push($classexam, $exam);?>
             tCounter(curtime,<?php echo strtotime($exam['begintime']);?>,"sideTime-<?php echo $n;?>", endTimer);
        <?php $n++;}?>
            
        //提前10min 提醒
        function arriveTimer(endID){
            $.ajax({
                type: "POST",
                url: "index.php?r=api/putNotice2&&class=<?php echo $classID;?>",
                data: {title:  "考试提醒！" , content:  "考试时间还有10分钟就到了"},
                success: function(){  
                    
                },
                error: function(xhr, type, exception){
                    console.log(xhr.responseText, "Failed");
                }
            });
        }
        <?php $m=0;foreach ($classexam as $exam){?>
            var ti=<?php echo strtotime($exam['begintime']);?>;
             tCounter2(curtime,<?php echo strtotime($exam['begintime'])-10*60?>,"sideTime2-<?php echo $m;?>", arriveTimer);
        <?php $m++;}?>
    });
    
       
</script>
