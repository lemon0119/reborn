-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-09-21 04:11:25
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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
