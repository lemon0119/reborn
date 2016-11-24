<html lang="zh-cn"><!--<![endif]--> 
        <head>
            <meta charset="utf-8">
            <title>亚伟速录</title>
            <link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
            <link href="<?php echo CSS_URL; ?>site.css" rel="stylesheet">
            <script src="<?php echo JS_URL; ?>jquery.min.js"></script>
            <script src="<?php echo JS_URL; ?>bootstrap.min.js"></script>
            <script src="<?php echo JS_URL; ?>site.js"></script>
            <script src="<?php echo JS_URL; ?>mydate.js"></script>
            <script src="<?php echo JS_URL; ?>fenye.js"></script>
<!--            改变alter样式-- extensions/xcConfirm 工具包下-- --> 
                <link rel="stylesheet" type="text/css" href="<?php echo XC_Confirm; ?>css/xcConfirm.css"/>
		<script src="<?php echo XC_Confirm; ?>js/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
		<script src="<?php echo XC_Confirm; ?>js/xcConfirm.js" type="text/javascript" charset="utf-8"></script>
<!--            -->
             <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        </head>
        <body>
            
<div class="span9" style="width: 450px; height: 380px">

<!--</select>   
           <button type="submit" class="btn btn-primary">导出详情</button>           
      </form>-->
<?php  if(isset($m)){ ?>
<form method="post" name="form" id="myform">  
 开始:&nbsp;<input  type="text" name="time" id="startTime" onfocus="MyCalendar.SetDate(this)" value="<?php if(isset($time1)) {echo $time1;}?>" style="width: 90px;height: 25px; position: relative;top: 2px">&nbsp;
 截至:&nbsp;<input type="text" name="endtime" id="overTime" onfocus="MyCalendar.SetDate(this)" value="<?php if(isset($time2)) {echo $time2;}?>"style="width: 90px;height: 25px; position: relative;top: 2px">&nbsp;
<input type="submit" name="" onclick="showma()" value="查询" class="btn btn-primary"style="width:70px; position: relative;top: -6px"/>
<input type="submit" onclick="expor()" value="导出" class="btn btn-primary" style="width:70px; position: relative;top: -6px"/> 
</form>

<?php }?>
<div>
                 <?php 
if(isset($time1)){
    echo "<h3>查询结果</h3>";
}
else if(isset ($time)){
    echo "<h3>$time"."月缺勤</h3>";
}  else {
    echo "<h3>该班没有任何签到</h3>";
}
?>
    <?php  if(isset($m)){ ?>
        <table class="table table-bordered table-striped" id="table1">
            <thead>
            <th>学号</th><th>姓名</th><th>课时</th><th>第几次点名</th></thead>
 <tbody id="group_one">
    <?php } ?>
            <?php  
            if(isset($m)){
foreach ($m as $v)
{
    if($v['userID']  == ''){
        echo"没有任何签到";        
    }else{
        echo "<tr>";
        echo "<td>";  
        echo $userID = $v['userID'];
        echo "</td>";
        $sql = "select * FROM student WHERE userID = '$userID'";
        $criteria   =   new CDbCriteria();
        $n  =   Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($n as $k){
            echo '<td>';
            echo $k['userName'];
            echo '</td>';
        }
                echo "<td>";
        $lessonID = $v['lessonID'];
        $sql = "select * FROM lesson WHERE lessonID = '$lessonID'";
        $criteria   =   new CDbCriteria();
        $m  =   Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($m as $ke){
            $Lesson = $ke['lessonName']; 
            echo "$Lesson";
        }
       
        echo "</td>";
        echo "<td>"; 
 echo $v['times'];
        echo "</td>";

        echo "</tr>";
}
            }
      
?>
        </tbody>
        </table>
            </div>
<!--<table class="table table-bordered table-striped">
    <th>星期一</th><th>星期二</th><th>星期三</th><th>星期四</th><th>星期五</th><th>星期六</th><th>星期日</th>
    </table>-->
	<span id="s"></span>
	<table>
		<td><a href="#" onclick="page.firstPage();">首页</a>&nbsp;&nbsp;</td>
			<td><a href="#" onclick="page.prePage();">上一页</a>&nbsp;&nbsp;</td>&nbsp;&nbsp;
			<td>第<span id="pageindex">1</span>页&nbsp;&nbsp;</td>&nbsp;&nbsp;
			<td><a href="#" onclick="page.nextPage();">下一页</a>&nbsp;&nbsp;</td>&nbsp;&nbsp;
			<td><a href="#" onclick="page.lastPage();">尾页</a>&nbsp;&nbsp;</td>&nbsp;&nbsp;
                        <td>共<font id='t'>2</font>页&nbsp;&nbsp;</td>&nbsp;&nbsp;
                        <td style="position: relative;top:1px">第&nbsp;<select id="pageselect" style="width:40px;height: 26px;position: relative;top:4px" onchange="page.changePage();"></select>&nbsp;页</td>
		</tr>

	</table>
            <button onclick="submit();"  class="btn btn-primary">确定</button>
            </body>
    </html>
<script>
window.onload=showLately();
    function showLately()
    {
 //  window.location.href ='index.php?r=teacher/showLately&&classID=<?php //echo $classID; ?>';
    }   
    function submit(){
         window.close();
    }
    
      function showma(){
        var startTime = document.getElementById('startTime').value;
       var overTime = document.getElementById('overTime').value;
           if(IsDate( startTime) && IsDate( overTime)){
                document.form.action='./index.php?r=teacher/CountAbsence&&classID=<?php echo $classID;?>';
           }else{
              // window.wxc.xcConfirm('这不是正确的日期格式，请重新选择', window.wxc.xcConfirm.typeEnum.error); 
              alert('这不是正确的日期格式，请重新选择');
           }
        
    }
    function IsDate(num){
      var regexp = /^([1][7-9][0-9][0-9]|[2][0][0-9][0-9])(\-)([0][1-9]|[1][0-2])(\-)([0-2][1-9]|[3][0-1])$/g;
　    /// 日期范围：1700-01-01 ----2099-01-01 　
　　　　 return regexp.test(num);
}

   function expor(){
    document.form.action='./index.php?r=teacher/Exportabsence&&classID=<?php echo $classID;?>'; 
   }
       	window.onload = function() {
		page = new Page(5, 'table1', 'group_one');
	}
</script>
            <?php }?>