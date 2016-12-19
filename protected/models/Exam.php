<?php

/**
 * This is the model class for table "exam".
 *
 * The followings are the available columns in table 'exam':
 * @property integer $suiteID
 * @property string $suiteName
 * @property string $begintime
 * @property string $endtime
 * @property string $createTime
 * @property integer $createPerson
 * 
 */
class Exam extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'exam';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('examID, examName, begintime, endtime, createTime, createPerson', 'required'),
            array('examID, createPerson', 'numerical', 'integerOnly' => true),
            array('examName', 'length', 'max' => 60),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('examID, examName, begintime, endtime, createTime, createPerson', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'examID' => 'exam',
            'examName' => 'exam Name',
            'begintime' => 'Begintime',
            'endtime' => 'Endtime',
            'createTime' => 'Create Time',
            'createPerson' => 'Create Person',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('examID', $this->examID);
        $criteria->compare('examName', $this->examName, true);
        $criteria->compare('begintime', $this->begintime, true);
        $criteria->compare('endtime', $this->endtime, true);
        $criteria->compare('createTime', $this->createTime, true);
        $criteria->compare('createPerson', $this->createPerson);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    //宋杰 2015-7-31 查找班级所有考试       
    public function getClassexamAll() {
        $userid = Yii::app()->session['userid_now'];
        $classID = Student::model()->findClassByStudentID($userid);
        $select = 'select begintime , endtime ,duration, exam.examID as examID, examName, class_exam.workID, class_exam.open as open from exam , class_exam';
        $condition = " where exam.examID = class_exam.examID and classId = '$classID'and open=1";
        $order = ' order by begintime desc';
        $sql = $select . $condition . $order;
        $result = Yii::app()->db->createCommand($sql)->query();
        return $result;
    }

    public function getAllExamByPage($pagesize) {
        $id = Yii::app()->session['userid_now'];
        $sql = "select * from exam where createPerson='$id' order by createTime desc";
        $result = Yii::app()->db->createCommand($sql)->query();
        $criteria = new CDbCriteria();
        $pages = new CPagination($result->rowCount);
        $pages->pageSize = $pagesize;
        $pages->applyLimit($criteria);

        $result = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize);
        $result->bindValue(':limit', $pages->pageSize);
        $examLst = $result->query();
        return ['examLst' => $examLst, 'pages' => $pages,];
    }

    public function getExamExerByType($examID, $type) {
        switch ($type) {
            case 'choice':
                $result = $this->getchoice($examID);
                break;
            case 'filling':
                $result = $this->getFilling($examID);
                break;
            case 'question':
                $result = $this->getQuestion($examID);
                break;
            case 'key':
                $result = $this->getKeyExer($examID);
                break;
            case 'listen':
                $result = $this->getListenExer($examID);
                break;
            default :
                $result = $this->getLookExer($examID);
        }
        return $result;
    }

    public function getchoice($examID) {
        $isExam = Yii::app()->session['isExam'];
        if ($isExam) {
            $suite_exer = 'exam_exercise';
            $findID = 'examID';
        } else {
            $suite_exer = 'suite_exercise';
            $findID = 'suiteID';
        }
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from exam_exercise where examID='$examID' and type='choice')";
        $select = "select * from choice";
        $sql = $select . $condition . $order;
        $result = Yii::app()->db->createCommand($sql)->query();
        return $result;
    }

    public function getchoice2($examID) {
        $isExam = Yii::app()->session['isExam'];
        if ($isExam) {
            $suite_exer = 'exam_exercise';
            $findID = 'examID';
        } else {
            $suite_exer = 'suite_exercise';
            $findID = 'suiteID';
        }
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from exam_exercise where examID='$examID' and type='choice')";
        $select = "select * from choice";
        $sql = $select . $condition . $order;
        $criteria = new CDbCriteria();
        $result = Yii::app()->db->createCommand($sql)->query();
        $pages = new CPagination($result->rowCount);
        $pages->pageSize = 8000;
        $pages->applyLimit($criteria);
        $result = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize);
        $result->bindValue(':limit', $pages->pageSize);
        $choiceLst = $result->query();

        return ['choiceLst' => $choiceLst, 'pages' => $pages,];
    }

    public function getFilling($examID) {
        $isExam = Yii::app()->session['isExam'];
        if ($isExam) {
            $suite_exer = 'exam_exercise';
            $findID = 'examID';
        } else {
            $suite_exer = 'suite_exercise';
            $findID = 'suiteID';
        }
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from exam_exercise where examID='$examID' and type='filling')";
        $select = "select * from filling";
        $sql = $select . $condition . $order;
        $result = Yii::app()->db->createCommand($sql)->query();
        return $result;
    }

    public function getFilling2($examID) {
        $isExam = Yii::app()->session['isExam'];
        if ($isExam) {
            $suite_exer = 'exam_exercise';
            $findID = 'examID';
        } else {
            $suite_exer = 'suite_exercise';
            $findID = 'suiteID';
        }
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from exam_exercise where examID='$examID' and type='filling')";
        $select = "select * from filling";
        $sql = $select . $condition . $order;
        $criteria = new CDbCriteria();
        $result = Yii::app()->db->createCommand($sql)->query();
        $pages = new CPagination($result->rowCount);
        $pages->pageSize = 8000;
        $pages->applyLimit($criteria);
        $result = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize);
        $result->bindValue(':limit', $pages->pageSize);
        $fillingLst = $result->query();

        return ['fillingLst' => $fillingLst, 'pages' => $pages,];
    }

    public function getQuestion($examID) {
        $isExam = Yii::app()->session['isExam'];
        if ($isExam) {
            $suite_exer = 'exam_exercise';
            $findID = 'examID';
        } else {
            $suite_exer = 'suite_exercise';
            $findID = 'suiteID';
        }
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from exam_exercise where examID='$examID' and type='question')";
        $select = "select * from question";
        $sql = $select . $condition . $order;
        $result = Yii::app()->db->createCommand($sql)->query();
        return $result;
    }

    public function getQuestion2($examID) {
        $isExam = Yii::app()->session['isExam'];
        if ($isExam) {
            $suite_exer = 'exam_exercise';
            $findID = 'examID';
        } else {
            $suite_exer = 'suite_exercise';
            $findID = 'suiteID';
        }
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from exam_exercise where examID='$examID' and type='question')";
        $select = "select * from question";
        $sql = $select . $condition . $order;
        $criteria = new CDbCriteria();
        $result = Yii::app()->db->createCommand($sql)->query();
        $pages = new CPagination($result->rowCount);
        $pages->pageSize = 8000;
        $pages->applyLimit($criteria);
        $result = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize);
        $result->bindValue(':limit', $pages->pageSize);
        $questionLst = $result->query();

        return ['questionLst' => $questionLst, 'pages' => $pages,];
    }

    public function getKeyExer($examID) {
        $isExam = Yii::app()->session['isExam'];
        if ($isExam) {
            $suite_exer = 'exam_exercise';
            $findID = 'examID';
        } else {
            $suite_exer = 'suite_exercise';
            $findID = 'suiteID';
        }
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from exam_exercise where examID='$examID' and type='key')";
        $select = "select * from key_type";
        $sql = $select . $condition . $order;
        $result = Yii::app()->db->createCommand($sql)->query();
        return $result;
    }

    public function getListenExer($examID) {
        $isExam = Yii::app()->session['isExam'];
        if ($isExam) {
            $suite_exer = 'exam_exercise';
            $findID = 'examID';
        } else {
            $suite_exer = 'suite_exercise';
            $findID = 'suiteID';
        }
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from exam_exercise where examID='$examID' and type='listen')";
        $select = "select * from listen_type";
        $sql = $select . $condition . $order;
        $result = Yii::app()->db->createCommand($sql)->query();
        return $result;
    }

    public function getLookExer($examID) {
        $isExam = Yii::app()->session['isExam'];
        if ($isExam) {
            $suite_exer = 'exam_exercise';
            $findID = 'examID';
        } else {
            $suite_exer = 'suite_exercise';
            $findID = 'suiteID';
        }
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from exam_exercise where examID='$examID' and type='look')";
        $select = "select * from look_type";
        $sql = $select . $condition . $order;
        $result = Yii::app()->db->createCommand($sql)->query();
        return $result;
    }

    public function getExamExerByTypePage($examID, $type, $pagesize) {
        $criteria = new CDbCriteria();
        $result = $this->getExamExerByType($examID, $type);
        $pages = new CPagination($result->rowCount);
        $pages->pageSize = $pagesize;
        $pages->applyLimit($criteria);
        if ($type == "key" || $type == "look" || $type == "listen") {
            $databaseType = $type . "_type";
        } else {
            $databaseType = $type;
        }
        $sql = "select * from " . $databaseType;
        $order = " order by exerciseID ASC";
        $condition = " where exerciseID in (select exerciseID from exam_exercise where examID='$examID' and type='" . $type . "')";
        $sql = $sql . $condition . $order;
        $result = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
        $result->bindValue(':offset', $pages->currentPage * $pages->pageSize);
        $result->bindValue(':limit', $pages->pageSize);
        $workLst = $result->query();
        return ['workLst' => $workLst, 'pages' => $pages,];
    }

    public function insertExam($title, $createPerson) {
        /*
          $sql        =   "select max(examID) as id from exam";
          $max_id     =   Yii::app()->db->createCommand($sql)->query();
          $temp       =   $max_id->read();
          if(empty($temp))
          {
          $new_id =   1;
          }
          else
          {
          $new_id =   $temp['id'] + 1;
          }
         * 
         */
        $newSuite = new Exam();
        //$newSuite->examID    =   $new_id;
        $newSuite->createPerson = $createPerson;
        $newSuite->createTime = date('y-m-d H:i:s', time());
        $newSuite->begintime = date('y-m-d H:i:s', time());
        $newSuite->endtime = date('y-m-d H:i:s', time());
        $newSuite->duration = 0;
        $newSuite->examName = $title;
        $sql = "select max(examID)+1 as id from exam";
        $max_id = Yii::app()->db->createCommand($sql)->query();
        $temp = $max_id->read();
        $new_id = $temp['id'];
        $newSuite->examID = $new_id;
        $newSuite->insert();
        return $new_id;
    }

    public function getExamByClassExam($teacherID) {
        $sql = "select * from exam";
        $condition = " where examID in(select examID from class_exam where classID in (select classID from teacher_class where teacherID = '$teacherID'))";
        $sql = $sql . $condition;
        return Yii::app()->db->createCommand($sql)->query();
    }

    public function changeExamName($examID, $newName) {
        $result = 0;
        $exam = $this->find('examID='.$examID);
        $examOwn = $this->findAll('examName LIKE "'.$newName.'"');
        $ownNum = count($examOwn);
        if ($newName !='' && $ownNum === 0) {
            $exam->examName = $newName;
            $result = $exam->update();
        }
        return $result;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Exam the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
