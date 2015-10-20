-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-10-20 09:43:53
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
  `is_delete` tinyint(1) NOT NULL,
  `noticestate` varchar(30) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `student`
--

INSERT INTO `student` (`userID`, `userName`, `sex`, `age`, `password`, `mail_address`, `phone_number`, `classID`, `is_delete`, `noticestate`) VALUES
('0001', '慕容雪耳', '女', 0, 'c6f057b86584942e415435ffb1fa93d4', '', '', 0, 0, '1'),
('001', '张三', '男', 0, 'c6f057b86584942e415435ffb1fa93d4', '', '', 1, 0, '1'),
('002', '小王', '男', 0, 'c6f057b86584942e415435ffb1fa93d4', '', '', 2, 0, '1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
