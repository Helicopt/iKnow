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


DROP TRIGGER IF EXISTS `insert_groups_trigger`;
DELIMITER //
CREATE TRIGGER `insert_groups_trigger` AFTER INSERT ON `belong`
  FOR EACH ROW 
    UPDATE `groups` SET `groups`.num=`groups`.num+1 where `groups`.id=NEW.`gid`;
//
DELIMITER ;


DROP TRIGGER IF EXISTS `delete_groups_trigger`;
DELIMITER //
CREATE TRIGGER `delete_groups_trigger` AFTER DELETE ON `belong`
  FOR EACH ROW 
    UPDATE `groups` SET `groups`.num=`groups`.num-1 where `groups`.id=NEW.`gid`;
//
DELIMITER ;

