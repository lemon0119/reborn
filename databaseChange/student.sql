-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-09-29 11:52:28
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

--
-- 转存表中的数据 `student`
--

INSERT INTO `student` (`userID`, `userName`, `sex`, `age`, `password`, `mail_address`, `phone_number`, `classID`, `img_address`, `is_delete`) VALUES
('001', '张小三', '男', 15, 'c6f057b86584942e415435ffb1fa93d4', '123@qq.com', '', 1, 'img/head/3518383977.gif', 0),
('002', '李小四', '女', 17, 'c6f057b86584942e415435ffb1fa93d4', '', '', 1, '0', 0),
('004', 'student', '男', 15, 'c6f057b86584942e415435ffb1fa93d4', '', '', 1, '0', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
