<?php require 'stuSideBar.php';?>
<div class="span9">
	<h2>添 加 学 生</h2>
	<form action="./index.php?r=admin/exlAddStu" class="form-horizontal"
		method="post" id="form-exlAddStu" enctype="multipart/form-data">
		<fieldset>
			<legend>批量添加</legend>
			<div class="control-group">
				<label class="control-label" for="input01">请选择Excle文件<br>必须为.xlsx结尾
				</label>
				<div class="controls">
					<input type="hidden" name="flag" id="flag" value="1" /> <input
						type="hidden" name="MAX_FILE_SIZE" value="3000000" /> <input
						type="file" name="file" id="file" />
				</div>
			</div>
			<div class="form-actions">
				<input type="submit" class="btn btn-primary" value="添加" />&nbsp;&nbsp;<a
					href="./index.php?r=admin/addStu" class="btn">逐个添加</a>&nbsp;&nbsp;<a
					href="./index.php?r=admin/stuLst" class="btn">取消</a>
			</div>
		</fieldset>
	</form>
</div>



<script>
$(document).ready(function(){
    var flag = <?php echo "'$flag'";?>;
    if(flag === '1'){
    	<?php
					if (! empty ( $_FILES ['file'] ['name'] )) {
						$tmp_file = $_FILES ['file'] ['tmp_name'];
						$file_types = explode ( ".", $_FILES ['file'] ['type'] );
						$file_type = $file_types [count ( $file_types ) - 1];
						
						// 判别是不是excel文件
						if (strtolower ( $file_type ) != "sheet" && strtolower ( $file_type ) != "ms-excel") {
							echo " alert('不是Exale文件');";
							error_log ( $file_type, 0 );
						} else {
							// 解析文件并存入数据库逻辑
							/* 设置上传路径 */
							$savePath = dirname ( Yii::app ()->BasePath ) . '/public/upload/excle/';
							/* 以时间来命名上传的文件 */
							$str = date ( 'Ymdhis' );
							$file_name = $str . ".xls";
							if (! copy ( $tmp_file, $savePath . $file_name )) {
								echo "alert('上传失败');";
							} else {
								$res = ExcelToDatabase::readToArray ( $savePath . $file_name, $file_type );
								ExcelToDatabase::readToDatabase ( $res );
								/* 对生成的数组进行数据库的写入 */
							}
						}
					} else {
						echo "alert('没有文件');";
					}
					?>
        }
});


<?php
/**
 *
 * @author pengjingcheng
 *         Excle上传工具类。
 *         功能为上传后的临时excle文件另存，解析以及其内容合理性的逻辑检查。
 */
class ExcelToDatabase {
	
	/**
	 * 读取excel $filename 路径文件名 $encode 返回数据的编码 默认为utf8
	 * $filetype为精简后的excle种类;
	 * $file_types = explode(".",$_FILES['file']['type']);
	 * $filetype = $file_types[count( $file_types )-1];
	 * 精简后 $filetype Excle2007(.xlsx)版为sheet,Excle2003(.xls)版为ms-excel
	 * return $excelData:获取的Excle解析结果数组
	 */
	public static function readToArray($filename, $filetype, $encode = 'utf-8') {
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
	 *
	 * @author pengjingcheng
	 *         将数组内容拆分后导入数据库。假如出现数据限制（如学号不能为空）问题
	 *         则返回问题原因的字符串,成功则返回成功。View接取内容后，可直接alert();
	 *         $excelData:传入Excle解析结果数组
	 *         return $result:导入结果
	 */
	public static function readToDatabase($excelData) {
		foreach ( $excelData as $k => $v ) {
			if ($k > 1) {
				$data ['uid'] = $v [0];
				$data ['userName'] = $v [1];
				$data ['className'] = $v [2];
				$data ['pass1'] = $v [3];
				$data ['pass2'] = $v [4];
				if (ExcelToDatabase::readUserID($data ['uid'] )){
					error_log("ID重复");
				}else {
					error_log($data ['uid']);
					error_log("ID不重复");
				}
				
				if (ExcelToDatabase::readClass ( $data ['className'] )){
					error_log("存在");
				}else {
					error_log("不存在");
				}
				/*
				 * if ($data ['uid'] === "") {
				 * $result = '学生学号不能为空';
				 * return $result;
				 * }
				 * if (ExcelToDatabase::readUserID ( $data ['uid'] )) {
				 * $result = "学生".$data ['uid'].'学生学号已存在！';
				 * return $result;
				 * }
				 * if ($data ['userName'] === "") {
				 * $result = "学生".$data ['uid'].'学生姓名不能为空';
				 * return $result;
				 * }
				 * if ($data ['classID'] === "") {
				 * $result = "学生".$data ['uid'].'学生班级不能为空';
				 * return $result;
				 * }
				 * if (classAll . indexOf ( $data ['classID'] ) < 0) {
				 * $result = "学生".$data ['uid'].'学生班级不存在！';
				 * return $result;
				 * }
				 * if ($data ['pass1'] === "") {
				 * $result = "学生".$data ['uid'].'密码不能为空';
				 * return $result;
				 * }
				 * if ($data ['pass2'] === "") {
				 * $result = "学生".$data ['uid'].'确认密码不能为空';
				 * return $result;
				 * }
				 * if ($data ['pass1'] !== pass2) {
				 * $result = "学生".$data ['uid'].'密码两次输入不相同！';
				 * return $result;
				 * } else {
				 * // 导入数据库
				 * return $result = '导入成功！';
				 * }
				 */
			}
			
			/*
			 * if (!$result) {
			 * $this->error ( '导入数据库失败' );
			 * }
			 */
		}
	}
	/**
	 * 验证用户班级是否存在
	 * return true 用户班级存在; false 用户班级不存在
	 */
	public static function readClass($className) {
	$classAll = Student::model ()->findAll ("");
		foreach ( $classAll as $k => $v ) {
			$allName = $v ['classID'];
			error_log($allName);
			if (strpos ($allName, $className)){
				return true;
			}
		}
		return false;
	}
	/**
	 * 验证用户ID（学号）是否存在
	 * return true 用户ID（学号）重复; false 用户ID（学号）不重复
	 */
	public static function readUserID($userId) {
		$userAll = Student::model ()->findAll ();
		foreach ( $userAll as $k => $v ) {
			$Id = $v ['userID'];
			if ($Id==$userId){
				return true;
			}
		}
		return false;
	}
}

?>
	
</script>
