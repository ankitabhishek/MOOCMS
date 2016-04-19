-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 19, 2016 at 07:27 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `moocms`
--
CREATE DATABASE IF NOT EXISTS `moocms` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `moocms`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pass` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `last_login` varchar(20) NOT NULL,
  `last_ip` varchar(20) NOT NULL,
  `reg_date` varchar(20) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `pass`, `first_name`, `last_name`, `email`, `last_login`, `last_ip`, `reg_date`) VALUES
(1, '7c286a779653e0c1d9cbc2b9c772fbea7033e452', 'Alpha', 'Administrator', 'admin@moocms', '2015-10-29 18:49:52', '::1', '2015-10-29 18:49:52');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `course_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(255) NOT NULL,
  `start_date` varchar(20) NOT NULL,
  PRIMARY KEY (`course_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_id`, `course_name`, `start_date`) VALUES
(1, 'Parallel Programming', '2016-01-04'),
(3, 'Formal Lannguage and Automata Theory', '2016-01-06'),
(4, 'Artificial Intelligence', '2016-01-05');

-- --------------------------------------------------------

--
-- Table structure for table `enrollment`
--

CREATE TABLE IF NOT EXISTS `enrollment` (
  `course_id` bigint(20) NOT NULL,
  `student_id` bigint(11) NOT NULL,
  `reg_date` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `enrollment`
--

INSERT INTO `enrollment` (`course_id`, `student_id`, `reg_date`) VALUES
(1, 1, '2015-11-23 11:10:14'),
(3, 1, '2015-11-23 18:26:32');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE IF NOT EXISTS `faculty` (
  `faculty_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pass` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dob` varchar(15) NOT NULL,
  `last_login` varchar(20) NOT NULL,
  `last_ip` varchar(20) NOT NULL,
  `reg_date` varchar(20) NOT NULL,
  PRIMARY KEY (`faculty_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`faculty_id`, `pass`, `first_name`, `last_name`, `email`, `dob`, `last_login`, `last_ip`, `reg_date`) VALUES
(1, 'b1b3773a05c0ed0176787a4f1574ff0075f7521e', 'Devi Prasad', 'Sharma', 'dps@moocms', '', '2015-10-29 18:59:20', '::1', '2015-10-29 18:59:20');

-- --------------------------------------------------------

--
-- Table structure for table `instructor`
--

CREATE TABLE IF NOT EXISTS `instructor` (
  `course_id` bigint(20) NOT NULL,
  `faculty_id` bigint(20) NOT NULL,
  `reg_date` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `instructor`
--

INSERT INTO `instructor` (`course_id`, `faculty_id`, `reg_date`) VALUES
(1, 1, '2015-11-21 12:58:53'),
(3, 1, '2015-11-21 13:07:18'),
(4, 0, '2015-11-22 00:34:13');

-- --------------------------------------------------------

--
-- Table structure for table `lecture`
--

CREATE TABLE IF NOT EXISTS `lecture` (
  `lecture_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) NOT NULL,
  `lecture_name` varchar(255) NOT NULL,
  `lecture_video` varchar(255) NOT NULL,
  `lecture_date` varchar(20) NOT NULL,
  PRIMARY KEY (`lecture_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `lecture`
--

INSERT INTO `lecture` (`lecture_id`, `course_id`, `lecture_name`, `lecture_video`, `lecture_date`) VALUES
(1, 1, 'Lecture 1 - Introduction to Parallel Programming', 'HDZOdrC-Q0Q', '2015-11-22 15:07:34'),
(2, 1, 'Lecture 2 - Parallel Programming Paradigms', 'xs727GE6AM4', '2015-11-22 15:09:15'),
(3, 1, 'Lecture 3 - Parallel Architecture', 'rjobxf1Qs_o', '2015-11-22 15:09:58'),
(4, 3, 'Lecture 1 - Introduction to TOC and DFA', 'eqCkkC9A0Q4', '2015-11-22 15:11:44'),
(5, 3, 'Lecture 2 - Construction of minimal DFA', 'Fp4x2JKlsG0', '2015-11-22 15:12:20'),
(6, 3, 'Lecture 3 - Construction of DFA and cross product of DFA', '_yBei5Ax4fk', '2015-11-22 15:48:24');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `student_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pass` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dob` varchar(15) NOT NULL,
  `last_login` varchar(20) NOT NULL,
  `last_ip` varchar(20) NOT NULL,
  `reg_date` varchar(20) NOT NULL,
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `pass`, `first_name`, `last_name`, `email`, `dob`, `last_login`, `last_ip`, `reg_date`) VALUES
(1, 'b1b3773a05c0ed0176787a4f1574ff0075f7521e', 'Ankit', 'Abhishek', 'ankit@moocms', '', '2015-10-29 14:17:24', '::1', '2015-10-29 14:17:24'),
(2, '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Kanwardeep', 'Singh Arora', 'kdsarora@moocms', '', '2015-11-22 00:20:27', '::1', '2015-11-22 00:20:27');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
