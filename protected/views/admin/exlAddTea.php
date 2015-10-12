<?php require 'teaSideBar.php';?>
<div class="span9">
	<h2>添 加 老 师</h2>
	<form action="./index.php?r=admin/exlAddTea" class="form-horizontal"
		method="post" id="form-exlAddTea" enctype="multipart/form-data">
		<fieldset>
			<legend>批量添加</legend>
			<div class="control-group">
				<label class="control-label" for="input01">请选择Excel文件
				</label>
				<div class="controls">
					<input type="hidden" name="flag" id="flag" value="1" /> <input
						type="hidden" name="MAX_FILE_SIZE" value="3000000" /> <input
						type="file" name="file" id="file" />
				</div>
			</div>
			<div class="form-actions">
				<input type="submit" class="btn btn-primary" value="添加" />&nbsp;&nbsp;<a
					href="./index.php?r=admin/AddTea" class="btn">逐个添加</a>&nbsp;&nbsp;<a
					href="./index.php?r=admin/teaLst" class="btn">返回</a>
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
							echo "  window.wxc.xcConfirm('不是Exale文件', window.wxc.xcConfirm.typeEnum.warning);";
							error_log ( $file_type, 0 );
						} else {
							// 解析文件并存入数据库逻辑
							/* 设置上传路径 */
							$savePath = dirname ( Yii::app ()->BasePath ) . '/public/upload/excel/';
							/* 以时间来命名上传的文件 */
							$str = date ( 'Ymdhis' );
							$file_name = "Tea".$str . ".xls";
							if (! copy ( $tmp_file, $savePath . $file_name )) {
								echo " window.wxc.xcConfirm('上传失败', window.wxc.xcConfirm.typeEnum.error);";
							} else {
								$res = Tool::excelreadToArray ( $savePath . $file_name, $file_type );
								$uploadResult = Tool::excelReadTeaToDatabase( $res );
								echo " window.wxc.xcConfirm('$uploadResult', window.wxc.xcConfirm.typeEnum.confirm);";
							}
						}
					} else {
						echo "window.wxc.xcConfirm('没有文件', window.wxc.xcConfirm.typeEnum.warning);";
					}
					?>
        }
});


	
</script>
