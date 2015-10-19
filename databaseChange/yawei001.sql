-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-10-16 11:46:17
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `yawei002`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `userID` varchar(30) NOT NULL,
  `userName` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `mail_address` varchar(50) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`userID`, `userName`, `password`, `mail_address`) VALUES
('001', 'admin', 'c6f057b86584942e415435ffb1fa93d4', 'admin@admin.com');

-- --------------------------------------------------------

--
-- 表的结构 `answer_record`
--

CREATE TABLE IF NOT EXISTS `answer_record` (
  `answerID` varchar(30) NOT NULL,
  `recordID` varchar(30) NOT NULL,
  `exerciseID` int(30) NOT NULL,
  `type` enum('look','listen','key','choice','filling','question') NOT NULL,
  `costTime` int(5) NOT NULL DEFAULT '9999',
  `ratio_accomplish` float NOT NULL DEFAULT '0',
  `ratio_correct` float NOT NULL DEFAULT '0',
  `answer` text NOT NULL,
  `createPerson` int(30) NOT NULL,
  `createTime` datetime NOT NULL,
  `score` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`answerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `bulletin_lesson_1`
--

CREATE TABLE IF NOT EXISTS `bulletin_lesson_1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text,
  `time` datetime DEFAULT NULL,
  `classID` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `chat_lesson_1`
--

CREATE TABLE IF NOT EXISTS `chat_lesson_1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identity` varchar(30) DEFAULT NULL,
  `username` varchar(32) DEFAULT NULL,
  `chat` varchar(128) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `classID` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `choice`
--

CREATE TABLE IF NOT EXISTS `choice` (
  `exerciseID` int(30) NOT NULL,
  `type` enum('danxuan','duoxuan','budingxiang') NOT NULL DEFAULT 'danxuan',
  `courseID` int(30) NOT NULL DEFAULT '0',
  `requirements` varchar(200) NOT NULL,
  `options` text NOT NULL,
  `answer` varchar(30) NOT NULL,
  `createPerson` varchar(30) NOT NULL,
  `createTime` datetime NOT NULL,
  `changeLog` text NOT NULL,
  PRIMARY KEY (`exerciseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `classsuite`
--

CREATE TABLE IF NOT EXISTS `classsuite` (
  `classID` varchar(30) NOT NULL,
  `suiteID` varchar(30) NOT NULL,
  `type` enum('exam','exercise','classwork','') NOT NULL,
  PRIMARY KEY (`classID`,`suiteID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `classwork`
--

CREATE TABLE IF NOT EXISTS `classwork` (
  `suiteID` int(30) NOT NULL,
  `begintime` datetime NOT NULL,
  `endtime` datetime NOT NULL,
  `createTime` datetime NOT NULL,
  `createPerson` varchar(30) NOT NULL,
  PRIMARY KEY (`suiteID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `class_exam`
--

CREATE TABLE IF NOT EXISTS `class_exam` (
  `classID` int(11) NOT NULL,
  `examID` int(11) NOT NULL,
  `open` tinyint(1) NOT NULL,
  `workID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`workID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `class_lesson_suite`
--

CREATE TABLE IF NOT EXISTS `class_lesson_suite` (
  `workID` int(11) NOT NULL DEFAULT '0',
  `suiteID` int(30) NOT NULL,
  `lessonID` int(30) NOT NULL,
  `classID` int(30) NOT NULL,
  `open` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`workID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `class_schedule`
--

CREATE TABLE IF NOT EXISTS `class_schedule` (
  `classID` int(30) NOT NULL,
  `sequence` int(10) NOT NULL,
  `day` int(10) NOT NULL,
  `courseID` int(30) NOT NULL,
  KEY `classID` (`classID`),
  KEY `courseID` (`courseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 表的结构 `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `courseID` int(30) NOT NULL,
  `courseName` varchar(30) NOT NULL,
  `createPerson` varchar(30) NOT NULL,
  `createTime` datetime NOT NULL,
  `changeLog` text NOT NULL,
  PRIMARY KEY (`courseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `exam`
--

CREATE TABLE IF NOT EXISTS `exam` (
  `examID` int(30) NOT NULL,
  `examName` varchar(60) NOT NULL,
  `begintime` datetime NOT NULL,
  `endtime` datetime NOT NULL,
  `createTime` datetime NOT NULL,
  `createPerson` varchar(30) NOT NULL,
  `duration` int(11) DEFAULT NULL,
  PRIMARY KEY (`examID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `exam_exercise`
--

CREATE TABLE IF NOT EXISTS `exam_exercise` (
  `examID` int(30) NOT NULL,
  `exerciseID` int(30) NOT NULL,
  `type` enum('look','listen','key','choice','filling','question') NOT NULL,
  `score` int(2) NOT NULL,
  `time` int(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`examID`,`exerciseID`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `exam_record`
--

CREATE TABLE IF NOT EXISTS `exam_record` (
  `recordID` varchar(30) NOT NULL,
  `ratio_accomplish` float NOT NULL DEFAULT '0',
  `ratio_correct` float NOT NULL DEFAULT '0',
  `createPerson` varchar(30) NOT NULL,
  `createTime` datetime NOT NULL,
  `workID` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `modifyTime` datetime DEFAULT NULL,
  `studentID` int(11) DEFAULT NULL,
  PRIMARY KEY (`recordID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `filling`
--

CREATE TABLE IF NOT EXISTS `filling` (
  `exerciseID` int(30) NOT NULL,
  `courseID` int(30) NOT NULL DEFAULT '0',
  `requirements` text NOT NULL,
  `answer` varchar(100) NOT NULL,
  `createPerson` varchar(30) NOT NULL,
  `createTime` datetime NOT NULL,
  `changeLog` text NOT NULL,
  PRIMARY KEY (`exerciseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `key_type`
--

CREATE TABLE IF NOT EXISTS `key_type` (
  `exerciseID` int(30) NOT NULL,
  `courseID` int(30) NOT NULL DEFAULT '0',
  `title` varchar(30) NOT NULL,
  `content` text NOT NULL,
  `createPerson` varchar(30) NOT NULL,
  `createTime` datetime NOT NULL,
  `changeLog` text NOT NULL,
  PRIMARY KEY (`exerciseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `lesson`
--

CREATE TABLE IF NOT EXISTS `lesson` (
  `lessonID` int(30) NOT NULL,
  `classID` int(30) NOT NULL,
  `number` int(4) NOT NULL,
  `lessonName` varchar(30) NOT NULL,
  `courseID` int(30) NOT NULL,
  `createPerson` varchar(30) NOT NULL,
  `createTime` datetime NOT NULL,
  PRIMARY KEY (`lessonID`,`courseID`),
  KEY `lesson_courseID` (`courseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `listen_type`
--

CREATE TABLE IF NOT EXISTS `listen_type` (
  `exerciseID` int(30) NOT NULL,
  `courseID` int(30) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `filePath` varchar(30) NOT NULL,
  `fileName` varchar(30) NOT NULL,
  `title` varchar(30) NOT NULL,
  `createPerson` varchar(30) NOT NULL,
  `createTime` datetime NOT NULL,
  `changeLog` text NOT NULL,
  PRIMARY KEY (`exerciseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `look_type`
--

CREATE TABLE IF NOT EXISTS `look_type` (
  `exerciseID` int(30) NOT NULL,
  `courseID` int(30) NOT NULL DEFAULT '0',
  `title` varchar(30) NOT NULL,
  `content` text NOT NULL,
  `createPerson` varchar(30) NOT NULL,
  `createTime` datetime NOT NULL,
  `changeLog` text NOT NULL,
  PRIMARY KEY (`exerciseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `exerciseID` int(30) NOT NULL,
  `courseID` int(30) NOT NULL DEFAULT '0',
  `requirements` varchar(200) NOT NULL,
  `answer` text NOT NULL,
  `createPerson` varchar(30) NOT NULL,
  `createTime` datetime NOT NULL,
  `changeLog` text NOT NULL,
  PRIMARY KEY (`exerciseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `resourse`
--

CREATE TABLE IF NOT EXISTS `resourse` (
  `resourseID` varchar(30) NOT NULL,
  `lessonID` int(30) NOT NULL,
  `type` enum('video','ppt') NOT NULL,
  `name` text NOT NULL,
  `path` text NOT NULL,
  PRIMARY KEY (`resourseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `userID` varchar(30) NOT NULL,
  `userName` varchar(30) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `age` int(10) NOT NULL,
  `password` varchar(50) NOT NULL,
  `mail_address` varchar(50) NOT NULL,
  `phone_number` varchar(30) NOT NULL,
  `classID` int(30) NOT NULL,
  `img_address` varchar(50) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `suite`
--

CREATE TABLE IF NOT EXISTS `suite` (
  `suiteID` int(30) NOT NULL,
  `suiteName` varchar(60) NOT NULL,
  `suiteType` enum('exam','exercise','classwork') NOT NULL DEFAULT 'exercise',
  `createTime` datetime NOT NULL,
  `createPerson` varchar(30) NOT NULL,
  PRIMARY KEY (`suiteID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `suite_exercise`
--

CREATE TABLE IF NOT EXISTS `suite_exercise` (
  `suiteID` int(30) NOT NULL,
  `exerciseID` int(30) NOT NULL,
  `type` enum('look','listen','key','choice','filling','question') NOT NULL,
  PRIMARY KEY (`suiteID`,`exerciseID`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `suite_record`
--

CREATE TABLE IF NOT EXISTS `suite_record` (
  `recordID` varchar(30) NOT NULL DEFAULT '000',
  `workID` int(30) NOT NULL,
  `ratio_accomplish` float NOT NULL DEFAULT '0',
  `ratio_correct` float NOT NULL DEFAULT '0',
  `score` int(3) NOT NULL DEFAULT '0',
  `studentID` varchar(30) NOT NULL,
  `createTime` datetime NOT NULL,
  `modifyTime` datetime NOT NULL,
  PRIMARY KEY (`recordID`),
  UNIQUE KEY `recordID` (`recordID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tb_class`
--

CREATE TABLE IF NOT EXISTS `tb_class` (
  `classID` int(30) NOT NULL,
  `className` varchar(30) NOT NULL,
  `currentCourse` int(30) NOT NULL,
  `currentLesson` int(30) NOT NULL,
  PRIMARY KEY (`classID`),
  KEY `tb_claas_courseID` (`currentCourse`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `teacher`
--

CREATE TABLE IF NOT EXISTS `teacher` (
  `userID` varchar(30) NOT NULL,
  `userName` varchar(30) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `age` int(10) NOT NULL,
  `password` varchar(50) NOT NULL,
  `phone_number` varchar(30) NOT NULL,
  `mail_address` varchar(50) NOT NULL,
  `department` varchar(80) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `teacher_class`
--

CREATE TABLE IF NOT EXISTS `teacher_class` (
  `teacherID` varchar(30) CHARACTER SET utf8 NOT NULL,
  `classID` int(30) NOT NULL,
  `remark` varchar(30) CHARACTER SET utf8 NOT NULL,
  KEY `classID` (`classID`),
  KEY `teacherID` (`teacherID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 表的结构 `teacher_schedule`
--

CREATE TABLE IF NOT EXISTS `teacher_schedule` (
  `userID` varchar(30) CHARACTER SET utf8 NOT NULL,
  `sequence` int(10) NOT NULL,
  `day` int(10) NOT NULL,
  `courseID` int(30) NOT NULL,
  KEY `teacher_schedule_courseID` (`courseID`),
  KEY `userID` (`userID`,`courseID`),
  KEY `userID_2` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `username` varchar(30) NOT NULL,
  `sex` tinyint(1) NOT NULL,
  `age` tinyint(2) unsigned DEFAULT NULL,
  `userid` varchar(30) NOT NULL,
  `id_card` varchar(40) NOT NULL,
  `password` varchar(70) NOT NULL,
  `role` tinyint(1) NOT NULL,
  `show` tinyint(1) NOT NULL DEFAULT '1',
  `xueli` tinyint(1) NOT NULL,
  `tel` char(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `qq` varchar(20) NOT NULL,
  `address` varchar(200) NOT NULL,
  `mianmao` tinyint(1) NOT NULL,
  `img_small` varchar(128) NOT NULL,
  `img_big` varchar(128) NOT NULL,
  `xuehao` varchar(20) NOT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `xuehao` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 限制导出的表
--

--
-- 限制表 `class_schedule`
--
ALTER TABLE `class_schedule`
  ADD CONSTRAINT `class_schedule_courseID` FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `class_schedule_classID` FOREIGN KEY (`classID`) REFERENCES `tb_class` (`classID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `lesson`
--
ALTER TABLE `lesson`
  ADD CONSTRAINT `lesson_courseID` FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `tb_class`
--
ALTER TABLE `tb_class`
  ADD CONSTRAINT `tb_claas_courseID` FOREIGN KEY (`currentCourse`) REFERENCES `course` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `teacher_schedule`
--
ALTER TABLE `teacher_schedule`
  ADD CONSTRAINT `teacher_schedule_courseID` FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `teacher_schedule_userID` FOREIGN KEY (`userID`) REFERENCES `teacher` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
