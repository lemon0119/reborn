-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-09-25 09:37:03
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `yawei001`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `userID` varchar(30) NOT NULL,
  `userName` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`userID`, `userName`, `password`) VALUES
('001', 'admin', '21232f297a57a5a743894a0e4a801fc3');

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

--
-- 转存表中的数据 `answer_record`
--

INSERT INTO `answer_record` (`answerID`, `recordID`, `exerciseID`, `type`, `costTime`, `ratio_accomplish`, `ratio_correct`, `answer`, `createPerson`, `createTime`, `score`) VALUES
('3085896593', '3085896302', 1, 'choice', 9999, 0, 0, 'A', 1, '2015-09-24 17:12:24', 0),
('3085900016', '3085896302', 3, 'choice', 9999, 0, 0, 'A', 1, '2015-09-24 17:11:40', 0),
('3085900151', '3085896302', 4, 'choice', 9999, 0, 0, 'B', 1, '2015-09-24 17:11:40', 0),
('3085911119', '3085896302', 1, 'question', 9999, 0, 0, '玩儿', 1, '2015-09-25 10:54:30', 0),
('3085911535', '3085896302', 2, 'question', 9999, 0, 0, '未确认', 1, '2015-09-25 10:54:30', 0),
('3085920311', '3085896302', 3, 'question', 9999, 0, 0, '确认', 1, '2015-09-25 10:54:30', 0),
('3085928443', '3085896302', 1, 'filling', 9999, 0, 0, '请问', 1, '2015-09-24 17:12:08', 0),
('3085928724', '3085896302', 2, 'filling', 9999, 0, 0, '请问', 1, '2015-09-24 17:12:08', 0),
('3085937500', '3085896302', 3, 'filling', 9999, 0, 0, '请问', 1, '2015-09-24 17:12:17', 0),
('3085944441', '3085896302', 2, 'choice', 9999, 0, 0, 'B', 1, '2015-09-24 17:12:24', 0),
('3085958323', '3085896302', 2, 'key', 2, 0, 0, '', 1, '2015-09-24 17:12:38', 0),
('3085972880', '3065424969', 3, 'choice', 9999, 0, 0, 'B', 1, '2015-09-24 17:12:52', 0),
('3085973500', '3065424969', 4, 'choice', 9999, 0, 0, 'B', 1, '2015-09-24 17:12:53', 0),
('3157672573', '3066487071', 1, 'choice', 9999, 0, 0, 'A', 1, '2015-09-25 13:07:52', 0),
('3163716175', '3086738342', 3, 'choice', 9999, 0, 0, 'C', 1, '2015-09-25 14:58:16', 0),
('3163716805', '3086738342', 2, 'choice', 9999, 0, 0, 'B', 1, '2015-09-25 14:58:16', 0),
('3163716830', '3086738342', 1, 'filling', 9999, 0, 0, '111$$222$$333', 1, '2015-09-25 14:58:16', 0),
('3163716838', '3086738342', 1, 'choice', 9999, 0, 0, 'A', 1, '2015-09-25 14:58:16', 0),
('3163726963', '3086738342', 1, 'question', 9999, 0, 0, '234', 1, '2015-09-25 14:49:20', 0),
('3164008697', '3086738342', 5, 'choice', 9999, 0, 0, 'B', 1, '2015-09-25 14:58:16', 0),
('3164032548', '3086738342', 6, 'choice', 9999, 0, 0, 'A', 1, '2015-09-25 14:58:16', 0),
('3164296113', '3086738342', 4, 'choice', 9999, 0, 0, 'C', 1, '2015-09-25 14:58:16', 0);

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

--
-- 转存表中的数据 `choice`
--

INSERT INTO `choice` (`exerciseID`, `type`, `courseID`, `requirements`, `options`, `answer`, `createPerson`, `createTime`, `changeLog`) VALUES
(1, 'danxuan', 0, '选择题1', '1$$2$$3$$4', 'A', '0', '2015-09-23 09:34:27', ''),
(2, 'danxuan', 0, '选择题2', '1$$2$$3$$4', 'B', '0', '2015-09-23 09:34:36', ''),
(3, 'danxuan', 0, '选择题3', '1$$2$$3$$4', 'C', '0', '2015-09-23 09:34:45', ''),
(4, 'danxuan', 0, '选择题4', '1$$2$$3$$4', 'D', '0', '2015-09-23 09:34:53', ''),
(5, 'danxuan', 0, '选择题1', '1$$2$$3$$4', 'A', '001', '2015-09-24 17:44:01', ''),
(6, 'danxuan', 0, '选择题4', '1$$2$$3$$4', 'D', '001', '2015-09-24 17:44:07', ''),
(7, 'danxuan', 0, '选择题3', '1$$2$$3$$4', 'C', '001', '2015-09-24 17:44:10', ''),
(8, 'danxuan', 0, '选择题2', '1$$2$$3$$4', 'B', '001', '2015-09-24 17:44:12', ''),
(9, 'danxuan', 0, '收到货', '啥地方你们$$撒旦$$送大礼$$傻到家开发', 'A', '001', '2015-09-24 17:47:38', ''),
(10, 'danxuan', 0, '傻的看了', 'sadfjk$$士大夫$$士大夫$$阿斯顿发文', 'A', '001', '2015-09-24 17:47:51', ''),
(11, 'danxuan', 0, '萨达哈可怜', '啥地方收到$$萨芬$$昂首$$阿斯蒂芬', 'B', '001', '2015-09-24 17:48:06', ''),
(12, 'danxuan', 0, '撒打发斯蒂芬', '啥地方$$昂首啥地方$$手动阀$$手动阀', 'B', '001', '2015-09-24 17:48:22', ''),
(13, 'danxuan', 0, '撒打发斯蒂芬', '啥地方收到$$士大夫$$撒的发生$$爱的方式', 'C', '001', '2015-09-24 17:48:36', ''),
(14, 'danxuan', 0, '撒旦法师法撒旦法师打发斯蒂芬是', '是的发送到发送到发送到$$是打发发送到发送到发送到$$是发达是的发送到发送到发送到发$$是打发斯蒂芬斯蒂芬沙发沙发沙发沙发', 'A', '001', '2015-09-24 17:48:52', ''),
(15, 'danxuan', 0, '文他认为该方法是否', '沙发上的发生地方$$沙发上的发生方式$$打发打发$$阿阿斯顿', 'A', '001', '2015-09-24 17:49:03', ''),
(16, 'danxuan', 0, '选择题1', '1$$2$$3$$4', 'A', '001', '2015-09-24 17:50:00', ''),
(17, 'danxuan', 0, '选择题2', '1$$2$$3$$4', 'B', '001', '2015-09-24 17:50:04', ''),
(18, 'danxuan', 0, '选择题4', '1$$2$$3$$4', 'D', '001', '2015-09-24 17:50:06', '');

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

--
-- 转存表中的数据 `class_exam`
--

INSERT INTO `class_exam` (`classID`, `examID`, `open`, `workID`) VALUES
(1, 1, 1, 1),
(1, 2, 1, 2),
(1, 3, 1, 3);

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

--
-- 转存表中的数据 `class_lesson_suite`
--

INSERT INTO `class_lesson_suite` (`workID`, `suiteID`, `lessonID`, `classID`, `open`) VALUES
(1, 1, 5, 1, 1),
(2, 2, 5, 1, 1);

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

--
-- 转存表中的数据 `course`
--

INSERT INTO `course` (`courseID`, `courseName`, `createPerson`, `createTime`, `changeLog`) VALUES
(1, '物理', '0', '2015-09-23 09:20:34', ''),
(2, '化学', '0', '2015-09-23 09:22:17', ''),
(3, '生物', '0', '2015-09-23 09:23:27', ''),
(4, '计算机', '0', '2015-09-23 09:23:50', '');

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

--
-- 转存表中的数据 `exam`
--

INSERT INTO `exam` (`examID`, `examName`, `begintime`, `endtime`, `createTime`, `createPerson`, `duration`) VALUES
(1, '11', '2015-09-23 15:15:02', '2015-10-23 15:15:02', '2015-09-23 15:15:02', '001', 0),
(2, '考卷1', '2015-09-23 16:10:13', '2015-09-25 18:30:13', '2015-09-23 16:00:13', '001', 181200),
(3, '爱上了', '2015-09-24 11:02:36', '2015-09-24 12:02:36', '2015-09-24 11:02:36', '001', 3600);

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

--
-- 转存表中的数据 `exam_exercise`
--

INSERT INTO `exam_exercise` (`examID`, `exerciseID`, `type`, `score`, `time`) VALUES
(1, 1, 'filling', 0, 0),
(1, 1, 'question', 0, 0),
(1, 2, 'question', 0, 0),
(1, 3, 'choice', 0, 0),
(1, 3, 'filling', 0, 0),
(1, 3, 'question', 0, 0),
(1, 4, 'choice', 0, 0),
(2, 1, 'look', 20, 60),
(2, 1, 'key', 20, 120),
(2, 1, 'choice', 5, 0),
(2, 1, 'filling', 5, 0),
(2, 1, 'question', 10, 0),
(2, 2, 'choice', 5, 0),
(2, 2, 'filling', 5, 0),
(2, 2, 'question', 10, 0),
(2, 3, 'choice', 0, 0),
(3, 1, 'choice', 10, 0),
(3, 1, 'filling', 10, 0),
(3, 1, 'question', 10, 0);

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

--
-- 转存表中的数据 `exam_record`
--

INSERT INTO `exam_record` (`recordID`, `ratio_accomplish`, `ratio_correct`, `createPerson`, `createTime`, `workID`, `score`, `modifyTime`, `studentID`) VALUES
('3065424969', 1, 0, '', '2015-09-24 11:30:24', 1, NULL, '2015-09-25 10:52:30', 1),
('3065501178', 0, 0, '', '2015-09-24 11:31:41', 3, NULL, '2015-09-24 11:43:28', 1),
('3066487071', 0, 0, '', '2015-09-24 11:48:07', 2, NULL, '2015-09-25 13:07:52', 1);

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

--
-- 转存表中的数据 `filling`
--

INSERT INTO `filling` (`exerciseID`, `courseID`, `requirements`, `answer`, `createPerson`, `createTime`, `changeLog`) VALUES
(1, 0, '填空1', '1', '0', '2015-09-23 09:44:53', ''),
(2, 0, '填空2', '1$$2$$3', '0', '2015-09-23 09:48:28', ''),
(3, 0, '填空3', '1$$2$$3$$4', '0', '2015-09-23 09:49:24', '');

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

--
-- 转存表中的数据 `key_type`
--

INSERT INTO `key_type` (`exerciseID`, `courseID`, `title`, `content`, `createPerson`, `createTime`, `changeLog`) VALUES
(1, 0, '键位练习1', 'A$A$2$A$A$3', '0', '2015-09-23 09:53:03', ''),
(2, 0, '键位练习2', 'AAA$AAA$12$AAA$AAA$2', '0', '2015-09-23 09:54:21', '');

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
  PRIMARY KEY (`lessonID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `lesson`
--

INSERT INTO `lesson` (`lessonID`, `classID`, `number`, `lessonName`, `courseID`, `createPerson`, `createTime`) VALUES
(1, 0, 1, '第一课：大气流动', 1, '0', '2015-09-23 09:21:40'),
(2, 0, 1, '第一课：化学键', 2, '0', '2015-09-23 09:23:16'),
(3, 0, 1, '第一课：生态圈', 3, '0', '2015-09-23 09:23:38'),
(4, 0, 1, '第一课：数据结构', 4, '0', '2015-09-23 09:24:03'),
(5, 1, 1, '第一课：大气流动', 1, '0', '2015-09-23 09:25:43'),
(6, 2, 1, '第一课：数据结构', 4, '0', '2015-09-23 09:32:25');

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

--
-- 转存表中的数据 `listen_type`
--

INSERT INTO `listen_type` (`exerciseID`, `courseID`, `content`, `filePath`, `fileName`, `title`, `createPerson`, `createTime`, `changeLog`) VALUES
(1, 0, '1111', 'admin/001/', 'heheda.mp3', '听打练习1', '0', '2015-09-23 09:56:21', '');

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

--
-- 转存表中的数据 `look_type`
--

INSERT INTO `look_type` (`exerciseID`, `courseID`, `title`, `content`, `createPerson`, `createTime`, `changeLog`) VALUES
(1, 0, '看打练习1', '11111', '0', '2015-09-23 09:55:31', ''),
(2, 0, '看打练习2', '2222222', '0', '2015-09-23 09:55:38', '');

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

--
-- 转存表中的数据 `question`
--

INSERT INTO `question` (`exerciseID`, `courseID`, `requirements`, `answer`, `createPerson`, `createTime`, `changeLog`) VALUES
(1, 0, '简答题1', '1', '0', '2015-09-23 09:49:36', ''),
(2, 0, '简答题2', '2', '0', '2015-09-23 09:49:41', ''),
(3, 0, '简答题3', '3', '0', '2015-09-23 09:49:46', '');

-- --------------------------------------------------------

--
-- 表的结构 `resourse`
--

CREATE TABLE IF NOT EXISTS `resourse` (
  `resourseID` varchar(30) NOT NULL,
  `lessonID` int(30) NOT NULL,
  `type` enum('video','ppt') NOT NULL,
  `name` text NOT NULL,
  `path` text NOT NULL
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
  `is_delete` tinyint(1) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `student`
--

INSERT INTO `student` (`userID`, `userName`, `sex`, `age`, `password`, `mail_address`, `phone_number`, `classID`, `is_delete`) VALUES
('001', '张小三', '男', 15, 'c6f057b86584942e415435ffb1fa93d4', '', '', 1, 0),
('002', '李小四', '女', 17, 'c6f057b86584942e415435ffb1fa93d4', '', '', 1, 0),
('004', 'student', '男', 15, 'c6f057b86584942e415435ffb1fa93d4', '', '', 1, 0);

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

--
-- 转存表中的数据 `suite`
--

INSERT INTO `suite` (`suiteID`, `suiteName`, `suiteType`, `createTime`, `createPerson`) VALUES
(1, '第一课作业', 'exercise', '2015-09-23 10:00:27', '001'),
(2, '罪业', 'exercise', '2015-09-24 16:34:27', '001');

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

--
-- 转存表中的数据 `suite_exercise`
--

INSERT INTO `suite_exercise` (`suiteID`, `exerciseID`, `type`) VALUES
(1, 1, 'key'),
(1, 1, 'choice'),
(1, 1, 'filling'),
(1, 1, 'question'),
(1, 2, 'key'),
(1, 2, 'choice'),
(1, 2, 'filling'),
(1, 2, 'question'),
(1, 3, 'choice'),
(1, 3, 'filling'),
(1, 3, 'question'),
(1, 4, 'choice'),
(2, 1, 'look'),
(2, 1, 'key'),
(2, 1, 'choice'),
(2, 1, 'filling'),
(2, 1, 'question'),
(2, 2, 'choice'),
(2, 2, 'filling'),
(2, 2, 'question'),
(2, 3, 'choice'),
(2, 3, 'filling'),
(2, 3, 'question'),
(2, 4, 'choice'),
(2, 5, 'choice'),
(2, 6, 'choice'),
(2, 7, 'choice'),
(2, 8, 'choice'),
(2, 9, 'choice'),
(2, 10, 'choice'),
(2, 11, 'choice'),
(2, 12, 'choice'),
(2, 13, 'choice'),
(2, 14, 'choice'),
(2, 15, 'choice'),
(2, 16, 'choice'),
(2, 17, 'choice'),
(2, 18, 'choice');

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

--
-- 转存表中的数据 `suite_record`
--

INSERT INTO `suite_record` (`recordID`, `workID`, `ratio_accomplish`, `ratio_correct`, `score`, `studentID`, `createTime`, `modifyTime`) VALUES
('3085896302', 1, 1, 0, 0, '001', '2015-09-24 17:11:36', '2015-09-25 10:54:30'),
('3086738342', 2, 0, 0, 0, '001', '2015-09-24 17:25:38', '2015-09-25 14:58:15');

-- --------------------------------------------------------

--
-- 表的结构 `tb_class`
--

CREATE TABLE IF NOT EXISTS `tb_class` (
  `classID` int(30) NOT NULL,
  `className` varchar(30) NOT NULL,
  `currentCourse` int(30) NOT NULL,
  `currentLesson` int(30) NOT NULL,
  PRIMARY KEY (`classID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tb_class`
--

INSERT INTO `tb_class` (`classID`, `className`, `currentCourse`, `currentLesson`) VALUES
(1, '三年一班', 1, 1),
(2, '三年二班', 4, 1);

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

--
-- 转存表中的数据 `teacher`
--

INSERT INTO `teacher` (`userID`, `userName`, `sex`, `age`, `password`, `phone_number`, `mail_address`, `department`, `is_delete`) VALUES
('001', '张三', '男', 23, 'c6f057b86584942e415435ffb1fa93d4', '', '123456@123.com', '物理教学部', 0),
('002', '李四', '男', 24, 'c6f057b86584942e415435ffb1fa93d4', '123456@123.com', '', '计算机教学部', 0);

-- --------------------------------------------------------

--
-- 表的结构 `teacher_class`
--

CREATE TABLE IF NOT EXISTS `teacher_class` (
  `teacherID` varchar(30) NOT NULL,
  `classID` int(30) NOT NULL,
  `remark` varchar(30) NOT NULL,
  PRIMARY KEY (`teacherID`,`classID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `teacher_class`
--

INSERT INTO `teacher_class` (`teacherID`, `classID`, `remark`) VALUES
('001', 1, ''),
('002', 2, '');

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
