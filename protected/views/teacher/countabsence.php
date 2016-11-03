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
 <th>学号</th><th>姓名</th><th>课时</th><th>第几次点名</th>
 <tbody id="table2">

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
                <div align=center>
 <span id="spanFirst">第一页</span> <span id="spanPre">上一页</span> <span id="spanNext">下一页</span> <span id="spanLast">最后一页</span> 第<span id="spanPageNum"></span>页/共<span id="spanTotalPage"></span>页
    </div>
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
   
   
   var theTable = document.getElementById("table2");
     var totalPage = document.getElementById("spanTotalPage");
     var pageNum = document.getElementById("spanPageNum");


     var spanPre = document.getElementById("spanPre");
     var spanNext = document.getElementById("spanNext");
     var spanFirst = document.getElementById("spanFirst");
     var spanLast = document.getElementById("spanLast");


     var numberRowsInTable = theTable.rows.length;
     var pageSize = 6;
     var page = 1;


     //下一页
     function next() {


         hideTable();


         currentRow = pageSize * page;
         maxRow = currentRow + pageSize;
         if (maxRow > numberRowsInTable) maxRow = numberRowsInTable;
         for (var i = currentRow; i < maxRow; i++) {
             theTable.rows[i].style.display = '';
         }
         page++;


         if (maxRow == numberRowsInTable) { nextText(); lastText(); }
         showPage();
         preLink();
         firstLink();
     }


     //上一页
     function pre() {


         hideTable();


         page--;


         currentRow = pageSize * page;
         maxRow = currentRow - pageSize;
         if (currentRow > numberRowsInTable) currentRow = numberRowsInTable;
         for (var i = maxRow; i < currentRow; i++) {
             theTable.rows[i].style.display = '';
         }




         if (maxRow == 0) { preText(); firstText(); }
         showPage();
         nextLink();
         lastLink();
     }


     //第一页
     function first() {
         hideTable();
         page = 1;
         for (var i = 0; i < pageSize; i++) {
             theTable.rows[i].style.display = '';
         }
         showPage();


         preText();
         nextLink();
         lastLink();
     }


     //最后一页
     function last() {
         hideTable();
         page = pageCount();
         currentRow = pageSize * (page - 1);
         for (var i = currentRow; i < numberRowsInTable; i++) {
             theTable.rows[i].style.display = '';
         }
         showPage();


         preLink();
         nextText();
         firstLink();
     }


     function hideTable() {
         for (var i = 0; i < numberRowsInTable; i++) {
             theTable.rows[i].style.display = 'none';
         }
     }


     function showPage() {
         pageNum.innerHTML = page;
     }


     //总共页数
     function pageCount() {
         var count = 0;
         if (numberRowsInTable % pageSize != 0) count = 1;
         return parseInt(numberRowsInTable / pageSize) + count;
     }


     //显示链接
     function preLink() { spanPre.innerHTML = "<a href='javascript:pre();'>上一页</a>"; }
     function preText() { spanPre.innerHTML = "上一页"; }


     function nextLink() { spanNext.innerHTML = "<a href='javascript:next();'>下一页</a>"; }
     function nextText() { spanNext.innerHTML = "下一页"; }


     function firstLink() { spanFirst.innerHTML = "<a href='javascript:first();'>第一页</a>"; }
     function firstText() { spanFirst.innerHTML = "第一页"; }


     function lastLink() { spanLast.innerHTML = "<a href='javascript:last();'>最后一页</a>"; }
     function lastText() { spanLast.innerHTML = "最后一页"; }


     //隐藏表格
     function hide() {
         for (var i = pageSize; i < numberRowsInTable; i++) {
             theTable.rows[i].style.display = 'none';
         }


         totalPage.innerHTML = pageCount();
         pageNum.innerHTML = '1';


         nextLink();
         lastLink();
     }


     hide();
</script>