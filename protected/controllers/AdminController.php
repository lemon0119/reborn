<?php

class AdminController extends CController {

    public $layout = '//layouts/adminBar';

    public function actionIndex() {
        $this->render('index');
    }
    //联系我们
    public function actionContact(){
        return $this->renderpartial('contact');
    }
    //法律声明
    public function actionlegalNotice() {
        return $this->renderpartial('legalNotice');
    }
    public function actionSet() {       //set
        $result = 'no';
        $mail = '';
        $userid_now = Yii::app()->session['userid_now'];
        $user = Admin::model()->find('userID=?', array($userid_now));
        if (!empty($user->mail_address)) {
            $mail = $user->mail_address;
        }
        if (isset($_POST['old'])) {
            $new1 = $_POST['new1'];
            $defnew = $_POST['defnew'];
//            $email = $_POST['email'];
            $usertype = Yii::app()->session['role_now'];
            $user = Admin::model()->find('userID=?', array($userid_now));
            if ($user->password != md5($_POST['old'])) {
                $result = 'old error';
                $this->render('set', ['result' => $result,]);
                return;
            }
            $user->password = md5($new1);
//            $user->mail_address = $email;
            $result = $user->update();
//            $mail = $email;
        }

        $this->render('set', ['result' => $result]);
    }

    public function actionHardDeleteStu() {
        if(isset($_POST ['password'])){
            $pass = md5($_POST ['password']);
            $id = Yii::app()->session ['userid_now'];
            $admin = Admin::model()->findByPK($id);
            if ($admin->password !== $pass) {
                return $this->render('confirmPass', [
                        'wrong' => '密码错误，请重新输入。'
                ]);
            }
        }
        $rows = 0;
        if (isset(Yii::app()->session ['deleteStuID'])) {
            $userID = Yii::app()->session ['deleteStuID'];
            unset(Yii::app()->session ['deleteStuID']);
            $rows = Student::model()->deleteByPK("$userID");
            Student::model()->delStuRes($userID);
        } else if (isset(Yii::app()->session ['deleteStuBox'])) {
            $ids = Yii::app()->session ['deleteStuBox'];
            unset(Yii::app()->session ['deleteStuBox']);
            $condition = '';
            foreach ($ids as $value) {
                $condition = $condition . "'$value',";
                Student::model()->delStuRes($value);
            }
            $condition = $condition . "''";
            $rows = Student::model()->deleteAll("userID in ($condition)");
        }
         if(isset($_GET ['page'])){
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        }else {
            Yii::app()->session ['lastPage'] = 1;
        }
         Yii::app()->session ['lastUrl'] = "recyclestu";
        $sql = "SELECT * FROM student WHERE is_delete = '1'";
        $array_stuLst = Tool::pager($sql,10);
        $pages = $array_stuLst['pages'];
        $stuLst=$array_stuLst['list'];
//        $stuLst = Student::model()->findAll("is_delete = '1'");
        $this->render('recycleStu', array(
            'pages' => $pages,
            'stuLst' => $stuLst,
            'rows' => $rows
        ));
    }

    public function actionConfirmPass() {
        if (isset($_GET ['userID'])) {
            Yii::app()->session ['deleteStuID'] = $_GET ['userID'];
        } else if (isset($_POST ['checkbox'])) {
            Yii::app()->session ['deleteStuBox'] = $_POST ['checkbox'];
        }
        return $this->render('confirmPass');
    }

    public function actionRevokeStu() {
        $rows = 0;
        if (isset($_GET ['userID'])) {
            $userID = $_GET ['userID'];
            $rows = Student::model()->updateAll(array(
                'is_delete' => '0'
                    ), 'userID=:userID', array(
                ':userID' => $userID
            ));
        } else if (isset($_POST ['checkbox'])) {
            $ids = $_POST ['checkbox'];
            $condition = '';
            foreach ($ids as $value) {
                $condition = $condition . "'$value',";
            }
            $condition = $condition . "''";
            $rows = Student::model()->updateAll(array(
                'is_delete' => '0'
                    ), "userID in ($condition)");
        }
         if(isset($_GET ['page'])){
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        }else {
            Yii::app()->session ['lastPage'] = 1;
        }
         Yii::app()->session ['lastUrl'] = "recyclestu";
        $sql = "SELECT * FROM student WHERE is_delete = '1'";
        $array_stuLst = Tool::pager($sql,10);
        $pages = $array_stuLst['pages'];
        
        $stuLst = Student::model()->findAll("is_delete = '1'");
        $this->render('recycleStu', array(
            'pages' => $pages,
            'stuLst' => $stuLst,
            'rows' => $rows
        ));
    }

    public function actionChangeLog() {
        $sql = "SELECT changeLog FROM course WHERE courseID=" . $_GET ['courseID'];
        $result = Yii::app()->db->createCommand($sql)->query();
        $log = $result->read() ['changeLog'];
        $this->render('changeLog', array(
            'log' => $log,
            'source' => $_GET ['source'],
            'teacher' => $this->teaInClass()
        ));
    }

    public function actionRecycleStu() {
        if(isset($_GET ['page'])){
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        }else {
            Yii::app()->session ['lastPage'] = 1;
        }
         Yii::app()->session ['lastUrl'] = "recyclestu";
        $sql = "SELECT * FROM student WHERE is_delete = '1'";
        $array_stuLst = Tool::pager($sql,10);
        $stuLst = $array_stuLst['list'];
        $pages = $array_stuLst['pages'];
        $this->render('recycleStu', array(
            'stuLst' => $stuLst,
            'pages' => $pages
        ));
    }

    public function actionStuLst() {
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        Yii::app()->session ['lastUrl'] = "stuLst";
        $result = Student::model()->getStuLst("", "");
        $stuLst = $result ['stuLst'];
        $pages = $result ['pages'];

        $this->render('stuLst', array(
            'stuLst' => $stuLst,
            'pages' => $pages
        ));
    }

    public function actionSearchStu() {
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        Yii::app()->session ['lastUrl'] = "searchStu";
        if (isset($_POST ['type'])) {
            $type = $_POST ['type'];
            $value = $_POST ['value'];
            if ($type == 'classID') {
                $className = $value;
                $sqlClass = TbClass::model()->find("className = '$className'");
                if(empty($sqlClass)){
                    $value=-1;
                }else{
                $value = $sqlClass['classID'];
                }
            }
            Yii::app()->session ['searchStuType'] = $type;
            Yii::app()->session ['searchStuValue'] = $value;
        } else {
            $type = Yii::app()->session ['searchStuType'];
            $value = Yii::app()->session ['searchStuValue'];
        }
        $result = Student::model()->getStuLst($type, $value);
        $stuLst = $result ['stuLst'];
        $pages = $result ['pages'];
        $this->render('searchStu', array(
            'stuLst' => $stuLst,
            'pages' => $pages
        ));
    }

    public function actionAddStu() {
        $result = 'no';

        if (isset($_POST ['userID']) && isset($_POST['sex'])) {
            $classID = $_POST ['classID'];
            $result = Student::model()->insertStu($_POST ['userID'], $_POST ['userName'], $_POST ['sex'], $_POST ['age'], '000', $_POST ['mail_address'], $_POST ['phone_number'], $classID);
//            $num=TbClass::model()->getStuNums($classID);
            
        }
        
        
        
        $classAll = TbClass::model()->findAll("");
        $userAll = Student::model()->findAll();
        $this->render('addStu', [
           
            'classAll' => $classAll,
            'userAll' => $userAll,
            'result' => $result
        ]);
    }
    public function actionGetNum(){
        $stuNumber=Tool::getStudentLimitNumber();
        $num=0;
        if(isset($_GET['classID'])){
            $classID=$_GET['classID'];
        $num=TbClass::model()->getStuNums($classID);
        }
        if($num>=$stuNumber)
            echo "error";
        else
            echo "success";
    }

    public function actionExlAddStu() {
        $studentNumber=Tool::getStudentLimitNumber();
        if (!empty($_FILES ['file'] ['name'])) {
            $tmp_file = $_FILES ['file'] ['tmp_name'];
            $file_types = explode(".", $_FILES ['file'] ['type']);
            $file_type = $file_types [count($file_types) - 1];

            // 判别是不是excel文件
            $file = $_FILES['file'];
            if (strtolower($file_type) != "sheet" && strtolower($file_type) != "ms-excel") {
                $result = '不是Excel文件';
                $this->render('exlAddStu', ['result' => $result]);
            } else if(Tool::detectUploadFileMIME($file)){
                // 解析文件并存入数据库逻辑
                /* 设置上传路径 */
                $savePath = dirname(Yii::app()->BasePath) . '\\public\\upload\\excel\\';
                /* 以时间来命名上传的文件 */
                $str = date('Ymdhis');
                $file_name = "Stu" . $str . ".xls";
                if (!copy($tmp_file, $savePath . $file_name)) {
                    $result = '上传失败';
                    $this->render('exlAddStu', ['result' => $result]);
                } else {
                    $res = Tool::excelreadToArray($savePath . $file_name, $file_type);
                    //判断导入逻辑 分离出导入成功array_success和导入失败array_fail
                    unlink($savePath . $file_name);
                    $array_fail = array();
                    $array_success = array();
                    $flag = 0;
                    $coun=0;
                    foreach ($res as $k => $v) {
                        // 判断第一行表格头内容
                        if ($k == 1) {
                             if (isset($v [0])) {
                                if ($v [0] != "学号") {
                                    $result = "表格A列名应为“学号”！";
                                    $flag = 1;
                                    $this->render('exlAddStu', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少A列“学号”";
                                $flag = 1;
                                $this->render('exlAddStu', ['result' => $result]);
                                break;
                            }
                            if (isset($v [1])) {
                                if ($v [1] != "姓名") {
                                    $result = "表格B列名应为“姓名”！";
                                    $flag = 1;
                                    $this->render('exlAddStu', ['result' => $result]);
                                    break;
                                        }
                            } else {
                                $flag = 1;
                                $result = "表格缺少B列“姓名”";
                                $this->render('exlAddStu', ['result' => $result]);
                                break;
                            }
                            if (isset($v [2])) {
                                if ($v [2] != "性别") {
                                    $result = "表格C列名应为“性别”！";
                                    $flag = 1;
                                    $this->render('exlAddStu', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少C列“性别”";
                                $flag = 1;
                                $this->render('exlAddStu', ['result' => $result]);
                                break;
                            }
                            if (isset($v [3])) {
                                if ($v [3] != "年龄") {
                                    $result = "表格D列名应为“年龄”！";
                                    $flag = 1;
                                    $this->render('exlAddStu', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少D列“年龄”";
                                $flag = 1;
                                $this->render('exlAddStu', ['result' => $result]);
                                break;
                            }
                            if (isset($v [4])) {
                                if ($v [4] != "班级") {
                                    $result = "表格E列名应为“班级”！";
                                    $flag = 1;
                                    $this->render('exlAddStu', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少E列“班级”";
                                $flag = 1;
                                $this->render('exlAddStu', ['result' => $result]);
                                break;
                            }
                            if (isset($v [5])) {
                                if ($v [5] != "联系邮箱") {
                                    $result = "表格F列名应为“联系邮箱”！";
                                    $flag = 1;
                                    $this->render('exlAddStu', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少F列“联系邮箱”";
                                $flag = 1;
                                $this->render('exlAddStu', ['result' => $result]);
                                break;
                            }
                            if (isset($v [6])) {
                                if ($v [6] != "联系电话") {
                                    $result = "表格G列名应为“联系电话”！";
                                    $flag = 1;
                                    $this->render('exlAddStu', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少G列“联系电话”";
                                $flag = 1;
                                $this->render('exlAddStu', ['result' => $result]);
                                break;
                            }
                                }
                        //判断内容逻辑
                        if ($k > 1) {
                            $array_success = array();
                            $data ['uid'] = $v [0];
                            $data ['userName'] = $v [1];
                            $data ['sex'] = $v [2];
                            $data ['age'] = $v[3];
                            $data ['className'] = $v[4];
                            $data ['mail_address'] = $v[5];
                            $data ['phone_number'] = $v[6];

                            if ($data ['uid'] === "" || ctype_space($data ['uid'])) {
                                $result = "学号不能为空";
                                $fixed = "需手动添加";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                            } else if (Tool::excelreadUserID($data ['uid'])) {
                                $result = "学号已存在！";
                                $fixed = "需手动添加";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                            } else if (!Tool::checkID($data ['uid'])) {
                                $result = "学号必须由字母和数字组成！";
                                $fixed = "需手动添加";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                            } else if ($data['sex'] === "") {
                                $result = "性别不能为空";
                                $fixed = "需手动添加";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                            } else if ($data['sex'] != "男" && $data['sex'] != "女") {
                                $result = "性别输入有误！";
                                $fixed = "需手动添加";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                            } else if ($data ['userName'] === "" || ctype_space($data ['userName'])) {
                                $result = "姓名不能为空";
                                $fixed = "需手动添加";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                            } else if (!Tool::excelreadClass($data ['className'])) {
                                $result = "班级不存在";
                                $fixed = "班级信息已置空";
                                $data['className'] = "";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                                array_push($array_success, $data);
                            }else if((TbClass::model()->getStuNumsByClassName($data ['className']))>=$studentNumber){
                                $result = "班级人数超过".$studentNumber."人！";
                                error_log($result);
                                $fixed = "请重新分班";
                                //$data['className'] = "";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                                //array_push($array_success, $data);
                            } else if (!Tool::checkMailAddress($data ['mail_address'])) {
                                $result = "邮箱格式不正确";
                                $fixed = "邮箱信息已置空";
                                $data['mail_address'] = "";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                                array_push($array_success, $data);
                            } else {
                                array_push($array_success, $data);
                            }
                            
                            //
                            $count_success = Tool::excelreadToDatabase($array_success);
                            $coun+=$count_success;
                        }
                    }
                    if ($flag === 0) {
                        //$count_success = Tool::excelreadToDatabase($array_success);
                        $count_fail = $k - $coun - 1;
                        $this->render('exlAddStu', ['result' => $coun, 'count_fail' => $count_fail, 'array_fail' => $array_fail]);
                    }
                }
            }else
              {
                $result="检测到您上传的Excel文件存在异常，请重新编辑并上传！";
                $this->render('exlAddStu',['result'=>$result]);
               }
        } else {
            $this->render('exlAddStu');
        }
    }

    public function actionExlAddTea() {
        if (!empty($_FILES ['file'] ['name'])) {
            $tmp_file = $_FILES ['file'] ['tmp_name'];
            $file_types = explode(".", $_FILES ['file'] ['type']);
            $file_type = $file_types [count($file_types) - 1];

            // 判别是不是excel文件
            $file = $_FILES['file'];
            if (strtolower($file_type) != "sheet" && strtolower($file_type) != "ms-excel") {
                $result = '不是Excel文件';
                $this->render('exlAddStu', ['result' => $result]);
            } else if(Tool::detectUploadFileMIME($file)){
                // 解析文件并存入数据库逻辑
                /* 设置上传路径 */
                $savePath = dirname(Yii::app()->BasePath) . '\\public\\upload\\excel\\';
                /* 以时间来命名上传的文件 */
                $str = date('Ymdhis');
                $file_name = "Stu" . $str . ".xls";
                if (!copy($tmp_file, $savePath . $file_name)) {
                    $result = '上传失败';
                    $this->render('exlAddStu', ['result' => $result]);
                } else {
                    $res = Tool::excelreadToArray($savePath . $file_name, $file_type);
                    //判断导入逻辑 分离出导入成功array_success和导入失败array_fail
                    unlink($savePath . $file_name);
                    $array_fail = array();
                    $array_success = array();
                    $flag = 0;
                    foreach ($res as $k => $v) {
                        // 判断第一行表格头内容
                        if ($k == 1) {
                            if (isset($v [0])) {
                                if ($v [0] != "工号") {
                                    $result = "表格A列名应为“工号”！";
                                    $flag = 1;
                                    $this->render('exlAddTea', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少A列“工号”";
                                $flag = 1;
                                $this->render('exlAddTea', ['result' => $result]);
                                break;
                            }
                            if (isset($v [1])) {
                                if ($v [1] != "姓名") {
                                    $result = "表格B列名应为“姓名”！";
                                    $flag = 1;
                                    $this->render('exlAddTea', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $flag = 1;
                                $result = "表格缺少B列“姓名”";
                                $this->render('exlAddTea', ['result' => $result]);
                                break;
                            }
                            if (isset($v [2])) {
                                if ($v [2] != "性别") {
                                    $result = "表格C列名应为“性别”！";
                                    $flag = 1;
                                    $this->render('exlAddTea', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少C列“性别”";
                                $flag = 1;
                                $this->render('exlAddTea', ['result' => $result]);
                                break;
                            }
                            if (isset($v [3])) {
                                if ($v [3] != "年龄") {
                                    $result = "表格D列名应为“年龄”！";
                                    $flag = 1;
                                    $this->render('exlAddTea', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少D列“年龄”";
                                $flag = 1;
                                $this->render('exlAddTea', ['result' => $result]);
                                break;
                            }
                            if (isset($v [4])) {
                                if ($v [4] != "联系邮箱") {
                                    $result = "表格E列名应为“联系邮箱”！";
                                    $flag = 1;
                                    $this->render('exlAddTea', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少E列“联系邮箱”";
                                $flag = 1;
                                $this->render('exlAddTea', ['result' => $result]);
                                break;
                            }
                            if (isset($v [5])) {
                                if ($v [5] != "联系电话") {
                                    $result = "表格F列名应为“联系电话”！";
                                    $flag = 1;
                                    $this->render('exlAddTea', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少F列“联系电话”";
                                $flag = 1;
                                $this->render('exlAddTea', ['result' => $result]);
                                break;
                            }
                            if (isset($v [6])) {
                                if ($v [6] != "部门") {
                                    $result = "表格G列名应为“部门”！";
                                    $flag = 1;
                                    $this->render('exlAddTea', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少G列“所属部门”";
                                $flag = 1;
                                $this->render('exlAddTea', ['result' => $result]);
                                break;
                            }
                            if (isset($v [7])) {
                                if ($v [7] != "院校") {
                                    $result = "表格H列名应为“院校”！";
                                    $flag = 1;
                                    $this->render('exlAddTea', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少H列“院校”";
                                $flag = 1;
                                $this->render('exlAddTea', ['result' => $result]);
                                break;
                            }
                        }
                        //判断内容逻辑
                        if ($k > 1) {
                            $data ['uid'] = $v [0];
                            $data ['userName'] = $v [1];
                            $data ['sex'] = $v [2];
                            $data ['age'] = $v[3];
                            $data ['mail_address'] = $v[4];
                            $data ['phone_number'] = $v[5];
                            $data ['department'] = $v[6];
                            $data ['school'] = $v[7];

                            if ($data ['uid'] === "" || ctype_space($data ['uid'])) {
                                $result = "工号不能为空";
                                $fixed = "需手动添加";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                            } else if (Tool::excelreadTeaUserID($data ['uid'])) {
                                $result = "工号已存在！";
                                $fixed = "需手动添加";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                            } else if (!Tool::checkID($data ['uid'])) {
                                $result = "工号必须由字母和数字组成！";
                                $fixed = "需手动添加";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                            } else if ($data['sex'] === "") {
                                $result = "性别不能为空";
                                $fixed = "需手动添加";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                            } else if ($data['sex'] != "男" && $data['sex'] != "女") {
                                $result = "性别输入有误！";
                                $fixed = "需手动添加";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                            } else if ($data ['userName'] === "" || ctype_space($data ['userName'])) {
                                $result = "姓名不能为空";
                                $fixed = "需手动添加";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                            } else if (!Tool::checkMailAddress($data ['mail_address'])) {
                                $result = "邮箱格式不正确";
                                $fixed = "邮箱信息已置空";
                                $data['mail_address'] = "";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                                array_push($array_success, $data);
                            } else {
                                array_push($array_success, $data);
                            }
                        }
                    }
                    if ($flag === 0) {
                        $count_success = Tool::excelreadTeaToDatabase($array_success);
                        $count_fail = $k - $count_success - 1;
                        $this->render('exlAddTea', ['result' => $count_success, 'count_fail' => $count_fail, 'array_fail' => $array_fail]);
                    }
                }
            }else
              {
                $result="检测到您上传的Excel文件存在异常，请重新编辑并上传！";
                $this->render('exlAddTea',['result'=>$result]);
               }
        } else {
            $this->render('exlAddTea');
        }
    }

    public function actionInfoStu() {
        $ID = $_GET ['id'];
        $student = Student::model()->find("userID = '$ID'");
        if (Yii::app()->session ['lastUrl'] == "infoClass") {
            $this->render('infoStu', array(
                'id' => $_GET ['id'],
                'name' => $_GET ['name'],
                'class' => $_GET ['classID'],
                'sex' => $student['sex'],
                'age' => $student['age'],
                'password' => $student['password'],
                'mail_address' => $student['mail_address'],
                'phone_number' => $student['phone_number']
            ));
        } else if (isset($_GET ['flag'])) {
            $this->render('infoStu', array(
                'id' => $_GET ['id'],
                'name' => $_GET ['name'],
                'class' => $_GET ['class'],
                'sex' => $student['sex'],
                'age' => $student['age'],
                'password' => $student['password'],
                'mail_address' => $student['mail_address'],
                'phone_number' => $student['phone_number'],
                'flag' => $_GET ['flag']
            ));
        } else {
            $this->render('infoStu', array(
                'id' => $_GET ['id'],
                'name' => $_GET ['name'],
                'class' => $_GET ['class'],
                'sex' => $student['sex'],
                'age' => $student['age'],
                'password' => $student['password'],
                'mail_address' => $student['mail_address'],
                'phone_number' => $student['phone_number']
            ));
        }
    }

    public function actionDeleteStuSearch() {
        $userID = $_GET ['id'];
        $thisStu = new Student ();
        $thisStu = $thisStu->find("userID = '$userID'");
        $thisStu->is_delete = '1';
        $thisStu->update();
        $type = Yii::app()->session ['searchStuType'];
        $value = Yii::app()->session ['searchStuValue'];
        $result = Student::model()->getStuLst($type, $value);
        $stuLst = $result ['stuLst'];
        $pages = $result ['pages'];
        $this->render('searchStu', array(
            'stuLst' => $stuLst,
            'pages' => $pages
        ));
    }

    public function actionDeleteStu() {
        if (isset($_GET ['id'])) {
            $userID = $_GET ['id'];
            $thisStu = new Student ();
            $thisStu = $thisStu->find("userID = '$userID'");
            $thisStu->is_delete = '1';
//            $thisStu->classID='0';
            $thisStu->update();
            $result = Student::model()->getStuLst("", "");
            $stuLst = $result ['stuLst'];
            $pages = $result ['pages'];
            $this->render('stuLst', array(
                'stuLst' => $stuLst,
                'pages' => $pages
            ));
        }
        if (isset($_POST['checkbox'])) {
            $userIDlist = $_POST['checkbox'];
            foreach ($userIDlist as $v) {
                $thisStu = new Student ();
                $thisStu = $thisStu->find("userID = '$v'");
                $thisStu->is_delete = '1';
                $thisStu->update();
            }
            $result = Student::model()->getStuLst("", "");
            $stuLst = $result ['stuLst'];
            $pages = $result ['pages'];
            $this->render('stuLst', array(
                'stuLst' => $stuLst,
                'pages' => $pages
            ));
        }
    }

    public function actionDeleteStuDontHaveClass() {
        if(isset($_GET['id'])){
        $userID = $_GET ['id'];
        $thisStu = new Student ();
        $thisStu = $thisStu->find("userID = '$userID'");
        $thisStu->is_delete = '1';
        $thisStu->update();       
        }
        
         if (isset($_POST['checkbox'])) {
            $userIDlist = $_POST['checkbox'];
            foreach ($userIDlist as $v) {
        $thisStu = new Student ();
        $thisStu = $thisStu->find("userID = '$v'");
        $thisStu->is_delete = '1';
        $thisStu->update();
            }
        }
        Yii::app()->session ['lastUrl'] = "stuDontHaveClass";
        $result = Student::model()->getStuLst("classID", 0);
        $this->render("stuDontHaveClass", [
            "stuLst" => $result ["stuLst"],
            "pages" => $result ['pages']
        ]);
    }

    public function actionResetPass() {
        $classAll = TbClass::model()->findAll();
        $userAll = Student::model()->findAll();
        $sqlUserID = $_GET['id'];
        $sqlStudentInfo = Student::model()->find("userID = '$sqlUserID'");

        $userID = $_GET ['id'];
        $thisStu = new Student ();
        $thisStu = $thisStu->find("userID = '$userID'");
        $thisStu->password = md5('000');

        $thisStu->update();
        $classAll = TbClass::model()->findAll();
        $userAll = Student::model()->findAll();
        $sqlStudentInfo = Student::model()->find("userID = '$userID'");
        if (isset($_GET ['flag'])) {
            $this->render('editStu', array(
                'userID' => $_GET ['id'],
                'userName' => $sqlStudentInfo['userName'],
                'classID' => $sqlStudentInfo['classID'],
                'classAll' => $classAll,
                'userAll' => $userAll,
                'sex' => $sqlStudentInfo['sex'],
                'age' => $sqlStudentInfo['age'],
                'phone_number' => $sqlStudentInfo['phone_number'],
                'mail_address' => $sqlStudentInfo['mail_address'],
                'result' => '密码重置成功！',
                'flag' => 'search'
            ));
        } else {
            $this->render('editStu', array(
                'userID' => $_GET ['id'],
                'userName' => $sqlStudentInfo['userName'],
                'classID' => $sqlStudentInfo['classID'],
                'classAll' => $classAll,
                'userAll' => $userAll,
                'sex' => $sqlStudentInfo['sex'],
                'age' => $sqlStudentInfo['age'],
                'phone_number' => $sqlStudentInfo['phone_number'],
                'mail_address' => $sqlStudentInfo['mail_address'],
                'result' => '密码重置成功！'
            ));
        }
    }

    public function actionEditStuInfo() {
        $userID = $_GET ['id'];
        $thisStu = new Student ();
        $thisStu = $thisStu->find("userID = '$userID'");
        $thisStu->userID = $_POST ['userID'];
        $thisStu->userName = $_POST ['userName'];
        if (isset($_POST ['sex'])) {
            $thisStu->sex = $_POST ['sex'];
        } else {
            $thisStu->sex = "";
        }
        $thisStu->age = $_POST ['age'];
        $thisStu->mail_address = $_POST ['mail_address'];
        $thisStu->phone_number = $_POST ['phone_number'];
        $thisStu->classID = $_POST['classID'];
        $thisStu->update();
        $classAll = TbClass::model()->findAll();
        $userAll = Student::model()->findAll();
        $sqlUserID = $thisStu->userID;
        $sqlStudentInfo = Student::model()->find("userID = '$sqlUserID'");
        if (isset($_GET ['flag'])) {
            $this->render('editStu', array(
                'userID' => $thisStu->userID,
                'userName' => $thisStu->userName,
                'classID' => $thisStu->classID,
                'classAll' => $classAll,
                'userAll' => $userAll,
                'sex' => $sqlStudentInfo['sex'],
                'age' => $sqlStudentInfo['age'],
                'phone_number' => $sqlStudentInfo['phone_number'],
                'mail_address' => $sqlStudentInfo['mail_address'],
                'result' => '信息修改成功！',
                'flag' => $_GET ['flag']
            ));
        } else {
            $this->render('editStu', array(
                'userID' => $thisStu->userID,
                'userName' => $thisStu->userName,
                'classID' => $thisStu->classID,
                'classAll' => $classAll,
                'userAll' => $userAll,
                'sex' => $sqlStudentInfo['sex'],
                'age' => $sqlStudentInfo['age'],
                'phone_number' => $sqlStudentInfo['phone_number'],
                'mail_address' => $sqlStudentInfo['mail_address'],
                'result' => '信息修改成功！'
            ));
        }
    }

    public function actionEditStu() {
        $classAll = TbClass::model()->findAll();
        $userAll = Student::model()->findAll();
        $sqlUserID = $_GET['id'];
        $sqlStudentInfo = Student::model()->find("userID = '$sqlUserID'");
        if (isset($_GET ['flag'])) {
            $this->render('editStu', array(
                'userID' => $_GET ['id'],
                'userName' => $_GET ['name'],
                'classID' => $_GET ['class'],
                'classAll' => $classAll,
                'userAll' => $userAll,
                'sex' => $sqlStudentInfo['sex'],
                'age' => $sqlStudentInfo['age'],
                'phone_number' => $sqlStudentInfo['phone_number'],
                'mail_address' => $sqlStudentInfo['mail_address'],
                'password' => $sqlStudentInfo['password'],
                'flag' => 'search'
            ));
        } else {
            $this->render('editStu', array(
                'userID' => $_GET ['id'],
                'userName' => $_GET ['name'],
                'classID' => $_GET ['class'],
                'classAll' => $classAll,
                'userAll' => $userAll,
                'sex' => $sqlStudentInfo['sex'],
                'age' => $sqlStudentInfo['age'],
                'phone_number' => $sqlStudentInfo['phone_number'],
                'mail_address' => $sqlStudentInfo['mail_address'],
                'password' => $sqlStudentInfo['password'],
            ));
        }
    }

    // 是否存在指定班级
    public function exClass($classID) {
        $sql = "select * from tb_class where classID = '$classID'";
        $course = Yii::app()->db->createCommand($sql)->query();
        if (empty($course->read()))
            return FALSE;
        else
            return TRUE;
    }

    public function actionTeaLst() {
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        Yii::app()->session ['lastUrl'] = "=teaLst";
        $result = Teacher::model()->getTeaLst("", "");
        $teaLst = $result ['teaLst'];
        $pages = $result ['pages'];
        $this->render('teaLst', array(
            'teaLst' => $teaLst,
            'pages' => $pages
        ));
    }

    public function actionSearchTea() {
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        Yii::app()->session ['lastUrl'] = "=searchTea";
        if (isset($_POST ['type'])) {
            $type = $_POST ['type'];
            $value = $_POST ['value'];
            Yii::app()->session ['searchTeaType'] = $type;
            Yii::app()->session ['searchTeaValue'] = $value;
        } else {
            $type = Yii::app()->session ['searchTeaType'];
            $value = Yii::app()->session ['searchTeaValue'];
        }
        if($type==="userID" && $value==="a01" || $value==="A01"){
            $value="";
        }
        if($type==="userName" && $value=="亚伟速录"){
            $value="";
        }
        if($type==="department" && $value=="模板"){
            $value="";
        }
        $result = Teacher::model()->getTeaLst($type, $value);
        $teaLst = $result ['teaLst'];
        $pages = $result ['pages'];
        $this->render('searchTea', array(
            'teaLst' => $teaLst,
            'pages' => $pages
        ));
    }

    public function actionInfoTea() {
        $ID = $_GET ['id'];
        $teacher = Teacher::model()->find("userID = '$ID'");
        if (Yii::app()->session ['lastUrl'] == "infoClass") {
            $this->render('infoTea', array(
                'id' => $_GET ['id'],
                'name' => $teacher ['userName'],
                'department' => $teacher ['department'],
                'school' => $teacher ['school'],
                'sex' => $teacher['sex'],
                'age' => $teacher['age'],
                'password' => $teacher['password'],
                'mail_address' => $teacher['mail_address'],
                'phone_number' => $teacher['phone_number']
            ));
        } else if (isset($_GET ['flag'])) {
            $this->render('infoTea', array(
                'id' => $_GET ['id'],
                'name' => $teacher ['userName'],
                'department' => $teacher ['department'],
                'school' => $teacher ['school'],
                'sex' => $teacher['sex'],
                'age' => $teacher['age'],
                'password' => $teacher['password'],
                'mail_address' => $teacher['mail_address'],
                'phone_number' => $teacher['phone_number'],
                'flag' => $_GET ['flag']
            ));
        } else {
            $this->render('infoTea', array(
                'id' => $_GET ['id'],
                'name' => $_GET ['name'],
                'department' => $teacher['department'],
                'school' => $teacher ['school'],
                'sex' => $teacher['sex'],
                'age' => $teacher['age'],
                'password' => $teacher['password'],
                'mail_address' => $teacher['mail_address'],
                'phone_number' => $teacher['phone_number']
            ));
        }
    }

    public function actionAddTea() {
        $result = 'no';
        if (isset($_POST ['userID']) && isset($_POST['sex'])) {
            $result = Teacher::model()->insertTea($_POST ['userID'], $_POST ['userName'], $_POST ['sex'], $_POST ['age'], '000', $_POST ['phone_number'], $_POST ['mail_address'], $_POST['department'],$_POST['school']);
        }
        $userAll = Teacher::model()->findAll();
        $this->render('addTea', [
            'userAll' => $userAll,
            'result' => $result
        ]);
    }

    public function actionResetTeaPass() {

        $userID = $_GET ['id'];
        $thisTea = new Teacher ();
        $thisTea = $thisTea->find("userID = '$userID'");
        $thisTea->password = md5('000');

        $thisTea->update();
        $userAll = Teacher::model()->findAll();
        $sqlTeaInof = Teacher::model()->find("userID = '$userID'");
        if (isset($_GET ['flag'])) {
            $this->render('editTea', array(
                'userID' => $_GET ['id'],
                'userName' => $sqlTeaInof ['userName'],
                'department' => $sqlTeaInof['department'],
                'school' => $sqlTeaInof['school'],
                'userAll' => $userAll,
                'sex' => $sqlTeaInof['sex'],
                'age' => $sqlTeaInof['age'],
                'phone_number' => $sqlTeaInof['phone_number'],
                'mail_address' => $sqlTeaInof['mail_address'],
                'result' => '密码重置成功！',
                'flag' => 'search'
            ));
        } else {
            $this->render('editTea', array(
                'userID' => $_GET ['id'],
                'userName' => $sqlTeaInof ['userName'],
                'department' => $sqlTeaInof['department'],
                'school' => $sqlTeaInof['school'],
                'userAll' => $userAll,
                'sex' => $sqlTeaInof['sex'],
                'age' => $sqlTeaInof['age'],
                'phone_number' => $sqlTeaInof['phone_number'],
                'mail_address' => $sqlTeaInof['mail_address'],
                'result' => '密码重置成功！',
            ));
        }
    }

    public function actionEditTeaInfo() {
        $userID = $_GET ['id'];
        $thisTea = new Teacher ();
        $thisTea = $thisTea->find("userID = '$userID'");
        $thisTea->userID = $_POST ['userID'];
        $thisTea->userName = $_POST ['userName'];
        if (isset($_POST ['sex'])) {
            $thisTea->sex = $_POST ['sex'];
        } else {
            $thisTea->sex = "";
        }
        $thisTea->age = $_POST ['age'];
        $thisTea->mail_address = $_POST ['mail_address'];
        $thisTea->phone_number = $_POST ['phone_number'];
        $thisTea->department = $_POST['department'];
        $thisTea->school = $_POST['school'];
        $thisTea->update();
        $userAll = Teacher::model()->findAll();
        $sqlUserID = $thisTea->userID;
        $sqlTeaInof = Teacher::model()->find("userID = '$sqlUserID'");
        if (isset($_GET ['flag'])) {
            $this->render('editTea', array(
                'userID' => $thisTea->userID,
                'userName' => $thisTea->userName,
                'department' => $thisTea->department,
                'school' => $sqlTeaInof['school'],
                'userAll' => $userAll,
                'sex' => $sqlTeaInof['sex'],
                'age' => $sqlTeaInof['age'],
                'phone_number' => $sqlTeaInof['phone_number'],
                'mail_address' => $sqlTeaInof['mail_address'],
                'result' => '信息修改成功！',
                'flag' => $_GET ['flag']
            ));
        } else {
            $this->render('editTea', array(
                'userID' => $thisTea->userID,
                'userName' => $thisTea->userName,
                'department' => $thisTea->department,
                'school' => $sqlTeaInof['school'],
                'userAll' => $userAll,
                'sex' => $sqlTeaInof['sex'],
                'age' => $sqlTeaInof['age'],
                'phone_number' => $sqlTeaInof['phone_number'],
                'mail_address' => $sqlTeaInof['mail_address'],
                'result' => '信息修改成功！'
            ));
        }
    }

    public function actionEditTea() {
        $userAll = Teacher::model()->findAll();
        $sqlUserID = $_GET['id'];
        $sqlTeaInof = Teacher::model()->find("userID = '$sqlUserID'");
        if (isset($_GET ['flag'])) {
            $this->render('editTea', array(
                'userID' => $_GET ['id'],
                'userName' => $_GET ['name'],
                'department' => $sqlTeaInof['department'],
                'school' => $sqlTeaInof['school'],
                'userAll' => $userAll,
                'sex' => $sqlTeaInof['sex'],
                'age' => $sqlTeaInof['age'],
                'phone_number' => $sqlTeaInof['phone_number'],
                'mail_address' => $sqlTeaInof['mail_address'],
                'flag' => 'search'
            ));
        } else {
            $this->render('editTea', array(
                'userID' => $_GET ['id'],
                'userName' => $_GET ['name'],
                'department' => $sqlTeaInof['department'],
                'school' => $sqlTeaInof['school'],
                'userAll' => $userAll,
                'sex' => $sqlTeaInof['sex'],
                'age' => $sqlTeaInof['age'],
                'phone_number' => $sqlTeaInof['phone_number'],
                'mail_address' => $sqlTeaInof['mail_address'],
            ));
        }
    }

    public function actionDeleteTeaSearch() {
        $userID = $_GET ['id'];
        $thisTea = new Teacher ();
        $thisTea = $thisTea->find("userID = '$userID'");
        $thisTea->is_delete = '1';
        $thisTea->update();
        $type = Yii::app()->session ['searchTeaType'];
        $value = Yii::app()->session ['searchTeaValue'];
        $result = Teacher::model()->getTeaLst($type, $value);
        $teaLst = $result ['teaLst'];
        $pages = $result ['pages'];
        $this->render('searchTea', array(
            'teaLst' => $teaLst,
            'pages' => $pages
        ));
    }

    public function actionDeleteTea() {
        if (isset($_GET ['id'])) {
            $userID = $_GET ['id'];
            $thisTea = new Teacher ();
            $thisTea = $thisTea->find("userID = '$userID'");
            $thisTea->is_delete = '1';
            $thisTea->update();
            $result = Teacher::model()->getTeaLst("", "");
            $teaLst = $result ['teaLst'];
            $pages = $result ['pages'];
            $this->render('teaLst', array(
                'teaLst' => $teaLst,
                'pages' => $pages
            ));
        }
        if (isset($_POST['checkbox'])) {
            $userIDlist = $_POST['checkbox'];
            foreach ($userIDlist as $v) {
                $thisTea = new Teacher ();
                $thisTea = $thisTea->find("userID = '$v'");
                $thisTea->is_delete = '1';
                $thisTea->update();
            }
            $result = Teacher::model()->getTeaLst("", "");
            $teaLst = $result ['teaLst'];
            $pages = $result ['pages'];
            $this->render('teaLst', array(
                'teaLst' => $teaLst,
                'pages' => $pages
            ));
        }
    }

    public function actionHardDeleteTea() {
        if(isset($_POST ['password'])){
            $pass = md5($_POST ['password']);
            $id = Yii::app()->session ['userid_now'];
            $admin = Admin::model()->findByPK($id);
            if ($admin->password !== $pass) {
                return $this->render('confirmTeaPass', [
                        'wrong' => '密码错误，请重新输入。'
                ]);
            }
        }
        $rows = 0;
        if (isset(Yii::app()->session ['deleteTeaID'])) {
            $userID = Yii::app()->session ['deleteTeaID'];
            unset(Yii::app()->session ['deleteTeaID']);
            TeacherClass::model()->deleteAll("teacherID = '$userID'");
            TeacherSign::model()->deleteAll("teacherID='$userID'");
            $rows = Teacher::model()->deleteByPK("$userID");
        } else if (isset(Yii::app()->session ['deleteTeaBox'])) {
            $ids = Yii::app()->session ['deleteTeaBox'];
            unset(Yii::app()->session ['deleteTeaBox']);
            $condition = '';
            foreach ($ids as $value) {
                $condition = $condition . "'$value',";
            }
            $condition = $condition . "''";
            TeacherClass::model()->deleteAll("teacherID in ($condition)");
            $rows = Teacher::model()->deleteAll("userID in ($condition)");
        }
         if(isset($_GET ['page'])){
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        }else {
            Yii::app()->session ['lastPage'] = 1;
        }
         Yii::app()->session ['lastUrl'] = "recyclestu";
        $sql = "SELECT * FROM teacher WHERE is_delete = '1'";
        $array_stuLst = Tool::pager($sql,10);
        $teaLst = $array_stuLst['list'];
        $pages = $array_stuLst['pages'];
//        $teaLst = Teacher::model()->findAll("is_delete = '1'");
        $this->render('recycleTea', array(
            'pages' => $pages,
            'teaLst' => $teaLst,
            'rows' => $rows
        ));
    }

    public function actionConfirmTeaPass() {
        if (isset($_GET ['userID'])) {
            Yii::app()->session ['deleteTeaID'] = $_GET ['userID'];
            TeacherClass::model()->deleteAll("teacherID='userID'");
            TeacherSign::model()->deleteAll("teacherID='userID'");
        } else if (isset($_POST ['checkbox'])) {
            Yii::app()->session ['deleteTeaBox'] = $_POST ['checkbox'];
        }
        return $this->render('confirmTeaPass');
    }

    public function actionRevokeTea() {
        $rows = 0;
        if (isset($_GET ['userID'])) {
            $userID = $_GET ['userID'];
            $rows = Teacher::model()->updateAll(array(
                'is_delete' => '0'
                    ), 'userID=:userID', array(
                ':userID' => $userID
            ));
        } else if (isset($_POST ['checkbox'])) {
            $ids = $_POST ['checkbox'];
            $condition = '';
            foreach ($ids as $value) {
                $condition = $condition . "'$value',";
            }
            $condition = $condition . "''";
            $rows = Teacher::model()->updateAll(array(
                'is_delete' => '0'
                    ), "userID in ($condition)");
        }
        if(isset($_GET ['page'])){
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        }else {
            Yii::app()->session ['lastPage'] = 1;
        }
         Yii::app()->session ['lastUrl'] = "recyclestu";
        $sql = "SELECT * FROM student WHERE is_delete = '1'";
        $array_stuLst = Tool::pager($sql,10);
        $pages = $array_stuLst['pages'];
        $teaLst = Teacher::model()->findAll("is_delete = '1'");
        $this->render('recycleTea', array(
            'pages'=>$pages,
            'teaLst' => $teaLst,
            'rows' => $rows
        ));
    }

    public function actionRecycleTea() {
         if(isset($_GET ['page'])){
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        }else {
            Yii::app()->session ['lastPage'] = 1;
        }
         Yii::app()->session ['lastUrl'] = "recyclestu";
        $sql = "SELECT * FROM teacher WHERE is_delete = '1'";
        $array_stuLst = Tool::pager($sql,10);
        $teaLst = $array_stuLst['list'];
        $pages = $array_stuLst['pages'];
        $this->render('recycleTea', array(
            'teaLst' => $teaLst,
            'pages' => $pages
        ));
        
    }

    public function actionClassLst() {
        //删除班级同时删除与之关联的学生，老师
        $act_result = '';
        if (isset($_GET ['flag'])) {
            if ($_GET ['flag'] == 'deleteClass') {
                $sql = "DELETE FROM tb_class WHERE classID ='" . $_GET ['ClassID'] . "'";
                //SQL删除关联学生
//                $sql_student = "UPDATE student SET classID= '0' WHERE classID= '" . $_GET ['ClassID'] . "'";
                $sql_student_all=  Student::model()->findAll("classID=?",array($_GET ['ClassID'])); 
                if(!empty($sql_student_all)){
                foreach($sql_student_all as $all_flag){
                    $sql_student_id=$all_flag['userID'];
                    Student::model()->delStuRes($sql_student_id);
                }
                }
                $sql_student = "DELETE FROM student WHERE classID ='" . $_GET ['ClassID'] . "'";
                //SQL删除关联老师
                $sql_teacher = "DELETE FROM teacher_class WHERE classID = '" . $_GET ['ClassID'] . "'";
                //SQL删除关联内容
                $sql_chat="DELETE FROM chat_lesson_1 WHERE classID = '" . $_GET ['ClassID'] . "'";
                ClassExam::model()->deleteAll("classID=?",array($_GET['ClassID'])); 
                ClassLessonSuite::model()->deleteAll("classID=?",array($_GET['ClassID']));
                $sql_class=ClassExercise::model()->findAll("classID=?",array($_GET['ClassID']));
                ScheduleClass::model()->deleteAll("classID=?",array($_GET ['ClassID']));
                ClassExercise::model()->deleteAll("classID=?",array($_GET ['ClassID'])); 
                ClassLessonSuite::model()->deleteAll("classID=?",array($_GET ['ClassID']));
                Yii::app()->db->createCommand($sql)->query();
                Yii::app()->db->createCommand($sql_teacher)->query();
                Yii::app()->db->createCommand($sql_student)->query();
                Yii::app()->db->createCommand($sql_chat)->query();
            }
            unset($_GET ['flag']);
        }

        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        // 显示结果列表并分页
        Yii::app()->session ['lastUrl'] = "classLst";
        $result = TbClass::model()->getClassLst();
        $this->render('classLst', array(
            'posts' => $result ['classLst'],
            'pages' => $result ['pages'],
            'nums' => TbClass::model()->numInClass(),
            'teacher' => TbClass::model()->teaInClass(),
            'teacherOfClass' => TbClass::model()->teaByClass()
        ));
    }
    
    public function ActionDeleteClass(){
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        
        if(isset($_GET['ClassID'])){  
                $sql = "DELETE FROM tb_class WHERE classID ='" . $_GET ['ClassID'] . "'";
                //SQL删除关联学生
//                $sql_student = "UPDATE student SET classID= '0' WHERE classID= '" . $_GET ['ClassID'] . "'";
                $sql_student_all=  Student::model()->findAll("classID=?",array($_GET ['ClassID'])); 
                if(!empty($sql_student_all)){
                foreach($sql_student_all as $all_flag){
                    $sql_student_id=$all_flag['userID'];
                    Student::model()->delStuRes($sql_student_id);
                }
                }
                $sql_student = "DELETE FROM student WHERE classID ='" . $_GET ['ClassID'] . "'";
                //SQL删除关联老师
                $sql_teacher = "DELETE FROM teacher_class WHERE classID = '" . $_GET ['ClassID'] . "'";
                $sql_lesson = "DELETE FROM lesson WHERE classID = '" . $_GET ['ClassID'] . "'";
                //SQL删除关联内容
                $sql_chat="DELETE FROM chat_lesson_1 WHERE classID = '" . $_GET ['ClassID'] . "'";
                ClassExam::model()->deleteAll("classID=?",array($_GET['ClassID'])); 
                ClassLessonSuite::model()->deleteAll("classID=?",array($_GET['ClassID']));
                $sql_class=ClassExercise::model()->findAll("classID=?",array($_GET['ClassID']));
//                if(!empty($sql_class)){
//                foreach($sql_class as $exercise_id){
//                    $sql_exam_id=ExamExercise::model()->findAll("exerciseID=?",array($exercise_id['exerciseID']));
//                    foreach($sql_exam_id as $exam_id){
//                        Exam::model()->deleteAll("examID=?",array($exam_id['examID']));
//                    }
//                    ExamExercise::model() -> deleteAll("exerciseID=?",array($exercise_id['exerciseID']));
//                    $sql_suite_id = SuiteExercise::model()->findAll("exerciseID=?",array($exercise_id['exerciseID']));
//                    foreach($sql_suite_id as $suite_id){
//                        suite::model()->deleteAll("suiteID=?",array($suite_id['suiteID']));
//                    }
//                    SuiteExercise::model()->deleteAll("exerciseID=?",array($exercise_id['exerciseID']));
//                }
//                }
                ScheduleClass::model()->deleteAll("classID=?",array($_GET ['ClassID']));
                ClassExercise::model()->deleteAll("classID=?",array($_GET ['ClassID'])); 
                ClassLessonSuite::model()->deleteAll("classID=?",array($_GET ['ClassID']));
                Yii::app()->db->createCommand($sql)->query();
                Yii::app()->db->createCommand($sql_teacher)->query();
                Yii::app()->db->createCommand($sql_student)->query();  
                Yii::app()->db->createCommand($sql_lesson)->query();  
                Yii::app()->db->createCommand($sql_chat)->query();
        }
        
         if (isset($_POST['checkbox'])) {
            $result = 1;
            $userIDlist = $_POST['checkbox'];
            foreach ($userIDlist as $v) {
                $sql = "DELETE FROM tb_class WHERE classID ='" . $v . "'";
                //SQL删除关联学生
                $sql_student_all=  Student::model()->findAll("classID=?",array($v)); 
                if(!empty($sql_student_all)){
                foreach($sql_student_all as $all_flag){
                    $sql_student_id=$all_flag['userID'];
                    Student::model()->delStuRes($sql_student_id);
                }
                }
//                $sql_student = "UPDATE student SET classID= '0' WHERE classID= '" . $v . "'";
                $sql_student = "DELETE FROM student WHERE classID ='" . $v . "'";
                //SQL删除关联老师
                $sql_teacher = "DELETE FROM teacher_class WHERE classID = '" . $v . "'";
                //SQL删除关联内容
                $sql_chat="DELETE FROM chat_lesson_1 WHERE classID = '" . $v . "'";
                ClassExam::model()->deleteAll("classID=?",array($v)); 
                ClassLessonSuite::model()->deleteAll("classID=?",array($v));
                $sql_class=ClassExercise::model()->findAll("classID=?",array($v));
                ScheduleClass::model()->deleteAll("classID=?",array($v));
                ClassExercise::model()->deleteAll("classID=?",array($v)); 
                ClassLessonSuite::model()->deleteAll("classID=?",array($v));
                Yii::app()->db->createCommand($sql)->query();
                Yii::app()->db->createCommand($sql_teacher)->query();
                Yii::app()->db->createCommand($sql_student)->query();  
            }
        }
        if (Yii::app()->session ['lastUrl'] == "searchClass") {           
            $type = Yii::app()->session ['searchType'];
            $value = Yii::app()->session ['searchValue'];
                   $ex_sq = "";
        if (isset($type)) {
            if ($type == "classID" || $type == "className") {
                $ex_sq = " WHERE " . $type . " = '" . $value . "'";
            } else if ($type == "courseName") {
                $course = Course::model()->find("courseName = ?", array($value));
                $ex_sq = " WHERE currentCourse = '" . $course->courseID . "'";
            } else if ($type == "teaName") {
                $sql = "SELECT * FROM teacher WHERE userName ='" . $value . "'";
                $an = Yii::app()->db->createCommand($sql)->query();
                $temp = $an->read();
                if (!empty($temp))
                    $teaID = $temp ['userID'];
                else
                    $teaID = - 1;
                $sql = "SELECT * FROM teacher_class WHERE teacherID ='" . $teaID . "'";
                $an = Yii::app()->db->createCommand($sql)->query();
                $temp = $an->read();
                if (!empty($temp)) {
                    $ex_sq = " WHERE ";
                    $id = $temp ['classID'];
                    $ex_sq = $ex_sq . "classID = '$id'";
                    $temp = $an->read();
                    while (!empty($temp)) {
                        $id = $temp ['classID'];
                        $ex_sq = $ex_sq . " OR classID = '$id'";
                        $temp = $an->read();
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
        $result = Yii::app()->db->createCommand($sql)->query();
        $pages = new CPagination($result->rowCount);
        $pages->pageSize = 10;
        $pages->applyLimit($criteria);
        $result = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize);
        $result->bindValue(':limit', $pages->pageSize);
        $posts = $result->query();
        $this->render('searchClass', array(
            'posts' => $posts,
            'pages' => $pages,
            'nums' => TbClass::model()->numInClass(),
            'teacher' => TbClass::model()->teaInClass(),
            'teacherOfClass' => TbClass::model()->teaByClass()
        ));
        } else {             
               $result = TbClass::model()->getClassLst();
            $this->render('classLst', array(
            'posts' => $result ['classLst'],
            'pages' => $result ['pages'],
            'nums' => TbClass::model()->numInClass(),
            'teacher' => TbClass::model()->teaInClass(),
            'teacherOfClass' => TbClass::model()->teaByClass()
        ));
    }
    }

    public function actionStuDontHaveClass() {
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        Yii::app()->session ['lastUrl'] = "stuDontHaveClass";
        $result = Student::model()->getStuLst("classID", 0);
        $this->render("stuDontHaveClass", [
            "stuLst" => $result ["stuLst"],
            "pages" => $result ['pages']
        ]);
    }

    public function actionSearchClass() {
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        Yii::app()->session ['lastUrl'] = "searchClass";
        if (isset($_POST ['which'])) {
            $type = $_POST ['which'];
            $value = $_POST ['value'];
            Yii::app()->session ['searchType'] = $type;
            Yii::app()->session ['searchValue'] = $value;
        } else {
            $type = Yii::app()->session ['searchType'];
            $value = Yii::app()->session ['searchValue'];
        }
        $ex_sq = "";
        error_log($type);
        error_log($value);
        if (isset($type)) {
            if ($type == "classID" || $type == "className") {
                if($type == "classID" && $value==="1" || $type == "className" && $value==="速录一班"){
                    $value="";
                }
                $ex_sq = " WHERE " . $type . " = '" . $value . "'";
            } else if ($type == "courseName") {
                if($value==="速录教学"){
                    $value="";
                }
                $course = Course::model()->find("courseName = ?", array($value));
                if(isset($course)){
                    $ex_sq = " WHERE currentCourse = '" . $course->courseID . "'";
                }else{
                    $ex_sq = " WHERE currentCourse = ''";
                }
            } else if ($type == "teaName") {
                if($value==="亚伟速录"){
                    $value="";
                }
                $sql = "SELECT * FROM teacher WHERE userName ='" . $value . "'";
                $an = Yii::app()->db->createCommand($sql)->query();
                $temp = $an->read();
                if (!empty($temp))
                    $teaID = $temp ['userID'];
                else
                    $teaID = - 1;
                $sql = "SELECT * FROM teacher_class WHERE teacherID ='" . $teaID . "'";
                $an = Yii::app()->db->createCommand($sql)->query();
                $temp = $an->read();
                if (!empty($temp)) {
                    $ex_sq = " WHERE ";
                    $id = $temp ['classID'];
                    $ex_sq = $ex_sq . "classID = '$id'";
                    $temp = $an->read();
                    while (!empty($temp)) {
                        $id = $temp ['classID'];
                        $ex_sq = $ex_sq . " OR classID = '$id'";
                        $temp = $an->read();
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
        $result = Yii::app()->db->createCommand($sql)->query();
        $pages = new CPagination($result->rowCount);
        $pages->pageSize = 10;
        $pages->applyLimit($criteria);
        $result = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize);
        $result->bindValue(':limit', $pages->pageSize);
        $posts = $result->query();
        $this->render('searchClass', array(
            'posts' => $posts,
            'pages' => $pages,
            'nums' => TbClass::model()->numInClass(),
            'teacher' => TbClass::model()->teaInClass(),
            'teacherOfClass' => TbClass::model()->teaByClass()
        ));
    }

    public function actionAddClass() {
        $result = 'no';
         $sql = "SELECT * FROM `tb_class` ORDER BY `tb_class`.`classID` DESC ";
        $aClass = Yii::app()->db->createCommand($sql)->query();
        if (isset($_POST ['className'])) {
            $className = $_POST ['className'];
            $classes = TbClass::model()->findAll("className = '$className'");
            if (count($classes) > 0) {
                $result = 2;
            } else {
                Yii::app()->session['insert_class']=$className;
                $classID = TbClass::model()->insertClass($_POST ['className'], $_POST ['courseID']);
                $lessons = Lesson::model()->findall('classID=? and courseID=?', array(0, $_POST ['courseID']));
                foreach ($lessons as $lesson) {
                    Lesson::model()->insertLesson($lesson['lessonName'], $lesson['courseID'], 0, $classID);
                }
                $result = 1;
            }
        }
        $this->render('addClass', [
            'result' => $result,
            'allClass' => $aClass
        ]);
    }

    public function actionInfoClass() {
        Yii::app()->session ['lastUrl'] = "infoClass";
        $act_result = "";
        $classID = $_GET ["classID"];
        $studentNumber=Tool::getStudentLimitNumber();
        // 删除某学生的班级
        if (isset($_GET ['flag'])) {
            if ($_GET ['flag'] == 'deleteStu') {
                $sql = "UPDATE student SET classID= '0' WHERE userID= '" . $_GET ['id'] . "'";
                Yii::app()->db->createCommand($sql)->query();
                $act_result = "删除成功！";
            } else if ($_GET ['flag'] == 'deleteTea') {
                TeacherSign::model()->deleteAll('teacherID=?',array($_GET ['id']));
                $sql = "DELETE FROM teacher_class WHERE teacherID = '" . $_GET ['id'] . "' AND classID = '" . $classID . "'";
                Yii::app()->db->createCommand($sql)->query();
                $act_result = "删除成功！";
            }
            unset($_GET ['flag']);
        }

        $nums=TbClass::model()->getStuNums($classID);
        
        if (isset($_GET ['action']) && isset($_POST ['checkbox'])) {
            $checkbox = $_POST ['checkbox'];
            if ($_GET ['action'] == "addStu") {
                if($nums+count($checkbox)>$studentNumber){
                $act_result = "overLimites";
                }
                else{
                    for ($i = 0; $i < count($checkbox); $i ++) {
                        if (!is_null($checkbox [$i])) {
                            $stuID = $checkbox [$i];
                            $sql = "UPDATE student SET classID= '" . $classID . "' WHERE userID= '" . $stuID . "'";
                            Yii::app()->db->createCommand($sql)->query();
                        }
                    }
                    $act_result = "添加 $i 名学生成功！";
                  }
            } else if ($_GET ['action'] == "addTea") {
                for ($i = 0; $i < count($checkbox); $i ++) {
                    if (!is_null($checkbox [$i])) {
                        $teaID = $checkbox [$i];
                        $sql = "INSERT INTO teacher_class VALUES ('" . $teaID . "','" . $classID . "','')";
                        Yii::app()->db->createCommand($sql)->query();
                    }
                }
                $act_result = "添加 $i 位老师成功！";
            }
        }

        $sql = "SELECT * FROM tb_class WHERE classID = '$classID'";
        $an = Yii::app()->db->createCommand($sql)->query();
        $class = $an->read();
        $className = $class ['className'];
        $curCourse = $class ['currentCourse'];
        $curLesson = $class ['currentLesson'];

        $sql = "SELECT * FROM student WHERE classID = '$classID' AND is_delete = 0";
        $array_stuLst = Tool::pager($sql,4);
        $stus = $array_stuLst['list'];
        
        $nums=TbClass::model()->getStuNums($classID);
        
        $pages_stu = $array_stuLst['pages'];
        
        $sql = "SELECT * FROM teacher_class WHERE classID =$classID";
        $teacherOfClass = Yii::app()->db->createCommand($sql)->query();

        $this->render('infoCLass', array(
            'studentNumber'=>$studentNumber,
            'pages_stu' =>$pages_stu,
            'classID' => $classID,
            'className' => $className,
            'curCourse' => $curCourse,
            'curLesson' => $curLesson,
            'teacher' => TbClass::model()->teaInClass(),
            'teacherOfClass' => $teacherOfClass,
            'nums' => $nums, // 学生人数
            'stus' => $stus, // 学生
            'result' => $act_result
                ), false, true);
    }
    
    //批量删除班级里的学生
    public function actionDeleteStuInClass(){
        $classID = $_GET['classID'];
        Yii::app()->session ['lastUrl'] = "infoClass";
        $studentNumber=Tool::getStudentLimitNumber();
        $act_result="";
        if (isset($_POST['checkbox'])) {
            $userIDlist = $_POST['checkbox'];
            foreach ($userIDlist as $v) {
                $sql = "UPDATE student SET classID= '0' WHERE userID= '" . $v . "'";
                Yii::app()->db->createCommand($sql)->query();
                $act_result = "删除成功！";
            }
        }
        
         $sql = "SELECT * FROM tb_class WHERE classID = '$classID'";
        $an = Yii::app()->db->createCommand($sql)->query();
        $class = $an->read();
        $className = $class ['className'];
        $curCourse = $class ['currentCourse'];
        $curLesson = $class ['currentLesson'];

        $sql = "SELECT * FROM student WHERE classID = '$classID' AND is_delete = 0";
        $array_stuLst = Tool::pager($sql,4);
        $stus = $array_stuLst['list'];
        
        $nums =TbClass::model()->getStuNums($classID);
        
        $pages_stu = $array_stuLst['pages'];
        
        $sql = "SELECT * FROM teacher_class WHERE classID =$classID";
        $teacherOfClass = Yii::app()->db->createCommand($sql)->query();

        $this->render('infoCLass', array(
            'studentNumber'=>$studentNumber,
            'pages_stu' =>$pages_stu,
            'classID' => $classID,
            'className' => $className,
            'curCourse' => $curCourse,
            'curLesson' => $curLesson,
            'teacher' => TbClass::model()->teaInClass(),
            'teacherOfClass' => $teacherOfClass,
            'nums' => $nums, // 学生人数
            'stus' => $stus, // 学生
            'result' => $act_result
                ), false, true);
        
        
    }
       
        

    public function actionAddStuClass() {
        $sql = "SELECT * FROM student WHERE classID = '0' AND is_delete = 0";
        $array_class = Tool::pager($sql, 10);
        $result = $array_class['list'];
        $pages = $array_class['pages'];
        
        $classID = $_GET['classID'];
        
        $nums = TbClass::model()->getStuNums($classID);
        
        $this->render('addStuClass', array(
            'classID' => $_GET ["classID"],
            'posts' => $result,
            'pages' =>$pages,
            'nums'=>$nums,
                )
        );
    }

    public function actionAddTeaClass() {
        $classID = $_GET ["classID"];
        $sql = "SELECT teacherID FROM teacher_class WHERE classID = '$classID'  order by teacherID ASC";
        $sqltea="SELECT * FROM teacher order by userID ASC";
        $array_tea = Tool::pager($sqltea, 10);
        $teacher = $array_tea['list'];
        $pages = $array_tea['pages'];
        $result = Yii::app()->db->createCommand($sql)->query();
        $this->render('addTeaClass', array(
            'pages'=>$pages,
            'classID' => $classID,
            'posts' => $result,
            'teachers' => $teacher,
                )
        );
    }

    public function actionLookLst() {
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        $result = LookType::model()->getLookLst("", "");
        $lookLst = $result ['lookLst'];
        $pages = $result ['pages'];
        Yii::app()->session ['lastUrl'] = "lookLst";
        $this->render('lookLst', array(
            'lookLst' => $lookLst,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall()
        ));
    }

    public function actionSearchLook() {
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        if (isset($_POST ['type'])) {
            $type = $_POST ['type'];
            $value = $_POST ['value'];
            Yii::app()->session ['searchType'] = $type;
            Yii::app()->session ['searchValue'] = $value;
        } else {
            $type = Yii::app()->session ['searchType'];
            $value = Yii::app()->session ['searchValue'];
        }
        Yii::app()->session ['lastUrl'] = "searchLook";
        if ($type == 'createPerson') {
            if ($value == "管理员")
                $value = 0;
            else {
                $tea = Teacher::model()->find("userName = '$value'");
                if ($tea ['userID'] != "")
                    $value = $tea ['userID'];
                else
                    $value = - 1;
            }
        }
        if ($type == "content" && $value !== "") {
            $searchKey = $value;
        } else {
            $searchKey = "no";
        }
        $result = LookType::model()->getLookLst($type, $value);
        $lookLst = $result ['lookLst'];
        $pages = $result ["pages"];
        $this->render('searchLook', array(
            'lookLst' => $lookLst,
            'pages' => $pages,
            'teacher' => TbClass::model()->teaInClass(),
            'searchKey' => $searchKey
        ));
    }

    public function actionAddLook() {
        $result = 'no';
        if (isset($_POST ['title'])) {
            $result = LookType::model()->insertLook($_POST ['title'], $_POST ['content'], 0);
        }
        $this->render('addLook', [
            'result' => $result
        ]);
    }

    public function actionReturnFromAddLook() {
        if (Yii::app()->session ['lastUrl'] == "lookLst") {
            $result = LookType::model()->getLookLst("", "");
            $lookLst = $result ['lookLst'];
            $pages = $result ['pages'];
            Yii::app()->session ['lastUrl'] = "lookLst";
            $this->render('lookLst', array(
                'lookLst' => $lookLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall()
            ));
        } else {
            $type = Yii::app()->session ['searchType'];
            $value = Yii::app()->session ['searchValue'];
            Yii::app()->session ['lastUrl'] = "searchKey";
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
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
            $result = LookType::model()->getLookLst($type, $value);
            $lookLst = $result ['lookLst'];
            $pages = $result ["pages"];
            $this->render('searchLook', array(
                'lookLst' => $lookLst,
                'pages' => $pages,
                'teacher' => TbClass::model()->teaInClass(),
                'searchKey' => $searchKey
            ));
        }
    }

    public function actionDeleteLook() {
        if(isset($_GET['exerciseID'])){
        $exerciseID = $_GET ['exerciseID'];
        $thisLook = new LookType ();
        $deleteResult = $thisLook->deleteAll("exerciseID = '$exerciseID'");
        }
        
        if (isset($_POST['checkbox'])) {
            $userIDlist = $_POST['checkbox'];
            foreach ($userIDlist as $v) {
                $thisLook = new LookType();
                $thisLook->deleteAll("exerciseID = '$v'");
            }
        } 
        
        
        if (Yii::app()->session ['lastUrl'] == "lookLst") {
            $result = LookType::model()->getLookLst("", "");
            $lookLst = $result ['lookLst'];
            $pages = $result ['pages'];
            Yii::app()->session ['lastUrl'] = "lookLst";
            $this->render('lookLst', array(
                'lookLst' => $lookLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
            ));
        } else {
            $type = Yii::app()->session ['searchType'];
            $value = Yii::app()->session ['searchValue'];
            Yii::app()->session ['lastUrl'] = "searchKey";
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
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
            $result = LookType::model()->getLookLst($type, $value);
            $lookLst = $result ['lookLst'];
            $pages = $result ["pages"];
            $this->render('searchLook', array(
                'lookLst' => $lookLst,
                'pages' => $pages,
                'teacher' => TbClass::model()->teaInClass(),
                'searchKey' => $searchKey
                    )
            );
        }
    }

    public function actionEditLook() {
        $exerciseID = $_GET ["exerciseID"];
        $sql = "SELECT * FROM look_type WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result = $result->read();
        if (!isset($_GET ['action'])) {
            $this->render("editLook", array(
                'exerciseID' => $exerciseID,
                'title' => $result ['title'],
                'content' => $result ['content']
            ));
        } else if ($_GET ['action'] = 'look') {
            $this->render("editLook", array(
                'exerciseID' => $exerciseID,
                'title' => $result ['title'],
                'content' => $result ['content'],
                'action' => 'look'
            ));
        }
    }

    public function actionEditLookInfo() {
        $exerciseID = $_GET ['exerciseID'];
        $thisLook = new LookType ();
        $thisLook = $thisLook->find("exerciseID = '$exerciseID'");
        $thisLook->title = $_POST ['title'];
        $thisLook->content = $_POST ['content'];
        $thisLook->update();
        $this->render("editLook", array(
            'exerciseID' => $thisLook->exerciseID,
            'title' => $thisLook->title,
            'content' => $thisLook->content,
            'result' => "修改习题成功"
        ));
    }

    public function actionKeyLst() {
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        $result = KeyType::model()->getKeyLst("", "");
        $keyLst = $result ['keyLst'];
        $pages = $result ['pages'];
        Yii::app()->session ['lastUrl'] = "keyLst";
        $this->render('keyLst', array(
            'keyLst' => $keyLst,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall()
        ));
    }

    public function actionSearchKey() {
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        if (isset($_POST ['type'])) {
            $type = $_POST ['type'];
            $value = $_POST ['value'];
            Yii::app()->session ['searchType'] = $type;
            Yii::app()->session ['searchValue'] = $value;
        } else {
            $type = Yii::app()->session ['searchType'];
            $value = Yii::app()->session ['searchValue'];
        }
        Yii::app()->session ['lastUrl'] = "searchKey";
        if ($type == 'createPerson') {
            if ($value == "管理员")
                $value = 0;
            else {
                $tea = Teacher::model()->find("userName = '$value'");
                if ($tea ['userID'] != "")
                    $value = $tea ['userID'];
                else
                    $value = - 1;
            }
        }
        $result = KeyType::model()->getKeyLst($type, $value);
        $keyLst = $result ['keyLst'];
        $pages = $result ["pages"];
        $this->render('searchKey', array(
            'keyLst' => $keyLst,
            'pages' => $pages,
            'teacher' => TbClass::model()->teaInClass()
        ));
    }

    public function actionReturnFromAddKey() {
        if (Yii::app()->session ['lastUrl'] == "keyLst") {
            $result = KeyType::model()->getKeyLst("", "");
            $keyLst = $result ['keyLst'];
            $pages = $result ['pages'];
            Yii::app()->session ['lastUrl'] = "keyLst";
            $this->render('keyLst', array(
                'keyLst' => $keyLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall()
            ));
        } else {
            $type = Yii::app()->session ['searchType'];
            $value = Yii::app()->session ['searchValue'];
            Yii::app()->session ['lastUrl'] = "searchKey";
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
                    if ($tea ['userID'] != "")
                        $value = $tea ['userID'];
                    else
                        $value = - 1;
                }
            }
            $result = KeyType::model()->getKeyLst($type, $value);
            $keyLst = $result ['keyLst'];
            $pages = $result ["pages"];
            $this->render('searchKey', array(
                'keyLst' => $keyLst,
                'pages' => $pages,
                'teacher' => TbClass::model()->teaInClass()
            ));
        }
    }

    public function actionDeleteKey() {
        if(isset($_GET['exerciseID'])){
        $exerciseID = $_GET ['exerciseID'];
        $thisKey = new KeyType ();
        $deleteResult = $thisKey->deleteAll("exerciseID = '$exerciseID'");
        }
        if (isset($_POST['checkbox'])) {
            $userIDlist = $_POST['checkbox'];
            foreach ($userIDlist as $v) {
                $thisKey = new KeyType();
                $thisKey->deleteAll("exerciseID = '$v'");
            }
        }  
        
        
        if (Yii::app()->session ['lastUrl'] == "keyLst") {
            $result = KeyType::model()->getKeyLst("", "");
            $keyLst = $result ['keyLst'];
            $pages = $result ['pages'];
            Yii::app()->session ['lastUrl'] = "keyLst";
            $this->render('keyLst', array(
                'keyLst' => $keyLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),            
            ));
        } else {
            $type = Yii::app()->session ['searchType'];
            $value = Yii::app()->session ['searchValue'];
            Yii::app()->session ['lastUrl'] = "searchKey";
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
                    if ($tea ['userID'] != "")
                        $value = $tea ['userID'];
                    else
                        $value = - 1;
                }
            }
            $result = KeyType::model()->getKeyLst($type, $value);
            $keyLst = $result ['keyLst'];
            $pages = $result ["pages"];
            $this->render('searchKey', array(
                'keyLst' => $keyLst,
                'pages' => $pages,
                'teacher' => TbClass::model()->teaInClass(),
            ));
        }
    }
    public function ActionGetProgress() {
        session_start();
        $i = ini_get('session.upload_progress.name');
        $key = ini_get("session.upload_progress.prefix") . $_GET[$i];
        if (!empty($_SESSION[$key])) {
            $current = $_SESSION[$key]["bytes_processed"];
            $total = $_SESSION[$key]["content_length"];
            echo $current < $total ? ceil($current / $total * 100) : 100;
        } else {
            echo 100;
        }
        
    }
    public function actionAddKey() {
        $result = 'no';
        if (isset($_POST ['title'])) {
            $i = 2;
            $answer = $_POST ['in1'];
            for (; $i <= 3 * 10; $i ++) {
                if ($_POST ['in' . $i] != ""){
                   if ($i % 3 == 0)
                        $answer = $answer . "_" . $_POST['in' . $i];
                    else
                        $answer = $answer . ":" . $_POST['in' . $i];
                }else
                    break;
            }
            $result = KeyType::model()->insertKey($_POST ['title'], $answer, 0);
        }
        $this->render('addKey', [
            'result' => $result
        ]);
    }

    public function actionEditKey() {
        $exerciseID = $_GET ["exerciseID"];
        $sql = "SELECT * FROM key_type WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result = $result->read();
        $result['content'] = str_replace("_", ":", $result['content']);
        if (!isset($_GET ['action'])) {
            $this->render("editKey", array(
                'exerciseID' => $exerciseID,
                'title' => $result ['title'],
                'content' => $result ['content']
            ));
        } else if ($_GET ['action'] = 'look') {
            $this->render("editKey", array(
                'exerciseID' => $exerciseID,
                'title' => $result ['title'],
                'content' => $result ['content'],
                'action' => 'look'
            ));
        }
    }

    public function actionEditKeyInfo() {
        $exerciseID = $_GET ['exerciseID'];
        $thisKey = new KeyType ();
        $thisKey = $thisKey->find("exerciseID = '$exerciseID'");
        $i = 2;
        $answer = $_POST ['in1'];
        for (; $i <= 3 * 10; $i ++) {
            if ($_POST ['in' . $i] != "")
                $answer = $answer . ":" . $_POST ['in' . $i];
            else
                break;
        }
        $thisKey->title = $_POST ['title'];
        $thisKey->content = $answer;
        $thisKey->update();
        $this->render("editKey", array(
            'exerciseID' => $thisKey->exerciseID,
            'title' => $thisKey->title,
            'content' => $thisKey->content,
            'result' => "修改习题成功"
        ));
    }

    public function actionListenLst() {
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        $result = ListenType::model()->getListenLst("", "");
        $listenLst = $result ['listenLst'];
        $pages = $result ['pages'];
        Yii::app()->session ['lastUrl'] = "listenLst";
        $this->render('listenLst', array(
            'listenLst' => $listenLst,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall()
        ));
    }

    public function actionSearchListen() {
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        if (isset($_POST ['type'])) {
            $type = $_POST ['type'];
            $value = $_POST ['value'];
            Yii::app()->session ['searchType'] = $type;
            Yii::app()->session ['searchValue'] = $value;
        } else {
            $type = Yii::app()->session ['searchType'];
            $value = Yii::app()->session ['searchValue'];
        }
        Yii::app()->session ['lastUrl'] = "searchListen";
        if ($type == 'createPerson') {
            if ($value == "管理员")
                $value = 0;
            else {
                $tea = Teacher::model()->find("userName = '$value'");
                if ($tea ['userID'] != "")
                    $value = $tea ['userID'];
                else
                    $value = - 1;
            }
        }
        if ($type == "content" && $value !== "") {
            $searchKey = $value;
        } else {
            $searchKey = "no";
        }
        $result = ListenType::model()->getListenLst($type, $value);
        $listenLst = $result ['listenLst'];
        $pages = $result ["pages"];
        $this->render('searchListen', array(
            'listenLst' => $listenLst,
            'pages' => $pages,
            'teacher' => TbClass::model()->teaInClass(),
            'searchKey' => $searchKey
        ));
    }

    public function actionAddListen() {
        $result = 'no';
        $typename = Yii::app()->session ['role_now'];
        $userid = Yii::app()->session ['userid_now'];
        $filePath = $typename . "/" . $userid . "/";
        $dir = "resources/" . $filePath;
        if (!is_dir($dir)) {
            mkdir($dir, 0777);
        }
        $title = "";
        $content = "";
        if (isset($_POST ['title'])) {
            $title = $_POST ['title'];
            $content = $_POST ['content'];
            
            if ($_FILES ['file'] ['type'] != "audio/mpeg" &&
                $_FILES ['file'] ['type'] != "audio/wav"  &&
                $_FILES ['file'] ['type'] != "audio/x-wav"    ) {
                $result = '文件格式不正确，应为MP3或WAV格式';
            } else if ($_FILES ['file'] ['error'] > 0) {
                $result = '文件上传失败';
            } else {
                $flag = 1;
                $sqlListen = Resourse::model()->findAll("type = 'radio'");
                foreach ($sqlListen as $v){
                    if($v['name'] == $_FILES["file"]["name"]){
                       $result = '该文件已存在，如需重复使用请改名重新上传！';
                       $flag = 0;
                    }
                }
                if($flag == 1){
                     $oldName = $_FILES["file"]["name"];
                    $newName = Tool::createID() . "." . pathinfo($oldName, PATHINFO_EXTENSION);
                    move_uploaded_file($_FILES["file"]["tmp_name"], $dir . iconv("UTF-8", "gb2312", $newName));
                    Resourse::model()->insertRelaRadio($newName, $oldName);
                    $result = ListenType::model()->insertListen($_POST ['title'], $_POST ['content'], $newName, $filePath, 0);
                    $result = '1';
                }
               
            }
        }
        $this->render('addListen', array(
            'result' => $result,
            'title' => $title,
            'content' => $content
        ));
    }

    public function actionReturnFromAddListen() {
        if (Yii::app()->session ['lastUrl'] == "listenLst") {
            $result = ListenType::model()->getListenLst("", "");
            $listenLst = $result ['listenLst'];
            $pages = $result ['pages'];
            Yii::app()->session ['lastUrl'] = "listenLst";
            $this->render('listenLst', array(
                'listenLst' => $listenLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall()
            ));
        } else {
            $type = Yii::app()->session ['searchType'];
            $value = Yii::app()->session ['searchValue'];
            Yii::app()->session ['lastUrl'] = "searchListen";
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
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
            $result = ListenType::model()->getListenLst($type, $value);
            $listenLst = $result ['listenLst'];
            $pages = $result ["pages"];
            $this->render('searchListen', array(
                'listenLst' => $listenLst,
                'pages' => $pages,
                'teacher' => TbClass::model()->teaInClass(),
                'searchKey' => $searchKey
            ));
        }
    }

    public function actionDeleteListen() {
        if(isset($_GET['exerciseID'])){
        $exerciseID = $_GET ['exerciseID'];
        $thisListen = new ListenType ();
        $deleteListen = $thisListen->findAll("exerciseID = '$exerciseID'");
        $deleteResult = $thisListen->deleteAll("exerciseID = '$exerciseID'");
        $filePath = $deleteListen [0] ['filePath'];
        $fileName = $deleteListen [0] ['fileName'];
        if ($deleteResult == '1') {
            $typename = Yii::app()->session ['role_now'];
            $userid = Yii::app()->session ['userid_now'];
            // 怎么用EXER_LISTEN_URL
            $path = 'resources/' . $filePath . iconv("UTF-8", "gb2312", $fileName);
            if (file_exists($path))
                unlink($path);
            Resourse::model()->delName($fileName);
        }
        }
        
       if (isset($_POST['checkbox'])) {
            $userIDlist = $_POST['checkbox'];
            foreach ($userIDlist as $v) {
                $thisListen = new ListenType();
                  $deleteListen = $thisListen->findAll("exerciseID = '$v'");
        $deleteResult = $thisListen->deleteAll("exerciseID = '$v'");
        $filePath = $deleteListen [0] ['filePath'];
        $fileName = $deleteListen [0] ['fileName'];
        if ($deleteResult == '1') {
            $typename = Yii::app()->session ['role_now'];
            $userid = Yii::app()->session ['userid_now'];
            // 怎么用EXER_LISTEN_URL
            $path = 'resources/' . $filePath . iconv("UTF-8", "gb2312", $fileName);
            if (file_exists($path))
                unlink($path);
            Resourse::model()->delName($fileName);
            }
        } 
       }
        
        
        if (Yii::app()->session ['lastUrl'] == "listenLst") {
            $result = ListenType::model()->getListenLst("", "");
            $listenLst = $result ['listenLst'];
            $pages = $result ['pages'];
            Yii::app()->session ['lastUrl'] = "listenLst";
            $this->render('listenLst', array(
                'listenLst' => $listenLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'deleteResult' => $deleteResult
            ));
        } else {
            $type = Yii::app()->session ['searchType'];
            $value = Yii::app()->session ['searchValue'];
            Yii::app()->session ['lastUrl'] = "searchListen";
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
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
            $result = ListenType::model()->getListenLst($type, $value);
            $listenLst = $result ['listenLst'];
            $pages = $result ["pages"];
            $this->render('searchListen', array(
                'listenLst' => $listenLst,
                'pages' => $pages,
                'teacher' => TbClass::model()->teaInClass(),
                'deleteResult' => $deleteResult,
                'searchKey' => $searchKey
            ));
        }
    }

    public function actionEditlisten() {
        $exerciseID = $_GET ["exerciseID"];
        $sql = "SELECT * FROM listen_type WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result = $result->read();
        if (!isset($_GET ['action'])) {
            $this->render("editListen", array(
                'exerciseID' => $exerciseID,
                'title' => $result ['title'],
                'filename' => $result ['fileName'],
                'filepath' => $result ['filePath'],
                'content' => $result ['content']
            ));
        } else if ($_GET ['action'] = 'look') {
            $this->render("editListen", array(
                'exerciseID' => $exerciseID,
                'title' => $result ['title'],
                'filename' => $result ['fileName'],
                'filepath' => $result ['filePath'],
                'content' => $result ['content'],
                'action' => 'look'
            ));
        }
    }

    public function actionEditListenInfo() {
        $typename = Yii::app()->session ['role_now'];
        $userid = Yii::app()->session ['userid_now'];
        $filePath = $_GET['filepath'];
        $dir = "resources/" . $filePath;
        $exerciseID = $_GET ['exerciseID'];
        $thisListen = new ListenType ();
        $thisListen = $thisListen->find("exerciseID = '$exerciseID'");
        $filename = $_GET ['oldfilename'];
        if ($_FILES ['modifyfile'] ['tmp_name']) {
            if ($_FILES ['modifyfile'] ['type'] != "audio/mpeg" &&
                $_FILES ['modifyfile'] ['type'] != "audio/wav"  &&
                $_FILES ['modifyfile'] ['type'] != "audio/x-wav"     ) {
                $result = '文件格式不正确，应为MP3或WAV格式：';
            } else if ($_FILES ['modifyfile'] ['error'] > 0) {
                $result = '文件上传失败';
            } else {
                $newName = Tool::createID() . "." . pathinfo($_FILES["modifyfile"]["name"], PATHINFO_EXTENSION);
                move_uploaded_file($_FILES["modifyfile"]["tmp_name"], $dir . iconv("UTF-8", "gb2312", $newName));
                if (file_exists($dir . iconv("UTF-8", "gb2312", $filename)))
                    unlink($dir . iconv("UTF-8", "gb2312", $filename));
                Resourse::model()->replaceRela($filename, $newName, $_FILES ["modifyfile"] ["name"]);
                $thisListen->fileName = $newName;
                $result = "上传成功";
            }
        }
        if (!isset($result) || $result == "上传成功") {
            $thisListen->title = $_POST ['title'];
            $thisListen->content = $_POST ['content'];
            $thisListen->update();
            $result = "修改成功!";
        } else {
            $result = "修改失败!";
        }
        $this->render("editListen", array(
            'exerciseID' => $thisListen->exerciseID,
            'filename' => $thisListen->fileName,
            'filepath' => $thisListen->filePath,
            'title' => $thisListen->title,
            'content' => $thisListen->content,
            'result' => $result
        ));
    }

    public function actionFillLst() {
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        $result = Filling::model()->getFillLst("", "");
        $fillLst = $result ['fillLst'];
        $pages = $result ['pages'];
        Yii::app()->session ['lastUrl'] = "fillLst";
        $this->render('fillLst', array(
            'fillLst' => $fillLst,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall()
        ));
    }

    public function actionSearchFill() {
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        if (isset($_POST ['type'])) {
            $type = $_POST ['type'];
            $value = $_POST ['value'];
            Yii::app()->session ['searchFillType'] = $type;
            Yii::app()->session ['searchFillValue'] = $value;
        } else {
            $type = Yii::app()->session ['searchFillType'];
            $value = Yii::app()->session ['searchFillValue'];
        }
        Yii::app()->session ['lastUrl'] = "searchFill";
        if ($type == 'createPerson') {
            if ($value == "管理员")
                $value = 0;
            else {
                $tea = Teacher::model()->find("userName = '$value'");
                if ($tea ['userID'] != "")
                    $value = $tea ['userID'];
                else
                    $value = - 1;
            }
        }
        if ($type == "requirements" && $value !== "") {
            $searchKey = $value;
        } else {
            $searchKey = "no";
        }
        $result = Filling::model()->getFillLst($type, $value);
        $fillLst = $result ['fillLst'];
        $pages = $result ['pages'];
        $this->render('searchFill', array(
            'fillLst' => $fillLst,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall(),
            'searchKey' => $searchKey
        ));
    }

    public function actionAddFill() {
        $result = 'no';
        if (isset($_POST ['requirements'])) {
            $i = 2;
            $answer = $_POST ['in1'];
            for (; $i <= 5; $i ++) {
                if ($_POST ['in' . $i] != "")
                    $answer = $answer . "$$" . $_POST ['in' . $i];
                else
                    break;
            }
            $result = Filling::model()->insertFill($_POST ['requirements'], $answer, 0);
        }
        $this->render('addFill', [
            'result' => $result
        ]);
    }

    public function actionReturnFromAddFill() {
        if (Yii::app()->session ['lastUrl'] == "searchFill") {
            $type = Yii::app()->session ['searchFillType'];
            $value = Yii::app()->session ['searchFillValue'];
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
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
            $result = Filling::model()->getFillLst($type, $value);
            $fillLst = $result ['fillLst'];
            $pages = $result ['pages'];
            $this->render('searchFill', array(
                'fillLst' => $fillLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'searchKey' => $searchKey
                    )
            );
        } else {
            $result = Filling::model()->getFillLst("", "");
            $fillLst = $result ['fillLst'];
            $pages = $result ['pages'];
            Yii::app()->session ['lastUrl'] = "fillLst";
            $this->render('fillLst', array(
                'fillLst' => $fillLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall()
            ));
        }
    }

    public function actionDeleteFill() {
        if(isset($_GET['exerciseID'])){
        $exerciseID = $_GET ["exerciseID"];
        $thisFill = new Filling ();
        $deleteResult = $thisFill->deleteAll("exerciseID = '$exerciseID'");
        }
        if (isset($_POST['checkbox'])) {
            $userIDlist = $_POST['checkbox'];
            foreach ($userIDlist as $v) {
                $thisFill = new Filling ();
                $thisFill->deleteAll("exerciseID = '$v'");
            }
        }     
        if (Yii::app()->session ['lastUrl'] == "searchFill") {
            $type = Yii::app()->session ['searchFillType'];
            $value = Yii::app()->session ['searchFillValue'];
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
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
            $result = Filling::model()->getFillLst($type, $value);
            $fillLst = $result ['fillLst'];
            $pages = $result ['pages'];
            $this->render('searchFill', array(
                'fillLst' => $fillLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'searchKey' => $searchKey
            ));
        } else {
            $result = Filling::model()->getFillLst("", "");
            $fillLst = $result ['fillLst'];
            $pages = $result ['pages'];
            Yii::app()->session ['lastUrl'] = "fillLst";
            $this->render('fillLst', array(
                'fillLst' => $fillLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
            ));
        }
    }

    public function actionEditFill() {
        $exerciseID = $_GET ["exerciseID"];
        $thisFill = new Filling ();
        $thisFill = $thisFill->find("exerciseID = '$exerciseID'");
        if (!isset($_GET ['action'])) {
            $this->render("editFill", array(
                'exerciseID' => $exerciseID,
                'requirements' => $thisFill->requirements,
                'answer' => $thisFill->answer
            ));
        } else if ($_GET ['action'] = 'look') {
            $this->render("editFill", array(
                'exerciseID' => $exerciseID,
                'requirements' => $thisFill->requirements,
                'answer' => $thisFill->answer,
                'action' => 'look'
            ));
        }
    }

    public function actionEditFillInfo() {
        $exerciseID = $_GET ['exerciseID'];
        $thisFill = new Filling ();
        $thisFill = $thisFill->find("exerciseID = '$exerciseID'");
        $i = 2;
        $answer = $_POST ['in1'];
        for (; $i <= 5; $i ++) {
            if ($_POST ['in' . $i] != "")
                $answer = $answer . "$$" . $_POST ['in' . $i];
            else
                break;
        }
        $thisFill->requirements = $_POST ['requirements'];
        $thisFill->answer = $answer;
        $thisFill->update();
        $this->render("editFill", array(
            'exerciseID' => $thisFill->exerciseID,
            'requirements' => $thisFill->requirements,
            'answer' => $thisFill->answer,
            'result' => "修改习题成功"
        ));
    }

    public function actionChoiceLst() {
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        $result = Choice::model()->getChoiceLst("", "");
        $choiceLst = $result ['choiceLst'];
        $pages = $result ['pages'];
        Yii::app()->session ['lastUrl'] = "choiceLst";
        $this->render('choiceLst', array(
            'choiceLst' => $choiceLst,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall()
        ));
    }

    public function actionSearchChoice() {
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        if (isset($_POST ['type'])) {
            $type = $_POST ['type'];
            $value = $_POST ['value'];
            Yii::app()->session ['searchChoiceType'] = $type;
            Yii::app()->session ['searchChoiceValue'] = $value;
        } else {
            $type = Yii::app()->session ['searchChoiceType'];
            $value = Yii::app()->session ['searchChoiceValue'];
        }
        Yii::app()->session ['lastUrl'] = "searchChoice";
        if ($type == 'createPerson') {
            if ($value == "管理员")
                $value = 0;
            else {
                $tea = Teacher::model()->find("userName = '$value'");
                if ($tea ['userID'] != "")
                    $value = $tea ['userID'];
                else
                    $value = - 1;
            }
        }
        if ($type == "requirements" && $value !== "") {
            $searchKey = $value;
        } else {
            $searchKey = "no";
        }
        $result = Choice::model()->getChoiceLst($type, $value);
        $choiceLst = $result ['choiceLst'];
        $pages = $result ['pages'];
        $this->render('searchChoice', array(
            'choiceLst' => $choiceLst,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall(),
            'searchKey' => $searchKey
        ));
    }

    public function actionReturnFromAddChoice() {
        if (Yii::app()->session ['lastUrl'] == "searchChoice") {
            $type = Yii::app()->session ['searchChoiceType'];
            $value = Yii::app()->session ['searchChoiceValue'];
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
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
            $result = Choice::model()->getChoiceLst($type, $value);
            $choiceLst = $result ['choiceLst'];
            $pages = $result ['pages'];
            $this->render('searchChoice', array(
                'choiceLst' => $choiceLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'searchKey' => $searchKey
            ));
        } else {
            $result = Choice::model()->getChoiceLst("", "");
            $choiceLst = $result ['choiceLst'];
            $pages = $result ['pages'];
            Yii::app()->session ['lastUrl'] = "choiceLst";
            $this->render('choiceLst', array(
                'choiceLst' => $choiceLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall()
            ));
        }
    }

    public function actionEditChoiceInfo() {
        $exerciseID = $_GET ['exerciseID'];
        $thisCh = new Choice ();
        $thisCh = $thisCh->find("exerciseID = '$exerciseID'");
        $thisCh->requirements = $_POST ['requirements'];
        $thisCh->options = $_POST ['A'] . "$$" . $_POST ['B'] . "$$" . $_POST ['C'] . "$$" . $_POST ['D'];
        $thisCh->answer = $_POST ['answer'];
        $thisCh->update();
        $this->render("editChoice", array(
            'exerciseID' => $exerciseID,
            'requirements' => $thisCh->requirements,
            'options' => $thisCh->options,
            'answer' => $thisCh->answer,
            'result' => "修改习题成功"
        ));
    }

    public function actionEditChoice() {
        $exerciseID = $_GET ['exerciseID'];
        $sql = "SELECT * FROM choice WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result = $result->read();
        if (!isset($_GET ['action'])) {
            $this->render("editChoice", array(
                'exerciseID' => $result ['exerciseID'],
                'requirements' => $result ['requirements'],
                'options' => $result ['options'],
                'answer' => $result ['answer']
            ));
        } else if ($_GET ['action'] == 'look') {
            $this->render("editChoice", array(
                'exerciseID' => $result ['exerciseID'],
                'requirements' => $result ['requirements'],
                'options' => $result ['options'],
                'answer' => $result ['answer'],
                'action' => 'look'
            ));
        }
    }

    public function actionAddChoice() {
        $result = 'no';
        if (isset($_POST ['requirements'])) {
            $result = Choice::model()->insertChoice($_POST ['requirements'], $_POST ['A'] . "$$" . $_POST ['B'] . "$$" . $_POST ['C'] . "$$" . $_POST ['D'], $_POST ['answer'], 0);
        }
        $this->render('addChoice', [
            'result' => $result
        ]);
    }

    // 2015 8-7 宋杰 删除选择题
    public function actionDeleteChoice() {
        if(isset($_GET ['exerciseID'])) {
        $exerciseID = $_GET ["exerciseID"];
        $thisChoice = new Choice ();
        $deleteResult = $thisChoice->deleteAll("exerciseID = '$exerciseID'");
        }
        if (isset($_POST['checkbox'])) {
            $userIDlist = $_POST['checkbox'];
            foreach ($userIDlist as $v) {
                $thisChoice = new Choice ();
                $thisChoice->deleteAll("exerciseID = '$v'");
            }
        }
        if (Yii::app()->session ['lastUrl'] == "searchChoice") {
            $type = Yii::app()->session ['searchChoiceType'];
            $value = Yii::app()->session ['searchChoiceValue'];
                        if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
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
            $result = Choice::model()->getChoiceLst($type, $value);
            $choiceLst = $result ['choiceLst'];
            $pages = $result ['pages'];          
            $this->render('searchChoice', array(
                'choiceLst' => $choiceLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
                'searchKey' => $searchKey
                    )
            );
        } else {
            $result = Choice::model()->getChoiceLst("", "");
            $choiceLst = $result ['choiceLst'];
            $pages = $result ['pages'];
            Yii::app()->session ['lastUrl'] = "choiceLst";
            $this->render('choiceLst', array(
                'choiceLst' => $choiceLst,
                'pages' => $pages,
                'teacher' => Teacher::model()->findall(),
            ));
        }
    }

    public function actionQuestionLst() {
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        Yii::app()->session ['lastUrl'] = "questionLst";
        $result = Question::model()->getQuestionLst("", "");
        $questionLst = $result ['questionLst'];
        $pages = $result ["pages"];
        $this->render('questionLst', array(
            'questionLst' => $questionLst,
            'pages' => $pages,
            'teacher' => TbClass::model()->teaInClass()
        ));
    }

    public function actionSearchQuestion() {
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        if (isset($_POST ['type'])) {
            $type = $_POST ['type'];
            $value = $_POST ['value'];
            Yii::app()->session ['searchType'] = $type;
            Yii::app()->session ['searchValue'] = $value;
        } else {
            $type = Yii::app()->session ['searchType'];
            $value = Yii::app()->session ['searchValue'];
        }
        Yii::app()->session ['lastUrl'] = "searchQuestion";
        if ($type == 'createPerson') {
            if ($value == "管理员")
                $value = 0;
            else {
                $tea = Teacher::model()->find("userName = '$value'");
                if ($tea ['userID'] != "")
                    $value = $tea ['userID'];
                else
                    $value = - 1;
            }
        }
        if ($type == "requirements" && $value !== "") {
            $searchKey = $value;
        } else {
            $searchKey = "no";
        }
        $result = Question::model()->getQuestionLst($type, $value);
        $questionLst = $result ['questionLst'];
        $pages = $result ["pages"];
        $this->render('searchQuestion', array(
            'questionLst' => $questionLst,
            'pages' => $pages,
            'teacher' => TbClass::model()->teaInClass(),
            'searchKey' => $searchKey
        ));
    }

    public function actionReturnFromAddQuestion() {
        if (Yii::app()->session ['lastUrl'] == "questionLst") {
            $result = Question::model()->getQuestionLst("", "");
            $questionLst = $result ['questionLst'];
            $pages = $result ["pages"];
            $this->render('questionLst', array(
                'questionLst' => $questionLst,
                'pages' => $pages,
                'teacher' => TbClass::model()->teaInClass()
            ));
        } else {
            $type = Yii::app()->session ['searchType'];
            $value = Yii::app()->session ['searchValue'];
            Yii::app()->session ['lastUrl'] = "searchQuestion";
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
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
            $result = Question::model()->getQuestionLst($type, $value);
            $questionLst = $result ['questionLst'];
            $pages = $result ["pages"];
            $this->render('searchQuestion', array(
                'questionLst' => $questionLst,
                'pages' => $pages,
                'teacher' => TbClass::model()->teaInClass(),
                'searchKey' => $searchKey
            ));
        }
    }

    public function actionDeleteQuestion() {
        if(isset($_GET['exerciseID'])){
        $exerciseID = $_GET ['exerciseID'];
        $thisQue = new Question ();
        $deleteResult = $thisQue->deleteAll("exerciseID = '$exerciseID'");
        }
        
         if (isset($_POST['checkbox'])) {
            $userIDlist = $_POST['checkbox'];
            foreach ($userIDlist as $v) {
                $thisQuestion = new Question();
                $thisQuestion->deleteAll("exerciseID = '$v'");
            }
        }     
        
        
        
        if (Yii::app()->session ['lastUrl'] == "questionLst") {
            $result = Question::model()->getQuestionLst("", "");
            $questionLst = $result ['questionLst'];
            $pages = $result ["pages"];
            $this->render('questionLst', array(
                'questionLst' => $questionLst,
                'pages' => $pages,
                'teacher' => TbClass::model()->teaInClass(),
            ));
        } else {
            $type = Yii::app()->session ['searchType'];
            $value = Yii::app()->session ['searchValue'];
            Yii::app()->session ['lastUrl'] = "searchQuestion";
            if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
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
            $result = Question::model()->getQuestionLst($type, $value);
            $questionLst = $result ['questionLst'];
            $pages = $result ["pages"];
            $this->render('searchQuestion', array(
                'questionLst' => $questionLst,
                'pages' => $pages,
                'teacher' => TbClass::model()->teaInClass(),
                'searchKey' => $searchKey
            ));
        }
    }

    public function actionEditQuestion() {
        $exerciseID = $_GET ["exerciseID"];
        $sql = "SELECT * FROM question WHERE exerciseID = '$exerciseID'";
        $result = Yii::app()->db->createCommand($sql)->query();
        $result = $result->read();
        if (!isset($_GET ['action'])) {
            $this->render("editQuestion", array(
                'exerciseID' => $result ['exerciseID'],
                'requirements' => $result ['requirements'],
                'answer' => $result ['answer']
            ));
        } else if ($_GET ['action'] == 'look') {
            $this->render("editQuestion", array(
                'exerciseID' => $result ['exerciseID'],
                'requirements' => $result ['requirements'],
                'answer' => $result ['answer'],
                'action' => 'look'
            ));
        }
    }

    public function actionEditQuestionInfo() {
        $exerciseID = $_GET ['exerciseID'];
        $thisQue = new Question ();
        $thisQue = $thisQue->find("exerciseID = '$exerciseID'");
        $thisQue->requirements = $_POST ['requirements'];
        $thisQue->answer = $_POST ['answer'];
        $thisQue->update();
        $this->render("editQuestion", array(
            'exerciseID' => $thisQue->exerciseID,
            'requirements' => $thisQue->requirements,
            'answer' => $thisQue->answer,
            'result' => "修改习题成功"
        ));
    }

    public function actionAddQuestion() {
        $result = 'no';
        if (isset($_POST ['requirements'])) {
            $result = Question::model()->insertQue($_POST ['requirements'], $_POST ['answer'], 0);
        }
        $this->render('addQuestion', [
            'result' => $result
        ]);
    }

    public function actionDeleteCourse() {
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        
        if(isset($_GET['courseID'])){
                    $result = '';
        $courseID = $_GET['courseID'];
            $classes = TbClass::model()->findall("currentCourse = $courseID");
            if (count($classes) > 0) {
                $result = 0;
            } else {
                $rows = Course::model()->deleteAll('courseID=?', array($courseID));
                $rows = Lesson::model()->deleteAll('courseID=?', array($courseID));
                $result = 1;
            }
        }
        
         if (isset($_POST['checkbox'])) {
             $result = 1;
            $userIDlist = $_POST['checkbox'];
            foreach ($userIDlist as $v) {
                            $classes = TbClass::model()->findall("currentCourse = '$v'");
            if (count($classes) > 0) {
                $result = 0;
            } else {
                $rows = Course::model()->deleteAll('courseID=?', array($v));
                $rows = Lesson::model()->deleteAll('courseID=?', array($v));              
            }
            }
        }
        if (Yii::app()->session ['lastUrl'] == "searchCourse") {           
            $type = Yii::app()->session ['searchType'];
            $value = Yii::app()->session ['searchValue'];
                        if ($type == 'createPerson') {
                if ($value == "管理员")
                    $value = 0;
                else {
                    $tea = Teacher::model()->find("userName = '$value'");
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
            $result1 = Course::model()->getCourseLst($type, $value);        
        $courseLst = $result1 ['courseLst'];
        $pages = $result1 ['pages'];
        $this->render('searchCourse', array(
            'courseLst' => $courseLst,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall(),
            'result' => $result,
            ));
        } else {             
        $result_forNumber = Course::model()->getCourseLst("", "");
        $courseLst_forNumber = $result_forNumber ['courseLst'];
        $array_maxNumber = array();
        foreach ($courseLst_forNumber as $v){
            $number = 0;
            $courseID = $v['courseID'];
            $lesson = Lesson::model()->findAll("courseID = '$courseID'");
            if(empty($lesson)){
            }else{
                foreach ($lesson as $value){
                    $number++;
                }
            }
            array_push($array_maxNumber,$number);
        }
        $courses = Course::model()->getCourseLst("", "");
        $courseLst = $courses ['courseLst'];
        $pages = $courses ['pages'];
        $this->render('courseLst', array(
            'courseLst' => $courseLst,
            'courseNumber' =>$array_maxNumber,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall(),
            'result' => $result
        ));
    }
    }

    public function actionCourseLst() {
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        $result = Course::model()->getCourseLst("", "");
        $courseLst = $result ['courseLst'];
        $result_forNumber = Course::model()->getCourseLst("", "");
        $courseLst_forNumber = $result_forNumber ['courseLst'];
        $array_maxNumber = array();
        foreach ($courseLst_forNumber as $v){
            $number = 0;
            $courseID = $v['courseID'];
            $lesson = Lesson::model()->findAll("courseID = '$courseID' and classID = 0");
            if(empty($lesson)){
            }else{
                foreach ($lesson as $value){
                    $number++;
                }
            }
            array_push($array_maxNumber,$number);
        }
        $pages = $result ['pages'];
        Yii::app()->session ['lastUrl'] = "courseLst";
        $this->render('courseLst', array(
            'courseLst' => $courseLst,
            'courseNumber' =>$array_maxNumber,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall(),
            'result' => ''
        ));
    }

    public function actionSearchCourse() {
        if (isset($_GET ['page'])) {
            Yii::app()->session ['lastPage'] = $_GET ['page'];
        } else {
            Yii::app()->session ['lastPage'] = 1;
        }
        if (isset($_POST ['type'])) {
            $type = $_POST ['type'];
            $value = $_POST ['value'];
            Yii::app()->session ['searchType'] = $type;
            Yii::app()->session ['searchValue'] = $value;
        } else {
            $type = Yii::app()->session ['searchType'];
            $value = Yii::app()->session ['searchValue'];
        }
        Yii::app()->session ['lastUrl'] = "searchCourse";
        if($type=='courseID'&& $value=='1'){
            $value="";
        }
        if($type=='courseName'&& $value=='速录教学'){
            $value="";
        }
        if ($type == 'createPerson') {
            if ($value == "管理员") {
                $value = 0;
            } else {
                $tea = Teacher::model()->find("userName = '$value'");
                if ($tea ['userID'] != "")
                    $value = $tea ['userID'];
                else
                    $value = - 1;
            }
        }
        $result = Course::model()->getCourseLst($type, $value);
        $courseLst = $result ['courseLst'];
        $pages = $result ['pages'];
        $this->render('searchCourse', array(
            'courseLst' => $courseLst,
            'pages' => $pages,
            'teacher' => Teacher::model()->findall(),
            'result' => 2,
        ));
    }

    public function actionAddCourse() {
        $result = 'no';
        $sql = "SELECT * FROM `course` ORDER BY `course`.`courseID` DESC ";
        $aCourse = Yii::app()->db->createCommand($sql)->query();
        if (isset($_POST ['courseName'])) {
            $flag = 1;
            $courseNumber = $_POST['courseNumber'];
            $courseName = $_POST['courseName'];
            $allCourse = Course::model()->findAll();
            foreach ($allCourse as $v){
                if($v['courseName']==$courseName){
                    $result = 'have_same_course';
                    $flag = 0;
                    break;
                }
                $flag = 1;
            }
            if($flag==1){
                 $result = Course::model()->insertCourse($_POST ['courseName'], 0);
            }
            if ($result == 1) {
                Yii::app()->session['insert_course'] = $_POST ['courseName'];
                $sql = "SELECT MAX(courseID) AS id FROM course";
                $max_id = Yii::app()->db->createCommand($sql)->query()->read();
                $courseID = $max_id['id'];
                $classes = TbClass::model()->findall("currentCourse = '$courseID'");
                if (empty($classes)) {
                    for ($i = 1; $i < ($courseNumber + 1); $i++) {
                        $result = Lesson::model()->insertLesson( "第".$i."课", $courseID, 0, 0);
                    }
                } else {
                    for ($i = 1; $i < ($courseNumber + 1); $i++) {
                        foreach ($classes as $class) {
                            $result = Lesson::model()->insertLesson( "第".$i."课", $courseID, 0, $class['classID']);
                        }
                    }
                }
            }
            $this->render('addCourse', [
                    'result' => $result,
                    'allCourse' =>$aCourse
                ]);
        } else {
            $this->render('addCourse', [
                'result' => $result,
                'allCourse' =>$aCourse
            ]);
        }
    }

    public function actionInfoCourse() {
        $deleteResult = 'no';
        $courseID = $_GET ['courseID'];
        if(isset($_GET['delete'])){
            //删
            $lessonName = $_GET['lessonName'];
            $deleteResult = Lesson::model()->deleteAll("courseID = '$courseID' and lessonName = '$lessonName'");
            $allLesson = Lesson::model()->findAll("courseID = '$courseID'");
            $count=1;
            foreach ($allLesson as $v){
                $ln = $v["lessonID"];
                $sql = "UPDATE `lesson` SET `number`= '$count' WHERE lessonID = '$ln'";
                Yii::app()->db->createCommand($sql)->query();
                $count++;
            }
            
        }else if (isset ($_GET['newName'])) {
            //改
            $lessonName = $_GET['lessonName'];
            $number=$_GET['number'];
            $newName = $_GET['newName'];
            $sql = "UPDATE `lesson` SET `lessonName`= '$newName' WHERE number= '$number' and courseID='$courseID' ";
            Yii::app()->db->createCommand($sql)->query();
        }
        $courseName = $_GET ['courseName'];
        $createPerson = $_GET ['createPerson'];
        Yii::app()->session['courseID'] = $courseID;
        Yii::app()->session['courseName'] = $courseName;
        Yii::app()->session['createPerson'] = $createPerson;
        $result = Lesson::model()->getLessonLst("", "", $courseID,0);
        $lessonLst = $result ['lessonLst'];
        $pages = $result ['pages'];
        
        $this->render('infoCourse', array(
            'deleteResult'=>$deleteResult,
            'courseID' => $courseID,
            'courseName' => $courseName,
            'createPerson' => $createPerson,
            'posts' => $lessonLst,
            'pages' => $pages,
        ));
    }

    public function actionAddLesson() {
        $courseID = $_GET ['courseID'];
        $courseName = $_GET ['courseName'];
        $createPerson = $_GET ['createPerson'];
        $result = 'no';
        $sql = "SELECT * FROM `lesson` WHERE `courseID` = $courseID AND `classID` = 0 ORDER BY `lesson`.`number` DESC ";
        $aLesson = Yii::app()->db->createCommand($sql)->query();
        if (isset($_POST['lessonName'])) {
            Yii::app()->session['insert_lesson'] = $_POST['lessonName'];
            $result = Lesson::model()->insertLesson($_POST['lessonName'], $courseID, 0, 0);
            $classes = TbClass::model()->findall("currentCourse = '$courseID'");
            foreach ($classes as $class) {
                $result = Lesson::model()->insertLesson($_POST['lessonName'], $courseID, 0, $class['classID']);
            }
        }
        $this->render('addLesson', array(
            'courseID' => $courseID,
            'courseName' => $courseName,
            'createPerson' => $createPerson,
            'result' => $result,
            'allLesson' =>$aLesson
        ));
    }

    public function actionPptLst() {
        $courseID = $_GET ['courseID'];
        $courseName = $_GET ['courseName'];
        $createPerson = $_GET ['createPerson'];
        $pdir = $_GET ['pdir'];
        Yii::app()->session['courseID'] = $courseID;
        Yii::app()->session['courseName'] = $courseName;
        Yii::app()->session['createPerson'] = $createPerson;
        $this->render("pptLst", array(
            'courseID' => $courseID,
            'courseName' => $courseName,
            'createPerson' => $createPerson,
            'pdir' => $pdir
        ));
    }
    
    public function actionAddPpt() {
        $dir = $_GET['pdir'];
        $result = "上传失败!";
        if (!isset($_FILES["file"])) {
            echo "请选择文件！";
            return;
        }
        $sqlPpt = Resourse::model()->findAll("type = 'ppt'");
        foreach ($sqlPpt as $v){
            if($v['name'] == $_FILES["file"]["name"]){
                echo "该文件已存在，如需重复使用请改名重新上传！";
                return;
            }
        }
        if ($_FILES["file"]["type"] == "application/vnd.ms-powerpoint"||$_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.presentationml.presentation") {
            if ($_FILES["file"]["error"] > 0) {
                $result = "Return Code: " . $_FILES["file"]["error"];
            } else {
                $newName = Tool::createID() . ".ppt";
                $oldName = $_FILES["file"]["name"];
                move_uploaded_file($_FILES["file"]["tmp_name"], $dir . iconv("UTF-8", "gb2312", $newName));
                Resourse::model()->insertRela($newName, $oldName);
                $result = "上传成功！";
            }
        } else {
            $result = "请上传正确类型的文件！";
        }
        echo $result;
    }

    public function actionPptTable() {
        $pdir = $_GET ['pdir'];
        return $this->renderPartial('pptTable', [
                    'pdir' => $pdir,
        ]);
    }

    public function actionDeletePpt() {
        $fileName = $_GET['ppt'];
        $dir = $_GET['pdir'];
        $result = 0;               //不显示提示
        if (!isset(Yii::app()->session['ppt2del']) ||
                Yii::app()->session['ppt2del'] != $fileName) {
            Yii::app()->session['ppt2del'] = $fileName;
            $file = $dir . $fileName;
            if (file_exists(iconv('utf-8', 'gb2312', $file)))
                unlink(iconv('utf-8', 'gb2312', $file));
            Resourse::model()->delName($fileName);
            $result = "删除成功！";
        }
        $courseID = Yii::app()->session['courseID'];
        $courseName = Yii::app()->session['courseName'];
        $createPerson = Yii::app()->session['createPerson'];
        $this->render("pptLst", array(
            'courseID' => $courseID,
            'courseName' => $courseName,
            'createPerson' => $createPerson,
            'pdir' => $dir,
            'result' => $result,
        ));
    }

    public function actionLookPpt() {
        $fileDir = $_GET['ppt'];
        $pdir = $_GET['pdir'];
        $dir = $pdir . $fileDir;
        return $this->render('lookPpt', [
                    'pdir' => $pdir,
                    'dir' => $dir,
        ]);
    }

    public function actionVideoLst() {
        $courseID = $_GET ['courseID'];
        $courseName = $_GET ['courseName'];
        $createPerson = $_GET ['createPerson'];
        $vdir = $_GET ['vdir'];
        Yii::app()->session['vdir'] = $vdir;
        Yii::app()->session['courseID'] = $courseID;
        Yii::app()->session['courseName'] = $courseName;
        Yii::app()->session['createPerson'] = $createPerson;
        $this->render("videoLst", array(
            'courseID' => $courseID,
            'courseName' => $courseName,
            'createPerson' => $createPerson,
            'vdir' => $vdir
        ));
    }

    public function actionVideoTable() {
        $vdir = $_GET ['vdir'];
        return $this->renderPartial('videoTable', [
                    'vdir' => $vdir,
        ]);
    }

    public function actionAddVideo() {
        $dir = $_GET['vdir'];
        $result = "上传失败!";
        if (!isset($_FILES["file"])) {
            echo "请选择文件！";
            return;
        }
        $sqlVideo = Resourse::model()->findAll("type = 'video'");
        foreach ($sqlVideo as $v){
            if($v['name'] == $_FILES["file"]["name"]){
                echo "该文件已存在，如需重复使用请改名重新上传！";
                return;
            }
        }
//        if ($_FILES["file"]["type"] == "video/mp4" || $_FILES["file"]["type"] == "application/octet-stream") {
        if ($_FILES["file"]["type"] == "video/mp4") {
            if ($_FILES["file"]["error"] > 0) {
                $result = "Return Code: " . $_FILES["file"]["error"];
            } else {
                $oldName = $_FILES["file"]["name"];
                $newName = Tool::createID() . "." . pathinfo($oldName, PATHINFO_EXTENSION);
                move_uploaded_file($_FILES["file"]["tmp_name"], $dir . iconv("UTF-8", "gb2312", $newName));
                Resourse::model()->insertRelaVideo($newName, $oldName);
                $result = "上传成功！";
            }
        } else {
            $result = "请上传正确类型的文件！";
        }
        echo $result;
    }

    public function actionDeleteVideo() {
        $courseID =Yii::app()->session['courseID'] ;
        $courseName=Yii::app()->session['courseName'];
        $createPerson =Yii::app()->session['createPerson'];
        $fileName = $_GET['video'];
        $dir = $_GET['vdir'];
        $file = $dir . $fileName;
        $result = "删除成功！";
        Resourse::model()->delName($fileName);
        if (file_exists(iconv('utf-8', 'gb2312', $file)))
            unlink(iconv('utf-8', 'gb2312', $file));
        
        return $this->render('videoLst', [
                    'result' => $result,
                    'courseID'=>$courseID,
                     'courseName'=>$courseName,
                     'vdir' => $dir,
                     'createPerson'=>$createPerson
        ]);
    }

    public function actionLookVideo() {
        $file = $_GET['video'];
        $vdir = $_GET['vdir'];
        return $this->render('lookVideo', [
                    'vdir' => $vdir,
                    'file' => $vdir . $file,
        ]);
    }

    public function actionNoticeLst() {
        $result = Notice::model()->findNotice();
        $noticeRecord = $result ['noticeLst'];
        $pages = $result ['pages'];
       $this->render('noticeLst',  array('noticeRecord'=>$noticeRecord,'pages'=>$pages));
   }
   public function ActionDeleteNotice()
     {
       if (isset($_POST['checkbox'])) {
            $userIDlist = $_POST['checkbox'];
            foreach ($userIDlist as $v) {
                Notice::model()->delNotice($v);
            }
            $result = Notice::model()->findNotice();
            $noticeRecord = $result ['noticeLst'];
            $pages = $result ['pages'];
            $this->render('noticeLst',  array('noticeRecord'=>$noticeRecord,'pages'=>$pages));
        }
         $id = $_GET['id'];
         Notice::model()->deleteAll("id='$id'");
         $result=Notice::model()->findNotice();
        $noticeRecord=$result ['noticeLst'];
        $pages = $result ['pages'];
       $this->render('noticeLst',  array('noticeRecord'=>$noticeRecord,'pages'=>$pages));
     }
     public function ActionNoticeContent(){
         $result=0;
        if(isset($_GET['action'])&&$_GET['action']=='edit'){
            $result=1;
        }
       $id = $_GET['id'];
       $noticeRecord=Notice::model()->find("id= '$id'");
       $this->render('noticeContent',  array('noticeRecord'=>$noticeRecord,'result'=>$result));
     }

    public function actionSchedule() {
        if (isset($_POST['which'])) {
            $type = $_POST['which'];
            $value = $_POST['value'];
            if ($type == "className") {
                $class = TbClass::model()->findAll("className='$value'");
                if (empty($class) || $value == "") {
                    return $this->render('schedule', ['noResult' => '1']);
                } else {
                    $class_course = array();
                    foreach ($class as $v) {
                        $courseID = $v['currentCourse'];
                        $sqlCourse = Course::model()->find("courseID='$courseID'");
                        $class_v = array("classID" => $v['classID'], "className" => $v['className'], "currentCourse" => $v['currentCourse'], "currentLesson" => $v['currentLesson'], "courseName" => $sqlCourse['courseName']);
                        array_push($class_course, $class_v);
                    }
                    return $this->render('schedule', ['class_search' => $class_course]);
                }
            } else if ($type == "teaName") {
                $teacher = Teacher::model()->findAll("userName = '$value'");
                if (empty($teacher) || $value == "") {
                    return $this->render('schedule', ['noResult' => '1']);
                } else {
                    return $this->render('schedule', ['teacher_search' => $teacher]);
                }
            }
        }


        $classsql = "SELECT * FROM tb_class";
        $array_classLst = Tool::pager($classsql,10);
        $class = $array_classLst['list'];
        $classPages = $array_classLst['pages'];
        
        $class_course = array();
        foreach ($class as $v) {
            $courseID = $v['currentCourse'];
            $sqlCourse = Course::model()->find("courseID='$courseID'");
            $class_v = array("classID" => $v['classID'], "className" => $v['className'], "currentCourse" => $v['currentCourse'], "currentLesson" => $v['currentLesson'], "courseName" => $sqlCourse['courseName']);
            array_push($class_course, $class_v);
        }
        $teasql = "SELECT * FROM teacher WHERE is_delete = '0'";
        $array_teaLst = Tool::pager($teasql,10);
        $teaLst = $array_teaLst['list'];
        $teaPages = $array_teaLst['pages'];
        
        return $this->render('schedule', ['class_pages'=>$classPages,'tea_pages'=>$teaPages,'teacher' => $teaLst, 'class' => $class_course]);
    }

    public function actionScheduleDetil() {
        if (isset($_GET['teacherId'])) {
            unset($_GET['classId']);
            Yii::app()->session['teacherId'] = $_GET['teacherId'];
            $userID = $_GET['teacherId'];
            $sqlTeacher = Teacher::model()->find("userID = '$userID'");
            //查询老师课程表
            $teaResult = ScheduleTeacher::model()->findAll("userID='$userID'");
            return $this->render('scheduleDetil', ['teacher' => $sqlTeacher, 'result' => $teaResult]);
        } else if (isset($_GET['classId'])) {
            unset($_GET['teacherId']);
            Yii::app()->session['classId'] = $_GET['classId'];
            $classID = $_GET['classId'];
            $sqlClass = TbClass::model()->find("classID='$classID'");
            $classResult = ScheduleClass::model()->findAll("classID='$classID'");
            return $this->render('scheduleDetil', ['class' => $sqlClass, 'result' => $classResult]);
        }
    }

    public function actionEditSchedule() {
        $sequence = $_GET['sequence'];
        $day = $_GET['day'];

        if (isset($_GET['teacherID'])) {
            $teacherID = Yii::app()->session['teacherId'];
            $sql = "SELECT * FROM schedule_teacher WHERE userID = '$teacherID' AND sequence = '$sequence' AND day = '$day'";
            $sqlSchedule = Yii::app()->db->createCommand($sql)->query()->read();

            if ($sqlSchedule == "") {
                //增
                $courseInfo = "";
                if (isset($_POST['in1']) && !$_POST['in1'] == "") {
                    $courseInfo = $_POST['in1'];
                }
                if (isset($_POST['in2']) && !$_POST['in2'] == "") {
                    $courseInfo = $courseInfo . "&&" . $_POST['in2'];
                }
                if (isset($_POST['in3']) && !$_POST['in3'] == "") {
                    $courseInfo = $courseInfo . "&&" . $_POST['in3'];
                }
                if ($courseInfo != "") {
                    $sql = "INSERT INTO `schedule_teacher`(`userID`, `sequence`, `day`, `courseInfo`) VALUES ('$teacherID',$sequence,$day,'$courseInfo') ";
                    Yii::app()->db->createCommand($sql)->query();
                }
            } else if(isset($_POST['in1']) || isset($_POST['in2']) || isset($_POST['in3'])){
                //改
                $courseInfo = "";
                if (isset($_POST['in1']) && !$_POST['in1'] == "") {
                    $courseInfo = $_POST['in1'];
                }
                if (isset($_POST['in2']) && !$_POST['in2'] == "") {
                    $courseInfo = $courseInfo . "&&" . $_POST['in2'];
                }
                if (isset($_POST['in3']) && !$_POST['in3'] == "") {
                    $courseInfo = $courseInfo . "&&" . $_POST['in3'];
                }
                if ($courseInfo == "") {
                    //删
                    $sql = "DELETE FROM `schedule_teacher` WHERE userID ='$teacherID' and sequence =$sequence and day = $day";
                    Yii::app()->db->createCommand($sql)->query();
                } else {
                    $sql = "UPDATE `schedule_teacher` SET courseInfo ='$courseInfo' WHERE userID ='$teacherID' and sequence =$sequence and day = $day";
                    Yii::app()->db->createCommand($sql)->query();
                }
            }
        } else if (isset($_GET['classID'])) {
            $classID = $_GET['classID'];
            $teacherID = Yii::app()->session['classId'];
            $sql = "SELECT * FROM schedule_class WHERE classID = '$classID' AND sequence = '$sequence' AND day = '$day'";
            $sqlSchedule = Yii::app()->db->createCommand($sql)->query()->read();
            if ($sqlSchedule == "") {
                //增
                $courseInfo = "";
                if (isset($_POST['in1']) && !$_POST['in1'] == "") {
                    $courseInfo = $_POST['in1'];
                }
                if (isset($_POST['in2']) && !$_POST['in2'] == "") {
                    $courseInfo = $courseInfo . "&&" . $_POST['in2'];
                }
                if (isset($_POST['in3']) && !$_POST['in3'] == "") {
                    $courseInfo = $courseInfo . "&&" . $_POST['in3'];
                }
                if ($courseInfo != "") {
                    $sql = "INSERT INTO `schedule_class`(`classID`, `sequence`, `day`, `courseInfo`) VALUES ('$classID',$sequence,$day,'$courseInfo') ";
                    Yii::app()->db->createCommand($sql)->query();
                }
            } else if(isset($_POST['in1']) || isset($_POST['in2']) || isset($_POST['in3'])){
                //改
                $courseInfo = "";
                if (isset($_POST['in1']) && !$_POST['in1'] == "") {
                    $courseInfo = $_POST['in1'];
                }
                if (isset($_POST['in2']) && !$_POST['in2'] == "") {
                    $courseInfo = $courseInfo . "&&" . $_POST['in2'];
                }
                if (isset($_POST['in3']) && !$_POST['in3'] == "") {
                    $courseInfo = $courseInfo . "&&" . $_POST['in3'];
                }
                if ($courseInfo == "") {
                    //删
                    $sql = "DELETE FROM `schedule_class` WHERE classID ='$classID' and sequence =$sequence and day = $day";
                    Yii::app()->db->createCommand($sql)->query();
                } else {
                    $sql = "UPDATE `schedule_class` SET courseInfo ='$courseInfo' WHERE classID ='$classID' and sequence =$sequence and day = $day";
                    Yii::app()->db->createCommand($sql)->query();
                }
            }
        }
        return $this->renderPartial('editSchedule', ['result' => $sqlSchedule]);
    }
    
    public function actionEditScheduleOne() {
        $sequence = $_GET['sequence'];
        $day = $_GET['day'];

        if (isset($_GET['teacherID'])) {
            $teacherID = Yii::app()->session['teacherId'];
            $sql = "SELECT * FROM schedule_teacher WHERE userID = '$teacherID' AND sequence = '$sequence' AND day = '$day'";
            $sqlSchedule = Yii::app()->db->createCommand($sql)->query()->read();

            if ($sqlSchedule == "") {
                //增
                $courseInfo = "";
                if (isset($_POST['hour1']) && !$_POST['hour1'] == "" && isset($_POST['min1']) && !$_POST['min1'] == "") {
                    $courseInfo = $_POST['hour1'].":".$_POST['min1'];
                    $courseInfo = $courseInfo . "&&至";
                }
                
                if (isset($_POST['hour2']) && !$_POST['hour2'] == "" && isset($_POST['min2']) && !$_POST['min2'] == "") {
                    $courseInfo = $courseInfo . "&&" . $_POST['hour2'].":".$_POST['min2'];
                }
                if ($courseInfo != "") {
                    $sql = "INSERT INTO `schedule_teacher`(`userID`, `sequence`, `day`, `courseInfo`) VALUES ('$teacherID',$sequence,$day,'$courseInfo') ";
                    Yii::app()->db->createCommand($sql)->query();
                }
            } else if(isset($_POST['hour1']) || isset($_POST['min1']) || isset($_POST['hour2']) || isset($_POST['min2'])){
                //改
                $courseInfo = "";
                if (isset($_POST['hour1']) && !$_POST['hour1'] == "" && isset($_POST['min1']) && !$_POST['min1'] == "") {
                    $courseInfo = $_POST['hour1'].":".$_POST['min1'];
                    $courseInfo = $courseInfo . "&&至";
                }
                
                if (isset($_POST['hour2']) && !$_POST['hour2'] == "" && isset($_POST['min2']) && !$_POST['min2'] == "") {
                    $courseInfo = $courseInfo . "&&" . $_POST['hour2'].":".$_POST['min2'];
                }
                if ($courseInfo == "") {
                    //删
                    $sql = "DELETE FROM `schedule_teacher` WHERE userID ='$teacherID' and sequence =$sequence and day = $day";
                    Yii::app()->db->createCommand($sql)->query();
                } else {
                    $sql = "UPDATE `schedule_teacher` SET courseInfo ='$courseInfo' WHERE userID ='$teacherID' and sequence =$sequence and day = $day";
                    Yii::app()->db->createCommand($sql)->query();
                }
            }
        } else if (isset($_GET['classID'])) {
            $classID = $_GET['classID'];
            $teacherID = Yii::app()->session['classId'];
            $sql = "SELECT * FROM schedule_class WHERE classID = '$classID' AND sequence = '$sequence' AND day = '$day'";
            $sqlSchedule = Yii::app()->db->createCommand($sql)->query()->read();
            if ($sqlSchedule == "") {
                //增
                $courseInfo = "";
                if (isset($_POST['hour1']) && !$_POST['hour1'] == "" && isset($_POST['min1']) && !$_POST['min1'] == "") {
                    $courseInfo = $_POST['hour1'].":".$_POST['min1'];
                    $courseInfo = $courseInfo . "&&至";
                }
                
                if (isset($_POST['hour2']) && !$_POST['hour2'] == "" && isset($_POST['min2']) && !$_POST['min2'] == "") {
                    $courseInfo = $courseInfo . "&&" . $_POST['hour2'].":".$_POST['min2'];
                }
                if ($courseInfo != "") {
                    $sql = "INSERT INTO `schedule_class`(`classID`, `sequence`, `day`, `courseInfo`) VALUES ('$classID',$sequence,$day,'$courseInfo') ";
                    Yii::app()->db->createCommand($sql)->query();
                }
            } else if(isset($_POST['hour1']) || isset($_POST['min1']) || isset($_POST['hour2']) || isset($_POST['min2'])){
                //改
                $courseInfo = "";
                if (isset($_POST['hour1']) && !$_POST['hour1'] == "" && isset($_POST['min1']) && !$_POST['min1'] == "") {
                    $courseInfo = $_POST['hour1'].":".$_POST['min1'];
                    $courseInfo = $courseInfo . "&&至";
                }
                
                if (isset($_POST['hour2']) && !$_POST['hour2'] == "" && isset($_POST['min2']) && !$_POST['min2'] == "") {
                    $courseInfo = $courseInfo . "&&" . $_POST['hour2'].":".$_POST['min2'];
                }
                if ($courseInfo == "") {
                    //删
                    $sql = "DELETE FROM `schedule_class` WHERE classID ='$classID' and sequence =$sequence and day = $day";
                    Yii::app()->db->createCommand($sql)->query();
                } else {
                    $sql = "UPDATE `schedule_class` SET courseInfo ='$courseInfo' WHERE classID ='$classID' and sequence =$sequence and day = $day";
                    Yii::app()->db->createCommand($sql)->query();
                }
            }
        }
        return $this->renderPartial('editScheduleOne', ['result' => $sqlSchedule]);
    }
    
    
    
    public function actionKey(){
        $studentNumber=Tool::getStudentLimitNumber();
        if(!empty($_FILES['file']['name'])){
            $tmp_file=$_FILES['file']['tmp_name'];
            $file_types=explode(".",$_FILES['file']['type']);
            $file_type=$file_types[count($file_types)-1];
            
            //判别是不是excel文件
            $file = $_FILES['file'];
            if(strtolower($file_type)!="sheet" && strtolower($file_type)!="ms-excel"){
                $result="不是Excel文件";
                $this->render('key',['result'=>$result]);
               
            }else if(Tool::detectUploadFileMIME($file)){
                //解析文件并存入数据库逻辑
                /*设置上传路径*/
                $savePath=dirname(Yii::app()->BasePath).'\\public\\upload\\excel\\';
                /* 以时间来命名上传的文件*/
                $str=date('Ymdhis');
                $file_name="Key".$str.".xls";
                if(!copy($tmp_file,$savePath.$file_name)){
                    $result='上传失败';
                    $this->render('key',['result'=>$result]);
                }else{
                    $res=Tool::excelreadToArray($savePath.$file_name,$file_type);
                    
                    //判断导入逻辑 分离出导入成功array_success和导入失败array_fail
                    unlink($savePath.$file_name);
                    $array_fail=array();
                    $array_success=array();
                    $array_successTea=array();
                    $array_failTea=array();
                    $stu_failTea=array();
                    $stu_fail=array();
                    $style_flag=0;
                    $result1="";
                    $flag=0;
                    $flagClass=0;
                    $flagTea=0;
                    $coun=0;
                    $counTea=0;
                    $resultExl='no';
                    $sql="SELECT * FROM `course` ORDER BY `course`.`courseID` DESC";
                    $aCourse=Yii::app()->db->createCommand($sql)->query();
                    foreach($res as $k => $v){
                        //判断第一行表格头内容
                        if($k==1){
                            if(isset($v[0])){
                                if($v[0]!="科目"){
                                    $result="表格A列名应为“科目”！";
                                    $flag=1;
                                    $this->render('key',['result'=>$result]);
                                    break;
                                }
                            }else{
                                $result="表格缺少A列“科目”";
                                $flag=1;
                                $this->render('key',['result'=>$result]);
                                break;
                            }
                            if (isset($v [1])) {
                                if ($v [1] != "课时") {
                                    $result = "表格B列名应为“课时”！";
                                    $flag = 1;
                                    $this->render('key', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少B列“课时”";
                                $flag = 1;
                                $this->render('key', ['result' => $result]);
                                break;
                            }
                            if(isset($v[2])){
                                if($v[2]!="班级"){
                                    $result="表格C列名应为“班级”！";
                                    $flag=1;
                                    $this->render('key',['result'=>$result]);
                                    break;
                                }
                            }else{
                                $result="表格缺少C列“班级”";
                                $flag=1;
                                $this->render('key',['result'=>$result]);
                                break;
                            }
                            
                            if(isset($v[4])){
                                if($v[4]!="学号"){
                                    $result="表格E列名应为“学号”！";
                                    $flag=1;
                                    $this->render('key',['result'=>$result]);
                                    break;
                                }
                            }else{
                                $result="表格缺少E列“学号”";
                                $flag=1;
                                $this->render('key',['result'=>$result]);
                                break;
                            }
                            if(isset($v[5])){
                                if($v[5]!="姓名"){
                                    $result="表格F列名应为“姓名”！";
                                    $flag=1;
                                    $this->render('key',['result'=>$result]);
                                    break;
                                }
                            }else{
                                $flag=1;
                                $result="表格缺少F列“姓名”";
                                $this->render('key',['result'=>$result]);
                                break;
                            }
                            if(isset($v[6])){
                                if($v[6]!="性别"){
                                    $result = "表格G列名应为“性别”！";
                                    $flag = 1;
                                    $this->render('key', ['result' => $result]);
                                    break;
                                }
                            }else {
                                $result = "表格缺少G列“性别”";
                                $flag = 1;
                                $this->render('key', ['result' => $result]);
                                break;
                            }
                            if (isset($v [7])) {
                                if ($v [7] != "年龄") {
                                    $result = "表格H列名应为“年龄”！";
                                    $flag = 1;
                                    $this->render('key', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少H列“年龄”";
                                $flag = 1;
                                $this->render('key', ['result' => $result]);
                                break;
                            }
                            
                            if (isset($v [8])) {
                                if ($v [8] != "联系邮箱") {
                                    $result = "表格I列名应为“联系邮箱”！";
                                    $flag = 1;
                                    $this->render('key', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少I列“联系邮箱”";
                                $flag = 1;
                                $this->render('key', ['result' => $result]);
                                break;
                            }
                            if (isset($v [9])) {
                                if ($v [9] != "联系电话") {
                                    $result = "表格J列名应为“联系电话”！";
                                    $flag = 1;
                                    $this->render('key', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少J列“联系电话”";
                                $flag = 1;
                                $this->render('key', ['result' => $result]);
                                break;
                            }
                            if (isset($v [11])) {
                                if ($v [11] != "老师姓名") {
                                    $result = "表格L列名应为“老师姓名”！";
                                    $flag = 1;
                                    $this->render('key', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少L列“老师姓名”";
                                $flag = 1;
                                $this->render('key', ['result' => $result]);
                                break;
                            }
                            if (isset($v [12])) {
                                if ($v [12] != "工号") {
                                    $result = "表格M列名应为“工号”！";
                                    $flag = 1;
                                    $this->render('key', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少M列“工号”";
                                $flag = 1;
                                $this->render('key', ['result' => $result]);
                                break;
                            }
                            if (isset($v [13])) {
                                if ($v [13] != "性别") {
                                    $result = "表格N列名应为“性别”！";
                                    $flag = 1;
                                    $this->render('key', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少N列“性别”";
                                $flag = 1;
                                $this->render('key', ['result' => $result]);
                                break;
                            }
                            if (isset($v [14])) {
                                if ($v [14] != "年龄") {
                                    $result = "表格O列名应为“年龄”！";
                                    $flag = 1;
                                    $this->render('key', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少O列“年龄”";
                                $flag = 1;
                                $this->render('key', ['result' => $result]);
                                break;
                            }
                            
                            if (isset($v [15])) {
                                if ($v [15] != "所属部门") {
                                    $result = "表格P列名应为“所属部门”！";
                                    $flag = 1;
                                    $this->render('key', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少P列“所属部门”";
                                $flag = 1;
                                $this->render('key', ['result' => $result]);
                                break;
                            }
                            if (isset($v [16])) {
                                if ($v [16] != "联系电话") {
                                    $result = "表格Q列名应为“联系电话”！";
                                    $flag = 1;
                                    $this->render('key', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少Q列“联系电话”";
                                $flag = 1;
                                $this->render('key', ['result' => $result]);
                                break;
                            }
                            if (isset($v [17])) {
                                if ($v [17] != "联系邮箱") {
                                    $result = "表格R列名应为“联系邮箱”！";
                                    $flag = 1;
                                    $this->render('key', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少R列“联系邮箱”";
                                $flag = 1;
                                $this->render('key', ['result' => $result]);
                                break;
                            }
                            if (isset($v [18])) {
                                if ($v [18] != "学校") {
                                    $result = "表格S列名应为“学校”！";
                                    $flag = 1;
                                    $this->render('key', ['result' => $result]);
                                    break;
                                }
                            } else {
                                $result = "表格缺少S列“学校”";
                                $flag = 1;
                                $this->render('key', ['result' => $result]);
                                break;
                            }
                        }
                        if($k==2){
                            if($v[0]==""|| ctype_space($v[0])){
                                $result="科目名不能为空！";
                                $flag=1;
                                $this->render('key',['result'=>$result]);
                                break;
                            }else{
                                $courseName=$v[0];
                                $allCourse=Course::model()->findAll();
                                if(empty($allCourse)){
                                    $flagTea=1;
                                }
                                foreach($allCourse as $a){
                                    if($a['courseName']==$courseName){
                                        $resultExl='have_same_course';
                                        $data['courseID']=$a['courseID'];
                                        $dataTea['courseID']=$a['courseID'];
                                        $flagTea=0;
                                        break;
                                    }
                                    $flagTea=1;
                                }
                            }
                            if ($v [1]==""|| ctype_space($v [1]) || $v [1]<1 ||$v [1]>99 || !preg_match( "/^[0-9]+$/",$v [1])) {
                                    $result = "课时数不能为空且为1-99之间的整数！";
                                    $flag = 1;
                                    $this->render('key', ['result' => $result]);
                                    break;
                                }else{                                
                                    if($flagTea==1){
                                    $resultExl=Course::model()->insertCourse($courseName,0);
                                }
                                    $courseNumber=$v [1];
                                    if($resultExl==1){
                                        Yii::app()->session['insert_course']=$courseName;
                                        $sql="SELECT MAX(courseID) AS id FROM course";
                                        $max_id=Yii::app()->db->createCommand($sql)->query()->read();
                                        $courseID=$max_id['id'];
                                        $data['courseID']=$courseID;
                                        $dataTea['courseID']=$courseID;
                                        $classes=TbClass::model()->findall("currentCourse='$courseID'");
                                        if(empty($classes)){
                                            for($i=1;$i<($courseNumber+1);$i++){
                                                Lesson::model()->insertLesson("第".$i."课",$courseID,0,0);
                                            }
                                     }//   else{
//                                            for($i=1;$i<($courseNumber+1);$i++){
//                                                foreach($classes as $class){
//                                                    Lesson::model()->insertLesson("第".$i."课",$courseID,0,$class['classID']);
//                                                }
//                                            }
//                                        }
                                    }
                                }
                                if($v[2]==""|| ctype_space($v[2])){
                                $result="班级名不能为空！";
                                $flag=1;
                                $this->render('key',['result'=>$result]);
                                break;
                            }else{
                                $dataTea['class']=$v[2];
                                $data ['className']=$v[2];
                                $classFlag=0;
                                $tbClassAll=TbClass::model()->findall('className=?',array($v[2]));
                                if(count($tbClassAll)>0){
                                foreach($tbClassAll as $allClass){
                                    if($allClass['currentCourse']==$dataTea['courseID']){
                                        $classFlag=1;
                                    }
                                }
                                if($classFlag==0){
                                    $result="班级已存在并被分配到已有的科目中，请重新创建班级！";
                                    $flag=1;
                                    $this->render('key',['result'=>$result]);
                                    break;
                                }
                                }
                                    if(!Tool::excelreadClass($v[2])){
                                        $flagClass=1;
                                        $classID=TbClass::model()->insertClass($v[2],$data['courseID']);
                                        $lessons=Lesson::model()->findall('classID=? and courseID=?',array(0,$data['courseID']));
                                        foreach($lessons as $lesson){
                                            Lesson::model()->insertLesson($lesson['lessonName'],$lesson['courseID'],0,$classID);
                                        }
                                    }else{
                                        $thisClassTea=new TbClass();
                                        $oldClassTea=$thisClassTea->findall('className=?',array($v[2]));
                                        foreach($oldClassTea as $oldClassID){
                                            $classID=$oldClassID['classID'];
                                        }
                                    }
                            }
                            if(isset($_POST['checkbox'])){
                                $style_flag=1;
                            }
                            }
                            
                            if($k==2||$k==3){
                                $array_successTea=array();
                                $dataTea['userName']=$v[11];
                                $dataTea['uid']=$v[12];
                                $dataTea['sex']=$v[13];
                                $dataTea['age']=$v[14];
                                $dataTea['department']=$v[15];
                                $dataTea['phone_number']=$v[16];
                                $dataTea['mail_address']=$v[17];
                                $dataTea['school']=$v[18];
                                    
                            
                                $classTeaID=TbClass::model()->findall('className=?',array($dataTea['class']));
                                if(preg_match("/\s/", $dataTea['userName'])){
                                 $dataTea['userName'] = str_replace(' ','',$dataTea['userName']);
                            }
                                if(count($classTeaID)>0){
                                    foreach($classTeaID as $tea){
                                        $teaID=$tea['classID'];
                                    }
                                    $flagTeaID=  TeacherClass::model()->findall('teacherID=? and classID=?',array($dataTea['uid'],$teaID));
                                    
                                }
                                if($k==2 || $dataTea ['uid']!="" || $dataTea ['userName']!="" || $dataTea ['sex']!=""){                            
                                if($dataTea['uid']=="" || ctype_space($dataTea['uid'])){
                                    $result1="工号不能为空";
                                    $fixed="需手动添加";
                                    $stu_failTea=array($result1,$dataTea['uid'],$dataTea['userName'],$fixed,$dataTea);
                                    array_push($array_failTea,$stu_failTea);
                                }else if(count($flagTeaID)>0){
                                    $result1="该老师已分配至相应的班级中";
                                    $fixed="需手动添加";
                                    $stu_failTea=array($result1,$dataTea['uid'],$dataTea['userName'],$fixed,$dataTea);
                                    array_push($array_failTea,$stu_failTea);
                                }else if(!Tool::checkID($dataTea['uid'])){
                                    $result1="工号必须由字母和数字组成！";
                                    $fixed="需手动添加";
                                    $stu_failTea=array($result1,$dataTea['uid'],$dataTea['userName'],$fixed,$dataTea);
                                    array_push($array_failTea,$stu_failTea);
                                }else if($dataTea['userName']=="" || ctype_space($dataTea['userName']) || !preg_match("/^[A-Za-z_\x80-\xff\s]+$/",$dataTea['userName'])){
                                    $result1="姓名不能为空且由汉字或英文组成！";
                                    $fixed="需手动添加";
                                    $stu_failTea=array($result1,$dataTea['uid'],$dataTea['userName'],$fixed,$dataTea);
                                    array_push($array_failTea,$stu_failTea);
                                }else if($dataTea['sex']!="男" && $dataTea['sex']!="女"){
                                    $result1="性别未填写或写错！";
                                    $fixed="性别默认为“女”";
                                    $dataTea['sex']="女";
                                    $stu_failTea=array($result1,$dataTea['uid'],$dataTea['userName'],$fixed,$dataTea);
                                    array_push($array_failTea,$stu_failTea);
                                    if($dataTea['age']<1 ||$dataTea['age']>99 || !preg_match( "/^[0-9]+$/",$dataTea['age'])){
                                        $result1="年龄应为1-99之间的整数！";
                                        $fixed="年龄默认为99";
                                        $dataTea['age']=99;
                                        $stu_failTea=array($result1,$dataTea['uid'],$dataTea['userName'],$fixed,$dataTea);
                                        array_push($array_failTea,$stu_failTea);
                                        if(!preg_match("/^1[34578]{1}\d{9}$/",$dataTea ['phone_number']) && $dataTea ['phone_number']!=""){
                                            $result1 = "手机号码格式不正确";
                                            $fixed = "手机号码已置空";
                                            $dataTea['phone_number'] = "";
                                            $stu_failTea=array($result1,$dataTea['uid'],$dataTea['userName'],$fixed,$dataTea);
                                            array_push($array_failTea,$stu_failTea);
                                            if(!Tool::checkMailAddress($dataTea['mail_address']) && $dataTea['mail_address']!=""){
                                                $result1="邮箱格式不正确！";
                                                $fixed="邮箱信息已置空";
                                                $dataTea['mail_address']="";
                                                $stu_failTea=array($result1,$dataTea['uid'],$dataTea['userName'],$fixed,$dataTea);
                                                array_push($array_failTea,$stu_failTea);
                                            }
                                        }
                                    }
                                    array_push($array_successTea,$dataTea);
                                }else if($dataTea['age']<1 ||$dataTea['age']>99 || !preg_match( "/^[0-9]+$/",$dataTea['age'])){
                                    $result1="年龄应为1-99之间的整数！";
                                    $fixed="年龄默认为99";
                                    $dataTea['age']=99;
                                    $stu_failTea=array($result1,$dataTea['uid'],$dataTea['userName'],$fixed,$dataTea);
                                    array_push($array_failTea,$stu_failTea);
                                    if(!preg_match("/^1[34578]{1}\d{9}$/",$dataTea ['phone_number'])&&$dataTea ['phone_number']!=""){
                                        $result1 = "手机号码格式不正确";
                                        $fixed = "手机号码已置空";
                                        $dataTea['phone_number'] = "";
                                        $stu_failTea=array($result1,$dataTea['uid'],$dataTea['userName'],$fixed,$dataTea);
                                        array_push($array_failTea,$stu_failTea);
                                        if(!Tool::checkMailAddress($dataTea['mail_address']) && $dataTea['mail_address']!=""){
                                            $result1="邮箱格式不正确！";
                                            $fixed="邮箱信息已置空";
                                            $dataTea['mail_address']="";
                                            $stu_failTea=array($result1,$dataTea['uid'],$dataTea['userName'],$fixed,$dataTea);
                                            array_push($array_failTea,$stu_failTea);
                                        }
                                    }
                                    array_push($array_successTea,$dataTea);
                                }else if(!preg_match("/^1[34578]{1}\d{9}$/",$dataTea ['phone_number']) && $dataTea ['phone_number']!="") {
                                    $result1 = "手机号码格式不正确";
                                    $fixed = "手机号码已置空";
                                    $dataTea['phone_number'] = "";
                                    $stu_failTea=array($result1,$dataTea['uid'],$dataTea['userName'],$fixed,$dataTea);
                                    array_push($array_failTea,$stu_failTea);
                                    if(!Tool::checkMailAddress($dataTea['mail_address'])&&$dataTea['mail_address']!=""){
                                        $result1="邮箱格式不正确！";
                                        $fixed="邮箱信息已置空";
                                        $dataTea['mail_address']="";
                                        $stu_failTea=array($result1,$dataTea['uid'],$dataTea['userName'],$fixed,$dataTea);
                                        array_push($array_failTea,$stu_failTea);
                                    }
                                    array_push($array_successTea,$dataTea);
                                }else if(!Tool::checkMailAddress($dataTea['mail_address']) && $dataTea['mail_address']!=""){
                                    $result1="邮箱格式不正确！";
                                    $fixed="邮箱信息已置空";
                                    $dataTea['mail_address']="";
                                    $stu_failTea=array($result1,$dataTea['uid'],$dataTea['userName'],$fixed,$dataTea);
                                    array_push($array_failTea,$stu_failTea);
                                    array_push($array_successTea,$dataTea);
                                }else{
                                    array_push($array_successTea,$dataTea);
                                }
                                if($style_flag==1 && $k==2){
                                    error_log(2);
                                    foreach($array_successTea as $success_teaID){
                                        error_log(3);
                                        $successTeaID=strtoupper($success_teaID['uid']);
                                    $styleTea="A01";
                                    $oldExerciseTea=ClassExercise::model()->findall('create_person=?',array($styleTea));
                                    foreach($oldExerciseTea as $exerciseTea){
                                        error_log(4);
                                        $style_lesson=Lesson::model()->findall('lessonID=?',array($exerciseTea['lessonID']));
                                        foreach($style_lesson as $lesson_flag){
                                            $class_number=$lesson_flag['number'];
                                        }
                                        $oldLessonTea=Lesson::model()->findall('classID=? and number=?',array($classID,$class_number));
                                        if(!empty($oldLessonTea)){
                                        foreach($oldLessonTea as $oldLesson){
                                            $oldLessonID=$oldLesson['lessonID'];
                                        }
                                        if($exerciseTea['type']=="look"){
                                            ClassExercise::model()->insertClassExercise($classID,$oldLessonID,$exerciseTea['title'],$exerciseTea['content'],
                                            $exerciseTea['type'],$successTeaID);
                                        }else if($exerciseTea['type']=="listen"){
                                            ClassExercise::model()->insertListen($classID,$oldLessonID,$exerciseTea['title'],$exerciseTea['content'],$exerciseTea['file_name'],
                                            $exerciseTea['file_path'],$exerciseTea['type'],$successTeaID,$exerciseTea['speed']);
                                        }else{
                                            ClassExercise::model()->insertKey($classID,$oldLessonID,$exerciseTea['title'],$exerciseTea['content'],
                                            $successTeaID,$exerciseTea['type'],$exerciseTea['speed'],$exerciseTea['repeatNum'],$exerciseTea['chosen_lib']);
                                        }
                                        }
                                    }
                                    }
                                }
                                
                                $count_successTea=Tool::excelreadTeaToDatabase($array_successTea);
                            $counTea+=$count_successTea;
                            }
                            }
                                

                             //判断内容逻辑
                        if ($k > 1) {
                            $array_success = array();
                            $data ['uid'] = $v [4];
                            $data ['userName'] = $v [5];
                            $data ['sex'] = $v [6];
                            $data ['age'] = $v[7];
                            $data ['mail_address'] = $v[8];
                            $data ['phone_number'] = $v[9];
                            if(preg_match("/\s/", $data['userName'])){
                                 $data['userName'] = str_replace(' ','',$data['userName']);
                            }
                            if($k>=2 && $data ['uid']=="" && $data ['userName']=="" && $data ['sex']==""){
                                $k = $k-1;
//                                if(next($data)=="" ){
//                                   break; 
//                                }  
                                break;
                            }
                            if ($data ['uid'] === "" || ctype_space($data ['uid'])) {
                                $result = "学号不能为空";
                                $fixed = "需手动添加";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                            } else if (Tool::excelreadUserID($data ['uid'])) {
                                $result = "学号已存在！";
                                $fixed = "需手动添加";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                            } else if (!Tool::checkID($data ['uid'])) {
                                $result = "学号必须由字母和数字组成！";
                                $fixed = "需手动添加";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                            } else if ($data ['userName'] === "" || ctype_space($data ['userName']) || !preg_match("/^[A-Za-z_\x80-\xff\s]+$/",$data ['userName'])) {
                                $result = "姓名不能为空且由汉字或英文组成";
                                $fixed = "需手动添加";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                            } else if ($data['sex'] != "男" && $data['sex'] != "女") {
                                $result = "性别输入有误！";
                                $fixed = "性别默认为“女”";
                                $data['sex']="女";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                                if($data['age']<1 ||$data['age']>99 || !preg_match( "/^[0-9]+$/",$data['age'])){
                                    $result="年龄应为1-99之间的整数！";
                                    $fixed="年龄默认为99";
                                    $data['age']=99;
                                    $stu_fail=array($result,$data['uid'],$data['userName'],$fixed,$data);
                                    array_push($array_fail, $stu_fail);
                                    if(!preg_match("/^1[34578]{1}\d{9}$/",$data ['phone_number']) && $data ['phone_number']!=""){
                                        $result = "手机号码格式不正确";
                                        $fixed = "手机号码已置空";
                                        $data['phone_number'] = "";
                                        $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                        array_push($array_fail, $stu_fail);
                                        if(!Tool::checkMailAddress($data ['mail_address']) && $data ['mail_address']!=""){
                                            $result = "邮箱格式不正确";
                                            $fixed = "邮箱信息已置空";
                                            $data['mail_address'] = "";
                                            $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                            array_push($array_fail, $stu_fail);
                                        }
                                    }
                                    array_push($array_success, $data);
                                }
                            } else if((TbClass::model()->getStuNumsByClassName($data ['className']))>=$studentNumber){
                                $result = "班级人数超过".$studentNumber."人！";
                                $fixed = "请重新分班";
                                //$data['className'] = "";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                                //array_push($array_success, $data);
                            }  else if($data['age']<1 ||$data['age']>99 || !preg_match( "/^[0-9]+$/",$data['age'])){
                                    $result="年龄应为1-99之间的整数！";
                                    $fixed="年龄默认为99";
                                    $data['age']=99;
                                    $stu_fail=array($result,$data['uid'],$data['userName'],$fixed,$data);
                                    array_push($array_fail, $stu_fail);
                                    if(!preg_match("/^1[34578]{1}\d{9}$/",$data ['phone_number']) && $data ['phone_number']!=""){
                                        $result = "手机号码格式不正确";
                                        $fixed = "手机号码已置空";
                                        $data['phone_number'] = "";
                                        $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                        array_push($array_fail, $stu_fail);
                                        if(!Tool::checkMailAddress($data ['mail_address']) && $data ['mail_address']!=""){
                                            $result = "邮箱格式不正确";
                                            $fixed = "邮箱信息已置空";
                                            $data['mail_address'] = "";
                                            $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                            array_push($array_fail, $stu_fail);
                                        }
                                    }
                                    array_push($array_success, $data);
                                }else if (!preg_match("/^1[34578]{1}\d{9}$/",$data ['phone_number']) &&$data ['phone_number']!="") {
                                    $result = "手机号码格式不正确";
                                    $fixed = "手机号码已置空";
                                    $data['phone_number'] = "";
                                    $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                    array_push($array_fail, $stu_fail);
                                    if(!Tool::checkMailAddress($data ['mail_address'])&& $data ['mail_address']!=""){
                                        $result = "邮箱格式不正确";
                                        $fixed = "邮箱信息已置空";
                                        $data['mail_address'] = "";
                                        $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                        array_push($array_fail, $stu_fail);
                                    }
                                    array_push($array_success, $data);
                            }else if (!Tool::checkMailAddress($data ['mail_address'])&& $data ['mail_address']!="") {
                                $result = "邮箱格式不正确";
                                $fixed = "邮箱信息已置空";
                                $data['mail_address'] = "";
                                $stu_fail = array($result, $data['uid'], $data['userName'], $fixed, $data);
                                array_push($array_fail, $stu_fail);
                                array_push($array_success, $data);
                            } else {
                                array_push($array_success, $data);
                            }
                            //
                            $count_success = Tool::excelreadToDatabase($array_success);
                            $coun+=$count_success;
                            
                        }
                    }
                    if ($flag === 0) {
                        //$count_success = Tool::excelreadToDatabase($array_success);
                        $count_fail = $k - $coun - 1;
                        $this->render('key', ['className'=>$data['className'],'flagTea'=>$flagTea,'courseName'=>$courseName,'courseNumber'=>$courseNumber,'flagClass'=>$flagClass,'result' => $coun,'result1'=>$counTea, 'count_fail' => $count_fail, 'array_fail' => $array_fail,'array_failTea' => $array_failTea]);
                    }
             
            }
          }else
              {
                $result="检测到您上传的Excel文件存在异常，请重新编辑并上传！";
                $this->render('key',['result'=>$result]);
               }
        }else{
            $this->render('key');
        }
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
