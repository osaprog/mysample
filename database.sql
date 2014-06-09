-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 09. Jun 2014 um 21:52
-- Server Version: 5.5.34
-- PHP-Version: 5.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Datenbank: `ofara_blog`
--
CREATE DATABASE IF NOT EXISTS `ofara_blog` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ofara_blog`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Blogs`
--

CREATE TABLE IF NOT EXISTS `Blogs` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Contents` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `Title` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `CreatedOn` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Comments`
--

CREATE TABLE IF NOT EXISTS `Comments` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `BlogId` int(10) unsigned NOT NULL,
  `CreatedOn` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `blog_id` (`BlogId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

