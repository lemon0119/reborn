<html lang="zh-cn"><!--<![endif]--> 
        <head>
            <meta charset="utf-8">
            <title>亚伟速录</title>
            <link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
            <link href="<?php echo CSS_URL; ?>site.css" rel="stylesheet">
            <script src="<?php echo JS_URL; ?>jquery.min.js"></script>
            <script src="<?php echo JS_URL; ?>bootstrap.min.js"></script>
            <script src="<?php echo JS_URL; ?>site.js"></script>
<!--            改变alter样式-- extensions/xcConfirm 工具包下-- --> 
                <link rel="stylesheet" type="text/css" href="<?php echo XC_Confirm; ?>css/xcConfirm.css"/>
		<script src="<?php echo XC_Confirm; ?>js/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
		<script src="<?php echo XC_Confirm; ?>js/xcConfirm.js" type="text/javascript" charset="utf-8"></script>
<!--            -->
             <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        </head>
        <body>
            
<div class="span9" style="width: 450px; height: 380px">
    <div class="control-group" id="div1">
            <h4 >缺勤信息</h4>
        <div>
            <select onchange="checktimes(this.value)">
                <?php 
                if(isset($one)){
                echo "<option value = '$one' >"."本课第".$one."次签到"."</option>";
                foreach ($times as $key){
                   $times = $key['times'];
                   if($times == $one){}else{
                echo "<option value = '$times' >"."本课第".$times."次签到"."</option>";}}
                }
                else{
                foreach ($times as $key){
                   $times = $key['times'];
                   echo "<option value = '$times' >"."本课第".$times."次签到"."</option>";
                }}
                ?> 
            </select>   
<table class="table table-bordered table-striped" id="table1">
       <th>学号</th><th>姓名</th><th>联系电话</th><th>第几次点名</th>
    <tbody id="table2">
            <?php  
foreach ($result as $v)
{
    if($v['userID'] == ''){
        echo"没有缺勤";        
    }else{
        echo '<tr>';
    echo '<td>';
    echo $v['userID'];
    echo '</td>';
    echo '<td>'; 
           $userID = $v['userID'];
          $sql = "SELECT * FROM student WHERE userID = '$userID'";
          $criteria   =   new CDbCriteria();
          $res     =   Yii::app()->db->createCommand($sql)->queryAll();
          foreach ($res as $key){
    echo $key['userName'].'</br>';}
    echo '</td>';
        echo '<td>';
          $userID = $v['userID'];
          $sql = "SELECT * FROM student WHERE userID = '$userID'";
          $criteria   =   new CDbCriteria();
          $res     =   Yii::app()->db->createCommand($sql)->queryAll();
          foreach ($res as $k){
        if($k['phone_number']== ''){echo '未录入该学生手机号';}
    echo $k['phone_number'].'</br>';}
    echo '</td>';
        echo '<td>第';
        echo $v['times'].'次';
        echo '</td>';
    echo '</tr>';
    }
}

?>
    </tbody>
    </table>
                    <div align=center>
 <span id="spanFirst">第一页</span> <span id="spanPre">上一页</span> <span id="spanNext">下一页</span> <span id="spanLast">最后一页</span> 第<span id="spanPageNum"></span>页/共<span id="spanTotalPage"></span>页
    </div>
            <button onclick="submit();"  class="btn btn-primary">确定</button>
    </form>
 </div>   
</div>
            </body>
    </html>
<script>
window.onload=isSign();
    function isSign()
    {
       $.ajax({
       type: "GET",
            url: "index.php?r=student/IsReleaseSign&&classID=<?php echo $classID; ?>&&lessonID=<?php echo $lessonID; ?>",
            success: function (data) {
             if (data['TeacherSign_ID'].length === 0){ 
                 document.getElementById("div1").innerHTML = "<h2 style = 'text-align:center;'>该课还没有进行签到...</h2>";            
             }   
           },            
                error: function(xhr, type, exception){
document.getElementById("div1").innerHTML = "<h1> 出错了。。。</h1>"; 
            }
       });    
    }   
    function submit(){
         window.close();
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
     function checktimes(c){
          window.location.href ='index.php?r=teacher/showAbsence&&classID=<?php echo $classID; ?>&&lessonID=<?php echo $lessonID; ?>&&times='+c;
     }
     
</script>