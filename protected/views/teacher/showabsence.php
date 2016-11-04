<html lang="zh-cn"><!--<![endif]--> 
        <head>
            <meta charset="utf-8">
            <title>亚伟速录</title>
            <link href="<?php echo CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
            <link href="<?php echo CSS_URL; ?>site.css" rel="stylesheet">
            <script src="<?php echo JS_URL; ?>jquery.min.js"></script>
            <script src="<?php echo JS_URL; ?>bootstrap.min.js"></script>
            <script src="<?php echo JS_URL; ?>site.js"></script>
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
    <thead>
       <th>学号</th><th>姓名</th><th>联系电话</th><th>第几次点名</th>
       </thead>
    <tbody id="group_one">
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
    
     function checktimes(c){
          window.location.href ='index.php?r=teacher/showAbsence&&classID=<?php echo $classID; ?>&&lessonID=<?php echo $lessonID; ?>&&times='+c;
     }
     	window.onload = function() {
		page = new Page(5, 'table1', 'group_one');
	};
     
</script>