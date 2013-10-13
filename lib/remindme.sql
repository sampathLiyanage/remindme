-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 13, 2013 at 10:22 AM
-- Server version: 5.6.12
-- PHP Version: 5.5.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `remindme`
--
CREATE DATABASE IF NOT EXISTS `remindme` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `remindme`;

-- --------------------------------------------------------

--
-- Table structure for table `reminder`
--

CREATE TABLE IF NOT EXISTS `reminder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `remindList_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `date_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `remindList_id` (`remindList_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=280 ;

--
-- Dumping data for table `reminder`
--

INSERT INTO `reminder` (`id`, `remindList_id`, `name`, `description`, `date_time`) VALUES
(269, 142, 'Requirement specification', 'Requirement specification document should contain requirements for the first and second iterations. Both functional and non functional requirements should be specified. ', '2013-09-01 00:00:00'),
(270, 142, 'Design document', 'This document should contain architectural and other design details related to requirements specified. Architectural diagrams, Class diagrams, ER diagrams should be included. This should follow RUP.', '2013-09-19 00:00:00'),
(271, 142, 'QA plan', 'Quality assurance plan should contain details about how the quality of the product is going to be measured. Details about unit testing, component testing, alpha, beta testing and stress testing should be included. RUP way should be followed.', '2013-10-02 00:00:00'),
(272, 142, 'Weekly report 1', 'Provide a brief description on how the implementation is going on.', '2013-10-13 00:00:00'),
(273, 142, 'week report 2', 'provide a brief description on how the implementation is going on.', '2013-10-20 00:00:00'),
(274, 142, 'mid evoluation', 'Work done so far, will be evaluated. prepare your documents and get ready.', '2013-10-31 00:00:00'),
(275, 142, 'weekly report 3', 'provide a brief description on how the implementation is going on.', '2013-11-01 00:00:00'),
(276, 142, 'weekly report 4', 'provide a brief description on how the implementation is going on.', '2013-11-28 00:00:00'),
(277, 142, 'final demo', 'Final demo. Prepare your self.', '2013-12-03 00:00:00'),
(278, 142, 'Schedule', 'Create a schedule for the project. ', '2013-08-30 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `remindList`
--

CREATE TABLE IF NOT EXISTS `remindList` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `public_token` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=145 ;

--
-- Dumping data for table `remindList`
--

INSERT INTO `remindList` (`id`, `user_id`, `title`, `description`, `date_created`, `date_updated`, `public_token`) VALUES
(142, 246, 'software engineering project ', 'This list includes reminders related to schedule of the software engineering project.', '2013-10-12 23:40:58', '2013-10-12 23:40:58', 'iTnDtW510L');

-- --------------------------------------------------------

--
-- Table structure for table `subcription`
--

CREATE TABLE IF NOT EXISTS `subcription` (
  `user_id` int(11) NOT NULL,
  `remindList_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`remindList_id`),
  KEY `remindList_id` (`remindList_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=247 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `user_name`, `password`, `email`) VALUES
(246, 'sampath', 'c19a9475b9074c31d6e071a9f4222fe6', 'sampth@gmail.com');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reminder`
--
ALTER TABLE `reminder`
  ADD CONSTRAINT `reminder_ibfk_1` FOREIGN KEY (`remindList_id`) REFERENCES `remindList` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `remindList`
--
ALTER TABLE `remindList`
  ADD CONSTRAINT `remindList_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subcription`
--
ALTER TABLE `subcription`
  ADD CONSTRAINT `subcription_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subcription_ibfk_2` FOREIGN KEY (`remindList_id`) REFERENCES `remindList` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
