-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 20, 2013 at 09:52 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `remindersharetest`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=140 ;

--
-- Dumping data for table `remindList`
--

INSERT INTO `remindList` (`id`, `user_id`, `title`, `description`, `date_created`, `date_updated`, `public_token`) VALUES
(139, 244, '&lt;script&gt;alert();&lt;/script&gt;', 'dfdfdf', '2013-09-21 01:20:28', '2013-09-21 01:21:27', NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=269 ;

--
-- Dumping data for table `reminder`
--

INSERT INTO `reminder` (`id`, `remindList_id`, `name`, `description`, `date_time`) VALUES
(267, 139, 'ddddd', 'ddd', '2013-09-17 00:00:00'),
(268, 139, 'dddddddd', 'dd', '2013-09-18 00:00:00');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=245 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `user_name`, `password`, `email`) VALUES
(244, 'sam', '9f6e6800cfae7749eb6c486619254b9c', 's@s.com');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `remindList`
--
ALTER TABLE `remindList`
  ADD CONSTRAINT `remindList_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reminder`
--
ALTER TABLE `reminder`
  ADD CONSTRAINT `reminder_ibfk_1` FOREIGN KEY (`remindList_id`) REFERENCES `remindList` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subcription`
--
ALTER TABLE `subcription`
  ADD CONSTRAINT `subcription_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subcription_ibfk_2` FOREIGN KEY (`remindList_id`) REFERENCES `remindList` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
