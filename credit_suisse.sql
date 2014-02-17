-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 17, 2014 at 08:02 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comment_id`, `discussion_id`, `insert_uid`, `date_inserted_c`, `update_uid`, `date_updated_c`, `comment_body`, `flag`, `comment_score`) VALUES
(2, 2, 5, '2014-01-19 06:40:07', NULL, '0000-00-00 00:00:00', 'This is comment body!', 0, 1),
(3, 2, 3, '2014-01-19 22:30:31', NULL, '0000-00-00 00:00:00', 'new Comment!', 0, 1),
(5, 4, 5, '2014-01-30 11:31:44', NULL, '0000-00-00 00:00:00', ' lol!', 0, 0),
(6, 2, 5, '2014-01-30 11:46:53', NULL, '0000-00-00 00:00:00', ' this is posted from forums!', 0, 0),
(7, 8, 5, '2014-01-30 12:02:25', NULL, '0000-00-00 00:00:00', ' temp comment to test redirection!', 0, 0),
(8, 2, 12, '2014-01-30 12:51:49', NULL, '0000-00-00 00:00:00', ' comment by photouser!', 0, -1),
(9, 10, 12, '2014-01-31 08:55:16', NULL, '0000-00-00 00:00:00', ' hmmm...looks like it is being updated. now testing if the comment count is being updated or not...', 0, 0),
(10, 2, 1, '2014-02-08 05:04:57', NULL, '0000-00-00 00:00:00', ' this is worst discussion i had', 0, 0),
(11, 4, 1, '2014-02-09 11:31:29', NULL, '0000-00-00 00:00:00', ' this is new comment to test bookmark comment notification', 0, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`c_id`, `c_name`, `c_stream`, `inserted_on`, `start_date`, `end_date`, `u_id`, `approve_status`, `course_image`, `avg_rating`, `description`) VALUES
(18, 'Cryptography-Basic', 'Computer Science', '2014-01-03 16:12:36', '2014-01-12', '2016-01-11', 2, 1, 'images/gallery/09-l.jpg', 0, 'This is course on cryptography. In this course we will introduce you to basic ciphers and the basics of cryptography.'),
(19, 'Networking Protocols', 'Networking', '2014-02-27 16:12:36', '2014-05-01', '2014-10-31', 2, 0, 'images/gallery/07.jpg', 0, ''),
(20, 'Cryptography-Advanced', 'Computer Science', '2014-01-15 16:12:36', '2014-01-22', '2014-07-17', 2, 1, 'images/gallery/05.jpg', 0, 'this is crypto'),
(21, 'Web Designing', 'Computer Science', '2014-02-14 16:12:36', '2014-01-03', '2014-07-17', 2, 1, 'images/gallery/03.jpg', 0, ''),
(22, 'Database Management', 'Computer Science', '2014-02-14 16:12:36', '2014-01-16', '2014-08-18', 2, 0, 'images/gallery/03.jpg', 0, 'Description!'),
(23, 'Database-Advanced', 'Computer Science', '2014-02-14 16:12:36', '2014-03-06', '2014-08-05', 2, 0, 'images/gallery/04.jpg', 0, 'This course is based n advance techniques of using database'),
(27, 'PHP', 'Computer Science', '2014-02-14 16:12:36', '2014-02-20', '2017-02-20', 2, 0, 'images/course_picture/PHP.jpg', 0, 'this is basic course'),
(28, 'Java Scripts', 'Computer Science', '2014-02-14 16:12:36', '2015-02-12', '2015-02-27', 2, 0, 'images/course_picture/Java Scripts.jpg', 0, 'Basics of Java Scripting'),
(29, 'Data Struct', 'Computer', '2014-02-08 16:12:36', '2014-02-14', '2014-02-21', 13, 1, 'images/course_picture/Data Struct.jpg', 0, 'ahjd'),
(30, 'temp_course', 'cs', '2014-02-12 16:12:36', '2014-03-06', '2014-02-22', 2, 1, 'images/course_picture/temp_course.jpg', 0, 'temp');

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
(18, 1, 'Which is asymmetric?', 'DES', 'AES', 'RSA', 'IDEA', 3),
(18, 2, 'Which is not a hash algorithm?', 'SHA', 'MD5', 'RSA', 'SHA-256', 3),
(18, 3, 'Which of these is least secure?', 'Caesar Cipher', 'Blowfish', 'DES', 'AES', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `discussion`
--

INSERT INTO `discussion` (`discussion_id`, `type`, `category_id`, `insert_uid`, `last_comment_id`, `name`, `disc_body`, `count_comments`, `date_inserted_d`, `date_updated_d`, `flag`, `rating`) VALUES
(2, 0, 1, 5, 0, 'First discussion', 'First discussion body!', 3, '2014-01-19 04:13:19', '0000-00-00 00:00:00', 0, 6),
(3, 0, 1, 8, 0, 'This is by Kunal', 'Kunal''s body! :P', 0, '2014-01-19 04:38:46', '0000-00-00 00:00:00', 0, 1),
(4, 0, 2, 5, 0, 'Is this forum style okay?', 'We''ll continue this is it looks good..', 2, '2014-01-19 04:42:45', '2014-02-09 11:31:29', 0, -1),
(8, 0, 1, 5, 0, 'new disc title', 'new disc body ', 1, '2014-01-30 09:12:29', '0000-00-00 00:00:00', 0, 0),
(9, 0, 1, 5, 0, 'testing new discussion', 'discussion body ', 0, '2014-01-30 11:58:56', '0000-00-00 00:00:00', 0, 0),
(10, 0, 1, 12, 0, 'testing update count', ' This discussion is being opened to test if the user''s discussion count is being updated or not.', 1, '2014-01-31 08:54:19', '0000-00-00 00:00:00', 0, 0);

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
  `c_enroll_id` int(10) unsigned NOT NULL,
  `completion_stat` tinyint(1) NOT NULL,
  `marks` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`u_id`,`c_enroll_id`),
  KEY `c_id` (`c_enroll_id`),
  KEY `u_id` (`u_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `enroll_course`
--

INSERT INTO `enroll_course` (`u_id`, `c_enroll_id`, `completion_stat`, `marks`) VALUES
(4, 18, 0, -1),
(5, 18, 0, 30),
(5, 21, 0, -1),
(8, 18, 0, -1);

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
  `view_location` varchar(255) NOT NULL,
  `uploaded_by` int(11) unsigned NOT NULL,
  `uploaded_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `download_status` tinyint(1) NOT NULL DEFAULT '0',
  `approve_status` tinyint(1) NOT NULL DEFAULT '0',
  `avg_rating` double unsigned NOT NULL DEFAULT '0',
  `flag_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`r_id`),
  KEY `c_id` (`c_id`),
  KEY `type_id` (`type_id`),
  KEY `uploaded_by` (`uploaded_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `resource`
--

INSERT INTO `resource` (`r_id`, `c_id`, `type_id`, `filename`, `file_type`, `file_size`, `file_location`, `view_location`, `uploaded_by`, `uploaded_date`, `download_status`, `approve_status`, `avg_rating`, `flag_status`) VALUES
(1, 18, 1, 'Reference.pdf', 'application/pdf', 5.3696355819702, 'resource/18/Reference.pdf', 'resource/18-s/abstract.swf', 2, '2014-02-09 00:15:49', 1, 1, 0, 0),
(2, 18, 7, 'Example.jpg', 'image/jpeg', 0.13896942138672, 'resource/18/Example.jpg', 'resource/18-s/des.swf', 2, '2014-02-09 00:17:09', 0, 1, 0, 0),
(3, 20, 1, 'reference.pdf', 'application/pdf', 102.67518806458, 'resource/20/reference.pdf', '', 2, '2014-02-12 17:17:39', 1, 0, 0, 0);

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
  `degree` varchar(50) NOT NULL DEFAULT 'B.E.',
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`u_id`, `u_name`, `password`, `f_name`, `l_name`, `contact_no`, `dob`, `institute`, `stream`, `degree`, `role`, `approve_id`, `photo`, `about`, `show_email`, `gender`, `user_score`, `count_bookmarks`, `created_comments`, `count_discussions`) VALUES
(1, 'xyz@gmail.com', 'qwerty', 'Abdul', 'Shaikh', 2147483647, '1993-03-01', 'SPIT', 'Computers', 'B.E.', 'student', 1, 'images/profiles/01.jpg', '', 0, 0, 0, 0, 2, 0),
(2, 'abc@tech.org', 'qwerty', 'Abhishek', 'Chaturvedi', 26845172, '1979-04-02', 'VJTI', 'Information Technolo', 'PHD in cryptography and Security', 'author', 1, 'images/profiles/02.jpg', '', 0, 0, 0, 0, 0, 0),
(3, 'dalvishaarad@gmail.c', 'password', 'Shaarad', 'Dalvi', 2147483647, '1992-04-01', 'vjti', 'comps', 'B.E.', 'student', 1, 'images/profiles/03.jpg', '', 0, 0, 2, 0, 0, 0),
(4, 'shaaraddalvi@outlook.com', 'password', 'Shaarad', 'Inamdar', 25406266, '1993-11-01', 'TSEC', 'Electronics', 'B.E.', 'student', 1, 'images/profiles/04.jpg', '', 0, 0, 0, 0, 0, 0),
(5, 'sh@yahoo.co.in', 'password', 'sahil', 'shah', 25406858, '1994-01-18', 'vjti', 'comp', 'B.E.', 'student', 1, 'images/profiles/05.jpg', '', 0, 0, 1, 0, 0, 0),
(6, 'root', 'rootpass', 'root', 'root', 2147483647, '1964-08-25', 'root', 'root', 'B.E.', 'admin', 1, 'images/profiles/06.jpg', '', 0, 0, 0, 0, 0, 0),
(8, 'kunalshah@gmail.com', 'pass1234', 'Kunal', 'Shah', 2147483647, '1993-07-17', 'VJTI', 'computers', 'B.E.', 'student', 1, 'images/profiles/08.jpg', '', 0, 0, 0, 0, 0, 0),
(9, 'nw@gmail.com', 'password', 'Nachiket', 'wagle', 982680350, '1993-04-07', 'vjti', 'Civil', 'B.E.', 'student', 2, '', '', 0, 0, 0, 0, 0, 0),
(10, 'new@gmail.com', 'password', 'Niket', 'wagle', 21474836, '1991-12-09', 'vjti', 'mechanical', 'B.E.', 'student', 0, '', '', 0, 0, 0, 0, 0, 0),
(12, 'photouser@gmail.com', 'password', 'Palak', 'Kulkarni', 987654321, '1995-01-06', 'SPCE', 'Civil', 'B.E.', 'student', 1, 'images/profiles/lamborghini-cars-logo-emblem.jpg', '', 0, 0, -1, 0, 1, 1),
(13, 'cm@gmail.com', 'qwerty', 'Chandresh', 'Mehta', 28964512, '1989-02-02', 'IIT-B', 'Computer Science', 'PHD in algorithims', 'cm', 1, 'images/profiles/cm@gmail.com', '', 0, 0, 0, 0, 0, 0);

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
(2, 1, -1, 0, '2014-02-08 05:04:58'),
(3, 1, 1, 0, '2014-02-08 05:04:58'),
(5, 1, 0, 0, '2014-02-09 11:31:29'),
(6, 1, 0, 0, '2014-02-08 05:04:58'),
(8, 1, -1, 0, '2014-02-08 05:04:58'),
(10, 1, 0, 0, '2014-02-08 05:04:58'),
(11, 1, 0, 0, '2014-02-09 11:31:29'),
(2, 5, 0, 0, '2014-02-17 19:00:20'),
(3, 5, 0, 0, '2014-02-17 19:00:20'),
(5, 5, 0, 0, '2014-02-09 11:32:11'),
(6, 5, 0, 0, '2014-02-17 19:00:20'),
(7, 5, 0, 0, '2014-01-30 12:02:25'),
(8, 5, 0, 0, '2014-02-17 19:00:20'),
(9, 5, 0, 0, '2014-02-09 11:17:44'),
(10, 5, 0, 0, '2014-02-17 19:00:20'),
(11, 5, 0, 0, '2014-02-09 11:32:11'),
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
(1, 2, 5, '2014-02-08 05:05:24', 0, -1),
(1, 3, 0, '2014-01-24 07:27:27', 0, 0),
(1, 4, 2, '2014-02-09 11:31:29', 0, 0),
(5, 2, 5, '2014-02-17 19:00:19', 1, 0),
(5, 3, 0, '2014-02-09 10:08:10', 0, 0),
(5, 4, 2, '2014-02-09 11:32:10', 1, 0),
(5, 8, 1, '2014-02-09 11:16:42', 1, 0),
(5, 9, 0, '2014-01-30 11:59:18', 0, 0),
(5, 10, 1, '2014-02-09 11:17:43', 1, 0),
(12, 2, 4, '2014-02-03 11:38:18', 1, 0),
(12, 4, 1, '2014-02-03 11:53:00', 0, 0),
(12, 8, 1, '2014-02-03 09:56:01', 0, 0),
(12, 9, 0, '2014-02-03 09:46:22', 0, 0),
(12, 10, 1, '2014-01-31 08:55:17', 0, 0);

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
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `course_eval`
--
ALTER TABLE `course_eval`
  ADD CONSTRAINT `course_eval_ibfk_1` FOREIGN KEY (`c_id_eval`) REFERENCES `course` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints for table `rate_resource`
--
ALTER TABLE `rate_resource`
  ADD CONSTRAINT `rate_resource_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rate_resource_ibfk_3` FOREIGN KEY (`r_id`) REFERENCES `resource` (`r_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `resource`
--
ALTER TABLE `resource`
  ADD CONSTRAINT `resource_ibfk_1` FOREIGN KEY (`c_id`) REFERENCES `course` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `resource_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `resource_type` (`type_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `resource_ibfk_3` FOREIGN KEY (`uploaded_by`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
