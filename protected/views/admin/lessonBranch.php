 <div class="hero-unit">
     <h3><?php echo $lessonName;?></h3>
        <button type="button" onclick="class()"><?php echo $class['suiteName'];?></button>
        
        <button type="button" onclick="()"><?php echo $exer['suiteName'];?></button>
        
        <button type="button" onclick="()"><?php echo '视频文件管理';?></button>
       
        <button type="button" onclick="()"><?php echo 'PPT文件管理';?></button>
        <script>
        function class()
        {
             $("#cont").load("./index.php?r=admin/goverLesson&&suiteID=<?php echo $class['suiteID'];?>&&suiteName=<?php echo $class['suiteName'];?>&&suiteType=<?php echo $class['suiteType'];?>");
        }
        function exer()
        {
             $("#cont").load("./index.php?r=admin/goverLesson&&suiteID=<?php echo $exer['suiteID'];?>&&suiteName=<?php echo $exer['suiteName'];?>&&suiteType=<?php echo $exer['suiteType'];?>");
        }
        </script>
 </div>


