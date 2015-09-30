-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-09-29 12:07:29
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100 ;

--
-- 转存表中的数据 `chat_lesson_1`
--

INSERT INTO `chat_lesson_1` (`id`, `identity`, `username`, `chat`, `time`, `classID`) VALUES
(94, 'teacher', '张三', '', '2015-09-29 17:06:10', '1'),
(95, 'teacher', '张三', '是的法规尽快来电', '2015-09-29 17:06:24', '1'),
(96, 'teacher', '张三', '啥地方', '2015-09-29 17:11:15', '1'),
(97, 'teacher', '张三', '对对对', '2015-09-29 17:50:33', '1'),
(98, 'student', '张小三', '撒打发斯蒂芬', '2015-09-29 17:52:00', '1'),
(99, 'teacher', '张三', '山东省地方', '2015-09-29 17:53:27', '1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
