-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2014 at 01:32 PM
-- Server version: 5.6.14
-- PHP Version: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `credit_suisse`
--

-- --------------------------------------------------------

--
-- Table structure for table `approved_status`
--

CREATE TABLE IF NOT EXISTS `approved_status` (
  `r_id` int(10) unsigned NOT NULL,
  `app_status` tinyint(1) NOT NULL,
  `u_id` int(10) unsigned NOT NULL,
  KEY `r_id` (`r_id`),
  KEY `u_id` (`u_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `delete_uid` int(11) unsigned DEFAULT NULL,
  `date_deleted_c` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_uid` int(11) unsigned DEFAULT NULL,
  `date_updated_c` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_body` text NOT NULL,
  `flag` tinyint(4) NOT NULL DEFAULT '0',
  `comment_score` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `insert_uid` (`insert_uid`),
  KEY `delete_uid` (`delete_uid`),
  KEY `update_uid` (`update_uid`),
  KEY `discussion_id` (`discussion_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comment_id`, `discussion_id`, `insert_uid`, `date_inserted_c`, `delete_uid`, `date_deleted_c`, `update_uid`, `date_updated_c`, `comment_body`, `flag`, `comment_score`) VALUES
(2, 2, 5, '2014-01-19 06:40:07', NULL, '0000-00-00 00:00:00', NULL, '0000-00-00 00:00:00', 'This is comment body!', 0, 3),
(3, 2, 3, '2014-01-19 22:30:31', NULL, '0000-00-00 00:00:00', NULL, '0000-00-00 00:00:00', 'new Comment!', 0, -1),
(5, 4, 5, '2014-01-30 11:31:44', NULL, '0000-00-00 00:00:00', NULL, '0000-00-00 00:00:00', ' lol!', 0, 0),
(6, 2, 5, '2014-01-30 11:46:53', NULL, '0000-00-00 00:00:00', NULL, '0000-00-00 00:00:00', ' this is posted from forums!', 0, 0),
(7, 8, 5, '2014-01-30 12:02:25', NULL, '0000-00-00 00:00:00', NULL, '0000-00-00 00:00:00', ' temp comment to test redirection!', 0, 0),
(8, 2, 12, '2014-01-30 12:51:49', NULL, '0000-00-00 00:00:00', NULL, '0000-00-00 00:00:00', ' comment by photouser!', 0, 0),
(9, 10, 12, '2014-01-31 08:55:16', NULL, '0000-00-00 00:00:00', NULL, '0000-00-00 00:00:00', ' hmmm...looks like it is being updated. now testing if the comment count is being updated or not...', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `c_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `c_name` varchar(50) NOT NULL,
  `c_stream` varchar(20) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `avg_rating` double NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`c_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`c_id`, `c_name`, `c_stream`, `start_date`, `end_date`, `avg_rating`, `description`) VALUES
(18, 'cs50', 'CS', '2014-01-01', '2014-03-01', 0, 'This is CS50!'),
(19, 'NewNet', 'IT', '2014-05-01', '0000-00-00', 0, ''),
(20, 'Crypto', 'CS', '2014-01-22', '2014-01-25', 0, ''),
(21, 'new', 'new', '2014-01-03', '2014-01-17', 0, ''),
(22, 'newcourse', 'cs', '2014-01-16', '2014-01-18', 0, 'Description!');

-- --------------------------------------------------------

--
-- Table structure for table `create_course`
--

CREATE TABLE IF NOT EXISTS `create_course` (
  `u_id` int(11) unsigned NOT NULL,
  `c_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`c_id`),
  UNIQUE KEY `c_id` (`c_id`),
  KEY `u_id_2` (`u_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `create_course`
--

INSERT INTO `create_course` (`u_id`, `c_id`) VALUES
(2, 18),
(2, 19),
(2, 20),
(2, 21),
(2, 22);

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
  `date_updated_d` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rating` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`discussion_id`),
  KEY `category_id` (`category_id`),
  KEY `last_comment_id` (`last_comment_id`),
  KEY `insert_uid` (`insert_uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `discussion`
--

INSERT INTO `discussion` (`discussion_id`, `type`, `category_id`, `insert_uid`, `last_comment_id`, `name`, `disc_body`, `count_comments`, `date_inserted_d`, `date_updated_d`, `rating`) VALUES
(2, 0, 1, 5, 0, 'First discussion', 'First discussion body!', 2, '2014-01-19 04:13:19', '0000-00-00 00:00:00', 7),
(3, 0, 1, 8, 0, 'This is by Kunal', 'Kunal''s body! :P', 0, '2014-01-19 04:38:46', '0000-00-00 00:00:00', 1),
(4, 0, 2, 5, 0, 'Is this forum style okay?', 'We''ll continue this is it looks good..', 1, '2014-01-19 04:42:45', '0000-00-00 00:00:00', -1),
(8, 0, 1, 5, 0, 'new disc title', 'new disc body ', 1, '2014-01-30 09:12:29', '0000-00-00 00:00:00', 0),
(9, 0, 1, 5, 0, 'testing new discussion', 'discussion body ', 0, '2014-01-30 11:58:56', '0000-00-00 00:00:00', 0),
(10, 0, 1, 12, 0, 'testing update count', ' This discussion is being opened to test if the user''s discussion count is being updated or not.', 1, '2014-01-31 08:54:19', '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `discussion_category`
--

CREATE TABLE IF NOT EXISTS `discussion_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` text NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `discussion_category`
--

INSERT INTO `discussion_category` (`category_id`, `category_name`) VALUES
(1, 'General'),
(2, 'Doubts');

-- --------------------------------------------------------

--
-- Table structure for table `enroll_course`
--

CREATE TABLE IF NOT EXISTS `enroll_course` (
  `u_id` int(10) unsigned NOT NULL,
  `c_id` int(10) unsigned NOT NULL,
  `completion_stat` tinyint(1) NOT NULL,
  KEY `c_id` (`c_id`),
  KEY `u_id` (`u_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `enroll_course`
--

INSERT INTO `enroll_course` (`u_id`, `c_id`, `completion_stat`) VALUES
(5, 18, 0),
(4, 18, 0),
(8, 18, 0),
(5, 21, 0);

-- --------------------------------------------------------

--
-- Table structure for table `rate_resource`
--

CREATE TABLE IF NOT EXISTS `rate_resource` (
  `u_id` int(10) unsigned NOT NULL,
  `r_id` int(10) unsigned NOT NULL,
  `rating` double unsigned NOT NULL,
  KEY `r_id` (`r_id`),
  KEY `u_id` (`u_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `uploaded_by` int(10) unsigned NOT NULL,
  `uploaded_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `avg_rating` double unsigned NOT NULL DEFAULT '0',
  `flag_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`r_id`),
  UNIQUE KEY `uploaded_by` (`uploaded_by`),
  KEY `c_id` (`c_id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
(1, 'books'),
(2, 'video_lectures'),
(3, 'slides'),
(4, 'research_papers'),
(5, 'self_written_papers'),
(6, 'notes'),
(7, 'others');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `u_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `u_name` varchar(30) NOT NULL,
  `password` varchar(20) NOT NULL,
  `f_name` varchar(20) NOT NULL,
  `l_name` varchar(20) NOT NULL,
  `contact_no` int(10) NOT NULL,
  `dob` date NOT NULL,
  `institute` varchar(40) NOT NULL,
  `stream` varchar(20) NOT NULL,
  `role` varchar(10) NOT NULL,
  `approve_id` tinyint(1) NOT NULL DEFAULT '0',
  `photo` varchar(255) NOT NULL,
  `about` text NOT NULL,
  `show_email` tinyint(4) NOT NULL DEFAULT '0',
  `gender` tinyint(4) NOT NULL DEFAULT '0',
  `user_score` float NOT NULL DEFAULT '0',
  `count_bookmarks` int(11) NOT NULL DEFAULT '0',
  `created_comments` int(11) NOT NULL DEFAULT '0',
  `count_discussions` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`u_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`u_id`, `u_name`, `password`, `f_name`, `l_name`, `contact_no`, `dob`, `institute`, `stream`, `role`, `approve_id`, `photo`, `about`, `show_email`, `gender`, `user_score`, `count_bookmarks`, `created_comments`, `count_discussions`) VALUES
(1, 'xyz@gmail.com', 'qwerty', 'a', 'b', 123, '2009-01-13', 'vb', 'cs', 'student', 1, 'images/profiles/01.jpg', '', 0, 0, 0, 0, 0, 0),
(2, 'abc@tech.org', 'qwerty', 'aaa', 'bbb', 4321, '1997-04-02', 'vj', 'it', 'author', 1, 'images/profiles/02.jpg', '', 0, 0, 0, 0, 0, 0),
(3, 'dalvishaarad@gmail.c', 'password', 'Shaarad', 'Dalvi', 2147483647, '2012-04-01', 'vjti', 'comps', 'student', 1, 'images/profiles/03.jpg', '', 0, 0, 0, 0, 0, 0),
(4, 'shaaraddalvi@outlook.com', 'password', 'Shaarad', 'Dalvi', 25406266, '1993-11-01', 'vjti', 'comps', 'student', 1, 'images/profiles/04.jpg', '', 0, 0, 0, 0, 0, 0),
(5, 'sh@yahoo.co.in', 'password', 'shaarad', 'dalvi', 25406266, '2014-11-01', 'vjti', 'comp', 'student', 1, 'images/profiles/05.jpg', '', 0, 0, 4, 0, 0, 0),
(6, 'root', 'rootpass', 'root', 'root', 12345678, '2014-01-01', 'root', 'root', 'admin', 1, 'images/profiles/06.jpg', '', 0, 0, 0, 0, 0, 0),
(7, 'cm', 'qwerty', 'cm', 'cm', 1234567890, '2014-01-05', 'vjti', 'comp', 'cm', 1, 'images/profiles/07.jpg', '', 0, 0, 0, 0, 0, 0),
(8, 'kunalshah', 'pass1234', 'Kunal', 'Shah', 876543321, '2014-01-03', 'veermata JTI', 'computers', 'student', 1, 'images/profiles/08.jpg', '', 0, 0, 0, 0, 0, 0),
(9, 'new1@gmail.com', 'password', 'new1name', 'new1surname', 982680350, '2014-01-01', 'vjti', 'comp', 'student', 2, '', '', 0, 0, 0, 0, 0, 0),
(10, 'new2@gmail.com', 'password', 'new2', 'new2surname', 2147483647, '2014-01-02', 'vjti', 'comp', 'student', 0, '', '', 0, 0, 0, 0, 0, 0),
(11, 'new3', 'password', 'new3fname', 'new3lname', 1234567890, '2013-12-04', 'vjti', 'comp', 'student', 0, '', '', 0, 0, 0, 0, 0, 0),
(12, 'photouser@gmail.com', 'password', 'photof', 'photol', 987654321, '2014-01-06', 'insti', 'comp', 'student', 1, 'images/profiles/lamborghini-cars-logo-emblem.jpg', '', 0, 0, 0, 0, 1, 1);

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
(2, 1, 1, 0, '2014-01-24 07:06:52'),
(3, 1, -1, 0, '2014-01-24 07:06:52'),
(2, 5, 0, 0, '2014-01-31 10:58:41'),
(3, 5, 0, 0, '2014-01-31 10:58:41'),
(5, 5, 0, 0, '2014-01-30 11:47:38'),
(6, 5, 0, 0, '2014-01-31 10:58:41'),
(7, 5, 0, 0, '2014-01-30 12:02:25'),
(8, 5, 0, 0, '2014-01-31 10:58:41'),
(2, 12, 0, 0, '2014-02-03 11:38:18'),
(3, 12, -1, 0, '2014-02-03 11:38:18'),
(5, 12, 0, 0, '2014-02-03 11:53:00'),
(6, 12, 0, 0, '2014-02-03 11:38:18'),
(7, 12, 0, 0, '2014-02-03 09:56:01'),
(8, 12, 0, 0, '2014-02-03 11:38:18'),
(9, 12, 0, 0, '2014-01-31 08:55:17');

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
(1, 2, 2, '2014-01-24 07:06:52', 0, 0),
(1, 3, 0, '2014-01-24 07:27:27', 0, 0),
(1, 4, 0, '2014-01-24 07:27:39', 0, 0),
(5, 2, 4, '2014-01-31 10:58:41', 0, 0),
(5, 3, 0, '2014-01-30 11:37:26', 0, 0),
(5, 4, 1, '2014-01-30 11:47:38', 0, 0),
(5, 8, 1, '2014-01-30 12:02:25', 0, 0),
(5, 9, 0, '2014-01-30 11:59:18', 0, 0),
(12, 2, 4, '2014-02-03 11:38:18', 1, 0),
(12, 4, 1, '2014-02-03 11:53:00', 0, 0),
(12, 8, 1, '2014-02-03 09:56:01', 0, 0),
(12, 9, 0, '2014-02-03 09:46:22', 0, 0),
(12, 10, 1, '2014-01-31 08:55:17', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_resource`
--

CREATE TABLE IF NOT EXISTS `user_resource` (
  `u_id` int(11) unsigned NOT NULL,
  `r_id` int(11) unsigned NOT NULL,
  `download_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`u_id`,`r_id`),
  KEY `r_id` (`r_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approved_status`
--
ALTER TABLE `approved_status`
  ADD CONSTRAINT `approved_status_ibfk_1` FOREIGN KEY (`r_id`) REFERENCES `resource` (`r_id`),
  ADD CONSTRAINT `approved_status_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`);

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`insert_uid`) REFERENCES `user` (`u_id`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`delete_uid`) REFERENCES `user` (`u_id`),
  ADD CONSTRAINT `comment_ibfk_3` FOREIGN KEY (`update_uid`) REFERENCES `user` (`u_id`),
  ADD CONSTRAINT `comment_ibfk_4` FOREIGN KEY (`discussion_id`) REFERENCES `discussion` (`discussion_id`);

--
-- Constraints for table `create_course`
--
ALTER TABLE `create_course`
  ADD CONSTRAINT `c_id_key` FOREIGN KEY (`c_id`) REFERENCES `course` (`c_id`);

--
-- Constraints for table `discussion`
--
ALTER TABLE `discussion`
  ADD CONSTRAINT `discussion_ibfk_3` FOREIGN KEY (`insert_uid`) REFERENCES `user` (`u_id`),
  ADD CONSTRAINT `discussion_ibfk_4` FOREIGN KEY (`category_id`) REFERENCES `discussion_category` (`category_id`);

--
-- Constraints for table `enroll_course`
--
ALTER TABLE `enroll_course`
  ADD CONSTRAINT `enroll_course_ibfk_1` FOREIGN KEY (`c_id`) REFERENCES `course` (`c_id`),
  ADD CONSTRAINT `enroll_course_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`);

--
-- Constraints for table `rate_resource`
--
ALTER TABLE `rate_resource`
  ADD CONSTRAINT `rate_resource_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`);

--
-- Constraints for table `resource`
--
ALTER TABLE `resource`
  ADD CONSTRAINT `resource_ibfk_1` FOREIGN KEY (`c_id`) REFERENCES `course` (`c_id`),
  ADD CONSTRAINT `resource_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `resource_type` (`type_id`),
  ADD CONSTRAINT `resource_ibfk_3` FOREIGN KEY (`uploaded_by`) REFERENCES `user` (`u_id`);

--
-- Constraints for table `user_comment`
--
ALTER TABLE `user_comment`
  ADD CONSTRAINT `user_comment_ibfk_1` FOREIGN KEY (`user_comment_id`) REFERENCES `comment` (`comment_id`),
  ADD CONSTRAINT `user_comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`u_id`);

--
-- Constraints for table `user_discussion`
--
ALTER TABLE `user_discussion`
  ADD CONSTRAINT `user_discussion_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`),
  ADD CONSTRAINT `user_discussion_ibfk_2` FOREIGN KEY (`user_discussion_id`) REFERENCES `discussion` (`discussion_id`);

--
-- Constraints for table `user_resource`
--
ALTER TABLE `user_resource`
  ADD CONSTRAINT `user_resource_ibfk_2` FOREIGN KEY (`r_id`) REFERENCES `resource` (`r_id`),
  ADD CONSTRAINT `user_resource_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
