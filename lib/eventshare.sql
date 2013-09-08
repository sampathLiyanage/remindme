-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 02, 2013 at 08:33 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `eventshare`
--

-- --------------------------------------------------------

--
-- Table structure for table `todoList`
--

CREATE TABLE IF NOT EXISTS `todoList` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `todoList_event`
--

CREATE TABLE IF NOT EXISTS `todoList_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `todoList_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `date_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `todoList_id` (`todoList_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `todoList_subcription`
--

CREATE TABLE IF NOT EXISTS `todoList_subcription` (
  `user_id` int(11) NOT NULL,
  `todoList_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`todoList_id`),
  KEY `todoList_id` (`todoList_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `todo_event_reminder`
--

CREATE TABLE IF NOT EXISTS `todo_event_reminder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `todo_event_id` int(11) NOT NULL,
  `date_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `todo_event_id` (`todo_event_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `todoList`
--
ALTER TABLE `todoList`
  ADD CONSTRAINT `todoList_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `todoList_event`
--
ALTER TABLE `todoList_event`
  ADD CONSTRAINT `todoList_event_ibfk_1` FOREIGN KEY (`todoList_id`) REFERENCES `todoList` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `todoList_subcription`
--
ALTER TABLE `todoList_subcription`
  ADD CONSTRAINT `todoList_subcription_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `todoList_subcription_ibfk_2` FOREIGN KEY (`todoList_id`) REFERENCES `todoList` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `todo_event_reminder`
--
ALTER TABLE `todo_event_reminder`
  ADD CONSTRAINT `todo_event_reminder_ibfk_1` FOREIGN KEY (`todo_event_id`) REFERENCES `todoList_event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
