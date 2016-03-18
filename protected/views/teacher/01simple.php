<?php
$data=Array();
$data=$arrayDetailData;

$maxData=Array();
    $title=array('成绩','正确率','平均速度','回改字数','平均击键','完成时间','总击键数');
    $filename='report';
    header("Content-type:application/octet-stream");
    header("Accept-Ranges:bytes");
    header("Content-type:application/vnd.ms-excel;charset=gbk");  
    header("Content-Disposition:attachment;filename=".$filename.".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    //导出xls 开始
    
    if (!empty($title)){
        foreach ($title as $k => $v) {
            //$title[$k]=iconv("UTF-8", "GB2312",$v);
            $title[$k]=$v;
        }
        $title= implode("\t", $title);
        echo "$title\n";
    }
    if (!empty($data)){
        foreach($data as $key=>$val){
            foreach ($val as $ck => $cv) {
                error_log($cv);
                //$data[$key][$ck]=iconv("UTF-8", "GB2312", $cv);
                $data[$key][$ck]=$cv;
            }
            $data[$key]=implode("\t", $data[$key]);
        }
        echo implode("\n",$data);
    }


?>
