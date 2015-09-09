<?php
class AdminController extends CController {
	public $layout = '//layouts/adminBar';
	public function actionIndex() {
		$this->render ( 'index' );
	}
        public function actionSet(){       //set
            $result ='no';
            $mail='';
            $userid_now = Yii::app()->session['userid_now'];
            $user = Admin::model()->find('userID=?', array($userid_now));
            if (!empty($user->mail_address)) {
                $mail = $user->mail_address;
            }
            if(isset($_POST['old'])){
                    $new1=$_POST['new1'];
                    $defnew=$_POST['defnew'];
                    $email=$_POST['email'];
                    $usertype=Yii::app()->session['role_now'];
                    $user = Admin::model()->find('userID=?', array($userid_now));
                    if($user->password !== $_POST['old']){
                            $result='old error';
                            $this->render('set',['result'=>$result,'mail'=>$mail]);
                            return;
                    }
                    $user->password=$new1;
                    $user->mail_address=$email;
                    $result=$user->save();
                    $mail=$email;
            }

            $this->render('set',['result'=>$result,'mail'=>$mail]);
        }
	public function actionHardDeleteStu() {
		$pass = $_POST ['password'];
		$id = Yii::app ()->session ['userid_now'];
		$admin = Admin::model ()->findByPK ( $id );
		if ($admin->password !== $pass) {
			return $this->render ( 'confirmPass', [ 
					'wrong' => '密码错误，请重新输入。' 
			] );
		}
		$rows = 0;
		if (isset ( Yii::app ()->session ['deleteStuID'] )) {
			$userID = Yii::app ()->session ['deleteStuID'];
			unset ( Yii::app ()->session ['deleteStuID'] );
			$rows = Student::model ()->deleteByPK ( "$userID" );
		} else if (isset ( Yii::app ()->session ['deleteStuBox'] )) {
			$ids = Yii::app ()->session ['deleteStuBox'];
			unset ( Yii::app ()->session ['deleteStuBox'] );
			$condition = '';
			foreach ( $ids as $value ) {
				$condition = $condition . "'$value',";
			}
			$condition = $condition . "''";
			$rows = Student::model ()->deleteAll ( "userID in ($condition)" );
		}
		$stuLst = Student::model ()->findAll ( "is_delete = '1'" );
		$this->render ( 'recycleStu', array (
				'stuLst' => $stuLst,
				'rows' => $rows 
		) );
	}
	public function actionConfirmPass() {
		if (isset ( $_GET ['userID'] )) {
			Yii::app ()->session ['deleteStuID'] = $_GET ['userID'];
		} else if (isset ( $_POST ['checkbox'] )) {
			Yii::app ()->session ['deleteStuBox'] = $_POST ['checkbox'];
		}
		return $this->render ( 'confirmPass' );
	}
	public function actionRevokeStu() {
		$rows = 0;
		if (isset ( $_GET ['userID'] )) {
			$userID = $_GET ['userID'];
			$rows = Student::model ()->updateAll ( array (
					'is_delete' => '0' 
			), 'userID=:userID', array (
					':userID' => $userID 
			) );
		} else if (isset ( $_POST ['checkbox'] )) {
			$ids = $_POST ['checkbox'];
			$condition = '';
			foreach ( $ids as $value ) {
				$condition = $condition . "'$value',";
			}
			$condition = $condition . "''";
			$rows = Student::model ()->updateAll ( array (
					'is_delete' => '0' 
			), "userID in ($condition)" );
		}
		$stuLst = Student::model ()->findAll ( "is_delete = '1'" );
		$this->render ( 'recycleStu', array (
				'stuLst' => $stuLst,
				'rows' => $rows 
		) );
	}
	public function actionChangeLog() {
		$sql = "SELECT changeLog FROM course WHERE courseID=" . $_GET ['courseID'];
		$result = Yii::app ()->db->createCommand ( $sql )->query ();
		$log = $result->read () ['changeLog'];
		$this->render ( 'changeLog', array (
				'log' => $log,
				'source' => $_GET ['source'],
				'teacher' => $this->teaInClass () 
		) );
	}
	public function actionRecycleStu() {
		$stuLst = Student::model ()->findAll ( "is_delete = '1'" );
		$this->render ( 'recycleStu', array (
				'stuLst' => $stuLst 
		) );
	}
	public function actionStuLst() {
		if (isset ( $_GET ['page'] )) {
			Yii::app ()->session ['lastPage'] = $_GET ['page'];
		} else {
			Yii::app ()->session ['lastPage'] = 1;
		}
		Yii::app ()->session ['lastUrl'] = "stuLst";
		$result = Student::model ()->getStuLst ( "", "" );
		$stuLst = $result ['stuLst'];
		$pages = $result ['pages'];
		$this->render ( 'stuLst', array (
				'stuLst' => $stuLst,
				'pages' => $pages 
		) );
	}
	public function actionSearchStu() {
		if (isset ( $_GET ['page'] )) {
			Yii::app ()->session ['lastPage'] = $_GET ['page'];
		} else {
			Yii::app ()->session ['lastPage'] = 1;
		}
		Yii::app ()->session ['lastUrl'] = "searchStu";
		if (isset ( $_POST ['type'] )) {
			$type = $_POST ['type'];
			$value = $_POST ['value'];
			Yii::app ()->session ['searchStuType'] = $type;
			Yii::app ()->session ['searchStuValue'] = $value;
		} else {
			$type = Yii::app ()->session ['searchStuType'];
			$value = Yii::app ()->session ['searchStuValue'];
		}
		$result = Student::model ()->getStuLst ( $type, $value );
		$stuLst = $result ['stuLst'];
		$pages = $result ['pages'];
		$this->render ( 'searchStu', array (
				'stuLst' => $stuLst,
				'pages' => $pages 
		) );
	}
	public function actionAddStu() {
		$result = 'no';
		if (isset ( $_POST ['userID'] )) {
			$result = Student::model ()->insertStu ( $_POST ['userID'], $_POST ['userName'], $_POST ['password1'], $_POST ['classID'] );
		}
		$classAll = TbClass::model ()->findAll ( "" );
		$userAll = Student::model ()->findAll ();
		$this->render ( 'addStu', [ 
				'classAll' => $classAll,
				'userAll' => $userAll,
				'result' => $result 
		] );
	}
	public function actionExlAddStu() {
		$flag = 'no';
		if (isset ( $_POST ['flag'] )) {
			$flag = "1";
		}
		$this->render ( 'exlAddStu', [ 
				'flag' => $flag 
		] );
	}
	public function actionInfoStu() {
		if (Yii::app ()->session ['lastUrl'] == "infoClass") {
			$this->render ( 'infoStu', array (
					'id' => $_GET ['id'],
					'name' => $_GET ['name'],
					'class' => $_GET ['classID'] 
			) );
		} else if (isset ( $_GET ['flag'] )) {
			$this->render ( 'infoStu', array (
					'id' => $_GET ['id'],
					'name' => $_GET ['name'],
					'class' => $_GET ['class'],
					'flag' => $_GET ['flag'] 
			) );
		} else {
			$this->render ( 'infoStu', array (
					'id' => $_GET ['id'],
					'name' => $_GET ['name'],
					'class' => $_GET ['class'] 
			) );
		}
	}
	public function actionDeleteStuSearch() {
		$userID = $_GET ['id'];
		$thisStu = new Student ();
		$thisStu = $thisStu->find ( "userID = '$userID'" );
		$thisStu->is_delete = '1';
		$thisStu->update ();
		$type = Yii::app ()->session ['searchStuType'];
		$value = Yii::app ()->session ['searchStuValue'];
		$result = Student::model ()->getStuLst ( $type, $value );
		$stuLst = $result ['stuLst'];
		$pages = $result ['pages'];
		$this->render ( 'searchStu', array (
				'stuLst' => $stuLst,
				'pages' => $pages 
		) );
	}
	public function actionDeleteStu() {
		$userID = $_GET ['id'];
		$thisStu = new Student ();
		$thisStu = $thisStu->find ( "userID = '$userID'" );
		$thisStu->is_delete = '1';
		$thisStu->update ();
		$result = Student::model ()->getStuLst ( "", "" );
		$stuLst = $result ['stuLst'];
		$pages = $result ['pages'];
		$this->render ( 'stuLst', array (
				'stuLst' => $stuLst,
				'pages' => $pages 
		) );
	}
	public function actionDeleteStuDontHaveClass() {
		$userID = $_GET ['id'];
		$thisStu = new Student ();
		$thisStu = $thisStu->find ( "userID = '$userID'" );
		$thisStu->is_delete = '1';
		$thisStu->update ();
		Yii::app ()->session ['lastUrl'] = "stuDontHaveClass";
		$result = Student::model ()->getStuLst ( "classID", 0 );
		$this->render ( "stuDontHaveClass", [ 
				"stuLst" => $result ["stuLst"],
				"pages" => $result ['pages'] 
		] );
	}
	public function actionResetPass() {
		$userID = $_GET ['id'];
		$thisStu = new Student ();
		$thisStu = $thisStu->find ( "userID = '$userID'" );
		$thisStu->password = '000';
		
		$thisStu->update ();
		$classAll = TbClass::model ()->findAll ();
		$userAll = Student::model ()->findAll ();
		if (isset ( $_GET ['flag'] )) {
			$this->render ( 'editStu', array (
					'userID' => $_GET ['id'],
					'userName' => $thisStu->userName,
					'classID' => $thisStu->classID,
					'classAll' => $classAll,
					'userAll' => $userAll,
					'result' => '密码重置成功！',
					'flag' => 'search' 
			) );
		} else {
			$this->render ( 'editStu', array (
					'userID' => $_GET ['id'],
					'userName' => $thisStu->userName,
					'classID' => $thisStu->classID,
					'classAll' => $classAll,
					'userAll' => $userAll,
					'result' => '密码重置成功！' 
			) );
		}
	}
	public function actionEditStuInfo() {
		$userID = $_GET ['id'];
		$thisStu = new Student ();
		$thisStu = $thisStu->find ( "userID = '$userID'" );
		$thisStu->userID = $_POST ['userID'];
		$thisStu->userName = $_POST ['userName'];
		$thisStu->classID = $_POST ['classID'];
		$thisStu->update ();
		$classAll = TbClass::model ()->findAll ();
		$userAll = Student::model ()->findAll ();
		if (isset ( $_GET ['flag'] )) {
			$this->render ( 'editStu', array (
					'userID' => $thisStu->userID,
					'userName' => $thisStu->userName,
					'classID' => $thisStu->classID,
					'classAll' => $classAll,
					'userAll' => $userAll,
					'result' => '信息修改成功！',
					'flag' => $_GET ['flag'] 
			) );
		} else {
			$this->render ( 'editStu', array (
					'userID' => $thisStu->userID,
					'userName' => $thisStu->userName,
					'classID' => $thisStu->classID,
					'classAll' => $classAll,
					'userAll' => $userAll,
					'result' => '信息修改成功！' 
			) );
		}
	}
	public function actionEditStu() {
		$classAll = TbClass::model ()->findAll ();
		$userAll = Student::model ()->findAll ();
		if (isset ( $_GET ['flag'] )) {
			$this->render ( 'editStu', array (
					'userID' => $_GET ['id'],
					'userName' => $_GET ['name'],
					'classID' => $_GET ['class'],
					'classAll' => $classAll,
					'userAll' => $userAll,
					'flag' => 'search' 
			) );
		} else {
			$this->render ( 'editStu', array (
					'userID' => $_GET ['id'],
					'userName' => $_GET ['name'],
					'classID' => $_GET ['class'],
					'classAll' => $classAll,
					'userAll' => $userAll 
			) );
		}
	}
	
	// 是否存在指定班级
	public function exClass($classID) {
		$sql = "select * from tb_class where classID = '$classID'";
		$course = Yii::app ()->db->createCommand ( $sql )->query ();
		if (empty ( $course->read () ))
			return FALSE;
		else
			return TRUE;
	}
	public function actionTeaLst() {
		if (isset ( $_GET ['page'] )) {
			Yii::app ()->session ['lastPage'] = $_GET ['page'];
		} else {
			Yii::app ()->session ['lastPage'] = 1;
		}
		Yii::app ()->session ['lastUrl'] = "=teaLst";
		$result = Teacher::model ()->getTeaLst ( "", "" );
		$teaLst = $result ['teaLst'];
		$pages = $result ['pages'];
		$this->render ( 'teaLst', array (
				'teaLst' => $teaLst,
				'pages' => $pages 
		) );
	}
	public function actionSearchTea() {
		if (isset ( $_GET ['page'] )) {
			Yii::app ()->session ['lastPage'] = $_GET ['page'];
		} else {
			Yii::app ()->session ['lastPage'] = 1;
		}
		Yii::app ()->session ['lastUrl'] = "=searchTea";
		if (isset ( $_POST ['type'] )) {
			$type = $_POST ['type'];
			$value = $_POST ['value'];
			Yii::app ()->session ['searchTeaType'] = $type;
			Yii::app ()->session ['searchTeaValue'] = $value;
		} else {
			$type = Yii::app ()->session ['searchTeaType'];
			$value = Yii::app ()->session ['searchTeaValue'];
		}
		$result = Teacher::model ()->getTeaLst ( $type, $value );
		$teaLst = $result ['teaLst'];
		$pages = $result ['pages'];
		$this->render ( 'searchTea', array (
				'teaLst' => $teaLst,
				'pages' => $pages 
		) );
	}
	public function actionInfoTea() {
		if (Yii::app ()->session ['lastUrl'] == "infoClass") {
			$this->render ( 'infoTea', array (
					'id' => $_GET ['id'],
					'name' => $_GET ['name'],
					'classID' => $_GET ['classID'] 
			) );
		} else if (isset ( $_GET ['flag'] )) {
			$this->render ( 'infoTea', array (
					'id' => $_GET ['id'],
					'name' => $_GET ['name'],
					'flag' => $_GET ['flag'] 
			) );
		} else {
			$this->render ( 'infoTea', array (
					'id' => $_GET ['id'],
					'name' => $_GET ['name'] 
			) );
		}
	}
	public function actionAddTea() {
		$result = 'no';
		if (isset ( $_POST ['userID'] )) {
			$result = Teacher::model ()->insertTea ( $_POST ['userID'], $_POST ['userName'], $_POST ['password1'] );
		}
		$userAll = Teacher::model ()->findAll ();
		$this->render ( 'addTea', [ 
				'userAll' => $userAll,
				'result' => $result 
		] );
	}
	public function actionResetTeaPass() {
		$userID = $_GET ['id'];
		$thisTea = new Teacher ();
		$thisTea = $thisTea->find ( "userID = '$userID'" );
		$thisTea->password = '000';
		
		$thisTea->update ();
		$userAll = Teacher::model ()->findAll ();
		if (isset ( $_GET ['flag'] )) {
			$this->render ( 'editTea', array (
					'userID' => $_GET ['id'],
					'userName' => $thisTea->userName,
					'userAll' => $userAll,
					'result' => '密码重置成功！',
					'flag' => 'search' 
			) );
		} else {
			$this->render ( 'editTea', array (
					'userID' => $_GET ['id'],
					'userName' => $thisTea->userName,
					'userAll' => $userAll,
					'result' => '密码重置成功！' 
			) );
		}
	}
	public function actionEditTeaInfo() {
		$userID = $_GET ['id'];
		$thisTea = new Teacher ();
		$thisTea = $thisTea->find ( "userID = '$userID'" );
		$thisTea->userID = $_POST ['userID'];
		$thisTea->userName = $_POST ['userName'];
		$thisTea->update ();
		$userAll = Teacher::model ()->findAll ();
		if (isset ( $_GET ['flag'] )) {
			$this->render ( 'editTea', array (
					'userID' => $thisTea->userID,
					'userName' => $thisTea->userName,
					'userAll' => $userAll,
					'result' => '信息修改成功！',
					'flag' => $_GET ['flag'] 
			) );
		} else {
			$this->render ( 'editTea', array (
					'userID' => $thisTea->userID,
					'userName' => $thisTea->userName,
					'userAll' => $userAll,
					'result' => '信息修改成功！' 
			) );
		}
	}
	public function actionEditTea() {
		$userAll = Teacher::model ()->findAll ();
		if (isset ( $_GET ['flag'] )) {
			$this->render ( 'editTea', array (
					'userID' => $_GET ['id'],
					'userName' => $_GET ['name'],
					'userAll' => $userAll,
					'flag' => 'search' 
			) );
		} else {
			$this->render ( 'editTea', array (
					'userID' => $_GET ['id'],
					'userName' => $_GET ['name'],
					'userAll' => $userAll 
			) );
		}
	}
	public function actionDeleteTeaSearch() {
		$userID = $_GET ['id'];
		$thisTea = new Teacher ();
		$thisTea = $thisTea->find ( "userID = '$userID'" );
		$thisTea->is_delete = '1';
		$thisTea->update ();
		$type = Yii::app ()->session ['searchTeaType'];
		$value = Yii::app ()->session ['searchTeaValue'];
		$result = Teacher::model ()->getTeaLst ( $type, $value );
		$teaLst = $result ['teaLst'];
		$pages = $result ['pages'];
		$this->render ( 'searchTea', array (
				'teaLst' => $teaLst,
				'pages' => $pages 
		) );
	}
	public function actionDeleteTea() {
		$userID = $_GET ['id'];
		$thisTea = new Teacher ();
		$thisTea = $thisTea->find ( "userID = '$userID'" );
		$thisTea->is_delete = '1';
		$thisTea->update ();
		$result = Teacher::model ()->getTeaLst ( "", "" );
		$teaLst = $result ['teaLst'];
		$pages = $result ['pages'];
		$this->render ( 'teaLst', array (
				'teaLst' => $teaLst,
				'pages' => $pages 
		) );
	}
	public function actionHardDeleteTea() {
		$pass = $_POST ['password'];
		$id = Yii::app ()->session ['userid_now'];
		$admin = Admin::model ()->findByPK ( $id );
		if ($admin->password !== $pass) {
			return $this->render ( 'confirmTeaPass', [ 
					'wrong' => '密码错误，请重新输入。' 
			] );
		}
		$rows = 0;
		if (isset ( Yii::app ()->session ['deleteTeaID'] )) {
			$userID = Yii::app ()->session ['deleteTeaID'];
			unset ( Yii::app ()->session ['deleteTeaID'] );
			TeacherClass::model ()->deleteAll ( "teacherID = '$userID'" );
			$rows = Teacher::model ()->deleteByPK ( "$userID" );
		} else if (isset ( Yii::app ()->session ['deleteTeaBox'] )) {
			$ids = Yii::app ()->session ['deleteTeaBox'];
			unset ( Yii::app ()->session ['deleteTeaBox'] );
			$condition = '';
			foreach ( $ids as $value ) {
				$condition = $condition . "'$value',";
			}
			$condition = $condition . "''";
			TeacherClass::model ()->deleteAll ( "teacherID in ($condition)" );
			$rows = Teacher::model ()->deleteAll ( "userID in ($condition)" );
		}
		$teaLst = Teacher::model ()->findAll ( "is_delete = '1'" );
		$this->render ( 'recycleTea', array (
				'teaLst' => $teaLst,
				'rows' => $rows 
		) );
	}
	public function actionConfirmTeaPass() {
		if (isset ( $_GET ['userID'] )) {
			Yii::app ()->session ['deleteTeaID'] = $_GET ['userID'];
		} else if (isset ( $_POST ['checkbox'] )) {
			Yii::app ()->session ['deleteTeaBox'] = $_POST ['checkbox'];
		}
		return $this->render ( 'confirmTeaPass' );
	}
	public function actionRevokeTea() {
		$rows = 0;
		if (isset ( $_GET ['userID'] )) {
			$userID = $_GET ['userID'];
			$rows = Teacher::model ()->updateAll ( array (
					'is_delete' => '0' 
			), 'userID=:userID', array (
					':userID' => $userID 
			) );
		} else if (isset ( $_POST ['checkbox'] )) {
			$ids = $_POST ['checkbox'];
			$condition = '';
			foreach ( $ids as $value ) {
				$condition = $condition . "'$value',";
			}
			$condition = $condition . "''";
			$rows = Teacher::model ()->updateAll ( array (
					'is_delete' => '0' 
			), "userID in ($condition)" );
		}
		$teaLst = Teacher::model ()->findAll ( "is_delete = '1'" );
		$this->render ( 'recycleTea', array (
				'teaLst' => $teaLst,
				'rows' => $rows 
		) );
	}
	public function actionRecycleTea() {
		$teaLst = Teacher::model ()->findAll ( "is_delete = '1'" );
		$this->render ( 'recycleTea', array (
				'teaLst' => $teaLst 
		) );
	}
	public function actionClassLst() {
		if (isset ( $_GET ['page'] )) {
			Yii::app ()->session ['lastPage'] = $_GET ['page'];
		} else {
			Yii::app ()->session ['lastPage'] = 1;
		}
		// 显示结果列表并分页
		Yii::app ()->session ['lastClassUrl'] = "classLst";
		$result = TbClass::model ()->getClassLst ();
		$this->render ( 'classLst', array (
				'posts' => $result ['classLst'],
				'pages' => $result ['pages'],
				'nums' => TbClass::model ()->numInClass (),
				'teacher' => TbClass::model ()->teaInClass (),
				'teacherOfClass' => TbClass::model ()->teaByClass () 
		) );
	}
	public function actionStuDontHaveClass() {
		if (isset ( $_GET ['page'] )) {
			Yii::app ()->session ['lastPage'] = $_GET ['page'];
		} else {
			Yii::app ()->session ['lastPage'] = 1;
		}
		Yii::app ()->session ['lastUrl'] = "stuDontHaveClass";
		$result = Student::model ()->getStuLst ( "classID", 0 );
		$this->render ( "stuDontHaveClass", [ 
				"stuLst" => $result ["stuLst"],
				"pages" => $result ['pages'] 
		] );
	}
	public function actionSearchClass() {
		if (isset ( $_GET ['page'] )) {
			Yii::app ()->session ['lastPage'] = $_GET ['page'];
		} else {
			Yii::app ()->session ['lastPage'] = 1;
		}
		Yii::app ()->session ['lastUrl'] = "searchClass";
		if (isset ( $_POST ['which'] )) {
			$type = $_POST ['which'];
			$value = $_POST ['value'];
			Yii::app ()->session ['searchType'] = $type;
			Yii::app ()->session ['searchValue'] = $value;
		} else {
			$type = Yii::app ()->session ['searchType'];
			$value = Yii::app ()->session ['searchValue'];
		}
		$ex_sq = "";
		if (isset ( $type )) {
			if ($type == "classID" || $type == "className") {
				$ex_sq = " WHERE " . $type . " = '" . $value . "'";
			} else if ($type == "courseID") {
				$ex_sq = " WHERE currentCourse = '" . $value . "'";
			} else if ($type == "teaName") {
				$sql = "SELECT * FROM teacher WHERE userName ='" . $value . "'";
				$an = Yii::app ()->db->createCommand ( $sql )->query ();
				$temp = $an->read ();
				if (! empty ( $temp ))
					$teaID = $temp ['userID'];
				else
					$teaID = - 1;
				$sql = "SELECT * FROM teacher_class WHERE teacherID ='" . $teaID . "'";
				$an = Yii::app ()->db->createCommand ( $sql )->query ();
				$temp = $an->read ();
				if (! empty ( $temp )) {
					$ex_sq = " WHERE ";
					$id = $temp ['classID'];
					$ex_sq = $ex_sq . "classID = '$id'";
					$temp = $an->read ();
					while ( ! empty ( $temp ) ) {
						$id = $temp ['classID'];
						$ex_sq = $ex_sq . " OR classID = '$id'";
						$temp = $an->read ();
					}
				} else {
					$ex_sq = " WHERE classID = 0";
				}
			} else {
				$ex_sq = "";
			}
		}
		$sql = "SELECT * FROM tb_class " . $ex_sq;
		$criteria = new CDbCriteria ();
		$result = Yii::app ()->db->createCommand ( $sql )->query ();
		$pages = new CPagination ( $result->rowCount );
		$pages->pageSize = 10;
		$pages->applyLimit ( $criteria );
		$result = Yii::app ()->db->createCommand ( $sql . " LIMIT :offset,:limit" );
		$result->bindValue ( ':offset', $pages->currentPage * $pages->pageSize );
		$result->bindValue ( ':limit', $pages->pageSize );
		$posts = $result->query ();
		$this->render ( 'searchClass', array (
				'posts' => $posts,
				'pages' => $pages,
				'nums' => TbClass::model ()->numInClass (),
				'teacher' => TbClass::model ()->teaInClass (),
				'teacherOfClass' => TbClass::model ()->teaByClass () 
		) );
	}
	public function actionAddClass() {
		$result = 'no';
		if (isset ( $_POST ['className'] )) {
			$result = TbClass::model ()->insertClass ( $_POST ['className'], $_POST ['courseID'] );
		}
		$this->render ( 'addClass', [ 
				'result' => $result 
		] );
	}
	public function actionInfoClass() {
		Yii::app ()->session ['lastUrl'] = "infoClass";
		$act_result = "";
		$classID = $_GET ["classID"];
		
		// 删除某学生的班级
		if (isset ( $_GET ['flag'] )) {
			if ($_GET ['flag'] == 'deleteStu') {
				$sql = "UPDATE student SET classID= '0' WHERE userID= '" . $_GET ['id'] . "'";
				Yii::app ()->db->createCommand ( $sql )->query ();
				$act_result = "删除成功！";
			} else if ($_GET ['flag'] == 'deleteTea') {
				$sql = "DELETE FROM teacher_class WHERE teacherID = '" . $_GET ['id'] . "' AND classID = '" . $classID . "'";
				Yii::app ()->db->createCommand ( $sql )->query ();
				$act_result = "删除成功！";
			}
			unset ( $_GET ['flag'] );
		}
		
		if (isset ( $_GET ['action'] ) && isset ( $_POST ['checkbox'] )) {
			$checkbox = $_POST ['checkbox'];
			if ($_GET ['action'] == "addStu") {
				for($i = 0; $i < count ( $checkbox ); $i ++) {
					if (! is_null ( $checkbox [$i] )) {
						$stuID = $checkbox [$i];
						$sql = "UPDATE student SET classID= '" . $classID . "' WHERE userID= '" . $stuID . "'";
						Yii::app ()->db->createCommand ( $sql )->query ();
					}
				}
				$act_result = "添加 $i 名学生成功！";
			} else if ($_GET ['action'] == "addTea") {
				for($i = 0; $i < count ( $checkbox ); $i ++) {
					if (! is_null ( $checkbox [$i] )) {
						$teaID = $checkbox [$i];
						$sql = "INSERT INTO teacher_class VALUES ('" . $teaID . "','" . $classID . "','')";
						Yii::app ()->db->createCommand ( $sql )->query ();
					}
				}
				$act_result = "添加 $i 位老师成功！";
			}
		}
		
		$sql = "SELECT * FROM tb_class WHERE classID = '$classID'";
		$an = Yii::app ()->db->createCommand ( $sql )->query ();
		$class = $an->read ();
		$className = $class ['className'];
		$curCourse = $class ['currentCourse'];
		$curLesson = $class ['currentLesson'];
		
		$sql = "SELECT * FROM student WHERE classID = '$classID' AND is_delete = 0";
		$criteria = new CDbCriteria ();
		$stus = Yii::app ()->db->createCommand ( $sql )->query ();
		$nums = $stus->rowCount;
		
		$sql = "SELECT * FROM teacher_class WHERE classID =$classID";
		$teacherOfClass = Yii::app ()->db->createCommand ( $sql )->query ();
		
		$this->render ( 'infoCLass', array (
				'classID' => $classID,
				'className' => $className,
				'curCourse' => $curCourse,
				'curLesson' => $curLesson,
				'teacher' => TbClass::model ()->teaInClass (),
				'teacherOfClass' => $teacherOfClass,
				'nums' => $nums, // 学生人数
				'stus' => $stus, // 学生
				'result' => $act_result 
		), false, true );
	}
	public function actionAddStuClass() {
		$sql = "SELECT * FROM student WHERE classID = '0' AND is_delete = 0";
		$result = Yii::app ()->db->createCommand ( $sql )->query ();
		$this->render ( 'addStuClass', array (
				'classID' => $_GET ["classID"],
				'posts' => $result 
		)
		 );
	}
	public function actionAddTeaClass() {
		$classID = $_GET ["classID"];
		$sql = "SELECT teacherID FROM teacher_class WHERE classID = '$classID'  order by teacherID ASC";
		$result = Yii::app ()->db->createCommand ( $sql )->query ();
		$this->render ( 'addTeaClass', array (
				'classID' => $classID,
				'posts' => $result,
				'teachers' => TbClass::model ()->teaInClass () 
		)
		 );
	}
	public function actionLookLst() {
		if (isset ( $_GET ['page'] )) {
			Yii::app ()->session ['lastPage'] = $_GET ['page'];
		} else {
			Yii::app ()->session ['lastPage'] = 1;
		}
		$result = LookType::model ()->getLookLst ( "", "" );
		$lookLst = $result ['lookLst'];
		$pages = $result ['pages'];
		Yii::app ()->session ['lastUrl'] = "lookLst";
		$this->render ( 'lookLst', array (
				'lookLst' => $lookLst,
				'pages' => $pages,
				'teacher' => Teacher::model ()->findall () 
		) );
	}
	public function actionSearchLook() {
		if (isset ( $_GET ['page'] )) {
			Yii::app ()->session ['lastPage'] = $_GET ['page'];
		} else {
			Yii::app ()->session ['lastPage'] = 1;
		}
		if (isset ( $_POST ['type'] )) {
			$type = $_POST ['type'];
			$value = $_POST ['value'];
			Yii::app ()->session ['searchType'] = $type;
			Yii::app ()->session ['searchValue'] = $value;
		} else {
			$type = Yii::app ()->session ['searchType'];
			$value = Yii::app ()->session ['searchValue'];
		}
		Yii::app ()->session ['lastUrl'] = "searchLook";
		if ($type == 'createPerson') {
			if ($value == "管理员")
				$vaule = 0;
			else {
				$tea = Teacher::model ()->find ( "userName = '$value'" );
				if ($tea ['userID'] != "")
					$value = $tea ['userID'];
				else
					$value = - 1;
			}
		}
		if ($type == "content") {
			$searchKey = $value;
		} else {
			$searchKey = "no";
		}
		$result = LookType::model ()->getLookLst ( $type, $value );
		$lookLst = $result ['lookLst'];
		$pages = $result ["pages"];
		$this->render ( 'searchLook', array (
				'lookLst' => $lookLst,
				'pages' => $pages,
				'teacher' => TbClass::model ()->teaInClass (),
				'searchKey' => $searchKey 
		) );
	}
	public function actionAddLook() {
		$result = 'no';
		if (isset ( $_POST ['title'] )) {
			$result = LookType::model ()->insertLook ( $_POST ['title'], $_POST ['content'], 0 );
		}
		$this->render ( 'addLook', [ 
				'result' => $result 
		] );
	}
	public function actionReturnFromAddLook() {
		if (Yii::app ()->session ['lastUrl'] == "lookLst") {
			$result = LookType::model ()->getLookLst ( "", "" );
			$lookLst = $result ['lookLst'];
			$pages = $result ['pages'];
			Yii::app ()->session ['lastUrl'] = "lookLst";
			$this->render ( 'lookLst', array (
					'lookLst' => $lookLst,
					'pages' => $pages,
					'teacher' => Teacher::model ()->findall () 
			) );
		} else {
			$type = Yii::app ()->session ['searchType'];
			$value = Yii::app ()->session ['searchValue'];
			Yii::app ()->session ['lastUrl'] = "searchKey";
			if ($type == 'createPerson') {
				if ($value == "管理员")
					$vaule = 0;
				else {
					$tea = Teacher::model ()->find ( "userName = '$value'" );
					if ($tea ['userID'] != "")
						$value = $tea ['userID'];
					else
						$value = - 1;
				}
			}
			
			if ($type == "content") {
				$searchKey = $value;
			} else {
				$searchKey = "no";
			}
			$result = LookType::model ()->getLookLst ( $type, $value );
			$lookLst = $result ['lookLst'];
			$pages = $result ["pages"];
			$this->render ( 'searchLook', array (
					'lookLst' => $lookLst,
					'pages' => $pages,
					'teacher' => TbClass::model ()->teaInClass (),
					'searchKey' => $searchKey 
			) );
		}
	}
	public function actionDeleteLook() {
		$exerciseID = $_GET ['exerciseID'];
		$thisLook = new LookType ();
		$deleteResult = $thisLook->deleteAll ( "exerciseID = '$exerciseID'" );
		if (Yii::app ()->session ['lastUrl'] == "lookLst") {
			$result = LookType::model ()->getLookLst ( "", "" );
			$lookLst = $result ['lookLst'];
			$pages = $result ['pages'];
			Yii::app ()->session ['lastUrl'] = "lookLst";
			$this->render ( 'lookLst', array (
					'lookLst' => $lookLst,
					'pages' => $pages,
					'teacher' => Teacher::model ()->findall (),
					'deleteResult' => $deleteResult 
			) );
		} else {
			$type = Yii::app ()->session ['searchType'];
			$value = Yii::app ()->session ['searchValue'];
			Yii::app ()->session ['lastUrl'] = "searchKey";
			if ($type == 'createPerson') {
				if ($value == "管理员")
					$vaule = 0;
				else {
					$tea = Teacher::model ()->find ( "userName = '$value'" );
					if ($tea ['userID'] != "")
						$value = $tea ['userID'];
					else
						$value = - 1;
				}
			}
			if ($type == "content") {
				$searchKey = $value;
			} else {
				$searchKey = "no";
			}
			$result = LookType::model ()->getLookLst ( $type, $value );
			$lookLst = $result ['lookLst'];
			$pages = $result ["pages"];
			$this->render ( 'searchLook', array (
					'lookLst' => $lookLst,
					'pages' => $pages,
					'teacher' => TbClass::model ()->teaInClass (),
					'deleteResult' => $deleteResult,
					'searchKey' => $searchKey 
			)
			 );
		}
	}
	public function actionEditLook() {
		$exerciseID = $_GET ["exerciseID"];
		$sql = "SELECT * FROM look_type WHERE exerciseID = '$exerciseID'";
		$result = Yii::app ()->db->createCommand ( $sql )->query ();
		$result = $result->read ();
		if (! isset ( $_GET ['action'] )) {
			$this->render ( "editLook", array (
					'exerciseID' => $exerciseID,
					'title' => $result ['title'],
					'content' => $result ['content'] 
			) );
		} else if ($_GET ['action'] = 'look') {
			$this->render ( "editLook", array (
					'exerciseID' => $exerciseID,
					'title' => $result ['title'],
					'content' => $result ['content'],
					'action' => 'look' 
			) );
		}
	}
	public function actionEditLookInfo() {
		$exerciseID = $_GET ['exerciseID'];
		$thisLook = new LookType ();
		$thisLook = $thisLook->find ( "exerciseID = '$exerciseID'" );
		$thisLook->title = $_POST ['title'];
		$thisLook->content = $_POST ['content'];
		$thisLook->update ();
		$this->render ( "editLook", array (
				'exerciseID' => $thisLook->exerciseID,
				'title' => $thisLook->title,
				'content' => $thisLook->content,
				'result' => "修改习题成功" 
		) );
	}
	public function actionKeyLst() {
		if (isset ( $_GET ['page'] )) {
			Yii::app ()->session ['lastPage'] = $_GET ['page'];
		} else {
			Yii::app ()->session ['lastPage'] = 1;
		}
		$result = KeyType::model ()->getKeyLst ( "", "" );
		$keyLst = $result ['keyLst'];
		$pages = $result ['pages'];
		Yii::app ()->session ['lastUrl'] = "keyLst";
		$this->render ( 'keyLst', array (
				'keyLst' => $keyLst,
				'pages' => $pages,
				'teacher' => Teacher::model ()->findall () 
		) );
	}
	public function actionSearchKey() {
		if (isset ( $_GET ['page'] )) {
			Yii::app ()->session ['lastPage'] = $_GET ['page'];
		} else {
			Yii::app ()->session ['lastPage'] = 1;
		}
		if (isset ( $_POST ['type'] )) {
			$type = $_POST ['type'];
			$value = $_POST ['value'];
			Yii::app ()->session ['searchType'] = $type;
			Yii::app ()->session ['searchValue'] = $value;
		} else {
			$type = Yii::app ()->session ['searchType'];
			$value = Yii::app ()->session ['searchValue'];
		}
		Yii::app ()->session ['lastUrl'] = "searchKey";
		if ($type == 'createPerson') {
			if ($value == "管理员")
				$vaule = 0;
			else {
				$tea = Teacher::model ()->find ( "userName = '$value'" );
				if ($tea ['userID'] != "")
					$value = $tea ['userID'];
				else
					$value = - 1;
			}
		}
		$result = KeyType::model ()->getKeyLst ( $type, $value );
		$keyLst = $result ['keyLst'];
		$pages = $result ["pages"];
		$this->render ( 'searchKey', array (
				'keyLst' => $keyLst,
				'pages' => $pages,
				'teacher' => TbClass::model ()->teaInClass () 
		) );
	}
	public function actionReturnFromAddKey() {
		if (Yii::app ()->session ['lastUrl'] == "keyLst") {
			$result = KeyType::model ()->getKeyLst ( "", "" );
			$keyLst = $result ['keyLst'];
			$pages = $result ['pages'];
			Yii::app ()->session ['lastUrl'] = "keyLst";
			$this->render ( 'keyLst', array (
					'keyLst' => $keyLst,
					'pages' => $pages,
					'teacher' => Teacher::model ()->findall () 
			) );
		} else {
			$type = Yii::app ()->session ['searchType'];
			$value = Yii::app ()->session ['searchValue'];
			Yii::app ()->session ['lastUrl'] = "searchKey";
			if ($type == 'createPerson') {
				if ($value == "管理员")
					$vaule = 0;
				else {
					$tea = Teacher::model ()->find ( "userName = '$value'" );
					if ($tea ['userID'] != "")
						$value = $tea ['userID'];
					else
						$value = - 1;
				}
			}
			$result = KeyType::model ()->getKeyLst ( $type, $value );
			$keyLst = $result ['keyLst'];
			$pages = $result ["pages"];
			$this->render ( 'searchKey', array (
					'keyLst' => $keyLst,
					'pages' => $pages,
					'teacher' => TbClass::model ()->teaInClass () 
			) );
		}
	}
	public function actionDeleteKey() {
		$exerciseID = $_GET ['exerciseID'];
		$thisKey = new KeyType ();
		$deleteResult = $thisKey->deleteAll ( "exerciseID = '$exerciseID'" );
		
		if (Yii::app ()->session ['lastUrl'] == "keyLst") {
			$result = KeyType::model ()->getKeyLst ( "", "" );
			$keyLst = $result ['keyLst'];
			$pages = $result ['pages'];
			Yii::app ()->session ['lastUrl'] = "keyLst";
			$this->render ( 'keyLst', array (
					'keyLst' => $keyLst,
					'pages' => $pages,
					'teacher' => Teacher::model ()->findall (),
					'deleteResult' => $deleteResult 
			) );
		} else {
			$type = Yii::app ()->session ['searchType'];
			$value = Yii::app ()->session ['searchValue'];
			Yii::app ()->session ['lastUrl'] = "searchKey";
			if ($type == 'createPerson') {
				if ($value == "管理员")
					$vaule = 0;
				else {
					$tea = Teacher::model ()->find ( "userName = '$value'" );
					if ($tea ['userID'] != "")
						$value = $tea ['userID'];
					else
						$value = - 1;
				}
			}
			$result = KeyType::model ()->getKeyLst ( $type, $value );
			$keyLst = $result ['keyLst'];
			$pages = $result ["pages"];
			$this->render ( 'searchKey', array (
					'keyLst' => $keyLst,
					'pages' => $pages,
					'teacher' => TbClass::model ()->teaInClass (),
					'deleteResult' => $deleteResult 
			) );
		}
	}
	public function actionAddKey() {
		$result = 'no';
		if (isset ( $_POST ['title'] )) {
			$i = 2;
			$answer = $_POST ['in1'];
			for(; $i <= 3 * 10; $i ++) {
				if ($_POST ['in' . $i] != "")
					$answer = $answer . "$" . $_POST ['in' . $i];
				else
					break;
			}
			$result = KeyType::model ()->insertKey ( $_POST ['title'], $answer, 0 );
		}
		$this->render ( 'addKey', [ 
				'result' => $result 
		] );
	}
	public function actionEditKey() {
		$exerciseID = $_GET ["exerciseID"];
		$sql = "SELECT * FROM key_type WHERE exerciseID = '$exerciseID'";
		$result = Yii::app ()->db->createCommand ( $sql )->query ();
		$result = $result->read ();
		if (! isset ( $_GET ['action'] )) {
			$this->render ( "editKey", array (
					'exerciseID' => $exerciseID,
					'title' => $result ['title'],
					'content' => $result ['content'] 
			) );
		} else if ($_GET ['action'] = 'look') {
			$this->render ( "editKey", array (
					'exerciseID' => $exerciseID,
					'title' => $result ['title'],
					'content' => $result ['content'],
					'action' => 'look' 
			) );
		}
	}
	public function actionEditKeyInfo() {
		$exerciseID = $_GET ['exerciseID'];
		$thisKey = new KeyType ();
		$thisKey = $thisKey->find ( "exerciseID = '$exerciseID'" );
		$i = 2;
		$answer = $_POST ['in1'];
		for(; $i <= 3 * 10; $i ++) {
			if ($_POST ['in' . $i] != "")
				$answer = $answer . "$" . $_POST ['in' . $i];
			else
				break;
		}
		$thisKey->title = $_POST ['title'];
		$thisKey->content = $answer;
		$thisKey->update ();
		$this->render ( "editKey", array (
				'exerciseID' => $thisKey->exerciseID,
				'title' => $thisKey->title,
				'content' => $thisKey->content,
				'result' => "修改习题成功" 
		) );
	}
	public function actionListenLst() {
		if (isset ( $_GET ['page'] )) {
			Yii::app ()->session ['lastPage'] = $_GET ['page'];
		} else {
			Yii::app ()->session ['lastPage'] = 1;
		}
		$result = ListenType::model ()->getListenLst ( "", "" );
		$listenLst = $result ['listenLst'];
		$pages = $result ['pages'];
		Yii::app ()->session ['lastUrl'] = "listenLst";
		$this->render ( 'listenLst', array (
				'listenLst' => $listenLst,
				'pages' => $pages,
				'teacher' => Teacher::model ()->findall () 
		) );
	}
	public function actionSearchListen() {
		if (isset ( $_GET ['page'] )) {
			Yii::app ()->session ['lastPage'] = $_GET ['page'];
		} else {
			Yii::app ()->session ['lastPage'] = 1;
		}
		if (isset ( $_POST ['type'] )) {
			$type = $_POST ['type'];
			$value = $_POST ['value'];
			Yii::app ()->session ['searchType'] = $type;
			Yii::app ()->session ['searchValue'] = $value;
		} else {
			$type = Yii::app ()->session ['searchType'];
			$value = Yii::app ()->session ['searchValue'];
		}
		Yii::app ()->session ['lastUrl'] = "searchListen";
		if ($type == 'createPerson') {
			if ($value == "管理员")
				$vaule = 0;
			else {
				$tea = Teacher::model ()->find ( "userName = '$value'" );
				if ($tea ['userID'] != "")
					$value = $tea ['userID'];
				else
					$value = - 1;
			}
		}
		if ($type == "content") {
			$searchKey = $value;
		} else {
			$searchKey = "no";
		}
		$result = ListenType::model ()->getListenLst ( $type, $value );
		$listenLst = $result ['listenLst'];
		$pages = $result ["pages"];
		$this->render ( 'searchListen', array (
				'listenLst' => $listenLst,
				'pages' => $pages,
				'teacher' => TbClass::model ()->teaInClass (),
				'searchKey' => $searchKey 
		) );
	}
	public function actionAddListen() {
		$result = 'no';
		$typename = Yii::app ()->session ['role_now'];
		$userid = Yii::app ()->session ['userid_now'];
		$filePath = $typename . "/" . $userid . "/";
		$dir = "resources/" . $filePath;
		if (! is_dir ( $dir )) {
			mkdir ( $dir, 0777 );
		}
		
		if (isset ( $_POST ['title'] )) {
			if ($_FILES ['file'] ['type'] != "audio/mpeg") {
				$result = '文件格式不正确，应为MP3格式';
			} else if ($_FILES ['file'] ['error'] > 0) {
				$result = '文件上传失败';
			} else if (file_exists ( $dir . iconv ( "UTF-8", "gb2312", $_FILES ["file"] ["name"] ) )) {
				$result = '服务器存在相同文件';
			} else {
				move_uploaded_file ( $_FILES ["file"] ["tmp_name"], $dir . iconv ( "UTF-8", "gb2312", $_FILES ["file"] ["name"] ) );
				$result = '1';
			}
			if ($result == '1') {
				$result = ListenType::model ()->insertListen ( $_POST ['title'], $_POST ['content'], $_FILES ["file"] ["name"], $filePath, 0 );
			}
		}
		$this->render ( 'addListen', [ 
				'result' => $result 
		] );
	}
	public function actionReturnFromAddListen() {
		if (Yii::app ()->session ['lastUrl'] == "listenLst") {
			$result = ListenType::model ()->getListenLst ( "", "" );
			$listenLst = $result ['listenLst'];
			$pages = $result ['pages'];
			Yii::app ()->session ['lastUrl'] = "listenLst";
			$this->render ( 'listenLst', array (
					'listenLst' => $listenLst,
					'pages' => $pages,
					'teacher' => Teacher::model ()->findall () 
			) );
		} else {
			$type = Yii::app ()->session ['searchType'];
			$value = Yii::app ()->session ['searchValue'];
			Yii::app ()->session ['lastUrl'] = "searchListen";
			if ($type == 'createPerson') {
				if ($value == "管理员")
					$vaule = 0;
				else {
					$tea = Teacher::model ()->find ( "userName = '$value'" );
					if ($tea ['userID'] != "")
						$value = $tea ['userID'];
					else
						$value = - 1;
				}
			}
			if ($type == "content") {
				$searchKey = $value;
			} else {
				$searchKey = "no";
			}
			$result = ListenType::model ()->getListenLst ( $type, $value );
			$listenLst = $result ['listenLst'];
			$pages = $result ["pages"];
			$this->render ( 'searchListen', array (
					'listenLst' => $listenLst,
					'pages' => $pages,
					'teacher' => TbClass::model ()->teaInClass (),
					'searchKey' => $searchKey 
			) );
		}
	}
	public function actionDeleteListen() {
		$exerciseID = $_GET ['exerciseID'];
		$thisListen = new ListenType ();
		$deleteListen = $thisListen->findAll ( "exerciseID = '$exerciseID'" );
		$deleteResult = $thisListen->deleteAll ( "exerciseID = '$exerciseID'" );
		$filePath = $deleteListen [0] ['filePath'];
		$fileName = $deleteListen [0] ['fileName'];
		if ($deleteResult == '1') {
			$typename = Yii::app ()->session ['role_now'];
			$userid = Yii::app ()->session ['userid_now'];
			// 怎么用EXER_LISTEN_URL
			$path = 'resources/' . $filePath . iconv ( "UTF-8", "gb2312", $fileName );
			unlink ( $path );
		}
		if (Yii::app ()->session ['lastUrl'] == "listenLst") {
			$result = ListenType::model ()->getListenLst ( "", "" );
			$listenLst = $result ['listenLst'];
			$pages = $result ['pages'];
			Yii::app ()->session ['lastUrl'] = "listenLst";
			$this->render ( 'listenLst', array (
					'listenLst' => $listenLst,
					'pages' => $pages,
					'teacher' => Teacher::model ()->findall (),
					'deleteResult' => $deleteResult 
			) );
		} else {
			$type = Yii::app ()->session ['searchType'];
			$value = Yii::app ()->session ['searchValue'];
			Yii::app ()->session ['lastUrl'] = "searchListen";
			if ($type == 'createPerson') {
				if ($value == "管理员")
					$vaule = 0;
				else {
					$tea = Teacher::model ()->find ( "userName = '$value'" );
					if ($tea ['userID'] != "")
						$value = $tea ['userID'];
					else
						$value = - 1;
				}
			}
			if ($type == "content") {
				$searchKey = $value;
			} else {
				$searchKey = "no";
			}
			$result = ListenType::model ()->getListenLst ( $type, $value );
			$listenLst = $result ['listenLst'];
			$pages = $result ["pages"];
			$this->render ( 'searchListen', array (
					'listenLst' => $listenLst,
					'pages' => $pages,
					'teacher' => TbClass::model ()->teaInClass (),
					'deleteResult' => $deleteResult,
					'searchKey' => $searchKey 
			) );
		}
	}
	public function actionEditlisten() {
		$exerciseID = $_GET ["exerciseID"];
		$sql = "SELECT * FROM listen_type WHERE exerciseID = '$exerciseID'";
		$result = Yii::app ()->db->createCommand ( $sql )->query ();
		$result = $result->read ();
		if (! isset ( $_GET ['action'] )) {
			$this->render ( "editListen", array (
					'exerciseID' => $exerciseID,
					'title' => $result ['title'],
					'filename' => $result ['fileName'],
					'filepath' => $result ['filePath'],
					'content' => $result ['content'] 
			) );
		} else if ($_GET ['action'] = 'look') {
			$this->render ( "editListen", array (
					'exerciseID' => $exerciseID,
					'title' => $result ['title'],
					'filename' => $result ['fileName'],
					'filepath' => $result ['filePath'],
					'content' => $result ['content'],
					'action' => 'look' 
			) );
		}
	}
	public function actionEditListenInfo() {
		$typename = Yii::app ()->session ['role_now'];
		$userid = Yii::app ()->session ['userid_now'];
		$filePath = $typename . "/" . $userid . "/";
		$dir = "resources/" . $filePath;
		$exerciseID = $_GET ['exerciseID'];
		$filename = $_GET ['oldfilename'];
		$result = "修改失败";
		if ($_FILES ['modifyfile'] ['tmp_name']) {
			if ($_FILES ['modifyfile'] ['type'] != "audio/mpeg") {
				$result = '文件格式不正确，应为MP3格式';
			} else if ($_FILES ['modifyfile'] ['error'] > 0) {
				$result = '文件上传失败';
			} else if (file_exists ( $dir . iconv ( "UTF-8", "gb2312", $_FILES ["modifyfile"] ["name"] ) )) {
				$result = '服务器存在相同文件';
			} else {
				move_uploaded_file ( $_FILES ["modifyfile"] ["tmp_name"], $dir . iconv ( "UTF-8", "gb2312", $_FILES ["modifyfile"] ["name"] ) );
				unlink ( $dir . iconv ( "UTF-8", "gb2312", $filename ) );
				$result = '修改失败';
			}
		}
		$thisListen = new ListenType ();
		$thisListen = $thisListen->find ( "exerciseID = '$exerciseID'" );
		$thisListen->title = $_POST ['title'];
		if ($_FILES ['modifyfile'] ['tmp_name']) {
			$thisListen->fileName = $_FILES ['modifyfile'] ['name'];
		} else {
			$thisListen->fileName = $filename;
		}
		$thisListen->content = $_POST ['content'];
		if ($result == '修改失败') {
			$thisListen->update ();
			$result = "修改成功";
		}
		$this->render ( "editListen", array (
				'exerciseID' => $thisListen->exerciseID,
				'filename' => $thisListen->fileName,
				'filepath' => $thisListen->filePath,
				'title' => $thisListen->title,
				'content' => $thisListen->content,
				'result' => $result 
		) );
	}
	public function actionFillLst() {
		if (isset ( $_GET ['page'] )) {
			Yii::app ()->session ['lastPage'] = $_GET ['page'];
		} else {
			Yii::app ()->session ['lastPage'] = 1;
		}
		$result = Filling::model ()->getFillLst ( "", "" );
		$fillLst = $result ['fillLst'];
		$pages = $result ['pages'];
		Yii::app ()->session ['lastUrl'] = "fillLst";
		$this->render ( 'fillLst', array (
				'fillLst' => $fillLst,
				'pages' => $pages,
				'teacher' => Teacher::model ()->findall () 
		) );
	}
	public function actionSearchFill() {
		if (isset ( $_GET ['page'] )) {
			Yii::app ()->session ['lastPage'] = $_GET ['page'];
		} else {
			Yii::app ()->session ['lastPage'] = 1;
		}
		if (isset ( $_POST ['type'] )) {
			$type = $_POST ['type'];
			$value = $_POST ['value'];
			Yii::app ()->session ['searchFillType'] = $type;
			Yii::app ()->session ['searchFillValue'] = $value;
		} else {
			$type = Yii::app ()->session ['searchFillType'];
			$value = Yii::app ()->session ['searchFillValue'];
		}
		Yii::app ()->session ['lastUrl'] = "searchFill";
		if ($type == 'createPerson') {
			if ($value == "管理员")
				$vaule = 0;
			else {
				$tea = Teacher::model ()->find ( "userName = '$value'" );
				if ($tea ['userID'] != "")
					$value = $tea ['userID'];
				else
					$value = - 1;
			}
		}
		if ($type == "requirements") {
			$searchKey = $value;
		} else {
			$searchKey = "no";
		}
		$result = Filling::model ()->getFillLst ( $type, $value );
		$fillLst = $result ['fillLst'];
		$pages = $result ['pages'];
		$this->render ( 'searchFill', array (
				'fillLst' => $fillLst,
				'pages' => $pages,
				'teacher' => Teacher::model ()->findall (),
				'searchKey' => $searchKey 
		) );
	}
	public function actionAddFill() {
		$result = 'no';
		if (isset ( $_POST ['requirements'] )) {
			$i = 2;
			$answer = $_POST ['in1'];
			for(; $i <= 5; $i ++) {
				if ($_POST ['in' . $i] != "")
					$answer = $answer . "$$" . $_POST ['in' . $i];
				else
					break;
			}
			$result = Filling::model ()->insertFill ( $_POST ['requirements'], $answer, 0 );
		}
		$this->render ( 'addFill', [ 
				'result' => $result 
		] );
	}
	public function actionReturnFromAddFill() {
		if (Yii::app ()->session ['lastUrl'] == "searchFill") {
			$type = Yii::app ()->session ['searchFillType'];
			$value = Yii::app ()->session ['searchFillValue'];
			if ($type == 'createPerson') {
				if ($value == "管理员")
					$vaule = 0;
				else {
					$tea = Teacher::model ()->find ( "userName = '$value'" );
					if ($tea ['userID'] != "")
						$value = $tea ['userID'];
					else
						$value = - 1;
				}
			}
			if ($type == "requirements") {
				$searchKey = $value;
			} else {
				$searchKey = "no";
			}
			$result = Filling::model ()->getFillLst ( $type, $value );
			$fillLst = $result ['fillLst'];
			$pages = $result ['pages'];
			$this->render ( 'searchFill', array (
					'fillLst' => $fillLst,
					'pages' => $pages,
					'teacher' => Teacher::model ()->findall (),
					'searchKey' => $searchKey 
			)
			 );
		} else {
			$result = Filling::model ()->getFillLst ( "", "" );
			$fillLst = $result ['fillLst'];
			$pages = $result ['pages'];
			Yii::app ()->session ['lastUrl'] = "fillLst";
			$this->render ( 'fillLst', array (
					'fillLst' => $fillLst,
					'pages' => $pages,
					'teacher' => Teacher::model ()->findall () 
			) );
		}
	}
	public function actionDeleteFill() {
		$exerciseID = $_GET ["exerciseID"];
		$thisFill = new Filling ();
		$deleteResult = $thisFill->deleteAll ( "exerciseID = '$exerciseID'" );
		if (Yii::app ()->session ['lastUrl'] == "searchFill") {
			$type = Yii::app ()->session ['searchFillType'];
			$value = Yii::app ()->session ['searchFillValue'];
			if ($type == 'createPerson') {
				if ($value == "管理员")
					$vaule = 0;
				else {
					$tea = Teacher::model ()->find ( "userName = '$value'" );
					if ($tea ['userID'] != "")
						$value = $tea ['userID'];
					else
						$value = - 1;
				}
			}
			if ($type == "requirements") {
				$searchKey = $value;
			} else {
				$searchKey = "no";
			}
			$result = Filling::model ()->getFillLst ( $type, $value );
			$fillLst = $result ['fillLst'];
			$pages = $result ['pages'];
			$this->render ( 'searchFill', array (
					'fillLst' => $fillLst,
					'pages' => $pages,
					'teacher' => Teacher::model ()->findall (),
					'deleteResult' => $deleteResult,
					'searchKey' => $searchKey 
			) );
		} else {
			$result = Filling::model ()->getFillLst ( "", "" );
			$fillLst = $result ['fillLst'];
			$pages = $result ['pages'];
			Yii::app ()->session ['lastUrl'] = "fillLst";
			$this->render ( 'fillLst', array (
					'fillLst' => $fillLst,
					'pages' => $pages,
					'teacher' => Teacher::model ()->findall (),
					'deleteResult' => $deleteResult 
			) );
		}
	}
	public function actionEditFill() {
		$exerciseID = $_GET ["exerciseID"];
		$thisFill = new Filling ();
		$thisFill = $thisFill->find ( "exerciseID = '$exerciseID'" );
		if (! isset ( $_GET ['action'] )) {
			$this->render ( "editFill", array (
					'exerciseID' => $exerciseID,
					'requirements' => $thisFill->requirements,
					'answer' => $thisFill->answer 
			) );
		} else if ($_GET ['action'] = 'look') {
			$this->render ( "editFill", array (
					'exerciseID' => $exerciseID,
					'requirements' => $thisFill->requirements,
					'answer' => $thisFill->answer,
					'action' => 'look' 
			) );
		}
	}
	public function actionEditFillInfo() {
		$exerciseID = $_GET ['exerciseID'];
		$thisFill = new Filling ();
		$thisFill = $thisFill->find ( "exerciseID = '$exerciseID'" );
		$i = 2;
		$answer = $_POST ['in1'];
		for(; $i <= 5; $i ++) {
			if ($_POST ['in' . $i] != "")
				$answer = $answer . "$$" . $_POST ['in' . $i];
			else
				break;
		}
		$thisFill->requirements = $_POST ['requirements'];
		$thisFill->answer = $answer;
		$thisFill->update ();
		$this->render ( "editFill", array (
				'exerciseID' => $thisFill->exerciseID,
				'requirements' => $thisFill->requirements,
				'answer' => $thisFill->answer,
				'result' => "修改习题成功" 
		) );
	}
	public function actionChoiceLst() {
		if (isset ( $_GET ['page'] )) {
			Yii::app ()->session ['lastPage'] = $_GET ['page'];
		} else {
			Yii::app ()->session ['lastPage'] = 1;
		}
		$result = Choice::model ()->getChoiceLst ( "", "" );
		$choiceLst = $result ['choiceLst'];
		$pages = $result ['pages'];
		Yii::app ()->session ['lastUrl'] = "choiceLst";
		$this->render ( 'choiceLst', array (
				'choiceLst' => $choiceLst,
				'pages' => $pages,
				'teacher' => Teacher::model ()->findall () 
		) );
	}
	public function actionSearchChoice() {
		if (isset ( $_GET ['page'] )) {
			Yii::app ()->session ['lastPage'] = $_GET ['page'];
		} else {
			Yii::app ()->session ['lastPage'] = 1;
		}
		if (isset ( $_POST ['type'] )) {
			$type = $_POST ['type'];
			$value = $_POST ['value'];
			Yii::app ()->session ['searchChoiceType'] = $type;
			Yii::app ()->session ['searchChoiceValue'] = $value;
		} else {
			$type = Yii::app ()->session ['searchChoiceType'];
			$value = Yii::app ()->session ['searchChoiceValue'];
		}
		Yii::app ()->session ['lastUrl'] = "searchChoice";
		if ($type == 'createPerson') {
			if ($value == "管理员")
				$vaule = 0;
			else {
				$tea = Teacher::model ()->find ( "userName = '$value'" );
				if ($tea ['userID'] != "")
					$value = $tea ['userID'];
				else
					$value = - 1;
			}
		}
		if ($type == "requirements") {
			$searchKey = $value;
		} else {
			$searchKey = "no";
		}
		$result = Choice::model ()->getChoiceLst ( $type, $value );
		$choiceLst = $result ['choiceLst'];
		$pages = $result ['pages'];
		$this->render ( 'searchChoice', array (
				'choiceLst' => $choiceLst,
				'pages' => $pages,
				'teacher' => Teacher::model ()->findall (),
				'searchKey' => $searchKey 
		) );
	}
	public function actionReturnFromAddChoice() {
		if (Yii::app ()->session ['lastUrl'] == "searchChoice") {
			$type = Yii::app ()->session ['searchChoiceType'];
			$value = Yii::app ()->session ['searchChoiceValue'];
			if ($type == 'createPerson') {
				if ($value == "管理员")
					$vaule = 0;
				else {
					$tea = Teacher::model ()->find ( "userName = '$value'" );
					if ($tea ['userID'] != "")
						$value = $tea ['userID'];
					else
						$value = - 1;
				}
			}
			if ($type == "requirements") {
				$searchKey = $value;
			} else {
				$searchKey = "no";
			}
			$result = Choice::model ()->getChoiceLst ( $type, $value );
			$choiceLst = $result ['choiceLst'];
			$pages = $result ['pages'];
			$this->render ( 'searchChoice', array (
					'choiceLst' => $choiceLst,
					'pages' => $pages,
					'teacher' => Teacher::model ()->findall (),
					'searchKey' => $searchKey 
			) );
		} else {
			$result = Choice::model ()->getChoiceLst ( "", "" );
			$choiceLst = $result ['choiceLst'];
			$pages = $result ['pages'];
			Yii::app ()->session ['lastUrl'] = "choiceLst";
			$this->render ( 'choiceLst', array (
					'choiceLst' => $choiceLst,
					'pages' => $pages,
					'teacher' => Teacher::model ()->findall () 
			) );
		}
	}
	public function actionEditChoiceInfo() {
		$exerciseID = $_GET ['exerciseID'];
		$thisCh = new Choice ();
		$thisCh = $thisCh->find ( "exerciseID = '$exerciseID'" );
		$thisCh->requirements = $_POST ['requirements'];
		$thisCh->options = $_POST ['A'] . "$$" . $_POST ['B'] . "$$" . $_POST ['C'] . "$$" . $_POST ['D'];
		$thisCh->answer = $_POST ['answer'];
		$thisCh->update ();
		$this->render ( "editChoice", array (
				'exerciseID' => $exerciseID,
				'requirements' => $thisCh->requirements,
				'options' => $thisCh->options,
				'answer' => $thisCh->answer,
				'result' => "修改习题成功" 
		) );
	}
	public function actionEditChoice() {
		$exerciseID = $_GET ['exerciseID'];
		$sql = "SELECT * FROM choice WHERE exerciseID = '$exerciseID'";
		$result = Yii::app ()->db->createCommand ( $sql )->query ();
		$result = $result->read ();
		if (! isset ( $_GET ['action'] )) {
			$this->render ( "editChoice", array (
					'exerciseID' => $result ['exerciseID'],
					'requirements' => $result ['requirements'],
					'options' => $result ['options'],
					'answer' => $result ['answer'] 
			) );
		} else if ($_GET ['action'] == 'look') {
			$this->render ( "editChoice", array (
					'exerciseID' => $result ['exerciseID'],
					'requirements' => $result ['requirements'],
					'options' => $result ['options'],
					'answer' => $result ['answer'],
					'action' => 'look' 
			) );
		}
	}
	public function actionAddChoice() {
		$result = 'no';
		if (isset ( $_POST ['requirements'] )) {
			$result = Choice::model ()->insertChoice ( $_POST ['requirements'], $_POST ['A'] . "$$" . $_POST ['B'] . "$$" . $_POST ['C'] . "$$" . $_POST ['D'], $_POST ['answer'], 0 );
		}
		$this->render ( 'addChoice', [ 
				'result' => $result 
		] );
	}
	
	// 2015 8-7 宋杰 删除选择题
	public function actionDeleteChoice() {
		$exerciseID = $_GET ["exerciseID"];
		$thisChoice = new Choice ();
		$deleteResult = $thisChoice->deleteAll ( "exerciseID = '$exerciseID'" );
		if (Yii::app ()->session ['lastUrl'] == "searchChoice") {
			$type = Yii::app ()->session ['searchChoiceType'];
			$value = Yii::app ()->session ['searchChoiceValue'];
			if ($type == "requirements") {
				$searchKey = $value;
			} else {
				$searchKey = "no";
			}
			$result = Choice::model ()->getChoiceLst ( $type, $value );
			$choiceLst = $result ['choiceLst'];
			$pages = $result ['pages'];
			$this->render ( 'searchChoice', array (
					'choiceLst' => $choiceLst,
					'pages' => $pages,
					'teacher' => Teacher::model ()->findall (),
					'deleteResult' => $deleteResult,
					'searchKey' => $searchKey 
			)
			 );
		} else {
			$result = Choice::model ()->getChoiceLst ( "", "" );
			$choiceLst = $result ['choiceLst'];
			$pages = $result ['pages'];
			Yii::app ()->session ['lastUrl'] = "choiceLst";
			$this->render ( 'choiceLst', array (
					'choiceLst' => $choiceLst,
					'pages' => $pages,
					'teacher' => Teacher::model ()->findall (),
					'deleteResult' => $deleteResult 
			) );
		}
	}
	public function actionQuestionLst() {
		if (isset ( $_GET ['page'] )) {
			Yii::app ()->session ['lastPage'] = $_GET ['page'];
		} else {
			Yii::app ()->session ['lastPage'] = 1;
		}
		Yii::app ()->session ['lastUrl'] = "questionLst";
		$result = Question::model ()->getQuestionLst ( "", "" );
		$questionLst = $result ['questionLst'];
		$pages = $result ["pages"];
		$this->render ( 'questionLst', array (
				'questionLst' => $questionLst,
				'pages' => $pages,
				'teacher' => TbClass::model ()->teaInClass () 
		) );
	}
	public function actionSearchQuestion() {
		if (isset ( $_GET ['page'] )) {
			Yii::app ()->session ['lastPage'] = $_GET ['page'];
		} else {
			Yii::app ()->session ['lastPage'] = 1;
		}
		if (isset ( $_POST ['type'] )) {
			$type = $_POST ['type'];
			$value = $_POST ['value'];
			Yii::app ()->session ['searchType'] = $type;
			Yii::app ()->session ['searchValue'] = $value;
		} else {
			$type = Yii::app ()->session ['searchType'];
			$value = Yii::app ()->session ['searchValue'];
		}
		Yii::app ()->session ['lastUrl'] = "searchQuestion";
		if ($type == 'createPerson') {
			if ($value == "管理员")
				$vaule = 0;
			else {
				$tea = Teacher::model ()->find ( "userName = '$value'" );
				if ($tea ['userID'] != "")
					$value = $tea ['userID'];
				else
					$value = - 1;
			}
		}
		if ($type == "requirements") {
			$searchKey = $value;
		} else {
			$searchKey = "no";
		}
		$result = Question::model ()->getQuestionLst ( $type, $value );
		$questionLst = $result ['questionLst'];
		$pages = $result ["pages"];
		$this->render ( 'searchQuestion', array (
				'questionLst' => $questionLst,
				'pages' => $pages,
				'teacher' => TbClass::model ()->teaInClass (),
				'searchKey' => $searchKey 
		) );
	}
	public function actionReturnFromAddQuestion() {
		if (Yii::app ()->session ['lastUrl'] == "questionLst") {
			$result = Question::model ()->getQuestionLst ( "", "" );
			$questionLst = $result ['questionLst'];
			$pages = $result ["pages"];
			$this->render ( 'questionLst', array (
					'questionLst' => $questionLst,
					'pages' => $pages,
					'teacher' => TbClass::model ()->teaInClass () 
			) );
		} else {
			$type = Yii::app ()->session ['searchType'];
			$value = Yii::app ()->session ['searchValue'];
			Yii::app ()->session ['lastUrl'] = "searchQuestion";
			if ($type == 'createPerson') {
				if ($value == "管理员")
					$vaule = 0;
				else {
					$tea = Teacher::model ()->find ( "userName = '$value'" );
					if ($tea ['userID'] != "")
						$value = $tea ['userID'];
					else
						$value = - 1;
				}
			}
			if ($type == "requirements") {
				$searchKey = $value;
			} else {
				$searchKey = "no";
			}
			$result = Question::model ()->getQuestionLst ( $type, $value );
			$questionLst = $result ['questionLst'];
			$pages = $result ["pages"];
			$this->render ( 'searchQuestion', array (
					'questionLst' => $questionLst,
					'pages' => $pages,
					'teacher' => TbClass::model ()->teaInClass (),
					'searchKey' => $searchKey 
			) );
		}
	}
	public function actionDeleteQuestion() {
		$exerciseID = $_GET ['exerciseID'];
		$thisQue = new Question ();
		$deleteResult = $thisQue->deleteAll ( "exerciseID = '$exerciseID'" );
		if (Yii::app ()->session ['lastUrl'] == "questionLst") {
			$result = Question::model ()->getQuestionLst ( "", "" );
			$questionLst = $result ['questionLst'];
			$pages = $result ["pages"];
			$this->render ( 'questionLst', array (
					'questionLst' => $questionLst,
					'pages' => $pages,
					'teacher' => TbClass::model ()->teaInClass (),
					'deleteResult' => $deleteResult 
			) );
		} else {
			$type = Yii::app ()->session ['searchType'];
			$value = Yii::app ()->session ['searchValue'];
			Yii::app ()->session ['lastUrl'] = "searchQuestion";
			if ($type == 'createPerson') {
				if ($value == "管理员")
					$vaule = 0;
				else {
					$tea = Teacher::model ()->find ( "userName = '$value'" );
					if ($tea ['userID'] != "")
						$value = $tea ['userID'];
					else
						$value = - 1;
				}
			}
			
			if ($type == "requirements") {
				$searchKey = $value;
			} else {
				$searchKey = "no";
			}
			$result = Question::model ()->getQuestionLst ( $type, $value );
			$questionLst = $result ['questionLst'];
			$pages = $result ["pages"];
			$this->render ( 'searchQuestion', array (
					'questionLst' => $questionLst,
					'pages' => $pages,
					'teacher' => TbClass::model ()->teaInClass (),
					'deleteResult' => $deleteResult,
					'searchKey' => $searchKey 
			) );
		}
	}
	public function actionEditQuestion() {
		$exerciseID = $_GET ["exerciseID"];
		$sql = "SELECT * FROM question WHERE exerciseID = '$exerciseID'";
		$result = Yii::app ()->db->createCommand ( $sql )->query ();
		$result = $result->read ();
		if (! isset ( $_GET ['action'] )) {
			$this->render ( "editQuestion", array (
					'exerciseID' => $result ['exerciseID'],
					'requirements' => $result ['requirements'],
					'answer' => $result ['answer'] 
			) );
		} else if ($_GET ['action'] == 'look') {
			$this->render ( "editQuestion", array (
					'exerciseID' => $result ['exerciseID'],
					'requirements' => $result ['requirements'],
					'answer' => $result ['answer'],
					'action' => 'look' 
			) );
		}
	}
	public function actionEditQuestionInfo() {
		$exerciseID = $_GET ['exerciseID'];
		$thisQue = new Question ();
		$thisQue = $thisQue->find ( "exerciseID = '$exerciseID'" );
		$thisQue->requirements = $_POST ['requirements'];
		$thisQue->answer = $_POST ['answer'];
		$thisQue->update ();
		$this->render ( "editQuestion", array (
				'exerciseID' => $thisQue->exerciseID,
				'requirements' => $thisQue->requirements,
				'answer' => $thisQue->answer,
				'result' => "修改习题成功" 
		) );
	}
	public function actionAddQuestion() {
		$result = 'no';
		if (isset ( $_POST ['requirements'] )) {
			$result = Question::model ()->insertQue ( $_POST ['requirements'], $_POST ['answer'], 0 );
		}
		$this->render ( 'addQuestion', [ 
				'result' => $result 
		] );
	}
	public function actionCourseLst() {
		// if(!empty($_POST['courseName']))
		// //得到当前最大的学生ID
		// $sql="select max(courseID) as id from course";
		// $max_id = Yii::app()->db->createCommand($sql)->query();
		// $temp=$max_id->read();
		// if(empty($temp))
		// {
		// $new_id=1;
		// }
		// else
		// {
		// $new_id = $temp['id'] + 1;
		// }
		// $sql= "INSERT INTO course VALUES ('".$new_id ."','" .$_POST['courseName'] ."','0','".date('y-m-d H:i:s',time()) ."','')";
		// Yii::app()->db->createCommand($sql)->query();
		// $act_result="新建课程成功！";
		// unset($_GET['action']);
		if (isset ( $_GET ['page'] )) {
			Yii::app ()->session ['lastPage'] = $_GET ['page'];
		} else {
			Yii::app ()->session ['lastPage'] = 1;
		}
		$result = Course::model ()->getCourseLst ( "", "" );
		$courseLst = $result ['courseLst'];
		$pages = $result ['pages'];
		Yii::app ()->session ['lastUrl'] = "courseLst";
		$this->render ( 'courseLst', array (
				'courseLst' => $courseLst,
				'pages' => $pages,
				'teacher' => Teacher::model ()->findall () 
		) );
	}
	public function actionSearchCourse() {
		if (isset ( $_GET ['page'] )) {
			Yii::app ()->session ['lastPage'] = $_GET ['page'];
		} else {
			Yii::app ()->session ['lastPage'] = 1;
		}
		if (isset ( $_POST ['type'] )) {
			$type = $_POST ['type'];
			$value = $_POST ['value'];
			Yii::app ()->session ['searchType'] = $type;
			Yii::app ()->session ['searchValue'] = $value;
		} else {
			$type = Yii::app ()->session ['searchType'];
			$value = Yii::app ()->session ['searchValue'];
		}
		Yii::app ()->session ['lastUrl'] = "searchCourse";
		if ($type == 'createPerson') {
			if ($value == "管理员")
				$vaule = 0;
			else {
				$tea = Teacher::model ()->find ( "userName = '$value'" );
				if ($tea ['userID'] != "")
					$value = $tea ['userID'];
				else
					$value = - 1;
			}
		}
		$result = Course::model ()->getCourseLst ( $type, $value );
		$courseLst = $result ['courseLst'];
		$pages = $result ['pages'];
		$this->render ( 'searchCourse', array (
				'courseLst' => $courseLst,
				'pages' => $pages,
				'teacher' => Teacher::model ()->findall () 
		) );
	}
	public function actionAddCourse() {
		$result = 'no';
		if (isset ( $_POST ['courseName'] )) {
			$result = Course::model ()->insertCourse ( $_POST ['courseName'], 0 );
		}
		$this->render ( 'addCourse', [ 
				'result' => $result 
		] );
	}
	public function actionInfoCourse() {
		$courseID = $_GET ['courseID'];
		$courseName = $_GET ['courseName'];
		$createPerson = $_GET ['createPerson'];
		
		// 定义动作
		$act_result = "";
		
		// 搜索动作
		if (isset ( $_POST ['which'] )) {
			if (! empty ( $_POST ['name'] )) {
				$ex_sq = " AND " . $_POST ['which'] . " = '" . $_POST ['name'] . "'";
			} else
				$ex_sq = "";
		} else
			$ex_sq = "";
			
			// 添加动作
		if (isset ( $_GET ['action'] )) {
			// 添加课程
			if ($_GET ['action'] == 'add') {
				if (! empty ( $_POST ['lessonName'] )) {
					
					// 得到当前最大的lessonID
					$sql = "select max(lessonID) as id from lesson";
					$max_id = Yii::app ()->db->createCommand ( $sql )->query ();
					$temp = $max_id->read ();
					if (empty ( $temp )) {
						$new_id = 1;
					} else {
						$new_id = $temp ['id'] + 1;
					}
					// 得到该lesson最大的number
					$sql = "select max(number) as number from lesson WHERE courseID = '" . $courseID . "'";
					$max_num = Yii::app ()->db->createCommand ( $sql )->query ();
					$temp = $max_num->read ();
					if (empty ( $temp )) {
						$new_num = 1;
					} else {
						$new_num = $temp ['number'] + 1;
					}
					
					$sql = "INSERT INTO lesson VALUES ('" . $new_id . "','" . $new_num . "','" . $_POST ['lessonName'] . "','" . $courseID . "','0','" . date ( 'y-m-d H:i:s', time () ) . "')";
					Yii::app ()->db->createCommand ( $sql )->query ();
					
					// 得到该suite最大的
					$sql = "select max(suiteID) as id from suite";
					$max_s = Yii::app ()->db->createCommand ( $sql )->query ();
					$temp = $max_s->read ();
					if (empty ( $temp )) {
						$new_s = 1;
					} else {
						$new_s = $temp ['id'] + 1;
					}
					$sql = "INSERT INTO suite VALUES ('" . $new_s . "','" . $new_id . "','课堂练习','classwork','" . date ( 'y-m-d H:i:s', time () ) . "','0','0')";
					Yii::app ()->db->createCommand ( $sql )->query ();
					$new_s = $new_s + 1;
					$sql = "INSERT INTO suite VALUES ('" . $new_s . "','" . $new_id . "','自我练习','exercise','" . date ( 'y-m-d H:i:s', time () ) . "','0','0')";
					Yii::app ()->db->createCommand ( $sql )->query ();
					
					$act_result = "新建课程成功！";
					unset ( $_GET ['action'] );
				} else {
					// 用户输入参数不足
					$this->render ( 'addCourse', array (
							'shao' => "输入不能为空",
							'courseID' => $courseID,
							'courseName' => $courseName,
							'createPerson' => $createPerson 
					) );
					return;
				}
			}
		}
		
		// 显示结果列表并分页
		$sql = "SELECT * FROM lesson WHERE courseID = $courseID " . $ex_sq;
		$criteria = new CDbCriteria ();
		$result = Yii::app ()->db->createCommand ( $sql )->query ();
		$pages = new CPagination ( $result->rowCount );
		$pages->pageSize = 10;
		$pages->applyLimit ( $criteria );
		$result = Yii::app ()->db->createCommand ( $sql . " LIMIT :offset,:limit" );
		$result->bindValue ( ':offset', $pages->currentPage * $pages->pageSize );
		$result->bindValue ( ':limit', $pages->pageSize );
		$posts = $result->query ();
		$this->render ( 'infoCourse', array (
				'courseID' => $courseID,
				'courseName' => $courseName,
				'createPerson' => $createPerson,
				'posts' => $posts,
				'pages' => $pages,
				'result' => $act_result 
		), false, true );
	}
	public function actionAddLesson() {
		$courseID = $_GET ['courseID'];
		$courseName = $_GET ['courseName'];
		$createPerson = $_GET ['createPerson'];
		
		$this->render ( 'addLesson', array (
				'courseID' => $courseID,
				'courseName' => $courseName,
				'createPerson' => $createPerson 
		) );
	}
	public function actionLessonBranch() {
		$sql = "SELECT * FROM suite WHERE lessonID = '" . $_GET ['lessonID'] . "'";
		$result = Yii::app ()->db->createCommand ( $sql )->query ();
		$class = $result->read ();
		$exer = $result->read ();
		$this->render ( 'lessonBranch', array (
				'lessonName' => $_GET ['lessonName'],
				'class' => $class,
				'exer' => $exer 
		) );
	}
        
	public function actionGoverLesson() {
		$suiteID = $_GET ['suiteID'];
		$suiteName = $_GET ['suiteName'];
		$suiteType = $_GET ['suiteType'];
	}
	// Uncomment the following methods and override them if needed
	/*
	 * public function filters()
	 * {
	 * // return the filter configuration for this controller, e.g.:
	 * return array(
	 * 'inlineFilterName',
	 * array(
	 * 'class'=>'path.to.FilterClass',
	 * 'propertyName'=>'propertyValue',
	 * ),
	 * );
	 * }
	 *
	 * public function actions()
	 * {
	 * // return external action classes, e.g.:
	 * return array(
	 * 'action1'=>'path.to.ActionClass',
	 * 'action2'=>array(
	 * 'class'=>'path.to.AnotherActionClass',
	 * 'propertyName'=>'propertyValue',
	 * ),
	 * );
	 * }
	 */
}