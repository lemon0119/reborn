<?php
class Tool {
    public static $EXER_TYPE = [
        'listen',
        'look',
        'key',
        'choice',
        'filling',
        'question'
        ];
    public function alertInfo($info,$url){
       return  "<script type='text/javascript'>alert('$info');location.href='$url';</script>";
    }
    public static function printprobar($value){
        $sv = sprintf('%2.1f',$value * 100);
        $pro = '<div class="progress">';
        $bar = '<div class="bar" style="width:'.$sv.'%;">';
        $barend = '</div>';
        $proend = '</div>';
        $bw = "<font size=\"2\" color=\"#0d8fd1\">$sv%</font>";
        if($sv > 10)
            echo $pro.$bar.$sv.'%'.$barend.$proend;
        else
            echo $pro.$bar.$barend.$bw.$proend;
    }
    public static function jsLog($info) {
        return "<script>console.log('"+$info+"');</script>";
    }
    public static function arrayMerge($a1,$a2){
        foreach ($a2 as $key => $value) {
            $a1[$key] = $value;
        }
        return $a1;
    }

    public static function clength($str, $charset="utf-8")
    {
        /**
        * 可以统计中文字符串长度的函数
        * @param $str 要计算长度的字符串,一个中文算一个字符
        */
        $re['utf-8']="/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312']="/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']= "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']="/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        return count($match[0]);
    }
    
    public static function csubstr($str, $start=0, $length, $charset="utf-8")
    {
        /* *
        * 中文截取，支持gb2312,gbk,utf-8,big5
        * @param string $str 要截取的字串 
        * @param int $start 截取起始位置 
        * @param int $length 截取长度 
        * @param string $charset utf-8|gb2312|gbk|big5 编码 
        * @param $suffix 是否加尾缀 
        */
        if(function_exists("mb_substr"))
        {
            if(mb_strlen($str, $charset) <= $length) return $str;
            $slice = mb_substr($str, $start, $length, $charset);
         }else{
            $re['utf-8']="/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312']="/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk']= "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5']="/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            if(count($match[0]) <= $length) return $str;
            $slice = join("",array_slice($match[0], $start, $length));
         }
        return $slice;
    }
    
    public static function createID() {
        //7位系统时间，2位随机数，1位校验和
	$tm = time();
	$cs = 0;
	$i = 0;
	$tmp = $tm;
	for($i = 0; $i < 7; $i++){
		$cs += $tmp % 10;
		$tmp = (int) $tmp / 10;
	}
        //echo("tm"."$tm\n");
        //echo("cs:$cs\n");
        srand((double)microtime()*1000000);
        $rand_number= rand(0,99);
        $cs = $cs + $rand_number % 10 + (int)$rand_number / 10;
        //echo("rand:$rand_number\n");
        //echo("cs:$cs\n");
	$tm = $tm + "";
	$id = substr($tm, -7);
        //echo("id:$id\n");
        $str_rand = sprintf("%02d",$rand_number);
        $id =$id.$str_rand;
        //echo("id:$id\n");
        $cs = $cs % 10;
	$id = $id . ($cs + "");
        //echo("id:$id\n");
	return $id;
    }
    
    
    /**
     * class ExcelToDatabase
     * @author pengjingcheng
     *         Excle上传工具类。
     *         功能为上传后的临时excle文件另存，解析以及其内容合理性的逻辑检查。
     *
     *
     * 读取excel $filename 路径文件名 $encode 返回数据的编码 默认为utf8
     * $filetype为精简后的excle种类;
     * $file_types = explode(".",$_FILES['file']['type']);
     * $filetype = $file_types[count( $file_types )-1];
     * 精简后 $filetype Excle2007(.xlsx)版为sheet,Excle2003(.xls)版为ms-excel
     * return $excelData:获取的Excle解析结果数组
     */
    public static function excelreadToArray($filename, $filetype, $encode = 'utf-8') {
    	/* 导入phpExcel核心类 */
    	/* 静用Yii自身的自动加载方法，使PHPExcel自带的autoload生效 */
    	Yii::$enableIncludePath = false;
    	/* 引入PHPExcel.php文件 */
    	Yii::import ( 'application.extensions.PHPExcel.PHPExcel', 1 );
    
    	if ($filetype == "ms-excel") {
    		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' );
    	} elseif ($filetype == "sheet") {
    		$objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
    	}
    	$objReader->setReadDataOnly ( true );
    	$objPHPExcel = $objReader->load ( $filename );
    	$objWorksheet = $objPHPExcel->getActiveSheet ();
    
    	$highestRow = $objWorksheet->getHighestRow ();
    	$highestColumn = $objWorksheet->getHighestColumn ();
    	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );
    	$excelData = array ();
    	for($row = 1; $row <= $highestRow; $row ++) {
    		for($col = 0; $col < $highestColumnIndex; $col ++) {
    			$excelData [$row] [] = ( string ) $objWorksheet->getCellByColumnAndRow ( $col, $row )->getValue ();
    		}
    	}
    	return $excelData;
    }
    
    /**
     *         将数组内容拆分后导入数据库。假如出现数据限制（如学号不能为空）问题
     *         则返回问题原因的字符串,成功则返回成功。View接取内容后，可直接alert();
     *         $excelData:传入Excle解析结果数组
     *         return $result:导入结果
     */
    public static function excelreadToDatabase($excelData) {
    	foreach ( $excelData as $k => $v ) {
    		if ($k > 1) {
    			$data ['uid'] = $v [0];
    			$data ['userName'] = $v [1];
    			$data ['className'] = $v [2];
    			$data ['pass1'] = $v [3];
    			$data ['pass2'] = $v [4];
    
    			if ($data ['uid'] === "") {
    				$result = "学生" . $data ['uid'] .'学生学号不能为空';
    				return $result;
    			}
    			if (Tool::excelreadUserID( $data ['uid'] )) {
    				$result = "学生" . $data ['uid'] . '学生学号已存在！';
    				return $result;
    			}
    			if ($data ['userName'] === "") {
    				$result = "学生" . $data ['uid'] . '学生姓名不能为空';
    				return $result;
    			}
    			if ($data ['className'] === "") {
    				$result = "学生" . $data ['uid'] . '学生班级不能为空';
    				return $result;
    			}
    			if (!Tool::excelreadClass($data ['className'])) {
    				$result = "学生" . $data ['uid'] . '学生班级不存在！';
    				return $result;
    			}
    			if ($data ['pass1'] === "") {
    				$result = "学生" . $data ['uid'] . '密码不能为空';
    				return $result;
    			}
    			if ($data ['pass2'] === "") {
    				$result = "学生" . $data ['uid'] . '确认密码不能为空';
    				return $result;
    			}
    			if ($data ['pass1'] !== $data ['pass2']) {
    				$result = "学生" . $data ['uid'].'密码两次输入不相同！';
    				return $result;
    			} else {
    				// 导入数据库
    				$className = $data ['className'];
    				$subClass = TbClass::model()->find("className = '$className'");
    				$classID = $subClass['classID'];
    				Student::model()->insertStu($data ['uid'], $data ['userName'], $data ['pass1'], $classID);
    			}
    		}
    
    	}
    	return $result = '导入成功！';
    }
    /**
     * 老师的excel导入条件判断，逻辑同学生
     */
    public static function excelReadTeaToDatabase($excelData) {
    	foreach ( $excelData as $k => $v ) {
    		if ($k > 1) {
    			$data ['uid'] = $v [0];
    			$data ['userName'] = $v [1];
    			$data ['pass1'] = $v [2];
    			$data ['pass2'] = $v [3];
    
    			if ($data ['uid'] === "") {
    				$result = "老师" . $data ['uid'] . '老师工号不能为空';
    				return $result;
    			}
    			if (Tool::excelReadTeaUserID( $data ['uid'] )) {
    				$result = "老师" . $data ['uid'] . '老师工号已存在！';
    				return $result;
    			}
    			if ($data ['userName'] === "") {
    				$result = "老师" . $data ['uid'] . '老师姓名不能为空';
    				return $result;
    			}
    			if ($data ['pass1'] === "") {
    				$result = "老师" . $data ['uid'] . '密码不能为空';
    				return $result;
    			}
    			if ($data ['pass2'] === "") {
    				$result = "老师" . $data ['uid'] . '确认密码不能为空';
    				return $result;
    			}
    			if ($data ['pass1'] !== $data ['pass2']) {
    				$result = "老师" . $data ['uid'].'密码两次输入不相同！';
    				return $result;
    			} else {
    				// 导入数据库
    				error_log("进入导入数据库");
    				Teacher::model()->insertTea($data ['uid'], $data ['userName'], $data ['pass1']);
    			}
    		}
    
    	}
    	return $result = '导入成功！';
    }
    /**
     * 验证用户班级是否存在
     * return true 用户班级存在; false 用户班级不存在
     */
    public static function excelreadClass($className) {
    	$classAll = TbClass::model ()->findAll ("");
    	foreach ( $classAll as $k => $v ) {
    		$name = $v ['className'];
    		if ($name == $className) {
    			return true;
    		}
    	}
    	return false;
    }
    /**
     * 验证学生ID（学号）是否存在
     * return true 学生ID（学号）重复; false 学生ID（学号）不重复
     */
    public static function excelreadUserID($userId) {
    	$userAll = Student::model ()->findAll ();
    	foreach ( $userAll as $k => $v ) {
    		$Id = $v ['userID'];
    		if ($Id == $userId) {
    			return true;
    		}
    	}
    	return false;
    }
    
    /**
     * 验证老师ID（工号）是否存在
     * return true 老师ID（工号）重复; false 老师ID（工号）不重复
     */
    public static function excelReadTeaUserID($userId) {
    	$userAll = Teacher::model ()->findAll ();
    	foreach ( $userAll as $k => $v ) {
    		$Id = $v ['userID'];
    		if ($Id == $userId) {
    			return true;
    		}
    	}
    	return false;
    }
    
}
