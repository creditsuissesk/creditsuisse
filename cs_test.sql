-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2014 at 10:04 PM
-- Server version: 5.6.14
-- PHP Version: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cs_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `approve_user`
--

CREATE TABLE IF NOT EXISTS `approve_user` (
  `approve_id` tinyint(1) unsigned NOT NULL,
  `app_stat` varchar(30) NOT NULL,
  PRIMARY KEY (`approve_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `approve_user`
--

INSERT INTO `approve_user` (`approve_id`, `app_stat`) VALUES
(0, 'New User'),
(1, 'Approved User'),
(2, 'Blocked User'),
(3, 'Rejected New User');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `discussion_id` int(11) NOT NULL,
  `insert_uid` int(11) unsigned NOT NULL,
  `date_inserted_c` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_uid` int(11) unsigned DEFAULT NULL,
  `date_updated_c` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_body` text NOT NULL,
  `flag` tinyint(4) NOT NULL DEFAULT '0',
  `comment_score` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `insert_uid` (`insert_uid`),
  KEY `update_uid` (`update_uid`),
  KEY `discussion_id` (`discussion_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comment_id`, `discussion_id`, `insert_uid`, `date_inserted_c`, `update_uid`, `date_updated_c`, `comment_body`, `flag`, `comment_score`) VALUES
(1, 1, 22, '2014-03-13 13:05:20', NULL, '0000-00-00 00:00:00', ' I think it''s O(n logn) but I am not sure!', 0, 1),
(2, 1, 21, '2014-03-13 13:07:22', NULL, '0000-00-00 00:00:00', ' Yes Shaarad, Kunal is right. It''s O(n logn) because Mergesort mimics binary tree structure in it''s operation. See the Mergesort picture provided in course resources!', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `c_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `c_name` varchar(50) NOT NULL,
  `c_stream` varchar(20) NOT NULL,
  `inserted_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `u_id` int(11) unsigned NOT NULL,
  `approve_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `course_image` varchar(75) NOT NULL DEFAULT 'images/gallery/09-l.jpg',
  `avg_rating` double NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`c_id`),
  KEY `u_id` (`u_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`c_id`, `c_name`, `c_stream`, `inserted_on`, `start_date`, `end_date`, `u_id`, `approve_status`, `course_image`, `avg_rating`, `description`) VALUES
(32, 'Algorithms', 'computers', '2014-03-13 02:37:50', '2014-03-14', '2014-03-31', 21, 1, 'images/course_picture/Algorithms.png', 4, 'This is a course about basics of Algorithms and Data Structures');

-- --------------------------------------------------------

--
-- Table structure for table `course_eval`
--

CREATE TABLE IF NOT EXISTS `course_eval` (
  `c_id_eval` int(11) unsigned NOT NULL,
  `q_no` int(11) NOT NULL,
  `ques` text NOT NULL,
  `opt1` text NOT NULL,
  `opt2` text NOT NULL,
  `opt3` text NOT NULL,
  `opt4` text NOT NULL,
  `answer` tinyint(4) NOT NULL,
  PRIMARY KEY (`c_id_eval`,`q_no`),
  KEY `c_id_eval` (`c_id_eval`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `course_eval`
--

INSERT INTO `course_eval` (`c_id_eval`, `q_no`, `ques`, `opt1`, `opt2`, `opt3`, `opt4`, `answer`) VALUES
(32, 1, 'Which is the worst sorting method?', 'Mergesort', 'Insertion Sort', 'Quick Sort', 'Radix Sort', 2),
(32, 2, 'Which is not an operation on trees?', 'BFS', 'DFS', 'In-order traversal', 'Reversal', 4);

-- --------------------------------------------------------

--
-- Table structure for table `course_reco`
--

CREATE TABLE IF NOT EXISTS `course_reco` (
  `c_reco_id` int(11) unsigned NOT NULL,
  `from_u_id` int(11) unsigned NOT NULL,
  `to_u_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`c_reco_id`,`from_u_id`,`to_u_id`),
  KEY `from_u_id` (`from_u_id`),
  KEY `to_u_id` (`to_u_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `discussion`
--

CREATE TABLE IF NOT EXISTS `discussion` (
  `discussion_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL,
  `insert_uid` int(11) unsigned NOT NULL,
  `last_comment_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `disc_body` text NOT NULL,
  `count_comments` int(11) NOT NULL DEFAULT '0',
  `date_inserted_d` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated_d` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `flag` tinyint(4) NOT NULL DEFAULT '0',
  `rating` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`discussion_id`),
  KEY `category_id` (`category_id`),
  KEY `last_comment_id` (`last_comment_id`),
  KEY `insert_uid` (`insert_uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `discussion`
--

INSERT INTO `discussion` (`discussion_id`, `type`, `category_id`, `insert_uid`, `last_comment_id`, `name`, `disc_body`, `count_comments`, `date_inserted_d`, `date_updated_d`, `flag`, `rating`) VALUES
(1, 0, 6, 19, 0, 'Complexity of mergesort', ' What exactly is the complexity of mergesort? Is it O(n^2) or O(n logn) ? I think it''s O(n logn) but I am not very sure about it. Can someone explain why?', 2, '2014-03-13 12:20:56', '2014-03-13 13:07:22', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `discussion_category`
--

CREATE TABLE IF NOT EXISTS `discussion_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` text NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `discussion_category`
--

INSERT INTO `discussion_category` (`category_id`, `category_name`) VALUES
(6, 'Algorithms');

-- --------------------------------------------------------

--
-- Table structure for table `enroll_course`
--

CREATE TABLE IF NOT EXISTS `enroll_course` (
  `u_id` int(10) unsigned NOT NULL,
  `c_enroll_id` int(10) unsigned NOT NULL,
  `completion_stat` tinyint(1) NOT NULL DEFAULT '0',
  `a_stat` tinyint(1) NOT NULL DEFAULT '0',
  `marks` int(11) NOT NULL DEFAULT '-1',
  `rating` decimal(2,1) DEFAULT '0.0',
  PRIMARY KEY (`u_id`,`c_enroll_id`),
  KEY `c_id` (`c_enroll_id`),
  KEY `u_id` (`u_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `enroll_course`
--

INSERT INTO `enroll_course` (`u_id`, `c_enroll_id`, `completion_stat`, `a_stat`, `marks`, `rating`) VALUES
(19, 32, 0, 1, -1, '0.0'),
(22, 32, 1, 1, 20, '4.0');

-- --------------------------------------------------------

--
-- Table structure for table `resource`
--

CREATE TABLE IF NOT EXISTS `resource` (
  `r_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `c_id` int(10) unsigned NOT NULL,
  `type_id` int(10) unsigned NOT NULL,
  `filename` varchar(30) NOT NULL,
  `file_type` varchar(30) NOT NULL,
  `file_size` double NOT NULL,
  `file_location` varchar(255) NOT NULL,
  `view_location` varchar(255) NOT NULL,
  `uploaded_by` int(11) unsigned NOT NULL,
  `uploaded_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `download_status` tinyint(1) NOT NULL DEFAULT '0',
  `approve_status` tinyint(1) NOT NULL DEFAULT '0',
  `avg_rating` decimal(2,1) DEFAULT '0.0',
  `flag_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`r_id`),
  KEY `c_id` (`c_id`),
  KEY `type_id` (`type_id`),
  KEY `uploaded_by` (`uploaded_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `resource`
--

INSERT INTO `resource` (`r_id`, `c_id`, `type_id`, `filename`, `file_type`, `file_size`, `file_location`, `view_location`, `uploaded_by`, `uploaded_date`, `download_status`, `approve_status`, `avg_rating`, `flag_status`) VALUES
(6, 32, 7, 'Mergesort representation.png', 'image/png', 0.011210441589355, 'resource/32/Mergesort representation.png', 'resource/32-s/mergesort.swf', 21, '2014-03-13 17:56:28', 1, 1, '0.0', 0),
(7, 32, 2, 'lecture 1.mp4', 'video/mp4', 9.5926952362061, 'resource/32/lecture 1.mp4', '', 21, '2014-03-13 18:07:51', 1, 1, '0.0', 0),
(8, 32, 3, 'how mergesort works.ppt', 'application/vnd.ms-powerpoint', 0.27880859375, 'resource/32/how mergesort works.ppt', 'resource/32-s/03-dc.swf', 21, '2014-03-13 18:21:24', 0, 1, '0.0', 1);

-- --------------------------------------------------------

--
-- Table structure for table `resource_type`
--

CREATE TABLE IF NOT EXISTS `resource_type` (
  `type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `r_type` varchar(20) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `resource_type`
--

INSERT INTO `resource_type` (`type_id`, `r_type`) VALUES
(1, 'book'),
(2, 'video lectures'),
(3, 'slides'),
(4, 'research papers'),
(5, 'self written papers'),
(6, 'notes'),
(7, 'others');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `u_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `u_name` varchar(30) NOT NULL,
  `password` varchar(64) NOT NULL,
  `f_name` varchar(20) NOT NULL,
  `l_name` varchar(20) NOT NULL,
  `contact_no` int(10) NOT NULL,
  `dob` date NOT NULL,
  `institute` varchar(40) NOT NULL,
  `stream` varchar(20) NOT NULL,
  `degree` varchar(50) NOT NULL DEFAULT 'B.E.',
  `role` varchar(10) NOT NULL,
  `approve_id` tinyint(1) NOT NULL DEFAULT '0',
  `photo` varchar(255) NOT NULL,
  `about` text NOT NULL,
  `user_score` float NOT NULL DEFAULT '0',
  `count_bookmarks` int(11) NOT NULL DEFAULT '0',
  `created_comments` int(11) NOT NULL DEFAULT '0',
  `count_discussions` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`u_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`u_id`, `u_name`, `password`, `f_name`, `l_name`, `contact_no`, `dob`, `institute`, `stream`, `degree`, `role`, `approve_id`, `photo`, `about`, `user_score`, `count_bookmarks`, `created_comments`, `count_discussions`) VALUES
(18, 'sidsh@gmail.com', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'Siddharth', 'Shah', 987654321, '2014-03-02', 'VJTI', 'Computers', 'B.E.', 'admin', 1, 'images/profiles/18.jpg', 'something', 0, 0, 0, 0),
(19, 'sh@yahoo.co.in', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'Shaarad', 'Dalvi', 1234567890, '2014-03-01', 'vjti', 'Computers', 'B.E.', 'student', 1, 'images/profiles/19.jpg', 'temp', 1, 0, 0, 1),
(20, 'aksrat@tech.org', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'Akshay', 'Rathod', 1234567890, '2014-03-03', 'vjti', 'Computers', 'B.E.', 'cm', 1, 'images/profiles/20.jpg', 'temp', 0, 0, 0, 0),
(21, 'inamtan@gmail.com', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'Tanmay', 'Inamdar', 1234567890, '2014-03-03', 'VJTI', 'Computers', 'B.E.', 'author', 1, 'images/profiles/21.jpg', 'about', 9, 0, 1, 0),
(22, 'kunsh@gmail.com', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'Kunal', 'Shah', 1234567890, '2013-09-23', 'VJTI', 'Computers', 'B.Tech.', 'student', 1, 'images/profiles/22.jpg', 'I am Kunal Shah', 1, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_comment`
--

CREATE TABLE IF NOT EXISTS `user_comment` (
  `user_comment_id` int(11) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `vote_status` tinyint(4) NOT NULL DEFAULT '0',
  `bookmarked` tinyint(4) NOT NULL DEFAULT '0',
  `date_last_viewed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`user_comment_id`),
  KEY `comment_id` (`user_comment_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_comment`
--

INSERT INTO `user_comment` (`user_comment_id`, `user_id`, `vote_status`, `bookmarked`, `date_last_viewed`) VALUES
(1, 19, 1, 0, '2014-03-13 13:08:44'),
(2, 19, 1, 0, '2014-03-13 13:08:44'),
(1, 21, 0, 0, '2014-03-13 13:07:23'),
(2, 21, 0, 0, '2014-03-13 13:07:23'),
(1, 22, 0, 0, '2014-03-13 13:05:30');

-- --------------------------------------------------------

--
-- Table structure for table `user_discussion`
--

CREATE TABLE IF NOT EXISTS `user_discussion` (
  `u_id` int(11) unsigned NOT NULL,
  `user_discussion_id` int(11) NOT NULL,
  `seen_comments` int(11) NOT NULL,
  `date_last_viewed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bookmarked` tinyint(4) NOT NULL DEFAULT '0',
  `vote_status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`u_id`,`user_discussion_id`),
  KEY `u_id` (`u_id`),
  KEY `discussion_id` (`user_discussion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_discussion`
--

INSERT INTO `user_discussion` (`u_id`, `user_discussion_id`, `seen_comments`, `date_last_viewed`, `bookmarked`, `vote_status`) VALUES
(19, 1, 2, '2014-03-13 13:08:44', 0, 0),
(21, 1, 2, '2014-03-13 13:07:23', 0, 0),
(22, 1, 1, '2014-03-13 13:05:32', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_resource`
--

CREATE TABLE IF NOT EXISTS `user_resource` (
  `user_resource_id` int(10) unsigned NOT NULL,
  `u_id` int(10) unsigned NOT NULL,
  `rating` decimal(2,1) DEFAULT NULL,
  `bookmarked` tinyint(4) NOT NULL DEFAULT '0',
  `date_last_viewed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_resource_id`,`u_id`),
  KEY `u_id` (`u_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_resource`
--

INSERT INTO `user_resource` (`user_resource_id`, `u_id`, `rating`, `bookmarked`, `date_last_viewed`) VALUES
(7, 22, '0.0', 1, '2014-03-13 20:26:08'),
(8, 22, '0.0', 1, '2014-03-13 20:26:15');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`insert_uid`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_3` FOREIGN KEY (`update_uid`) REFERENCES `user` (`u_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_4` FOREIGN KEY (`discussion_id`) REFERENCES `discussion` (`discussion_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `course_eval`
--
ALTER TABLE `course_eval`
  ADD CONSTRAINT `course_eval_ibfk_1` FOREIGN KEY (`c_id_eval`) REFERENCES `course` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `course_reco`
--
ALTER TABLE `course_reco`
  ADD CONSTRAINT `course_reco_ibfk_1` FOREIGN KEY (`c_reco_id`) REFERENCES `course` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `course_reco_ibfk_2` FOREIGN KEY (`from_u_id`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `course_reco_ibfk_3` FOREIGN KEY (`to_u_id`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `discussion`
--
ALTER TABLE `discussion`
  ADD CONSTRAINT `discussion_ibfk_3` FOREIGN KEY (`insert_uid`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `discussion_ibfk_4` FOREIGN KEY (`category_id`) REFERENCES `discussion_category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `enroll_course`
--
ALTER TABLE `enroll_course`
  ADD CONSTRAINT `enroll_course_ibfk_1` FOREIGN KEY (`c_enroll_id`) REFERENCES `course` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `enroll_course_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `resource`
--
ALTER TABLE `resource`
  ADD CONSTRAINT `resource_ibfk_1` FOREIGN KEY (`c_id`) REFERENCES `course` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `resource_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `resource_type` (`type_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `resource_ibfk_3` FOREIGN KEY (`uploaded_by`) REFERENCES `user` (`u_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `user_comment`
--
ALTER TABLE `user_comment`
  ADD CONSTRAINT `user_comment_ibfk_1` FOREIGN KEY (`user_comment_id`) REFERENCES `comment` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_discussion`
--
ALTER TABLE `user_discussion`
  ADD CONSTRAINT `user_discussion_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_discussion_ibfk_2` FOREIGN KEY (`user_discussion_id`) REFERENCES `discussion` (`discussion_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_resource`
--
ALTER TABLE `user_resource`
  ADD CONSTRAINT `user_resource_ibfk_1` FOREIGN KEY (`user_resource_id`) REFERENCES `resource` (`r_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_resource_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
