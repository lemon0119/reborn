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
<form method="post" name="form">  
开始:<input type="text" name="time" onfocus="MyCalendar.SetDate(this)" value="<?php if(isset($time1)) {echo $time1;}?>" style="width: 90px;">
截至:<input type="text" name="endtime" onfocus="MyCalendar.SetDate(this)" value="<?php if(isset($time2)) {echo $time2;}?>"style="width: 90px;">
<input type="submit" name="" onclick="showma()" value="查询" class="btn btn-primary"style="width:60px"/>
<input type="submit" onclick="expor()" value="导出" class="btn btn-primary" style="width:60px"/> 
</form>
<div>
                 <?php 
if(isset($time1)){
    echo "<h3>查询结果</h3>";
}
else{
    echo "<h3>$time"."月缺勤</h3>";
}
?>
        <table class="table table-bordered table-striped" id="table1">
            <thead>
            <th>学号</th><th>姓名</th><th>课时</th><th>第几次点名</th></thead>
 <tbody id="group_one">

            <?php                    
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
		<tr>
			<td><a href="#" onclick="page.firstPage();">首页</a></td>
			<td><a href="#" onclick="page.prePage();">上一页</a></td>
			<td>第<span id="pageindex">1</span>页</td>
			<td><a href="#" onclick="page.nextPage();">下一页</a></td>
			<td><a href="#" onclick="page.lastPage();">尾页</a></td>
                        <td>共<font id='t'>2</font>页&nbsp;</td>
                        <td>第<select id="pageselect" style="width:50px" onchange="page.changePage();"></select>页</td>
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
      document.form.action='./index.php?r=teacher/CountAbsence&&classID=<?php echo $classID;?>';
    }
   function expor(){
    document.form.action='./index.php?r=teacher/Exportabsence&&classID=<?php echo $classID;?>'; 
   }
       	window.onload = function() {
		page = new Page(5, 'table1', 'group_one');
	}
</script>