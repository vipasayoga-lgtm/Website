-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: vipasa_yoga
-- ------------------------------------------------------
-- Server version	8.0.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_activitylog_user` (`user_id`),
  KEY `idx_activitylog_action` (`action`),
  CONSTRAINT `fk_activitylog_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=563 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,6,'account_created','user',6,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-23 14:15:21'),(2,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-23 16:56:16'),(3,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-23 17:54:49'),(4,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-23 17:58:14'),(5,7,'account_created','user',7,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-23 18:32:25'),(6,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-23 18:40:17'),(7,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-23 18:42:24'),(8,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-26 10:56:05'),(9,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-29 19:51:42'),(10,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-29 20:00:32'),(11,1,'package_deactivated','package',3,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-29 20:45:46'),(12,1,'package_activated','package',3,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-29 20:45:55'),(13,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-29 20:46:38'),(14,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-29 20:47:49'),(15,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-29 20:50:28'),(16,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-29 20:53:47'),(17,1,'settings_updated','setting',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','{\"section\": \"website\"}','2026-07-29 20:55:59'),(18,1,'settings_updated','setting',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','{\"section\": \"website\"}','2026-07-29 20:56:07'),(19,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-30 15:21:43'),(20,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-30 15:22:59'),(21,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-30 15:32:12'),(22,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-30 16:48:39'),(23,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 17:00:32'),(24,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 17:01:51'),(25,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 17:02:32'),(26,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 17:02:56'),(27,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 17:03:37'),(28,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 17:03:55'),(29,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 17:05:15'),(30,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-30 17:34:08'),(31,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 17:35:48'),(32,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 17:40:42'),(33,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 17:48:57'),(34,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 17:52:38'),(35,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 17:59:48'),(36,1,'instructor_created','instructor',5,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133','{\"name\": \"Test Trainer\", \"password_set_by_admin\": true}','2026-07-30 17:59:49'),(39,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 18:00:34'),(40,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 18:02:12'),(41,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 18:13:20'),(42,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 18:14:24'),(43,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 18:14:47'),(44,1,'package_deleted','package',2,'127.0.0.1',NULL,'{\"name\": \"Extended Private Session\"}','2026-07-30 18:14:48'),(45,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 18:15:35'),(46,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 18:19:59'),(47,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 18:20:37'),(48,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 18:23:30'),(49,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 18:25:30'),(50,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 18:25:58'),(51,1,'login','user',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.22621.6133',NULL,'2026-07-30 18:26:51'),(52,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-31 15:59:59'),(53,1,'instructor_created','instructor',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','{\"name\": \"lakshu\", \"password_set_by_admin\": true}','2026-07-31 16:00:56'),(54,NULL,'login','user',9,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-31 16:01:30'),(55,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-31 16:16:08'),(56,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-31 16:17:10'),(57,1,'instructor_deactivated','instructor',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-31 16:17:21'),(58,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-31 16:17:30'),(59,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-31 16:18:16'),(60,1,'instructor_activated','instructor',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-31 16:18:25'),(61,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-31 16:19:26'),(62,1,'instructor_updated','instructor',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','{\"name\": \"lakshu\", \"email_updated\": true, \"password_updated\": true}','2026-07-31 16:27:00'),(63,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-07-31 16:28:02'),(64,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-08-06 19:14:13'),(65,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-08-06 19:24:27'),(66,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-08-06 19:29:21'),(67,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-08-06 19:30:01'),(68,1,'instructor_deleted','instructor',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','{\"name\": \"lakshu\"}','2026-08-06 19:30:21'),(69,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:40:46'),(70,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:41:55'),(71,10,'account_created','user',10,'::1',NULL,NULL,'2026-08-06 19:42:58'),(72,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:42:58'),(73,11,'account_created','user',11,'::1',NULL,NULL,'2026-08-06 19:43:53'),(74,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:43:54'),(75,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:43:54'),(76,12,'account_created','user',12,'::1',NULL,NULL,'2026-08-06 19:44:20'),(77,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:44:20'),(78,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:44:20'),(79,13,'account_created','user',13,'::1',NULL,NULL,'2026-08-06 19:44:25'),(80,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:44:25'),(81,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:44:26'),(82,14,'account_created','user',14,'::1',NULL,NULL,'2026-08-06 19:44:31'),(83,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:44:31'),(84,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:44:31'),(85,15,'account_created','user',15,'::1',NULL,NULL,'2026-08-06 19:44:36'),(86,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:44:37'),(87,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:44:37'),(88,16,'account_created','user',16,'::1',NULL,NULL,'2026-08-06 19:44:42'),(89,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:44:42'),(90,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:44:42'),(91,1,'instructor_created','instructor',7,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','{\"name\": \"Lakshu\", \"password_set_by_admin\": true}','2026-08-06 19:46:50'),(92,17,'login','user',17,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-08-06 19:47:22'),(93,17,'trainer_profile_updated','instructor',7,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-08-06 19:48:05'),(94,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:50:59'),(95,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:51:10'),(96,18,'account_created','user',18,'::1',NULL,NULL,'2026-08-06 19:51:18'),(97,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:51:18'),(98,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:51:18'),(99,19,'account_created','user',19,'::1',NULL,NULL,'2026-08-06 19:51:23'),(100,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:51:23'),(101,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:51:24'),(102,20,'account_created','user',20,'::1',NULL,NULL,'2026-08-06 19:51:29'),(103,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:51:29'),(104,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:51:29'),(105,21,'account_created','user',21,'::1',NULL,NULL,'2026-08-06 19:51:35'),(106,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:51:35'),(107,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:51:35'),(108,22,'account_created','user',22,'::1',NULL,NULL,'2026-08-06 19:51:41'),(109,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:51:41'),(110,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:51:41'),(111,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:54:02'),(112,23,'account_created','user',23,'::1',NULL,NULL,'2026-08-06 19:54:10'),(113,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:54:11'),(114,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:54:11'),(115,24,'account_created','user',24,'::1',NULL,NULL,'2026-08-06 19:54:16'),(116,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:54:16'),(117,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:54:16'),(118,25,'account_created','user',25,'::1',NULL,NULL,'2026-08-06 19:54:21'),(119,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:54:22'),(120,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:54:22'),(121,26,'account_created','user',26,'::1',NULL,NULL,'2026-08-06 19:54:27'),(122,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:54:27'),(123,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:54:28'),(124,27,'account_created','user',27,'::1',NULL,NULL,'2026-08-06 19:54:33'),(125,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:54:33'),(126,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:54:33'),(127,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:58:43'),(128,28,'account_created','user',28,'::1',NULL,NULL,'2026-08-06 19:58:54'),(129,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:58:54'),(130,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:58:54'),(131,29,'account_created','user',29,'::1',NULL,NULL,'2026-08-06 19:58:59'),(132,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:58:59'),(133,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:59:00'),(134,30,'account_created','user',30,'::1',NULL,NULL,'2026-08-06 19:59:05'),(135,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:59:05'),(136,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:59:05'),(137,31,'account_created','user',31,'::1',NULL,NULL,'2026-08-06 19:59:10'),(138,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:59:11'),(139,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:59:11'),(140,32,'account_created','user',32,'::1',NULL,NULL,'2026-08-06 19:59:16'),(141,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 19:59:16'),(142,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 19:59:17'),(143,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:01:45'),(144,33,'account_created','user',33,'::1',NULL,NULL,'2026-08-06 20:01:56'),(145,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:01:56'),(146,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:01:56'),(147,34,'account_created','user',34,'::1',NULL,NULL,'2026-08-06 20:02:01'),(148,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:02:01'),(149,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:02:02'),(150,35,'account_created','user',35,'::1',NULL,NULL,'2026-08-06 20:02:07'),(151,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:02:07'),(152,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:02:07'),(153,36,'account_created','user',36,'::1',NULL,NULL,'2026-08-06 20:02:12'),(154,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:02:13'),(155,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:02:13'),(156,37,'account_created','user',37,'::1',NULL,NULL,'2026-08-06 20:02:18'),(157,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:02:18'),(158,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:02:19'),(159,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-08-06 20:07:38'),(160,38,'account_created','user',38,'::1',NULL,NULL,'2026-08-06 20:12:38'),(161,39,'account_created','user',39,'::1',NULL,NULL,'2026-08-06 20:14:42'),(162,40,'account_created','user',40,'::1',NULL,NULL,'2026-08-06 20:26:22'),(163,41,'account_created','user',41,'::1',NULL,NULL,'2026-08-06 20:26:32'),(164,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:26:32'),(165,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:26:33'),(166,42,'account_created','user',42,'::1',NULL,NULL,'2026-08-06 20:26:38'),(167,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:26:38'),(168,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:26:38'),(169,43,'account_created','user',43,'::1',NULL,NULL,'2026-08-06 20:26:43'),(170,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:26:44'),(171,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:26:44'),(172,44,'account_created','user',44,'::1',NULL,NULL,'2026-08-06 20:26:49'),(173,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:26:49'),(174,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:26:50'),(175,45,'account_created','user',45,'::1',NULL,NULL,'2026-08-06 20:26:55'),(176,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:26:55'),(177,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:26:55'),(178,46,'account_created','user',46,'::1',NULL,NULL,'2026-08-06 20:37:15'),(179,47,'account_created','user',47,'::1',NULL,NULL,'2026-08-06 20:40:04'),(180,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:40:04'),(181,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:40:04'),(182,48,'account_created','user',48,'::1',NULL,NULL,'2026-08-06 20:40:09'),(183,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:40:09'),(184,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:40:10'),(185,49,'account_created','user',49,'::1',NULL,NULL,'2026-08-06 20:40:15'),(186,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:40:15'),(187,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:40:15'),(188,50,'account_created','user',50,'::1',NULL,NULL,'2026-08-06 20:40:20'),(189,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:40:21'),(190,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:40:21'),(191,51,'account_created','user',51,'::1',NULL,NULL,'2026-08-06 20:40:26'),(192,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:40:26'),(193,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:40:26'),(194,52,'account_created','user',52,'::1',NULL,NULL,'2026-08-06 20:50:02'),(195,53,'account_created','user',53,'::1',NULL,NULL,'2026-08-06 20:50:10'),(196,54,'account_created','user',54,'::1',NULL,NULL,'2026-08-06 20:51:21'),(197,55,'account_created','user',55,'::1',NULL,NULL,'2026-08-06 20:51:33'),(198,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:51:33'),(199,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:51:33'),(200,56,'account_created','user',56,'::1',NULL,NULL,'2026-08-06 20:51:38'),(201,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:51:39'),(202,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:51:39'),(203,57,'account_created','user',57,'::1',NULL,NULL,'2026-08-06 20:51:44'),(204,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:51:44'),(205,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:51:44'),(206,58,'account_created','user',58,'::1',NULL,NULL,'2026-08-06 20:51:50'),(207,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:51:50'),(208,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:51:50'),(209,59,'account_created','user',59,'::1',NULL,NULL,'2026-08-06 20:51:55'),(210,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:51:55'),(211,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:51:55'),(212,60,'account_created','user',60,'::1',NULL,NULL,'2026-08-06 20:59:02'),(213,61,'account_created','user',61,'::1',NULL,NULL,'2026-08-06 20:59:16'),(214,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:59:16'),(215,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:59:16'),(216,62,'account_created','user',62,'::1',NULL,NULL,'2026-08-06 20:59:21'),(217,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:59:22'),(218,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:59:22'),(219,63,'account_created','user',63,'::1',NULL,NULL,'2026-08-06 20:59:27'),(220,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:59:27'),(221,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:59:28'),(222,64,'account_created','user',64,'::1',NULL,NULL,'2026-08-06 20:59:33'),(223,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:59:33'),(224,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:59:33'),(225,65,'account_created','user',65,'::1',NULL,NULL,'2026-08-06 20:59:38'),(226,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 20:59:39'),(227,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 20:59:39'),(228,66,'account_created','user',66,'::1',NULL,NULL,'2026-08-06 21:08:52'),(229,67,'account_created','user',67,'::1',NULL,NULL,'2026-08-06 21:09:40'),(230,67,'health_assessment_stage_completed','user',67,'::1',NULL,'{\"stage\": 2}','2026-08-06 21:09:40'),(231,68,'account_created','user',68,'::1',NULL,NULL,'2026-08-06 21:09:47'),(232,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:09:47'),(233,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:09:47'),(234,69,'account_created','user',69,'::1',NULL,NULL,'2026-08-06 21:09:52'),(235,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:09:52'),(236,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:09:53'),(237,70,'account_created','user',70,'::1',NULL,NULL,'2026-08-06 21:09:57'),(238,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:09:58'),(239,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:09:58'),(240,71,'account_created','user',71,'::1',NULL,NULL,'2026-08-06 21:10:03'),(241,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:10:03'),(242,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:10:03'),(243,72,'account_created','user',72,'::1',NULL,NULL,'2026-08-06 21:10:08'),(244,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:10:08'),(245,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:10:08'),(246,73,'account_created','user',73,'::1',NULL,NULL,'2026-08-06 21:13:58'),(247,74,'account_created','user',74,'::1',NULL,NULL,'2026-08-06 21:14:20'),(248,74,'health_assessment_stage_completed','user',74,'::1',NULL,'{\"stage\": 2}','2026-08-06 21:14:20'),(249,75,'account_created','user',75,'::1',NULL,NULL,'2026-08-06 21:14:31'),(250,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:14:31'),(251,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:14:31'),(252,76,'account_created','user',76,'::1',NULL,NULL,'2026-08-06 21:14:36'),(253,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:14:36'),(254,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:14:36'),(255,77,'account_created','user',77,'::1',NULL,NULL,'2026-08-06 21:14:41'),(256,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:14:41'),(257,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:14:42'),(258,78,'account_created','user',78,'::1',NULL,NULL,'2026-08-06 21:14:46'),(259,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:14:47'),(260,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:14:47'),(261,79,'account_created','user',79,'::1',NULL,NULL,'2026-08-06 21:14:52'),(262,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:14:52'),(263,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:14:52'),(264,6,'health_assessment_stage_completed','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','{\"stage\": 2}','2026-08-06 21:17:26'),(265,80,'account_created','user',80,'::1',NULL,NULL,'2026-08-06 21:22:03'),(266,80,'health_assessment_stage_completed','user',80,'::1',NULL,'{\"stage\": 2}','2026-08-06 21:22:03'),(267,81,'account_created','user',81,'::1',NULL,NULL,'2026-08-06 21:22:20'),(268,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:22:20'),(269,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:22:21'),(270,82,'account_created','user',82,'::1',NULL,NULL,'2026-08-06 21:22:25'),(271,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:22:25'),(272,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:22:26'),(273,83,'account_created','user',83,'::1',NULL,NULL,'2026-08-06 21:22:30'),(274,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:22:31'),(275,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:22:31'),(276,84,'account_created','user',84,'::1',NULL,NULL,'2026-08-06 21:22:36'),(277,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:22:36'),(278,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:22:37'),(279,85,'account_created','user',85,'::1',NULL,NULL,'2026-08-06 21:22:42'),(280,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:22:42'),(281,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:22:42'),(282,6,'booking_created','booking',5,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','{\"booking_ref\": \"VYNRK4RADN\"}','2026-08-06 21:23:52'),(283,6,'payment_success','booking',5,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','{\"amount\": \"1573.95\", \"booking_ref\": \"VYNRK4RADN\"}','2026-08-06 21:23:55'),(284,6,'health_assessment_stage_completed','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','{\"stage\": 4}','2026-08-06 21:25:05'),(285,6,'booking_created','booking',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','{\"booking_ref\": \"VY14IIJ383\"}','2026-08-06 21:27:13'),(286,6,'payment_success','booking',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','{\"amount\": \"4198.95\", \"booking_ref\": \"VY14IIJ383\"}','2026-08-06 21:27:44'),(287,86,'account_created','user',86,'::1',NULL,NULL,'2026-08-06 21:31:56'),(288,86,'health_assessment_stage_completed','user',86,'::1',NULL,'{\"stage\": 2}','2026-08-06 21:31:56'),(289,87,'account_created','user',87,'::1',NULL,NULL,'2026-08-06 21:32:04'),(290,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:32:04'),(291,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:32:04'),(292,88,'account_created','user',88,'::1',NULL,NULL,'2026-08-06 21:32:09'),(293,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:32:09'),(294,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:32:10'),(295,89,'account_created','user',89,'::1',NULL,NULL,'2026-08-06 21:32:15'),(296,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:32:15'),(297,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:32:15'),(298,90,'account_created','user',90,'::1',NULL,NULL,'2026-08-06 21:32:21'),(299,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:32:21'),(300,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:32:21'),(301,91,'account_created','user',91,'::1',NULL,NULL,'2026-08-06 21:32:26'),(302,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:32:26'),(303,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:32:26'),(304,92,'account_created','user',92,'::1',NULL,NULL,'2026-08-06 21:35:40'),(305,92,'health_assessment_stage_completed','user',92,'::1',NULL,'{\"stage\": 2}','2026-08-06 21:35:41'),(306,93,'account_created','user',93,'::1',NULL,NULL,'2026-08-06 21:35:47'),(307,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:35:47'),(308,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:35:47'),(309,94,'account_created','user',94,'::1',NULL,NULL,'2026-08-06 21:35:52'),(310,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:35:52'),(311,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:35:53'),(312,95,'account_created','user',95,'::1',NULL,NULL,'2026-08-06 21:35:57'),(313,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:35:58'),(314,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:35:58'),(315,96,'account_created','user',96,'::1',NULL,NULL,'2026-08-06 21:36:03'),(316,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:36:03'),(317,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:36:04'),(318,97,'account_created','user',97,'::1',NULL,NULL,'2026-08-06 21:36:08'),(319,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:36:09'),(320,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:36:09'),(321,98,'account_created','user',98,'::1',NULL,NULL,'2026-08-06 21:42:48'),(322,98,'health_assessment_stage_completed','user',98,'::1',NULL,'{\"stage\": 2}','2026-08-06 21:42:49'),(323,99,'account_created','user',99,'::1',NULL,NULL,'2026-08-06 21:42:59'),(324,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:43:00'),(325,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:43:00'),(326,100,'account_created','user',100,'::1',NULL,NULL,'2026-08-06 21:43:05'),(327,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:43:05'),(328,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:43:06'),(329,101,'account_created','user',101,'::1',NULL,NULL,'2026-08-06 21:43:10'),(330,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:43:11'),(331,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:43:11'),(332,102,'account_created','user',102,'::1',NULL,NULL,'2026-08-06 21:43:16'),(333,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:43:16'),(334,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:43:16'),(335,103,'account_created','user',103,'::1',NULL,NULL,'2026-08-06 21:43:22'),(336,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:43:22'),(337,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:43:22'),(338,104,'account_created','user',104,'::1',NULL,NULL,'2026-08-06 21:49:45'),(339,104,'health_assessment_stage_completed','user',104,'::1',NULL,'{\"stage\": 2}','2026-08-06 21:49:46'),(340,NULL,'account_created','user',105,'::1',NULL,NULL,'2026-08-06 21:49:55'),(341,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:49:55'),(342,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:49:55'),(343,NULL,'account_created','user',106,'::1',NULL,NULL,'2026-08-06 21:50:01'),(344,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:50:01'),(345,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:50:01'),(346,107,'account_created','user',107,'::1',NULL,NULL,'2026-08-06 21:50:06'),(347,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:50:06'),(348,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:50:07'),(349,108,'account_created','user',108,'::1',NULL,NULL,'2026-08-06 21:50:12'),(350,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:50:12'),(351,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:50:12'),(352,109,'account_created','user',109,'::1',NULL,NULL,'2026-08-06 21:50:17'),(353,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:50:17'),(354,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:50:18'),(355,110,'account_created','user',110,'::1',NULL,NULL,'2026-08-06 21:55:00'),(356,110,'health_assessment_stage_completed','user',110,'::1',NULL,'{\"stage\": 2}','2026-08-06 21:55:00'),(357,111,'account_created','user',111,'::1',NULL,NULL,'2026-08-06 21:55:10'),(358,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:55:10'),(359,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:55:10'),(360,112,'account_created','user',112,'::1',NULL,NULL,'2026-08-06 21:55:15'),(361,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:55:16'),(362,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:55:16'),(363,113,'account_created','user',113,'::1',NULL,NULL,'2026-08-06 21:55:21'),(364,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:55:21'),(365,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:55:21'),(366,114,'account_created','user',114,'::1',NULL,NULL,'2026-08-06 21:55:27'),(367,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:55:27'),(368,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:55:28'),(369,115,'account_created','user',115,'::1',NULL,NULL,'2026-08-06 21:55:33'),(370,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 21:55:33'),(371,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 21:55:33'),(372,116,'account_created','user',116,'::1',NULL,NULL,'2026-08-06 22:01:50'),(373,116,'health_assessment_stage_completed','user',116,'::1',NULL,'{\"stage\": 2}','2026-08-06 22:01:50'),(374,117,'account_created','user',117,'::1',NULL,NULL,'2026-08-06 22:01:59'),(375,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 22:02:00'),(376,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 22:02:00'),(377,118,'account_created','user',118,'::1',NULL,NULL,'2026-08-06 22:02:05'),(378,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 22:02:05'),(379,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 22:02:06'),(380,119,'account_created','user',119,'::1',NULL,NULL,'2026-08-06 22:02:10'),(381,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 22:02:11'),(382,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 22:02:11'),(383,120,'account_created','user',120,'::1',NULL,NULL,'2026-08-06 22:02:16'),(384,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 22:02:16'),(385,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 22:02:17'),(386,121,'account_created','user',121,'::1',NULL,NULL,'2026-08-06 22:02:22'),(387,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 22:02:22'),(388,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 22:02:22'),(389,NULL,'account_created','user',122,'::1',NULL,NULL,'2026-08-06 22:04:11'),(390,NULL,'health_assessment_stage_completed','user',122,'::1',NULL,'{\"stage\": 2}','2026-08-06 22:04:12'),(391,NULL,'account_created','user',123,'::1',NULL,NULL,'2026-08-06 22:04:21'),(392,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 22:04:21'),(393,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 22:04:22'),(394,124,'account_created','user',124,'::1',NULL,NULL,'2026-08-06 22:04:27'),(395,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 22:04:28'),(396,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 22:04:28'),(397,125,'account_created','user',125,'::1',NULL,NULL,'2026-08-06 22:04:33'),(398,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 22:04:34'),(399,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 22:04:34'),(400,126,'account_created','user',126,'::1',NULL,NULL,'2026-08-06 22:04:41'),(401,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 22:04:41'),(402,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 22:04:41'),(403,NULL,'account_created','user',127,'::1',NULL,NULL,'2026-08-06 22:04:47'),(404,1,'login','user',1,'::1',NULL,NULL,'2026-08-06 22:04:47'),(405,5,'login','user',5,'::1',NULL,NULL,'2026-08-06 22:04:48'),(406,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-08-06 22:05:00'),(407,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-08-06 22:05:40'),(408,1,'meeting_link_attached','booking',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','{\"provider\": \"google_meet\"}','2026-08-06 22:06:41'),(409,17,'login','user',17,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-08-06 22:07:24'),(410,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-08-06 22:07:52'),(411,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 10:36:19'),(412,6,'booking_cancelled','booking',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"booking_ref\": \"VY14IIJ383\"}','2026-08-08 10:36:48'),(413,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 10:46:48'),(414,1,'settings_updated','setting',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"section\": \"website\"}','2026-08-08 10:48:24'),(415,1,'settings_updated','setting',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"section\": \"website\"}','2026-08-08 10:48:28'),(416,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 10:49:39'),(417,6,'consent_accepted','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 10:51:27'),(418,6,'health_assessment_stage_completed','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"stage\": 5}','2026-08-08 10:51:27'),(419,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 11:09:37'),(420,1,'instructor_updated','instructor',7,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"name\": \"Lakshu\", \"email_updated\": true, \"password_updated\": true}','2026-08-08 11:10:08'),(421,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 11:10:21'),(422,17,'login','user',17,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 11:10:47'),(423,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 11:11:14'),(424,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 11:11:40'),(425,1,'instructor_updated','instructor',4,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"name\": \"Vijaya Parameswaran\", \"email_updated\": true, \"password_updated\": true}','2026-08-08 11:17:09'),(426,5,'login','user',5,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 11:17:26'),(427,5,'booking_accepted','booking',5,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"booking_ref\": \"VYNRK4RADN\"}','2026-08-08 11:17:51'),(428,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 12:32:48'),(429,6,'booking_created','booking',7,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"booking_ref\": \"VY4KYEL7RJ\"}','2026-08-08 12:33:10'),(430,6,'payment_success','booking',7,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"amount\": \"4198.95\", \"booking_ref\": \"VY4KYEL7RJ\"}','2026-08-08 12:33:13'),(431,5,'login','user',5,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 12:39:16'),(432,128,'account_created','user',128,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 12:53:05'),(433,5,'login','user',5,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 13:43:56'),(434,5,'booking_accepted','booking',7,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"booking_ref\": \"VY4KYEL7RJ\"}','2026-08-08 13:44:10'),(435,5,'meeting_link_saved','booking',7,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"url\": \"https://meet.google.com/ank-gvgk-spw\", \"provider\": \"google_meet\"}','2026-08-08 14:20:01'),(436,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 14:20:17'),(437,6,'consent_accepted','booking',7,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 14:22:12'),(438,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 19:24:35'),(439,5,'login','user',5,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 19:26:11'),(440,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 19:40:49'),(441,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 20:05:16'),(442,1,'meeting_link_attached','booking',7,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"provider\": \"google_meet\"}','2026-08-08 21:28:38'),(443,1,'customer_deleted','user',127,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"name\": \"QA Automated Client\", \"email\": \"qa_client_1786034087@vipasa.demo\"}','2026-08-08 21:30:32'),(444,1,'customer_deleted','user',123,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"name\": \"QA Automated Client\", \"email\": \"qa_client_1786034061@vipasa.demo\"}','2026-08-08 21:39:50'),(445,1,'customer_deleted','user',122,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"name\": \"QA Pregnant Client\", \"email\": \"qa_pregnant_dyn_1786034051@vipasa.demo\"}','2026-08-08 21:39:54'),(446,1,'customer_deleted','user',106,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"name\": \"QA Automated Client\", \"email\": \"qa_client_1786033200@vipasa.demo\"}','2026-08-08 21:40:09'),(447,1,'customer_deleted','user',105,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"name\": \"QA Automated Client\", \"email\": \"qa_client_1786033195@vipasa.demo\"}','2026-08-08 21:40:13'),(448,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 22:06:41'),(449,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 22:09:09'),(450,1,'gallery_image_uploaded','gallery_image',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 22:18:27'),(451,1,'gallery_image_deleted','gallery_image',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-08 22:18:35'),(452,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-09 18:54:51'),(453,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-09 18:55:55'),(454,6,'password_reset','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-09 18:59:38'),(455,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-09 18:59:46'),(456,6,'instructor_rated','booking',7,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"rating\": 4}','2026-08-09 19:02:53'),(457,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-09 19:03:26'),(458,1,'review_approval_toggled','instructor_review',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"is_approved\": true}','2026-08-09 19:03:40'),(459,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-09 19:05:38'),(460,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-09 19:15:03'),(461,1,'review_updated','instructor_review',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"rating\": 4, \"is_approved\": 1, \"review_text\": \"good\"}','2026-08-09 19:20:10'),(462,17,'login','user',17,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-09 19:26:26'),(463,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-16 17:45:44'),(464,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-16 18:00:24'),(465,129,'account_created','user',129,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-16 18:11:26'),(466,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-16 18:16:42'),(467,6,'booking_created','booking',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"booking_ref\": \"VY0BYBB6M7\"}','2026-08-16 18:17:06'),(468,6,'payment_success','booking',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"amount\": \"1573.95\", \"booking_ref\": \"VY0BYBB6M7\"}','2026-08-16 18:17:10'),(469,5,'login','user',5,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-16 18:17:46'),(470,5,'booking_accepted','booking',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"booking_ref\": \"VY0BYBB6M7\"}','2026-08-16 18:18:05'),(471,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-16 18:18:53'),(472,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-16 18:19:34'),(473,1,'instructor_created','instructor',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"name\": \"nithish\", \"password_set_by_admin\": true}','2026-08-16 18:23:38'),(474,NULL,'login','user',130,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-16 18:23:55'),(475,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-16 18:25:56'),(476,1,'review_updated','instructor_review',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"rating\": 5, \"is_approved\": 1, \"review_text\": \"very good\"}','2026-08-16 18:26:24'),(477,NULL,'login','user',130,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-18 21:18:37'),(478,129,'login','user',129,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-20 21:00:23'),(479,131,'account_created','user',131,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-20 21:08:05'),(480,131,'health_assessment_stage_completed','user',131,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"stage\": 2}','2026-08-20 21:28:41'),(481,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-20 21:35:48'),(482,129,'login','user',129,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-20 21:45:45'),(483,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-22 19:17:02'),(484,129,'login','user',129,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-22 20:52:51'),(485,129,'health_assessment_stage_completed','user',129,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"stage\": 2}','2026-08-22 20:56:04'),(486,6,'booking_created','booking',9,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"booking_ref\": \"VYDGTGRZ9D\"}','2026-08-22 20:59:37'),(487,129,'login','user',129,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-22 21:13:57'),(488,129,'login','user',129,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-22 21:18:22'),(489,132,'login_google_demo','user',132,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-22 21:23:15'),(490,132,'login_google_demo','user',132,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-22 21:24:12'),(491,132,'login_google_demo','user',132,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-22 22:16:41'),(492,132,'login_google_demo','user',132,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-22 22:17:22'),(493,132,'login_google_demo','user',132,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-22 22:32:21'),(494,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-23 10:47:34'),(495,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-23 10:48:28'),(496,1,'review_approval_toggled','instructor_review',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"is_approved\": false}','2026-08-23 11:02:22'),(497,1,'notification_deleted','notification',52,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-23 11:19:01'),(498,1,'notification_deleted','notification',46,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-23 11:19:10'),(499,1,'notification_deleted','notification',39,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-23 11:19:10'),(500,1,'notification_deleted','notification',30,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-23 11:19:11'),(501,1,'settings_updated','setting',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"section\": \"website\"}','2026-08-23 11:20:31'),(502,1,'settings_updated','setting',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"section\": \"website\"}','2026-08-23 11:20:38'),(503,1,'instructor_deleted','instructor',8,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"name\": \"nithish\"}','2026-08-23 12:10:55'),(504,1,'instructor_created','instructor',9,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"name\": \"Nithin\", \"password_set_by_admin\": true}','2026-08-23 12:12:06'),(505,133,'login','user',133,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-23 12:12:18'),(506,133,'availability_updated','instructor',9,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-23 12:12:43'),(507,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-23 12:23:05'),(508,6,'booking_created','booking',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"booking_ref\": \"VY01Q984Y0\"}','2026-08-23 12:23:24'),(509,6,'payment_success','booking',10,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','{\"amount\": \"14698.95\", \"booking_ref\": \"VY01Q984Y0\"}','2026-08-23 12:23:27'),(510,133,'login','user',133,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-23 12:23:59'),(511,5,'login','user',5,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-23 12:29:42'),(512,5,'trainer_profile_updated','instructor',4,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-23 12:30:43'),(513,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-23 12:30:59'),(514,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-23 12:45:49'),(515,1,'instructor_activated','instructor',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-23 12:47:25'),(516,1,'instructor_activated','instructor',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-23 12:47:35'),(517,6,'login','user',6,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-23 12:49:31'),(518,133,'login','user',133,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-23 12:51:17'),(519,133,'login','user',133,'::1','Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36',NULL,'2026-08-26 20:41:07'),(520,129,'login','user',129,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-26 20:58:22'),(521,129,'login','user',129,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36',NULL,'2026-08-26 21:43:12'),(522,133,'login','user',133,'::1','Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36',NULL,'2026-09-05 19:06:22'),(523,133,'login','user',133,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36',NULL,'2026-09-20 11:24:16'),(524,129,'login','user',129,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36',NULL,'2026-09-20 13:02:10'),(525,129,'booking_created','booking',11,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36','{\"booking_ref\": \"VYTIRM64YH\"}','2026-09-20 13:07:12'),(526,129,'health_assessment_stage_completed','user',129,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36','{\"stage\": 4}','2026-09-20 13:14:31'),(527,129,'booking_created','booking',12,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36','{\"booking_ref\": \"VY6PIECV6R\"}','2026-09-20 13:14:40'),(528,129,'booking_created','booking',13,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36','{\"booking_ref\": \"VYLWNX7D0K\"}','2026-09-20 13:22:32'),(529,129,'payment_success','booking',13,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36','{\"amount\": \"1573.95\", \"booking_ref\": \"VYLWNX7D0K\"}','2026-09-20 13:36:46'),(530,133,'login','user',133,'::1','Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Mobile Safari/537.36',NULL,'2026-09-20 13:37:08'),(531,129,'booking_created','booking',14,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36','{\"booking_ref\": \"VYD8NT51KL\"}','2026-09-20 13:37:32'),(532,1,'login','user',1,'::1','Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Mobile Safari/537.36',NULL,'2026-09-20 14:11:25'),(533,1,'notification_deleted','notification',59,'::1','Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Mobile Safari/537.36',NULL,'2026-09-20 14:55:07'),(534,1,'notification_deleted','notification',58,'::1','Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Mobile Safari/537.36',NULL,'2026-09-20 14:55:07'),(535,1,'notification_deleted','notification',57,'::1','Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Mobile Safari/537.36',NULL,'2026-09-20 14:55:07'),(536,1,'notification_deleted','notification',56,'::1','Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Mobile Safari/537.36',NULL,'2026-09-20 14:55:07'),(537,1,'notification_deleted','notification',55,'::1','Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Mobile Safari/537.36',NULL,'2026-09-20 14:55:08'),(538,1,'notification_deleted','notification',54,'::1','Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Mobile Safari/537.36',NULL,'2026-09-20 14:55:14'),(539,1,'notification_deleted','notification',29,'::1','Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Mobile Safari/537.36',NULL,'2026-09-20 14:55:16'),(540,1,'notification_deleted','notification',27,'::1','Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Mobile Safari/537.36',NULL,'2026-09-20 14:55:17'),(541,132,'login_google_demo','user',132,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36',NULL,'2026-10-07 21:49:29'),(542,1,'login','user',1,'::1',NULL,NULL,'2026-10-07 21:54:52'),(543,1,'login','user',1,'::1',NULL,NULL,'2026-10-07 21:55:13'),(544,2,'login','user',2,'::1',NULL,NULL,'2026-10-07 21:56:05'),(545,6,'login','user',6,'::1',NULL,NULL,'2026-10-07 21:56:06'),(546,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36',NULL,'2026-10-07 22:33:36'),(547,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36',NULL,'2026-10-07 22:40:13'),(548,1,'settings_updated','setting',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','{\"site_name\": \"Vipasa Yoga\", \"tax_percent\": \"5\", \"default_currency\": \"INR\", \"default_timezone\": \"Asia/Kolkata\"}','2026-10-07 22:49:40'),(549,1,'settings_updated','setting',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','{\"section\": \"website\"}','2026-10-07 22:50:57'),(550,1,'settings_updated','setting',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','{\"section\": \"website\"}','2026-10-07 22:51:55'),(551,1,'settings_updated','setting',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','{\"section\": \"google\"}','2026-10-07 22:53:20'),(552,1,'settings_updated','setting',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','{\"section\": \"google\"}','2026-10-07 22:53:42'),(553,1,'settings_updated','setting',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','{\"section\": \"google\"}','2026-10-07 22:55:36'),(554,1,'settings_updated','setting',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','{\"section\": \"google\"}','2026-10-07 22:56:11'),(555,1,'settings_updated','setting',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','{\"section\": \"google\"}','2026-10-07 22:56:34'),(556,1,'settings_updated','setting',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','{\"section\": \"payments\"}','2026-10-07 22:57:54'),(557,1,'settings_updated','setting',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','{\"section\": \"smtp\"}','2026-10-07 22:59:54'),(558,1,'settings_updated','setting',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','{\"section\": \"security\"}','2026-10-07 23:01:43'),(559,1,'settings_updated','setting',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','{\"section\": \"security\"}','2026-10-07 23:01:57'),(560,1,'settings_updated','setting',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','{\"section\": \"security\"}','2026-10-07 23:02:18'),(561,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,'2026-10-17 19:23:32'),(562,1,'login','user',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,'2026-10-17 19:28:07');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_reschedule_requests`
--

DROP TABLE IF EXISTS `booking_reschedule_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_reschedule_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `requested_slot_date` date NOT NULL,
  `requested_start_time` time NOT NULL,
  `status` enum('pending','approved','declined') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_reschedule_request_booking` (`booking_id`),
  CONSTRAINT `fk_reschedule_request_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_slots` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `instructor_id` bigint unsigned NOT NULL,
  `slot_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` enum('available','booked','blocked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `booking_id` bigint unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_instructor_slot` (`instructor_id`,`slot_date`,`start_time`),
  KEY `idx_slot_date` (`slot_date`),
  KEY `idx_slot_status` (`status`),
  KEY `fk_slot_booking` (`booking_id`),
  CONSTRAINT `fk_slot_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_slot_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_slots`
--

LOCK TABLES `booking_slots` WRITE;
/*!40000 ALTER TABLE `booking_slots` DISABLE KEYS */;
INSERT INTO `booking_slots` VALUES (1,4,'2026-08-20','06:00:00','07:00:00','available',NULL,'2026-08-06 21:23:42'),(2,4,'2026-08-20','07:00:00','08:00:00','available',NULL,'2026-08-06 21:23:42'),(3,4,'2026-08-20','08:00:00','09:00:00','available',NULL,'2026-08-06 21:23:42'),(4,4,'2026-08-20','09:00:00','10:00:00','booked',5,'2026-08-06 21:23:42'),(5,4,'2026-08-20','10:00:00','11:00:00','available',NULL,'2026-08-06 21:23:42'),(6,4,'2026-08-20','16:00:00','17:00:00','available',NULL,'2026-08-06 21:23:42'),(7,4,'2026-08-20','17:00:00','18:00:00','available',NULL,'2026-08-06 21:23:42'),(8,4,'2026-08-20','18:00:00','19:00:00','available',NULL,'2026-08-06 21:23:42'),(9,4,'2026-08-20','19:00:00','20:00:00','available',NULL,'2026-08-06 21:23:42'),(10,4,'2026-08-27','06:00:00','07:00:00','available',NULL,'2026-08-06 21:25:09'),(11,4,'2026-08-27','07:00:00','08:00:00','available',NULL,'2026-08-06 21:25:09'),(12,4,'2026-08-27','08:00:00','09:00:00','available',NULL,'2026-08-06 21:25:09'),(13,4,'2026-08-27','09:00:00','10:00:00','available',NULL,'2026-08-06 21:25:09'),(14,4,'2026-08-27','10:00:00','11:00:00','available',NULL,'2026-08-06 21:25:09'),(15,4,'2026-08-27','16:00:00','17:00:00','available',NULL,'2026-08-06 21:25:09'),(16,4,'2026-08-27','17:00:00','18:00:00','available',NULL,'2026-08-06 21:25:09'),(17,4,'2026-08-27','18:00:00','19:00:00','available',NULL,'2026-08-06 21:25:09'),(18,4,'2026-08-27','19:00:00','20:00:00','booked',7,'2026-08-06 21:25:09'),(19,4,'2026-08-17','06:00:00','07:00:00','available',NULL,'2026-08-16 18:17:00'),(20,4,'2026-08-17','07:00:00','08:00:00','booked',8,'2026-08-16 18:17:00'),(21,4,'2026-08-17','08:00:00','09:00:00','available',NULL,'2026-08-16 18:17:00'),(22,4,'2026-08-17','09:00:00','10:00:00','available',NULL,'2026-08-16 18:17:00'),(23,4,'2026-08-17','10:00:00','11:00:00','available',NULL,'2026-08-16 18:17:00'),(24,4,'2026-08-17','16:00:00','17:00:00','available',NULL,'2026-08-16 18:17:00'),(25,4,'2026-08-17','17:00:00','18:00:00','available',NULL,'2026-08-16 18:17:00'),(26,4,'2026-08-17','18:00:00','19:00:00','available',NULL,'2026-08-16 18:17:00'),(27,4,'2026-08-17','19:00:00','20:00:00','available',NULL,'2026-08-16 18:17:00'),(28,4,'2026-08-26','06:00:00','07:00:00','available',NULL,'2026-08-22 20:59:30'),(29,4,'2026-08-26','07:00:00','08:00:00','available',NULL,'2026-08-22 20:59:30'),(30,4,'2026-08-26','08:00:00','09:00:00','available',NULL,'2026-08-22 20:59:30'),(31,4,'2026-08-26','09:00:00','10:00:00','available',NULL,'2026-08-22 20:59:30'),(32,4,'2026-08-26','10:00:00','11:00:00','available',NULL,'2026-08-22 20:59:30'),(33,4,'2026-08-26','16:00:00','17:00:00','available',NULL,'2026-08-22 20:59:30'),(34,4,'2026-08-26','17:00:00','18:00:00','available',NULL,'2026-08-22 20:59:30'),(35,4,'2026-08-26','18:00:00','19:00:00','available',NULL,'2026-08-22 20:59:30'),(36,4,'2026-08-26','19:00:00','20:00:00','available',NULL,'2026-08-22 20:59:30'),(37,4,'2026-08-28','06:00:00','07:00:00','available',NULL,'2026-08-22 20:59:33'),(38,4,'2026-08-28','07:00:00','08:00:00','available',NULL,'2026-08-22 20:59:33'),(39,4,'2026-08-28','08:00:00','09:00:00','available',NULL,'2026-08-22 20:59:33'),(40,4,'2026-08-28','09:00:00','10:00:00','available',NULL,'2026-08-22 20:59:33'),(41,4,'2026-08-28','10:00:00','11:00:00','available',NULL,'2026-08-22 20:59:33'),(42,4,'2026-08-28','16:00:00','17:00:00','available',NULL,'2026-08-22 20:59:33'),(43,4,'2026-08-28','17:00:00','18:00:00','available',NULL,'2026-08-22 20:59:33'),(44,4,'2026-08-28','18:00:00','19:00:00','available',NULL,'2026-08-22 20:59:33'),(45,4,'2026-08-28','19:00:00','20:00:00','booked',9,'2026-08-22 20:59:33'),(46,4,'2026-08-29','08:00:00','09:00:00','available',NULL,'2026-08-22 21:17:19'),(47,4,'2026-08-29','09:00:00','10:00:00','available',NULL,'2026-08-22 21:17:19'),(48,4,'2026-08-29','10:00:00','11:00:00','available',NULL,'2026-08-22 21:17:19'),(49,4,'2026-08-29','11:00:00','12:00:00','available',NULL,'2026-08-22 21:17:19'),(50,9,'2026-08-24','09:00:00','10:00:00','available',NULL,'2026-08-23 12:23:19'),(51,9,'2026-08-24','10:00:00','11:00:00','booked',10,'2026-08-23 12:23:19'),(52,9,'2026-08-24','11:00:00','12:00:00','available',NULL,'2026-08-23 12:23:19'),(53,9,'2026-08-24','12:00:00','13:00:00','available',NULL,'2026-08-23 12:23:19'),(54,9,'2026-08-24','13:00:00','14:00:00','available',NULL,'2026-08-23 12:23:19'),(55,9,'2026-08-24','14:00:00','15:00:00','available',NULL,'2026-08-23 12:23:19'),(56,9,'2026-08-24','15:00:00','16:00:00','available',NULL,'2026-08-23 12:23:19'),(57,9,'2026-08-24','16:00:00','17:00:00','available',NULL,'2026-08-23 12:23:19'),(58,1,'2026-09-21','06:00:00','07:00:00','booked',13,'2026-09-20 13:05:14'),(59,1,'2026-09-21','07:00:00','08:00:00','booked',11,'2026-09-20 13:05:14'),(60,1,'2026-09-21','08:00:00','09:00:00','available',NULL,'2026-09-20 13:05:14'),(61,1,'2026-09-21','09:00:00','10:00:00','available',NULL,'2026-09-20 13:05:14'),(62,1,'2026-09-21','10:00:00','11:00:00','available',NULL,'2026-09-20 13:05:14'),(63,1,'2026-09-21','16:00:00','17:00:00','available',NULL,'2026-09-20 13:05:14'),(64,1,'2026-09-21','17:00:00','18:00:00','available',NULL,'2026-09-20 13:05:14'),(65,1,'2026-09-21','18:00:00','19:00:00','available',NULL,'2026-09-20 13:05:14'),(66,1,'2026-09-21','19:00:00','20:00:00','available',NULL,'2026-09-20 13:05:14'),(67,1,'2026-09-22','06:00:00','07:00:00','available',NULL,'2026-09-20 13:14:35'),(68,1,'2026-09-22','07:00:00','08:00:00','available',NULL,'2026-09-20 13:14:35'),(69,1,'2026-09-22','08:00:00','09:00:00','booked',12,'2026-09-20 13:14:35'),(70,1,'2026-09-22','09:00:00','10:00:00','available',NULL,'2026-09-20 13:14:35'),(71,1,'2026-09-22','10:00:00','11:00:00','available',NULL,'2026-09-20 13:14:35'),(72,1,'2026-09-22','16:00:00','17:00:00','available',NULL,'2026-09-20 13:14:35'),(73,1,'2026-09-22','17:00:00','18:00:00','available',NULL,'2026-09-20 13:14:35'),(74,1,'2026-09-22','18:00:00','19:00:00','available',NULL,'2026-09-20 13:14:35'),(75,1,'2026-09-22','19:00:00','20:00:00','available',NULL,'2026-09-20 13:14:35'),(76,1,'2026-09-23','06:00:00','07:00:00','available',NULL,'2026-09-20 13:37:28'),(77,1,'2026-09-23','07:00:00','08:00:00','available',NULL,'2026-09-20 13:37:28'),(78,1,'2026-09-23','08:00:00','09:00:00','booked',14,'2026-09-20 13:37:28'),(79,1,'2026-09-23','09:00:00','10:00:00','available',NULL,'2026-09-20 13:37:28'),(80,1,'2026-09-23','10:00:00','11:00:00','available',NULL,'2026-09-20 13:37:28'),(81,1,'2026-09-23','16:00:00','17:00:00','available',NULL,'2026-09-20 13:37:28'),(82,1,'2026-09-23','17:00:00','18:00:00','available',NULL,'2026-09-20 13:37:28'),(83,1,'2026-09-23','18:00:00','19:00:00','available',NULL,'2026-09-20 13:37:28'),(84,1,'2026-09-23','19:00:00','20:00:00','available',NULL,'2026-09-20 13:37:28');
/*!40000 ALTER TABLE `booking_slots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_ref` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `instructor_id` bigint unsigned NOT NULL,
  `package_id` bigint unsigned NOT NULL,
  `slot_id` bigint unsigned DEFAULT NULL,
  `slot_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `client_timezone` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UTC',
  `status` enum('pending_payment','awaiting_trainer_approval','confirmed','completed','cancelled','rescheduled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_payment',
  `price` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `coupon_id` bigint unsigned DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `currency` char(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INR',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `reminder_sent_at` datetime DEFAULT NULL,
  `reminder_sent_24h` tinyint(1) NOT NULL DEFAULT '0',
  `reminder_sent_1h` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
  CONSTRAINT `fk_booking_package` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_booking_slot` FOREIGN KEY (`slot_id`) REFERENCES `booking_slots` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (5,'VYNRK4RADN',6,4,1,4,'2026-08-20','09:00:00','10:00:00','Asia/Calcutta','completed',1499.00,74.95,0.00,NULL,1573.95,'INR',NULL,NULL,0,0,'2026-08-06 21:23:52','2026-10-17 19:22:40'),(6,'VY14IIJ383',6,4,4,13,'2026-08-27','09:00:00','10:00:00','Asia/Calcutta','cancelled',3999.00,199.95,0.00,NULL,4198.95,'INR',NULL,NULL,0,0,'2026-08-06 21:27:13','2026-08-08 10:36:48'),(7,'VY4KYEL7RJ',6,4,4,18,'2026-08-27','19:00:00','20:00:00','Asia/Calcutta','completed',3999.00,199.95,0.00,NULL,4198.95,'INR',NULL,NULL,0,0,'2026-08-08 12:33:10','2026-10-17 19:22:40'),(8,'VY0BYBB6M7',6,4,1,20,'2026-08-17','07:00:00','08:00:00','Asia/Calcutta','completed',1499.00,74.95,0.00,NULL,1573.95,'INR',NULL,NULL,0,0,'2026-08-16 18:17:06','2026-10-17 19:22:40'),(9,'VYDGTGRZ9D',6,4,1,45,'2026-08-28','19:00:00','20:00:00','Asia/Calcutta','pending_payment',1499.00,74.95,0.00,NULL,1573.95,'INR',NULL,NULL,0,0,'2026-08-22 20:59:37','2026-08-22 20:59:37'),(10,'VY01Q984Y0',6,9,5,51,'2026-08-24','10:00:00','11:00:00','Asia/Calcutta','completed',13999.00,699.95,0.00,NULL,14698.95,'INR',NULL,NULL,0,0,'2026-08-23 12:23:24','2026-10-17 19:22:40'),(11,'VYTIRM64YH',129,1,1,59,'2026-09-21','07:00:00','08:00:00','Asia/Calcutta','pending_payment',1499.00,74.95,0.00,NULL,1573.95,'INR',NULL,NULL,0,0,'2026-09-20 13:07:12','2026-09-20 13:07:12'),(12,'VY6PIECV6R',129,1,1,69,'2026-09-22','08:00:00','09:00:00','Asia/Calcutta','pending_payment',1499.00,74.95,0.00,NULL,1573.95,'INR',NULL,NULL,0,0,'2026-09-20 13:14:40','2026-09-20 13:14:40'),(13,'VYLWNX7D0K',129,1,1,58,'2026-09-21','06:00:00','07:00:00','Asia/Calcutta','completed',1499.00,74.95,0.00,NULL,1573.95,'INR',NULL,NULL,0,0,'2026-09-20 13:22:32','2026-10-17 19:22:40'),(14,'VYD8NT51KL',129,1,1,78,'2026-09-23','08:00:00','09:00:00','Asia/Calcutta','pending_payment',1499.00,74.95,0.00,NULL,1573.95,'INR',NULL,NULL,0,0,'2026-09-20 13:37:32','2026-09-20 13:37:32'),(15,'VYJQMUSFWG',6,1,1,NULL,'2026-10-09','10:00:00','11:00:00','Asia/Kolkata','pending_payment',1499.00,0.00,0.00,NULL,1499.00,'INR','Pre-deployment verification test',NULL,0,0,'2026-10-07 21:53:24','2026-10-07 21:53:24'),(17,'VY1CHBJQX6',128,1,1,NULL,'2026-10-18','10:00:00','11:00:00','Asia/Kolkata','completed',1499.00,50.00,0.00,NULL,1549.00,'INR','Flow test session',NULL,0,0,'2026-10-17 19:22:40','2026-10-17 19:22:40');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calendar_syncs`
--

DROP TABLE IF EXISTS `calendar_syncs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `calendar_syncs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `provider` enum('google','outlook','apple') COLLATE utf8mb4_unicode_ci NOT NULL,
  `sync_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `connected_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `age` tinyint unsigned DEFAULT NULL,
  `height_cm` smallint unsigned DEFAULT NULL,
  `weight_kg` smallint unsigned DEFAULT NULL,
  `gender` enum('female','male','non_binary','prefer_not_to_say') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `medical_notes` text COLLATE utf8mb4_unicode_ci,
  `emergency_contact_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_relationship` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_category` enum('child','teenager','adult','senior_citizen','pregnant_woman','postpartum_mother') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `health_assessment` json DEFAULT NULL,
  `preferences` json DEFAULT NULL,
  `timezone` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UTC',
  `avatar_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `onboarding_completed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `fk_clientprofile_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_profiles`
--

LOCK TABLES `client_profiles` WRITE;
/*!40000 ALTER TABLE `client_profiles` DISABLE KEYS */;
INSERT INTO `client_profiles` VALUES (1,6,'2004-09-27',21,170,59,'male','','coimbatore','','','','','','adult','{\"stage\": 5, \"fallRisk\": {\"recentFalls\": false}, \"surgeries\": {\"hadSurgery\": false}, \"medication\": {\"recentChange\": false, \"takingMedication\": false}, \"currentStep\": \"consent\", \"advancedPain\": {\"painChange\": \"no_pain\"}, \"longTermGoals\": {\"primary\": \"mental_wellness\"}, \"advancedHealth\": {\"changed\": false}, \"categorySafety\": {\"deskSetup\": false, \"longSitting\": true, \"screenHours\": \"4-8\", \"workPattern\": \"Shift work\", \"previousYoga\": false, \"startingPace\": \"Moderate\", \"sessionLength\": \"30 min\", \"caregivingLoad\": true}, \"foodPreference\": {\"diet\": \"eggetarian\"}, \"medicalHistory\": {\"details\": {\"other\": []}, \"conditions\": [\"other\"]}, \"painAssessment\": {\"areas\": [\"no_pain\"]}, \"healthScreening\": [], \"fitnessBackground\": {\"level\": \"none\"}, \"doctorRestrictions\": {\"notes\": \"\", \"hasRestriction\": false, \"restrictionTypes\": []}, \"languagePreference\": {\"language\": \"english\"}, \"lifestylePreferences\": {\"classTime\": \"evening\", \"classType\": \"private\"}, \"communicationPreference\": {\"channel\": \"whatsapp\"}}',NULL,'Asia/Calcutta',NULL,'2026-07-23 14:16:03','2026-07-23 14:16:03','2026-08-08 10:51:27'),(2,7,'2004-05-24',22,129,68,'male','','coimbatore','','','','','','adult','{\"currentStep\": \"painAssessmentDetails\", \"medicalHistory\": {\"details\": {\"low_bp\": {\"since\": \"lt_1y\", \"medication\": false, \"doctorRestriction\": true}, \"diabetes\": {\"since\": \"1_5y\", \"medication\": true, \"doctorRestriction\": false}}, \"conditions\": [\"diabetes\", \"low_bp\"]}, \"painAssessment\": {\"areas\": [\"shoulder\", \"back\", \"lower_back\", \"knee\", \"wrist\"], \"details\": {\"back\": [], \"knee\": [], \"wrist\": [], \"shoulder\": [], \"lower_back\": []}}}',NULL,'Asia/Calcutta',NULL,'2026-07-23 18:33:27','2026-07-23 18:33:27','2026-07-23 18:39:53'),(3,66,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'{\"currentStep\": \"healthScreening\", \"medicalHistory\": {\"details\": {\"high_bp\": {\"notes\": \"Monitored monthly\", \"since\": \"lt_1y\", \"medication\": true, \"medicationNotes\": \"Amlodipine 5mg daily\", \"medicationTypes\": [\"blood_pressure\", \"other\"], \"restrictionNotes\": \"Keep heart rate below 130 bpm\", \"restrictionTypes\": [\"no_heavy_lifting\", \"avoid_high_heart_rate\"], \"doctorRestriction\": true}}, \"conditions\": [\"high_bp\", \"other\"]}}',NULL,'UTC',NULL,NULL,'2026-08-06 21:08:52','2026-08-06 21:08:53'),(4,67,'1995-05-15',31,NULL,NULL,'female','','','','','','','','pregnant_woman','{\"stage\": 2, \"currentStep\": \"healthScreening\", \"categorySafety\": {\"trimester\": \"2nd\", \"complications\": true, \"weeksPregnant\": 20, \"doctorApproval\": true, \"firstPregnancy\": false, \"swellingFlagged\": false, \"pelvicOrBackPain\": true, \"nauseaOrDizziness\": false, \"prenatalYogaBefore\": true, \"complicationsDetails\": \"Preeclampsia history in previous pregnancy\"}, \"medicalHistory\": {\"details\": {\"high_bp\": {\"notes\": \"Monitored monthly\", \"since\": \"lt_1y\", \"medication\": true, \"medicationNotes\": \"Amlodipine 5mg daily\", \"medicationTypes\": [\"blood_pressure\", \"other\"], \"restrictionNotes\": \"Keep heart rate below 130 bpm\", \"restrictionTypes\": [\"no_heavy_lifting\", \"avoid_high_heart_rate\"], \"doctorRestriction\": true}}, \"conditions\": [\"high_bp\", \"other\"]}}',NULL,'Asia/Kolkata',NULL,'2026-08-06 21:09:40','2026-08-06 21:09:40','2026-08-06 21:09:40'),(5,73,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'{\"currentStep\": \"healthScreening\", \"medicalHistory\": {\"details\": {\"high_bp\": {\"notes\": \"Monitored monthly\", \"since\": \"lt_1y\", \"medication\": true, \"medicationNotes\": \"Amlodipine 5mg daily\", \"medicationTypes\": [\"blood_pressure\", \"other\"], \"restrictionNotes\": \"Keep heart rate below 130 bpm\", \"restrictionTypes\": [\"no_heavy_lifting\", \"avoid_high_heart_rate\"], \"doctorRestriction\": true}}, \"conditions\": [\"high_bp\", \"other\"]}}',NULL,'UTC',NULL,NULL,'2026-08-06 21:13:58','2026-08-06 21:13:58'),(6,74,'1995-05-15',31,NULL,NULL,'female','','','','','','','','pregnant_woman','{\"stage\": 2, \"currentStep\": \"healthScreening\", \"categorySafety\": {\"trimester\": \"2nd\", \"complications\": true, \"weeksPregnant\": 20, \"doctorApproval\": true, \"firstPregnancy\": false, \"swellingFlagged\": false, \"pelvicOrBackPain\": true, \"nauseaOrDizziness\": false, \"prenatalYogaBefore\": true, \"complicationsDetails\": \"Preeclampsia history in previous pregnancy\"}, \"medicalHistory\": {\"details\": {\"high_bp\": {\"notes\": \"Monitored monthly\", \"since\": \"lt_1y\", \"medication\": true, \"medicationNotes\": \"Amlodipine 5mg daily\", \"medicationTypes\": [\"blood_pressure\", \"other\"], \"restrictionNotes\": \"Keep heart rate below 130 bpm\", \"restrictionTypes\": [\"no_heavy_lifting\", \"avoid_high_heart_rate\"], \"doctorRestriction\": true}}, \"conditions\": [\"high_bp\", \"other\"]}}',NULL,'Asia/Kolkata',NULL,'2026-08-06 21:14:20','2026-08-06 21:14:20','2026-08-06 21:14:20'),(7,80,'1995-05-15',31,NULL,NULL,'female','','','','','','','','pregnant_woman','{\"stage\": 2, \"currentStep\": \"healthScreening\", \"categorySafety\": {\"trimester\": \"2nd\", \"complications\": true, \"weeksPregnant\": 20, \"doctorApproval\": true, \"firstPregnancy\": false, \"swellingFlagged\": false, \"pelvicOrBackPain\": true, \"nauseaOrDizziness\": false, \"prenatalYogaBefore\": true, \"complicationsDetails\": \"Preeclampsia history in previous pregnancy\"}, \"medicalHistory\": {\"details\": {\"high_bp\": {\"notes\": \"Monitored monthly\", \"since\": \"lt_1y\", \"medication\": true, \"medicationNotes\": \"Amlodipine 5mg daily\", \"medicationTypes\": [\"blood_pressure\", \"other\"], \"restrictionNotes\": \"Keep heart rate below 130 bpm\", \"restrictionTypes\": [\"no_heavy_lifting\", \"avoid_high_heart_rate\"], \"doctorRestriction\": true}}, \"conditions\": [\"high_bp\", \"other\"]}}',NULL,'Asia/Kolkata',NULL,'2026-08-06 21:22:03','2026-08-06 21:22:03','2026-08-06 21:22:03'),(8,86,'1995-05-15',31,NULL,NULL,'female','','','','','','','','pregnant_woman','{\"stage\": 2, \"currentStep\": \"healthScreening\", \"categorySafety\": {\"trimester\": \"2nd\", \"complications\": true, \"weeksPregnant\": 20, \"doctorApproval\": true, \"firstPregnancy\": false, \"swellingFlagged\": false, \"pelvicOrBackPain\": true, \"nauseaOrDizziness\": false, \"prenatalYogaBefore\": true, \"complicationsDetails\": \"Preeclampsia history in previous pregnancy\"}, \"medicalHistory\": {\"details\": {\"high_bp\": {\"notes\": \"Monitored monthly\", \"since\": \"lt_1y\", \"medication\": true, \"medicationNotes\": \"Amlodipine 5mg daily\", \"medicationTypes\": [\"blood_pressure\", \"other\"], \"restrictionNotes\": \"Keep heart rate below 130 bpm\", \"restrictionTypes\": [\"no_heavy_lifting\", \"avoid_high_heart_rate\"], \"doctorRestriction\": true}}, \"conditions\": [\"high_bp\", \"other\"]}}',NULL,'Asia/Kolkata',NULL,'2026-08-06 21:31:56','2026-08-06 21:31:56','2026-08-06 21:31:56'),(9,92,'1995-05-15',31,NULL,NULL,'female','','','','','','','','pregnant_woman','{\"stage\": 2, \"currentStep\": \"healthScreening\", \"categorySafety\": {\"trimester\": \"2nd\", \"complications\": true, \"weeksPregnant\": 20, \"doctorApproval\": true, \"firstPregnancy\": false, \"swellingFlagged\": false, \"pelvicOrBackPain\": true, \"nauseaOrDizziness\": false, \"prenatalYogaBefore\": true, \"complicationsDetails\": \"Preeclampsia history in previous pregnancy\"}, \"medicalHistory\": {\"details\": {\"high_bp\": {\"notes\": \"Monitored monthly\", \"since\": \"lt_1y\", \"medication\": true, \"medicationNotes\": \"Amlodipine 5mg daily\", \"medicationTypes\": [\"blood_pressure\", \"other\"], \"restrictionNotes\": \"Keep heart rate below 130 bpm\", \"restrictionTypes\": [\"no_heavy_lifting\", \"avoid_high_heart_rate\"], \"doctorRestriction\": true}}, \"conditions\": [\"high_bp\", \"other\"]}}',NULL,'Asia/Kolkata',NULL,'2026-08-06 21:35:40','2026-08-06 21:35:40','2026-08-06 21:35:41'),(10,98,'1995-05-15',31,NULL,NULL,'female','','','','','','','','pregnant_woman','{\"stage\": 2, \"currentStep\": \"healthScreening\", \"categorySafety\": {\"trimester\": \"2nd\", \"complications\": true, \"weeksPregnant\": 20, \"doctorApproval\": true, \"firstPregnancy\": false, \"swellingFlagged\": false, \"pelvicOrBackPain\": true, \"nauseaOrDizziness\": false, \"prenatalYogaBefore\": true, \"complicationsDetails\": \"Preeclampsia history in previous pregnancy\"}, \"medicalHistory\": {\"details\": {\"high_bp\": {\"notes\": \"Monitored monthly\", \"since\": \"lt_1y\", \"medication\": true, \"medicationNotes\": \"Amlodipine 5mg daily\", \"medicationTypes\": [\"blood_pressure\", \"other\"], \"restrictionNotes\": \"Keep heart rate below 130 bpm\", \"restrictionTypes\": [\"no_heavy_lifting\", \"avoid_high_heart_rate\"], \"doctorRestriction\": true}}, \"conditions\": [\"high_bp\", \"other\"]}}',NULL,'Asia/Kolkata',NULL,'2026-08-06 21:42:49','2026-08-06 21:42:49','2026-08-06 21:42:49'),(11,104,'1995-05-15',31,NULL,NULL,'female','','','','','','','','pregnant_woman','{\"stage\": 2, \"currentStep\": \"healthScreening\", \"categorySafety\": {\"trimester\": \"2nd\", \"complications\": true, \"weeksPregnant\": 20, \"doctorApproval\": true, \"firstPregnancy\": false, \"swellingFlagged\": false, \"pelvicOrBackPain\": true, \"nauseaOrDizziness\": false, \"prenatalYogaBefore\": true, \"complicationsDetails\": \"Preeclampsia history in previous pregnancy\"}, \"medicalHistory\": {\"details\": {\"high_bp\": {\"notes\": \"Monitored monthly\", \"since\": \"lt_1y\", \"medication\": true, \"medicationNotes\": \"Amlodipine 5mg daily\", \"medicationTypes\": [\"blood_pressure\", \"other\"], \"restrictionNotes\": \"Keep heart rate below 130 bpm\", \"restrictionTypes\": [\"no_heavy_lifting\", \"avoid_high_heart_rate\"], \"doctorRestriction\": true}}, \"conditions\": [\"high_bp\", \"other\"]}}',NULL,'Asia/Kolkata',NULL,'2026-08-06 21:49:46','2026-08-06 21:49:46','2026-08-06 21:49:46'),(12,110,'1995-05-15',31,NULL,NULL,'female','','','','','','','','pregnant_woman','{\"stage\": 2, \"currentStep\": \"healthScreening\", \"categorySafety\": {\"trimester\": \"2nd\", \"complications\": true, \"weeksPregnant\": 20, \"doctorApproval\": true, \"firstPregnancy\": false, \"swellingFlagged\": false, \"pelvicOrBackPain\": true, \"nauseaOrDizziness\": false, \"prenatalYogaBefore\": true, \"complicationsDetails\": \"Preeclampsia history in previous pregnancy\"}, \"medicalHistory\": {\"details\": {\"high_bp\": {\"notes\": \"Monitored monthly\", \"since\": \"lt_1y\", \"medication\": true, \"medicationNotes\": \"Amlodipine 5mg daily\", \"medicationTypes\": [\"blood_pressure\", \"other\"], \"restrictionNotes\": \"Keep heart rate below 130 bpm\", \"restrictionTypes\": [\"no_heavy_lifting\", \"avoid_high_heart_rate\"], \"doctorRestriction\": true}}, \"conditions\": [\"high_bp\", \"other\"]}}',NULL,'Asia/Kolkata',NULL,'2026-08-06 21:55:00','2026-08-06 21:55:00','2026-08-06 21:55:00'),(13,116,'1995-05-15',31,NULL,NULL,'female','','','','','','','','pregnant_woman','{\"stage\": 2, \"currentStep\": \"healthScreening\", \"categorySafety\": {\"trimester\": \"2nd\", \"complications\": true, \"weeksPregnant\": 20, \"doctorApproval\": true, \"firstPregnancy\": false, \"swellingFlagged\": false, \"pelvicOrBackPain\": true, \"nauseaOrDizziness\": false, \"prenatalYogaBefore\": true, \"complicationsDetails\": \"Preeclampsia history in previous pregnancy\"}, \"medicalHistory\": {\"details\": {\"high_bp\": {\"notes\": \"Monitored monthly\", \"since\": \"lt_1y\", \"medication\": true, \"medicationNotes\": \"Amlodipine 5mg daily\", \"medicationTypes\": [\"blood_pressure\", \"other\"], \"restrictionNotes\": \"Keep heart rate below 130 bpm\", \"restrictionTypes\": [\"no_heavy_lifting\", \"avoid_high_heart_rate\"], \"doctorRestriction\": true}}, \"conditions\": [\"high_bp\", \"other\"]}}',NULL,'Asia/Kolkata',NULL,'2026-08-06 22:01:50','2026-08-06 22:01:50','2026-08-06 22:01:50'),(15,128,'2007-12-14',18,124,44,'male','','chennai','','','','','','child',NULL,NULL,'Asia/Calcutta',NULL,'2026-08-08 12:54:25','2026-08-08 12:54:25','2026-08-08 12:54:25'),(16,129,'2026-08-14',0,140,34,'female','','thoothukkudi','','','','','','child','{\"stage\": 4, \"fallRisk\": {\"recentFalls\": true}, \"surgeries\": {\"hadSurgery\": true, \"howLongAgo\": \"less_than_6_months\"}, \"medication\": {\"recentChange\": true, \"takingMedication\": true}, \"currentStep\": \"advancedRecommendations\", \"advancedPain\": {\"newAreas\": [\"neck\"], \"painChange\": \"same\"}, \"advancedHealth\": {\"changed\": true}, \"categorySafety\": {\"screenTime\": \"1-3h\", \"previousYoga\": true, \"schoolSports\": true, \"supportDevice\": false, \"growthConcerns\": true, \"guardianPresent\": true, \"instructionSupport\": false, \"guardianRelationship\": \"Mother\"}, \"medicalHistory\": {\"details\": {\"adhd_/_hyperactivity\": {\"since\": \"lt_1y\", \"medication\": true, \"doctorRestriction\": true}}, \"conditions\": [\"adhd_/_hyperactivity\"], \"otherConditionNotes\": \"\"}, \"painAssessment\": {\"areas\": [\"knee\"], \"details\": {\"knee\": {\"side\": \"right\", \"pattern\": \"continuous\", \"severity\": \"2\", \"treatment\": \"physiotherapy\", \"walkingPain\": true, \"doctorDiagnosis\": true}}}, \"healthScreening\": {\"hadSurgery\": false, \"hadInjuries\": false, \"hasAllergies\": false, \"takingMedication\": false, \"advisedNotToExercise\": false}, \"doctorRestrictions\": {\"notes\": \"\", \"hasRestriction\": true, \"restrictionTypes\": []}, \"lifestylePreferences\": {\"classTime\": \"evening\", \"classType\": \"group\"}}',NULL,'Asia/Calcutta',NULL,'2026-08-16 18:12:11','2026-08-16 18:12:11','2026-09-20 13:14:31'),(17,131,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'{\"stage\": 2, \"currentStep\": \"completed\", \"medicalHistory\": {\"conditions\": [\"none_of_these\"], \"otherConditionNotes\": \"\"}, \"painAssessment\": {\"areas\": [\"no_pain\"]}, \"healthScreening\": {\"hadSurgery\": false, \"hadInjuries\": false, \"hasAllergies\": false, \"takingMedication\": false, \"advisedNotToExercise\": false}, \"doctorRestrictions\": {\"notes\": \"\", \"hasRestriction\": false, \"restrictionTypes\": []}}',NULL,'UTC',NULL,'2026-08-20 21:28:41','2026-08-20 21:20:36','2026-08-20 21:28:41'),(18,132,'2004-09-27',21,170,65,'male','','Bangalore','','','Parent','9876543211','Parent','adult',NULL,NULL,'Asia/Calcutta',NULL,'2026-08-22 21:29:37','2026-08-22 21:27:17','2026-08-22 21:29:37');
/*!40000 ALTER TABLE `client_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consents`
--

DROP TABLE IF EXISTS `consents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `consents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `booking_id` bigint unsigned DEFAULT NULL,
  `consent_version` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
INSERT INTO `consents` VALUES (1,6,NULL,'v1','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Asia/Calcutta','2026-08-08 10:51:27'),(2,6,7,'v1','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','Asia/Calcutta','2026-08-08 14:22:12');
/*!40000 ALTER TABLE `consents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
INSERT INTO `contact_messages` VALUES (1,'QA Tester','qa.test@vipasa.demo','Automated QA regression test submission.','2026-08-06 19:40:48');
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_type` enum('percent','flat') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `max_uses` int unsigned DEFAULT NULL,
  `used_count` int unsigned NOT NULL DEFAULT '0',
  `min_order_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
INSERT INTO `coupons` VALUES (1,'WELCOME10','percent',10.00,500,0,0.00,'2026-07-23 13:56:53','2027-07-23 13:56:53',1,'2026-07-23 13:56:53'),(2,'FLAT200','flat',200.00,200,0,1000.00,'2026-07-23 13:56:53','2027-07-23 13:56:53',1,'2026-07-23 13:56:53');
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_verifications`
--

DROP TABLE IF EXISTS `email_verifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_verifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `token_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  `verified_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_emailverify_user` (`user_id`),
  CONSTRAINT `fk_emailverify_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_verifications`
--

LOCK TABLES `email_verifications` WRITE;
/*!40000 ALTER TABLE `email_verifications` DISABLE KEYS */;
INSERT INTO `email_verifications` VALUES (1,6,'14f74d16c3378e60b445493ff26aec4cf8f18787da51b330550381250c56e8a9','2026-07-24 14:15:21',NULL,'2026-07-23 14:15:21'),(2,7,'d59079ec4367906af65634be88f5434f594b101bb356dcfa7b1598c5c96de1ec','2026-07-24 18:32:25',NULL,'2026-07-23 18:32:25'),(3,10,'6e790e3d854129a098464d00482387b03fd52561dfb9e9dd8f6f1ad7d3ec41d6','2026-08-07 19:42:58',NULL,'2026-08-06 19:42:58'),(4,11,'c37e63ab4d07d58a62b5a1f409b1444a2510a44a24f558d1d507f0e44f561e3a','2026-08-07 19:43:53',NULL,'2026-08-06 19:43:53'),(5,12,'87b93733aa7f95f21f2ba152ded3e2bf6977ebe7a57b1813c8f212a17f5b49f6','2026-08-07 19:44:20',NULL,'2026-08-06 19:44:20'),(6,13,'0430fb5a2a339a63f4af16a615796d5c1006d0635e4d5aa81f438ce33e2322ee','2026-08-07 19:44:25',NULL,'2026-08-06 19:44:25'),(7,14,'2a4381c9db9ceb4fa77feaa80e7cc25f7e33c38f0580b6dc605d0c8343f483b5','2026-08-07 19:44:31',NULL,'2026-08-06 19:44:31'),(8,15,'526cce4560cfa786fd21a50c3c87a70d9f7a1ffb65e1060d74e97700b2a10588','2026-08-07 19:44:36',NULL,'2026-08-06 19:44:36'),(9,16,'06545d61ecc90cf78aafcf5051cdf9c1a23db0f8257589c0b9495906d7fb7e86','2026-08-07 19:44:42',NULL,'2026-08-06 19:44:42'),(10,18,'ac9f6f938f16866e93266684f9605169fab783a5dc1ea88307b2f2ccce353ef1','2026-08-07 19:51:18',NULL,'2026-08-06 19:51:18'),(11,19,'b9f647a53bda86812a894b81a0aa4462ce73166989990b7708c29ebff07db4b9','2026-08-07 19:51:23',NULL,'2026-08-06 19:51:23'),(12,20,'e691811bfa9629b4c95d455e386f05683810b262ebd758629b8e5a4204c69743','2026-08-07 19:51:29',NULL,'2026-08-06 19:51:29'),(13,21,'ff1c8eb1a5e43997d78df57059179411d50c04fbbbac27df0839873a076d7b6e','2026-08-07 19:51:35',NULL,'2026-08-06 19:51:35'),(14,22,'4c80de6801d9b86bcadf6dc961e43f856eb1fcf492b3e7fc7e6df207333695bb','2026-08-07 19:51:41',NULL,'2026-08-06 19:51:41'),(15,23,'217c2d65c5405df46ab02f6bf530480aa1b4cea07e2be767c169050a35905fa9','2026-08-07 19:54:10',NULL,'2026-08-06 19:54:10'),(16,24,'e97fb2aeba9829f57fc23f4a8d3f4c506b0a6f28aee976000d77684aadea2be7','2026-08-07 19:54:16',NULL,'2026-08-06 19:54:16'),(17,25,'780bde406c9ef97bc92d44f5c12ab0cd0104352fa7b84ffe1197f40f6b87ff47','2026-08-07 19:54:21',NULL,'2026-08-06 19:54:21'),(18,26,'a98035ff404c5caf69e98ac51cd68adee22c7d48fe1657a14407816dcba71f17','2026-08-07 19:54:27',NULL,'2026-08-06 19:54:27'),(19,27,'6e045d2b5021c83b9021ee0757479043535e386ddf4f279a8fb87de381392c8d','2026-08-07 19:54:33',NULL,'2026-08-06 19:54:33'),(20,28,'cf5bf939972efe8139accb32ca02c6f168de181c5a5155f0699299caf5f01ab1','2026-08-07 19:58:54',NULL,'2026-08-06 19:58:54'),(21,29,'74e79a57100afa05282b5b0f4ab02a1d19844ddee9f68a824a40587737183515','2026-08-07 19:58:59',NULL,'2026-08-06 19:58:59'),(22,30,'3d4f663dd45f9bc2562bb702b9427b160f57147f2e8bf0f2a5a46989a8e66677','2026-08-07 19:59:05',NULL,'2026-08-06 19:59:05'),(23,31,'4b5121b4678d179a386bc3736332619309f7bcb3e567665681e234f4664eaa39','2026-08-07 19:59:10',NULL,'2026-08-06 19:59:10'),(24,32,'51e434b7ddb8ff5f0b28642c5f692e4d1958415a6e89ef55b7825bb2a8beec5a','2026-08-07 19:59:16',NULL,'2026-08-06 19:59:16'),(25,33,'915c0dd628fc750a186b49d52d7e0166f796ffa8f32c438e2f3b496ee27f1f5e','2026-08-07 20:01:56',NULL,'2026-08-06 20:01:56'),(26,34,'4cc504cc16f367e9313b91a8cfccbcdfdba8ed7b89ca1c1d2e4ce56d645e6c80','2026-08-07 20:02:01',NULL,'2026-08-06 20:02:01'),(27,35,'a1602e2ce9e56490be6aa6da5edd26226c5f0c62f3672cdc37fa608170aa663e','2026-08-07 20:02:07',NULL,'2026-08-06 20:02:07'),(28,36,'f1998157e43fdae003fe5bcd44c99c3f25893e96a5844d8d79fa1e52e82eaa9a','2026-08-07 20:02:12',NULL,'2026-08-06 20:02:12'),(29,37,'541a05b58f1dde9a8656f0dd54542f93fe4dc06f1067928b5f3c4adba20b5525','2026-08-07 20:02:18',NULL,'2026-08-06 20:02:18'),(30,38,'4b223435014411e6a6fdb458c47e32c4ff401d4caf8990a71232e673c57b4856','2026-08-07 20:12:38',NULL,'2026-08-06 20:12:38'),(31,39,'c68a42bbd5b6733847ab38d0490d37b0a271d64afaafe359e8bdc9e0d0a3d9bf','2026-08-07 20:14:42',NULL,'2026-08-06 20:14:42'),(32,40,'b24f812d50420eded653fdcbe3f594be8e6e031599339a287a62418847584837','2026-08-07 20:26:22',NULL,'2026-08-06 20:26:22'),(33,41,'57764d0284b65e464d29557f4f4dc79a6e535427030be62aae8547ad07fe17ef','2026-08-07 20:26:32',NULL,'2026-08-06 20:26:32'),(34,42,'2583b1264db1a5452b741774751415fb01d26f32cd91daa002e98e28d0de486c','2026-08-07 20:26:38',NULL,'2026-08-06 20:26:38'),(35,43,'7034ca212c55040c57322b938448e55be36db48b8384f2038d3742491bd7ef5b','2026-08-07 20:26:43',NULL,'2026-08-06 20:26:43'),(36,44,'c1d4182ca463048a9778e5a2ece45eb6cc5d350ef8ba0e5b970bb29f6c3d9ebb','2026-08-07 20:26:49',NULL,'2026-08-06 20:26:49'),(37,45,'24cf569593bc96e421ff3442052d4e737bd511415bc2541c89aab5de9bfbf29f','2026-08-07 20:26:55',NULL,'2026-08-06 20:26:55'),(38,46,'824cbdcc9cdc3aca61b36097bcf36e3cf7199e9f3365b2403f9c28e6c9d42e59','2026-08-07 20:37:15',NULL,'2026-08-06 20:37:15'),(39,47,'9e72d9f0ee654fa0dbdbf2ef60a0ec83173d502d92c0dc8aa998b891b1b6d07d','2026-08-07 20:40:04',NULL,'2026-08-06 20:40:04'),(40,48,'28bd18020a66d0bdb57450332b9ebf3cf210197a2264c79067273677b0027a8b','2026-08-07 20:40:09',NULL,'2026-08-06 20:40:09'),(41,49,'82f4684113bf0c47bc8334b5332ad44eeef9fb648836de6df8d23d5518133314','2026-08-07 20:40:15',NULL,'2026-08-06 20:40:15'),(42,50,'689ebbb114fb8fafdbbfa780576c11946f7c3cad8f6c4528d53ec0fcaafd7fec','2026-08-07 20:40:20',NULL,'2026-08-06 20:40:20'),(43,51,'2919634b650d0493b8b2fbdb36fa6acc306db5746e8c3597daefb4a0579082cd','2026-08-07 20:40:26',NULL,'2026-08-06 20:40:26'),(44,52,'5f278b4e9950a473eca1731d15825d88a2de191dd12a6ed82607deb49adfccb3','2026-08-07 20:50:02',NULL,'2026-08-06 20:50:02'),(45,53,'05e87abf63f5d5a75f087ec0f7e50fe9a03a1025af025a2664866a4af02504c7','2026-08-07 20:50:10',NULL,'2026-08-06 20:50:10'),(46,54,'5cbe3d426fbd126a3ff47ae3385fd5f69715934b9c7abca6812fade095701a75','2026-08-07 20:51:21',NULL,'2026-08-06 20:51:21'),(47,55,'0524cf860f0a14e92a02267e6e55f209aa0dd6ffbc315fd5fd49c0e2f6279d25','2026-08-07 20:51:33',NULL,'2026-08-06 20:51:33'),(48,56,'20922e7c9b653c99dca4b96cb7f921e463540dfda36a781fb06c3bc81179cdb1','2026-08-07 20:51:38',NULL,'2026-08-06 20:51:38'),(49,57,'dcd61ca298d20c6f32ac003b1bd0960a5a7f93cf80899d52569670dddfa1b0a3','2026-08-07 20:51:44',NULL,'2026-08-06 20:51:44'),(50,58,'e7d4ac1dfdde82c27b3757d17c95f3fd133bd17e40b5b7e9e165853ff0da2547','2026-08-07 20:51:50',NULL,'2026-08-06 20:51:50'),(51,59,'660395c3f6153eab01bf848c03aec7de142b4ceb16add1711f68e488f0d60591','2026-08-07 20:51:55',NULL,'2026-08-06 20:51:55'),(52,60,'a5de12bbcf89f5e7c15f9739c0813ca26b5b764d4d1ed0d44a8889d711a60315','2026-08-07 20:59:02',NULL,'2026-08-06 20:59:02'),(53,61,'657d2f3a117858a54f35582a80b8f2f83874ed0319d9d3f0e3631ee56ba6eaaa','2026-08-07 20:59:16',NULL,'2026-08-06 20:59:16'),(54,62,'b11fa80f8c95b6d706d47f036a4bf9d40835f301c36a9e7223390777cc8b7c4c','2026-08-07 20:59:21',NULL,'2026-08-06 20:59:21'),(55,63,'70ada723302d18d3248f45dbf4f362f1434502c600bb9d901bd9367209f00fbe','2026-08-07 20:59:27',NULL,'2026-08-06 20:59:27'),(56,64,'d20c96a0b3525267f62e15b984b2dd4fdd0d9e412cf6544d91a1fcc990536e46','2026-08-07 20:59:33',NULL,'2026-08-06 20:59:33'),(57,65,'d72ebba651315cb854b239d303e03a378b44d81b191a185253e0a46f3db28928','2026-08-07 20:59:38',NULL,'2026-08-06 20:59:38'),(58,66,'abdc0f7e3eef9b950579beede659ca1619504f941258bd4ab5a2a601dd9a366e','2026-08-07 21:08:52',NULL,'2026-08-06 21:08:52'),(59,67,'716bff670e726c46f94a389b3dc5f3449a6e24b1a55698ee36f1e83acf403759','2026-08-07 21:09:40',NULL,'2026-08-06 21:09:40'),(60,68,'33c6506e95dea2a380f0c2ca66146610ea8ecfb30de78e21a7bb660e4616c19e','2026-08-07 21:09:47',NULL,'2026-08-06 21:09:47'),(61,69,'500d9dc68611684199905c55965519ef5e52d36f36efa9f59af6e49cd56011c9','2026-08-07 21:09:52',NULL,'2026-08-06 21:09:52'),(62,70,'ad37872970c5f8011ccd9edf6a4aa8a0fd7dd1ed9dae239080e3266d41b2d973','2026-08-07 21:09:57',NULL,'2026-08-06 21:09:57'),(63,71,'aa05cb8977a1db73d373dbb8efc38cd7abf97de4635feb22892e49899f55be98','2026-08-07 21:10:03',NULL,'2026-08-06 21:10:03'),(64,72,'d34e9c0767db86c3e9994364e245bd7ac49a7acdce0496355592065eb641b7a6','2026-08-07 21:10:08',NULL,'2026-08-06 21:10:08'),(65,73,'76049056db620ae2f309783ee6f4fc36abe04360ef2435fb76ff49a88689428e','2026-08-07 21:13:58',NULL,'2026-08-06 21:13:58'),(66,74,'ef9d738c5405cc92440a34f50f6a310e47294891aaa0b29db6069e88e599b4e2','2026-08-07 21:14:20',NULL,'2026-08-06 21:14:20'),(67,75,'2ddbcb588241a3ef07ed7383459397bfa0fc6ce288b94ad9e53098bc7ec84748','2026-08-07 21:14:31',NULL,'2026-08-06 21:14:31'),(68,76,'088a2671bd49a916e686eb01add20729e0b274f8275ba09aec7fa5f2c934fb4e','2026-08-07 21:14:36',NULL,'2026-08-06 21:14:36'),(69,77,'3b83cd33cbfd042d9d388f34e9bf725f657e6497268d7fcf4daccbfb9ac16a85','2026-08-07 21:14:41',NULL,'2026-08-06 21:14:41'),(70,78,'25b5f77c43a27cf0a6b5d388c08843212b4951dd4763ef6ac0e01013fd77a43d','2026-08-07 21:14:46',NULL,'2026-08-06 21:14:46'),(71,79,'5c76428030dca43f72d0da2a9657531f01211d6365f710ba84fdc239de5bcf9e','2026-08-07 21:14:52',NULL,'2026-08-06 21:14:52'),(72,80,'c286b003bac242ca6c0059d61c0344c39b2f2ffe6a7290d39bc87c6fdcbd860e','2026-08-07 21:22:03',NULL,'2026-08-06 21:22:03'),(73,81,'442ab141b49c5e5a598a71b64ccc9a49e027b36ec34b1760cf97166a151992f4','2026-08-07 21:22:20',NULL,'2026-08-06 21:22:20'),(74,82,'02dbabe57ade7e27677fcfadfd28cbf5c8e8d736c676436d003049b4b9bc5986','2026-08-07 21:22:25',NULL,'2026-08-06 21:22:25'),(75,83,'b7081afee98f756c7c65194dafc2d8d7baa537988dd871f5418e20ac98ab06a2','2026-08-07 21:22:30',NULL,'2026-08-06 21:22:30'),(76,84,'565559a25dd17b8624d5f30c5c3a1e29a8e816c35a1f2ad9b70b7bf1a9f605fd','2026-08-07 21:22:36',NULL,'2026-08-06 21:22:36'),(77,85,'30e7c8e3015807d151430b9b71295f7654bd42eefbd092d13425b54c4d638c53','2026-08-07 21:22:42',NULL,'2026-08-06 21:22:42'),(78,86,'4b09b7f241359b8ad23dcd0d5fa551d9b4f34d0ba5785561730f2fcd315354c6','2026-08-07 21:31:56',NULL,'2026-08-06 21:31:56'),(79,87,'ebb4ef9559b883ecbec626df2080b95f3830f2f10532ca6b4d92413f1da0d687','2026-08-07 21:32:04',NULL,'2026-08-06 21:32:04'),(80,88,'3150417e5459ac9a04e62d51bd7acec7b6e6cdf7ba154f7fb21f8e917c122d2a','2026-08-07 21:32:09',NULL,'2026-08-06 21:32:09'),(81,89,'ffd7d21a5853a5ac6c549309c9cd5fd803373a922ca391ff2eab861b99ad1d40','2026-08-07 21:32:15',NULL,'2026-08-06 21:32:15'),(82,90,'db686df27edb53e0ba6b35928ce4518936400ea1a84b708d8f0243cefb3c4c7d','2026-08-07 21:32:21',NULL,'2026-08-06 21:32:21'),(83,91,'45eae7a4c430b1ee621b8a2d45df5402475500924eb7b25d4241b4368d0f20f6','2026-08-07 21:32:26',NULL,'2026-08-06 21:32:26'),(84,92,'d54fe675f532393f6d89456941a6b0ccf3ecb76b4fc365a0c74f98cc8cf409c9','2026-08-07 21:35:40',NULL,'2026-08-06 21:35:40'),(85,93,'9a351efa128f212b33a0f75becb5d2d5be511a37c2f3c4c870ad2036f0f119ba','2026-08-07 21:35:47',NULL,'2026-08-06 21:35:47'),(86,94,'7e65424ed8bf069ecb119ea91cc7004d92c37294a7f639029983b2f937868b81','2026-08-07 21:35:52',NULL,'2026-08-06 21:35:52'),(87,95,'39b8de5210a27a7ee0074a00846243e85b6f2c0a20705ec630ea82d6315e1a84','2026-08-07 21:35:57',NULL,'2026-08-06 21:35:57'),(88,96,'f116f82bf9f2946296304e6e90b2b93e86b55d013f62bbc694776b389c2d4bab','2026-08-07 21:36:03',NULL,'2026-08-06 21:36:03'),(89,97,'ad7fa01e26098e1e5bc282dcfe15862923d851f486d1cd0442480dae2dde018f','2026-08-07 21:36:08',NULL,'2026-08-06 21:36:08'),(90,98,'31d9f1eb55643e4fa5c8721396dd5ab5063f4fccbe5f91c591c6f63932861170','2026-08-07 21:42:48',NULL,'2026-08-06 21:42:48'),(91,99,'79bfd24a8363581015482c5f8e278ba9b021bbe88cfb1a2a8841f3874760858c','2026-08-07 21:42:59',NULL,'2026-08-06 21:42:59'),(92,100,'c9c47eadb7f0530dacd989620897e1ff25ebe62bbe81824957eac6163ca6a818','2026-08-07 21:43:05',NULL,'2026-08-06 21:43:05'),(93,101,'ebd739e3489d203d7d3ac00a97538c3ff3e40ffdbe6ed5962f92c3a8e23eec61','2026-08-07 21:43:10',NULL,'2026-08-06 21:43:10'),(94,102,'040fb04ac8fc2aacade7180cde12bcf28bd7c94ca6e968585182df8388510591','2026-08-07 21:43:16',NULL,'2026-08-06 21:43:16'),(95,103,'658255c2b2070cf597e3db6fd4ff37b66dc0ad45b2d54e8a1ff4f76d536bb061','2026-08-07 21:43:22',NULL,'2026-08-06 21:43:22'),(96,104,'0d52e341c724f6462c6b43eac309c91fa7fa95a4100e7b3f0678b41d8f3a851b','2026-08-07 21:49:45',NULL,'2026-08-06 21:49:45'),(99,107,'8a0a113632ff1ade9e37c1281ceab0430c6eb09da8fa2f4c2bf90de5089d50a7','2026-08-07 21:50:06',NULL,'2026-08-06 21:50:06'),(100,108,'e0a96c8cb71bef37be80dc683ab2cbd9022c497962a283cb87d2a1c64b167560','2026-08-07 21:50:12',NULL,'2026-08-06 21:50:12'),(101,109,'58174475fce74ba81630f93490894a692bb527a536aa28a9484e2f3a0b29d06a','2026-08-07 21:50:17',NULL,'2026-08-06 21:50:17'),(102,110,'ffb8b416af6bab531dc49c0b36157a5d00e0b519f15df98d44c316a82f75487b','2026-08-07 21:55:00',NULL,'2026-08-06 21:55:00'),(103,111,'abc89ab30f50f6b567b65931b8135d3ce298e0f9f752fe51df083bdd2de48936','2026-08-07 21:55:10',NULL,'2026-08-06 21:55:10'),(104,112,'0d31f603476aa606ce6794cb54f9092cd1fe5c9db2c711f3b4c5cd54a6278f09','2026-08-07 21:55:15',NULL,'2026-08-06 21:55:15'),(105,113,'5a8acd58c27d992ac507758cb92f45bda0ae468310477a8f34334a7bbb166d16','2026-08-07 21:55:21',NULL,'2026-08-06 21:55:21'),(106,114,'4c6f5e8c7d9bdca125ce3f39bf7a338bf7765c192f615f3a6131d8d9b6d25666','2026-08-07 21:55:27',NULL,'2026-08-06 21:55:27'),(107,115,'223913d67321c207b55d19dde14534c3012a7159bb364f705fcdcb184a5285cf','2026-08-07 21:55:33',NULL,'2026-08-06 21:55:33'),(108,116,'6946d53b76991ee157cd5758802e0624049d33aa3342d13698a7a18cc78d2f5e','2026-08-07 22:01:50',NULL,'2026-08-06 22:01:50'),(109,117,'1bf01bb5f1b4aaf111807b74b2e05db0a145728d4ae960366a8ee7868056d60c','2026-08-07 22:01:59',NULL,'2026-08-06 22:01:59'),(110,118,'ea4d07d8c268cb236abf7d1569589cb7edc433cb6079a849ab5939367b95fe03','2026-08-07 22:02:05',NULL,'2026-08-06 22:02:05'),(111,119,'e6623fba83957e3cb5a98961edfec16ec15479eef0a0e3853e01390a7afd576c','2026-08-07 22:02:10',NULL,'2026-08-06 22:02:10'),(112,120,'6fe5df7ac8ab41fb4442fdcffe0d3fe0b03452e4eb6e65138cc79d62b9753fb5','2026-08-07 22:02:16',NULL,'2026-08-06 22:02:16'),(113,121,'259bf9d9b65c4df70c24e840221a0b583f1bd056f46577c3fb220873a992243b','2026-08-07 22:02:22',NULL,'2026-08-06 22:02:22'),(116,124,'38578069e6d3efee1f4b840380a0352fd6634114b9ecc409f73bcafc1e8302d4','2026-08-07 22:04:27',NULL,'2026-08-06 22:04:27'),(117,125,'c91b054f508413177523bfe0a6f2b92e1e96bee2ac59ebbcfbd7b5143d6dc41a','2026-08-07 22:04:33',NULL,'2026-08-06 22:04:33'),(118,126,'856c27af8f4c87912ded19e9fd8e1f8595fa83f99b158fba22338ef43c3e8f66','2026-08-07 22:04:41',NULL,'2026-08-06 22:04:41'),(120,128,'22f84e05cb8fe50025e3c85333983edf2c5c5e0a9b9b5923fec59aee071c2a23','2026-08-09 12:53:05',NULL,'2026-08-08 12:53:05'),(121,129,'493091f7c40791ac665e31b29a096ee15d2486ca4673b42d81bf3b6e192c1123','2026-08-17 18:11:26',NULL,'2026-08-16 18:11:26'),(122,131,'d96fc43bf5302719607b08833e334b501899f4369282076c47f8cc78ad4c2108','2026-08-21 21:08:05',NULL,'2026-08-20 21:08:05');
/*!40000 ALTER TABLE `email_verifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favorites` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `instructor_id` bigint unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_favorite` (`client_id`,`instructor_id`),
  KEY `fk_favorite_instructor` (`instructor_id`),
  CONSTRAINT `fk_favorite_client` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_favorite_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites`
--

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
INSERT INTO `favorites` VALUES (1,6,4,'2026-08-08 10:37:24');
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_images`
--

DROP TABLE IF EXISTS `gallery_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_gallery_sort` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_images`
--

LOCK TABLES `gallery_images` WRITE;
/*!40000 ALTER TABLE `gallery_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `gallery_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instructor_availability`
--

DROP TABLE IF EXISTS `instructor_availability`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `instructor_availability` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `instructor_id` bigint unsigned NOT NULL,
  `day_of_week` tinyint unsigned DEFAULT NULL COMMENT '0=Sunday..6=Saturday, NULL if is_recurring=0 (specific_date used instead)',
  `specific_date` date DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_recurring` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_availability_instructor` (`instructor_id`),
  CONSTRAINT `fk_availability_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instructor_availability`
--

LOCK TABLES `instructor_availability` WRITE;
/*!40000 ALTER TABLE `instructor_availability` DISABLE KEYS */;
INSERT INTO `instructor_availability` VALUES (1,3,1,NULL,'06:00:00','11:00:00',1,'2026-07-23 13:56:53'),(2,3,1,NULL,'16:00:00','20:00:00',1,'2026-07-23 13:56:53'),(3,2,1,NULL,'06:00:00','11:00:00',1,'2026-07-23 13:56:53'),(4,2,1,NULL,'16:00:00','20:00:00',1,'2026-07-23 13:56:53'),(5,1,1,NULL,'06:00:00','11:00:00',1,'2026-07-23 13:56:53'),(6,1,1,NULL,'16:00:00','20:00:00',1,'2026-07-23 13:56:53'),(7,4,1,NULL,'06:00:00','11:00:00',1,'2026-07-23 13:56:53'),(8,4,1,NULL,'16:00:00','20:00:00',1,'2026-07-23 13:56:53'),(9,3,2,NULL,'06:00:00','11:00:00',1,'2026-07-23 13:56:53'),(10,3,2,NULL,'16:00:00','20:00:00',1,'2026-07-23 13:56:53'),(11,2,2,NULL,'06:00:00','11:00:00',1,'2026-07-23 13:56:53'),(12,2,2,NULL,'16:00:00','20:00:00',1,'2026-07-23 13:56:53'),(13,1,2,NULL,'06:00:00','11:00:00',1,'2026-07-23 13:56:53'),(14,1,2,NULL,'16:00:00','20:00:00',1,'2026-07-23 13:56:53'),(15,4,2,NULL,'06:00:00','11:00:00',1,'2026-07-23 13:56:53'),(16,4,2,NULL,'16:00:00','20:00:00',1,'2026-07-23 13:56:53'),(17,3,3,NULL,'06:00:00','11:00:00',1,'2026-07-23 13:56:53'),(18,3,3,NULL,'16:00:00','20:00:00',1,'2026-07-23 13:56:53'),(19,2,3,NULL,'06:00:00','11:00:00',1,'2026-07-23 13:56:53'),(20,2,3,NULL,'16:00:00','20:00:00',1,'2026-07-23 13:56:53'),(21,1,3,NULL,'06:00:00','11:00:00',1,'2026-07-23 13:56:53'),(22,1,3,NULL,'16:00:00','20:00:00',1,'2026-07-23 13:56:53'),(23,4,3,NULL,'06:00:00','11:00:00',1,'2026-07-23 13:56:53'),(24,4,3,NULL,'16:00:00','20:00:00',1,'2026-07-23 13:56:53'),(25,3,4,NULL,'06:00:00','11:00:00',1,'2026-07-23 13:56:53'),(26,3,4,NULL,'16:00:00','20:00:00',1,'2026-07-23 13:56:53'),(27,2,4,NULL,'06:00:00','11:00:00',1,'2026-07-23 13:56:53'),(28,2,4,NULL,'16:00:00','20:00:00',1,'2026-07-23 13:56:53'),(29,1,4,NULL,'06:00:00','11:00:00',1,'2026-07-23 13:56:53'),(30,1,4,NULL,'16:00:00','20:00:00',1,'2026-07-23 13:56:53'),(31,4,4,NULL,'06:00:00','11:00:00',1,'2026-07-23 13:56:53'),(32,4,4,NULL,'16:00:00','20:00:00',1,'2026-07-23 13:56:53'),(33,3,5,NULL,'06:00:00','11:00:00',1,'2026-07-23 13:56:53'),(34,3,5,NULL,'16:00:00','20:00:00',1,'2026-07-23 13:56:53'),(35,2,5,NULL,'06:00:00','11:00:00',1,'2026-07-23 13:56:53'),(36,2,5,NULL,'16:00:00','20:00:00',1,'2026-07-23 13:56:53'),(37,1,5,NULL,'06:00:00','11:00:00',1,'2026-07-23 13:56:53'),(38,1,5,NULL,'16:00:00','20:00:00',1,'2026-07-23 13:56:53'),(39,4,5,NULL,'06:00:00','11:00:00',1,'2026-07-23 13:56:53'),(40,4,5,NULL,'16:00:00','20:00:00',1,'2026-07-23 13:56:53'),(64,4,6,NULL,'08:00:00','12:00:00',1,'2026-07-23 13:56:53'),(65,1,6,NULL,'08:00:00','12:00:00',1,'2026-07-23 13:56:53'),(66,2,6,NULL,'08:00:00','12:00:00',1,'2026-07-23 13:56:53'),(67,3,6,NULL,'08:00:00','12:00:00',1,'2026-07-23 13:56:53'),(68,9,0,NULL,'09:00:00','17:00:00',1,'2026-08-23 12:12:43'),(69,9,1,NULL,'09:00:00','17:00:00',1,'2026-08-23 12:12:43'),(70,9,2,NULL,'09:00:00','17:00:00',1,'2026-08-23 12:12:43'),(71,9,3,NULL,'09:00:00','17:00:00',1,'2026-08-23 12:12:43'),(72,9,4,NULL,'09:00:00','17:00:00',1,'2026-08-23 12:12:43'),(73,9,5,NULL,'09:00:00','17:00:00',1,'2026-08-23 12:12:43');
/*!40000 ALTER TABLE `instructor_availability` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instructor_blocked_dates`
--

DROP TABLE IF EXISTS `instructor_blocked_dates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `instructor_blocked_dates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `instructor_id` bigint unsigned DEFAULT NULL COMMENT 'NULL = platform-wide holiday',
  `blocked_date` date NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
INSERT INTO `instructor_blocked_dates` VALUES (1,NULL,'2026-08-02','Public Holiday','2026-07-23 13:56:53');
/*!40000 ALTER TABLE `instructor_blocked_dates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instructor_certificate_files`
--

DROP TABLE IF EXISTS `instructor_certificate_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `instructor_certificate_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `instructor_id` bigint unsigned NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_instructor_cert_file_instructor` (`instructor_id`),
  CONSTRAINT `fk_instructor_cert_file_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `instructor_gallery_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `instructor_id` bigint unsigned NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `instructor_reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `instructor_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `booking_id` bigint unsigned DEFAULT NULL,
  `rating` tinyint unsigned NOT NULL,
  `review_text` text COLLATE utf8mb4_unicode_ci,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_review_client` (`client_id`),
  KEY `idx_review_instructor` (`instructor_id`),
  CONSTRAINT `fk_review_client` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_review_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instructor_reviews`
--

LOCK TABLES `instructor_reviews` WRITE;
/*!40000 ALTER TABLE `instructor_reviews` DISABLE KEYS */;
INSERT INTO `instructor_reviews` VALUES (1,4,6,7,5,'very good',0,'2026-08-09 19:02:52'),(2,1,128,17,5,'Brilliant session! The instructor was peaceful, attentive, and guided each pose perfectly.',0,'2026-10-17 19:22:40');
/*!40000 ALTER TABLE `instructor_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instructor_services`
--

DROP TABLE IF EXISTS `instructor_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `instructor_services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `instructor_id` bigint unsigned NOT NULL,
  `package_id` bigint unsigned NOT NULL,
  `price_override` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
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
INSERT INTO `instructor_services` VALUES (1,3,1,NULL,1),(2,2,1,NULL,1),(3,1,1,NULL,1),(4,4,1,NULL,1),(9,3,3,NULL,1),(10,2,3,NULL,1),(11,1,3,NULL,1),(12,4,3,NULL,1),(13,3,4,NULL,1),(14,2,4,NULL,1),(15,1,4,NULL,1),(16,4,4,NULL,1),(17,3,5,NULL,1),(18,2,5,NULL,1),(19,1,5,NULL,1),(20,4,5,NULL,1),(21,3,6,NULL,1),(22,2,6,NULL,1),(23,1,6,NULL,1),(24,4,6,NULL,1);
/*!40000 ALTER TABLE `instructor_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instructors`
--

DROP TABLE IF EXISTS `instructors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `instructors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `headline` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `experience_years` tinyint unsigned NOT NULL DEFAULT '0',
  `certificates` json DEFAULT NULL,
  `specialties` json DEFAULT NULL,
  `timezone` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UTC',
  `rating_avg` decimal(3,2) NOT NULL DEFAULT '0.00',
  `rating_count` int unsigned NOT NULL DEFAULT '0',
  `avatar_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive','test') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `idx_instructor_status` (`status`),
  CONSTRAINT `fk_instructor_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instructors`
--

LOCK TABLES `instructors` WRITE;
/*!40000 ALTER TABLE `instructors` DISABLE KEYS */;
INSERT INTO `instructors` VALUES (1,2,'Certified Hatha & Vinyasa Instructor','Ananya has been teaching yoga for over a decade, blending traditional Hatha with modern Vinyasa flow.',11,'[\"RYT-500\", \"Yoga Alliance Certified\"]','[\"Hatha\", \"Vinyasa\", \"Prenatal\"]','Asia/Kolkata',5.00,1,'/assets/img/instructors/ananya.jpg','active','2026-07-23 13:56:53','2026-10-17 19:22:40'),(2,3,'Power Yoga & Strength Coach','Rahul specializes in power yoga and strength-focused sequences for athletes and beginners alike.',7,'[\"RYT-200\", \"Power Yoga Specialist\"]','[\"Power Yoga\", \"Core Strength\"]','Asia/Kolkata',4.60,84,'/assets/img/instructors/rahul.jpg','inactive','2026-07-23 13:56:53','2026-07-23 13:56:53'),(3,4,'Restorative & Mindfulness Guide','Sarah focuses on restorative yoga, breathwork, and mindfulness practices for stress relief.',9,'[\"RYT-300\", \"Mindfulness Based Stress Reduction\"]','[\"Restorative\", \"Breathwork\", \"Meditation\"]','Europe/London',4.90,201,'/assets/img/instructors/sarah.jpg','inactive','2026-07-23 13:56:53','2026-07-23 13:56:53'),(4,5,'Certified Yoga Teacher & Evaluator','Vijaya is a certified Yoga Teacher and Evaluator, accredited by the Yoga Certification Board under the Certification Scheme for Yoga Professionals, Ministry of AYUSH, Government of India. Her practice blends steady, breath-led sequencing with real attention to how each student is actually doing that day.',5,'[\"Yoga Teacher & Evaluator — Yoga Certification Board (Ministry of AYUSH\", \"Govt. of India)\"]','[\"Hatha Yoga\", \"Vinyasa Flow\", \"Restorative Yoga\"]','Asia/Kolkata',5.00,1,'/assets/img/client/instructor-vijaya.webp','active','2026-07-23 13:56:53','2026-08-23 12:30:43'),(7,17,'fghjds','dfghjgfdx',11,NULL,NULL,'UTC',0.00,0,'/uploads/avatars/instructor-7-1786025881.jpg','active','2026-08-06 19:46:50','2026-08-06 19:48:01'),(9,133,'Yoga',NULL,10,NULL,NULL,'UTC',0.00,0,NULL,'active','2026-08-23 12:12:06','2026-08-23 12:12:06');
/*!40000 ALTER TABLE `instructors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meeting_links`
--

DROP TABLE IF EXISTS `meeting_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `meeting_links` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `provider` enum('google_meet','zoom') COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_id` (`booking_id`),
  KEY `fk_meetinglink_admin` (`created_by`),
  CONSTRAINT `fk_meetinglink_admin` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_meetinglink_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meeting_links`
--

LOCK TABLES `meeting_links` WRITE;
/*!40000 ALTER TABLE `meeting_links` DISABLE KEYS */;
INSERT INTO `meeting_links` VALUES (1,6,'google_meet','https://meet.google.com/poh-kcki-yeo',1,'2026-08-06 22:06:41'),(2,7,'google_meet','https://meet.google.com/ank-gvgk-spw',1,'2026-08-08 14:20:01'),(3,17,'google_meet','https://meet.google.com/vyp-yoga-1chbjqx6',2,'2026-10-17 19:22:40');
/*!40000 ALTER TABLE `meeting_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletter_subscribers`
--

DROP TABLE IF EXISTS `newsletter_subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `newsletter_subscribers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel` enum('email','sms','in_app') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_app',
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_notification_user` (`user_id`,`is_read`),
  CONSTRAINT `fk_notification_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (3,7,'profile_milestone','in_app','Profile 20% complete!','Nice progress — keep going to unlock your full wellness profile.',1,'2026-07-23 18:33:27'),(23,6,'profile_milestone','in_app','Profile 100% complete!','Nice progress — keep going to unlock your full wellness profile.',1,'2026-08-08 10:51:27'),(60,132,'profile_milestone','in_app','Profile 20% complete!','Nice progress — keep going to unlock your full wellness profile.',0,'2026-10-07 21:49:29');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service_type_id` bigint unsigned NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `sessions_count` smallint unsigned NOT NULL DEFAULT '1',
  `duration_minutes` smallint unsigned NOT NULL DEFAULT '60',
  `max_participants` smallint unsigned NOT NULL DEFAULT '1',
  `price` decimal(10,2) NOT NULL,
  `currency` char(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INR',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
INSERT INTO `packages` VALUES (1,1,'Single Session','One-on-one 60 minute session.',1,60,1,1499.00,'INR',1,1,'2026-07-23 13:56:53'),(3,2,'Group Drop-in','Single group class, up to 12 participants.',1,60,12,599.00,'INR',1,0,'2026-07-23 13:56:53'),(4,3,'Weekly Package','3 sessions per week for one week.',3,60,1,3999.00,'INR',1,1,'2026-07-23 13:56:53'),(5,4,'Monthly Package','12 sessions across one month.',12,60,1,13999.00,'INR',1,1,'2026-07-23 13:56:53'),(6,5,'Custom Wellness Plan','Sessions and duration tailored after a consultation.',4,60,1,5999.00,'INR',1,0,'2026-07-23 13:56:53'),(7,1,'Extended Private Session','A longer 90-minute private session for deeper practice.',1,90,1,2199.00,'INR',1,0,'2026-07-30 18:15:13');
/*!40000 ALTER TABLE `packages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_content`
--

DROP TABLE IF EXISTS `page_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_content` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `section_key` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` json NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `section_key` (`section_key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_content`
--

LOCK TABLES `page_content` WRITE;
/*!40000 ALTER TABLE `page_content` DISABLE KEYS */;
INSERT INTO `page_content` VALUES (1,'hero','{\"title\": \"Find calm. Book your next live yoga session.\", \"eyebrow\": \"Live 1:1 & group yoga, booked in minutes\", \"tagline\": \"Flow Like a River\", \"subtitle\": \"Book certified yoga instructors for live sessions tailored to your goals — from a single drop-in class to a full monthly practice.\", \"titleHighlight\": \"yoga\"}','2026-08-23 12:32:52','2026-08-23 11:06:40');
/*!40000 ALTER TABLE `page_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `token_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_pwreset_user` (`user_id`),
  CONSTRAINT `fk_pwreset_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `gateway` enum('razorpay','stripe','paypal','wallet','upi','qr','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'upi',
  `gateway_order_id` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_txn_id` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` char(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INR',
  `status` enum('pending','success','failed','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `raw_response` json DEFAULT NULL,
  `screenshot_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_payment_booking` (`booking_id`),
  CONSTRAINT `fk_payment_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (2,5,'other','mock_0931ff0b89ea586f','mock_txn_0931ff0b89ea586f',NULL,1573.95,'INR','success',NULL,NULL,'2026-08-06 21:23:55'),(3,6,'other','mock_eb5c7facdc79ea8b','mock_txn_eb5c7facdc79ea8b',NULL,4198.95,'INR','success',NULL,NULL,'2026-08-06 21:27:44'),(4,7,'other','mock_b51a6011bb7b7a25','mock_txn_b51a6011bb7b7a25',NULL,4198.95,'INR','success',NULL,NULL,'2026-08-08 12:33:13'),(5,8,'other','mock_3c1bc1b21d5132d9','mock_txn_3c1bc1b21d5132d9',NULL,1573.95,'INR','success',NULL,NULL,'2026-08-16 18:17:10'),(6,10,'other','mock_ad1d771200a58424','mock_txn_ad1d771200a58424',NULL,14698.95,'INR','success',NULL,NULL,'2026-08-23 12:23:27'),(7,13,'razorpay','order_TeDQkKh9LIkFUc',NULL,NULL,1573.95,'INR','pending',NULL,NULL,'2026-09-20 13:23:37'),(8,13,'razorpay','order_TeDddKssC5nL5G','pay_TeDeIvv2xEI4Yw',NULL,1573.95,'INR','success',NULL,NULL,'2026-09-20 13:35:49'),(9,14,'razorpay','order_TeDffPePi1SGgX',NULL,NULL,1573.95,'INR','pending',NULL,NULL,'2026-09-20 13:37:45'),(10,15,'razorpay','order_Tl5CFu9ls8ySmN',NULL,NULL,1499.00,'INR','pending',NULL,NULL,'2026-10-07 21:53:24'),(12,17,'upi',NULL,'UPI146295171','UPI QR',1549.00,'INR','success','{\"utr\": \"UPI146295171\", \"submitted_at\": \"2026-10-17 19:22:40\"}','/assets/img/payment-qr.jpg','2026-10-17 19:22:40');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'manage_trainers','Manage trainers','admin','2026-07-23 13:56:53'),(2,'manage_clients','Manage clients','admin','2026-07-23 13:56:53'),(3,'book_session','Book a session','client','2026-07-23 13:56:53'),(4,'manage_packages','Manage packages','admin','2026-07-23 13:56:53'),(5,'manage_calendar','Manage calendar','scheduling','2026-07-23 13:56:53'),(6,'manage_own_availability','Manage own availability','scheduling','2026-07-23 13:56:53'),(7,'accept_booking','Accept or reject bookings','scheduling','2026-07-23 13:56:53'),(8,'join_live_class','Join live class','video','2026-07-23 13:56:53'),(9,'conduct_live_class','Conduct live class','video','2026-07-23 13:56:53'),(10,'view_reports','View reports','reporting','2026-07-23 13:56:53'),(11,'view_revenue','View revenue','reporting','2026-07-23 13:56:53'),(12,'manage_payment_settings','Manage payment gateway settings','admin','2026-07-23 13:56:53'),(13,'manage_website_settings','Manage website settings','admin','2026-07-23 13:56:53'),(14,'view_activity_logs','View activity logs','admin','2026-07-23 13:56:53'),(15,'manage_gallery','Manage gallery images','content','2026-07-23 13:56:53');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile_edit_history`
--

DROP TABLE IF EXISTS `profile_edit_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profile_edit_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `field_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_value` text COLLATE utf8mb4_unicode_ci,
  `new_value` text COLLATE utf8mb4_unicode_ci,
  `changed_by_user_id` bigint unsigned NOT NULL,
  `version` int unsigned NOT NULL,
  `browser` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_profileedithistory_changedby` (`changed_by_user_id`),
  KEY `idx_profileedithistory_user` (`user_id`,`version`),
  CONSTRAINT `fk_profileedithistory_changedby` FOREIGN KEY (`changed_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_profileedithistory_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile_edit_history`
--

LOCK TABLES `profile_edit_history` WRITE;
/*!40000 ALTER TABLE `profile_edit_history` DISABLE KEYS */;
INSERT INTO `profile_edit_history` VALUES (1,6,'date_of_birth','','2004-09-27',6,1,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 14:16:03'),(2,6,'height_cm','','170',6,2,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 14:16:03'),(3,6,'weight_kg','','59',6,3,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 14:16:03'),(4,6,'gender','','male',6,4,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 14:16:03'),(5,6,'city','','coimbatore',6,5,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 14:16:03'),(6,6,'timezone','','Asia/Calcutta',6,6,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 14:16:03'),(7,6,'client_category','','adult',6,7,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 14:16:03'),(8,6,'health_assessment.medicalHistory','','{\"conditions\":[\"other\"]}',6,8,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 17:19:33'),(9,6,'health_assessment.medicalHistory','{\"conditions\":[\"other\"]}','{\"conditions\":[\"other\"],\"details\":{\"other\":[]}}',6,9,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 17:36:24'),(10,7,'date_of_birth','','2004-05-24',7,1,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 18:33:27'),(11,7,'height_cm','','129',7,2,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 18:33:27'),(12,7,'weight_kg','','68',7,3,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 18:33:27'),(13,7,'gender','','male',7,4,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 18:33:27'),(14,7,'city','','coimbatore',7,5,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 18:33:27'),(15,7,'timezone','','Asia/Calcutta',7,6,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 18:33:27'),(16,7,'client_category','','adult',7,7,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 18:33:27'),(17,7,'health_assessment.medicalHistory','','{\"conditions\":[\"diabetes\",\"low_bp\"]}',7,8,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 18:34:04'),(18,7,'health_assessment.medicalHistory','{\"conditions\":[\"diabetes\",\"low_bp\"]}','{\"conditions\":[\"diabetes\",\"low_bp\"],\"details\":{\"diabetes\":{\"since\":\"1_5y\",\"medication\":true,\"doctorRestriction\":false},\"low_bp\":{\"since\":\"lt_1y\",\"medication\":false,\"doctorRestriction\":true}}}',7,9,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 18:35:51'),(19,7,'health_assessment.painAssessment','','{\"areas\":[\"shoulder\",\"back\",\"lower_back\",\"knee\",\"wrist\"]}',7,10,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 18:36:26'),(20,7,'health_assessment.painAssessment','{\"areas\":[\"shoulder\",\"back\",\"lower_back\",\"knee\",\"wrist\"]}','{\"areas\":[\"shoulder\",\"back\",\"lower_back\",\"knee\",\"wrist\"],\"details\":{\"shoulder\":[],\"back\":[],\"lower_back\":[],\"knee\":[],\"wrist\":[]}}',7,11,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-07-23 18:39:53'),(21,66,'health_assessment.medicalHistory','','{\"conditions\":[\"high_bp\",\"other\"]}',66,1,'Unknown','Desktop','UTC',NULL,'2026-08-06 21:08:52'),(22,66,'health_assessment.medicalHistory','{\"conditions\":[\"high_bp\",\"other\"]}','{\"conditions\":[\"high_bp\",\"other\"],\"details\":{\"high_bp\":{\"since\":\"lt_1y\",\"medication\":true,\"medicationTypes\":[\"blood_pressure\",\"other\"],\"medicationNotes\":\"Amlodipine 5mg daily\",\"doctorRestriction\":true,\"restrictionTypes\":[\"no_heavy_lifting\",\"avoid_high_heart_rate\"],\"restrictionNotes\":\"Keep heart rate below 130 bpm\",\"notes\":\"Monitored monthly\"}}}',66,2,'Unknown','Desktop','UTC',NULL,'2026-08-06 21:08:53'),(23,67,'phone','','9876543210',67,1,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:09:40'),(24,67,'date_of_birth','','1995-05-15',67,2,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:09:40'),(25,67,'gender','','female',67,3,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:09:40'),(26,67,'timezone','','Asia/Kolkata',67,4,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:09:40'),(27,67,'client_category','','pregnant_woman',67,5,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:09:40'),(28,67,'health_assessment.medicalHistory','','{\"conditions\":[\"high_bp\",\"other\"]}',67,6,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:09:40'),(29,67,'health_assessment.medicalHistory','{\"conditions\":[\"high_bp\",\"other\"]}','{\"conditions\":[\"high_bp\",\"other\"],\"details\":{\"high_bp\":{\"since\":\"lt_1y\",\"medication\":true,\"medicationTypes\":[\"blood_pressure\",\"other\"],\"medicationNotes\":\"Amlodipine 5mg daily\",\"doctorRestriction\":true,\"restrictionTypes\":[\"no_heavy_lifting\",\"avoid_high_heart_rate\"],\"restrictionNotes\":\"Keep heart rate below 130 bpm\",\"notes\":\"Monitored monthly\"}}}',67,7,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:09:40'),(30,67,'health_assessment.categorySafety','','{\"trimester\":\"2nd\",\"weeksPregnant\":20,\"doctorApproval\":true,\"complications\":true,\"complicationsDetails\":\"Preeclampsia history in previous pregnancy\",\"firstPregnancy\":false,\"prenatalYogaBefore\":true,\"nauseaOrDizziness\":false,\"pelvicOrBackPain\":true,\"swellingFlagged\":false}',67,8,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:09:40'),(31,6,'health_assessment.healthScreening','','[]',6,10,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-06 21:12:15'),(32,73,'health_assessment.medicalHistory','','{\"conditions\":[\"high_bp\",\"other\"]}',73,1,'Unknown','Desktop','UTC',NULL,'2026-08-06 21:13:58'),(33,73,'health_assessment.medicalHistory','{\"conditions\":[\"high_bp\",\"other\"]}','{\"conditions\":[\"high_bp\",\"other\"],\"details\":{\"high_bp\":{\"since\":\"lt_1y\",\"medication\":true,\"medicationTypes\":[\"blood_pressure\",\"other\"],\"medicationNotes\":\"Amlodipine 5mg daily\",\"doctorRestriction\":true,\"restrictionTypes\":[\"no_heavy_lifting\",\"avoid_high_heart_rate\"],\"restrictionNotes\":\"Keep heart rate below 130 bpm\",\"notes\":\"Monitored monthly\"}}}',73,2,'Unknown','Desktop','UTC',NULL,'2026-08-06 21:13:58'),(34,74,'phone','','9812153694',74,1,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:14:20'),(35,74,'date_of_birth','','1995-05-15',74,2,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:14:20'),(36,74,'gender','','female',74,3,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:14:20'),(37,74,'timezone','','Asia/Kolkata',74,4,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:14:20'),(38,74,'client_category','','pregnant_woman',74,5,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:14:20'),(39,74,'health_assessment.medicalHistory','','{\"conditions\":[\"high_bp\",\"other\"]}',74,6,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:14:20'),(40,74,'health_assessment.medicalHistory','{\"conditions\":[\"high_bp\",\"other\"]}','{\"conditions\":[\"high_bp\",\"other\"],\"details\":{\"high_bp\":{\"since\":\"lt_1y\",\"medication\":true,\"medicationTypes\":[\"blood_pressure\",\"other\"],\"medicationNotes\":\"Amlodipine 5mg daily\",\"doctorRestriction\":true,\"restrictionTypes\":[\"no_heavy_lifting\",\"avoid_high_heart_rate\"],\"restrictionNotes\":\"Keep heart rate below 130 bpm\",\"notes\":\"Monitored monthly\"}}}',74,7,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:14:20'),(41,74,'health_assessment.categorySafety','','{\"trimester\":\"2nd\",\"weeksPregnant\":20,\"doctorApproval\":true,\"complications\":true,\"complicationsDetails\":\"Preeclampsia history in previous pregnancy\",\"firstPregnancy\":false,\"prenatalYogaBefore\":true,\"nauseaOrDizziness\":false,\"pelvicOrBackPain\":true,\"swellingFlagged\":false}',74,8,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:14:20'),(42,6,'health_assessment.painAssessment','','{\"areas\":[\"no_pain\"]}',6,11,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-06 21:16:50'),(43,6,'health_assessment.doctorRestrictions','','{\"hasRestriction\":false,\"restrictionTypes\":[],\"notes\":\"\"}',6,12,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-06 21:16:56'),(44,6,'health_assessment.categorySafety','','{\"workPattern\":\"Shift work\",\"longSitting\":true,\"startingPace\":\"Moderate\",\"previousYoga\":false,\"screenHours\":\"4-8\",\"deskSetup\":false,\"sessionLength\":\"30 min\",\"caregivingLoad\":true}',6,13,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-06 21:17:26'),(45,80,'phone','','9891584843',80,1,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:22:03'),(46,80,'date_of_birth','','1995-05-15',80,2,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:22:03'),(47,80,'gender','','female',80,3,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:22:03'),(48,80,'timezone','','Asia/Kolkata',80,4,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:22:03'),(49,80,'client_category','','pregnant_woman',80,5,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:22:03'),(50,80,'health_assessment.medicalHistory','','{\"conditions\":[\"high_bp\",\"other\"]}',80,6,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:22:03'),(51,80,'health_assessment.medicalHistory','{\"conditions\":[\"high_bp\",\"other\"]}','{\"conditions\":[\"high_bp\",\"other\"],\"details\":{\"high_bp\":{\"since\":\"lt_1y\",\"medication\":true,\"medicationTypes\":[\"blood_pressure\",\"other\"],\"medicationNotes\":\"Amlodipine 5mg daily\",\"doctorRestriction\":true,\"restrictionTypes\":[\"no_heavy_lifting\",\"avoid_high_heart_rate\"],\"restrictionNotes\":\"Keep heart rate below 130 bpm\",\"notes\":\"Monitored monthly\"}}}',80,7,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:22:03'),(52,80,'health_assessment.categorySafety','','{\"trimester\":\"2nd\",\"weeksPregnant\":20,\"doctorApproval\":true,\"complications\":true,\"complicationsDetails\":\"Preeclampsia history in previous pregnancy\",\"firstPregnancy\":false,\"prenatalYogaBefore\":true,\"nauseaOrDizziness\":false,\"pelvicOrBackPain\":true,\"swellingFlagged\":false}',80,8,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:22:03'),(53,6,'health_assessment.advancedHealth','','{\"changed\":false}',6,14,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-06 21:24:38'),(54,6,'health_assessment.medication','','{\"takingMedication\":false,\"recentChange\":false}',6,15,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-06 21:24:42'),(55,6,'health_assessment.surgeries','','{\"hadSurgery\":false}',6,16,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-06 21:24:47'),(56,6,'health_assessment.advancedPain','','{\"painChange\":\"no_pain\"}',6,17,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-06 21:24:53'),(57,6,'health_assessment.fallRisk','','{\"recentFalls\":false}',6,18,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-06 21:24:56'),(58,6,'health_assessment.lifestylePreferences','','{\"classType\":\"private\",\"classTime\":\"evening\"}',6,19,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-06 21:25:03'),(59,6,'health_assessment.lifestylePreferences','{\"classTime\":\"evening\",\"classType\":\"private\"}','{\"classType\":\"private\",\"classTime\":\"evening\"}',6,20,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-06 21:25:05'),(60,86,'phone','','9863164611',86,1,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:31:56'),(61,86,'date_of_birth','','1995-05-15',86,2,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:31:56'),(62,86,'gender','','female',86,3,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:31:56'),(63,86,'timezone','','Asia/Kolkata',86,4,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:31:56'),(64,86,'client_category','','pregnant_woman',86,5,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:31:56'),(65,86,'health_assessment.medicalHistory','','{\"conditions\":[\"high_bp\",\"other\"]}',86,6,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:31:56'),(66,86,'health_assessment.medicalHistory','{\"conditions\":[\"high_bp\",\"other\"]}','{\"conditions\":[\"high_bp\",\"other\"],\"details\":{\"high_bp\":{\"since\":\"lt_1y\",\"medication\":true,\"medicationTypes\":[\"blood_pressure\",\"other\"],\"medicationNotes\":\"Amlodipine 5mg daily\",\"doctorRestriction\":true,\"restrictionTypes\":[\"no_heavy_lifting\",\"avoid_high_heart_rate\"],\"restrictionNotes\":\"Keep heart rate below 130 bpm\",\"notes\":\"Monitored monthly\"}}}',86,7,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:31:56'),(67,86,'health_assessment.categorySafety','','{\"trimester\":\"2nd\",\"weeksPregnant\":20,\"doctorApproval\":true,\"complications\":true,\"complicationsDetails\":\"Preeclampsia history in previous pregnancy\",\"firstPregnancy\":false,\"prenatalYogaBefore\":true,\"nauseaOrDizziness\":false,\"pelvicOrBackPain\":true,\"swellingFlagged\":false}',86,8,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:31:56'),(68,92,'phone','','9878430902',92,1,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:35:40'),(69,92,'date_of_birth','','1995-05-15',92,2,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:35:40'),(70,92,'gender','','female',92,3,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:35:40'),(71,92,'timezone','','Asia/Kolkata',92,4,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:35:40'),(72,92,'client_category','','pregnant_woman',92,5,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:35:40'),(73,92,'health_assessment.medicalHistory','','{\"conditions\":[\"high_bp\",\"other\"]}',92,6,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:35:40'),(74,92,'health_assessment.medicalHistory','{\"conditions\":[\"high_bp\",\"other\"]}','{\"conditions\":[\"high_bp\",\"other\"],\"details\":{\"high_bp\":{\"since\":\"lt_1y\",\"medication\":true,\"medicationTypes\":[\"blood_pressure\",\"other\"],\"medicationNotes\":\"Amlodipine 5mg daily\",\"doctorRestriction\":true,\"restrictionTypes\":[\"no_heavy_lifting\",\"avoid_high_heart_rate\"],\"restrictionNotes\":\"Keep heart rate below 130 bpm\",\"notes\":\"Monitored monthly\"}}}',92,7,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:35:40'),(75,92,'health_assessment.categorySafety','','{\"trimester\":\"2nd\",\"weeksPregnant\":20,\"doctorApproval\":true,\"complications\":true,\"complicationsDetails\":\"Preeclampsia history in previous pregnancy\",\"firstPregnancy\":false,\"prenatalYogaBefore\":true,\"nauseaOrDizziness\":false,\"pelvicOrBackPain\":true,\"swellingFlagged\":false}',92,8,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:35:41'),(76,98,'phone','','9898495315',98,1,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:42:49'),(77,98,'date_of_birth','','1995-05-15',98,2,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:42:49'),(78,98,'gender','','female',98,3,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:42:49'),(79,98,'timezone','','Asia/Kolkata',98,4,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:42:49'),(80,98,'client_category','','pregnant_woman',98,5,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:42:49'),(81,98,'health_assessment.medicalHistory','','{\"conditions\":[\"high_bp\",\"other\"]}',98,6,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:42:49'),(82,98,'health_assessment.medicalHistory','{\"conditions\":[\"high_bp\",\"other\"]}','{\"conditions\":[\"high_bp\",\"other\"],\"details\":{\"high_bp\":{\"since\":\"lt_1y\",\"medication\":true,\"medicationTypes\":[\"blood_pressure\",\"other\"],\"medicationNotes\":\"Amlodipine 5mg daily\",\"doctorRestriction\":true,\"restrictionTypes\":[\"no_heavy_lifting\",\"avoid_high_heart_rate\"],\"restrictionNotes\":\"Keep heart rate below 130 bpm\",\"notes\":\"Monitored monthly\"}}}',98,7,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:42:49'),(83,98,'health_assessment.categorySafety','','{\"trimester\":\"2nd\",\"weeksPregnant\":20,\"doctorApproval\":true,\"complications\":true,\"complicationsDetails\":\"Preeclampsia history in previous pregnancy\",\"firstPregnancy\":false,\"prenatalYogaBefore\":true,\"nauseaOrDizziness\":false,\"pelvicOrBackPain\":true,\"swellingFlagged\":false}',98,8,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:42:49'),(84,104,'phone','','9863971001',104,1,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:49:46'),(85,104,'date_of_birth','','1995-05-15',104,2,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:49:46'),(86,104,'gender','','female',104,3,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:49:46'),(87,104,'timezone','','Asia/Kolkata',104,4,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:49:46'),(88,104,'client_category','','pregnant_woman',104,5,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:49:46'),(89,104,'health_assessment.medicalHistory','','{\"conditions\":[\"high_bp\",\"other\"]}',104,6,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:49:46'),(90,104,'health_assessment.medicalHistory','{\"conditions\":[\"high_bp\",\"other\"]}','{\"conditions\":[\"high_bp\",\"other\"],\"details\":{\"high_bp\":{\"since\":\"lt_1y\",\"medication\":true,\"medicationTypes\":[\"blood_pressure\",\"other\"],\"medicationNotes\":\"Amlodipine 5mg daily\",\"doctorRestriction\":true,\"restrictionTypes\":[\"no_heavy_lifting\",\"avoid_high_heart_rate\"],\"restrictionNotes\":\"Keep heart rate below 130 bpm\",\"notes\":\"Monitored monthly\"}}}',104,7,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:49:46'),(91,104,'health_assessment.categorySafety','','{\"trimester\":\"2nd\",\"weeksPregnant\":20,\"doctorApproval\":true,\"complications\":true,\"complicationsDetails\":\"Preeclampsia history in previous pregnancy\",\"firstPregnancy\":false,\"prenatalYogaBefore\":true,\"nauseaOrDizziness\":false,\"pelvicOrBackPain\":true,\"swellingFlagged\":false}',104,8,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:49:46'),(92,110,'phone','','9818067085',110,1,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:55:00'),(93,110,'date_of_birth','','1995-05-15',110,2,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:55:00'),(94,110,'gender','','female',110,3,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:55:00'),(95,110,'timezone','','Asia/Kolkata',110,4,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:55:00'),(96,110,'client_category','','pregnant_woman',110,5,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:55:00'),(97,110,'health_assessment.medicalHistory','','{\"conditions\":[\"high_bp\",\"other\"]}',110,6,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:55:00'),(98,110,'health_assessment.medicalHistory','{\"conditions\":[\"high_bp\",\"other\"]}','{\"conditions\":[\"high_bp\",\"other\"],\"details\":{\"high_bp\":{\"since\":\"lt_1y\",\"medication\":true,\"medicationTypes\":[\"blood_pressure\",\"other\"],\"medicationNotes\":\"Amlodipine 5mg daily\",\"doctorRestriction\":true,\"restrictionTypes\":[\"no_heavy_lifting\",\"avoid_high_heart_rate\"],\"restrictionNotes\":\"Keep heart rate below 130 bpm\",\"notes\":\"Monitored monthly\"}}}',110,7,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:55:00'),(99,110,'health_assessment.categorySafety','','{\"trimester\":\"2nd\",\"weeksPregnant\":20,\"doctorApproval\":true,\"complications\":true,\"complicationsDetails\":\"Preeclampsia history in previous pregnancy\",\"firstPregnancy\":false,\"prenatalYogaBefore\":true,\"nauseaOrDizziness\":false,\"pelvicOrBackPain\":true,\"swellingFlagged\":false}',110,8,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 21:55:00'),(100,116,'phone','','9882697610',116,1,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 22:01:50'),(101,116,'date_of_birth','','1995-05-15',116,2,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 22:01:50'),(102,116,'gender','','female',116,3,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 22:01:50'),(103,116,'timezone','','Asia/Kolkata',116,4,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 22:01:50'),(104,116,'client_category','','pregnant_woman',116,5,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 22:01:50'),(105,116,'health_assessment.medicalHistory','','{\"conditions\":[\"high_bp\",\"other\"]}',116,6,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 22:01:50'),(106,116,'health_assessment.medicalHistory','{\"conditions\":[\"high_bp\",\"other\"]}','{\"conditions\":[\"high_bp\",\"other\"],\"details\":{\"high_bp\":{\"since\":\"lt_1y\",\"medication\":true,\"medicationTypes\":[\"blood_pressure\",\"other\"],\"medicationNotes\":\"Amlodipine 5mg daily\",\"doctorRestriction\":true,\"restrictionTypes\":[\"no_heavy_lifting\",\"avoid_high_heart_rate\"],\"restrictionNotes\":\"Keep heart rate below 130 bpm\",\"notes\":\"Monitored monthly\"}}}',116,7,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 22:01:50'),(107,116,'health_assessment.categorySafety','','{\"trimester\":\"2nd\",\"weeksPregnant\":20,\"doctorApproval\":true,\"complications\":true,\"complicationsDetails\":\"Preeclampsia history in previous pregnancy\",\"firstPregnancy\":false,\"prenatalYogaBefore\":true,\"nauseaOrDizziness\":false,\"pelvicOrBackPain\":true,\"swellingFlagged\":false}',116,8,'Unknown','Desktop','Asia/Kolkata',NULL,'2026-08-06 22:01:50'),(116,6,'health_assessment.fitnessBackground','','{\"level\":\"none\"}',6,21,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-08 10:50:56'),(117,6,'health_assessment.foodPreference','','{\"diet\":\"eggetarian\"}',6,22,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-08 10:51:00'),(118,6,'health_assessment.communicationPreference','','{\"channel\":\"whatsapp\"}',6,23,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-08 10:51:05'),(119,6,'health_assessment.languagePreference','','{\"language\":\"english\"}',6,24,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-08 10:51:09'),(120,6,'health_assessment.longTermGoals','','{\"primary\":\"mental_wellness\"}',6,25,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-08 10:51:13'),(121,128,'date_of_birth','','2007-12-14',128,1,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-08 12:54:25'),(122,128,'height_cm','','124',128,2,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-08 12:54:25'),(123,128,'weight_kg','','44',128,3,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-08 12:54:25'),(124,128,'gender','','male',128,4,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-08 12:54:25'),(125,128,'city','','chennai',128,5,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-08 12:54:25'),(126,128,'timezone','','Asia/Calcutta',128,6,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-08 12:54:25'),(127,128,'client_category','','child',128,7,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-08 12:54:25'),(128,129,'date_of_birth','','2026-08-14',129,1,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-16 18:12:11'),(129,129,'height_cm','','140',129,2,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-16 18:12:11'),(130,129,'weight_kg','','34',129,3,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-16 18:12:11'),(131,129,'gender','','female',129,4,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-16 18:12:11'),(132,129,'city','','thoothukkudi',129,5,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-16 18:12:11'),(133,129,'timezone','','Asia/Calcutta',129,6,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-16 18:12:11'),(134,129,'client_category','','child',129,7,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-16 18:12:11'),(135,131,'health_assessment.medicalHistory','','{\"conditions\":[\"none_of_these\"],\"otherConditionNotes\":\"\"}',131,1,'Chrome','Desktop','UTC',NULL,'2026-08-20 21:20:36'),(136,131,'health_assessment.healthScreening','','{\"hadSurgery\":false,\"takingMedication\":false,\"hadInjuries\":false,\"hasAllergies\":false,\"advisedNotToExercise\":false}',131,2,'Chrome','Desktop','UTC',NULL,'2026-08-20 21:25:08'),(137,131,'health_assessment.painAssessment','','{\"areas\":[\"no_pain\"]}',131,3,'Chrome','Desktop','UTC',NULL,'2026-08-20 21:27:11'),(138,131,'health_assessment.doctorRestrictions','','{\"hasRestriction\":false,\"restrictionTypes\":[],\"notes\":\"\"}',131,4,'Chrome','Desktop','UTC',NULL,'2026-08-20 21:28:41'),(139,129,'health_assessment.categorySafety','','{\"guardianPresent\":true,\"guardianRelationship\":\"Mother\",\"schoolSports\":true,\"growthConcerns\":true,\"previousYoga\":true,\"screenTime\":\"1-3h\",\"instructionSupport\":false,\"supportDevice\":false}',129,8,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 20:55:16'),(140,129,'health_assessment.medicalHistory','','{\"conditions\":[\"adhd_\\/_hyperactivity\"],\"otherConditionNotes\":\"\"}',129,9,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 20:55:20'),(141,129,'health_assessment.medicalHistory','{\"conditions\":[\"adhd_\\/_hyperactivity\"],\"otherConditionNotes\":\"\"}','{\"conditions\":[\"adhd_\\/_hyperactivity\"],\"otherConditionNotes\":\"\",\"details\":{\"adhd_\\/_hyperactivity\":{\"doctorRestriction\":true,\"medication\":true,\"since\":\"lt_1y\"}}}',129,10,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 20:55:28'),(142,129,'health_assessment.healthScreening','','{\"hadSurgery\":false,\"takingMedication\":false,\"hadInjuries\":false,\"hasAllergies\":false,\"advisedNotToExercise\":false}',129,11,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 20:55:38'),(143,129,'health_assessment.painAssessment','','{\"areas\":[\"knee\"]}',129,12,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 20:55:41'),(144,129,'health_assessment.painAssessment','{\"areas\":[\"knee\"]}','{\"areas\":[\"knee\"],\"details\":{\"knee\":{\"severity\":\"2\",\"side\":\"right\",\"pattern\":\"continuous\",\"walkingPain\":true,\"doctorDiagnosis\":true,\"treatment\":\"physiotherapy\"}}}',129,13,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 20:55:58'),(145,129,'health_assessment.doctorRestrictions','','{\"hasRestriction\":true,\"restrictionTypes\":[],\"notes\":\"\"}',129,14,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 20:56:04'),(146,132,'phone','','2846293748',132,1,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 21:27:17'),(147,132,'date_of_birth','','2004-09-27',132,2,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 21:27:17'),(148,132,'height_cm','','123',132,3,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 21:27:17'),(149,132,'weight_kg','','33',132,4,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 21:27:17'),(150,132,'gender','','non_binary',132,5,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 21:27:17'),(151,132,'city','','coimbatore',132,6,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 21:27:17'),(152,132,'timezone','','Asia/Calcutta',132,7,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 21:27:17'),(153,132,'client_category','','senior_citizen',132,8,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 21:27:17'),(154,132,'height_cm','123','170',132,9,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 21:29:37'),(155,132,'weight_kg','33','65',132,10,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 21:29:37'),(156,132,'gender','non_binary','male',132,11,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 21:29:37'),(157,132,'city','coimbatore','Bangalore',132,12,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 21:29:37'),(158,132,'emergency_contact_name','','Parent',132,13,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 21:29:37'),(159,132,'emergency_contact_phone','','9876543211',132,14,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 21:29:37'),(160,132,'emergency_contact_relationship','','Parent',132,15,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 21:29:37'),(161,132,'client_category','senior_citizen','adult',132,16,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-08-22 21:29:37'),(162,129,'health_assessment.advancedHealth','','{\"changed\":true}',129,15,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-09-20 13:14:04'),(163,129,'health_assessment.medication','','{\"takingMedication\":true,\"recentChange\":true}',129,16,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-09-20 13:14:09'),(164,129,'health_assessment.surgeries','','{\"hadSurgery\":true,\"howLongAgo\":\"less_than_6_months\"}',129,17,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-09-20 13:14:15'),(165,129,'health_assessment.advancedPain','','{\"painChange\":\"same\",\"newAreas\":[\"neck\"]}',129,18,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-09-20 13:14:21'),(166,129,'health_assessment.fallRisk','','{\"recentFalls\":true}',129,19,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-09-20 13:14:24'),(167,129,'health_assessment.lifestylePreferences','','{\"classTime\":\"evening\",\"classType\":\"group\"}',129,20,'Chrome','Desktop','Asia/Calcutta',NULL,'2026-09-20 13:14:28');
/*!40000 ALTER TABLE `profile_edit_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rate_limits`
--

DROP TABLE IF EXISTS `rate_limits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rate_limits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bucket_key` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hits` int unsigned NOT NULL DEFAULT '1',
  `window_started_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_rate_bucket` (`bucket_key`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rate_limits`
--

LOCK TABLES `rate_limits` WRITE;
/*!40000 ALTER TABLE `rate_limits` DISABLE KEYS */;
INSERT INTO `rate_limits` VALUES (1,'login:127.0.0.1',1,'2026-07-30 18:26:51'),(2,'login:::1',2,'2026-10-17 19:27:50'),(3,'contact:::1',2,'2026-08-06 19:40:48'),(4,'password_reset:::1',3,'2026-08-16 18:24:33');
/*!40000 ALTER TABLE `rate_limits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `receipts`
--

DROP TABLE IF EXISTS `receipts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `receipts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `invoice_number` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pdf_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permissions` (
  `role_id` bigint unsigned NOT NULL,
  `permission_id` bigint unsigned NOT NULL,
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
INSERT INTO `role_permissions` VALUES (1,1),(1,2),(3,3),(1,4),(1,5),(2,5),(2,6),(2,7),(1,8),(2,8),(3,8),(2,9),(1,10),(2,10),(1,11),(2,11),(1,12),(1,13),(1,14),(1,15),(2,15);
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','Super Admin','2026-07-23 13:56:53'),(2,'instructor','Trainer','2026-07-23 13:56:53'),(3,'client','Client','2026-07-23 13:56:53');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_types`
--

DROP TABLE IF EXISTS `service_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `icon` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` smallint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_types`
--

LOCK TABLES `service_types` WRITE;
/*!40000 ALTER TABLE `service_types` DISABLE KEYS */;
INSERT INTO `service_types` VALUES (1,'one-to-one','One to One Session','Private personalized yoga session with a dedicated instructor.','user',1,1),(2,'group','Group Session','Live group class with other practitioners.','users',1,2),(3,'weekly','Weekly Package','A structured weekly plan of recurring sessions.','calendar-week',1,3),(4,'monthly','Monthly Package','A full month of guided practice.','calendar-month',1,4),(5,'custom','Custom Package','Tailored session count and duration, built around your goals.','sliders',1,5);
/*!40000 ALTER TABLE `service_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `session_feedback`
--

DROP TABLE IF EXISTS `session_feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `session_feedback` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `instructor_id` bigint unsigned NOT NULL,
  `rating` tinyint unsigned DEFAULT NULL,
  `feedback_text` text COLLATE utf8mb4_unicode_ci,
  `session_notes` text COLLATE utf8mb4_unicode_ci,
  `recommendation` text COLLATE utf8mb4_unicode_ci,
  `homework` text COLLATE utf8mb4_unicode_ci,
  `attendance` enum('present','absent') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_feedback_booking` (`booking_id`),
  KEY `fk_feedback_client` (`client_id`),
  KEY `fk_feedback_instructor` (`instructor_id`),
  CONSTRAINT `fk_feedback_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_feedback_client` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_feedback_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `session_feedback`
--

LOCK TABLES `session_feedback` WRITE;
/*!40000 ALTER TABLE `session_feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `session_feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `session_resources`
--

DROP TABLE IF EXISTS `session_resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `session_resources` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `instructor_id` bigint unsigned NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `setting_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES ('default_currency','INR','2026-07-23 13:56:53'),('default_language','en','2026-08-23 11:20:38'),('default_timezone','Asia/Kolkata','2026-07-23 13:56:53'),('google_client_id','info@vipasayoga.com','2026-10-07 22:53:20'),('google_client_secret','dummy_client_secret_123','2026-10-07 22:55:36'),('google_redirect_uri','http://localhost:8000/auth/google/callback','2026-10-07 22:53:20'),('mail_driver','log','2026-10-07 22:59:54'),('mail_from_address','info@vipasayoga.com','2026-10-07 22:59:54'),('mail_from_name','Vipasa Yoga','2026-10-07 22:59:54'),('mail_host','','2026-10-07 22:59:54'),('mail_password','S@njusai1010','2026-10-07 22:59:54'),('mail_port','587','2026-10-07 22:59:54'),('mail_username','info@vipasayoga.com','2026-10-07 22:59:54'),('payment_gateway','upi_qr','2026-10-17 18:12:39'),('payment_mode','live','2026-09-20 14:09:42'),('razorpay_key_id','rzp_test_TdvRNoZtuiKqrB','2026-09-20 12:51:25'),('razorpay_key_secret','raOkI5CkWNrc1qJJn0MeS2hO','2026-09-20 12:51:25'),('session_lifetime_minutes','120','2026-10-07 23:01:43'),('site_name','Vipasa Yoga','2026-07-23 13:56:53'),('social_facebook','','2026-07-29 20:55:59'),('social_instagram','','2026-07-29 20:55:59'),('social_twitter','https://x.com/vipasayoga','2026-10-07 22:50:57'),('social_youtube','','2026-07-29 20:55:59'),('stripe_public_key','','2026-10-07 22:57:54'),('tax_percent','5','2026-07-23 13:56:53'),('upi_id','vijayaparameshh@oksbi','2026-10-17 18:12:39'),('upi_name','Vijaya Parameswaran','2026-10-17 18:12:39'),('upi_phone','+91 70127 75990','2026-10-17 18:12:39'),('upi_qr_image','/assets/img/payment-qr.jpg','2026-10-17 18:12:39');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('client','instructor','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client',
  `status` enum('active','suspended','pending','test') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `email_verified_at` datetime DEFAULT NULL,
  `phone_verified_at` datetime DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone` (`phone`),
  UNIQUE KEY `google_id` (`google_id`),
  KEY `idx_users_role` (`role`),
  KEY `idx_users_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Vipasa Admin','info@vipasayoga.com',NULL,NULL,'$2y$10$YDi4QkrIitFNTpE4.HuODuflPN3ThjjVYZ01CFKuJYZuG88bsnY4G','admin','active','2026-07-23 13:56:53',NULL,NULL,'2026-10-17 19:28:07','2026-07-23 13:56:53','2026-10-17 19:28:07'),(2,'Ananya Iyer','ananya.iyer@vipasa.demo','+919810000001',NULL,'$2y$10$Kz6Wp9YJ0CgyqWdVruXWJ.NQTV5wNf.DsLkoQF.vBH/EO5PCwUgA.','instructor','active','2026-07-23 13:56:53','2026-07-23 13:56:53',NULL,'2026-10-07 21:56:05','2026-07-23 13:56:53','2026-10-07 21:56:05'),(3,'Rahul Menon','rahul.menon@vipasa.demo','+919810000002',NULL,'$2y$10$jFWFOehUSdjsD0Amd6yxyem2PRINUVBZJAoQf/hEIJ5QfawM3RoEu','instructor','active','2026-07-23 13:56:53','2026-07-23 13:56:53',NULL,NULL,'2026-07-23 13:56:53','2026-07-23 13:56:53'),(4,'Sarah Thomas','sarah.thomas@vipasa.demo','+919810000003',NULL,'$2y$10$jFWFOehUSdjsD0Amd6yxyem2PRINUVBZJAoQf/hEIJ5QfawM3RoEu','instructor','active','2026-07-23 13:56:53','2026-07-23 13:56:53',NULL,NULL,'2026-07-23 13:56:53','2026-07-23 13:56:53'),(5,'Vijaya Parameswaran','vijaya.parameswaran@gmail.com',NULL,NULL,'$2y$10$iSQIFN00Piu8pgHscCQDDuI/6pA5tQwqnVtGHlVc1lqng6XHKwigW','instructor','active','2026-07-23 13:56:53',NULL,NULL,'2026-08-23 12:29:42','2026-07-23 13:56:53','2026-08-23 12:29:42'),(6,'lakshu','lakshmanan@gmail.com','7904870129',NULL,'$2y$10$MN9/d.RTv691aHx/l9ZguuYvpoA23kjCvvbwCr5AIRplwWcmHfOOe','client','active',NULL,NULL,NULL,'2026-10-07 21:56:06','2026-07-23 14:15:21','2026-10-07 21:56:06'),(7,'siva','siva@gmail.com','123456789',NULL,'$2y$10$CxWPUH.Jy5g9FOJu4IGvo.Pm2DWv2tzj99Ok9IUX7KH.ZxRkTGkd.','client','active',NULL,NULL,NULL,'2026-07-23 18:32:25','2026-07-23 18:32:25','2026-07-23 18:32:25'),(10,'QA Automated Client','qa_client_1786025578@vipasa.demo',NULL,NULL,'$2y$10$wb7/7ykEu3/0TbNTKrCkaejojD/ybVf3xS2jKzXbVqDa3UHVwxEEG','client','active',NULL,NULL,NULL,'2026-08-06 19:42:58','2026-08-06 19:42:58','2026-08-06 19:42:58'),(11,'QA Automated Client','qa_client_1786025633@vipasa.demo',NULL,NULL,'$2y$10$HYUS98ZhIXF1AAZch2pj3O8KtNGmFrU5HiAP.nWln4IRNPSuTN1UO','client','active',NULL,NULL,NULL,'2026-08-06 19:43:53','2026-08-06 19:43:53','2026-08-06 19:43:53'),(12,'QA Automated Client','qa_client_1786025659@vipasa.demo',NULL,NULL,'$2y$10$ZWg1pH6cwGm5mpoGlUcJR.1BljmcSDktVNW1ktTFFswzZZ/EdEUxm','client','active',NULL,NULL,NULL,'2026-08-06 19:44:20','2026-08-06 19:44:20','2026-08-06 19:44:20'),(13,'QA Automated Client','qa_client_1786025665@vipasa.demo',NULL,NULL,'$2y$10$Jt2k2UBtFJhO3s6L1qdoluXDgncToUfZtMGe6Lhrg3c9qdyfi29ea','client','active',NULL,NULL,NULL,'2026-08-06 19:44:25','2026-08-06 19:44:25','2026-08-06 19:44:25'),(14,'QA Automated Client','qa_client_1786025670@vipasa.demo',NULL,NULL,'$2y$10$MxG66B/Vx5tDSPhe8M7pseieQ8zuuZUtxoeE3ZixqOcmEQu0hPTdy','client','active',NULL,NULL,NULL,'2026-08-06 19:44:31','2026-08-06 19:44:31','2026-08-06 19:44:31'),(15,'QA Automated Client','qa_client_1786025676@vipasa.demo',NULL,NULL,'$2y$10$sUBqXikih7k4DSrHMe8DbOZxd8YWOGXSkixdE5XqQ.rG5D/sVj2Xq','client','active',NULL,NULL,NULL,'2026-08-06 19:44:36','2026-08-06 19:44:36','2026-08-06 19:44:36'),(16,'QA Automated Client','qa_client_1786025682@vipasa.demo',NULL,NULL,'$2y$10$zYGZ2NLX8KjiqYZlLj6Ncu3uaqZAI5wD3UNjjUghq1eOAgf6S9fdi','client','active',NULL,NULL,NULL,'2026-08-06 19:44:42','2026-08-06 19:44:42','2026-08-06 19:44:42'),(17,'Lakshu','lakshu@gmail.com','12345675',NULL,'$2y$10$OEDZfFatNMvoCh2f7jMp9exwG5ggP1.INhKZDZnZUBsSS8hfClLdu','instructor','active',NULL,NULL,NULL,'2026-08-09 19:26:26','2026-08-06 19:46:50','2026-08-09 19:26:26'),(18,'QA Automated Client','qa_client_1786026077@vipasa.demo',NULL,NULL,'$2y$10$fqzIJ/qe84XQ8D/1QHHvi.A9och.jS2VTQMI7u6cEw.Paql4Ilp4O','client','active',NULL,NULL,NULL,'2026-08-06 19:51:18','2026-08-06 19:51:17','2026-08-06 19:51:18'),(19,'QA Automated Client','qa_client_1786026083@vipasa.demo',NULL,NULL,'$2y$10$lkFe3tVTlmJhPKmh6reuAu7Llp0tKAFf7q9LTPlLUIp3/qZGhaN7q','client','active',NULL,NULL,NULL,'2026-08-06 19:51:23','2026-08-06 19:51:23','2026-08-06 19:51:23'),(20,'QA Automated Client','qa_client_1786026089@vipasa.demo',NULL,NULL,'$2y$10$5vSuol9vLa7Vz6vrq8xwOu7m.8CMgjY.LIuPY5vzAMltRxqPcZT5O','client','active',NULL,NULL,NULL,'2026-08-06 19:51:29','2026-08-06 19:51:29','2026-08-06 19:51:29'),(21,'QA Automated Client','qa_client_1786026095@vipasa.demo',NULL,NULL,'$2y$10$ypcHhJo3pLO1xV7UOBOK0.QMoPJv4AL8Vdahu8AuhfEz4XXk3qAO.','client','active',NULL,NULL,NULL,'2026-08-06 19:51:35','2026-08-06 19:51:35','2026-08-06 19:51:35'),(22,'QA Automated Client','qa_client_1786026100@vipasa.demo',NULL,NULL,'$2y$10$uSOZv8I9H0gh.ZkApwhWuuYYsgJjP524Ij/A7MpQZtS8bsYVIgTs6','client','active',NULL,NULL,NULL,'2026-08-06 19:51:41','2026-08-06 19:51:41','2026-08-06 19:51:41'),(23,'QA Automated Client','qa_client_1786026250@vipasa.demo',NULL,NULL,'$2y$10$AtV4n7d0fCCrcFqSVPc8lu5kBos4YZ48KJebiibFtCC0FP5.UX7IC','client','active',NULL,NULL,NULL,'2026-08-06 19:54:10','2026-08-06 19:54:10','2026-08-06 19:54:10'),(24,'QA Automated Client','qa_client_1786026256@vipasa.demo',NULL,NULL,'$2y$10$X8rTXgAEuFWCZT0z5GH.1O63Gy4Rst0SCIvfoU8AiS0ZW6G9mfH6O','client','active',NULL,NULL,NULL,'2026-08-06 19:54:16','2026-08-06 19:54:16','2026-08-06 19:54:16'),(25,'QA Automated Client','qa_client_1786026261@vipasa.demo',NULL,NULL,'$2y$10$1KcGX2qmFJjxsvSlyEkQeuYMzS6HLunLmLvQ5QuAzwJGIVlO9w7h6','client','active',NULL,NULL,NULL,'2026-08-06 19:54:21','2026-08-06 19:54:21','2026-08-06 19:54:21'),(26,'QA Automated Client','qa_client_1786026267@vipasa.demo',NULL,NULL,'$2y$10$i86uTzI5iKKjqzAUOhOqZurypbe/ag/W49QhDNGlH4qOCWallgaGq','client','active',NULL,NULL,NULL,'2026-08-06 19:54:27','2026-08-06 19:54:27','2026-08-06 19:54:27'),(27,'QA Automated Client','qa_client_1786026273@vipasa.demo',NULL,NULL,'$2y$10$yRL9FL2YqrvHcNEJnWptsOfVQc10hCx8T1UfCgMYsAhKy22ZBCNHS','client','active',NULL,NULL,NULL,'2026-08-06 19:54:33','2026-08-06 19:54:33','2026-08-06 19:54:33'),(28,'QA Automated Client','qa_client_1786026533@vipasa.demo',NULL,NULL,'$2y$10$tT0h8VgMLuWSE.5PwEzUzuIjvppiovEmOpUPR8tC2UoB2p80C5WoK','client','active',NULL,NULL,NULL,'2026-08-06 19:58:54','2026-08-06 19:58:54','2026-08-06 19:58:54'),(29,'QA Automated Client','qa_client_1786026539@vipasa.demo',NULL,NULL,'$2y$10$t6lTgN0aqNhchgF3r2Hd0eeLcIMt6wcQxitEFB3F/MTN0fwkENqKe','client','active',NULL,NULL,NULL,'2026-08-06 19:58:59','2026-08-06 19:58:59','2026-08-06 19:58:59'),(30,'QA Automated Client','qa_client_1786026545@vipasa.demo',NULL,NULL,'$2y$10$Wzq31qDZLTDom/zVspQf2.FMpj5uQxbgziiMKIrOyk4jR57P8WGqu','client','active',NULL,NULL,NULL,'2026-08-06 19:59:05','2026-08-06 19:59:05','2026-08-06 19:59:05'),(31,'QA Automated Client','qa_client_1786026550@vipasa.demo',NULL,NULL,'$2y$10$VCnXbzQyQCNSyTeBUnveyeqE3PX7oxXnxLIbj64YrduPUU1oSLdTG','client','active',NULL,NULL,NULL,'2026-08-06 19:59:10','2026-08-06 19:59:10','2026-08-06 19:59:10'),(32,'QA Automated Client','qa_client_1786026556@vipasa.demo',NULL,NULL,'$2y$10$JPj2fIqmNIEzuX.Q5ptm8uxecJGdcxBSbDI.Q/BH5x6Jq1yrXa8Em','client','active',NULL,NULL,NULL,'2026-08-06 19:59:16','2026-08-06 19:59:16','2026-08-06 19:59:16'),(33,'QA Automated Client','qa_client_1786026715@vipasa.demo',NULL,NULL,'$2y$10$i1gWTnKhTixGEka/lchDK.pr.wNRY1cqIHTKjBsOCUei1zrXJCj8m','client','active',NULL,NULL,NULL,'2026-08-06 20:01:56','2026-08-06 20:01:56','2026-08-06 20:01:56'),(34,'QA Automated Client','qa_client_1786026721@vipasa.demo',NULL,NULL,'$2y$10$nWxyX7m6eospmH.uWwosGeIUQ7TesKBkmGzxsjzWur4qbV7wRHLWG','client','active',NULL,NULL,NULL,'2026-08-06 20:02:01','2026-08-06 20:02:01','2026-08-06 20:02:01'),(35,'QA Automated Client','qa_client_1786026727@vipasa.demo',NULL,NULL,'$2y$10$OpBuQ5Lk9WgHkx1.vMWvQutv2lyXhVVPVazWzFJyusJVA8Vc8ixLG','client','active',NULL,NULL,NULL,'2026-08-06 20:02:07','2026-08-06 20:02:07','2026-08-06 20:02:07'),(36,'QA Automated Client','qa_client_1786026732@vipasa.demo',NULL,NULL,'$2y$10$L0PRGjGO5VgHrZdPt9DL3O1U9e0/rEZzTTnx8vT5eKR5x.XfjDHXq','client','active',NULL,NULL,NULL,'2026-08-06 20:02:12','2026-08-06 20:02:12','2026-08-06 20:02:12'),(37,'QA Automated Client','qa_client_1786026738@vipasa.demo',NULL,NULL,'$2y$10$/KaL0bhKsZwKw2k85mJt7umb4O/s.UNWtNyZLLcoZwUh8W3LsZQBm','client','active',NULL,NULL,NULL,'2026-08-06 20:02:18','2026-08-06 20:02:18','2026-08-06 20:02:18'),(38,'QA Client In-Dash','qa_client_detail_1786027357@vipasa.demo',NULL,NULL,'$2y$10$8T4g2N7jn/Jv8H/g4gdDuuLAZWevRIzG8/6sCi.nsiItSIcoYOx66','client','active',NULL,NULL,NULL,'2026-08-06 20:12:38','2026-08-06 20:12:38','2026-08-06 20:12:38'),(39,'QA Client In-Dash','qa_client_detail_1786027482@vipasa.demo',NULL,NULL,'$2y$10$oMsnKtLqZAU3hi09AsV2uu/LneaS9OB71DM7413hPNVoElstbitsC','client','active',NULL,NULL,NULL,'2026-08-06 20:14:42','2026-08-06 20:14:42','2026-08-06 20:14:42'),(40,'QA Client In-Dash','qa_client_detail_1786028182@vipasa.demo',NULL,NULL,'$2y$10$tzfjatwYNVaYMY6eLBxNsexVqaRFj/sBnTG7dRZ/oaiObRLn2JNR6','client','active',NULL,NULL,NULL,'2026-08-06 20:26:22','2026-08-06 20:26:22','2026-08-06 20:26:22'),(41,'QA Automated Client','qa_client_1786028192@vipasa.demo',NULL,NULL,'$2y$10$CNYKdlcK11zXdAl9hW7OhOzt1mRfIaCXc8tqlanttwpDgVJzbybtW','client','active',NULL,NULL,NULL,'2026-08-06 20:26:32','2026-08-06 20:26:32','2026-08-06 20:26:32'),(42,'QA Automated Client','qa_client_1786028198@vipasa.demo',NULL,NULL,'$2y$10$KRfYgZELn6qIKwvkpFO/vuDj78Wi5eFsr9nDCbhqaGcXlWVEgKqqO','client','active',NULL,NULL,NULL,'2026-08-06 20:26:38','2026-08-06 20:26:38','2026-08-06 20:26:38'),(43,'QA Automated Client','qa_client_1786028203@vipasa.demo',NULL,NULL,'$2y$10$piJGwYtytjm5RGJGeZzPtORXGOMCU0lzYtGCy/otbci3q12QYlXaK','client','active',NULL,NULL,NULL,'2026-08-06 20:26:43','2026-08-06 20:26:43','2026-08-06 20:26:43'),(44,'QA Automated Client','qa_client_1786028209@vipasa.demo',NULL,NULL,'$2y$10$kBKU66E7Nk/lwAVr3.rgEeHMoIdq3cJRvNaPMDA/FfZC.Bkw/11dS','client','active',NULL,NULL,NULL,'2026-08-06 20:26:49','2026-08-06 20:26:49','2026-08-06 20:26:49'),(45,'QA Automated Client','qa_client_1786028215@vipasa.demo',NULL,NULL,'$2y$10$4XZ882Xv.3Wrvxx8F7CYo.zvefGUwGJE.DLKM.1VxplDPKQSFMaUO','client','active',NULL,NULL,NULL,'2026-08-06 20:26:55','2026-08-06 20:26:55','2026-08-06 20:26:55'),(46,'QA Client UI','qa_client_ui_1786028835@vipasa.demo',NULL,NULL,'$2y$10$xiOhCxQjLFSmgrB2IUKUquZl0RpZGMbsA/l29Toff/W35mfTo4jPK','client','active',NULL,NULL,NULL,'2026-08-06 20:37:15','2026-08-06 20:37:15','2026-08-06 20:37:15'),(47,'QA Automated Client','qa_client_1786029003@vipasa.demo',NULL,NULL,'$2y$10$1FSKMwP0PMbLAslWx0eqpeooWnNLe6d7CPUzhsg.MMt/hME7s0gyS','client','active',NULL,NULL,NULL,'2026-08-06 20:40:04','2026-08-06 20:40:04','2026-08-06 20:40:04'),(48,'QA Automated Client','qa_client_1786029009@vipasa.demo',NULL,NULL,'$2y$10$Ah52Eoz7ktwtlJytAr2UBu5tk5gldQvT37l1ViCqbVXd5457HXM6W','client','active',NULL,NULL,NULL,'2026-08-06 20:40:09','2026-08-06 20:40:09','2026-08-06 20:40:09'),(49,'QA Automated Client','qa_client_1786029014@vipasa.demo',NULL,NULL,'$2y$10$Wgo8vcBKC27K6vO56KMLkeIgaE0RaGzKe0aqQTbhZQdZ9n1a9GHRq','client','active',NULL,NULL,NULL,'2026-08-06 20:40:15','2026-08-06 20:40:15','2026-08-06 20:40:15'),(50,'QA Automated Client','qa_client_1786029020@vipasa.demo',NULL,NULL,'$2y$10$t4wNc5TWMs0UqjajPOxDj.0.E5c3k1ZkjBIJNLHZtx2l/mf2YgAMe','client','active',NULL,NULL,NULL,'2026-08-06 20:40:20','2026-08-06 20:40:20','2026-08-06 20:40:20'),(51,'QA Automated Client','qa_client_1786029026@vipasa.demo',NULL,NULL,'$2y$10$3XU4DYxmZoKAdg5selaHTOpDrdLkOSBX0uFvCFMJSmOj7WTyqln8.','client','active',NULL,NULL,NULL,'2026-08-06 20:40:26','2026-08-06 20:40:26','2026-08-06 20:40:26'),(52,'QA In-Dash Client','qa_client_indash_1786029601@vipasa.demo',NULL,NULL,'$2y$10$vMaJ9B5nDjaO8F0IsErNW.boWonsmlet1/pt/DQ8fPqNURFgYHZMi','client','active',NULL,NULL,NULL,'2026-08-06 20:50:02','2026-08-06 20:50:02','2026-08-06 20:50:02'),(53,'QA In-Dash Client','qa_client_indash_1786029610@vipasa.demo',NULL,NULL,'$2y$10$6PrZtcN/9JuSgdVXx9QtxuGZsQi586OsjaLFm.ZSpGu2xPKKQAK9u','client','active',NULL,NULL,NULL,'2026-08-06 20:50:10','2026-08-06 20:50:10','2026-08-06 20:50:10'),(54,'QA In-Dash Client','qa_client_indash_1786029680@vipasa.demo',NULL,NULL,'$2y$10$TpGOcTv0NAGu/plb2RbjG.HPpDkb7/P/.FhfB4bjeO1FC.SYNiWV.','client','active',NULL,NULL,NULL,'2026-08-06 20:51:21','2026-08-06 20:51:21','2026-08-06 20:51:21'),(55,'QA Automated Client','qa_client_1786029693@vipasa.demo',NULL,NULL,'$2y$10$9.e7BZIFn4V3bXm75SBnHetp1N3St6R9VjA8nwyJGG1wbKOfx4w2K','client','active',NULL,NULL,NULL,'2026-08-06 20:51:33','2026-08-06 20:51:33','2026-08-06 20:51:33'),(56,'QA Automated Client','qa_client_1786029698@vipasa.demo',NULL,NULL,'$2y$10$B2dTdqF5YNtamYSfZxQrM.zNydusqwHh0TxOcBdtXwu9zFo9ctWsa','client','active',NULL,NULL,NULL,'2026-08-06 20:51:38','2026-08-06 20:51:38','2026-08-06 20:51:38'),(57,'QA Automated Client','qa_client_1786029703@vipasa.demo',NULL,NULL,'$2y$10$gqPGkPhsGV.XwqlKREvPVuFAoAODdPu0Ivyc9NZyitnXOwe7awd6C','client','active',NULL,NULL,NULL,'2026-08-06 20:51:44','2026-08-06 20:51:44','2026-08-06 20:51:44'),(58,'QA Automated Client','qa_client_1786029709@vipasa.demo',NULL,NULL,'$2y$10$rb6LOZgEbhAfiwsQ5aRUdeIrxVTEVsLUUjbOvHsJPeDzQEhn1HpsW','client','active',NULL,NULL,NULL,'2026-08-06 20:51:50','2026-08-06 20:51:50','2026-08-06 20:51:50'),(59,'QA Automated Client','qa_client_1786029715@vipasa.demo',NULL,NULL,'$2y$10$gbjQgv902QpFrk70Ovbua.xw74vG2Ukq2vdxjRmU3/EpsWlrBIEoq','client','active',NULL,NULL,NULL,'2026-08-06 20:51:55','2026-08-06 20:51:55','2026-08-06 20:51:55'),(60,'QA In-Dash Client','qa_client_indash_1786030142@vipasa.demo',NULL,NULL,'$2y$10$7h3.pt0ltMpwv73dbYYzbuz5XKQRPo/vR8LASAT6p9I1a3354zywy','client','active',NULL,NULL,NULL,'2026-08-06 20:59:02','2026-08-06 20:59:02','2026-08-06 20:59:02'),(61,'QA Automated Client','qa_client_1786030156@vipasa.demo',NULL,NULL,'$2y$10$W.SERXZPF6XY3.VXcGfvq.ijHW0tutyenzEEwiQQaob3tdxLdOlhe','client','active',NULL,NULL,NULL,'2026-08-06 20:59:16','2026-08-06 20:59:16','2026-08-06 20:59:16'),(62,'QA Automated Client','qa_client_1786030161@vipasa.demo',NULL,NULL,'$2y$10$gHXAOKf6GTngHGe2jVqFteUdC6.uuiuQH84VPAHsNYCjKrTM/1lrO','client','active',NULL,NULL,NULL,'2026-08-06 20:59:21','2026-08-06 20:59:21','2026-08-06 20:59:21'),(63,'QA Automated Client','qa_client_1786030167@vipasa.demo',NULL,NULL,'$2y$10$L43Ts2bT8hcLbSf4YIO1JOmFm6iBMp2t4uxFP0XTASO2ljJOQ7Wce','client','active',NULL,NULL,NULL,'2026-08-06 20:59:27','2026-08-06 20:59:27','2026-08-06 20:59:27'),(64,'QA Automated Client','qa_client_1786030173@vipasa.demo',NULL,NULL,'$2y$10$kunLsC5OJuGAZ6NwEbOamejjSaJ4zS9YO1J7zBbVKrr/HiJx8o0bK','client','active',NULL,NULL,NULL,'2026-08-06 20:59:33','2026-08-06 20:59:33','2026-08-06 20:59:33'),(65,'QA Automated Client','qa_client_1786030178@vipasa.demo',NULL,NULL,'$2y$10$Ja6Byw3akwVQVsd3l2uCo.u5r0qu.maAlye2As4dBy.u.rezsul4u','client','active',NULL,NULL,NULL,'2026-08-06 20:59:38','2026-08-06 20:59:38','2026-08-06 20:59:38'),(66,'QA Pregnant Client','qa_pregnant_dyn_1786030732@vipasa.demo',NULL,NULL,'$2y$10$qNX7/p/SFcbgOjXvrbWS8exGK0P//F4Gups7nhrMWjFahhZDFYEpi','client','active',NULL,NULL,NULL,'2026-08-06 21:08:52','2026-08-06 21:08:52','2026-08-06 21:08:52'),(67,'QA Pregnant Client','qa_pregnant_dyn_1786030780@vipasa.demo','9876543210',NULL,'$2y$10$FERkFbRZ1DkInYZJ1WM8ZeaIKgyRUTtPYhIsOaxwZyXk4GevnUyMi','client','active',NULL,NULL,NULL,'2026-08-06 21:09:40','2026-08-06 21:09:40','2026-08-06 21:09:40'),(68,'QA Automated Client','qa_client_1786030787@vipasa.demo',NULL,NULL,'$2y$10$J/CVo4RKcNfS40sF0KcKDe6qQWw.TPdUeH3TwyrtG87P2neWofrSW','client','active',NULL,NULL,NULL,'2026-08-06 21:09:47','2026-08-06 21:09:47','2026-08-06 21:09:47'),(69,'QA Automated Client','qa_client_1786030792@vipasa.demo',NULL,NULL,'$2y$10$tF3pskPjqofuikhcNVQggupF0Sm54jN1vYyzXUJ76MZOuodiJht0K','client','active',NULL,NULL,NULL,'2026-08-06 21:09:52','2026-08-06 21:09:52','2026-08-06 21:09:52'),(70,'QA Automated Client','qa_client_1786030797@vipasa.demo',NULL,NULL,'$2y$10$MOS/eTDoy7KPJ9LuX6K.5.Mm3AUhaBqhhlF0GmnP4o2J1BnSVUmGq','client','active',NULL,NULL,NULL,'2026-08-06 21:09:57','2026-08-06 21:09:57','2026-08-06 21:09:57'),(71,'QA Automated Client','qa_client_1786030803@vipasa.demo',NULL,NULL,'$2y$10$XTmGNKq9tY5H657A0lZTLO4WDepJ5ruxgJddwYfMIcS8eMA4js5vi','client','active',NULL,NULL,NULL,'2026-08-06 21:10:03','2026-08-06 21:10:03','2026-08-06 21:10:03'),(72,'QA Automated Client','qa_client_1786030808@vipasa.demo',NULL,NULL,'$2y$10$Mdf5i4FPJKLDOuh0Jon22O4tZ6fzCBi1A6y5KOkZdcks/dRq7XjNa','client','active',NULL,NULL,NULL,'2026-08-06 21:10:08','2026-08-06 21:10:08','2026-08-06 21:10:08'),(73,'QA Pregnant Client','qa_pregnant_dyn_1786031037@vipasa.demo',NULL,NULL,'$2y$10$1J1eTcxZghiDn/Y5EE8Kl.JM.9Z0madwAYZlEZYD4wbPq7DDgZsOG','client','active',NULL,NULL,NULL,'2026-08-06 21:13:58','2026-08-06 21:13:58','2026-08-06 21:13:58'),(74,'QA Pregnant Client','qa_pregnant_dyn_1786031060@vipasa.demo','9812153694',NULL,'$2y$10$WSpUCOxW8jwveix4uaB1DeIyc2D8X9tcDsFbyXcX9icontfDv5AQe','client','active',NULL,NULL,NULL,'2026-08-06 21:14:20','2026-08-06 21:14:20','2026-08-06 21:14:20'),(75,'QA Automated Client','qa_client_1786031071@vipasa.demo',NULL,NULL,'$2y$10$7kzwTb4h0y1Y9woYrkJqaOm370f6EDrA.vA0NExmL3t5Eps91aJ8q','client','active',NULL,NULL,NULL,'2026-08-06 21:14:31','2026-08-06 21:14:31','2026-08-06 21:14:31'),(76,'QA Automated Client','qa_client_1786031076@vipasa.demo',NULL,NULL,'$2y$10$odxk7yoHg7OcmlDAHRKQtukaDqGvXonF2wzxoYeMEFRpTRQYpuQeS','client','active',NULL,NULL,NULL,'2026-08-06 21:14:36','2026-08-06 21:14:36','2026-08-06 21:14:36'),(77,'QA Automated Client','qa_client_1786031081@vipasa.demo',NULL,NULL,'$2y$10$Csf3hHzTy2TZlS9902I5DOIu9racf9CabQRM0Lc2JiF9m/HY7VvMm','client','active',NULL,NULL,NULL,'2026-08-06 21:14:41','2026-08-06 21:14:41','2026-08-06 21:14:41'),(78,'QA Automated Client','qa_client_1786031086@vipasa.demo',NULL,NULL,'$2y$10$vTFUx0bTe9FFT4wwIp08o.igNcEtthp/lPFlmwyB9RdZT1ZD419w2','client','active',NULL,NULL,NULL,'2026-08-06 21:14:46','2026-08-06 21:14:46','2026-08-06 21:14:46'),(79,'QA Automated Client','qa_client_1786031092@vipasa.demo',NULL,NULL,'$2y$10$9Znf8xXW5uVWIVm7Ty2v/u4lBEEcuWOQnH81memPei8ZLMLgVO2R2','client','active',NULL,NULL,NULL,'2026-08-06 21:14:52','2026-08-06 21:14:52','2026-08-06 21:14:52'),(80,'QA Pregnant Client','qa_pregnant_dyn_1786031523@vipasa.demo','9891584843',NULL,'$2y$10$sXRup2FtftZtEC/IV6pv1OiUYIPfAMetzlJQDdUZw1bhVQ9cUs9GG','client','active',NULL,NULL,NULL,'2026-08-06 21:22:03','2026-08-06 21:22:03','2026-08-06 21:22:03'),(81,'QA Automated Client','qa_client_1786031540@vipasa.demo',NULL,NULL,'$2y$10$3dFv7u955Hctp3DC8cvzP.gHVXgMzFL.kFb/ppRAzAPZRlDKb9jFG','client','active',NULL,NULL,NULL,'2026-08-06 21:22:20','2026-08-06 21:22:20','2026-08-06 21:22:20'),(82,'QA Automated Client','qa_client_1786031545@vipasa.demo',NULL,NULL,'$2y$10$ElAH3hxs/c1KbDPr54KHEOzM6Mf0fDiKkgnKf7GV1Kyw5pzSGSXdW','client','active',NULL,NULL,NULL,'2026-08-06 21:22:25','2026-08-06 21:22:25','2026-08-06 21:22:25'),(83,'QA Automated Client','qa_client_1786031550@vipasa.demo',NULL,NULL,'$2y$10$2a7zCIWLcAjnMj1ruFZESu8DCnhRd4Z8dEuXdx3QdalOCDnkWhOVO','client','active',NULL,NULL,NULL,'2026-08-06 21:22:30','2026-08-06 21:22:30','2026-08-06 21:22:30'),(84,'QA Automated Client','qa_client_1786031556@vipasa.demo',NULL,NULL,'$2y$10$Rs5tse9jjEzXAva895m/AusudNBd6Yz1cZo3GVfxYulVCQOFPgd8G','client','active',NULL,NULL,NULL,'2026-08-06 21:22:36','2026-08-06 21:22:36','2026-08-06 21:22:36'),(85,'QA Automated Client','qa_client_1786031561@vipasa.demo',NULL,NULL,'$2y$10$OUV5aSsA9L5GmPLqjUeh9OGzHxKyFv5axAiVw0e7y7CRRNZCUcfH6','client','active',NULL,NULL,NULL,'2026-08-06 21:22:42','2026-08-06 21:22:42','2026-08-06 21:22:42'),(86,'QA Pregnant Client','qa_pregnant_dyn_1786032116@vipasa.demo','9863164611',NULL,'$2y$10$BNJ3iUsyA.Lua9SZ9yYMdOY5CA2Q9BsxF8tTVe6ZMq2AlXEeS7gmK','client','active',NULL,NULL,NULL,'2026-08-06 21:31:56','2026-08-06 21:31:56','2026-08-06 21:31:56'),(87,'QA Automated Client','qa_client_1786032124@vipasa.demo',NULL,NULL,'$2y$10$Y8xu0vE4eX2IQntm/PHu6uT/EF7Tqqpl93ouAzQFwPGVfQ36u9sUS','client','active',NULL,NULL,NULL,'2026-08-06 21:32:04','2026-08-06 21:32:04','2026-08-06 21:32:04'),(88,'QA Automated Client','qa_client_1786032129@vipasa.demo',NULL,NULL,'$2y$10$lYh.iqhTFSLLsMs0XiYrq.UDLym0fPmS/sVAi30vNnnioasiQYuHK','client','active',NULL,NULL,NULL,'2026-08-06 21:32:09','2026-08-06 21:32:09','2026-08-06 21:32:09'),(89,'QA Automated Client','qa_client_1786032135@vipasa.demo',NULL,NULL,'$2y$10$v7fQcGzulLUOSaMdnASht.lbWrFeEEhRwKqj4Tr25y/tpP12jN/zq','client','active',NULL,NULL,NULL,'2026-08-06 21:32:15','2026-08-06 21:32:15','2026-08-06 21:32:15'),(90,'QA Automated Client','qa_client_1786032141@vipasa.demo',NULL,NULL,'$2y$10$JULls024FOXTzQAnAMNSjOQzSu78r3WrUpPCM8.EVgArtJOqtramu','client','active',NULL,NULL,NULL,'2026-08-06 21:32:21','2026-08-06 21:32:21','2026-08-06 21:32:21'),(91,'QA Automated Client','qa_client_1786032146@vipasa.demo',NULL,NULL,'$2y$10$phj0329DU4tCk7IdhTGrjek59e1cPSxyUbRcUSRyf0cUAp/rufk6q','client','active',NULL,NULL,NULL,'2026-08-06 21:32:26','2026-08-06 21:32:26','2026-08-06 21:32:26'),(92,'QA Pregnant Client','qa_pregnant_dyn_1786032340@vipasa.demo','9878430902',NULL,'$2y$10$WGqz7kIJtduKPpu.QKo2zud9QKQOoWzNxNbsHsFBEggmhk06kWFT6','client','active',NULL,NULL,NULL,'2026-08-06 21:35:40','2026-08-06 21:35:40','2026-08-06 21:35:40'),(93,'QA Automated Client','qa_client_1786032347@vipasa.demo',NULL,NULL,'$2y$10$0Gbe/1JDNUCXeioJnl2B2.askolEqvftQdFZgXmXekgrfu94IabrC','client','active',NULL,NULL,NULL,'2026-08-06 21:35:47','2026-08-06 21:35:47','2026-08-06 21:35:47'),(94,'QA Automated Client','qa_client_1786032352@vipasa.demo',NULL,NULL,'$2y$10$05TuVMgl45KpXyPLA439FuwOmVuSzFT4jrwtbNzFGES8AF9g2BJia','client','active',NULL,NULL,NULL,'2026-08-06 21:35:52','2026-08-06 21:35:52','2026-08-06 21:35:52'),(95,'QA Automated Client','qa_client_1786032357@vipasa.demo',NULL,NULL,'$2y$10$A5/L8JWFwiPaQffU8wMYce0rlVqvIxTI34KGoFEEcgNXKYVUdxvjy','client','active',NULL,NULL,NULL,'2026-08-06 21:35:57','2026-08-06 21:35:57','2026-08-06 21:35:57'),(96,'QA Automated Client','qa_client_1786032363@vipasa.demo',NULL,NULL,'$2y$10$SaCZmFVL/ExN1nFodcK18eIGZRp5nmnEDt.Ky9izqBXzD1mUbrDT6','client','active',NULL,NULL,NULL,'2026-08-06 21:36:03','2026-08-06 21:36:03','2026-08-06 21:36:03'),(97,'QA Automated Client','qa_client_1786032368@vipasa.demo',NULL,NULL,'$2y$10$.VU3ndwYBsRrkH5E6Dw0u.rC2hA6qqUR3FW9HoARrDrqPHdyKq4QC','client','active',NULL,NULL,NULL,'2026-08-06 21:36:08','2026-08-06 21:36:08','2026-08-06 21:36:08'),(98,'QA Pregnant Client','qa_pregnant_dyn_1786032768@vipasa.demo','9898495315',NULL,'$2y$10$3quYaHN5OAZWRgZTRQBWmOISlWnoj2jgVVy4epKV06RY6plOEJ9ZG','client','active',NULL,NULL,NULL,'2026-08-06 21:42:48','2026-08-06 21:42:48','2026-08-06 21:42:49'),(99,'QA Automated Client','qa_client_1786032779@vipasa.demo',NULL,NULL,'$2y$10$J3vlypqPYOXLoYmVmsMa0OpurbvLwdWrYIw4NHiLlIuNcyCjGPTrS','client','active',NULL,NULL,NULL,'2026-08-06 21:42:59','2026-08-06 21:42:59','2026-08-06 21:42:59'),(100,'QA Automated Client','qa_client_1786032785@vipasa.demo',NULL,NULL,'$2y$10$3p/jUGxcSpZCg/ai4CxV9.915njE0nmYxMM1ARO2wkEks2hD5rL0C','client','active',NULL,NULL,NULL,'2026-08-06 21:43:05','2026-08-06 21:43:05','2026-08-06 21:43:05'),(101,'QA Automated Client','qa_client_1786032790@vipasa.demo',NULL,NULL,'$2y$10$wsZFCmrHe0l0OKSlup6hMePiaIysv7xaA3dFUn5Xo0owuxb0pTPWu','client','active',NULL,NULL,NULL,'2026-08-06 21:43:10','2026-08-06 21:43:10','2026-08-06 21:43:10'),(102,'QA Automated Client','qa_client_1786032796@vipasa.demo',NULL,NULL,'$2y$10$q7/Qdkbf6TPGwBRodrEJuuczoCugzDeWx22Dlf2Ghlg/LBEOK63Yq','client','active',NULL,NULL,NULL,'2026-08-06 21:43:16','2026-08-06 21:43:16','2026-08-06 21:43:16'),(103,'QA Automated Client','qa_client_1786032801@vipasa.demo',NULL,NULL,'$2y$10$yAvL8SKUx7sO.neeD11HTuoqnSzuuTTDmRoBuhO/QoYiUcT39L12q','client','active',NULL,NULL,NULL,'2026-08-06 21:43:22','2026-08-06 21:43:22','2026-08-06 21:43:22'),(104,'QA Pregnant Client','qa_pregnant_dyn_1786033185@vipasa.demo','9863971001',NULL,'$2y$10$kbJX58JnO6IvOjf8RUuN1e4/san2lPZnnzelHSioSDtuTJqC8zvW2','client','active',NULL,NULL,NULL,'2026-08-06 21:49:45','2026-08-06 21:49:45','2026-08-06 21:49:46'),(107,'QA Automated Client','qa_client_1786033206@vipasa.demo',NULL,NULL,'$2y$10$ftng224tyTpX1RC2bIqO..XbIMAmArSXHEuFMvDv3Y0wnf0uhQpOi','client','active',NULL,NULL,NULL,'2026-08-06 21:50:06','2026-08-06 21:50:06','2026-08-06 21:50:06'),(108,'QA Automated Client','qa_client_1786033211@vipasa.demo',NULL,NULL,'$2y$10$nbyJLoK.DyoenB1aABn1uOoOJb7cnY9LP/HW1O17VKx6WQx6IS4hG','client','active',NULL,NULL,NULL,'2026-08-06 21:50:12','2026-08-06 21:50:12','2026-08-06 21:50:12'),(109,'QA Automated Client','qa_client_1786033217@vipasa.demo',NULL,NULL,'$2y$10$nZBztVOFafq7ZtCW1UCTKukYEZTh3M4nDWwSWmhc5kFr.zBgfBP.i','client','active',NULL,NULL,NULL,'2026-08-06 21:50:17','2026-08-06 21:50:17','2026-08-06 21:50:17'),(110,'QA Pregnant Client','qa_pregnant_dyn_1786033500@vipasa.demo','9818067085',NULL,'$2y$10$hpjif4OaJ4N4jiAGrRlY3OV3hMiHKRjZlH5DS4sEDP57TrJlcsckK','client','active',NULL,NULL,NULL,'2026-08-06 21:55:00','2026-08-06 21:55:00','2026-08-06 21:55:00'),(111,'QA Automated Client','qa_client_1786033510@vipasa.demo',NULL,NULL,'$2y$10$Jgk7QVDI2hSg1DQxOPQywu08yOlZXXQVgVk4Qad.ekl3BruhB0w.O','client','active',NULL,NULL,NULL,'2026-08-06 21:55:10','2026-08-06 21:55:10','2026-08-06 21:55:10'),(112,'QA Automated Client','qa_client_1786033515@vipasa.demo',NULL,NULL,'$2y$10$MxzUUJkYeX1N6e8Csnm9b.0CYQcDAb9xYBkQn8do.rLZPvI3bH.HW','client','active',NULL,NULL,NULL,'2026-08-06 21:55:15','2026-08-06 21:55:15','2026-08-06 21:55:15'),(113,'QA Automated Client','qa_client_1786033521@vipasa.demo',NULL,NULL,'$2y$10$qd7TC0AA3TSZPfYTQxWg3OvT8ocvIv3UD8knherJuFBao6D.xfR0G','client','active',NULL,NULL,NULL,'2026-08-06 21:55:21','2026-08-06 21:55:21','2026-08-06 21:55:21'),(114,'QA Automated Client','qa_client_1786033527@vipasa.demo',NULL,NULL,'$2y$10$TzVFNGJwPhnzKToVfv3BduBiHXoohoa/5rA2pZPhnD.TXTT2VnfpS','client','active',NULL,NULL,NULL,'2026-08-06 21:55:27','2026-08-06 21:55:27','2026-08-06 21:55:27'),(115,'QA Automated Client','qa_client_1786033533@vipasa.demo',NULL,NULL,'$2y$10$fA7BszGZGtt07imMq/njDOm13Vkyp6dbIcZhE1zcxMwBVJe8/hEFO','client','active',NULL,NULL,NULL,'2026-08-06 21:55:33','2026-08-06 21:55:33','2026-08-06 21:55:33'),(116,'QA Pregnant Client','qa_pregnant_dyn_1786033909@vipasa.demo','9882697610',NULL,'$2y$10$NhcDKzQTRu34aHP37Ag1k.l.2CZ3lYrjzbSD4zXOUzmZWdHzMESH.','client','active',NULL,NULL,NULL,'2026-08-06 22:01:50','2026-08-06 22:01:50','2026-08-06 22:01:50'),(117,'QA Automated Client','qa_client_1786033919@vipasa.demo',NULL,NULL,'$2y$10$dposfM3OF.Jmm51gEe35sOeAWd/jvL.2opfHfZFaFlrDCuk.zk6Cm','client','active',NULL,NULL,NULL,'2026-08-06 22:01:59','2026-08-06 22:01:59','2026-08-06 22:01:59'),(118,'QA Automated Client','qa_client_1786033925@vipasa.demo',NULL,NULL,'$2y$10$p.zUGl2QT0k3LX1L.s8XVeJot79q37RVH3mWm7BKbxTWxgzqfQBl6','client','active',NULL,NULL,NULL,'2026-08-06 22:02:05','2026-08-06 22:02:05','2026-08-06 22:02:05'),(119,'QA Automated Client','qa_client_1786033930@vipasa.demo',NULL,NULL,'$2y$10$fjDEfRTmAUoRvWOnOuMuouQhknMZdZNKyDqPYx2AzphkNr5WdeN6.','client','active',NULL,NULL,NULL,'2026-08-06 22:02:10','2026-08-06 22:02:10','2026-08-06 22:02:10'),(120,'QA Automated Client','qa_client_1786033936@vipasa.demo',NULL,NULL,'$2y$10$dM1N//InpnX.5gyqL9.DyOldqgH1BmRcSJuemBnQ6z/OaDRlKkIBu','client','active',NULL,NULL,NULL,'2026-08-06 22:02:16','2026-08-06 22:02:16','2026-08-06 22:02:16'),(121,'QA Automated Client','qa_client_1786033941@vipasa.demo',NULL,NULL,'$2y$10$min.zyoPKEj5aQZqm3wviOEoTlNmp9cFdWRuG3htOD95evWMCExmS','client','active',NULL,NULL,NULL,'2026-08-06 22:02:22','2026-08-06 22:02:22','2026-08-06 22:02:22'),(124,'QA Automated Client','qa_client_1786034067@vipasa.demo',NULL,NULL,'$2y$10$0Gh4UInYBX0XAS9GLKrSrecNZ6WrDkHUUN9t4SOWA2WPREAUqx5jm','client','active',NULL,NULL,NULL,'2026-08-06 22:04:27','2026-08-06 22:04:27','2026-08-06 22:04:27'),(125,'QA Automated Client','qa_client_1786034073@vipasa.demo',NULL,NULL,'$2y$10$yRznoZesD1bcXjQZJ3GYx.gPktWfH44xD4DhiQlsnFZ07kEyHk7Ru','client','active',NULL,NULL,NULL,'2026-08-06 22:04:33','2026-08-06 22:04:33','2026-08-06 22:04:33'),(126,'QA Automated Client','qa_client_1786034080@vipasa.demo',NULL,NULL,'$2y$10$9OO7mbzrVKLDGk86/HBw1OrzhQMxzLMXFpD8DDyiFBg6jTtnTNdxm','client','active',NULL,NULL,NULL,'2026-08-06 22:04:41','2026-08-06 22:04:41','2026-08-06 22:04:41'),(128,'siva','sivakumar@gmail.com','12345678997',NULL,'$2y$10$YQhD6CIA1GfIDIagur8ggetoiihYoIF75Kx9zvPIpeUbeQL59vK7u','client','active',NULL,NULL,NULL,'2026-08-08 12:53:05','2026-08-08 12:53:05','2026-08-08 12:53:05'),(129,'Ganga','ganga@gmail.com','1728373767',NULL,'$2y$10$ff40xieck7Tpy6dvyze.qeOyX4RGos0jSYUBcfG4sXnFEza.DAe/i','client','active',NULL,NULL,NULL,'2026-09-20 13:02:10','2026-08-16 18:11:25','2026-09-20 13:02:10'),(131,'Test Client','client_test_178724@example.com','1234567890',NULL,'$2y$10$.T5xiVOZDsd1LV5guMNfD.KDLfn3ltcliudSvRlaB5OH7Mpvkp1Jq','client','active',NULL,NULL,NULL,'2026-08-20 21:08:05','2026-08-20 21:08:05','2026-08-20 21:08:05'),(132,'Google User','google.demo@vipasa.demo','2846293748','demo_google_123456',NULL,'client','active','2026-08-22 21:23:15',NULL,NULL,'2026-10-07 21:49:28','2026-08-22 21:23:15','2026-10-07 21:49:28'),(133,'Nithin','Nithin@gmail.com','9475438392',NULL,'$2y$10$9g.ARBgciVDmAd7.sbOJxeSMHKerzRWxkDLRUVFnv0.y/3pHunI7W','instructor','active',NULL,NULL,NULL,'2026-09-20 13:37:08','2026-08-23 12:12:06','2026-09-20 13:37:08');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallet_transactions`
--

DROP TABLE IF EXISTS `wallet_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallet_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `wallet_id` bigint unsigned NOT NULL,
  `type` enum('credit','debit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reference` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `currency` char(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INR',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-17 19:56:32
