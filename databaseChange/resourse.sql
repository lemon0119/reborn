-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-10-12 11:35:40
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

--
-- 转存表中的数据 `resourse`
--

INSERT INTO `resourse` (`resourseID`, `lessonID`, `type`, `name`, `path`) VALUES
('4642294023.wav', 0, 'video', '小镇.wav', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
