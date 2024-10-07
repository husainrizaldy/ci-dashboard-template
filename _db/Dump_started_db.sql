-- MySQL dump 10.13  Distrib 8.0.29, for Win64 (x86_64)
--
-- Host: localhost    Database: app_cms_started_db
-- ------------------------------------------------------
-- Server version	8.0.29

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `app_menu_content`
--

DROP TABLE IF EXISTS `app_menu_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `app_menu_content` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_path` int DEFAULT NULL,
  `menu_name` varchar(70) NOT NULL,
  `menu_route` varchar(70) DEFAULT NULL,
  `menu_key` varchar(70) DEFAULT NULL,
  `data_icon` varchar(100) DEFAULT 'bx bx-grid-alt',
  `data_key` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_menu_content_path` (`id_path`),
  CONSTRAINT `fk_menu_content_path` FOREIGN KEY (`id_path`) REFERENCES `app_menu_path` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_menu_content`
--

LOCK TABLES `app_menu_content` WRITE;
/*!40000 ALTER TABLE `app_menu_content` DISABLE KEYS */;
INSERT INTO `app_menu_content` VALUES (1,1,'Dashboard','dashboard',NULL,'bx bx-tachometer','t-dashboards',1),(2,2,'Users','users',NULL,'bx bx-grid-alt','t-users',1),(3,2,'Roles','roles',NULL,'bx bx-grid-alt','t-roles',1),(4,2,'Access menu','access',NULL,'bx bx-grid-alt','t-access',1),(5,2,'Menu','#','menu','bx bx-grid-alt','t-data-menu',1);
/*!40000 ALTER TABLE `app_menu_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_menu_path`
--

DROP TABLE IF EXISTS `app_menu_path`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `app_menu_path` (
  `id` int NOT NULL AUTO_INCREMENT,
  `path_name` varchar(70) NOT NULL,
  `path_key` varchar(70) DEFAULT NULL,
  `data_key` varchar(100) DEFAULT NULL,
  `data_icon` varchar(100) DEFAULT 'bx bx-grid-alt',
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_menu_path`
--

LOCK TABLES `app_menu_path` WRITE;
/*!40000 ALTER TABLE `app_menu_path` DISABLE KEYS */;
INSERT INTO `app_menu_path` VALUES (1,'Content','dashboard','t-content','bx bxs-dashboard',1),(2,'Apps','apps','t-apps','bx bx-grid-alt',1);
/*!40000 ALTER TABLE `app_menu_path` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_menu_sub`
--

DROP TABLE IF EXISTS `app_menu_sub`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `app_menu_sub` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_menu` int DEFAULT NULL,
  `sub_name` varchar(70) NOT NULL,
  `sub_route` varchar(70) DEFAULT NULL,
  `data_key` varchar(100) DEFAULT NULL,
  `data_icon` varchar(100) DEFAULT 'bx bx-grid-alt',
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_menu_sub_content` (`id_menu`),
  CONSTRAINT `fk_menu_sub_content` FOREIGN KEY (`id_menu`) REFERENCES `app_menu_content` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_menu_sub`
--

LOCK TABLES `app_menu_sub` WRITE;
/*!40000 ALTER TABLE `app_menu_sub` DISABLE KEYS */;
INSERT INTO `app_menu_sub` VALUES (1,5,'Modul','modul','t-modul','bx bx-grid-alt',1),(2,5,'Content','content','t-content','bx bx-grid-alt',1),(3,5,'Sub Content','sub-content','t-sub-content','bx bx-grid-alt',1);
/*!40000 ALTER TABLE `app_menu_sub` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_roles`
--

DROP TABLE IF EXISTS `app_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `app_roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` varchar(20) NOT NULL,
  `role_name` varchar(45) NOT NULL,
  `code` varchar(45) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_roles`
--

LOCK TABLES `app_roles` WRITE;
/*!40000 ALTER TABLE `app_roles` DISABLE KEYS */;
INSERT INTO `app_roles` VALUES (1,'6702786b43768','Master','master',1,'2024-10-04 22:32:29','2024-10-06 18:46:33'),(2,'67027890a2f10','Admin','admin',1,'2024-10-04 22:32:44','2024-10-06 18:47:38');
/*!40000 ALTER TABLE `app_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_roles_access`
--

DROP TABLE IF EXISTS `app_roles_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `app_roles_access` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_role` int NOT NULL,
  `id_menu` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_roles_access_menu` (`id_menu`),
  KEY `fk_roles_access_role` (`id_role`),
  CONSTRAINT `fk_roles_access_menu` FOREIGN KEY (`id_menu`) REFERENCES `app_menu_content` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_roles_access_role` FOREIGN KEY (`id_role`) REFERENCES `app_roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_roles_access`
--

LOCK TABLES `app_roles_access` WRITE;
/*!40000 ALTER TABLE `app_roles_access` DISABLE KEYS */;
INSERT INTO `app_roles_access` VALUES (2,1,2),(3,1,3),(4,1,4),(5,1,5),(16,2,2);
/*!40000 ALTER TABLE `app_roles_access` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_users`
--

DROP TABLE IF EXISTS `app_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `app_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` varchar(20) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(200) NOT NULL,
  `roles` int DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_UNIQUE` (`uid`),
  KEY `fk_users_roles` (`roles`),
  CONSTRAINT `fk_users_roles` FOREIGN KEY (`roles`) REFERENCES `app_roles` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_users`
--

LOCK TABLES `app_users` WRITE;
/*!40000 ALTER TABLE `app_users` DISABLE KEYS */;
INSERT INTO `app_users` VALUES (1,'67000afb7f881','dev.master@agt.id','$2y$10$8PioanfTlikLAYUKPZNAb.iJwByfUBbX0v561AaI95VoUGTti32Um',1,1,'2024-10-04 22:34:46','2024-10-08 03:08:00','2024-10-08 03:08:00'),(2,'6702291137f06','husain.rizaldy@gmail.com','$2y$10$kBYHITbb6j2TiG/yBddDLOff4jlYHmMfDAJFrTkyFM3my18AdWMBO',2,1,'2024-10-06 13:07:13','2024-10-06 16:58:26','2024-10-06 16:58:26');
/*!40000 ALTER TABLE `app_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_users_layout_settings`
--

DROP TABLE IF EXISTS `app_users_layout_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `app_users_layout_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(20) NOT NULL,
  `theme_mode` enum('light','dark') DEFAULT 'light',
  `theme_layout` enum('vertical','horizontal') DEFAULT 'vertical',
  `sidebar` enum('light','dark') DEFAULT 'light',
  `topbar` enum('light','dark') DEFAULT 'dark',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_UNIQUE` (`user_id`),
  CONSTRAINT `fk_user_layout_settings` FOREIGN KEY (`user_id`) REFERENCES `app_users` (`uid`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_users_layout_settings`
--

LOCK TABLES `app_users_layout_settings` WRITE;
/*!40000 ALTER TABLE `app_users_layout_settings` DISABLE KEYS */;
INSERT INTO `app_users_layout_settings` VALUES (1,'67000afb7f881','light','vertical','light','dark'),(2,'6702291137f06','light','vertical','light','dark');
/*!40000 ALTER TABLE `app_users_layout_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_users_profile`
--

DROP TABLE IF EXISTS `app_users_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `app_users_profile` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(20) NOT NULL,
  `fullname` varchar(70) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text,
  `picture` varchar(100) DEFAULT 'default.png',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_UNIQUE` (`user_id`),
  CONSTRAINT `fk_user_profile` FOREIGN KEY (`user_id`) REFERENCES `app_users` (`uid`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_users_profile`
--

LOCK TABLES `app_users_profile` WRITE;
/*!40000 ALTER TABLE `app_users_profile` DISABLE KEYS */;
INSERT INTO `app_users_profile` VALUES (1,'67000afb7f881','Master Account','08121181000','Jl. H. Muchtar Raya Raya, Kreo, Kec. Larangan, Kota Tengerang, Banten','670439a3c93e6-profile-20241008024227.png','2024-10-04 22:36:23','2024-10-08 02:42:27'),(2,'6702291137f06','husain tuasikal','0812181909283','','default.png','2024-10-06 13:07:13','2024-10-06 20:37:51');
/*!40000 ALTER TABLE `app_users_profile` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-08  3:12:38
