-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2014 at 08:30 AM
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`c_id`, `c_name`, `c_stream`, `inserted_on`, `start_date`, `end_date`, `u_id`, `approve_status`, `course_image`, `avg_rating`, `description`) VALUES
(18, 'Cryptography-Basic', 'Computer Science', '2014-01-03 16:12:36', '2014-01-12', '2016-01-11', 2, 1, 'images/gallery/09-l.jpg', 4.5, 'This is course on cryptography. In this course we will introduce you to basic ciphers and the basics of cryptography.'),
(19, 'Networking Protocols', 'Networking', '2014-02-27 16:12:36', '2014-05-01', '2014-10-31', 2, 1, 'images/gallery/07.jpg', 0, ''),
(20, 'Cryptography-Advanced', 'Computer Science', '2014-01-15 16:12:36', '2014-01-22', '2014-07-17', 2, 1, 'images/gallery/05.jpg', 0, 'this is crypto'),
(21, 'Web Designing', 'Computer Science', '2014-02-14 16:12:36', '2014-01-03', '2014-07-17', 13, 1, 'images/gallery/03.jpg', 0, ''),
(22, 'Database Management', 'Computer Science', '2014-02-14 16:12:36', '2014-01-16', '2014-08-18', 2, 0, 'images/gallery/03.jpg', 0, 'Description!'),
(23, 'Database-Advanced', 'Computer Science', '2014-02-14 16:12:36', '2014-03-06', '2014-08-05', 2, 0, 'images/gallery/04.jpg', 0, 'This course is based n advance techniques of using database'),
(27, 'PHP', 'Computer Science', '2014-02-14 16:12:36', '2014-02-20', '2017-02-20', 2, 0, 'images/course_picture/PHP.jpg', 0, 'this is basic course'),
(28, 'Java Scripts', 'Computer Science', '2014-02-14 16:12:36', '2015-02-12', '2015-02-27', 2, 0, 'images/course_picture/Java Scripts.jpg', 0, 'Basics of Java Scripting'),
(29, 'Data Struct', 'Computer', '2014-02-08 16:12:36', '2014-02-14', '2014-02-21', 13, 1, 'images/course_picture/Data Struct.jpg', 0, 'ahjd'),
(30, 'temp_course', 'cs', '2014-02-12 16:12:36', '2014-03-06', '2014-02-22', 2, 1, 'images/course_picture/temp_course.jpg', 0, 'temp'),
(31, 'electromagnetics', 'electromagnetism', '2014-03-12 13:14:28', '2014-03-13', '2014-03-27', 17, 1, 'images/course_picture/electromagnetics.jpg', 0, 'basic of electromagnetism');

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

--
-- Dumping data for table `course_reco`
--

INSERT INTO `course_reco` (`c_reco_id`, `from_u_id`, `to_u_id`) VALUES
(18, 5, 1),
(18, 5, 3);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `discussion`
--

INSERT INTO `discussion` (`discussion_id`, `type`, `category_id`, `insert_uid`, `last_comment_id`, `name`, `disc_body`, `count_comments`, `date_inserted_d`, `date_updated_d`, `flag`, `rating`) VALUES
(3, 0, 4, 8, 0, 'This is by Kunal', 'Kunal''s body! :P', 0, '2014-01-19 04:38:46', '0000-00-00 00:00:00', 0, -2),
(11, 0, 3, 2, 0, 'new discussion for networking', ' body', 0, '2014-03-13 04:37:31', '2014-03-13 04:37:31', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `discussion_category`
--

CREATE TABLE IF NOT EXISTS `discussion_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` text NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `discussion_category`
--

INSERT INTO `discussion_category` (`category_id`, `category_name`) VALUES
(3, 'Networking Protocols'),
(4, 'Cryptography-Basic'),
(5, 'electromagnetics');

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
(1, 20, 0, 0, -1, '0.0'),
(4, 18, 0, 2, -1, '0.0'),
(5, 18, 1, 1, 30, '4.5'),
(5, 21, 0, 1, -1, '0.0'),
(5, 31, 0, 1, -1, '0.0'),
(8, 18, 0, 0, -1, '0.0');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `resource`
--

INSERT INTO `resource` (`r_id`, `c_id`, `type_id`, `filename`, `file_type`, `file_size`, `file_location`, `view_location`, `uploaded_by`, `uploaded_date`, `download_status`, `approve_status`, `avg_rating`, `flag_status`) VALUES
(1, 18, 1, 'Reference.pdf', 'application/pdf', 0.038190841674805, 'resource/18/Reference.pdf', 'resource/18-s/abstract.swf', 2, '2014-03-12 18:37:10', 0, 0, '1.0', 0),
(2, 18, 7, 'Example.jpg', 'image/jpeg', 0.13896942138672, 'resource/18/Example.jpg', 'resource/18-s/des.swf', 2, '2014-02-09 00:17:09', 0, 1, '2.0', 1),
(3, 18, 2, 'lect1.mp4', 'video/mp4', 26.060379981995, 'resource/18/lect1.mp4', '', 2, '2014-03-11 14:49:33', 1, 0, '0.0', 0);

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
  `show_email` tinyint(4) NOT NULL DEFAULT '0',
  `gender` tinyint(4) NOT NULL DEFAULT '0',
  `user_score` float NOT NULL DEFAULT '0',
  `count_bookmarks` int(11) NOT NULL DEFAULT '0',
  `created_comments` int(11) NOT NULL DEFAULT '0',
  `count_discussions` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`u_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`u_id`, `u_name`, `password`, `f_name`, `l_name`, `contact_no`, `dob`, `institute`, `stream`, `degree`, `role`, `approve_id`, `photo`, `about`, `show_email`, `gender`, `user_score`, `count_bookmarks`, `created_comments`, `count_discussions`) VALUES
(1, 'xyz@gmail.com', '65e84be33532fb784c48129675f9eff3a682b27168c0ea744b2cf58ee02337c5', 'Abdul', 'Shaikh', 2147483647, '1993-03-01', 'SPIT', 'Computers', 'B.E.', 'student', 1, 'images/profiles/1.jpg', '', 0, 0, 1, 0, 1, 0),
(2, 'abc@tech.org', '65e84be33532fb784c48129675f9eff3a682b27168c0ea744b2cf58ee02337c5', 'Abhishek', 'Chaturvedi', 26845172, '1979-04-02', 'VJTI', 'Information Technolo', 'PHD in cryptography and Security', 'author', 1, 'images/profiles/2.jpg', '', 0, 0, 13.5, 0, 0, 1),
(3, 'dalvishaarad@gmail.c', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'Shaarad', 'Dalvi', 2147483647, '1992-04-01', 'vjti', 'comps', 'B.E.', 'student', 1, 'images/profiles/3.jpg', '', 0, 0, 2, 0, 0, 0),
(4, 'shaaraddalvi@outlook.com', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'Shaarad', 'Inamdar', 25406266, '1993-11-01', 'TSEC', 'Electronics', 'B.E.', 'student', 1, 'images/profiles/4.jpg', '', 0, 0, 0, 0, 0, 0),
(5, 'sh@yahoo.co.in', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'sahil', 'shah', 25406858, '1994-01-18', 'vjti', 'comp', 'B.E.', 'student', 1, 'images/profiles/5.jpg', 'Hi, I''m Sahil Shah', 0, 0, 6, 0, 0, 0),
(6, 'root', '5012f5182061c46e57859cf617128c6f70eddfba4db27772bdede5a039fa7085', 'root', 'root', 2147483647, '1964-08-25', 'root', 'root', 'B.E.', 'admin', 1, 'images/profiles/6.jpg', '', 0, 0, 0, 0, 0, 0),
(8, 'kunalshah@gmail.com', 'bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f', 'Kunal', 'Shah', 2147483647, '1993-07-17', 'VJTI', 'computers', 'B.E.', 'student', 1, 'images/profiles/8.jpg', '', 0, 0, -1, 0, 0, 0),
(9, 'nw@gmail.com', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'Nachiket', 'wagle', 982680350, '1993-04-07', 'vjti', 'Civil', 'B.E.', 'student', 1, 'images/profiles/9.jpg', '', 0, 0, 0, 0, 0, 0),
(10, 'new@gmail.com', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'Niket', 'wagle', 21474836, '1991-12-09', 'vjti', 'mechanical', 'B.E.', 'student', 0, 'images/profiles/10.jpg', '', 0, 0, 0, 0, 0, 0),
(12, 'photouser@gmail.com', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'Palak', 'Kulkarni', 987654321, '1995-01-06', 'SPCE', 'Civil', 'B.E.', 'student', 1, 'images/profiles/12.jpg', '', 0, 0, -1, 0, 1, 1),
(13, 'cm@gmail.com', '65e84be33532fb784c48129675f9eff3a682b27168c0ea744b2cf58ee02337c5', 'Chandresh', 'Mehta', 28964512, '1989-02-02', 'IIT-B', 'Computer Science', 'PHD in algorithims', 'cm', 1, 'images/profiles/13.jpg', '', 0, 0, 0, 0, 0, 0),
(14, 'test@gmail.com', 'qwerty', 'Tanmay', 'Gandhi', 2147483647, '2013-03-05', 'IIT_B', 'Computer Science', 'B.Tech', 'cm', 1, 'images/profiles/14.jpg', 'HI', 0, 0, 0, 0, 0, 0),
(17, 'cm2@gmail.com', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'cm2', 'cm2', 1234567890, '2006-03-24', 'VJTI', 'electromagnetics', 'PhD in electromagnetics', 'author', 1, 'images/profiles/17.jpg', 'PhD in electromagnetics from VJTI', 0, 0, 0, 0, 0, 0);

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
(1, 3, 0, '2014-01-24 07:27:27', 0, 0),
(2, 11, 0, '2014-03-13 04:37:38', 0, 0),
(3, 3, 0, '2014-02-19 18:27:29', 0, 0),
(5, 3, 0, '2014-03-13 04:36:35', 0, 0),
(5, 11, 0, '2014-03-13 04:39:35', 0, 0),
(6, 3, 0, '2014-02-19 18:05:20', 0, -1);

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
(1, 5, '1.5', 1, '2014-02-21 10:58:58'),
(2, 5, '5.0', 0, '2014-02-21 08:56:44');

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
