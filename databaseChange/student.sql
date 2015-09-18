-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-09-18 02:55:37
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
  `password` varchar(30) NOT NULL,
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
('1', 'student', '女', 0, '000', '', '', 1, 0),
('102', '0000', '男', 0, '000', '', '', 0, 0),
('111', '111', '男', 11, '000', '1111', '', 1, 0),
('13', '13', '女', 0, '000', '', '', 2, 0),
('14', '111', '男', 22, '000', '', '', 1, 0),
('17', '111', '女', 22, '000', '', '', 1, 0),
('18', '222', '女', 1, '000', '', '', 2, 0),
('19', '321', '女', 22, '000', '', '', 2, 0),
('2', '2', '', 0, '000', '', '0', 1, 0),
('20', 'student2', '', 0, '000', '', '0', 1, 0),
('200', '000', '男', 0, '000', '', '', 1, 0),
('21', 'student3', '', 0, '000', '', '', 2, 0),
('22', '22', '', 0, '22', '', '0', 2, 0),
('222', '222', '女', 22, '222', '2222', '22222222222', 2, 0),
('23', '23', '', 0, '23', '', '0', 2, 0),
('25', 'asf', '', 0, 'asd', '', '0', 2, 0),
('27', 'ad', '', 0, 'as', '', '0', 1, 0),
('29', 'asd', '', 0, 'as', '', '0', 1, 0),
('321', '32', '男', 22, '000', '', '', 2, 0),
('33', '333', '男', 3, '000', '333', '33333333333', 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
