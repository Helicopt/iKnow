-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2017-01-02 17:42:30
-- 服务器版本： 5.6.17
-- PHP Version: 5.6.27


--
-- Database: `iknow`
--
CREATE DATABASE IF NOT EXISTS `iknow` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `iknow`;

ALTER TABLE `user` ADD INDEX mail (`email`)