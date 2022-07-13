-- phpMyAdmin SQL Dump
-- version 3.5.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 05. Jul 2022 um 08:55
-- Server Version: 5.5.62-0+deb8u1
-- PHP-Version: 5.6.40-0+deb8u12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `usr_web126669_2`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `equ_administrator`
--

CREATE TABLE IF NOT EXISTS `equ_administrator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(45) COLLATE utf8_bin NOT NULL,
  `passwort` varchar(45) COLLATE utf8_bin NOT NULL,
  `token` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `token_verbraucht` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `equ_administrator`
--

INSERT INTO `equ_administrator` (`id`, `login`, `passwort`, `token`, `token_verbraucht`) VALUES
(3, 'administrator', 'b3aca92c793ee0e9b1a9b0a5f5fc044e05140df3', '123456', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `equ_equation`
--

CREATE TABLE IF NOT EXISTS `equ_equation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(45) COLLATE utf8_bin NOT NULL DEFAULT 'none',
  `equation` varchar(150) COLLATE utf8_bin NOT NULL,
  `variable` varchar(10) COLLATE utf8_bin NOT NULL,
  `equ_type_id` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `equ_task_id` int(11) DEFAULT NULL,
  `intervalLeft` float NOT NULL,
  `intervalRight` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_equ_equation_equ_type_idx` (`equ_type_id`),
  KEY `fk_equ_equation_equ_task1_idx` (`equ_task_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=134 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `equ_solution`
--

CREATE TABLE IF NOT EXISTS `equ_solution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `equ_equation_id` int(11) DEFAULT NULL,
  `equ_student_id` int(11) DEFAULT NULL,
  `equ_task_work_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_equ_solution_equ_equation1_idx` (`equ_equation_id`),
  KEY `fk_equ_solution_equ_student1_idx` (`equ_student_id`),
  KEY `fk_equ_solution_equ_task_work1_idx` (`equ_task_work_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=142 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `equ_step`
--

CREATE TABLE IF NOT EXISTS `equ_step` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `equation` varchar(150) COLLATE utf8_bin NOT NULL,
  `equ_solution_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_equ_step_equ_solution1_idx` (`equ_solution_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=377 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `equ_student`
--

CREATE TABLE IF NOT EXISTS `equ_student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(45) COLLATE utf8_bin NOT NULL,
  `passwort` varchar(45) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `token` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `token_verbraucht` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `equ_task`
--

CREATE TABLE IF NOT EXISTS `equ_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(200) COLLATE utf8_bin NOT NULL DEFAULT '',
  `token` varchar(45) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `equ_teacher_id` int(11) NOT NULL,
  `titel` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cod_UNIQUE` (`token`),
  KEY `fk_equ_task_equ_teacher1_idx` (`equ_teacher_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=93 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `equ_task_work`
--

CREATE TABLE IF NOT EXISTS `equ_task_work` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `equ_task_id` int(11) DEFAULT NULL,
  `equ_student_id` int(11) DEFAULT NULL,
  `nickname` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `token` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `finished` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_UNIQUE` (`token`),
  KEY `fk_equ_task_work_equ_task1_idx` (`equ_task_id`),
  KEY `fk_equ_task_work_equ_student1_idx` (`equ_student_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=143 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `equ_teacher`
--

CREATE TABLE IF NOT EXISTS `equ_teacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(45) COLLATE utf8_bin NOT NULL,
  `passwort` varchar(45) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `token` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `token_verbraucht` tinyint(4) NOT NULL DEFAULT '0',
  `nickname` varchar(45) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=84 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `equ_type`
--

CREATE TABLE IF NOT EXISTS `equ_type` (
  `id` varchar(20) COLLATE utf8_bin NOT NULL,
  `german` varchar(45) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `equ_equation`
--
ALTER TABLE `equ_equation`
  ADD CONSTRAINT `fk_equ_equation_equ_task1` FOREIGN KEY (`equ_task_id`) REFERENCES `equ_task` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_equ_equation_equ_type` FOREIGN KEY (`equ_type_id`) REFERENCES `equ_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `equ_solution`
--
ALTER TABLE `equ_solution`
  ADD CONSTRAINT `fk_equ_solution_equ_equation1` FOREIGN KEY (`equ_equation_id`) REFERENCES `equ_equation` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_equ_solution_equ_student1` FOREIGN KEY (`equ_student_id`) REFERENCES `equ_student` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_equ_solution_equ_task_work1` FOREIGN KEY (`equ_task_work_id`) REFERENCES `equ_task_work` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `equ_step`
--
ALTER TABLE `equ_step`
  ADD CONSTRAINT `fk_equ_step_equ_solution1` FOREIGN KEY (`equ_solution_id`) REFERENCES `equ_solution` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `equ_task`
--
ALTER TABLE `equ_task`
  ADD CONSTRAINT `fk_equ_task_equ_teacher1` FOREIGN KEY (`equ_teacher_id`) REFERENCES `equ_teacher` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `equ_task_work`
--
ALTER TABLE `equ_task_work`
  ADD CONSTRAINT `fk_equ_task_work_equ_student1` FOREIGN KEY (`equ_student_id`) REFERENCES `equ_student` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_equ_task_work_equ_task1` FOREIGN KEY (`equ_task_id`) REFERENCES `equ_task` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
