<?php
//$data=Array();
//$data=$arrayDetailData;
//$maxData=Array();
$name = Array();
$email =  Array();
$phone =  Array();
$studentID =  Array();
$signtime =  Array();
$absence = Array();
$lesson = Array();
$times = Array();
foreach ($result as $res){
    $id = $res['userID'];
    array_push($signtime,$res['time']);
    array_push($times,$res['times']);
       $lessonID = $res['lessonID'];
        $sql = "select * FROM student WHERE userID = '$id'";
        $criteria   =   new CDbCriteria();
        $n  =   Yii::app()->db->createCommand($sql)->queryAll();
        $sql = "select * FROM lesson WHERE lessonID = '$lessonID'";
        $criteria   =   new CDbCriteria();
        $m  =   Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($m as $ke){
            array_push($lesson,$ke['lessonName']);
        }
        foreach ($n as $k){
            array_push($studentID,$k['userID']);
            array_push($name,$k['userName']);
            array_push($email,$k['mail_address']);
            array_push($phone,$k['phone_number']);
        } 
} 
//    $id = $result['userID'];
//    $result = $result['userID'];
$absence = Array($studentID,$name,$email);
    $title=array('学号','姓名','手机号','邮箱','课时','第几次签到','缺勤时间',);
    $filename="学生"."$time"."缺勤详情";
    header("Content-type:application/octet-stream");
    header("Accept-Ranges:bytes");
   header("Content-type:application/vnd.ms-excel;charset=gbk");  
   header("Content-Disposition:attachment;filename=".$filename.".xls");
   header("Pragma: no-cache");
   header("Expires: 0");
    //导出xls 开始   
   if (!empty($title)){
       $title= implode("\t", $title);
        echo "$title\n";
    }
for ($i = 0;$i< count($name);$i++)
{   echo "$studentID[$i]\t";
    echo "$name[$i]\t";
    if($phone[$i] == '')
    {echo "未录入\t";}else{
    echo "$phone[$i]\t";}
    if($email[$i]==''){echo "未录入\t";}
    else{
    echo "$email[$i]\t";}
    echo "$lesson[$i]\t";
    echo "第"."$times[$i]";
    echo "次\t";
    echo "$signtime[$i]\n";}
?>
