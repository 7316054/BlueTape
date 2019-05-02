/* phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2019 at 09:27 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.4

--SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
--SET AUTOCOMMIT = 0;
--START TRANSACTION;
--SET time_zone = "+00:00";

--CREATE TEST USER
--CREATE USER 'dev'@'localhost' IDENTIFIED BY 'dev';
--GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP ON *.* TO 'dev'@'localhost';

--CREATE DB*/
CREATE DATABASE IF NOT EXISTS `bluetape` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `bluetape`;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 ;

--
-- Database: `bluetape`
--

-- --------------------------------------------------------

--
-- Table structure for table `bluetape_userinfo`
--
*/
CREATE TABLE `bluetape_userinfo` (
  `email` varchar(128) NOT NULL,
  `name` varchar(256) NOT NULL,
  `lastUpdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



 insert into bluetape_Userinfo values 
                            ('7316053@student.unpar.ac.id', 'ANUGRAH JAYA SAKTI', '2019-04-02 09:25:44'),
                            ('anugrahjaya23@gmail.com', 'anugrah jaya', '2019-04-02 04:40:28'),
                            ('muhammmaddipo@gmail.com', 'Muhammad Dipo', '2019-04-02 00:00:00'),
                            ('rootbluetape@gmail.com', 'Root Bluetape', '2019-04-02 09:26:20');



CREATE TABLE `jadwal_dosen` (
  `id` int(11) NOT NULL,
  `user` varchar(256) NOT NULL,
  `hari` int(11) NOT NULL,
  `jam_mulai` int(11) NOT NULL,
  `durasi` int(11) NOT NULL,
  `jenis` varchar(256) NOT NULL,
  `label` varchar(100) NOT NULL,
  `lastUpdate` datetime DEFAULT '2019-01-27 14:20:34'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


insert into jadwal_dosen values
                            (2, 'gemini2911f665@gmail.com', 0, 7, 1, 'konsultasi', 'aa', '2019-03-28 08:30:32'),
                            (3, 'gemini2911f665@gmail.com', 0, 7, 1, 'konsultasi', 'aa', '2019-03-28 08:31:58'),
                            (4, 'Dipo1', 4, 13, 5, 'Kelas', 'Update', '2019-03-28 08:32:56'),
                            (5, 'gemini2911f665@gmail.com', 0, 7, 1, 'konsultasi', 'aa', '2019-03-28 08:33:22'),
                            (6, 'anugrahjaya23@gmail.com', 0, 7, 3, 'kelas', 'kelas TBD', '2019-04-02 04:41:56'),
                            (7, 'anugrahjaya23@gmail.com', 0, 11, 4, 'konsultasi', 'kelas TBD', '2019-04-02 04:41:40'),
                            (8, 'rootbluetape@gmail.com', 0, 7, 1, 'konsultasi', 'kelas TBD', '2019-04-02 05:14:49'),
                            (9, 'rootbluetape@gmail.com', 2, 12, 2, 'kelas', 'Kelas pengganti', '2019-04-02 09:11:07'),
                            (10, 'rootbluetape@gmail.com', 0, 13, 3, 'kelas', 'Kelas Proyek Informatika', '2019-04-02 09:13:27'),
                            (11, 'rootbluetape@gmail.com', 4, 7, 3, 'kelas', 'Kelas Jaringan Komputer', '2019-04-02 09:13:43'),
                            (12, 'rootbluetape@gmail.com', 3, 10, 2, 'kelas', 'Kelas Multi Agen', '2019-04-02 09:14:23');


CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO `migrations` (`version`) VALUES
(20180821101900);


CREATE TABLE `perubahankuliah` (
  `id` int(9) NOT NULL,
  `requestByEmail` varchar(256) NOT NULL,
  `requestDateTime` datetime NOT NULL,
  `mataKuliahName` varchar(256) NOT NULL,
  `mataKuliahCode` varchar(9) DEFAULT NULL,
  `class` varchar(1) NOT NULL,
  `changeType` varchar(1) NOT NULL,
  `fromDateTime` datetime DEFAULT NULL,
  `fromRoom` varchar(16) DEFAULT NULL,
  `to` varchar(1024) DEFAULT NULL,
  `remarks` varchar(256) NOT NULL,
  `answer` varchar(16) DEFAULT NULL,
  `answeredByEmail` varchar(256) DEFAULT NULL,
  `answeredDateTime` datetime DEFAULT NULL,
  `answeredMessage` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


insert into PerubahanKuliah values 
                            (1, 'rootbluetape@gmail.com', '2019-04-02 09:48:23', 'Teknologi ', 'AIF123', 'A', 'G', '2019-04-02 09:47:00', '09021', '[{\"dateTime\":\"2019-04-05 09:48:00\",\"room\":\"09022\"}]', '', 'confirmed', 'rootbluetape@gmail.com', '2019-04-02 09:58:33', 'oke'),
                            (3, 'rootbluetape@gmail.com', '2019-04-02 14:17:40', 'Multi Agen', 'AIF234', 'A', 'X', '2019-04-04 10:00:00', '09020', '[]', 'Kelas diadakan karena dosen sakit', 'confirmed', 'rootbluetape@gmail.com', '2019-04-02 14:20:55', 'Boleh'),
                            (4, 'rootbluetape@gmail.com', '2019-04-02 14:19:47', 'Jaringan Komputer', 'AIF101', 'A', 'G', '2019-04-05 07:00:00', '09021', '[{\"dateTime\":\"2019-04-12 07:00:00\",\"room\":\"10317\"}]', 'Kelas diganti karena jaya sakit perut', 'confirmed', 'rootbluetape@gmail.com', '2019-04-02 14:21:03', 'Dipersilahkan'),
                            (5, 'rootbluetape@gmail.com', '2019-04-02 14:24:10', 'Proyek Informatika', 'AIF142', 'B', 'X', '2019-04-01 07:00:00', '09016', '[]', 'Kelas ditiadakan karena dosen ada urusan', 'confirmed', 'rootbluetape@gmail.com', '2019-04-02 14:24:20', '');


CREATE TABLE `transkrip` (
  `id` int(9) NOT NULL,
  `requestByEmail` varchar(128) DEFAULT NULL,
  `requestDateTime` datetime NOT NULL,
  `requestType` varchar(8) DEFAULT NULL,
  `requestUsage` varchar(256) NOT NULL,
  `answer` varchar(16) DEFAULT NULL,
  `answeredByEmail` varchar(128) DEFAULT NULL,
  `answeredDateTime` datetime DEFAULT NULL,
  `answeredMessage` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


 insert into transkrip values
                        (1, '7316053@student.unpar.ac.id', '2019-03-26 15:28:58', 'DPS_ID', 'kuliah', 'printed', 'rootbluetape@gmail.com', '2019-04-02 10:06:09', 'oke'),
                        (3, '7316053@student.unpar.ac.id', '2019-04-02 10:21:03', 'LHS', 'kuliah', 'rejected', 'rootbluetape@gmail.com', '2019-04-02 14:25:33', 'Belum lunas pembayaran'),
                        (4, '7316053@student.unpar.ac.id', '2019-04-02 14:25:58', 'LHS', 'Untuk beasiswa', 'printed', 'rootbluetape@gmail.com', '2019-04-02 14:26:32', 'Ambil di TU');


ALTER TABLE `bluetape_userinfo`
  ADD PRIMARY KEY (`email`);

ALTER TABLE `jadwal_dosen`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `perubahankuliah`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `transkrip`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `jadwal_dosen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;


ALTER TABLE `perubahankuliah`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;


ALTER TABLE `transkrip`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
