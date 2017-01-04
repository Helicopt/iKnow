-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2017-01-02 17:42:30
-- 服务器版本： 5.6.17
-- PHP Version: 5.6.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `iknow`
--
CREATE DATABASE IF NOT EXISTS `iknow` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `iknow`;

-- --------------------------------------------------------

--
-- 表的结构 `accuse`
--

CREATE TABLE IF NOT EXISTS `accuse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `tpid` int(11) NOT NULL,
  `reason` varchar(256) NOT NULL,
  `result` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `tpid` (`tpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `answer`
--

CREATE TABLE IF NOT EXISTS `answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topicid` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `createTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actTime` DATETIME NOT NULL,
  `status` int(11) NOT NULL,
  `infoid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `infoid` (`infoid`),
  KEY `topicid` (`topicid`),
  KEY `author` (`author`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ans_accuse`
--

CREATE TABLE IF NOT EXISTS `ans_accuse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `ansid` int(11) NOT NULL,
  `reason` varchar(256) NOT NULL,
  `result` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `ansid` (`ansid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ans_notify`
--

CREATE TABLE IF NOT EXISTS `ans_notify` (
  `ansid` int(11) NOT NULL,
  `assess_cnt` int(11) NOT NULL DEFAULT '0',
  KEY `ansid` (`ansid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `assess`
--

CREATE TABLE IF NOT EXISTS `assess` (
  `ansid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `value` enum('agree','disagree') NOT NULL,
  PRIMARY KEY (`uid`,`ansid`),
  KEY `ansid` (`ansid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `avatar`
--

CREATE TABLE IF NOT EXISTS `avatar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(64) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `ext` varchar(16) NOT NULL DEFAULT 'png',
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `belong`
--

CREATE TABLE IF NOT EXISTS `belong` (
  `uid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  PRIMARY KEY (`uid`,`gid`),
  KEY `gid` (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `college`
--

CREATE TABLE IF NOT EXISTS `college` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ansid` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `createTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL,
  `txt` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `author` (`author`),
  KEY `ansid` (`ansid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `edu`
--

CREATE TABLE IF NOT EXISTS `edu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `cid` (`cid`),
  KEY `mid` (`mid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `favor`
--

CREATE TABLE IF NOT EXISTS `favor` (
  `tpid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`uid`,`tpid`),
  KEY `tpid` (`tpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `field`
--

CREATE TABLE IF NOT EXISTS `field` (
  `tgid` int(11) NOT NULL,
  `tpid` int(11) NOT NULL,
  PRIMARY KEY (`tpid`,`tgid`),
  KEY `tgid` (`tgid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `focus`
--

CREATE TABLE IF NOT EXISTS `focus` (
  `tgid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`uid`,`tgid`),
  KEY `tgid` (`tgid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `follow`
--

CREATE TABLE IF NOT EXISTS `follow` (
  `followerid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`userid`,`followerid`),
  KEY `followerid` (`followerid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL,
  `description` varchar(256) NOT NULL,
  `num` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `img`
--

CREATE TABLE IF NOT EXISTS `img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(64) NOT NULL DEFAULT '0',
  `ext` varchar(16) NOT NULL DEFAULT 'png',
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `info`
--

CREATE TABLE IF NOT EXISTS `info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `major`
--

CREATE TABLE IF NOT EXISTS `major` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `topic`
--

CREATE TABLE IF NOT EXISTS `topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `author` int(11) NOT NULL,
  `createTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actTime` DATETIME NOT NULL,
  `status` int(11) NOT NULL,
  `infoid` int(11) NOT NULL,
  `imgid` int(11) DEFAULT NULL,
  `views` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `infoid` (`infoid`),
  KEY `imgid` (`imgid`),
  KEY `author` (`author`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `topic_notify`
--

CREATE TABLE IF NOT EXISTS `topic_notify` (
  `tpid` int(11) NOT NULL,
  `ans_cnt` int(11) NOT NULL DEFAULT '0',
  KEY `tpid` (`tpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) NOT NULL,
  `pwd` char(64) NOT NULL,
  `nick` varchar(32) NOT NULL,
  `sig` varchar(128) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `avatarid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `avatarid` (`avatarid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 限制导出的表
--

--
-- 限制表 `accuse`
--
ALTER TABLE `accuse`
  ADD CONSTRAINT `accuse_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `accuse_ibfk_2` FOREIGN KEY (`tpid`) REFERENCES `topic` (`id`);

--
-- 限制表 `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`infoid`) REFERENCES `info` (`id`),
  ADD CONSTRAINT `answer_ibfk_2` FOREIGN KEY (`topicid`) REFERENCES `topic` (`id`),
  ADD CONSTRAINT `answer_ibfk_3` FOREIGN KEY (`author`) REFERENCES `user` (`id`);

--
-- 限制表 `ans_accuse`
--
ALTER TABLE `ans_accuse`
  ADD CONSTRAINT `ans_accuse_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `ans_accuse_ibfk_2` FOREIGN KEY (`ansid`) REFERENCES `answer` (`id`);

--
-- 限制表 `ans_notify`
--
ALTER TABLE `ans_notify`
  ADD CONSTRAINT `ans_notify_ibfk_1` FOREIGN KEY (`ansid`) REFERENCES `answer` (`id`);

--
-- 限制表 `assess`
--
ALTER TABLE `assess`
  ADD CONSTRAINT `assess_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `assess_ibfk_2` FOREIGN KEY (`ansid`) REFERENCES `answer` (`id`);

--
-- 限制表 `belong`
--
ALTER TABLE `belong`
  ADD CONSTRAINT `belong_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `belong_ibfk_2` FOREIGN KEY (`gid`) REFERENCES `groups` (`id`);

--
-- 限制表 `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`author`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`ansid`) REFERENCES `answer` (`id`);

--
-- 限制表 `edu`
--
ALTER TABLE `edu`
  ADD CONSTRAINT `edu_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `edu_ibfk_2` FOREIGN KEY (`cid`) REFERENCES `college` (`id`),
  ADD CONSTRAINT `edu_ibfk_3` FOREIGN KEY (`mid`) REFERENCES `major` (`id`);

--
-- 限制表 `favor`
--
ALTER TABLE `favor`
  ADD CONSTRAINT `favor_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `favor_ibfk_2` FOREIGN KEY (`tpid`) REFERENCES `topic` (`id`);

--
-- 限制表 `field`
--
ALTER TABLE `field`
  ADD CONSTRAINT `field_ibfk_1` FOREIGN KEY (`tpid`) REFERENCES `topic` (`id`),
  ADD CONSTRAINT `field_ibfk_2` FOREIGN KEY (`tgid`) REFERENCES `tag` (`id`);

--
-- 限制表 `focus`
--
ALTER TABLE `focus`
  ADD CONSTRAINT `focus_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `focus_ibfk_2` FOREIGN KEY (`tgid`) REFERENCES `tag` (`id`);

--
-- 限制表 `follow`
--
ALTER TABLE `follow`
  ADD CONSTRAINT `follow_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `follow_ibfk_2` FOREIGN KEY (`followerid`) REFERENCES `user` (`id`);

--
-- 限制表 `topic`
--
ALTER TABLE `topic`
  ADD CONSTRAINT `topic_ibfk_1` FOREIGN KEY (`infoid`) REFERENCES `info` (`id`),
  ADD CONSTRAINT `topic_ibfk_2` FOREIGN KEY (`imgid`) REFERENCES `img` (`id`),
  ADD CONSTRAINT `topic_ibfk_3` FOREIGN KEY (`author`) REFERENCES `user` (`id`);

--
-- 限制表 `topic_notify`
--
ALTER TABLE `topic_notify`
  ADD CONSTRAINT `topic_notify_ibfk_1` FOREIGN KEY (`tpid`) REFERENCES `topic` (`id`);

--
-- 限制表 `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`avatarid`) REFERENCES `avatar` (`id`);

DROP TRIGGER IF EXISTS `update_answer_trigger`;
DELIMITER //
CREATE TRIGGER `update_answer_trigger` BEFORE UPDATE ON `answer`
 FOR EACH ROW SET NEW.`actTime` = NOW()
//
DELIMITER ;

DROP TRIGGER IF EXISTS `update_topic_trigger`;
DELIMITER //
CREATE TRIGGER `update_topic_trigger` BEFORE UPDATE ON `topic`
 FOR EACH ROW SET NEW.`actTime` = NOW()
//
DELIMITER ;
