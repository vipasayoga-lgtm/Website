-- enable sandbox mode 
-- MariaDB dump 10.19-11.4.5-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: vipasa_yoga
-- ------------------------------------------------------
-- Server version	11.4.5-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
-- note verbosity

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(60) DEFAULT NULL,
  `entity_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_activitylog_user` (`user_id`),
  KEY `idx_activitylog_action` (`action`),
  CONSTRAINT `fk_activitylog_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=316 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES
(1,1,'login','user',1,'::1','curl/8.6.0',NULL,'2026-07-10 16:53:23'),
(2,10,'signup','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,'2026-07-10 17:02:54'),
(3,10,'booking_created','booking',3,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','{\"booking_ref\":\"VYG5ED3D0K\"}','2026-07-10 17:04:48'),
(4,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,'2026-07-10 17:54:40'),
(5,8,'password_reset','user',8,'::1','curl/8.6.0',NULL,'2026-07-11 09:34:37'),
(6,8,'login','user',8,'::1','curl/8.6.0',NULL,'2026-07-11 09:34:45'),
(7,8,'password_reset','user',8,'::1','curl/8.6.0',NULL,'2026-07-11 09:34:54'),
(8,10,'password_reset','user',10,'::1','curl/8.6.0',NULL,'2026-07-11 10:06:00'),
(9,10,'login','user',10,'::1','curl/8.6.0',NULL,'2026-07-11 10:06:15'),
(10,10,'login','user',10,'::1','curl/8.6.0',NULL,'2026-07-11 10:08:19'),
(11,10,'login','user',10,'::1','curl/8.6.0',NULL,'2026-07-11 10:08:53'),
(12,8,'login','user',8,'::1','curl/8.6.0',NULL,'2026-07-11 10:11:01'),
(13,8,'package_created','package',7,'::1','curl/8.6.0','{\"name\":\"Test Quarterly Package\"}','2026-07-11 10:11:10'),
(14,8,'package_updated','package',7,'::1','curl/8.6.0','{\"name\":\"Test Quarterly Package (Updated)\"}','2026-07-11 10:11:55'),
(15,8,'package_deactivated','package',7,'::1','curl/8.6.0',NULL,'2026-07-11 10:11:55'),
(16,8,'login','user',8,'::1','curl/8.6.0',NULL,'2026-07-11 10:21:09'),
(17,8,'instructor_created','instructor',4,'::1','curl/8.6.0','{\"name\":\"Priya Nair\"}','2026-07-11 10:21:42'),
(20,8,'instructor_updated','instructor',4,'::1','curl/8.6.0','{\"name\":\"Priya Nair-Updated\"}','2026-07-11 10:22:17'),
(21,8,'instructor_deactivated','instructor',4,'::1','curl/8.6.0',NULL,'2026-07-11 10:22:17'),
(22,8,'settings_updated','setting',NULL,'::1','curl/8.6.0','{\"site_name\":\"Vipasa Yoga\",\"tax_percent\":\"7\",\"default_currency\":\"INR\",\"default_timezone\":\"Asia\\/Kolkata\"}','2026-07-11 10:22:32'),
(23,8,'settings_updated','setting',NULL,'::1','curl/8.6.0','{\"site_name\":\"Vipasa Yoga\",\"tax_percent\":\"5\",\"default_currency\":\"INR\",\"default_timezone\":\"Asia\\/Kolkata\"}','2026-07-11 10:22:47'),
(24,8,'notification_sent','notification',1,'::1','curl/8.6.0','{\"to_user_id\":10,\"title\":\"Welcome offer\"}','2026-07-11 10:22:59'),
(25,8,'login','user',8,'::1','curl/8.6.0',NULL,'2026-07-11 10:33:25'),
(27,10,'login','user',10,'::1','curl/8.6.0',NULL,'2026-07-11 10:37:37'),
(28,10,'payment_success','booking',3,'::1','curl/8.6.0','{\"booking_ref\":\"VYG5ED3D0K\",\"amount\":\"1573.95\"}','2026-07-11 10:37:38'),
(29,10,'login','user',10,'::1','curl/8.6.0',NULL,'2026-07-11 10:40:16'),
(31,10,'login','user',10,'::1','curl/8.6.0',NULL,'2026-07-11 10:42:12'),
(34,10,'login','user',10,'::1','curl/8.6.0',NULL,'2026-07-11 10:46:18'),
(36,8,'login','user',8,'::1','curl/8.6.0',NULL,'2026-07-11 10:50:35'),
(37,10,'login','user',10,'::1','curl/8.6.0',NULL,'2026-07-11 10:50:35'),
(38,14,'signup','user',14,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-11 19:58:27'),
(39,15,'signup','user',15,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-11 20:00:30'),
(40,16,'signup','user',16,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-11 20:01:45'),
(41,17,'signup','user',17,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-11 20:02:19'),
(42,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,'2026-07-11 20:05:06'),
(43,18,'signup','user',18,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-11 20:06:02'),
(44,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,'2026-07-11 20:08:08'),
(45,19,'signup','user',19,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-11 20:09:43'),
(46,20,'signup','user',20,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-11 21:02:03'),
(47,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-11 21:02:36'),
(48,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-11 21:47:44'),
(49,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-11 22:23:46'),
(51,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-11 22:23:51'),
(56,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-11 22:28:06'),
(57,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-11 22:28:11'),
(58,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-11 22:29:22'),
(59,10,'booking_created','booking',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36','{\"booking_ref\":\"VYAUIFP234\"}','2026-07-11 22:29:23'),
(60,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-11 22:30:02'),
(62,13,'booking_accepted','booking',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36','{\"booking_ref\":\"VYAUIFP234\"}','2026-07-11 22:30:43'),
(63,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-11 22:31:14'),
(64,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-11 22:31:53'),
(65,10,'booking_created','booking',9,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36','{\"booking_ref\":\"VYYG2TAQL3\"}','2026-07-11 22:31:54'),
(67,13,'booking_rejected','booking',9,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36','{\"booking_ref\":\"VYYG2TAQL3\"}','2026-07-11 22:32:36'),
(68,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-11 22:33:14'),
(69,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-11 22:34:02'),
(70,10,'instructor_rated','booking',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36','{\"rating\":5}','2026-07-11 22:34:04'),
(71,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 08:53:29'),
(73,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 08:53:46'),
(74,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 08:54:34'),
(75,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 08:55:48'),
(76,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 08:56:46'),
(77,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 08:57:48'),
(78,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 08:57:58'),
(79,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 08:58:42'),
(81,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 08:58:49'),
(82,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:00:01'),
(83,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:00:24'),
(84,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:10:59'),
(85,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:11:46'),
(86,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:12:08'),
(87,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:13:28'),
(88,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:13:53'),
(89,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:14:52'),
(90,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:15:10'),
(91,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:15:44'),
(92,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:16:11'),
(93,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:17:16'),
(94,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:19:38'),
(95,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:20:22'),
(97,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:20:39'),
(98,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:21:01'),
(99,8,'content_updated','page_content',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36','{\"section\":\"hero\"}','2026-07-12 09:21:02'),
(100,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:22:37'),
(101,8,'gallery_image_uploaded','gallery_image',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:22:39'),
(102,8,'gallery_image_deleted','gallery_image',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:22:42'),
(103,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:29:24'),
(104,8,'settings_updated','setting',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36','{\"section\":\"website\"}','2026-07-12 09:29:27'),
(105,8,'settings_updated','setting',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36','{\"section\":\"google\"}','2026-07-12 09:29:27'),
(106,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:31:42'),
(107,8,'notification_broadcast','notification',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36','{\"target\":\"all_trainers\",\"recipient_count\":4,\"title\":\"Test broadcast to trainers\"}','2026-07-12 09:31:44'),
(108,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:35:03'),
(109,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:37:51'),
(111,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:38:51'),
(115,13,'session_notes_saved','booking',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 09:51:51'),
(117,13,'certificate_file_uploaded','instructor',5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 10:01:07'),
(118,13,'certificate_file_deleted','instructor',5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 10:01:08'),
(119,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 10:07:50'),
(120,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 10:11:10'),
(121,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 10:23:10'),
(123,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 10:23:52'),
(127,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 10:26:07'),
(129,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 10:26:29'),
(130,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 10:27:25'),
(134,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,'2026-07-12 10:37:39'),
(136,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,'2026-07-12 10:40:29'),
(137,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,'2026-07-12 10:45:23'),
(138,8,'instructor_updated','instructor',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','{\"name\":\"Ananya Iyer\"}','2026-07-12 10:46:23'),
(139,8,'instructor_activated','instructor',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,'2026-07-12 10:46:26'),
(140,8,'gallery_image_uploaded','gallery_image',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,'2026-07-12 10:47:22'),
(141,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,'2026-07-12 10:47:41'),
(142,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 10:49:54'),
(143,8,'login','user',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,'2026-07-12 10:49:55'),
(144,8,'gallery_image_deleted','gallery_image',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,'2026-07-12 10:50:11'),
(145,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 10:51:44'),
(146,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 10:57:32'),
(147,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 10:58:16'),
(148,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,'2026-07-12 11:09:09'),
(149,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 11:43:17'),
(150,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 11:44:31'),
(151,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 11:51:00'),
(152,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 11:52:29'),
(154,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 11:54:16'),
(155,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,'2026-07-12 12:00:57'),
(156,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:04:30'),
(157,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:04:57'),
(158,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:05:18'),
(159,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:06:19'),
(160,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:08:43'),
(161,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:09:09'),
(162,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:13:19'),
(163,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:18:08'),
(164,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:22:07'),
(165,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:23:10'),
(166,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:35:45'),
(167,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:38:38'),
(168,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:40:25'),
(169,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:43:57'),
(170,10,'booking_created','booking',14,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','{\"booking_ref\":\"VY3R3VHIQ6\"}','2026-07-12 12:44:58'),
(171,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:51:09'),
(173,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:51:44'),
(174,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:52:07'),
(175,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:52:25'),
(176,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:52:44'),
(177,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:53:58'),
(179,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:54:15'),
(180,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:55:13'),
(183,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:57:08'),
(184,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 12:59:43'),
(185,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 13:02:36'),
(186,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 13:06:14'),
(187,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 13:28:14'),
(188,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 13:30:06'),
(189,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 13:32:41'),
(190,10,'login','user',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-12 13:35:51'),
(191,21,'signup','user',21,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-13 19:13:22'),
(192,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-13 19:18:03'),
(193,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-13 19:20:09'),
(194,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-13 19:20:39'),
(195,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-13 19:21:07'),
(196,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-13 19:21:54'),
(197,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-13 19:22:25'),
(198,21,'login','user',21,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-13 19:24:55'),
(199,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-13 19:26:38'),
(200,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-13 19:27:04'),
(201,21,'login','user',21,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-13 19:36:32'),
(202,21,'login','user',21,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-13 19:39:19'),
(203,21,'booking_created','booking',17,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','{\"booking_ref\":\"VY8VJXN9RF\"}','2026-07-13 19:40:04'),
(204,21,'payment_success','booking',17,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','{\"booking_ref\":\"VY8VJXN9RF\",\"amount\":\"1573.95\"}','2026-07-13 19:40:10'),
(205,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-13 19:43:32'),
(206,8,'instructor_deactivated','instructor',5,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-13 19:46:59'),
(207,8,'instructor_activated','instructor',2,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-13 19:47:05'),
(208,8,'gallery_image_uploaded','gallery_image',3,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-13 19:49:07'),
(209,21,'login','user',21,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-13 19:50:02'),
(210,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-13 19:51:12'),
(211,8,'instructor_activated','instructor',3,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-13 19:51:19'),
(212,8,'instructor_activated','instructor',5,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-13 19:51:21'),
(213,8,'instructor_created','instructor',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','{\"name\":\"Lakshu\"}','2026-07-13 19:51:35'),
(214,21,'login','user',21,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-13 19:51:44'),
(215,21,'booking_created','booking',18,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','{\"booking_ref\":\"VYX1CM4K8V\"}','2026-07-13 19:54:19'),
(216,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-13 19:58:52'),
(217,21,'login','user',21,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-13 20:10:15'),
(218,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 10:24:16'),
(219,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 10:40:06'),
(220,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 12:25:53'),
(221,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 12:45:26'),
(222,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 12:46:14'),
(223,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 13:14:39'),
(224,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 13:16:17'),
(225,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 13:18:30'),
(226,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 13:20:14'),
(227,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 13:20:44'),
(228,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 13:22:15'),
(229,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 13:22:39'),
(230,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 13:25:51'),
(231,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 13:25:55'),
(232,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 13:26:37'),
(233,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 13:28:19'),
(234,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 13:29:22'),
(235,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 13:32:07'),
(236,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 13:33:29'),
(237,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 14:15:56'),
(238,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 14:17:42'),
(239,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 14:18:16'),
(240,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 14:19:37'),
(241,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 14:46:56'),
(242,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 14:47:54'),
(243,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 14:48:36'),
(244,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 14:57:58'),
(245,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 15:29:22'),
(246,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 15:31:18'),
(247,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 16:46:49'),
(248,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 16:51:35'),
(249,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 16:52:24'),
(250,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 16:53:14'),
(252,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 16:53:56'),
(254,21,'login','user',21,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-14 17:36:29'),
(255,21,'booking_created','booking',20,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','{\"booking_ref\":\"VYPH4YZWKC\"}','2026-07-14 17:37:19'),
(256,21,'payment_success','booking',20,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','{\"booking_ref\":\"VYPH4YZWKC\",\"amount\":\"1573.95\"}','2026-07-14 17:37:22'),
(257,21,'login','user',21,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-14 17:40:25'),
(258,21,'login','user',21,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-14 19:41:26'),
(259,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 19:47:58'),
(260,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 19:48:59'),
(261,NULL,'signup','user',23,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 19:51:06'),
(262,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 19:57:15'),
(263,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 19:57:17'),
(264,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 20:00:01'),
(265,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 20:13:04'),
(266,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 20:45:19'),
(267,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-14 20:45:52'),
(268,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 16:26:49'),
(269,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 16:26:51'),
(270,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 16:26:53'),
(271,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 18:46:05'),
(272,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 18:46:54'),
(273,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 18:47:28'),
(274,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 18:56:41'),
(275,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 18:57:21'),
(276,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 19:03:16'),
(277,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 19:03:52'),
(278,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 19:04:35'),
(279,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 19:12:00'),
(280,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 19:12:41'),
(281,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 19:32:19'),
(282,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 19:42:22'),
(283,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 19:46:33'),
(284,10,'consent_accepted','booking',14,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 19:46:36'),
(285,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 19:53:50'),
(286,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 19:53:53'),
(287,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 19:53:55'),
(288,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 19:55:59'),
(289,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 19:56:07'),
(290,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 19:56:15'),
(291,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 19:56:27'),
(292,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-15 19:56:57'),
(293,21,'login','user',21,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-16 16:10:31'),
(294,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-16 16:28:34'),
(295,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-16 16:28:37'),
(296,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-16 16:28:40'),
(297,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-16 16:28:42'),
(298,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-16 16:28:45'),
(299,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-16 16:29:36'),
(300,8,'login','user',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-16 16:29:45'),
(301,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-16 16:29:56'),
(302,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-16 16:30:21'),
(303,21,'login','user',21,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-16 16:51:25'),
(304,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-16 16:53:05'),
(305,10,'login','user',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/149.0.7827.55 Safari/537.36',NULL,'2026-07-16 16:56:31'),
(306,21,'login','user',21,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-16 20:43:39'),
(307,21,'health_assessment_stage_completed','user',21,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','{\"stage\":2}','2026-07-16 20:45:03'),
(308,21,'login','user',21,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-16 21:08:53'),
(309,21,'login','user',21,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-18 11:31:56'),
(310,21,'login','user',21,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-18 16:41:12'),
(311,21,'payment_success','booking',18,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','{\"booking_ref\":\"VYX1CM4K8V\",\"amount\":\"1573.95\"}','2026-07-18 16:41:50'),
(312,21,'login','user',21,'::1','Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1',NULL,'2026-07-19 14:26:42'),
(313,21,'login','user',21,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-22 16:37:26'),
(314,21,'login','user',21,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-23 12:54:31'),
(315,25,'account_created','user',25,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-23 12:56:56');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_reschedule_requests`
--

DROP TABLE IF EXISTS `booking_reschedule_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_reschedule_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `requested_slot_date` date NOT NULL,
  `requested_start_time` time NOT NULL,
  `status` enum('pending','approved','declined') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_reschedule_request_booking` (`booking_id`),
  CONSTRAINT `fk_reschedule_request_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_reschedule_requests`
--

LOCK TABLES `booking_reschedule_requests` WRITE;
/*!40000 ALTER TABLE `booking_reschedule_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_reschedule_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_slots`
--

DROP TABLE IF EXISTS `booking_slots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_slots` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `instructor_id` bigint(20) unsigned NOT NULL,
  `slot_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` enum('available','booked','blocked') NOT NULL DEFAULT 'available',
  `booking_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_instructor_slot` (`instructor_id`,`slot_date`,`start_time`),
  KEY `idx_slot_date` (`slot_date`),
  KEY `idx_slot_status` (`status`),
  KEY `fk_slot_booking` (`booking_id`),
  CONSTRAINT `fk_slot_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_slot_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_slots`
--

LOCK TABLES `booking_slots` WRITE;
/*!40000 ALTER TABLE `booking_slots` DISABLE KEYS */;
INSERT INTO `booking_slots` VALUES
(1,1,'2026-07-11','08:00:00','09:00:00','available',NULL,'2026-07-10 13:37:35'),
(2,1,'2026-07-11','09:00:00','10:00:00','available',NULL,'2026-07-10 13:37:35'),
(3,1,'2026-07-11','10:00:00','11:00:00','available',NULL,'2026-07-10 13:37:35'),
(4,1,'2026-07-11','11:00:00','12:00:00','available',NULL,'2026-07-10 13:37:35'),
(5,2,'2026-07-13','06:00:00','07:00:00','available',NULL,'2026-07-10 16:27:49'),
(6,2,'2026-07-13','07:00:00','08:00:00','available',NULL,'2026-07-10 16:27:49'),
(7,2,'2026-07-13','08:00:00','09:00:00','available',NULL,'2026-07-10 16:27:49'),
(8,2,'2026-07-13','09:00:00','10:00:00','available',NULL,'2026-07-10 16:27:49'),
(9,2,'2026-07-13','10:00:00','11:00:00','available',NULL,'2026-07-10 16:27:49'),
(10,2,'2026-07-13','16:00:00','17:00:00','available',NULL,'2026-07-10 16:27:49'),
(11,2,'2026-07-13','17:00:00','18:00:00','available',NULL,'2026-07-10 16:27:49'),
(12,2,'2026-07-13','18:00:00','19:00:00','available',NULL,'2026-07-10 16:27:49'),
(13,2,'2026-07-13','19:00:00','20:00:00','available',NULL,'2026-07-10 16:27:49'),
(14,1,'2026-07-10','18:00:00','19:00:00','available',NULL,'2026-07-10 17:04:31'),
(15,1,'2026-07-10','19:00:00','20:00:00','booked',3,'2026-07-10 17:04:31'),
(16,1,'2026-07-14','06:00:00','07:00:00','booked',NULL,'2026-07-11 10:41:06'),
(17,1,'2026-07-14','07:00:00','08:00:00','available',NULL,'2026-07-11 10:41:06'),
(18,1,'2026-07-14','08:00:00','09:00:00','available',NULL,'2026-07-11 10:41:06'),
(19,1,'2026-07-14','09:00:00','10:00:00','available',NULL,'2026-07-11 10:41:06'),
(20,1,'2026-07-14','10:00:00','11:00:00','available',NULL,'2026-07-11 10:41:06'),
(21,1,'2026-07-14','16:00:00','17:00:00','available',NULL,'2026-07-11 10:41:06'),
(22,1,'2026-07-14','17:00:00','18:00:00','available',NULL,'2026-07-11 10:41:06'),
(23,1,'2026-07-14','18:00:00','19:00:00','available',NULL,'2026-07-11 10:41:06'),
(24,1,'2026-07-14','19:00:00','20:00:00','booked',20,'2026-07-11 10:41:06'),
(25,5,'2026-07-18','08:00:00','09:00:00','booked',8,'2026-07-11 20:06:39'),
(26,5,'2026-07-18','09:00:00','10:00:00','booked',NULL,'2026-07-11 20:06:39'),
(27,5,'2026-07-18','10:00:00','11:00:00','available',NULL,'2026-07-11 20:06:39'),
(28,5,'2026-07-18','11:00:00','12:00:00','available',NULL,'2026-07-11 20:06:39'),
(29,5,'2026-07-16','06:00:00','07:00:00','booked',NULL,'2026-07-11 20:06:43'),
(30,5,'2026-07-16','07:00:00','08:00:00','available',NULL,'2026-07-11 20:06:43'),
(31,5,'2026-07-16','08:00:00','09:00:00','available',NULL,'2026-07-11 20:06:43'),
(32,5,'2026-07-16','09:00:00','10:00:00','available',NULL,'2026-07-11 20:06:43'),
(33,5,'2026-07-16','10:00:00','11:00:00','available',NULL,'2026-07-11 20:06:43'),
(34,5,'2026-07-16','16:00:00','17:00:00','available',NULL,'2026-07-11 20:06:43'),
(35,5,'2026-07-16','17:00:00','18:00:00','available',NULL,'2026-07-11 20:06:43'),
(36,5,'2026-07-16','18:00:00','19:00:00','available',NULL,'2026-07-11 20:06:43'),
(37,5,'2026-07-16','19:00:00','20:00:00','booked',17,'2026-07-11 20:06:43'),
(38,5,'2026-07-22','06:00:00','07:00:00','available',NULL,'2026-07-12 09:37:52'),
(39,5,'2026-07-22','07:00:00','08:00:00','available',NULL,'2026-07-12 09:37:52'),
(40,5,'2026-07-22','08:00:00','09:00:00','available',NULL,'2026-07-12 09:37:52'),
(41,5,'2026-07-22','09:00:00','10:00:00','available',NULL,'2026-07-12 09:37:52'),
(42,5,'2026-07-22','10:00:00','11:00:00','available',NULL,'2026-07-12 09:37:52'),
(43,5,'2026-07-22','16:00:00','17:00:00','available',NULL,'2026-07-12 09:37:52'),
(44,5,'2026-07-22','17:00:00','18:00:00','available',NULL,'2026-07-12 09:37:52'),
(45,5,'2026-07-22','18:00:00','19:00:00','available',NULL,'2026-07-12 09:37:52'),
(46,5,'2026-07-22','19:00:00','20:00:00','available',NULL,'2026-07-12 09:37:52'),
(47,5,'2026-07-23','06:00:00','07:00:00','booked',NULL,'2026-07-12 09:38:53'),
(48,5,'2026-07-23','07:00:00','08:00:00','available',NULL,'2026-07-12 09:38:53'),
(49,5,'2026-07-23','08:00:00','09:00:00','available',NULL,'2026-07-12 09:38:53'),
(50,5,'2026-07-23','09:00:00','10:00:00','available',NULL,'2026-07-12 09:38:53'),
(51,5,'2026-07-23','10:00:00','11:00:00','available',NULL,'2026-07-12 09:38:53'),
(52,5,'2026-07-23','16:00:00','17:00:00','available',NULL,'2026-07-12 09:38:53'),
(53,5,'2026-07-23','17:00:00','18:00:00','available',NULL,'2026-07-12 09:38:53'),
(54,5,'2026-07-23','18:00:00','19:00:00','available',NULL,'2026-07-12 09:38:53'),
(55,5,'2026-07-23','19:00:00','20:00:00','available',NULL,'2026-07-12 09:38:53'),
(56,5,'2026-07-26','06:00:00','07:00:00','available',NULL,'2026-07-12 10:23:10'),
(57,5,'2026-07-26','07:00:00','08:00:00','available',NULL,'2026-07-12 10:23:10'),
(58,5,'2026-07-26','08:00:00','09:00:00','available',NULL,'2026-07-12 10:23:10'),
(59,5,'2026-07-26','09:00:00','10:00:00','available',NULL,'2026-07-12 10:23:10'),
(60,5,'2026-07-26','10:00:00','11:00:00','available',NULL,'2026-07-12 10:23:10'),
(61,5,'2026-07-26','16:00:00','17:00:00','available',NULL,'2026-07-12 10:23:10'),
(62,5,'2026-07-26','17:00:00','18:00:00','available',NULL,'2026-07-12 10:23:10'),
(63,5,'2026-07-26','18:00:00','19:00:00','available',NULL,'2026-07-12 10:23:10'),
(64,5,'2026-07-26','19:00:00','20:00:00','available',NULL,'2026-07-12 10:23:10'),
(65,5,'2026-07-27','06:00:00','07:00:00','booked',NULL,'2026-07-12 10:23:56'),
(66,5,'2026-07-27','07:00:00','08:00:00','available',NULL,'2026-07-12 10:23:56'),
(67,5,'2026-07-27','08:00:00','09:00:00','available',NULL,'2026-07-12 10:23:56'),
(68,5,'2026-07-27','09:00:00','10:00:00','available',NULL,'2026-07-12 10:23:56'),
(69,5,'2026-07-27','10:00:00','11:00:00','available',NULL,'2026-07-12 10:23:56'),
(70,5,'2026-07-27','16:00:00','17:00:00','available',NULL,'2026-07-12 10:23:56'),
(71,5,'2026-07-27','17:00:00','18:00:00','available',NULL,'2026-07-12 10:23:56'),
(72,5,'2026-07-27','18:00:00','19:00:00','available',NULL,'2026-07-12 10:23:56'),
(73,5,'2026-07-27','19:00:00','20:00:00','available',NULL,'2026-07-12 10:23:56'),
(74,5,'2026-08-01','08:00:00','09:00:00','booked',NULL,'2026-07-12 11:52:30'),
(75,5,'2026-08-01','09:00:00','10:00:00','available',NULL,'2026-07-12 11:52:30'),
(76,5,'2026-08-01','10:00:00','11:00:00','available',NULL,'2026-07-12 11:52:30'),
(77,5,'2026-08-01','11:00:00','12:00:00','available',NULL,'2026-07-12 11:52:30'),
(78,1,'2026-07-15','06:00:00','07:00:00','available',NULL,'2026-07-12 12:32:36'),
(79,1,'2026-07-15','07:00:00','08:00:00','available',NULL,'2026-07-12 12:32:36'),
(80,1,'2026-07-15','08:00:00','09:00:00','available',NULL,'2026-07-12 12:32:36'),
(81,1,'2026-07-15','09:00:00','10:00:00','available',NULL,'2026-07-12 12:32:36'),
(82,1,'2026-07-15','10:00:00','11:00:00','available',NULL,'2026-07-12 12:32:36'),
(83,1,'2026-07-15','16:00:00','17:00:00','available',NULL,'2026-07-12 12:32:36'),
(84,1,'2026-07-15','17:00:00','18:00:00','available',NULL,'2026-07-12 12:32:36'),
(85,1,'2026-07-15','18:00:00','19:00:00','available',NULL,'2026-07-12 12:32:36'),
(86,1,'2026-07-15','19:00:00','20:00:00','available',NULL,'2026-07-12 12:32:36'),
(87,5,'2026-07-30','06:00:00','07:00:00','available',NULL,'2026-07-12 12:41:53'),
(88,5,'2026-07-30','07:00:00','08:00:00','available',NULL,'2026-07-12 12:41:53'),
(89,5,'2026-07-30','08:00:00','09:00:00','available',NULL,'2026-07-12 12:41:53'),
(90,5,'2026-07-30','09:00:00','10:00:00','available',NULL,'2026-07-12 12:41:53'),
(91,5,'2026-07-30','10:00:00','11:00:00','available',NULL,'2026-07-12 12:41:53'),
(92,5,'2026-07-30','16:00:00','17:00:00','available',NULL,'2026-07-12 12:41:53'),
(93,5,'2026-07-30','17:00:00','18:00:00','available',NULL,'2026-07-12 12:41:53'),
(94,5,'2026-07-30','18:00:00','19:00:00','available',NULL,'2026-07-12 12:41:53'),
(95,5,'2026-07-30','19:00:00','20:00:00','available',NULL,'2026-07-12 12:41:53'),
(96,1,'2026-07-17','06:00:00','07:30:00','available',NULL,'2026-07-12 12:42:39'),
(97,1,'2026-07-17','07:30:00','09:00:00','available',NULL,'2026-07-12 12:42:39'),
(98,1,'2026-07-17','09:00:00','10:30:00','available',NULL,'2026-07-12 12:42:39'),
(99,1,'2026-07-17','16:00:00','17:30:00','booked',14,'2026-07-12 12:42:39'),
(100,1,'2026-07-17','17:30:00','19:00:00','available',NULL,'2026-07-12 12:42:39'),
(101,5,'2026-08-06','06:00:00','07:00:00','booked',NULL,'2026-07-12 12:51:10'),
(102,5,'2026-08-06','07:00:00','08:00:00','available',NULL,'2026-07-12 12:51:10'),
(103,5,'2026-08-06','08:00:00','09:00:00','available',NULL,'2026-07-12 12:51:10'),
(104,5,'2026-08-06','09:00:00','10:00:00','available',NULL,'2026-07-12 12:51:10'),
(105,5,'2026-08-06','10:00:00','11:00:00','available',NULL,'2026-07-12 12:51:10'),
(106,5,'2026-08-06','16:00:00','17:00:00','available',NULL,'2026-07-12 12:51:10'),
(107,5,'2026-08-06','17:00:00','18:00:00','available',NULL,'2026-07-12 12:51:10'),
(108,5,'2026-08-06','18:00:00','19:00:00','available',NULL,'2026-07-12 12:51:10'),
(109,5,'2026-08-06','19:00:00','20:00:00','available',NULL,'2026-07-12 12:51:10'),
(110,2,'2026-07-31','06:00:00','07:00:00','booked',18,'2026-07-13 19:54:07'),
(111,2,'2026-07-31','07:00:00','08:00:00','available',NULL,'2026-07-13 19:54:07'),
(112,2,'2026-07-31','08:00:00','09:00:00','available',NULL,'2026-07-13 19:54:07'),
(113,2,'2026-07-31','09:00:00','10:00:00','available',NULL,'2026-07-13 19:54:07'),
(114,2,'2026-07-31','10:00:00','11:00:00','available',NULL,'2026-07-13 19:54:07'),
(115,2,'2026-07-31','16:00:00','17:00:00','available',NULL,'2026-07-13 19:54:07'),
(116,2,'2026-07-31','17:00:00','18:00:00','available',NULL,'2026-07-13 19:54:07'),
(117,2,'2026-07-31','18:00:00','19:00:00','available',NULL,'2026-07-13 19:54:07'),
(118,2,'2026-07-31','19:00:00','20:00:00','available',NULL,'2026-07-13 19:54:07');
/*!40000 ALTER TABLE `booking_slots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_ref` varchar(20) NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `instructor_id` bigint(20) unsigned NOT NULL,
  `package_id` bigint(20) unsigned NOT NULL,
  `slot_id` bigint(20) unsigned DEFAULT NULL,
  `slot_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `client_timezone` varchar(60) NOT NULL DEFAULT 'UTC',
  `status` enum('pending_payment','awaiting_trainer_approval','confirmed','completed','cancelled','rescheduled') NOT NULL DEFAULT 'pending_payment',
  `price` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `coupon_id` bigint(20) unsigned DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `currency` char(3) NOT NULL DEFAULT 'INR',
  `notes` text DEFAULT NULL,
  `reminder_sent_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_ref` (`booking_ref`),
  KEY `fk_booking_package` (`package_id`),
  KEY `fk_booking_slot` (`slot_id`),
  KEY `fk_booking_coupon` (`coupon_id`),
  KEY `idx_booking_client` (`client_id`),
  KEY `idx_booking_instructor` (`instructor_id`),
  KEY `idx_booking_status` (`status`),
  CONSTRAINT `fk_booking_client` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_booking_coupon` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_booking_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_booking_package` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`),
  CONSTRAINT `fk_booking_slot` FOREIGN KEY (`slot_id`) REFERENCES `booking_slots` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES
(3,'VYG5ED3D0K',10,1,1,15,'2026-07-10','19:00:00','20:00:00','Asia/Calcutta','pending_payment',1499.00,74.95,0.00,NULL,1573.95,'INR',NULL,NULL,'2026-07-10 17:04:48','2026-07-11 10:38:22'),
(8,'VYAUIFP234',10,5,1,25,'2026-07-18','08:00:00','09:00:00','Asia/Kolkata','completed',1499.00,74.95,0.00,NULL,1573.95,'INR',NULL,NULL,'2026-07-11 22:29:23','2026-07-15 19:42:53'),
(9,'VYYG2TAQL3',10,5,1,26,'2026-07-18','09:00:00','10:00:00','Asia/Kolkata','cancelled',1499.00,74.95,0.00,NULL,1573.95,'INR',NULL,NULL,'2026-07-11 22:31:54','2026-07-11 22:32:36'),
(14,'VY3R3VHIQ6',10,1,2,99,'2026-07-17','16:00:00','17:30:00','Asia/Calcutta','pending_payment',2199.00,109.95,0.00,NULL,2308.95,'INR',NULL,NULL,'2026-07-12 12:44:58','2026-07-15 19:47:26'),
(17,'VY8VJXN9RF',21,5,1,37,'2026-07-16','19:00:00','20:00:00','Asia/Calcutta','awaiting_trainer_approval',1499.00,74.95,0.00,NULL,1573.95,'INR',NULL,NULL,'2026-07-13 19:40:04','2026-07-13 19:40:10'),
(18,'VYX1CM4K8V',21,2,1,110,'2026-07-31','06:00:00','07:00:00','Asia/Calcutta','awaiting_trainer_approval',1499.00,74.95,0.00,NULL,1573.95,'INR',NULL,NULL,'2026-07-13 19:54:19','2026-07-18 16:41:50'),
(20,'VYPH4YZWKC',21,1,1,24,'2026-07-14','19:00:00','20:00:00','Asia/Calcutta','awaiting_trainer_approval',1499.00,74.95,0.00,NULL,1573.95,'INR',NULL,NULL,'2026-07-14 17:37:18','2026-07-14 17:37:22');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calendar_syncs`
--

DROP TABLE IF EXISTS `calendar_syncs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `calendar_syncs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `provider` enum('google','outlook','apple') NOT NULL,
  `sync_token` varchar(255) DEFAULT NULL,
  `connected_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_calsync` (`client_id`,`provider`),
  CONSTRAINT `fk_calsync_client` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calendar_syncs`
--

LOCK TABLES `calendar_syncs` WRITE;
/*!40000 ALTER TABLE `calendar_syncs` DISABLE KEYS */;
/*!40000 ALTER TABLE `calendar_syncs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_profiles`
--

DROP TABLE IF EXISTS `client_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `age` tinyint(3) unsigned DEFAULT NULL,
  `height_cm` smallint(5) unsigned DEFAULT NULL,
  `weight_kg` smallint(5) unsigned DEFAULT NULL,
  `gender` enum('female','male','non_binary','prefer_not_to_say') DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `bio` varchar(200) DEFAULT NULL,
  `medical_notes` text DEFAULT NULL,
  `emergency_contact_name` varchar(150) DEFAULT NULL,
  `emergency_contact_phone` varchar(20) DEFAULT NULL,
  `emergency_contact_relationship` varchar(50) DEFAULT NULL,
  `client_category` enum('child','teenager','adult','senior_citizen','pregnant_woman','postpartum_mother') DEFAULT NULL,
  `health_assessment` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`health_assessment`)),
  `preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`preferences`)),
  `timezone` varchar(60) NOT NULL DEFAULT 'UTC',
  `avatar_path` varchar(255) DEFAULT NULL,
  `onboarding_completed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `fk_clientprofile_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_profiles`
--

LOCK TABLES `client_profiles` WRITE;
/*!40000 ALTER TABLE `client_profiles` DISABLE KEYS */;
INSERT INTO `client_profiles` VALUES
(3,10,NULL,21,NULL,NULL,'male','tamilnadu',NULL,'','Mild lower back sensitivity ΓÇö avoid deep backbends.',NULL,NULL,NULL,'pregnant_woman',NULL,NULL,'UTC','/uploads/avatars/10-1783839499.jpg','2026-07-14 20:45:56','2026-07-10 17:04:08','2026-07-16 16:56:58'),
(5,21,'2026-07-16',0,223,22,'male','','coimbatore','','','242424234','12345678','Sibling','child','{\"medicalHistory\":{\"conditions\":[\"vertigo\"],\"details\":{\"vertigo\":{\"since\":\"lt_1y\",\"medication\":true,\"doctorRestriction\":true}}},\"currentStep\":\"surgeries\",\"painAssessment\":{\"areas\":[\"no_pain\"]},\"doctorRestrictions\":{\"hasRestriction\":false,\"notes\":\"\"},\"categorySafety\":{\"guardianPresent\":true},\"stage\":2,\"advancedHealth\":{\"changed\":false},\"medication\":{\"recentChange\":true,\"takingMedication\":false},\"surgeries\":{\"hadSurgery\":false}}',NULL,'Asia/Calcutta',NULL,'2026-07-16 16:12:25','2026-07-16 16:12:25','2026-07-19 14:27:40'),
(6,25,'2004-09-27',21,175,59,'male','','coimbatore','','','','','','adult',NULL,NULL,'Asia/Calcutta',NULL,'2026-07-23 13:30:20','2026-07-23 13:30:20','2026-07-23 13:30:20');
/*!40000 ALTER TABLE `client_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consents`
--

DROP TABLE IF EXISTS `consents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `consents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `booking_id` bigint(20) unsigned DEFAULT NULL,
  `consent_version` varchar(20) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `timezone` varchar(60) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_consent_booking` (`booking_id`),
  KEY `idx_consent_user` (`user_id`),
  CONSTRAINT `fk_consent_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_consent_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consents`
--

LOCK TABLES `consents` WRITE;
/*!40000 ALTER TABLE `consents` DISABLE KEYS */;
/*!40000 ALTER TABLE `consents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `email` varchar(190) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(40) NOT NULL,
  `discount_type` enum('percent','flat') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `max_uses` int(10) unsigned DEFAULT NULL,
  `used_count` int(10) unsigned NOT NULL DEFAULT 0,
  `min_order_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
INSERT INTO `coupons` VALUES
(1,'WELCOME10','percent',10.00,500,0,0.00,'2026-07-10 13:03:44','2027-07-10 13:03:44',1,'2026-07-10 13:03:44'),
(2,'FLAT200','flat',200.00,200,0,1000.00,'2026-07-10 13:03:44','2027-07-10 13:03:44',1,'2026-07-10 13:03:44');
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_verifications`
--

DROP TABLE IF EXISTS `email_verifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_verifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `verified_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_emailverify_user` (`user_id`),
  CONSTRAINT `fk_emailverify_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_verifications`
--

LOCK TABLES `email_verifications` WRITE;
/*!40000 ALTER TABLE `email_verifications` DISABLE KEYS */;
INSERT INTO `email_verifications` VALUES
(2,10,'e3a1c30e2f0f63f5d8c6946f91e2ce6e984efb3f29c16a28c824304e91a35f1b','2026-07-11 17:02:54',NULL,'2026-07-10 17:02:54'),
(4,14,'9167624f3341b834e1173677dab46483adbc53a19ccc21b11f9cf0b4e7f8714a','2026-07-12 19:58:27',NULL,'2026-07-11 19:58:27'),
(5,15,'20f437dc5e41925ac9eea73ddeccf09adfbf5bbb4422766d4048549ee9893b31','2026-07-12 20:00:30',NULL,'2026-07-11 20:00:30'),
(6,16,'270ff81007b753108775664b972cdec5075573a82259411c1c16d03f32dd0710','2026-07-12 20:01:45',NULL,'2026-07-11 20:01:45'),
(7,17,'a61f52b6850aecbc598314ac697b8b3692c03eb81b6b82886507397af594e280','2026-07-12 20:02:19',NULL,'2026-07-11 20:02:19'),
(8,18,'e4b6d1a07c04d7f2c081574578c8719e9050dacd8b51152e23136cd4b4a2dac7','2026-07-12 20:06:02',NULL,'2026-07-11 20:06:02'),
(9,19,'0b74345171a9b7cf45c0681e0cf4957e891cd1f61f97146e12b3ae8c3db33987','2026-07-12 20:09:43',NULL,'2026-07-11 20:09:43'),
(10,20,'0278b47adbc6e9c586c945e3394f08ece31cf4909737639895bbda577ebe1c7a','2026-07-12 21:02:03',NULL,'2026-07-11 21:02:03'),
(11,21,'529cc6237f8330e7264b686c07394d0b76d29fc2aba365ce3b27efd7ac99f0ac','2026-07-14 19:13:22',NULL,'2026-07-13 19:13:22'),
(13,25,'0e66dea041f73c944fb0c335cd873fbbd891386aa2d234ad96f7bb23864378f1','2026-07-24 12:56:56',NULL,'2026-07-23 12:56:56');
/*!40000 ALTER TABLE `email_verifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `favorites` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `instructor_id` bigint(20) unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_favorite` (`client_id`,`instructor_id`),
  KEY `fk_favorite_instructor` (`instructor_id`),
  CONSTRAINT `fk_favorite_client` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_favorite_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites`
--

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
INSERT INTO `favorites` VALUES
(1,10,5,'2026-07-11 22:33:16'),
(4,21,2,'2026-07-13 19:52:55');
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_images`
--

DROP TABLE IF EXISTS `gallery_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `file_path` varchar(255) NOT NULL,
  `caption` varchar(200) DEFAULT NULL,
  `category` varchar(60) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_gallery_sort` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_images`
--

LOCK TABLES `gallery_images` WRITE;
/*!40000 ALTER TABLE `gallery_images` DISABLE KEYS */;
INSERT INTO `gallery_images` VALUES
(3,'/assets/img/gallery/gallery-1783952347-9209.jpg','sf','sf',2,0,'2026-07-13 19:49:07'),
(4,'/assets/img/client/pose-headstand-1.webp','Headstand practice','inversions',1,0,'2026-07-14 14:56:45'),
(5,'/assets/img/client/pose-headstand-2.webp','Finding balance in headstand','inversions',2,1,'2026-07-14 14:56:45'),
(6,'/assets/img/client/pose-shoulderstand.webp','Shoulderstand','inversions',3,1,'2026-07-14 14:56:45'),
(7,'/assets/img/client/pose-shoulderstand-2.webp','Shoulderstand flow','inversions',4,1,'2026-07-14 14:56:45'),
(8,'/assets/img/client/pose-plough.webp','Lizard Pose','hip opener',5,1,'2026-07-14 14:56:45'),
(9,'/assets/img/client/pose-plough-2.webp','Lizard Pose, deep stretch','hip opener',6,1,'2026-07-14 14:56:45'),
(10,'/assets/img/client/pose-childs-pose.webp','Child\'s pose','stillness',7,1,'2026-07-14 14:56:45'),
(11,'/assets/img/client/pose-childs-pose-2.webp','Karnapidasana','inversion',8,1,'2026-07-14 14:56:45'),
(12,'/assets/img/client/pose-childs-pose-extended.webp','Child\'s Pose with a Shoulder Stretch Bind','deep stretch',9,1,'2026-07-14 14:56:45'),
(13,'/assets/img/client/philosophy-meditation.webp','A moment of stillness','stillness',10,1,'2026-07-14 14:56:45'),
(14,'/assets/img/client/philosophy-flow.webp','Finding your flow','stillness',11,1,'2026-07-14 14:56:45'),
(15,'/assets/img/client/pose-seated-twist.webp','Gomukhasana','shoulder opener',12,1,'2026-07-14 14:56:45'),
(16,'/assets/img/client/pose-crow-1.webp','Crow pose','poses',13,1,'2026-07-14 14:56:45'),
(17,'/assets/img/client/pose-crow-2.webp','Crow pose, building strength','poses',14,1,'2026-07-14 14:56:45'),
(18,'/assets/img/client/pose-crow-3.webp','Crow pose practice','poses',15,1,'2026-07-14 14:56:45'),
(19,'/assets/img/client/pose-bridge.webp','Bridge pose','poses',16,1,'2026-07-14 14:56:45'),
(20,'/assets/img/client/pose-plank-fold.webp','Plank to fold transition','poses',17,1,'2026-07-14 14:56:45'),
(21,'/assets/img/client/pose-standing-fold.webp','Standing forward fold','poses',18,1,'2026-07-14 14:56:45'),
(22,'/assets/img/client/pose-boat-balcony.webp','Boat pose','poses',19,1,'2026-07-14 14:56:45'),
(23,'/assets/img/client/pose-boat-balcony-2.webp','Boat pose, core strength','poses',20,1,'2026-07-14 14:56:45'),
(24,'/assets/img/client/pose-side-stretch.webp','Side body stretch','poses',21,1,'2026-07-14 14:56:45'),
(25,'/assets/img/client/pose-dramatic-stretch.webp','Deep stretch practice','poses',22,1,'2026-07-14 14:56:45');
/*!40000 ALTER TABLE `gallery_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instructor_availability`
--

DROP TABLE IF EXISTS `instructor_availability`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `instructor_availability` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `instructor_id` bigint(20) unsigned NOT NULL,
  `day_of_week` tinyint(3) unsigned DEFAULT NULL COMMENT '0=Sunday..6=Saturday, NULL if is_recurring=0 (specific_date used instead)',
  `specific_date` date DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_recurring` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_availability_instructor` (`instructor_id`),
  CONSTRAINT `fk_availability_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instructor_availability`
--

LOCK TABLES `instructor_availability` WRITE;
/*!40000 ALTER TABLE `instructor_availability` DISABLE KEYS */;
INSERT INTO `instructor_availability` VALUES
(1,1,1,NULL,'06:00:00','11:00:00',1,'2026-07-10 13:03:44'),
(2,1,1,NULL,'16:00:00','20:00:00',1,'2026-07-10 13:03:44'),
(3,2,1,NULL,'06:00:00','11:00:00',1,'2026-07-10 13:03:44'),
(4,2,1,NULL,'16:00:00','20:00:00',1,'2026-07-10 13:03:44'),
(5,3,1,NULL,'06:00:00','11:00:00',1,'2026-07-10 13:03:44'),
(6,3,1,NULL,'16:00:00','20:00:00',1,'2026-07-10 13:03:44'),
(7,1,2,NULL,'06:00:00','11:00:00',1,'2026-07-10 13:03:44'),
(8,1,2,NULL,'16:00:00','20:00:00',1,'2026-07-10 13:03:44'),
(9,2,2,NULL,'06:00:00','11:00:00',1,'2026-07-10 13:03:44'),
(10,2,2,NULL,'16:00:00','20:00:00',1,'2026-07-10 13:03:44'),
(11,3,2,NULL,'06:00:00','11:00:00',1,'2026-07-10 13:03:44'),
(12,3,2,NULL,'16:00:00','20:00:00',1,'2026-07-10 13:03:44'),
(13,1,3,NULL,'06:00:00','11:00:00',1,'2026-07-10 13:03:44'),
(14,1,3,NULL,'16:00:00','20:00:00',1,'2026-07-10 13:03:44'),
(15,2,3,NULL,'06:00:00','11:00:00',1,'2026-07-10 13:03:44'),
(16,2,3,NULL,'16:00:00','20:00:00',1,'2026-07-10 13:03:44'),
(17,3,3,NULL,'06:00:00','11:00:00',1,'2026-07-10 13:03:44'),
(18,3,3,NULL,'16:00:00','20:00:00',1,'2026-07-10 13:03:44'),
(19,1,4,NULL,'06:00:00','11:00:00',1,'2026-07-10 13:03:44'),
(20,1,4,NULL,'16:00:00','20:00:00',1,'2026-07-10 13:03:44'),
(21,2,4,NULL,'06:00:00','11:00:00',1,'2026-07-10 13:03:44'),
(22,2,4,NULL,'16:00:00','20:00:00',1,'2026-07-10 13:03:44'),
(23,3,4,NULL,'06:00:00','11:00:00',1,'2026-07-10 13:03:44'),
(24,3,4,NULL,'16:00:00','20:00:00',1,'2026-07-10 13:03:44'),
(25,1,5,NULL,'06:00:00','11:00:00',1,'2026-07-10 13:03:44'),
(26,1,5,NULL,'16:00:00','20:00:00',1,'2026-07-10 13:03:44'),
(27,2,5,NULL,'06:00:00','11:00:00',1,'2026-07-10 13:03:44'),
(28,2,5,NULL,'16:00:00','20:00:00',1,'2026-07-10 13:03:44'),
(29,3,5,NULL,'06:00:00','11:00:00',1,'2026-07-10 13:03:44'),
(30,3,5,NULL,'16:00:00','20:00:00',1,'2026-07-10 13:03:44'),
(32,1,6,NULL,'08:00:00','12:00:00',1,'2026-07-10 13:03:44'),
(33,2,6,NULL,'08:00:00','12:00:00',1,'2026-07-10 13:03:44'),
(34,3,6,NULL,'08:00:00','12:00:00',1,'2026-07-10 13:03:44'),
(35,5,0,NULL,'06:00:00','11:00:00',1,'2026-07-11 19:11:40'),
(36,5,1,NULL,'06:00:00','11:00:00',1,'2026-07-11 19:11:40'),
(37,5,2,NULL,'06:00:00','11:00:00',1,'2026-07-11 19:11:40'),
(38,5,3,NULL,'06:00:00','11:00:00',1,'2026-07-11 19:11:40'),
(39,5,4,NULL,'06:00:00','11:00:00',1,'2026-07-11 19:11:40'),
(42,5,0,NULL,'16:00:00','20:00:00',1,'2026-07-11 19:11:40'),
(43,5,1,NULL,'16:00:00','20:00:00',1,'2026-07-11 19:11:40'),
(44,5,2,NULL,'16:00:00','20:00:00',1,'2026-07-11 19:11:40'),
(45,5,3,NULL,'16:00:00','20:00:00',1,'2026-07-11 19:11:40'),
(46,5,4,NULL,'16:00:00','20:00:00',1,'2026-07-11 19:11:40'),
(49,5,6,NULL,'08:00:00','12:00:00',1,'2026-07-11 19:11:40');
/*!40000 ALTER TABLE `instructor_availability` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instructor_blocked_dates`
--

DROP TABLE IF EXISTS `instructor_blocked_dates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `instructor_blocked_dates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `instructor_id` bigint(20) unsigned DEFAULT NULL COMMENT 'NULL = platform-wide holiday',
  `blocked_date` date NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_blocked_instructor` (`instructor_id`),
  KEY `idx_blocked_date` (`blocked_date`),
  CONSTRAINT `fk_blocked_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instructor_blocked_dates`
--

LOCK TABLES `instructor_blocked_dates` WRITE;
/*!40000 ALTER TABLE `instructor_blocked_dates` DISABLE KEYS */;
INSERT INTO `instructor_blocked_dates` VALUES
(1,NULL,'2026-07-20','Public Holiday','2026-07-10 13:03:44');
/*!40000 ALTER TABLE `instructor_blocked_dates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instructor_certificate_files`
--

DROP TABLE IF EXISTS `instructor_certificate_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `instructor_certificate_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `instructor_id` bigint(20) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_instructor_cert_file_instructor` (`instructor_id`),
  CONSTRAINT `fk_instructor_cert_file_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instructor_certificate_files`
--

LOCK TABLES `instructor_certificate_files` WRITE;
/*!40000 ALTER TABLE `instructor_certificate_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `instructor_certificate_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instructor_gallery_images`
--

DROP TABLE IF EXISTS `instructor_gallery_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `instructor_gallery_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `instructor_id` bigint(20) unsigned NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_instructor_gallery_instructor` (`instructor_id`),
  CONSTRAINT `fk_instructor_gallery_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instructor_gallery_images`
--

LOCK TABLES `instructor_gallery_images` WRITE;
/*!40000 ALTER TABLE `instructor_gallery_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `instructor_gallery_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instructor_reviews`
--

DROP TABLE IF EXISTS `instructor_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `instructor_reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `instructor_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `booking_id` bigint(20) unsigned DEFAULT NULL,
  `rating` tinyint(3) unsigned NOT NULL,
  `review_text` text DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_review_client` (`client_id`),
  KEY `idx_review_instructor` (`instructor_id`),
  CONSTRAINT `fk_review_client` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_review_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instructor_reviews`
--

LOCK TABLES `instructor_reviews` WRITE;
/*!40000 ALTER TABLE `instructor_reviews` DISABLE KEYS */;
INSERT INTO `instructor_reviews` VALUES
(1,5,10,8,5,'Wonderful session, very calming and well-guided.',1,'2026-07-11 22:34:04');
/*!40000 ALTER TABLE `instructor_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instructor_services`
--

DROP TABLE IF EXISTS `instructor_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `instructor_services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `instructor_id` bigint(20) unsigned NOT NULL,
  `package_id` bigint(20) unsigned NOT NULL,
  `price_override` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_instructor_package` (`instructor_id`,`package_id`),
  KEY `fk_instsvc_package` (`package_id`),
  CONSTRAINT `fk_instsvc_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_instsvc_package` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instructor_services`
--

LOCK TABLES `instructor_services` WRITE;
/*!40000 ALTER TABLE `instructor_services` DISABLE KEYS */;
INSERT INTO `instructor_services` VALUES
(1,1,1,NULL,1),
(2,2,1,NULL,1),
(3,3,1,NULL,1),
(4,1,2,NULL,1),
(5,2,2,NULL,1),
(6,3,2,NULL,1),
(7,1,3,NULL,1),
(8,2,3,NULL,1),
(9,3,3,NULL,1),
(10,1,4,NULL,1),
(11,2,4,NULL,1),
(12,3,4,NULL,1),
(13,1,5,NULL,1),
(14,2,5,NULL,1),
(15,3,5,NULL,1),
(16,1,6,NULL,1),
(17,2,6,NULL,1),
(18,3,6,NULL,1),
(19,5,1,NULL,1),
(20,5,2,NULL,1),
(21,5,3,NULL,1),
(22,5,4,NULL,1),
(23,5,5,NULL,1),
(24,5,6,NULL,1);
/*!40000 ALTER TABLE `instructor_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instructors`
--

DROP TABLE IF EXISTS `instructors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `instructors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `headline` varchar(200) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `experience_years` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `certificates` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`certificates`)),
  `specialties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specialties`)),
  `timezone` varchar(60) NOT NULL DEFAULT 'UTC',
  `rating_avg` decimal(3,2) NOT NULL DEFAULT 0.00,
  `rating_count` int(10) unsigned NOT NULL DEFAULT 0,
  `avatar_path` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','test') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `idx_instructor_status` (`status`),
  CONSTRAINT `fk_instructor_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instructors`
--

LOCK TABLES `instructors` WRITE;
/*!40000 ALTER TABLE `instructors` DISABLE KEYS */;
INSERT INTO `instructors` VALUES
(1,1,'Certified Hatha & Vinyasa Instructor','Ananya has been teaching yoga for over a decade, blending traditional Hatha with modern Vinyasa flow.',11,'[\"RYT-500\",\"Yoga Alliance Certified\"]','[\"Hatha\",\"Vinyasa\",\"Prenatal\"]','Asia/Kolkata',4.80,132,'/assets/img/instructors/ananya.jpg','active','2026-07-10 13:03:44','2026-07-12 10:46:26'),
(2,2,'Power Yoga & Strength Coach','Rahul specializes in power yoga and strength-focused sequences for athletes and beginners alike.',7,'[\"RYT-200\", \"Power Yoga Specialist\"]','[\"Power Yoga\", \"Core Strength\"]','Asia/Kolkata',4.60,84,'/assets/img/instructors/rahul.jpg','active','2026-07-10 13:03:44','2026-07-13 19:47:05'),
(3,3,'Restorative & Mindfulness Guide','Sarah focuses on restorative yoga, breathwork, and mindfulness practices for stress relief.',9,'[\"RYT-300\", \"Mindfulness Based Stress Reduction\"]','[\"Restorative\", \"Breathwork\", \"Meditation\"]','Europe/London',4.90,201,'/assets/img/instructors/sarah.jpg','active','2026-07-10 13:03:44','2026-07-13 19:51:19'),
(5,13,'Certified Yoga Teacher & Evaluator','Vijaya is a certified Yoga Teacher and Evaluator, accredited by the Yoga Certification Board under the Certification Scheme for Yoga Professionals, Ministry of AYUSH, Government of India. Her practice blends steady, breath-led sequencing with real attention to how each student is actually doing that day.',0,'[\"Yoga Teacher & Evaluator — Yoga Certification Board (Ministry of AYUSH, Govt. of India)\", \"RYT 200 Hours\", \"RYT 300 Hours\", \"Art of Yoga Teaching 50 Hours\", \"Mat Pilates Teacher Training Course\", \"Therapeutic Yoga TTC 100 Hours\", \"Prenatal and Postnatal TTC 85 Hours\", \"Garbhsanskara, Breastfeeding and Infant Massage\", \"Face Yoga Teacher Training Course\"]','[\"Hatha Yoga\", \"Vinyasa Flow\", \"Restorative Yoga\"]','Asia/Kolkata',5.00,1,'/assets/img/client/instructor-vijaya.webp','active','2026-07-11 19:11:40','2026-07-13 19:51:21'),
(6,22,NULL,NULL,0,NULL,NULL,'UTC',0.00,0,NULL,'test','2026-07-13 19:51:35','2026-07-14 14:44:08');
/*!40000 ALTER TABLE `instructors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meeting_links`
--

DROP TABLE IF EXISTS `meeting_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `meeting_links` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `provider` enum('google_meet','zoom') NOT NULL,
  `url` varchar(500) NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_id` (`booking_id`),
  KEY `fk_meetinglink_admin` (`created_by`),
  CONSTRAINT `fk_meetinglink_admin` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_meetinglink_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meeting_links`
--

LOCK TABLES `meeting_links` WRITE;
/*!40000 ALTER TABLE `meeting_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `meeting_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletter_subscribers`
--

DROP TABLE IF EXISTS `newsletter_subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `newsletter_subscribers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter_subscribers`
--

LOCK TABLES `newsletter_subscribers` WRITE;
/*!40000 ALTER TABLE `newsletter_subscribers` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletter_subscribers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `type` varchar(60) NOT NULL,
  `channel` enum('email','sms','in_app') NOT NULL DEFAULT 'in_app',
  `title` varchar(200) NOT NULL,
  `body` text DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_notification_user` (`user_id`,`is_read`),
  CONSTRAINT `fk_notification_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES
(2,8,'booking_rejected','in_app','Booking rejected ΓÇö VYYG2TAQL3','A trainer rejected a paid booking. The client\'s payment needs manual review for a refund.',0,'2026-07-11 22:32:36'),
(7,13,'reschedule_requested','in_app','Reschedule request ΓÇö VYBB0HR4IA','A client has requested to move their session to 2026-07-27 at 6:00 AM.',1,'2026-07-12 10:23:54'),
(10,10,'profile_milestone','in_app','Profile 20% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-15 16:26:53'),
(11,10,'profile_milestone','in_app','Profile 20% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-15 18:46:05'),
(12,10,'profile_milestone','in_app','Profile 40% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-15 18:46:54'),
(13,10,'profile_milestone','in_app','Profile 20% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-15 18:47:28'),
(14,10,'profile_milestone','in_app','Profile 40% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-15 18:56:41'),
(15,10,'profile_milestone','in_app','Profile 65% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-15 18:56:48'),
(16,10,'profile_milestone','in_app','Profile 65% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-15 18:57:21'),
(17,10,'profile_milestone','in_app','Profile 65% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-15 19:03:16'),
(18,10,'profile_milestone','in_app','Profile 80% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-15 19:03:52'),
(19,10,'profile_milestone','in_app','Profile 80% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-15 19:04:35'),
(20,10,'profile_milestone','in_app','Profile 80% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-15 19:12:00'),
(21,10,'profile_milestone','in_app','Profile 100% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-15 19:12:07'),
(22,10,'profile_milestone','in_app','Profile 100% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-15 19:12:41'),
(23,10,'profile_milestone','in_app','Profile 100% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-15 19:46:33'),
(24,10,'profile_milestone','in_app','Profile 100% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-15 19:53:50'),
(25,10,'profile_milestone','in_app','Profile 20% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-15 19:55:59'),
(26,10,'profile_milestone','in_app','Profile 20% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',0,'2026-07-15 19:56:27'),
(27,21,'profile_milestone','in_app','Profile 20% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-16 16:12:25'),
(28,10,'profile_milestone','in_app','Profile 20% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',0,'2026-07-16 16:28:34'),
(29,10,'profile_milestone','in_app','Profile 20% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',0,'2026-07-16 16:28:37'),
(30,10,'profile_milestone','in_app','Profile 20% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',0,'2026-07-16 16:29:56'),
(31,10,'profile_milestone','in_app','Profile 20% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',0,'2026-07-16 16:30:21'),
(32,21,'profile_milestone','in_app','Profile 20% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-16 16:51:25'),
(33,10,'profile_milestone','in_app','Profile 20% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',0,'2026-07-16 16:53:05'),
(34,10,'profile_milestone','in_app','Profile 20% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',0,'2026-07-16 16:56:31'),
(35,21,'profile_milestone','in_app','Profile 20% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-16 20:43:39'),
(36,21,'profile_milestone','in_app','Profile 40% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-16 20:45:38'),
(37,21,'profile_milestone','in_app','Profile 40% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-16 21:08:53'),
(38,21,'profile_milestone','in_app','Profile 40% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-18 11:31:57'),
(39,21,'profile_milestone','in_app','Profile 40% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',1,'2026-07-18 16:41:13'),
(40,21,'profile_milestone','in_app','Profile 40% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',0,'2026-07-19 14:26:42'),
(41,21,'profile_milestone','in_app','Profile 40% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',0,'2026-07-22 16:37:27'),
(42,21,'profile_milestone','in_app','Profile 40% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',0,'2026-07-23 12:54:31'),
(43,25,'profile_milestone','in_app','Profile 20% complete!','Nice progress ΓÇö keep going to unlock your full wellness profile.',0,'2026-07-23 13:30:20');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `otp_codes`
--

DROP TABLE IF EXISTS `otp_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `otp_codes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `channel` enum('email','sms') NOT NULL,
  `purpose` enum('verify_account','reset_password','login') NOT NULL,
  `code_hash` varchar(255) NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `expires_at` datetime NOT NULL,
  `consumed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_otp_user_purpose` (`user_id`,`purpose`),
  CONSTRAINT `fk_otp_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `otp_codes`
--

LOCK TABLES `otp_codes` WRITE;
/*!40000 ALTER TABLE `otp_codes` DISABLE KEYS */;
INSERT INTO `otp_codes` VALUES
(3,6,'email','verify_account','$2y$10$fFy6BJ/VXZqHjnnNlSbB1e5HW.XxQVcSiU5anjUqvnBM92KuOiu0C',0,'2026-07-10 14:23:34',NULL,'2026-07-10 14:13:34');
/*!40000 ALTER TABLE `otp_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `packages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `service_type_id` bigint(20) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `sessions_count` smallint(5) unsigned NOT NULL DEFAULT 1,
  `duration_minutes` smallint(5) unsigned NOT NULL DEFAULT 60,
  `max_participants` smallint(5) unsigned NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `currency` char(3) NOT NULL DEFAULT 'INR',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_package_servicetype` (`service_type_id`),
  CONSTRAINT `fk_package_servicetype` FOREIGN KEY (`service_type_id`) REFERENCES `service_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `packages`
--

LOCK TABLES `packages` WRITE;
/*!40000 ALTER TABLE `packages` DISABLE KEYS */;
INSERT INTO `packages` VALUES
(1,1,'Single Session','One-on-one 60 minute session.',1,60,1,1499.00,'INR',1,1,'2026-07-10 13:03:44'),
(2,1,'Extended Private Session','One-on-one 90 minute deep-dive session.',1,90,1,2199.00,'INR',1,0,'2026-07-10 13:03:44'),
(3,2,'Group Drop-in','Single group class, up to 12 participants.',1,60,12,599.00,'INR',1,0,'2026-07-10 13:03:44'),
(4,3,'Weekly Package','3 sessions per week for one week.',3,60,1,3999.00,'INR',1,1,'2026-07-10 13:03:44'),
(5,4,'Monthly Package','12 sessions across one month.',12,60,1,13999.00,'INR',1,1,'2026-07-10 13:03:44'),
(6,5,'Custom Wellness Plan','Sessions and duration tailored after a consultation.',4,60,1,5999.00,'INR',1,0,'2026-07-10 13:03:44');
/*!40000 ALTER TABLE `packages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_content`
--

DROP TABLE IF EXISTS `page_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_content` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `section_key` varchar(60) NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`content`)),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `section_key` (`section_key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_content`
--

LOCK TABLES `page_content` WRITE;
/*!40000 ALTER TABLE `page_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_pwreset_user` (`user_id`),
  CONSTRAINT `fk_pwreset_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
INSERT INTO `password_resets` VALUES
(1,8,'eb970b7e5c75a27f4fa7c41291774710f82dc9e3a197aa97ab459ea16324202f','2026-07-11 10:04:17','2026-07-11 09:34:37','2026-07-11 09:34:17'),
(2,8,'e83d6f794b867ba96e128bd4fa92c65eb2bc0a320f7a8eda3ac1d725fcdf7220','2026-07-11 10:04:54','2026-07-11 09:34:54','2026-07-11 09:34:54'),
(3,10,'e62777f5ad2746db057059200284683f07f9432d8706529a4197b09b7c4df71e','2026-07-11 10:35:59','2026-07-11 10:06:00','2026-07-11 10:05:59'),
(5,6,'9faa0f96e0c646faae4ad27f77dcffb9982b1f9dc74815c6a0e445203fa7d023','2026-07-11 21:54:26',NULL,'2026-07-11 21:24:26'),
(6,22,'4bc191c4950de0ab63d31715433ca085560ed5d25808ca44a1a0ec80b676a96b','2026-07-13 20:21:35',NULL,'2026-07-13 19:51:35');
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `gateway_order_id` varchar(150) DEFAULT NULL,
  `gateway` enum('razorpay','stripe','paypal','wallet','upi','qr','other') NOT NULL DEFAULT 'upi',
  `gateway_txn_id` varchar(150) DEFAULT NULL,
  `method` varchar(60) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` char(3) NOT NULL DEFAULT 'INR',
  `status` enum('pending','success','failed','refunded') NOT NULL DEFAULT 'pending',
  `raw_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw_response`)),
  `screenshot_path` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_payment_booking` (`booking_id`),
  CONSTRAINT `fk_payment_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES
(3,8,'order_test_8','razorpay','pay_test_8',NULL,1573.95,'INR','success',NULL,'2026-07-11 22:29:39'),
(4,9,'order_test_9','razorpay','pay_test_9',NULL,1573.95,'INR','success',NULL,'2026-07-11 22:32:16'),
(11,17,'mock_d281321b2b117640','other','mock_txn_d281321b2b117640',NULL,1573.95,'INR','success',NULL,'2026-07-13 19:40:10'),
(13,20,'mock_e3856b04e3156c01','other','mock_txn_e3856b04e3156c01',NULL,1573.95,'INR','success',NULL,'2026-07-14 17:37:22'),
(14,18,'mock_1b06e28e04274c7a','other','mock_txn_1b06e28e04274c7a',NULL,1573.95,'INR','success',NULL,'2026-07-18 16:41:50');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(60) NOT NULL,
  `name` varchar(150) NOT NULL,
  `category` varchar(60) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES
(1,'manage_trainers','Manage trainers','admin','2026-07-11 21:25:44'),
(2,'manage_clients','Manage clients','admin','2026-07-11 21:25:44'),
(3,'book_session','Book a session','client','2026-07-11 21:25:44'),
(4,'manage_packages','Manage packages','admin','2026-07-11 21:25:44'),
(5,'manage_calendar','Manage calendar','scheduling','2026-07-11 21:25:44'),
(6,'manage_own_availability','Manage own availability','scheduling','2026-07-11 21:25:44'),
(7,'accept_booking','Accept or reject bookings','scheduling','2026-07-11 21:25:44'),
(8,'join_live_class','Join live class','video','2026-07-11 21:25:44'),
(9,'conduct_live_class','Conduct live class','video','2026-07-11 21:25:44'),
(10,'view_reports','View reports','reporting','2026-07-11 21:25:44'),
(11,'view_revenue','View revenue','reporting','2026-07-11 21:25:44'),
(12,'manage_payment_settings','Manage payment gateway settings','admin','2026-07-11 21:25:44'),
(13,'manage_website_settings','Manage website settings','admin','2026-07-11 21:25:44'),
(14,'view_activity_logs','View activity logs','admin','2026-07-11 21:25:44'),
(15,'manage_gallery','Manage gallery images','content','2026-07-11 21:25:44');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile_edit_history`
--

DROP TABLE IF EXISTS `profile_edit_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `profile_edit_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `field_name` varchar(100) NOT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `changed_by_user_id` bigint(20) unsigned NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `browser` varchar(60) DEFAULT NULL,
  `device` varchar(30) DEFAULT NULL,
  `timezone` varchar(60) DEFAULT NULL,
  `note` varchar(500) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_profileedithistory_changedby` (`changed_by_user_id`),
  KEY `idx_profileedithistory_user` (`user_id`,`version`),
  CONSTRAINT `fk_profileedithistory_changedby` FOREIGN KEY (`changed_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_profileedithistory_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile_edit_history`
--

LOCK TABLES `profile_edit_history` WRITE;
/*!40000 ALTER TABLE `profile_edit_history` DISABLE KEYS */;
INSERT INTO `profile_edit_history` VALUES
(1,21,'date_of_birth','','2026-07-16',21,1,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-16 16:12:25'),
(2,21,'height_cm','','223',21,2,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-16 16:12:25'),
(3,21,'weight_kg','','22',21,3,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-16 16:12:25'),
(4,21,'gender','','male',21,4,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-16 16:12:25'),
(5,21,'city','','coimbatore',21,5,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-16 16:12:25'),
(6,21,'timezone','','Asia/Calcutta',21,6,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-16 16:12:25'),
(7,21,'emergency_contact_name','','242424234',21,7,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-16 16:12:25'),
(8,21,'emergency_contact_phone','','12345678',21,8,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-16 16:12:25'),
(9,21,'emergency_contact_relationship','','Sibling',21,9,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-16 16:12:25'),
(10,21,'client_category','','child',21,10,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-16 16:12:25'),
(11,21,'health_assessment.medicalHistory','','{\"conditions\":[\"vertigo\"]}',21,11,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-16 16:12:51'),
(12,10,'health_assessment.medicalHistory','','{\"conditions\":[\"diabetes\"]}',10,1,'Chrome','Desktop','UTC',NULL,'2026-07-16 16:53:07'),
(13,10,'health_assessment.medicalHistory','','{\"conditions\":[\"none_of_these\"]}',10,2,'Chrome','Desktop','UTC',NULL,'2026-07-16 16:56:33'),
(14,21,'health_assessment.medicalHistory','{\"conditions\":[\"vertigo\"]}','{\"conditions\":[\"vertigo\"],\"details\":{\"vertigo\":{\"since\":\"lt_1y\",\"medication\":true,\"doctorRestriction\":true}}}',21,12,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-16 20:44:42'),
(15,21,'health_assessment.painAssessment','','{\"areas\":[\"no_pain\"]}',21,13,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-16 20:44:56'),
(16,21,'health_assessment.doctorRestrictions','','{\"hasRestriction\":false,\"notes\":\"\"}',21,14,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-16 20:45:00'),
(17,21,'health_assessment.categorySafety','','{\"guardianPresent\":true}',21,15,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-16 20:45:03'),
(18,21,'health_assessment.advancedHealth','','{\"changed\":false}',21,16,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-16 20:45:09'),
(19,21,'health_assessment.medication','','{\"recentChange\":true,\"takingMedication\":false}',21,17,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-16 20:45:18'),
(20,21,'health_assessment.surgeries','','{\"hadSurgery\":false}',21,18,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-16 20:45:29'),
(21,25,'date_of_birth','','2004-09-27',25,1,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 13:30:20'),
(22,25,'height_cm','','175',25,2,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 13:30:20'),
(23,25,'weight_kg','','59',25,3,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 13:30:20'),
(24,25,'gender','','male',25,4,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 13:30:20'),
(25,25,'city','','coimbatore',25,5,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 13:30:20'),
(26,25,'timezone','','Asia/Calcutta',25,6,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 13:30:20'),
(27,25,'client_category','','adult',25,7,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 13:30:20');
/*!40000 ALTER TABLE `profile_edit_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rate_limits`
--

DROP TABLE IF EXISTS `rate_limits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rate_limits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bucket_key` varchar(190) NOT NULL,
  `hits` int(10) unsigned NOT NULL DEFAULT 1,
  `window_started_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_rate_bucket` (`bucket_key`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rate_limits`
--

LOCK TABLES `rate_limits` WRITE;
/*!40000 ALTER TABLE `rate_limits` DISABLE KEYS */;
INSERT INTO `rate_limits` VALUES
(1,'login:::1',4,'2026-07-23 12:56:13'),
(2,'otp:::1',1,'2026-07-10 14:28:05'),
(3,'contact:::1',2,'2026-07-10 16:47:39'),
(4,'password_reset:::1',1,'2026-07-11 21:24:26'),
(5,'login:127.0.0.1',1,'2026-07-12 13:35:51');
/*!40000 ALTER TABLE `rate_limits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `receipts`
--

DROP TABLE IF EXISTS `receipts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `receipts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `invoice_number` varchar(40) NOT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_id` (`booking_id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  CONSTRAINT `fk_receipt_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receipts`
--

LOCK TABLES `receipts` WRITE;
/*!40000 ALTER TABLE `receipts` DISABLE KEYS */;
/*!40000 ALTER TABLE `receipts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permissions` (
  `role_id` bigint(20) unsigned NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `fk_role_permissions_permission` (`permission_id`),
  CONSTRAINT `fk_role_permissions_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_role_permissions_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` VALUES
(1,1),
(1,2),
(3,3),
(1,4),
(1,5),
(2,5),
(2,6),
(2,7),
(1,8),
(2,8),
(3,8),
(2,9),
(1,10),
(2,10),
(1,11),
(2,11),
(1,12),
(1,13),
(1,14),
(1,15),
(2,15);
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(40) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES
(1,'admin','Super Admin','2026-07-11 21:25:44'),
(2,'instructor','Trainer','2026-07-11 21:25:44'),
(3,'client','Client','2026-07-11 21:25:44');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_types`
--

DROP TABLE IF EXISTS `service_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(60) NOT NULL,
  `name` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(60) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_types`
--

LOCK TABLES `service_types` WRITE;
/*!40000 ALTER TABLE `service_types` DISABLE KEYS */;
INSERT INTO `service_types` VALUES
(1,'one-to-one','One to One Session','Private personalized yoga session with a dedicated instructor.','user',1,1),
(2,'group','Group Session','Live group class with other practitioners.','users',1,2),
(3,'weekly','Weekly Package','A structured weekly plan of recurring sessions.','calendar-week',1,3),
(4,'monthly','Monthly Package','A full month of guided practice.','calendar-month',1,4),
(5,'custom','Custom Package','Tailored session count and duration, built around your goals.','sliders',1,5);
/*!40000 ALTER TABLE `service_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `session_feedback`
--

DROP TABLE IF EXISTS `session_feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `session_feedback` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `instructor_id` bigint(20) unsigned NOT NULL,
  `rating` tinyint(3) unsigned DEFAULT NULL,
  `feedback_text` text DEFAULT NULL,
  `session_notes` text DEFAULT NULL,
  `recommendation` text DEFAULT NULL,
  `homework` text DEFAULT NULL,
  `attendance` enum('present','absent') DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_feedback_booking` (`booking_id`),
  KEY `fk_feedback_client` (`client_id`),
  KEY `fk_feedback_instructor` (`instructor_id`),
  CONSTRAINT `fk_feedback_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_feedback_client` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_feedback_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `session_feedback`
--

LOCK TABLES `session_feedback` WRITE;
/*!40000 ALTER TABLE `session_feedback` DISABLE KEYS */;
INSERT INTO `session_feedback` VALUES
(1,8,10,5,NULL,NULL,NULL,'Focus on deeper breathing during forward folds.','Practice cat-cow stretch 10 times daily.',NULL,'2026-07-12 09:51:51');
/*!40000 ALTER TABLE `session_feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `session_resources`
--

DROP TABLE IF EXISTS `session_resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `session_resources` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `instructor_id` bigint(20) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_session_resource_instructor` (`instructor_id`),
  KEY `idx_session_resource_booking` (`booking_id`),
  CONSTRAINT `fk_session_resource_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_session_resource_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `session_resources`
--

LOCK TABLES `session_resources` WRITE;
/*!40000 ALTER TABLE `session_resources` DISABLE KEYS */;
/*!40000 ALTER TABLE `session_resources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES
('default_currency','INR','2026-07-10 13:03:44'),
('default_language','en','2026-07-12 09:29:27'),
('default_timezone','Asia/Kolkata','2026-07-10 13:03:44'),
('google_redirect_uri','','2026-07-12 09:29:27'),
('site_name','Vipasa Yoga','2026-07-10 13:03:44'),
('social_facebook','https://www.facebook.com/share/1KTuGJAxaL/?mibextid=wwXIfr','2026-07-12 09:29:27'),
('social_instagram','https://www.instagram.com/vipasayoga?cplk=emt5emUxdHY2NWVo&utm_source=qr','2026-07-12 09:29:27'),
('social_twitter','','2026-07-12 09:29:27'),
('social_youtube','https://youtube.com/@vipasayoga?si=NeqboKH4S-6jHi4n','2026-07-12 09:29:27'),
('tax_percent','5','2026-07-11 10:22:47');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `email` varchar(190) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `google_id` varchar(64) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `role` enum('client','instructor','admin') NOT NULL DEFAULT 'client',
  `status` enum('active','suspended','pending','test') NOT NULL DEFAULT 'pending',
  `email_verified_at` datetime DEFAULT NULL,
  `phone_verified_at` datetime DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone` (`phone`),
  UNIQUE KEY `google_id` (`google_id`),
  KEY `idx_users_role` (`role`),
  KEY `idx_users_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(998,'Studio Admin','info@vipasayoga.com',NULL,NULL,'$2y$10$i.cWLXLv8pJbxWo22e/31edHaO5xZ.uqLGNw6K43vUdFXcuo9iNL2','admin','active','2026-07-10 16:04:57',NULL,NULL,NULL,'2026-07-10 16:04:57','2026-07-10 16:04:57'),
(999,'Vipasa Admin','admin@vipasayoga.com',NULL,NULL,'$2y$10$i.cWLXLv8pJbxWo22e/31edHaO5xZ.uqLGNw6K43vUdFXcuo9iNL2','admin','active','2026-07-10 16:04:57',NULL,NULL,NULL,'2026-07-10 16:04:57','2026-07-10 16:04:57'),
(1,'Ananya Iyer','ananya.iyer@vipasa.demo','+919810000001',NULL,'$2y$10$jFWFOehUSdjsD0Amd6yxyem2PRINUVBZJAoQf/hEIJ5QfawM3RoEu','instructor','active','2026-07-10 13:03:44','2026-07-10 13:03:44',NULL,'2026-07-16 16:29:36','2026-07-10 13:03:44','2026-07-16 16:29:36'),
(2,'Rahul Menon','rahul.menon@vipasa.demo','+919810000002',NULL,'$2y$10$jFWFOehUSdjsD0Amd6yxyem2PRINUVBZJAoQf/hEIJ5QfawM3RoEu','instructor','active','2026-07-10 13:03:44','2026-07-10 13:03:44',NULL,NULL,'2026-07-10 13:03:44','2026-07-10 13:03:44'),
(3,'Sarah Thomas','sarah.thomas@vipasa.demo','+919810000003',NULL,'$2y$10$jFWFOehUSdjsD0Amd6yxyem2PRINUVBZJAoQf/hEIJ5QfawM3RoEu','instructor','active','2026-07-10 13:03:44','2026-07-10 13:03:44',NULL,NULL,'2026-07-10 13:03:44','2026-07-10 13:03:44'),
(6,'lakshmanan','lakshmananselvaraj7999@gmail.com','7904870129',NULL,'$2y$10$uCQ3MH3bhJCUV05TFCp8/edKeRcqmqo8p5w0zmmbhkYV06srEQdxS','client','test',NULL,NULL,NULL,NULL,'2026-07-10 14:13:34','2026-07-14 14:43:47'),
(8,'Vipasa Admin','admin@vipasa.demo',NULL,NULL,'$2y$10$.0OG14gKCnN/UFW8vDhGE.5nQLMvEhXUFZMSgmoNsnwgrlXi4nr0e','admin','active','2026-07-10 16:04:57',NULL,NULL,'2026-07-16 16:29:45','2026-07-10 16:04:57','2026-07-16 16:29:45'),
(10,'Lakshmanan','lakshmananselvaraj@gmail.com','8883795084',NULL,'$2y$10$G7HVgYYEAVgDgOH0Piia.ezprMvJp1bt/pDr9SsagNoKtMzDtOcY6','client','test',NULL,NULL,NULL,'2026-07-16 16:56:31','2026-07-10 17:02:54','2026-07-16 16:56:31'),
(13,'Vijaya Parameswaran','vijaya.parameswaran@vipasayoga.demo',NULL,NULL,'$2y$10$G7HVgYYEAVgDgOH0Piia.ezprMvJp1bt/pDr9SsagNoKtMzDtOcY6','instructor','active','2026-07-11 19:11:40',NULL,NULL,'2026-07-12 10:39:34','2026-07-11 19:11:40','2026-07-12 10:39:34'),
(14,'Test Client','testclient-1783780106828@example.com',NULL,NULL,'$2y$10$5hBiUpx6P1njBCx.CoBPoOgMpcM6Ty7AlTSuBE7AKv/TZ09VfB4AS','client','test',NULL,NULL,NULL,'2026-07-11 19:58:27','2026-07-11 19:58:27','2026-07-14 14:43:47'),
(15,'Test Client','testclient2-1783780229235@example.com',NULL,NULL,'$2y$10$wQhqhbj2.oCKXf7/JtqyL.Kuewmwlzrz3ZeZ1XtGPbUfl14fTn0Ha','client','test',NULL,NULL,NULL,'2026-07-11 20:00:30','2026-07-11 20:00:30','2026-07-14 14:43:47'),
(16,'Test Client','testclient3-1783780304359@example.com',NULL,NULL,'$2y$10$OxABW5qTnqq1aPBwjlNC1OVyDflE5o5qnMz7/AZtYgpP1zqtHEwLG','client','test',NULL,NULL,NULL,'2026-07-11 20:01:45','2026-07-11 20:01:45','2026-07-14 14:43:47'),
(17,'Test Client','testclient4-1783780337987@example.com',NULL,NULL,'$2y$10$bELAGbc3KhgY/j4h4WswF.bwpxe8Jig8j/./B65ZmtGK5CHTbK2ny','client','test',NULL,NULL,NULL,'2026-07-11 20:02:19','2026-07-11 20:02:19','2026-07-14 14:43:47'),
(18,'Test Client','testclient5-1783780559220@example.com',NULL,NULL,'$2y$10$0gZLB4AdA8.XOK5l8EbI9OW73oBae4UZAhzOPS9aImaiwb4TyQBL2','client','test',NULL,NULL,NULL,'2026-07-11 20:06:02','2026-07-11 20:06:02','2026-07-14 14:43:47'),
(19,'Test Client','testclient6-1783780779694@example.com',NULL,NULL,'$2y$10$sqlMo7M1gXSbdgBjLFbBvuZ0KQXFKctizx.exqm5R33K6mRNULBm6','client','test',NULL,NULL,NULL,'2026-07-11 20:09:43','2026-07-11 20:09:43','2026-07-14 14:43:47'),
(20,'Final Check','finalcheck-1783783922136@example.com',NULL,NULL,'$2y$10$DQAiTS62vCeYi/RVg5Fv1O3eDbEbFIaZGeX9UPD.0RkzXU93/0JWa','client','test',NULL,NULL,NULL,'2026-07-11 21:02:03','2026-07-11 21:02:03','2026-07-14 14:43:47'),
(21,'razi','razi@gmail.com','7025814594',NULL,'$2y$10$0VmVHIHta8Y8EeQk1W9DtuUgFHZBzavlM/P9ot4GbsERjybRj4n7i','client','active',NULL,NULL,NULL,'2026-07-23 12:54:31','2026-07-13 19:13:22','2026-07-23 12:54:31'),
(22,'Lakshu','lakshu@gmail.com',NULL,NULL,NULL,'instructor','test',NULL,NULL,NULL,NULL,'2026-07-13 19:51:35','2026-07-14 14:43:47'),
(25,'Lakshmanan','lakshmanan@gmail.com','7904870122',NULL,'$2y$10$1LZfoL8DmCRV5eDf.YEMQeY6UT6dWQTMdYSA8umVAq4fQVLnk42wa','client','active',NULL,NULL,NULL,'2026-07-23 12:56:56','2026-07-23 12:56:56','2026-07-23 12:56:56');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `video_sessions`
--

DROP TABLE IF EXISTS `video_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `video_sessions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `room_name` varchar(150) NOT NULL,
  `jitsi_url` varchar(255) DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `ended_at` datetime DEFAULT NULL,
  `recording_url` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_videosession_booking` (`booking_id`),
  CONSTRAINT `fk_videosession_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `video_sessions`
--

LOCK TABLES `video_sessions` WRITE;
/*!40000 ALTER TABLE `video_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `video_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallet_transactions`
--

DROP TABLE IF EXISTS `wallet_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallet_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `wallet_id` bigint(20) unsigned NOT NULL,
  `type` enum('credit','debit') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reference` varchar(150) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_wallettxn_wallet` (`wallet_id`),
  CONSTRAINT `fk_wallettxn_wallet` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallet_transactions`
--

LOCK TABLES `wallet_transactions` WRITE;
/*!40000 ALTER TABLE `wallet_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `wallet_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallets`
--

DROP TABLE IF EXISTS `wallets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currency` char(3) NOT NULL DEFAULT 'INR',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_id` (`client_id`),
  CONSTRAINT `fk_wallet_client` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallets`
--

LOCK TABLES `wallets` WRITE;
/*!40000 ALTER TABLE `wallets` DISABLE KEYS */;
/*!40000 ALTER TABLE `wallets` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- note verbosity

-- Dump completed on 2026-10-31 21:43:26
