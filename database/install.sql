-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: sistema_biblioteca
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_09_09_000741_create_categorias_table',1),(5,'2026_09_09_000756_create_editorials_table',1),(6,'2026_09_09_000811_create_autors_table',1),(7,'2026_09_09_000934_create_bibliotecarios_table',1),(8,'2026_09_09_000949_create_socios_table',1),(9,'2026_09_09_001013_create_libros_table',1),(10,'2026_09_09_001023_create_libro_autors_table',1),(11,'2026_09_09_001051_create_ejemplars_table',1),(12,'2026_09_09_001106_create_reservas_table',1),(13,'2026_09_09_001122_create_prestamos_table',1),(14,'2026_09_09_001132_create_devolucions_table',1),(15,'2026_09_09_001142_create_detalle_prestamos_table',1),(16,'2026_09_09_001151_create_multas_table',1),(17,'2026_09_09_003032_create_permission_tables',1),(18,'2026_09_09_013521_add_two_factor_columns_to_users_table',1),(19,'2026_09_09_013522_create_passkeys_table',1),(20,'2026_09_09_013603_create_personal_access_tokens_table',1),(21,'2026_09_12_222708_create_sessions_table',1),(22,'2026_09_12_223422_add_password_to_socios_table',1),(23,'2026_09_14_155317_create_notifications_table',1),(24,'2026_09_17_154038_add_timestamps_to_libros_table',1),(25,'2026_09_18_200408_add_portada_to_libros_table',1),(26,'2026_09_29_164028_add_unique_num_documento_to_bibliotecarios_table',1),(27,'2026_11_03_000001_create_password_reset_tokens_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `Num_documento` varchar(20) DEFAULT NULL,
  `Apellidos` varchar(50) DEFAULT NULL,
  `NombreUsuario` varchar(30) DEFAULT NULL,
  `EstadoEmpleado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `Codigo_Supervisor` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_nombreusuario_unique` (`NombreUsuario`),
  KEY `users_codigo_supervisor_foreign` (`Codigo_Supervisor`),
  CONSTRAINT `users_codigo_supervisor_foreign` FOREIGN KEY (`Codigo_Supervisor`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrador','admin@sgb.test',NULL,'$2y$12$KUNWcAPlCCb.nv9GBzQyf.6.D.V.HBUT5a3Tq3TYzaJGO/4nhcyvC',NULL,NULL,NULL,NULL,'2026-11-03 10:22:09','2026-11-03 10:22:09',NULL,NULL,NULL,'Activo',NULL),(2,'Bibliotecario Prueba','biblio@sgb.test',NULL,'$2y$12$95YGQqrfHIeooZI3Wg4/F.CJE7l8JDjQJEh1nunhlTgf3LfAwBT2G',NULL,NULL,NULL,NULL,'2026-11-03 10:22:09','2026-11-03 10:22:09',NULL,NULL,NULL,'Activo',NULL),(3,'Administrador Principal','admin@biblioteca.com','2026-11-03 10:14:19','$2y$12$eCd7pI2IP2kPGmdHaLDE2OsPHRlFRmYp8JuDYW31a36PYCmM4C53m',NULL,NULL,NULL,NULL,'2026-11-03 10:14:19','2026-11-03 16:18:48',NULL,NULL,NULL,'Activo',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Administrador','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(2,'Bibliotecario','web','2026-11-03 10:22:09','2026-11-03 10:22:09');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'libros.ver','web','2026-11-03 10:22:08','2026-11-03 10:22:08'),(2,'libros.crear','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(3,'libros.editar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(4,'libros.eliminar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(5,'categorias.ver','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(6,'categorias.crear','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(7,'categorias.editar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(8,'categorias.eliminar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(9,'editoriales.ver','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(10,'editoriales.crear','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(11,'editoriales.editar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(12,'editoriales.eliminar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(13,'autores.ver','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(14,'autores.crear','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(15,'autores.editar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(16,'autores.eliminar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(17,'ejemplares.ver','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(18,'ejemplares.crear','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(19,'ejemplares.editar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(20,'ejemplares.eliminar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(21,'socios.ver','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(22,'socios.crear','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(23,'socios.editar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(24,'socios.eliminar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(25,'prestamos.ver','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(26,'prestamos.crear','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(27,'prestamos.editar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(28,'prestamos.eliminar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(29,'devoluciones.ver','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(30,'devoluciones.crear','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(31,'devoluciones.editar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(32,'devoluciones.eliminar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(33,'reservas.ver','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(34,'reservas.crear','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(35,'reservas.editar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(36,'reservas.eliminar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(37,'multas.ver','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(38,'multas.crear','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(39,'multas.editar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(40,'multas.eliminar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(41,'bibliotecarios.ver','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(42,'bibliotecarios.crear','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(43,'bibliotecarios.editar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(44,'bibliotecarios.eliminar','web','2026-11-03 10:22:09','2026-11-03 10:22:09'),(45,'roles.gestionar','web','2026-11-03 10:22:09','2026-11-03 10:22:09');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(1,'App\\Models\\User',3),(2,'App\\Models\\User',2);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(1,2),(2,1),(2,2),(3,1),(3,2),(4,1),(4,2),(5,1),(5,2),(6,1),(6,2),(7,1),(7,2),(8,1),(8,2),(9,1),(9,2),(10,1),(10,2),(11,1),(11,2),(12,1),(12,2),(13,1),(13,2),(14,1),(14,2),(15,1),(15,2),(16,1),(16,2),(17,1),(17,2),(18,1),(18,2),(19,1),(19,2),(20,1),(20,2),(21,1),(21,2),(22,1),(22,2),(23,1),(23,2),(24,1),(24,2),(25,1),(25,2),(26,1),(26,2),(27,1),(27,2),(28,1),(28,2),(29,1),(29,2),(30,1),(30,2),(31,1),(31,2),(32,1),(32,2),(33,1),(33,2),(34,1),(34,2),(35,1),(35,2),(36,1),(36,2),(37,1),(37,2),(38,1),(38,2),(39,1),(39,2),(40,1),(40,2),(41,1),(42,1),(43,1),(44,1),(45,1);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bibliotecarios`
--

DROP TABLE IF EXISTS `bibliotecarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bibliotecarios` (
  `Codigo_Bibliotecario` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Num_documento` varchar(20) NOT NULL,
  `Nombres` varchar(50) NOT NULL,
  `Apellidos` varchar(50) NOT NULL,
  `NombreUsuario` varchar(30) NOT NULL,
  `clave_hash` varchar(255) NOT NULL,
  `EstadoEmpleado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `Codigo_Supervisor` int(10) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `two_factor_secret` varchar(255) DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_modificacion` varchar(50) DEFAULT NULL,
  `motivo_modificacion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`Codigo_Bibliotecario`),
  UNIQUE KEY `bibliotecarios_nombreusuario_unique` (`NombreUsuario`),
  UNIQUE KEY `bibliotecarios_num_documento_unique` (`Num_documento`),
  UNIQUE KEY `bibliotecarios_user_id_unique` (`user_id`),
  KEY `bibliotecarios_codigo_supervisor_foreign` (`Codigo_Supervisor`),
  CONSTRAINT `bibliotecarios_codigo_supervisor_foreign` FOREIGN KEY (`Codigo_Supervisor`) REFERENCES `bibliotecarios` (`Codigo_Bibliotecario`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bibliotecarios_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bibliotecarios`
--

LOCK TABLES `bibliotecarios` WRITE;
/*!40000 ALTER TABLE `bibliotecarios` DISABLE KEYS */;
INSERT INTO `bibliotecarios` VALUES (1,'12212111-1','Administrador Principal','User','admin','$2y$12$eCd7pI2IP2kPGmdHaLDE2OsPHRlFRmYp8JuDYW31a36PYCmM4C53m','Activo',NULL,3,NULL,NULL,NULL,NULL,'2026-11-03 10:14:19',NULL,NULL),(2,'00000001-1','Bibliotecario','Prueba','biblio','$2y$12$NfWRwIT5.Z2QQ8cbSVrFjumemZToAYRjdlS9gTQ/VLyztXlXs1gFy','Activo',NULL,2,NULL,NULL,NULL,NULL,'2026-11-03 04:22:10',NULL,NULL),(3,'00000002-2','Administrador','Demo','admindemo','$2y$12$7c/e4UOunLXWNcrIs931jeIt8e/zpoNkLjKnQif5cMUGtE0OG.TEm','Activo',NULL,1,NULL,NULL,NULL,NULL,'2026-11-03 21:02:09',NULL,NULL);
/*!40000 ALTER TABLE `bibliotecarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `socios`
--

DROP TABLE IF EXISTS `socios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `socios` (
  `Codigo_Socio` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Num_Documento` varchar(20) NOT NULL,
  `Nombres` varchar(50) NOT NULL,
  `Apellidos` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `Telefono` varchar(15) NOT NULL,
  `EstadoSocio` enum('Activo','Sancionado','Inactivo') NOT NULL DEFAULT 'Activo',
  `Fecha_Nac` date NOT NULL,
  `Fecha_RegistroSocio` date NOT NULL DEFAULT curdate(),
  `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_modificacion` varchar(50) DEFAULT NULL,
  `motivo_modificacion` varchar(200) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Codigo_Socio`),
  UNIQUE KEY `socios_num_documento_unique` (`Num_Documento`),
  UNIQUE KEY `socios_email_unique` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `socios`
--

LOCK TABLES `socios` WRITE;
/*!40000 ALTER TABLE `socios` DISABLE KEYS */;
INSERT INTO `socios` VALUES (1,'00000000-0','Socio','Prueba','socio@sgb.test','$2y$12$Mb0R/sokuUFnGmbbOCTWMefog6kwWEQPFWQjji6mDnPi2s85VPela','00000000','Activo','2000-01-01','2026-11-02','2026-11-03 04:22:10',NULL,NULL,NULL),(30,'HIST-0001','María José','Cruz Morales','hist.socio1@sgb.test','$2y$12$PTBG4n5ZR50/xOmgFI6pCeJlfK8uNzod54.0Sab2vrG5Fo4iBsZO2','73244435','Activo','1971-10-12','2025-03-28','2026-11-03 20:34:03',NULL,NULL,NULL),(31,'HIST-0002','Carlos','Silva Núñez','hist.socio2@sgb.test','$2y$12$NnyqbzkIAufcvwlztJKRbe3dmpBB6VQuLQtmCJV1u4Z/mK/MdTDtO','71546269','Activo','2001-04-08','2025-06-21','2026-11-03 20:34:04',NULL,NULL,NULL),(32,'HIST-0003','Fernanda','Torres Ramírez','hist.socio3@sgb.test','$2y$12$lattIC1PHdJwwHwjwiDiZet0.55r974q0RptKwRndyoaCUK8gOK6y','75191999','Sancionado','2001-03-07','2025-07-10','2026-11-03 20:34:13',NULL,NULL,NULL),(33,'HIST-0004','José Luis','Chávez Rojas','hist.socio4@sgb.test','$2y$12$.yU0cJ9yrzN8gIATp1kzguHhEhCLRmV/4h7dvyIzaOmZt8WG6rbCG','75868305','Activo','1978-03-26','2025-04-02','2026-11-03 20:34:05',NULL,NULL,NULL),(34,'HIST-0005','Ana Gabriela','López Hernández','hist.socio5@sgb.test','$2y$12$E6ZP.QtBPGYMjwWIpAG/N.rj0cOigrxT7AUHDeMW1BlZfzqglKeSC','74098426','Inactivo','1995-03-09','2025-11-04','2026-11-03 20:34:13',NULL,NULL,NULL),(35,'HIST-0006','Ricardo','Reyes Ortiz','hist.socio6@sgb.test','$2y$12$eErdyPCpVvO1GKcOXLEybud4zpBdAj9heqc6wOkXsYqw7.dH/Hc6K','75467073','Activo','1968-06-28','2025-03-17','2026-11-03 20:34:07',NULL,NULL,NULL),(36,'HIST-0007','Paula','Mendoza Aguilar','hist.socio7@sgb.test','$2y$12$CV4bLKALRJ00aUTDpF8hquGqUSYxJIV0ZXoUasrD6ZUWo1o/znIwO','70508349','Sancionado','1986-05-20','2025-09-16','2026-11-03 20:34:13',NULL,NULL,NULL),(37,'HIST-0008','Andrés','Flores Mendoza','hist.socio8@sgb.test','$2y$12$h34R4XQ1X.fy41hF071RiOmF6JJeNs15Gb6NfUtePHIFfA3bwEMja','75538419','Activo','1994-06-01','2025-03-15','2026-11-03 20:34:08',NULL,NULL,NULL),(38,'HIST-0009','Lucía','Ramos Vargas','hist.socio9@sgb.test','$2y$12$c.cJsIctGVOGrmU8a7oxM.sNvQuQxc8m8fGBUS6r1TVCP2gqoJHvi','75381528','Inactivo','1972-02-17','2025-09-22','2026-11-03 20:34:13',NULL,NULL,NULL),(39,'HIST-0010','Miguel Ángel','García Pérez','hist.socio10@sgb.test','$2y$12$s3J/gWDApgBLyb0ux.XlHeDUqSXZxug9A4ELUQy1RDVyGjO7EbyJC','77574198','Sancionado','1978-05-08','2025-06-23','2026-11-03 20:34:13',NULL,NULL,NULL),(40,'HIST-0011','Valeria','Gutiérrez Castro','hist.socio11@sgb.test','$2y$12$pLoHPIobflYhVv0wvysKkuWnJsxxzHTQO39T/P5.8.lVwU6VWKtPe','78057929','Activo','1970-10-18','2025-04-08','2026-11-03 20:34:09',NULL,NULL,NULL),(41,'HIST-0012','Diego','Martínez Rodríguez','hist.socio12@sgb.test','$2y$12$XwA7XKIQli1p1N0ZTJmhXuO6oWPjmCejc2tpP1h.G4b.s6jVPgZaq','70757399','Activo','1994-11-18','2025-09-28','2026-11-03 20:34:10',NULL,NULL,NULL),(42,'HIST-0013','Camila','Cruz Morales','hist.socio13@sgb.test','$2y$12$awMF/7Utp2R0qnxMAeNKqe4sDc9vFiG38zak3i5HZB/SofYLSa.I.','71752675','Activo','1985-07-12','2025-01-09','2026-11-03 20:34:10',NULL,NULL,NULL),(43,'HIST-0014','Roberto','Silva Núñez','hist.socio14@sgb.test','$2y$12$9oA7GoZ0VJd5tW8NH5zXK.v1JUpS.a/inmq3D6QFy22f1j1D/7Pvy','76166896','Sancionado','1975-02-08','2025-03-16','2026-11-03 20:34:13',NULL,NULL,NULL);
/*!40000 ALTER TABLE `socios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorias` (
  `Codigo_Categoria` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `NombreCategoria` varchar(50) NOT NULL,
  `Descripcion` varchar(200) DEFAULT NULL,
  `Codigo_categoriaPadre` int(10) unsigned DEFAULT NULL,
  `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_modificacion` varchar(50) DEFAULT NULL,
  `motivo_modificacion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`Codigo_Categoria`),
  KEY `categorias_codigo_categoriapadre_foreign` (`Codigo_categoriaPadre`),
  CONSTRAINT `categorias_codigo_categoriapadre_foreign` FOREIGN KEY (`Codigo_categoriaPadre`) REFERENCES `categorias` (`Codigo_Categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (2,'Fantasía Romántica / Romantasy','Fantasía Romántica / Romantasy, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:29',NULL,NULL),(3,'Fantasía Romántica / Young Adult','Fantasía Romántica / Young Adult, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:29',NULL,NULL),(4,'Fantasía / Romantasy','Fantasía / Romantasy, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:29',NULL,NULL),(5,'Fantasía Urbana','Fantasía Urbana, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:29',NULL,NULL),(6,'Fantasía / Fae','Fantasía / Fae, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:29',NULL,NULL),(7,'Ficción Histórica / Humor','Ficción Histórica / Humor, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:29',NULL,NULL),(8,'Fantasía Romántica / Cuentos de hadas','Fantasía Romántica / Cuentos de hadas, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:29',NULL,NULL),(9,'Fantasía Romántica','Fantasía Romántica, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:29',NULL,NULL),(10,'Romantasy / Distopía','Romantasy / Distopía, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:29',NULL,NULL),(11,'Fantasía Histórica / Dark Academia','Fantasía Histórica / Dark Academia, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:29',NULL,NULL),(12,'Grimdark Fantasy','Grimdark Fantasy, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:30',NULL,NULL),(13,'Fantasía Épica (Cosmere)','Fantasía Épica (Cosmere), Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:30',NULL,NULL),(14,'Fantasía / Heist','Fantasía / Heist, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:30',NULL,NULL),(15,'Thriller Psicológico / Novela Negra','Thriller Psicológico / Novela Negra, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:30',NULL,NULL),(16,'Thriller Psicológico','Thriller Psicológico, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:30',NULL,NULL),(17,'Thriller de Venganza','Thriller de Venganza, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:30',NULL,NULL),(18,'Misterio / Suspense','Misterio / Suspense, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:30',NULL,NULL),(19,'Misterio / Novela de Enigma','Misterio / Novela de Enigma, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:31',NULL,NULL),(20,'Novela Negra / Policiaca','Novela Negra / Policiaca, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:31',NULL,NULL),(21,'Thriller Histórico','Thriller Histórico, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:31',NULL,NULL),(22,'Novela Negra / Thriller','Novela Negra / Thriller, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:31',NULL,NULL),(23,'Thriller de Robo y Suspense','Thriller de Robo y Suspense, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:31',NULL,NULL),(24,'Misterio Juvenil / True Crime','Misterio Juvenil / True Crime, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:31',NULL,NULL),(25,'Ficción Histórica / Romance','Ficción Histórica / Romance, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:31',NULL,NULL),(26,'Ficción Contemporánea / Música','Ficción Contemporánea / Música, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:31',NULL,NULL),(27,'Romance Contemporáneo / Drama','Romance Contemporáneo / Drama, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:31',NULL,NULL),(28,'Romance Contemporáneo','Romance Contemporáneo, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:31',NULL,NULL),(29,'Thriller Psicológico / Romance','Thriller Psicológico / Romance, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:31',NULL,NULL),(30,'Comedia Romántica / STEM Romance','Comedia Romántica / STEM Romance, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:31',NULL,NULL),(31,'Romance Emocional Contemporáneo','Romance Emocional Contemporáneo, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:31',NULL,NULL),(32,'Dark Romance / Enemigos a Amantes','Dark Romance / Enemigos a Amantes, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:32',NULL,NULL),(33,'Romance / Guardaespaldas y Realeza','Romance / Guardaespaldas y Realeza, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:32',NULL,NULL),(34,'Romance Deportivo / Hockey','Romance Deportivo / Hockey, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:32',NULL,NULL),(35,'Romance LGBTQ+ / Realeza','Romance LGBTQ+ / Realeza, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:32',NULL,NULL),(36,'Romance Juvenil / New Adult','Romance Juvenil / New Adult, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:32',NULL,NULL),(37,'Desarrollo Personal / Psicología Práctica','Desarrollo Personal / Psicología Práctica, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:32',NULL,NULL),(38,'Ficción Contemporánea / Realismo Mágico','Ficción Contemporánea / Realismo Mágico, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:32',NULL,NULL),(39,'Ensayo Histórico y Literario','Ensayo Histórico y Literario, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:32',NULL,NULL),(40,'Terror Gótico / Novela Literaria','Terror Gótico / Novela Literaria, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:32',NULL,NULL),(41,'Ficción Literaria Contemporánea','Ficción Literaria Contemporánea, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:32',NULL,NULL),(42,'Ciencia Ficción Dura / Espacial','Ciencia Ficción Dura / Espacial, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:32',NULL,NULL),(43,'Ciencia Ficción Hard / Espacial','Ciencia Ficción Hard / Espacial, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:32',NULL,NULL),(44,'Thriller / Suspense','Thriller / Suspense, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:32',NULL,NULL),(45,'Misterio Gótico / Ficción Literaria','Misterio Gótico / Ficción Literaria, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:32',NULL,NULL),(46,'Novela Gráfica / Romance Juvenil LGBTQ+','Novela Gráfica / Romance Juvenil LGBTQ+, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:33',NULL,NULL),(47,'Romance Juvenil / Drama','Romance Juvenil / Drama, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:33',NULL,NULL),(48,'Ficción Literaria y Emocional','Ficción Literaria y Emocional, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:33',NULL,NULL),(49,'Romance Histórico y Contemporáneo','Romance Histórico y Contemporáneo, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:33',NULL,NULL),(50,'Dark Romance / Matrimonio por Conveniencia','Dark Romance / Matrimonio por Conveniencia, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:33',NULL,NULL),(51,'Dark Romance / Enemigos con Beneficios','Dark Romance / Enemigos con Beneficios, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:33',NULL,NULL),(52,'Dark Romance / Romance Falso','Dark Romance / Romance Falso, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:34',NULL,NULL),(53,'Drama Familiar / Romance','Drama Familiar / Romance, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:34',NULL,NULL),(54,'Ficción Contemporánea / Drama','Ficción Contemporánea / Drama, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:34',NULL,NULL),(55,'Ficción Contemporánea / Tenis','Ficción Contemporánea / Tenis, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:34',NULL,NULL),(56,'Paranormal Romance / Vampiros y Hombres Lobo','Paranormal Romance / Vampiros y Hombres Lobo, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:34',NULL,NULL),(57,'Novela Negra / Thriller Arqueológico','Novela Negra / Thriller Arqueológico, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:34',NULL,NULL),(58,'Thriller Bibliográfico / Novela Negra','Thriller Bibliográfico / Novela Negra, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:34',NULL,NULL),(59,'Thriller / Arte y Misterio','Thriller / Arte y Misterio, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:34',NULL,NULL),(60,'Thriller / Suspense Tecnológico','Thriller / Suspense Tecnológico, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:35',NULL,NULL),(61,'Thriller Psicológico / Médico','Thriller Psicológico / Médico, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:35',NULL,NULL),(62,'Misterio / Suspense Literario','Misterio / Suspense Literario, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:35',NULL,NULL),(63,'Ficción Contemporánea / Videojuegos','Ficción Contemporánea / Videojuegos, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:35',NULL,NULL),(64,'Historia / Antropología / No Ficción','Historia / Antropología / No Ficción, Bestseller Contemporáneo, Lectura Recomendada',NULL,'2026-11-03 18:25:35',NULL,NULL);
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `editorials`
--

DROP TABLE IF EXISTS `editorials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `editorials` (
  `Codigo_Editorial` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `NombreEditorial` varchar(100) NOT NULL,
  `Pais` varchar(50) NOT NULL,
  `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_modificacion` varchar(50) DEFAULT NULL,
  `motivo_modificacion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`Codigo_Editorial`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `editorials`
--

LOCK TABLES `editorials` WRITE;
/*!40000 ALTER TABLE `editorials` DISABLE KEYS */;
INSERT INTO `editorials` VALUES (2,'Editorial Planeta','España','2026-11-03 18:25:29',NULL,NULL),(3,'Crossbooks','España','2026-11-03 18:25:29',NULL,NULL),(4,'Alfaguara','España','2026-11-03 18:25:29',NULL,NULL),(5,'Editorial Hidra','España','2026-11-03 18:25:29',NULL,NULL),(6,'Salamandra','España','2026-11-03 18:25:29',NULL,NULL),(7,'Puck','España','2026-11-03 18:25:29',NULL,NULL),(8,'Nova','España','2026-11-03 18:25:30',NULL,NULL),(9,'Ediciones B','España','2026-11-03 18:25:30',NULL,NULL),(10,'Suma de Letras','España','2026-11-03 18:25:30',NULL,NULL),(11,'Umbriel','España','2026-11-03 18:25:31',NULL,NULL),(12,'Contraluz','España','2026-11-03 18:25:31',NULL,NULL),(13,'Bruguera','España','2026-11-03 18:25:32',NULL,NULL),(14,'Molino','España','2026-11-03 18:25:32',NULL,NULL),(15,'Montena','España','2026-11-03 18:25:32',NULL,NULL),(16,'Diana México / Paidós','España','2026-11-03 18:25:32',NULL,NULL),(17,'Ediciones Destino','España','2026-11-03 18:25:32',NULL,NULL),(18,'Siruela','España','2026-11-03 18:25:32',NULL,NULL),(19,'Editorial Anagrama','Argentina / España','2026-11-03 18:25:32',NULL,NULL),(20,'Seix Barral','España','2026-11-03 18:25:32',NULL,NULL),(21,'Plaza & Janés','España','2026-11-03 18:25:32',NULL,NULL),(22,'Literatura Random House','España','2026-11-03 18:25:33',NULL,NULL),(23,'Alianza / Alfaguara','España','2026-11-03 18:25:35',NULL,NULL),(24,'Debate','España','2026-11-03 18:25:35',NULL,NULL);
/*!40000 ALTER TABLE `editorials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `autors`
--

DROP TABLE IF EXISTS `autors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `autors` (
  `Codigo_Autor` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombres` varchar(50) NOT NULL,
  `Apellidos` varchar(50) NOT NULL,
  `Seudonimo` varchar(50) DEFAULT NULL,
  `Biografia` text DEFAULT NULL,
  `Nacionalidad` varchar(50) NOT NULL,
  `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_modificacion` varchar(50) DEFAULT NULL,
  `motivo_modificacion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`Codigo_Autor`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `autors`
--

LOCK TABLES `autors` WRITE;
/*!40000 ALTER TABLE `autors` DISABLE KEYS */;
INSERT INTO `autors` VALUES (2,'Rebecca','Yarros',NULL,NULL,'Estados Unidos','2026-11-03 18:25:29',NULL,NULL),(3,'Sarah J.','Maas',NULL,NULL,'Estados Unidos','2026-11-03 18:25:29',NULL,NULL),(4,'Holly','Black',NULL,NULL,'Estados Unidos','2026-11-03 18:25:29',NULL,NULL),(5,'Bonnie','Garmus',NULL,NULL,'Estados Unidos','2026-11-03 18:25:29',NULL,NULL),(6,'Stephanie','Garber',NULL,NULL,'Estados Unidos','2026-11-03 18:25:29',NULL,NULL),(7,'Lauren','Roberts',NULL,NULL,'Estados Unidos','2026-11-03 18:25:29',NULL,NULL),(8,'R. F.','Kuang',NULL,NULL,'Estados Unidos','2026-11-03 18:25:29',NULL,NULL),(9,'Brandon','Sanderson',NULL,NULL,'Estados Unidos','2026-11-03 18:25:30',NULL,NULL),(10,'Leigh','Bardugo',NULL,NULL,'Estados Unidos','2026-11-03 18:25:30',NULL,NULL),(11,'Juan','Gómez-Jurado',NULL,NULL,'España','2026-11-03 18:25:30',NULL,NULL),(12,'Javier','Castillo',NULL,NULL,'España','2026-11-03 18:25:30',NULL,NULL),(13,'Freida','McFadden','Freida McFadden (Seudónimo literario)',NULL,'Estados Unidos','2026-11-03 18:25:30',NULL,NULL),(14,'Alex','Michaelides',NULL,NULL,'Reino Unido / Chipre','2026-11-03 18:25:31',NULL,NULL),(15,'Carmen','Mola','Jorge Díaz, Agustín Martínez y Antonio Mercero',NULL,'España','2026-11-03 18:25:31',NULL,NULL),(16,'Joël','Dicker',NULL,NULL,'Suiza','2026-11-03 18:25:31',NULL,NULL),(17,'Holly','Jackson',NULL,NULL,'Reino Unido','2026-11-03 18:25:31',NULL,NULL),(18,'Taylor','Jenkins Reid',NULL,NULL,'Estados Unidos','2026-11-03 18:25:31',NULL,NULL),(19,'Colleen','Hoover',NULL,NULL,'Estados Unidos','2026-11-03 18:25:31',NULL,NULL),(20,'Ali','Hazelwood','Ali Hazelwood (Seudónimo literario)',NULL,'Italia / Estados Unidos','2026-11-03 18:25:31',NULL,NULL),(21,'Alice','Kellen','Silvia Hervás (Seudónimo Alice Kellen)',NULL,'España','2026-11-03 18:25:31',NULL,NULL),(22,'Ana','Huang',NULL,NULL,'Estados Unidos','2026-11-03 18:25:32',NULL,NULL),(23,'Hannah','Grace','Hannah Grace',NULL,'Reino Unido','2026-11-03 18:25:32',NULL,NULL),(24,'Casey','McQuiston',NULL,NULL,'Estados Unidos','2026-11-03 18:25:32',NULL,NULL),(25,'Joana','Marcus',NULL,NULL,'España','2026-11-03 18:25:32',NULL,NULL),(26,'James','Clear',NULL,NULL,'Estados Unidos','2026-11-03 18:25:32',NULL,NULL),(27,'Matt','Haig',NULL,NULL,'Reino Unido','2026-11-03 18:25:32',NULL,NULL),(28,'Irene','Vallejo',NULL,NULL,'España','2026-11-03 18:25:32',NULL,NULL),(29,'Mariana','Enriquez',NULL,NULL,'Argentina','2026-11-03 18:25:32',NULL,NULL),(30,'Alana S.','Portero',NULL,NULL,'España','2026-11-03 18:25:32',NULL,NULL),(31,'Andy','Weir',NULL,NULL,'Estados Unidos','2026-11-03 18:25:32',NULL,NULL),(32,'Cixin','Liu',NULL,NULL,'China','2026-11-03 18:25:32',NULL,NULL),(33,'Stephen','King','Richard Bachman',NULL,'Estados Unidos','2026-11-03 18:25:32',NULL,NULL),(34,'Carlos','Ruiz Zafón',NULL,NULL,'España','2026-11-03 18:25:32',NULL,NULL),(35,'Alice','Oseman',NULL,NULL,'Reino Unido','2026-11-03 18:25:33',NULL,NULL),(36,'Sally','Rooney',NULL,NULL,'Irlanda','2026-11-03 18:25:33',NULL,NULL),(37,'Flor M.','Salvador',NULL,NULL,'México','2026-11-03 18:25:33',NULL,NULL),(38,'Eva','García Sáenz de Urturi',NULL,NULL,'España','2026-11-03 18:25:34',NULL,NULL),(39,'Gabrielle','Zevin',NULL,NULL,'Estados Unidos','2026-11-03 18:25:35',NULL,NULL),(40,'Yuval Noah','Harari',NULL,NULL,'Israel','2026-11-03 18:25:35',NULL,NULL);
/*!40000 ALTER TABLE `autors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `libros`
--

DROP TABLE IF EXISTS `libros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `libros` (
  `Codigo_ISBN` varchar(20) NOT NULL,
  `Titulo` varchar(150) NOT NULL,
  `Subtitulo` varchar(150) DEFAULT NULL,
  `Anio_Publicacion` int(11) NOT NULL,
  `N_paginas` int(11) NOT NULL,
  `Idioma` varchar(30) NOT NULL DEFAULT 'español',
  `Codigo_Categoria` int(10) unsigned NOT NULL,
  `Codigo_Editorial` int(10) unsigned NOT NULL,
  `portada` varchar(255) DEFAULT NULL,
  `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_modificacion` varchar(50) DEFAULT NULL,
  `motivo_modificacion` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Codigo_ISBN`),
  KEY `libros_codigo_categoria_foreign` (`Codigo_Categoria`),
  KEY `libros_codigo_editorial_foreign` (`Codigo_Editorial`),
  CONSTRAINT `libros_codigo_categoria_foreign` FOREIGN KEY (`Codigo_Categoria`) REFERENCES `categorias` (`Codigo_Categoria`),
  CONSTRAINT `libros_codigo_editorial_foreign` FOREIGN KEY (`Codigo_Editorial`) REFERENCES `editorials` (`Codigo_Editorial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `libros`
--

LOCK TABLES `libros` WRITE;
/*!40000 ALTER TABLE `libros` DISABLE KEYS */;
INSERT INTO `libros` VALUES ('9788401031380','Holly','Novela de misterio y suspense',2023,624,'Español (Castellano)',44,21,'portadas/ESP_066_9788401031380.jpg','2026-11-03 18:25:32',NULL,NULL,'2026-11-04 00:25:32','2026-11-04 00:25:32'),('9788408154167','El silencio de la ciudad blanca','Trilogía Ciudad Blanca 1',2016,480,'Español (Castellano)',57,2,'portadas/ESP_096_9788408154167.jpg','2026-11-03 18:25:34',NULL,NULL,'2026-11-04 00:25:34','2026-11-04 00:25:34'),('9788408163381','El laberinto de los espíritus','El Cementerio de los Libros Olvidados 4',2016,928,'Español (Castellano)',45,2,'portadas/ESP_068_9788408163381.jpg','2026-11-03 18:25:32',NULL,NULL,'2026-11-04 00:25:32','2026-11-04 00:25:32'),('9788408163435','La sombra del viento','El Cementerio de los Libros Olvidados 1',2016,576,'Español (Castellano)',45,2,'portadas/ESP_067_9788408163435.jpg','2026-11-03 18:25:32',NULL,NULL,'2026-11-04 00:25:32','2026-11-04 00:25:32'),('9788408204688','Todo lo que nunca fuimos','Deja que ocurra 1',2019,352,'Español (Castellano)',28,2,'portadas/ESP_052_9788408204688.jpg','2026-11-03 18:25:32',NULL,NULL,'2026-11-04 00:25:31','2026-11-04 00:25:31'),('9788408209867','Una corte de rosas y espinas','ACOTAR 1',2016,456,'Español (Castellano)',3,3,'portadas/ESP_003_9788408209867.jpg','2026-11-03 18:25:29',NULL,NULL,'2026-11-04 00:25:29','2026-11-04 00:25:29'),('9788408209874','Una corte de niebla y furia','ACOTAR 2',2017,672,'Español (Castellano)',3,3,'portadas/ESP_004_9788408209874.jpg','2026-11-03 18:25:29',NULL,NULL,'2026-11-04 00:25:29','2026-11-04 00:25:29'),('9788408209881','Una corte de alas y ruina','ACOTAR 3',2017,812,'Español (Castellano)',3,3,'portadas/ESP_005_9788408209881.jpg','2026-11-03 18:25:29',NULL,NULL,'2026-11-04 00:25:29','2026-11-04 00:25:29'),('9788408221449','Nosotros en la luna','Novela romántica',2020,480,'Español (Castellano)',28,2,'portadas/ESP_053_9788408221449.jpg','2026-11-03 18:25:32',NULL,NULL,'2026-11-04 00:25:32','2026-11-04 00:25:32'),('9788408225584','Heartstopper 1: Dos chicos juntos','Heartstopper 1',2020,288,'Español (Castellano)',46,3,'portadas/ESP_069_9788408225584.jpg','2026-11-03 18:25:33',NULL,NULL,'2026-11-04 00:25:33','2026-11-04 00:25:33'),('9788408225591','Heartstopper 2: Mi persona favorita','Heartstopper 2',2020,320,'Español (Castellano)',46,3,'portadas/ESP_070_9788408225591.jpg','2026-11-03 18:25:33',NULL,NULL,'2026-11-04 00:25:33','2026-11-04 00:25:33'),('9788408226055','Asesinato para principiantes','Trilogía Pip Fitz-Amobi 1',2020,448,'Español (Castellano)',24,3,'portadas/ESP_043_9788408226055.jpg','2026-11-03 18:25:31',NULL,NULL,'2026-11-04 00:25:31','2026-11-04 00:25:31'),('9788408226062','El chico que dibujaba constelaciones','Novela intimista',2020,304,'Español (Castellano)',49,2,'portadas/ESP_081_9788408226062.jpg','2026-11-03 18:25:33',NULL,NULL,'2026-11-04 00:25:33','2026-11-04 00:25:33'),('9788408232971','Heartstopper 3: Un paso adelante','Heartstopper 3',2020,384,'Español (Castellano)',46,3,'portadas/ESP_071_9788408232971.jpg','2026-11-03 18:25:33',NULL,NULL,'2026-11-04 00:25:33','2026-11-04 00:25:33'),('9788408244196','Heartstopper 4: Más que palabras','Heartstopper 4',2021,384,'Español (Castellano)',46,3,'portadas/ESP_072_9788408244196.jpg','2026-11-03 18:25:33',NULL,NULL,'2026-11-04 00:25:33','2026-11-04 00:25:33'),('9788408249832','La bestia','Premio Planeta 2021',2021,544,'Español (Castellano)',21,2,'portadas/ESP_040_9788408249832.jpg','2026-11-03 18:25:31',NULL,NULL,'2026-11-04 00:25:31','2026-11-04 00:25:31'),('9788408249849','Una corte de llamas plateadas','ACOTAR 4',2021,736,'Español (Castellano)',4,3,'portadas/ESP_006_9788408249849.jpg','2026-11-03 18:25:29',NULL,NULL,'2026-11-04 00:25:29','2026-11-04 00:25:29'),('9788408252870','El libro negro de las horas','Serie Kraken',2022,384,'Español (Castellano)',58,2,'portadas/ESP_097_9788408252870.jpg','2026-11-03 18:25:34',NULL,NULL,'2026-11-04 00:25:34','2026-11-04 00:25:34'),('9788408255147','El mapa de los anhelos','Novela emocional',2022,496,'Español (Castellano)',31,2,'portadas/ESP_051_9788408255147.jpg','2026-11-03 18:25:31',NULL,NULL,'2026-11-04 00:25:31','2026-11-04 00:25:31'),('9788408257493','Romper el círculo','It Ends With Us',2022,400,'Español (Castellano)',27,2,'portadas/ESP_046_9788408257493.jpg','2026-11-03 18:25:31',NULL,NULL,'2026-11-04 00:25:31','2026-11-04 00:25:31'),('9788408263142','La teoría de los archipiélagos','Novela literaria',2022,288,'Español (Castellano)',48,2,'portadas/ESP_080_9788408263142.jpg','2026-11-03 18:25:33',NULL,NULL,'2026-11-04 00:25:33','2026-11-04 00:25:33'),('9788408263159','Verity: La sombra de un engaño','Thriller Romántico',2022,368,'Español (Castellano)',29,2,'portadas/ESP_048_9788408263159.jpg','2026-11-03 18:25:31',NULL,NULL,'2026-11-04 00:25:31','2026-11-04 00:25:31'),('9788408267195','Volver a empezar','It Starts with Us',2023,384,'Español (Castellano)',28,2,'portadas/ESP_047_9788408267195.jpg','2026-11-03 18:25:31',NULL,NULL,'2026-11-04 00:25:31','2026-11-04 00:25:31'),('9788408268574','Donde todo brilla','Novela romántica',2023,544,'Español (Castellano)',28,2,'portadas/ESP_079_9788408268574.jpg','2026-11-03 18:25:33',NULL,NULL,'2026-11-04 00:25:33','2026-11-04 00:25:33'),('9788408269557','El ángel de la ciudad','Serie Kraken',2023,400,'Español (Castellano)',59,2,'portadas/ESP_098_9788408269557.jpg','2026-11-03 18:25:35',NULL,NULL,'2026-11-04 00:25:35','2026-11-04 00:25:35'),('9788408272533','A pesar de ti','Novela dramática y romance',2023,432,'Español (Castellano)',53,2,'portadas/ESP_087_9788408272533.jpg','2026-11-03 18:25:34',NULL,NULL,'2026-11-04 00:25:34','2026-11-04 00:25:34'),('9788408277255','El infierno','Thriller Histórico',2023,480,'Español (Castellano)',21,2,'portadas/ESP_095_9788408277255.jpg','2026-11-03 18:25:34',NULL,NULL,'2026-11-04 00:25:34','2026-11-04 00:25:34'),('9788408279990','Alas de sangre','Empireo 1',2023,736,'Español (Castellano)',2,2,'portadas/ESP_001_9788408279990.jpg','2026-11-03 18:25:29',NULL,NULL,'2026-11-04 00:25:29','2026-11-04 00:25:29'),('9788408284550','Alas de hierro','Empireo 2',2024,896,'Español (Castellano)',2,2,'portadas/ESP_002_9788408284550.jpg','2026-11-03 18:25:29',NULL,NULL,'2026-11-04 00:25:29','2026-11-04 00:25:29'),('9788408287711','Powerless','Trilogía Powerless 1',2024,528,'Español (Castellano)',10,3,'portadas/ESP_016_9788408287711.jpg','2026-11-03 18:25:29',NULL,NULL,'2026-11-04 00:25:29','2026-11-04 00:25:29'),('9788408292838','El clan','Serie Inspectora Elena Blanco 5',2024,480,'Español (Castellano)',22,2,'portadas/ESP_041_9788408292838.jpg','2026-11-03 18:25:31',NULL,NULL,'2026-11-04 00:25:31','2026-11-04 00:25:31'),('9788408293248','Reckless','Trilogía Powerless 2',2024,544,'Español (Castellano)',10,3,'portadas/ESP_086_9788408293248.jpg','2026-11-03 18:25:34',NULL,NULL,'2026-11-04 00:25:34','2026-11-04 00:25:34'),('9788416517275','Los siete maridos de Evelyn Hugo','Novela de drama y romance',2020,384,'Español (Castellano)',25,11,'portadas/ESP_044_9788416517275.jpg','2026-11-03 18:25:31',NULL,NULL,'2026-11-04 00:25:31','2026-11-04 00:25:31'),('9788416517404','Todos quieren a Daisy Jones','Novela musical y romance',2021,368,'Español (Castellano)',26,11,'portadas/ESP_045_9788416517404.jpg','2026-11-03 18:25:31',NULL,NULL,'2026-11-04 00:25:31','2026-11-04 00:25:31'),('9788416517619','Malibu renace','Novela dramática',2021,416,'Español (Castellano)',54,11,'portadas/ESP_088_9788416517619.jpg','2026-11-03 18:25:34',NULL,NULL,'2026-11-04 00:25:34','2026-11-04 00:25:34'),('9788416517923','El regreso de Carrie Soto','Novela deportiva y superación',2022,416,'Español (Castellano)',55,11,'portadas/ESP_089_9788416517923.jpg','2026-11-03 18:25:34',NULL,NULL,'2026-11-04 00:25:34','2026-11-04 00:25:34'),('9788416587421','Seis de cuervos','Grishaverse 1',2016,544,'Español (Castellano)',14,5,'portadas/ESP_022_9788416587421.jpg','2026-11-03 18:25:30',NULL,NULL,'2026-11-04 00:25:30','2026-11-04 00:25:30'),('9788417517243','Gente normal','Novela de amor y juventud',2019,256,'Español (Castellano)',41,22,'portadas/ESP_073_9788417517243.jpg','2026-11-03 18:25:33',NULL,NULL,'2026-11-04 00:25:33','2026-11-04 00:25:33'),('9788417517595','Dónde estás, mundo bello','Novela contemporánea',2021,320,'Español (Castellano)',41,22,'portadas/ESP_074_9788417517595.jpg','2026-11-03 18:25:33',NULL,NULL,'2026-11-04 00:25:33','2026-11-04 00:25:33'),('9788417589257','El rey malvado','Los habitantes del aire 2',2019,480,'Español (Castellano)',6,5,'portadas/ESP_011_9788417589257.jpg','2026-11-03 18:25:29',NULL,NULL,'2026-11-04 00:25:29','2026-11-04 00:25:29'),('9788417589639','La guerra de la amapola','Trilogía de la Amapola 1',2020,624,'Español (Castellano)',12,5,'portadas/ESP_018_9788417589639.jpg','2026-11-03 18:25:30',NULL,NULL,'2026-11-04 00:25:30','2026-11-04 00:25:30'),('9788417589882','La reina de la nada','Los habitantes del aire 3',2020,448,'Español (Castellano)',6,5,'portadas/ESP_012_9788417589882.jpg','2026-11-03 18:25:29',NULL,NULL,'2026-11-04 00:25:29','2026-11-04 00:25:29'),('9788417854447','Érase una vez un corazón roto','Trilogía Corazón Roto 1',2021,416,'Español (Castellano)',8,7,'portadas/ESP_013_9788417854447.jpg','2026-11-03 18:25:29',NULL,NULL,'2026-11-04 00:25:29','2026-11-04 00:25:29'),('9788417854614','La hipótesis del amor','Comedia Romántica',2022,480,'Español (Castellano)',30,12,'portadas/ESP_049_9788417854614.jpg','2026-11-03 18:25:31',NULL,NULL,'2026-11-04 00:25:31','2026-11-04 00:25:31'),('9788417854720','La balada de nunca jamás','Trilogía Corazón Roto 2',2022,448,'Español (Castellano)',9,7,'portadas/ESP_014_9788417854720.jpg','2026-11-03 18:25:29',NULL,NULL,'2026-11-04 00:25:29','2026-11-04 00:25:29'),('9788417854744','La química del amor','Novela romántica',2022,432,'Español (Castellano)',30,12,'portadas/ESP_050_9788417854744.jpg','2026-11-03 18:25:31',NULL,NULL,'2026-11-04 00:25:31','2026-11-04 00:25:31'),('9788417854898','Jaque mate al amor','Love, Theoretically',2023,448,'Español (Castellano)',30,12,'portadas/ESP_090_9788417854898.jpg','2026-11-03 18:25:34',NULL,NULL,'2026-11-04 00:25:34','2026-11-04 00:25:34'),('9788417854935','Una maldición de amor verdadero','Trilogía Corazón Roto 3',2023,448,'Español (Castellano)',9,7,'portadas/ESP_015_9788417854935.jpg','2026-11-03 18:25:29',NULL,NULL,'2026-11-04 00:25:29','2026-11-04 00:25:29'),('9788417854973','Novia','Bride',2024,448,'Español (Castellano)',56,12,'portadas/ESP_091_9788417854973.jpg','2026-11-03 18:25:34',NULL,NULL,'2026-11-04 00:25:34','2026-11-04 00:25:34'),('9788417860790','El infinito en un junco','La invención de los libros en el mundo antiguo',2019,452,'Español (Castellano)',39,18,'portadas/ESP_061_9788417860790.jpg','2026-11-03 18:25:32',NULL,NULL,'2026-11-04 00:25:32','2026-11-04 00:25:32'),('9788418037832','Trenza del mar Esmeralda','Novela Secreta 1 (Cosmere)',2023,560,'Español (Castellano)',13,8,'portadas/ESP_019_9788418037832.jpg','2026-11-03 18:25:30',NULL,NULL,'2026-11-04 00:25:30','2026-11-04 00:25:30'),('9788418037856','Yumi y el pintor de pesadillas','Novela Secreta 3 (Cosmere)',2023,528,'Español (Castellano)',13,8,'portadas/ESP_020_9788418037856.jpg','2026-11-03 18:25:30',NULL,NULL,'2026-11-04 00:25:30','2026-11-04 00:25:30'),('9788418045905','Twisted Love','Saga Twisted 1',2022,384,'Español (Castellano)',32,3,'portadas/ESP_054_9788418045905.jpg','2026-11-03 18:25:32',NULL,NULL,'2026-11-04 00:25:32','2026-11-04 00:25:32'),('9788418045912','Twisted Games','Saga Twisted 2',2023,448,'Español (Castellano)',33,3,'portadas/ESP_055_9788418045912.jpg','2026-11-03 18:25:32',NULL,NULL,'2026-11-04 00:25:32','2026-11-04 00:25:32'),('9788418045929','Twisted Hate','Saga Twisted 3',2023,512,'Español (Castellano)',51,3,'portadas/ESP_083_9788418045929.jpg','2026-11-03 18:25:33',NULL,NULL,'2026-11-04 00:25:33','2026-11-04 00:25:33'),('9788418045936','Twisted Lies','Saga Twisted 4',2023,576,'Español (Castellano)',52,3,'portadas/ESP_084_9788418045936.jpg','2026-11-03 18:25:34',NULL,NULL,'2026-11-04 00:25:34','2026-11-04 00:25:34'),('9788418045950','Rey de la ira','Serie Pecados 1',2023,416,'Español (Castellano)',50,3,'portadas/ESP_082_9788418045950.jpg','2026-11-03 18:25:33',NULL,NULL,'2026-11-04 00:25:33','2026-11-04 00:25:33'),('9788418057120','Boulevard','Libro 1',2021,368,'Español (Castellano)',47,15,'portadas/ESP_078_9788418057120.jpg','2026-11-03 18:25:33',NULL,NULL,'2026-11-04 00:25:33','2026-11-04 00:25:33'),('9788418057281','Antes de diciembre','Meses a tu lado 1',2021,496,'Español (Castellano)',36,15,'portadas/ESP_058_9788418057281.jpg','2026-11-03 18:25:32',NULL,NULL,'2026-11-04 00:25:32','2026-11-04 00:25:32'),('9788418057397','Después de diciembre','Meses a tu lado 2',2022,496,'Español (Castellano)',36,15,'portadas/ESP_075_9788418057397.jpg','2026-11-03 18:25:33',NULL,NULL,'2026-11-04 00:25:33','2026-11-04 00:25:33'),('9788418057779','Tres meses','Meses a tu lado 3',2023,528,'Español (Castellano)',36,15,'portadas/ESP_076_9788418057779.jpg','2026-11-03 18:25:33',NULL,NULL,'2026-11-04 00:25:33','2026-11-04 00:25:33'),('9788418118036','Hábitos atómicos','Cambios diminutos, resultados extraordinarios',2020,336,'Español (Castellano)',37,16,'portadas/ESP_059_9788418118036.jpg','2026-11-03 18:25:32',NULL,NULL,'2026-11-04 00:25:32','2026-11-04 00:25:32'),('9788419266507','Babel','O la necesidad de la violencia',2023,736,'Español (Castellano)',11,5,'portadas/ESP_017_9788419266507.jpg','2026-11-03 18:25:30',NULL,NULL,'2026-11-04 00:25:30','2026-11-04 00:25:30'),('9788419650009','Las luces de febrero','Meses a tu lado 4',2023,560,'Español (Castellano)',36,15,'portadas/ESP_077_9788419650009.jpg','2026-11-03 18:25:33',NULL,NULL,'2026-11-04 00:25:33','2026-11-04 00:25:33'),('9788420414065','La verdad sobre el caso Harry Quebert','Novela de intriga literaria',2016,672,'Español (Castellano)',62,4,'portadas/ESP_102_9788420414065.jpg','2026-11-03 18:25:35',NULL,NULL,'2026-11-04 00:25:35','2026-11-04 00:25:35'),('9788420433141','La novia gitana','Serie Inspectora Elena Blanco 1',2018,408,'Español (Castellano)',20,4,'portadas/ESP_039_9788420433141.jpg','2026-11-03 18:25:31',NULL,NULL,'2026-11-04 00:25:31','2026-11-04 00:25:31'),('9788420435534','La paciente silenciosa','Novela de intriga psicológica',2019,400,'Español (Castellano)',16,4,'portadas/ESP_037_9788420435534.jpg','2026-11-03 18:25:31',NULL,NULL,'2026-11-04 00:25:31','2026-11-04 00:25:31'),('9788420435572','La red púrpura','Serie Inspectora Elena Blanco 2',2019,432,'Español (Castellano)',22,4,'portadas/ESP_092_9788420435572.jpg','2026-11-03 18:25:34',NULL,NULL,'2026-11-04 00:25:34','2026-11-04 00:25:34'),('9788420435947','La nena','Serie Inspectora Elena Blanco 3',2020,392,'Español (Castellano)',22,4,'portadas/ESP_093_9788420435947.jpg','2026-11-03 18:25:34',NULL,NULL,'2026-11-04 00:25:34','2026-11-04 00:25:34'),('9788420453125','Casa de tierra y sangre','Ciudad Medialuna 1',2020,800,'Español (Castellano)',5,4,'portadas/ESP_007_9788420453125.jpg','2026-11-03 18:25:29',NULL,NULL,'2026-11-04 00:25:29','2026-11-04 00:25:29'),('9788420456867','Casa de cielo y aliento','Ciudad Medialuna 2',2022,840,'Español (Castellano)',5,4,'portadas/ESP_008_9788420456867.jpg','2026-11-03 18:25:29',NULL,NULL,'2026-11-04 00:25:29','2026-11-04 00:25:29'),('9788420461878','Las madres','Serie Inspectora Elena Blanco 4',2022,464,'Español (Castellano)',22,4,'portadas/ESP_094_9788420461878.jpg','2026-11-03 18:25:34',NULL,NULL,'2026-11-04 00:25:34','2026-11-04 00:25:34'),('9788420462745','El caso Alaska Sanders','Secuela de Harry Quebert',2022,592,'Español (Castellano)',18,4,'portadas/ESP_103_9788420462745.jpg','2026-11-03 18:25:35',NULL,NULL,'2026-11-04 00:25:35','2026-11-04 00:25:35'),('9788420464626','Mañana, y mañana, y mañana','Novela sobre el amor y los videojuegos',2023,448,'Español (Castellano)',63,23,'portadas/ESP_105_9788420464626.jpg','2026-11-03 18:25:35',NULL,NULL,'2026-11-04 00:25:35','2026-11-04 00:25:35'),('9788420476483','La furia','Novela de misterio',2024,336,'Español (Castellano)',19,4,'portadas/ESP_038_9788420476483.jpg','2026-11-03 18:25:31',NULL,NULL,'2026-11-04 00:25:31','2026-11-04 00:25:31'),('9788420476834','Casa de llama y sombra','Ciudad Medialuna 3',2024,864,'Español (Castellano)',5,4,'portadas/ESP_009_9788420476834.jpg','2026-11-03 18:25:29',NULL,NULL,'2026-11-04 00:25:29','2026-11-04 00:25:29'),('9788420477121','Un animal salvaje','Novela de suspense',2024,512,'Español (Castellano)',23,4,'portadas/ESP_042_9788420477121.jpg','2026-11-03 18:25:31',NULL,NULL,'2026-11-04 00:25:31','2026-11-04 00:25:31'),('9788423358892','La biblioteca de la medianoche','Novela filosófica de vida',2021,336,'Español (Castellano)',38,17,'portadas/ESP_060_9788423358892.jpg','2026-11-03 18:25:32',NULL,NULL,'2026-11-04 00:25:32','2026-11-04 00:25:32'),('9788427218673','Rojo, blanco y sangre azul','Comedia Romántica',2019,496,'Español (Castellano)',35,14,'portadas/ESP_057_9788427218673.jpg','2026-11-03 18:25:32',NULL,NULL,'2026-11-04 00:25:32','2026-11-04 00:25:32'),('9788432242120','La mala costumbre','Novela contemporánea',2023,256,'Español (Castellano)',41,20,'portadas/ESP_063_9788432242120.jpg','2026-11-03 18:25:32',NULL,NULL,'2026-11-04 00:25:32','2026-11-04 00:25:32'),('9788433998859','Nuestra parte de noche','Premio Herralde de Novela',2019,672,'Español (Castellano)',40,19,'portadas/ESP_062_9788433998859.jpg','2026-11-03 18:25:32',NULL,NULL,'2026-11-04 00:25:32','2026-11-04 00:25:32'),('9788466373722','La asistenta','Trilogía La asistenta 1',2023,336,'Español (Castellano)',16,10,'portadas/ESP_033_9788466373722.jpg','2026-11-03 18:25:30',NULL,NULL,'2026-11-04 00:25:30','2026-11-04 00:25:30'),('9788466374095','El secreto de la asistenta','Trilogía La asistenta 2',2023,336,'Español (Castellano)',16,10,'portadas/ESP_034_9788466374095.jpg','2026-11-03 18:25:30',NULL,NULL,'2026-11-04 00:25:30','2026-11-04 00:25:30'),('9788466376228','El recluso','Novela de suspense psicológico',2024,368,'Español (Castellano)',16,10,'portadas/ESP_036_9788466376228.jpg','2026-11-03 18:25:31',NULL,NULL,'2026-11-04 00:25:31','2026-11-04 00:25:31'),('9788466376822','La asistenta te vigila','Trilogía La asistenta 3',2024,368,'Español (Castellano)',16,10,'portadas/ESP_035_9788466376822.jpg','2026-11-03 18:25:31',NULL,NULL,'2026-11-04 00:25:31','2026-11-04 00:25:31'),('9788466654746','El paciente','Novela de suspense médico',2016,480,'Español (Castellano)',61,9,'portadas/ESP_101_9788466654746.jpg','2026-11-03 18:25:35',NULL,NULL,'2026-11-04 00:25:35','2026-11-04 00:25:35'),('9788466657969','Cicatriz','Novela de intriga y obsesión',2016,576,'Español (Castellano)',60,9,'portadas/ESP_100_9788466657969.jpg','2026-11-03 18:25:35',NULL,NULL,'2026-11-04 00:25:35','2026-11-04 00:25:35'),('9788466659734','El problema de los tres cuerpos','Trilogía de los Tres Cuerpos 1',2016,416,'Español (Castellano)',43,8,'portadas/ESP_065_9788466659734.jpg','2026-11-03 18:25:32',NULL,NULL,'2026-11-04 00:25:32','2026-11-04 00:25:32'),('9788466664417','Reina roja','Trilogía Antonia Scott 1',2018,568,'Español (Castellano)',15,9,'portadas/ESP_023_9788466664417.jpg','2026-11-03 18:25:30',NULL,NULL,'2026-11-04 00:25:30','2026-11-04 00:25:30'),('9788466666466','Loba negra','Trilogía Antonia Scott 2',2019,544,'Español (Castellano)',16,9,'portadas/ESP_024_9788466666466.jpg','2026-11-03 18:25:30',NULL,NULL,'2026-11-04 00:25:30','2026-11-04 00:25:30'),('9788466667609','El ritmo de la guerra','El Archivo de las Tormentas 4',2020,1408,'Español (Castellano)',13,8,'portadas/ESP_021_9788466667609.jpg','2026-11-03 18:25:30',NULL,NULL,'2026-11-04 00:25:30','2026-11-04 00:25:30'),('9788466668545','Rey blanco','Trilogía Antonia Scott 3',2020,528,'Español (Castellano)',16,9,'portadas/ESP_025_9788466668545.jpg','2026-11-03 18:25:30',NULL,NULL,'2026-11-04 00:25:30','2026-11-04 00:25:30'),('9788466669146','Proyecto Hail Mary','Novela de ciencia ficción dura',2021,624,'Español (Castellano)',42,8,'portadas/ESP_064_9788466669146.jpg','2026-11-03 18:25:32',NULL,NULL,'2026-11-04 00:25:32','2026-11-04 00:25:32'),('9788466672474','Todo arde','Serie Aura Reyes 1',2022,600,'Español (Castellano)',17,9,'portadas/ESP_026_9788466672474.jpg','2026-11-03 18:25:30',NULL,NULL,'2026-11-04 00:25:30','2026-11-04 00:25:30'),('9788466676342','Todo vuelve','Serie Aura Reyes 2',2023,608,'Español (Castellano)',17,9,'portadas/ESP_027_9788466676342.jpg','2026-11-03 18:25:30',NULL,NULL,'2026-11-04 00:25:30','2026-11-04 00:25:30'),('9788466679294','Rompehielos','Icebreaker (Saga Maple Hills 1)',2023,448,'Español (Castellano)',34,13,'portadas/ESP_056_9788466679294.jpg','2026-11-03 18:25:32',NULL,NULL,'2026-11-04 00:25:32','2026-11-04 00:25:32'),('9788466679300','Fuego salvaje','Wildfire (Saga Maple Hills 2)',2023,432,'Español (Castellano)',28,13,'portadas/ESP_085_9788466679300.jpg','2026-11-03 18:25:34',NULL,NULL,'2026-11-04 00:25:34','2026-11-04 00:25:34'),('9788483658963','El día que se perdió la cordura','Novela de suspense',2017,464,'Español (Castellano)',16,10,'portadas/ESP_028_9788483658963.jpg','2026-11-03 18:25:30',NULL,NULL,'2026-11-04 00:25:30','2026-11-04 00:25:30'),('9788491292029','El día que se perdió el amor','Bilogía de la Cordura 2',2018,416,'Español (Castellano)',16,10,'portadas/ESP_029_9788491292029.jpg','2026-11-03 18:25:30',NULL,NULL,'2026-11-04 00:25:30','2026-11-04 00:25:30'),('9788491292661','La chica de nieve','Serie Miren Triggs 1',2020,512,'Español (Castellano)',18,10,'portadas/ESP_030_9788491292661.jpg','2026-11-03 18:25:30',NULL,NULL,'2026-11-04 00:25:30','2026-11-04 00:25:30'),('9788491295242','El juego del alma','Serie Miren Triggs 2',2021,528,'Español (Castellano)',18,10,'portadas/ESP_031_9788491295242.jpg','2026-11-03 18:25:30',NULL,NULL,'2026-11-04 00:25:30','2026-11-04 00:25:30'),('9788491295594','El cuco de cristal','Novela de suspense',2023,496,'Español (Castellano)',16,10,'portadas/ESP_099_9788491295594.jpg','2026-11-03 18:25:35',NULL,NULL,'2026-11-04 00:25:35','2026-11-04 00:25:35'),('9788491298717','La grieta del silencio','Serie Miren Triggs 3',2024,448,'Español (Castellano)',18,10,'portadas/ESP_032_9788491298717.jpg','2026-11-03 18:25:30',NULL,NULL,'2026-11-04 00:25:30','2026-11-04 00:25:30'),('9788491815853','Lecciones de química','Novela feminista y comedia',2022,464,'Español (Castellano)',7,6,'portadas/ESP_0104_9788491815853.jpg','2026-11-03 18:25:29',NULL,NULL,'2026-11-04 00:25:29','2026-11-04 00:25:29'),('9788494801129','El príncipe cruel','Los habitantes del aire 1',2018,528,'Español (Castellano)',6,5,'portadas/ESP_010_9788494801129.jpg','2026-11-03 18:25:29',NULL,NULL,'2026-11-04 00:25:29','2026-11-04 00:25:29'),('9788499926223','Sapiens: De animales a dioses','Breve historia de la humanidad',2016,496,'Español (Castellano)',64,24,'portadas/ESP_106_9788499926223.jpg','2026-11-03 18:25:35',NULL,NULL,'2026-11-04 00:25:35','2026-11-04 00:25:35');
/*!40000 ALTER TABLE `libros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `libro_autors`
--

DROP TABLE IF EXISTS `libro_autors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `libro_autors` (
  `Codigo_ISBN` varchar(20) NOT NULL,
  `Codigo_Autor` int(10) unsigned NOT NULL,
  `Tipo_Autoria` enum('Principal','Coautor','Traductor') NOT NULL DEFAULT 'Principal',
  `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_modificacion` varchar(50) DEFAULT NULL,
  `motivo_modificacion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`Codigo_ISBN`,`Codigo_Autor`),
  KEY `libro_autors_codigo_autor_foreign` (`Codigo_Autor`),
  CONSTRAINT `libro_autors_codigo_autor_foreign` FOREIGN KEY (`Codigo_Autor`) REFERENCES `autors` (`Codigo_Autor`) ON DELETE CASCADE,
  CONSTRAINT `libro_autors_codigo_isbn_foreign` FOREIGN KEY (`Codigo_ISBN`) REFERENCES `libros` (`Codigo_ISBN`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `libro_autors`
--

LOCK TABLES `libro_autors` WRITE;
/*!40000 ALTER TABLE `libro_autors` DISABLE KEYS */;
INSERT INTO `libro_autors` VALUES ('9788401031380',33,'Principal','2026-11-03 18:25:32',NULL,NULL),('9788408154167',38,'Principal','2026-11-03 18:25:34',NULL,NULL),('9788408163381',34,'Principal','2026-11-03 18:25:32',NULL,NULL),('9788408163435',34,'Principal','2026-11-03 18:25:32',NULL,NULL),('9788408204688',21,'Principal','2026-11-03 18:25:32',NULL,NULL),('9788408209867',3,'Principal','2026-11-03 18:25:29',NULL,NULL),('9788408209874',3,'Principal','2026-11-03 18:25:29',NULL,NULL),('9788408209881',3,'Principal','2026-11-03 18:25:29',NULL,NULL),('9788408221449',21,'Principal','2026-11-03 18:25:32',NULL,NULL),('9788408225584',35,'Principal','2026-11-03 18:25:33',NULL,NULL),('9788408225591',35,'Principal','2026-11-03 18:25:33',NULL,NULL),('9788408226055',17,'Principal','2026-11-03 18:25:31',NULL,NULL),('9788408226062',21,'Principal','2026-11-03 18:25:33',NULL,NULL),('9788408232971',35,'Principal','2026-11-03 18:25:33',NULL,NULL),('9788408244196',35,'Principal','2026-11-03 18:25:33',NULL,NULL),('9788408249832',15,'Principal','2026-11-03 18:25:31',NULL,NULL),('9788408249849',3,'Principal','2026-11-03 18:25:29',NULL,NULL),('9788408252870',38,'Principal','2026-11-03 18:25:34',NULL,NULL),('9788408255147',21,'Principal','2026-11-03 18:25:31',NULL,NULL),('9788408257493',19,'Principal','2026-11-03 18:25:31',NULL,NULL),('9788408263142',21,'Principal','2026-11-03 18:25:33',NULL,NULL),('9788408263159',19,'Principal','2026-11-03 18:25:31',NULL,NULL),('9788408267195',19,'Principal','2026-11-03 18:25:31',NULL,NULL),('9788408268574',21,'Principal','2026-11-03 18:25:33',NULL,NULL),('9788408269557',38,'Principal','2026-11-03 18:25:35',NULL,NULL),('9788408272533',19,'Principal','2026-11-03 18:25:34',NULL,NULL),('9788408277255',15,'Principal','2026-11-03 18:25:34',NULL,NULL),('9788408279990',2,'Principal','2026-11-03 18:25:29',NULL,NULL),('9788408284550',2,'Principal','2026-11-03 18:25:29',NULL,NULL),('9788408287711',7,'Principal','2026-11-03 18:25:29',NULL,NULL),('9788408292838',15,'Principal','2026-11-03 18:25:31',NULL,NULL),('9788408293248',7,'Principal','2026-11-03 18:25:34',NULL,NULL),('9788416517275',18,'Principal','2026-11-03 18:25:31',NULL,NULL),('9788416517404',18,'Principal','2026-11-03 18:25:31',NULL,NULL),('9788416517619',18,'Principal','2026-11-03 18:25:34',NULL,NULL),('9788416517923',18,'Principal','2026-11-03 18:25:34',NULL,NULL),('9788416587421',10,'Principal','2026-11-03 18:25:30',NULL,NULL),('9788417517243',36,'Principal','2026-11-03 18:25:33',NULL,NULL),('9788417517595',36,'Principal','2026-11-03 18:25:33',NULL,NULL),('9788417589257',4,'Principal','2026-11-03 18:25:29',NULL,NULL),('9788417589639',8,'Principal','2026-11-03 18:25:30',NULL,NULL),('9788417589882',4,'Principal','2026-11-03 18:25:29',NULL,NULL),('9788417854447',6,'Principal','2026-11-03 18:25:29',NULL,NULL),('9788417854614',20,'Principal','2026-11-03 18:25:31',NULL,NULL),('9788417854720',6,'Principal','2026-11-03 18:25:29',NULL,NULL),('9788417854744',20,'Principal','2026-11-03 18:25:31',NULL,NULL),('9788417854898',20,'Principal','2026-11-03 18:25:34',NULL,NULL),('9788417854935',6,'Principal','2026-11-03 18:25:29',NULL,NULL),('9788417854973',20,'Principal','2026-11-03 18:25:34',NULL,NULL),('9788417860790',28,'Principal','2026-11-03 18:25:32',NULL,NULL),('9788418037832',9,'Principal','2026-11-03 18:25:30',NULL,NULL),('9788418037856',9,'Principal','2026-11-03 18:25:30',NULL,NULL),('9788418045905',22,'Principal','2026-11-03 18:25:32',NULL,NULL),('9788418045912',22,'Principal','2026-11-03 18:25:32',NULL,NULL),('9788418045929',22,'Principal','2026-11-03 18:25:33',NULL,NULL),('9788418045936',22,'Principal','2026-11-03 18:25:34',NULL,NULL),('9788418045950',22,'Principal','2026-11-03 18:25:33',NULL,NULL),('9788418057120',37,'Principal','2026-11-03 18:25:33',NULL,NULL),('9788418057281',25,'Principal','2026-11-03 18:25:32',NULL,NULL),('9788418057397',25,'Principal','2026-11-03 18:25:33',NULL,NULL),('9788418057779',25,'Principal','2026-11-03 18:25:33',NULL,NULL),('9788418118036',26,'Principal','2026-11-03 18:25:32',NULL,NULL),('9788419266507',8,'Principal','2026-11-03 18:25:30',NULL,NULL),('9788419650009',25,'Principal','2026-11-03 18:25:33',NULL,NULL),('9788420414065',16,'Principal','2026-11-03 18:25:35',NULL,NULL),('9788420433141',15,'Principal','2026-11-03 18:25:31',NULL,NULL),('9788420435534',14,'Principal','2026-11-03 18:25:31',NULL,NULL),('9788420435572',15,'Principal','2026-11-03 18:25:34',NULL,NULL),('9788420435947',15,'Principal','2026-11-03 18:25:34',NULL,NULL),('9788420453125',3,'Principal','2026-11-03 18:25:29',NULL,NULL),('9788420456867',3,'Principal','2026-11-03 18:25:29',NULL,NULL),('9788420461878',15,'Principal','2026-11-03 18:25:34',NULL,NULL),('9788420462745',16,'Principal','2026-11-03 18:25:35',NULL,NULL),('9788420464626',39,'Principal','2026-11-03 18:25:35',NULL,NULL),('9788420476483',14,'Principal','2026-11-03 18:25:31',NULL,NULL),('9788420476834',3,'Principal','2026-11-03 18:25:29',NULL,NULL),('9788420477121',16,'Principal','2026-11-03 18:25:31',NULL,NULL),('9788423358892',27,'Principal','2026-11-03 18:25:32',NULL,NULL),('9788427218673',24,'Principal','2026-11-03 18:25:32',NULL,NULL),('9788432242120',30,'Principal','2026-11-03 18:25:32',NULL,NULL),('9788433998859',29,'Principal','2026-11-03 18:25:32',NULL,NULL),('9788466373722',13,'Principal','2026-11-03 18:25:30',NULL,NULL),('9788466374095',13,'Principal','2026-11-03 18:25:31',NULL,NULL),('9788466376228',13,'Principal','2026-11-03 18:25:31',NULL,NULL),('9788466376822',13,'Principal','2026-11-03 18:25:31',NULL,NULL),('9788466654746',11,'Principal','2026-11-03 18:25:35',NULL,NULL),('9788466657969',11,'Principal','2026-11-03 18:25:35',NULL,NULL),('9788466659734',32,'Principal','2026-11-03 18:25:32',NULL,NULL),('9788466664417',11,'Principal','2026-11-03 18:25:30',NULL,NULL),('9788466666466',11,'Principal','2026-11-03 18:25:30',NULL,NULL),('9788466667609',9,'Principal','2026-11-03 18:25:30',NULL,NULL),('9788466668545',11,'Principal','2026-11-03 18:25:30',NULL,NULL),('9788466669146',31,'Principal','2026-11-03 18:25:32',NULL,NULL),('9788466672474',11,'Principal','2026-11-03 18:25:30',NULL,NULL),('9788466676342',11,'Principal','2026-11-03 18:25:30',NULL,NULL),('9788466679294',23,'Principal','2026-11-03 18:25:32',NULL,NULL),('9788466679300',23,'Principal','2026-11-03 18:25:34',NULL,NULL),('9788483658963',12,'Principal','2026-11-03 18:25:30',NULL,NULL),('9788491292029',12,'Principal','2026-11-03 18:25:30',NULL,NULL),('9788491292661',12,'Principal','2026-11-03 18:25:30',NULL,NULL),('9788491295242',12,'Principal','2026-11-03 18:25:30',NULL,NULL),('9788491295594',12,'Principal','2026-11-03 18:25:35',NULL,NULL),('9788491298717',12,'Principal','2026-11-03 18:25:30',NULL,NULL),('9788491815853',5,'Principal','2026-11-03 18:25:29',NULL,NULL),('9788494801129',4,'Principal','2026-11-03 18:25:29',NULL,NULL),('9788499926223',40,'Principal','2026-11-03 18:25:35',NULL,NULL);
/*!40000 ALTER TABLE `libro_autors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ejemplars`
--

DROP TABLE IF EXISTS `ejemplars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ejemplars` (
  `Codigo_Ejemplar` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Codigo_ISBN` varchar(20) NOT NULL,
  `Ubicacion_Estante` varchar(30) NOT NULL,
  `Estado_Ejemplar` enum('Disponible','Prestado','Mantenimiento','Perdido') NOT NULL DEFAULT 'Disponible',
  `Fecha_Adquisicion` date NOT NULL DEFAULT curdate(),
  `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_modificacion` varchar(50) DEFAULT NULL,
  `motivo_modificacion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`Codigo_Ejemplar`),
  KEY `ejemplars_codigo_isbn_foreign` (`Codigo_ISBN`),
  CONSTRAINT `ejemplars_codigo_isbn_foreign` FOREIGN KEY (`Codigo_ISBN`) REFERENCES `libros` (`Codigo_ISBN`)
) ENGINE=InnoDB AUTO_INCREMENT=319 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ejemplars`
--

LOCK TABLES `ejemplars` WRITE;
/*!40000 ALTER TABLE `ejemplars` DISABLE KEYS */;
INSERT INTO `ejemplars` VALUES (1,'9788408279990','Estante C-01','Disponible','2026-11-02','2026-11-03 21:12:33',NULL,NULL),(2,'9788408279990','Estante C-01','Disponible','2026-11-02','2026-11-03 18:25:29',NULL,NULL),(3,'9788408279990','Estante C-01','Disponible','2026-11-02','2026-11-03 18:25:29',NULL,NULL),(4,'9788408284550','Estante C-02','Disponible','2026-11-01','2026-11-03 18:25:29',NULL,NULL),(5,'9788408284550','Estante C-02','Disponible','2026-11-01','2026-11-03 18:25:29',NULL,NULL),(6,'9788408284550','Estante C-02','Disponible','2026-11-01','2026-11-03 18:25:29',NULL,NULL),(7,'9788408209867','Estante D-03','Disponible','2026-10-31','2026-11-03 18:25:29',NULL,NULL),(8,'9788408209867','Estante D-03','Disponible','2026-10-31','2026-11-03 18:25:29',NULL,NULL),(9,'9788408209867','Estante D-03','Disponible','2026-10-31','2026-11-03 18:25:29',NULL,NULL),(10,'9788408209874','Estante D-04','Disponible','2026-10-30','2026-11-03 18:25:29',NULL,NULL),(11,'9788408209874','Estante D-04','Disponible','2026-10-30','2026-11-03 18:25:29',NULL,NULL),(12,'9788408209874','Estante D-04','Disponible','2026-10-30','2026-11-03 18:25:29',NULL,NULL),(13,'9788408209881','Estante D-05','Disponible','2026-10-29','2026-11-03 18:25:29',NULL,NULL),(14,'9788408209881','Estante D-05','Disponible','2026-10-29','2026-11-03 18:25:29',NULL,NULL),(15,'9788408209881','Estante D-05','Disponible','2026-10-29','2026-11-03 18:25:29',NULL,NULL),(16,'9788408249849','Estante E-06','Disponible','2026-10-28','2026-11-03 18:25:29',NULL,NULL),(17,'9788408249849','Estante E-06','Disponible','2026-10-28','2026-11-03 18:25:29',NULL,NULL),(18,'9788408249849','Estante E-06','Prestado','2026-10-28','2026-11-03 20:34:13',NULL,NULL),(19,'9788420453125','Estante F-07','Disponible','2026-10-27','2026-11-03 18:25:29',NULL,NULL),(20,'9788420453125','Estante F-07','Disponible','2026-10-27','2026-11-03 18:25:29',NULL,NULL),(21,'9788420453125','Estante F-07','Disponible','2026-10-27','2026-11-03 18:25:29',NULL,NULL),(22,'9788420456867','Estante F-08','Disponible','2026-10-26','2026-11-03 18:25:29',NULL,NULL),(23,'9788420456867','Estante F-08','Disponible','2026-10-26','2026-11-03 18:25:29',NULL,NULL),(24,'9788420456867','Estante F-08','Disponible','2026-10-26','2026-11-03 18:25:29',NULL,NULL),(25,'9788420476834','Estante F-09','Disponible','2026-10-25','2026-11-03 18:25:29',NULL,NULL),(26,'9788420476834','Estante F-09','Disponible','2026-10-25','2026-11-03 18:25:29',NULL,NULL),(27,'9788420476834','Estante F-09','Disponible','2026-10-25','2026-11-03 18:25:29',NULL,NULL),(28,'9788494801129','Estante G-10','Perdido','2026-10-24','2026-11-03 20:34:13',NULL,NULL),(29,'9788494801129','Estante G-10','Disponible','2026-10-24','2026-11-03 18:25:29',NULL,NULL),(30,'9788494801129','Estante G-10','Disponible','2026-10-24','2026-11-03 18:25:29',NULL,NULL),(31,'9788491815853','Estante H-11','Disponible','2026-10-23','2026-11-03 18:25:29',NULL,NULL),(32,'9788491815853','Estante H-11','Disponible','2026-10-23','2026-11-03 18:25:29',NULL,NULL),(33,'9788491815853','Estante H-11','Disponible','2026-10-23','2026-11-03 18:25:29',NULL,NULL),(34,'9788417589257','Estante G-12','Disponible','2026-10-22','2026-11-03 18:25:29',NULL,NULL),(35,'9788417589257','Estante G-12','Disponible','2026-10-22','2026-11-03 18:25:29',NULL,NULL),(36,'9788417589257','Estante G-12','Disponible','2026-10-22','2026-11-03 18:25:29',NULL,NULL),(37,'9788417589882','Estante G-13','Disponible','2026-10-21','2026-11-03 18:25:29',NULL,NULL),(38,'9788417589882','Estante G-13','Disponible','2026-10-21','2026-11-03 18:25:29',NULL,NULL),(39,'9788417589882','Estante G-13','Disponible','2026-10-21','2026-11-03 18:25:29',NULL,NULL),(40,'9788417854447','Estante A-14','Disponible','2026-10-20','2026-11-03 18:25:29',NULL,NULL),(41,'9788417854447','Estante A-14','Disponible','2026-10-20','2026-11-03 18:25:29',NULL,NULL),(42,'9788417854447','Estante A-14','Disponible','2026-10-20','2026-11-03 18:25:29',NULL,NULL),(43,'9788417854720','Estante B-15','Prestado','2026-10-19','2026-11-03 20:34:13',NULL,NULL),(44,'9788417854720','Estante B-15','Disponible','2026-10-19','2026-11-03 18:25:29',NULL,NULL),(45,'9788417854720','Estante B-15','Disponible','2026-10-19','2026-11-03 18:25:29',NULL,NULL),(46,'9788417854935','Estante B-16','Perdido','2026-10-18','2026-11-03 20:34:13',NULL,NULL),(47,'9788417854935','Estante B-16','Disponible','2026-10-18','2026-11-03 18:25:29',NULL,NULL),(48,'9788417854935','Estante B-16','Disponible','2026-10-18','2026-11-03 18:25:29',NULL,NULL),(49,'9788408287711','Estante C-17','Disponible','2026-10-17','2026-11-03 18:25:29',NULL,NULL),(50,'9788408287711','Estante C-17','Disponible','2026-10-17','2026-11-03 18:25:29',NULL,NULL),(51,'9788408287711','Estante C-17','Disponible','2026-10-17','2026-11-03 18:25:29',NULL,NULL),(52,'9788419266507','Estante D-18','Disponible','2026-10-16','2026-11-03 18:25:30',NULL,NULL),(53,'9788419266507','Estante D-18','Disponible','2026-10-16','2026-11-03 18:25:30',NULL,NULL),(54,'9788419266507','Estante D-18','Disponible','2026-10-16','2026-11-03 18:25:30',NULL,NULL),(55,'9788417589639','Estante E-19','Disponible','2026-10-15','2026-11-03 18:25:30',NULL,NULL),(56,'9788417589639','Estante E-19','Disponible','2026-10-15','2026-11-03 18:25:30',NULL,NULL),(57,'9788417589639','Estante E-19','Disponible','2026-10-15','2026-11-03 18:25:30',NULL,NULL),(58,'9788418037832','Estante F-20','Disponible','2026-10-14','2026-11-03 20:32:25',NULL,NULL),(59,'9788418037832','Estante F-20','Disponible','2026-10-14','2026-11-03 18:25:30',NULL,NULL),(60,'9788418037832','Estante F-20','Disponible','2026-10-14','2026-11-03 18:25:30',NULL,NULL),(61,'9788418037856','Estante F-21','Disponible','2026-10-13','2026-11-03 18:25:30',NULL,NULL),(62,'9788418037856','Estante F-21','Disponible','2026-10-13','2026-11-03 18:25:30',NULL,NULL),(63,'9788418037856','Estante F-21','Disponible','2026-10-13','2026-11-03 18:25:30',NULL,NULL),(64,'9788466667609','Estante F-22','Disponible','2026-10-12','2026-11-03 18:25:30',NULL,NULL),(65,'9788466667609','Estante F-22','Disponible','2026-10-12','2026-11-03 18:25:30',NULL,NULL),(66,'9788466667609','Estante F-22','Disponible','2026-10-12','2026-11-03 18:25:30',NULL,NULL),(67,'9788416587421','Estante G-23','Disponible','2026-10-11','2026-11-03 18:25:30',NULL,NULL),(68,'9788416587421','Estante G-23','Disponible','2026-10-11','2026-11-03 18:25:30',NULL,NULL),(69,'9788416587421','Estante G-23','Prestado','2026-10-11','2026-11-03 20:34:13',NULL,NULL),(70,'9788466664417','Estante H-24','Disponible','2026-10-10','2026-11-03 18:25:30',NULL,NULL),(71,'9788466664417','Estante H-24','Disponible','2026-10-10','2026-11-03 18:25:30',NULL,NULL),(72,'9788466664417','Estante H-24','Disponible','2026-10-10','2026-11-03 18:25:30',NULL,NULL),(73,'9788466666466','Estante A-25','Disponible','2026-10-09','2026-11-03 18:25:30',NULL,NULL),(74,'9788466666466','Estante A-25','Disponible','2026-10-09','2026-11-03 18:25:30',NULL,NULL),(75,'9788466666466','Estante A-25','Perdido','2026-10-09','2026-11-03 20:34:13',NULL,NULL),(76,'9788466668545','Estante A-01','Disponible','2026-10-08','2026-11-03 18:25:30',NULL,NULL),(77,'9788466668545','Estante A-01','Disponible','2026-10-08','2026-11-03 18:25:30',NULL,NULL),(78,'9788466668545','Estante A-01','Disponible','2026-10-08','2026-11-03 18:25:30',NULL,NULL),(79,'9788466672474','Estante B-02','Disponible','2026-10-07','2026-11-03 18:25:30',NULL,NULL),(80,'9788466672474','Estante B-02','Disponible','2026-10-07','2026-11-03 18:25:30',NULL,NULL),(81,'9788466672474','Estante B-02','Disponible','2026-10-07','2026-11-03 18:25:30',NULL,NULL),(82,'9788466676342','Estante B-03','Disponible','2026-10-06','2026-11-03 18:25:30',NULL,NULL),(83,'9788466676342','Estante B-03','Disponible','2026-10-06','2026-11-03 18:25:30',NULL,NULL),(84,'9788466676342','Estante B-03','Disponible','2026-10-06','2026-11-03 18:25:30',NULL,NULL),(85,'9788483658963','Estante A-04','Disponible','2026-10-05','2026-11-03 18:25:30',NULL,NULL),(86,'9788483658963','Estante A-04','Disponible','2026-10-05','2026-11-03 18:25:30',NULL,NULL),(87,'9788483658963','Estante A-04','Disponible','2026-10-05','2026-11-03 18:25:30',NULL,NULL),(88,'9788491292029','Estante A-05','Disponible','2026-10-04','2026-11-03 18:25:30',NULL,NULL),(89,'9788491292029','Estante A-05','Disponible','2026-10-04','2026-11-03 18:25:30',NULL,NULL),(90,'9788491292029','Estante A-05','Disponible','2026-10-04','2026-11-03 18:25:30',NULL,NULL),(91,'9788491292661','Estante C-06','Perdido','2026-10-03','2026-11-03 20:34:13',NULL,NULL),(92,'9788491292661','Estante C-06','Disponible','2026-10-03','2026-11-03 18:25:30',NULL,NULL),(93,'9788491292661','Estante C-06','Disponible','2026-10-03','2026-11-03 18:25:30',NULL,NULL),(94,'9788491295242','Estante C-07','Disponible','2026-10-02','2026-11-03 18:25:30',NULL,NULL),(95,'9788491295242','Estante C-07','Disponible','2026-10-02','2026-11-03 20:32:25',NULL,NULL),(96,'9788491295242','Estante C-07','Disponible','2026-10-02','2026-11-03 18:25:30',NULL,NULL),(97,'9788491298717','Estante C-08','Disponible','2026-10-01','2026-11-03 18:25:30',NULL,NULL),(98,'9788491298717','Estante C-08','Disponible','2026-10-01','2026-11-03 18:25:30',NULL,NULL),(99,'9788491298717','Estante C-08','Disponible','2026-10-01','2026-11-03 18:25:30',NULL,NULL),(100,'9788466373722','Estante A-09','Disponible','2026-09-30','2026-11-03 18:25:30',NULL,NULL),(101,'9788466373722','Estante A-09','Disponible','2026-09-30','2026-11-03 18:25:30',NULL,NULL),(102,'9788466373722','Estante A-09','Disponible','2026-09-30','2026-11-03 18:25:30',NULL,NULL),(103,'9788466374095','Estante A-10','Disponible','2026-09-29','2026-11-03 18:25:31',NULL,NULL),(104,'9788466374095','Estante A-10','Disponible','2026-09-29','2026-11-03 18:25:31',NULL,NULL),(105,'9788466374095','Estante A-10','Disponible','2026-09-29','2026-11-03 18:25:31',NULL,NULL),(106,'9788466376822','Estante A-11','Disponible','2026-09-28','2026-11-03 18:25:31',NULL,NULL),(107,'9788466376822','Estante A-11','Disponible','2026-09-28','2026-11-03 18:25:31',NULL,NULL),(108,'9788466376822','Estante A-11','Disponible','2026-09-28','2026-11-03 18:25:31',NULL,NULL),(109,'9788466376228','Estante A-12','Disponible','2026-09-27','2026-11-03 18:25:31',NULL,NULL),(110,'9788466376228','Estante A-12','Disponible','2026-09-27','2026-11-03 18:25:31',NULL,NULL),(111,'9788466376228','Estante A-12','Disponible','2026-09-27','2026-11-03 18:25:31',NULL,NULL),(112,'9788420435534','Estante A-13','Prestado','2026-09-26','2026-11-03 20:34:13',NULL,NULL),(113,'9788420435534','Estante A-13','Disponible','2026-09-26','2026-11-03 18:25:31',NULL,NULL),(114,'9788420435534','Estante A-13','Disponible','2026-09-26','2026-11-03 18:25:31',NULL,NULL),(115,'9788420476483','Estante D-14','Disponible','2026-09-25','2026-11-03 18:25:31',NULL,NULL),(116,'9788420476483','Estante D-14','Disponible','2026-09-25','2026-11-03 18:25:31',NULL,NULL),(117,'9788420476483','Estante D-14','Disponible','2026-09-25','2026-11-03 18:25:31',NULL,NULL),(118,'9788420433141','Estante E-15','Disponible','2026-09-24','2026-11-03 18:25:31',NULL,NULL),(119,'9788420433141','Estante E-15','Disponible','2026-09-24','2026-11-03 18:25:31',NULL,NULL),(120,'9788420433141','Estante E-15','Disponible','2026-09-24','2026-11-03 18:25:31',NULL,NULL),(121,'9788408249832','Estante F-16','Disponible','2026-09-23','2026-11-03 18:25:31',NULL,NULL),(122,'9788408249832','Estante F-16','Disponible','2026-09-23','2026-11-03 18:25:31',NULL,NULL),(123,'9788408249832','Estante F-16','Disponible','2026-09-23','2026-11-03 18:25:31',NULL,NULL),(124,'9788408292838','Estante G-17','Disponible','2026-09-22','2026-11-03 18:25:31',NULL,NULL),(125,'9788408292838','Estante G-17','Disponible','2026-09-22','2026-11-03 18:25:31',NULL,NULL),(126,'9788408292838','Estante G-17','Disponible','2026-09-22','2026-11-03 18:25:31',NULL,NULL),(127,'9788420477121','Estante H-18','Disponible','2026-09-21','2026-11-03 18:25:31',NULL,NULL),(128,'9788420477121','Estante H-18','Disponible','2026-09-21','2026-11-03 18:25:31',NULL,NULL),(129,'9788420477121','Estante H-18','Disponible','2026-09-21','2026-11-03 18:25:31',NULL,NULL),(130,'9788408226055','Estante A-19','Disponible','2026-09-20','2026-11-03 18:25:31',NULL,NULL),(131,'9788408226055','Estante A-19','Disponible','2026-09-20','2026-11-03 18:25:31',NULL,NULL),(132,'9788408226055','Estante A-19','Disponible','2026-09-20','2026-11-03 18:25:31',NULL,NULL),(133,'9788416517275','Estante B-20','Disponible','2026-09-19','2026-11-03 18:25:31',NULL,NULL),(134,'9788416517275','Estante B-20','Perdido','2026-09-19','2026-11-03 20:34:13',NULL,NULL),(135,'9788416517275','Estante B-20','Disponible','2026-09-19','2026-11-03 18:25:31',NULL,NULL),(136,'9788416517404','Estante C-21','Prestado','2026-09-18','2026-11-03 20:34:13',NULL,NULL),(137,'9788416517404','Estante C-21','Disponible','2026-09-18','2026-11-03 18:25:31',NULL,NULL),(138,'9788416517404','Estante C-21','Disponible','2026-09-18','2026-11-03 18:25:31',NULL,NULL),(139,'9788408257493','Estante D-22','Disponible','2026-09-17','2026-11-03 18:25:31',NULL,NULL),(140,'9788408257493','Estante D-22','Disponible','2026-09-17','2026-11-03 18:25:31',NULL,NULL),(141,'9788408257493','Estante D-22','Disponible','2026-09-17','2026-11-03 18:25:31',NULL,NULL),(142,'9788408267195','Estante E-23','Disponible','2026-09-16','2026-11-03 18:25:31',NULL,NULL),(143,'9788408267195','Estante E-23','Disponible','2026-09-16','2026-11-03 18:25:31',NULL,NULL),(144,'9788408267195','Estante E-23','Disponible','2026-09-16','2026-11-03 18:25:31',NULL,NULL),(145,'9788408263159','Estante F-24','Disponible','2026-09-15','2026-11-03 18:25:31',NULL,NULL),(146,'9788408263159','Estante F-24','Disponible','2026-09-15','2026-11-03 18:25:31',NULL,NULL),(147,'9788408263159','Estante F-24','Disponible','2026-09-15','2026-11-03 18:25:31',NULL,NULL),(148,'9788417854614','Estante G-25','Disponible','2026-09-14','2026-11-03 18:25:31',NULL,NULL),(149,'9788417854614','Estante G-25','Disponible','2026-09-14','2026-11-03 18:25:31',NULL,NULL),(150,'9788417854614','Estante G-25','Disponible','2026-09-14','2026-11-03 18:25:31',NULL,NULL),(151,'9788417854744','Estante G-01','Disponible','2026-09-13','2026-11-03 18:25:31',NULL,NULL),(152,'9788417854744','Estante G-01','Disponible','2026-09-13','2026-11-03 18:25:31',NULL,NULL),(153,'9788417854744','Estante G-01','Perdido','2026-09-13','2026-11-03 20:34:13',NULL,NULL),(154,'9788408255147','Estante H-02','Disponible','2026-09-12','2026-11-03 18:25:31',NULL,NULL),(155,'9788408255147','Estante H-02','Disponible','2026-09-12','2026-11-03 20:32:25',NULL,NULL),(156,'9788408255147','Estante H-02','Disponible','2026-09-12','2026-11-03 18:25:31',NULL,NULL),(157,'9788408204688','Estante E-03','Disponible','2026-09-11','2026-11-03 18:25:32',NULL,NULL),(158,'9788408204688','Estante E-03','Disponible','2026-09-11','2026-11-03 18:25:32',NULL,NULL),(159,'9788408204688','Estante E-03','Disponible','2026-09-11','2026-11-03 18:25:32',NULL,NULL),(160,'9788408221449','Estante E-04','Disponible','2026-09-10','2026-11-03 18:25:32',NULL,NULL),(161,'9788408221449','Estante E-04','Disponible','2026-09-10','2026-11-03 18:25:32',NULL,NULL),(162,'9788408221449','Estante E-04','Disponible','2026-09-10','2026-11-03 18:25:32',NULL,NULL),(163,'9788418045905','Estante A-05','Disponible','2026-09-09','2026-11-03 18:25:32',NULL,NULL),(164,'9788418045905','Estante A-05','Disponible','2026-09-09','2026-11-03 18:25:32',NULL,NULL),(165,'9788418045905','Estante A-05','Disponible','2026-09-09','2026-11-03 18:25:32',NULL,NULL),(166,'9788418045912','Estante B-06','Disponible','2026-09-08','2026-11-03 18:25:32',NULL,NULL),(167,'9788418045912','Estante B-06','Disponible','2026-09-08','2026-11-03 18:25:32',NULL,NULL),(168,'9788418045912','Estante B-06','Disponible','2026-09-08','2026-11-03 18:25:32',NULL,NULL),(169,'9788466679294','Estante C-07','Disponible','2026-09-07','2026-11-03 18:25:32',NULL,NULL),(170,'9788466679294','Estante C-07','Perdido','2026-09-07','2026-11-03 20:34:13',NULL,NULL),(171,'9788466679294','Estante C-07','Disponible','2026-09-07','2026-11-03 18:25:32',NULL,NULL),(172,'9788427218673','Estante D-08','Disponible','2026-09-06','2026-11-03 18:25:32',NULL,NULL),(173,'9788427218673','Estante D-08','Disponible','2026-09-06','2026-11-03 18:25:32',NULL,NULL),(174,'9788427218673','Estante D-08','Disponible','2026-09-06','2026-11-03 18:25:32',NULL,NULL),(175,'9788418057281','Estante E-09','Disponible','2026-09-05','2026-11-03 18:25:32',NULL,NULL),(176,'9788418057281','Estante E-09','Disponible','2026-09-05','2026-11-03 18:25:32',NULL,NULL),(177,'9788418057281','Estante E-09','Disponible','2026-09-05','2026-11-03 18:25:32',NULL,NULL),(178,'9788418118036','Estante F-10','Disponible','2026-09-04','2026-11-03 18:25:32',NULL,NULL),(179,'9788418118036','Estante F-10','Disponible','2026-09-04','2026-11-03 18:25:32',NULL,NULL),(180,'9788418118036','Estante F-10','Disponible','2026-09-04','2026-11-03 18:25:32',NULL,NULL),(181,'9788423358892','Estante G-11','Disponible','2026-09-03','2026-11-03 18:25:32',NULL,NULL),(182,'9788423358892','Estante G-11','Disponible','2026-09-03','2026-11-03 18:25:32',NULL,NULL),(183,'9788423358892','Estante G-11','Disponible','2026-09-03','2026-11-03 18:25:32',NULL,NULL),(184,'9788417860790','Estante H-12','Disponible','2026-09-02','2026-11-03 18:25:32',NULL,NULL),(185,'9788417860790','Estante H-12','Disponible','2026-09-02','2026-11-03 18:25:32',NULL,NULL),(186,'9788417860790','Estante H-12','Disponible','2026-09-02','2026-11-03 18:25:32',NULL,NULL),(187,'9788433998859','Estante A-13','Disponible','2026-09-01','2026-11-03 18:25:32',NULL,NULL),(188,'9788433998859','Estante A-13','Disponible','2026-09-01','2026-11-03 18:25:32',NULL,NULL),(189,'9788433998859','Estante A-13','Disponible','2026-09-01','2026-11-03 18:25:32',NULL,NULL),(190,'9788432242120','Estante B-14','Disponible','2026-08-31','2026-11-03 18:25:32',NULL,NULL),(191,'9788432242120','Estante B-14','Disponible','2026-08-31','2026-11-03 18:25:32',NULL,NULL),(192,'9788432242120','Estante B-14','Disponible','2026-08-31','2026-11-03 18:25:32',NULL,NULL),(193,'9788466669146','Estante C-15','Disponible','2026-08-30','2026-11-03 18:25:32',NULL,NULL),(194,'9788466669146','Estante C-15','Disponible','2026-08-30','2026-11-03 18:25:32',NULL,NULL),(195,'9788466669146','Estante C-15','Disponible','2026-08-30','2026-11-03 18:25:32',NULL,NULL),(196,'9788466659734','Estante D-16','Disponible','2026-08-29','2026-11-03 20:32:25',NULL,NULL),(197,'9788466659734','Estante D-16','Disponible','2026-08-29','2026-11-03 18:25:32',NULL,NULL),(198,'9788466659734','Estante D-16','Disponible','2026-08-29','2026-11-03 18:25:32',NULL,NULL),(199,'9788401031380','Estante E-17','Prestado','2026-08-28','2026-11-03 21:03:52',NULL,NULL),(200,'9788401031380','Estante E-17','Disponible','2026-08-28','2026-11-03 18:25:32',NULL,NULL),(201,'9788401031380','Estante E-17','Disponible','2026-08-28','2026-11-03 18:25:32',NULL,NULL),(202,'9788408163435','Estante F-18','Disponible','2026-08-27','2026-11-03 18:25:32',NULL,NULL),(203,'9788408163435','Estante F-18','Disponible','2026-08-27','2026-11-03 18:25:32',NULL,NULL),(204,'9788408163435','Estante F-18','Disponible','2026-08-27','2026-11-03 18:25:32',NULL,NULL),(205,'9788408163381','Estante F-19','Mantenimiento','2026-08-26','2026-11-03 20:34:13',NULL,NULL),(206,'9788408163381','Estante F-19','Disponible','2026-08-26','2026-11-03 18:25:32',NULL,NULL),(207,'9788408163381','Estante F-19','Disponible','2026-08-26','2026-11-03 18:25:32',NULL,NULL),(208,'9788408225584','Estante G-20','Disponible','2026-08-25','2026-11-03 18:25:33',NULL,NULL),(209,'9788408225584','Estante G-20','Disponible','2026-08-25','2026-11-03 18:25:33',NULL,NULL),(210,'9788408225584','Estante G-20','Disponible','2026-08-25','2026-11-03 18:25:33',NULL,NULL),(211,'9788408225591','Estante G-21','Disponible','2026-08-24','2026-11-03 18:25:33',NULL,NULL),(212,'9788408225591','Estante G-21','Disponible','2026-08-24','2026-11-03 18:25:33',NULL,NULL),(213,'9788408225591','Estante G-21','Disponible','2026-08-24','2026-11-03 18:25:33',NULL,NULL),(214,'9788408232971','Estante G-22','Disponible','2026-08-23','2026-11-03 18:25:33',NULL,NULL),(215,'9788408232971','Estante G-22','Disponible','2026-08-23','2026-11-03 18:25:33',NULL,NULL),(216,'9788408232971','Estante G-22','Disponible','2026-08-23','2026-11-03 18:25:33',NULL,NULL),(217,'9788408244196','Estante G-23','Disponible','2026-08-22','2026-11-03 18:25:33',NULL,NULL),(218,'9788408244196','Estante G-23','Disponible','2026-08-22','2026-11-03 18:25:33',NULL,NULL),(219,'9788408244196','Estante G-23','Disponible','2026-08-22','2026-11-03 18:25:33',NULL,NULL),(220,'9788417517243','Estante B-24','Disponible','2026-08-21','2026-11-03 18:25:33',NULL,NULL),(221,'9788417517243','Estante B-24','Disponible','2026-08-21','2026-11-03 18:25:33',NULL,NULL),(222,'9788417517243','Estante B-24','Disponible','2026-08-21','2026-11-03 18:25:33',NULL,NULL),(223,'9788417517595','Estante B-25','Disponible','2026-08-20','2026-11-03 20:32:25',NULL,NULL),(224,'9788417517595','Estante B-25','Disponible','2026-08-20','2026-11-03 18:25:33',NULL,NULL),(225,'9788417517595','Estante B-25','Disponible','2026-08-20','2026-11-03 18:25:33',NULL,NULL),(226,'9788418057397','Estante E-01','Disponible','2026-08-19','2026-11-03 18:25:33',NULL,NULL),(227,'9788418057397','Estante E-01','Disponible','2026-08-19','2026-11-03 18:25:33',NULL,NULL),(228,'9788418057397','Estante E-01','Disponible','2026-08-19','2026-11-03 20:32:25',NULL,NULL),(229,'9788418057779','Estante E-02','Disponible','2026-08-18','2026-11-03 18:25:33',NULL,NULL),(230,'9788418057779','Estante E-02','Disponible','2026-08-18','2026-11-03 18:25:33',NULL,NULL),(231,'9788418057779','Estante E-02','Disponible','2026-08-18','2026-11-03 18:25:33',NULL,NULL),(232,'9788419650009','Estante E-03','Disponible','2026-08-17','2026-11-03 18:25:33',NULL,NULL),(233,'9788419650009','Estante E-03','Disponible','2026-08-17','2026-11-03 18:25:33',NULL,NULL),(234,'9788419650009','Estante E-03','Disponible','2026-08-17','2026-11-03 18:25:33',NULL,NULL),(235,'9788418057120','Estante H-04','Disponible','2026-08-16','2026-11-03 18:25:33',NULL,NULL),(236,'9788418057120','Estante H-04','Disponible','2026-08-16','2026-11-03 18:25:33',NULL,NULL),(237,'9788418057120','Estante H-04','Perdido','2026-08-16','2026-11-03 20:34:13',NULL,NULL),(238,'9788408268574','Estante E-05','Disponible','2026-08-15','2026-11-03 18:25:33',NULL,NULL),(239,'9788408268574','Estante E-05','Disponible','2026-08-15','2026-11-03 18:25:33',NULL,NULL),(240,'9788408268574','Estante E-05','Disponible','2026-08-15','2026-11-03 18:25:33',NULL,NULL),(241,'9788408263142','Estante A-06','Disponible','2026-08-14','2026-11-03 18:25:33',NULL,NULL),(242,'9788408263142','Estante A-06','Disponible','2026-08-14','2026-11-03 18:25:33',NULL,NULL),(243,'9788408263142','Estante A-06','Disponible','2026-08-14','2026-11-03 18:25:33',NULL,NULL),(244,'9788408226062','Estante B-07','Disponible','2026-08-13','2026-11-03 18:25:33',NULL,NULL),(245,'9788408226062','Estante B-07','Disponible','2026-08-13','2026-11-03 18:25:33',NULL,NULL),(246,'9788408226062','Estante B-07','Disponible','2026-08-13','2026-11-03 20:32:25',NULL,NULL),(247,'9788418045950','Estante C-08','Disponible','2026-08-12','2026-11-03 18:25:33',NULL,NULL),(248,'9788418045950','Estante C-08','Disponible','2026-08-12','2026-11-03 18:25:33',NULL,NULL),(249,'9788418045950','Estante C-08','Prestado','2026-08-12','2026-11-03 20:34:13',NULL,NULL),(250,'9788418045929','Estante D-09','Perdido','2026-08-11','2026-11-03 20:34:13',NULL,NULL),(251,'9788418045929','Estante D-09','Perdido','2026-08-11','2026-11-03 20:34:13',NULL,NULL),(252,'9788418045929','Estante D-09','Disponible','2026-08-11','2026-11-03 20:32:25',NULL,NULL),(253,'9788418045936','Estante E-10','Disponible','2026-08-10','2026-11-03 18:25:34',NULL,NULL),(254,'9788418045936','Estante E-10','Perdido','2026-08-10','2026-11-03 20:34:13',NULL,NULL),(255,'9788418045936','Estante E-10','Disponible','2026-08-10','2026-11-03 18:25:34',NULL,NULL),(256,'9788466679300','Estante E-11','Disponible','2026-08-09','2026-11-03 18:25:34',NULL,NULL),(257,'9788466679300','Estante E-11','Prestado','2026-08-09','2026-11-03 20:34:13',NULL,NULL),(258,'9788466679300','Estante E-11','Prestado','2026-08-09','2026-11-03 20:34:13',NULL,NULL),(259,'9788408293248','Estante C-12','Disponible','2026-08-08','2026-11-03 18:25:34',NULL,NULL),(260,'9788408293248','Estante C-12','Disponible','2026-08-08','2026-11-03 18:25:34',NULL,NULL),(261,'9788408293248','Estante C-12','Disponible','2026-08-08','2026-11-03 18:25:34',NULL,NULL),(262,'9788408272533','Estante F-13','Disponible','2026-08-07','2026-11-03 18:25:34',NULL,NULL),(263,'9788408272533','Estante F-13','Disponible','2026-08-07','2026-11-03 18:25:34',NULL,NULL),(264,'9788408272533','Estante F-13','Disponible','2026-08-07','2026-11-03 18:25:34',NULL,NULL),(265,'9788416517619','Estante G-14','Disponible','2026-08-06','2026-11-03 18:25:34',NULL,NULL),(266,'9788416517619','Estante G-14','Disponible','2026-08-06','2026-11-03 18:25:34',NULL,NULL),(267,'9788416517619','Estante G-14','Disponible','2026-08-06','2026-11-03 18:25:34',NULL,NULL),(268,'9788416517923','Estante H-15','Disponible','2026-08-05','2026-11-03 18:25:34',NULL,NULL),(269,'9788416517923','Estante H-15','Disponible','2026-08-05','2026-11-03 18:25:34',NULL,NULL),(270,'9788416517923','Estante H-15','Disponible','2026-08-05','2026-11-03 18:25:34',NULL,NULL),(271,'9788417854898','Estante G-16','Disponible','2026-11-02','2026-11-03 18:25:34',NULL,NULL),(272,'9788417854898','Estante G-16','Disponible','2026-11-02','2026-11-03 18:25:34',NULL,NULL),(273,'9788417854898','Estante G-16','Disponible','2026-11-02','2026-11-03 18:25:34',NULL,NULL),(274,'9788417854973','Estante A-17','Disponible','2026-11-01','2026-11-03 18:25:34',NULL,NULL),(275,'9788417854973','Estante A-17','Disponible','2026-11-01','2026-11-03 18:25:34',NULL,NULL),(276,'9788417854973','Estante A-17','Disponible','2026-11-01','2026-11-03 18:25:34',NULL,NULL),(277,'9788420435572','Estante G-18','Disponible','2026-10-31','2026-11-03 18:25:34',NULL,NULL),(278,'9788420435572','Estante G-18','Disponible','2026-10-31','2026-11-03 18:25:34',NULL,NULL),(279,'9788420435572','Estante G-18','Disponible','2026-10-31','2026-11-03 18:25:34',NULL,NULL),(280,'9788420435947','Estante G-19','Disponible','2026-10-30','2026-11-03 18:25:34',NULL,NULL),(281,'9788420435947','Estante G-19','Disponible','2026-10-30','2026-11-03 18:25:34',NULL,NULL),(282,'9788420435947','Estante G-19','Disponible','2026-10-30','2026-11-03 18:25:34',NULL,NULL),(283,'9788420461878','Estante G-20','Disponible','2026-10-29','2026-11-03 18:25:34',NULL,NULL),(284,'9788420461878','Estante G-20','Disponible','2026-10-29','2026-11-03 18:25:34',NULL,NULL),(285,'9788420461878','Estante G-20','Disponible','2026-10-29','2026-11-03 18:25:34',NULL,NULL),(286,'9788408277255','Estante F-21','Disponible','2026-10-28','2026-11-03 18:25:34',NULL,NULL),(287,'9788408277255','Estante F-21','Disponible','2026-10-28','2026-11-03 18:25:34',NULL,NULL),(288,'9788408277255','Estante F-21','Disponible','2026-10-28','2026-11-03 20:32:25',NULL,NULL),(289,'9788408154167','Estante B-22','Disponible','2026-10-27','2026-11-03 18:25:34',NULL,NULL),(290,'9788408154167','Estante B-22','Disponible','2026-10-27','2026-11-03 18:25:34',NULL,NULL),(291,'9788408154167','Estante B-22','Disponible','2026-10-27','2026-11-03 18:25:34',NULL,NULL),(292,'9788408252870','Estante C-23','Disponible','2026-10-26','2026-11-03 18:25:34',NULL,NULL),(293,'9788408252870','Estante C-23','Disponible','2026-10-26','2026-11-03 18:25:34',NULL,NULL),(294,'9788408252870','Estante C-23','Disponible','2026-10-26','2026-11-03 18:25:34',NULL,NULL),(295,'9788408269557','Estante D-24','Disponible','2026-10-25','2026-11-03 18:25:35',NULL,NULL),(296,'9788408269557','Estante D-24','Disponible','2026-10-25','2026-11-03 18:25:35',NULL,NULL),(297,'9788408269557','Estante D-24','Disponible','2026-10-25','2026-11-03 18:25:35',NULL,NULL),(298,'9788491295594','Estante A-25','Disponible','2026-10-24','2026-11-03 18:25:35',NULL,NULL),(299,'9788491295594','Estante A-25','Disponible','2026-10-24','2026-11-03 20:32:25',NULL,NULL),(300,'9788491295594','Estante A-25','Disponible','2026-10-24','2026-11-03 18:25:35',NULL,NULL),(301,'9788466657969','Estante E-01','Disponible','2026-10-23','2026-11-03 18:25:35',NULL,NULL),(302,'9788466657969','Estante E-01','Disponible','2026-10-23','2026-11-03 18:25:35',NULL,NULL),(303,'9788466657969','Estante E-01','Disponible','2026-10-23','2026-11-03 18:25:35',NULL,NULL),(304,'9788466654746','Estante F-02','Disponible','2026-10-22','2026-11-03 18:25:35',NULL,NULL),(305,'9788466654746','Estante F-02','Disponible','2026-10-22','2026-11-03 18:25:35',NULL,NULL),(306,'9788466654746','Estante F-02','Disponible','2026-10-22','2026-11-03 18:25:35',NULL,NULL),(307,'9788420414065','Estante G-03','Disponible','2026-10-21','2026-11-03 18:25:35',NULL,NULL),(308,'9788420414065','Estante G-03','Disponible','2026-10-21','2026-11-03 18:25:35',NULL,NULL),(309,'9788420414065','Estante G-03','Perdido','2026-10-21','2026-11-03 20:34:13',NULL,NULL),(310,'9788420462745','Estante C-04','Disponible','2026-10-20','2026-11-03 18:25:35',NULL,NULL),(311,'9788420462745','Estante C-04','Disponible','2026-10-20','2026-11-03 18:25:35',NULL,NULL),(312,'9788420462745','Estante C-04','Disponible','2026-10-20','2026-11-03 18:25:35',NULL,NULL),(313,'9788420464626','Estante H-05','Disponible','2026-10-19','2026-11-03 18:25:35',NULL,NULL),(314,'9788420464626','Estante H-05','Perdido','2026-10-19','2026-11-03 20:34:13',NULL,NULL),(315,'9788420464626','Estante H-05','Disponible','2026-10-19','2026-11-03 18:25:35',NULL,NULL),(316,'9788499926223','Estante A-06','Disponible','2026-10-18','2026-11-03 18:25:35',NULL,NULL),(317,'9788499926223','Estante A-06','Disponible','2026-10-18','2026-11-03 18:25:35',NULL,NULL),(318,'9788499926223','Estante A-06','Disponible','2026-10-18','2026-11-03 18:25:35',NULL,NULL);
/*!40000 ALTER TABLE `ejemplars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservas`
--

DROP TABLE IF EXISTS `reservas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reservas` (
  `Codigo_Reserva` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Codigo_Socio` int(10) unsigned NOT NULL,
  `Codigo_ISBN` varchar(20) NOT NULL,
  `FechaReserva` date NOT NULL DEFAULT curdate(),
  `EstadoReserva` enum('Pendiente','Atendida','Cancelada') NOT NULL DEFAULT 'Pendiente',
  `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_modificacion` varchar(50) DEFAULT NULL,
  `motivo_modificacion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`Codigo_Reserva`),
  KEY `reservas_codigo_socio_foreign` (`Codigo_Socio`),
  KEY `reservas_codigo_isbn_foreign` (`Codigo_ISBN`),
  CONSTRAINT `reservas_codigo_isbn_foreign` FOREIGN KEY (`Codigo_ISBN`) REFERENCES `libros` (`Codigo_ISBN`),
  CONSTRAINT `reservas_codigo_socio_foreign` FOREIGN KEY (`Codigo_Socio`) REFERENCES `socios` (`Codigo_Socio`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservas`
--

LOCK TABLES `reservas` WRITE;
/*!40000 ALTER TABLE `reservas` DISABLE KEYS */;
INSERT INTO `reservas` VALUES (17,34,'9788408225584','2026-10-05','Pendiente','2026-11-03 20:34:13',NULL,NULL),(18,32,'9788401031380','2026-10-09','Pendiente','2026-11-03 20:34:13',NULL,NULL),(19,38,'9788416517923','2026-10-07','Pendiente','2026-11-03 20:34:13',NULL,NULL),(20,39,'9788408287711','2026-10-28','Pendiente','2026-11-03 20:34:13',NULL,NULL),(21,43,'9788491292029','2026-04-15','Atendida','2026-11-03 20:34:13',NULL,NULL),(22,38,'9788491295242','2026-06-09','Atendida','2026-11-03 20:34:13',NULL,NULL),(23,41,'9788408255147','2025-07-14','Cancelada','2026-11-03 20:34:13',NULL,NULL),(24,31,'9788420435947','2025-11-16','Cancelada','2026-11-03 20:34:13',NULL,NULL);
/*!40000 ALTER TABLE `reservas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prestamos`
--

DROP TABLE IF EXISTS `prestamos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prestamos` (
  `Codigo_Prestamo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Codigo_Socio` int(10) unsigned NOT NULL,
  `Codigo_Bibliotecario` int(10) unsigned NOT NULL,
  `EstadoPrestamo` enum('Activo','Concluido','Vencido') NOT NULL DEFAULT 'Activo',
  `FechaPrestamo` date NOT NULL DEFAULT curdate(),
  `FechaLimite_Devolucion` date NOT NULL,
  `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_modificacion` varchar(50) DEFAULT NULL,
  `motivo_modificacion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`Codigo_Prestamo`),
  KEY `prestamos_codigo_bibliotecario_foreign` (`Codigo_Bibliotecario`),
  KEY `prestamos_codigo_socio_foreign` (`Codigo_Socio`),
  CONSTRAINT `prestamos_codigo_bibliotecario_foreign` FOREIGN KEY (`Codigo_Bibliotecario`) REFERENCES `bibliotecarios` (`Codigo_Bibliotecario`) ON UPDATE CASCADE,
  CONSTRAINT `prestamos_codigo_socio_foreign` FOREIGN KEY (`Codigo_Socio`) REFERENCES `socios` (`Codigo_Socio`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=385 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prestamos`
--

LOCK TABLES `prestamos` WRITE;
/*!40000 ALTER TABLE `prestamos` DISABLE KEYS */;
INSERT INTO `prestamos` VALUES (1,1,2,'Concluido','2026-11-03','2026-11-17','2026-11-03 19:27:27',NULL,NULL),(252,32,1,'Concluido','2025-01-23','2025-02-06','2026-11-03 20:34:11',NULL,NULL),(253,41,2,'Concluido','2025-01-18','2025-02-01','2026-11-03 20:34:11',NULL,NULL),(254,35,1,'Concluido','2025-01-20','2025-02-03','2026-11-03 20:34:11',NULL,NULL),(255,31,1,'Concluido','2025-02-19','2025-03-05','2026-11-03 20:34:11',NULL,NULL),(256,33,1,'Concluido','2025-02-24','2025-03-10','2026-11-03 20:34:11',NULL,NULL),(257,36,1,'Concluido','2025-02-09','2025-02-23','2026-11-03 20:34:11',NULL,NULL),(258,35,2,'Concluido','2025-02-02','2025-02-16','2026-11-03 20:34:11',NULL,NULL),(259,34,1,'Concluido','2025-03-19','2025-04-02','2026-11-03 20:34:11',NULL,NULL),(260,43,2,'Concluido','2025-03-25','2025-04-08','2026-11-03 20:34:11',NULL,NULL),(261,37,2,'Concluido','2025-03-10','2025-03-24','2026-11-03 20:34:11',NULL,NULL),(262,40,2,'Concluido','2025-03-24','2025-04-07','2026-11-03 20:34:11',NULL,NULL),(263,31,1,'Concluido','2025-03-20','2025-04-03','2026-11-03 20:34:11',NULL,NULL),(264,42,2,'Concluido','2025-03-11','2025-03-25','2026-11-03 20:34:11',NULL,NULL),(265,30,1,'Concluido','2025-03-27','2025-04-10','2026-11-03 20:34:11',NULL,NULL),(266,39,1,'Concluido','2025-03-01','2025-03-15','2026-11-03 20:34:11',NULL,NULL),(267,30,1,'Concluido','2025-04-13','2025-04-27','2026-11-03 20:34:11',NULL,NULL),(268,31,2,'Concluido','2025-04-05','2025-04-19','2026-11-03 20:34:11',NULL,NULL),(269,36,1,'Concluido','2025-04-21','2025-05-05','2026-11-03 20:34:12',NULL,NULL),(270,38,2,'Concluido','2025-04-28','2025-05-12','2026-11-03 20:34:12',NULL,NULL),(271,32,1,'Concluido','2025-04-19','2025-05-03','2026-11-03 20:34:12',NULL,NULL),(272,30,1,'Concluido','2025-04-08','2025-04-22','2026-11-03 20:34:12',NULL,NULL),(273,36,1,'Concluido','2025-04-14','2025-04-28','2026-11-03 20:34:12',NULL,NULL),(274,35,2,'Concluido','2025-04-07','2025-04-21','2026-11-03 20:34:12',NULL,NULL),(275,36,1,'Concluido','2025-04-27','2025-05-11','2026-11-03 20:34:12',NULL,NULL),(276,41,1,'Concluido','2025-05-07','2025-05-21','2026-11-03 20:34:12',NULL,NULL),(277,33,1,'Concluido','2025-05-17','2025-05-31','2026-11-03 20:34:12',NULL,NULL),(278,32,2,'Concluido','2025-05-02','2025-05-16','2026-11-03 20:34:12',NULL,NULL),(279,40,1,'Concluido','2025-05-13','2025-05-27','2026-11-03 20:34:12',NULL,NULL),(280,33,2,'Concluido','2025-05-21','2025-06-04','2026-11-03 20:34:12',NULL,NULL),(281,32,1,'Concluido','2025-05-20','2025-06-03','2026-11-03 20:34:12',NULL,NULL),(282,36,1,'Concluido','2025-05-23','2025-06-06','2026-11-03 20:34:12',NULL,NULL),(283,43,2,'Concluido','2025-05-03','2025-05-17','2026-11-03 20:34:12',NULL,NULL),(284,31,1,'Concluido','2025-06-22','2025-07-06','2026-11-03 20:34:12',NULL,NULL),(285,37,2,'Concluido','2025-06-22','2025-07-06','2026-11-03 20:34:12',NULL,NULL),(286,34,2,'Concluido','2025-06-17','2025-07-01','2026-11-03 20:34:12',NULL,NULL),(287,38,1,'Concluido','2025-06-17','2025-07-01','2026-11-03 20:34:12',NULL,NULL),(288,37,1,'Concluido','2025-06-22','2025-07-06','2026-11-03 20:34:12',NULL,NULL),(289,30,1,'Concluido','2025-07-02','2025-07-16','2026-11-03 20:34:12',NULL,NULL),(290,36,2,'Concluido','2025-07-13','2025-07-27','2026-11-03 20:34:12',NULL,NULL),(291,35,2,'Concluido','2025-08-23','2025-09-06','2026-11-03 20:34:12',NULL,NULL),(292,38,2,'Concluido','2025-08-21','2025-09-04','2026-11-03 20:34:12',NULL,NULL),(293,31,2,'Concluido','2025-08-23','2025-09-06','2026-11-03 20:34:12',NULL,NULL),(294,40,1,'Concluido','2025-09-20','2025-10-04','2026-11-03 20:34:12',NULL,NULL),(295,30,2,'Concluido','2025-09-27','2025-10-11','2026-11-03 20:34:12',NULL,NULL),(296,36,2,'Concluido','2025-09-07','2025-09-21','2026-11-03 20:34:12',NULL,NULL),(297,35,1,'Concluido','2025-09-19','2025-10-03','2026-11-03 20:34:12',NULL,NULL),(298,35,1,'Concluido','2025-09-24','2025-10-08','2026-11-03 20:34:12',NULL,NULL),(299,38,1,'Concluido','2025-09-07','2025-09-21','2026-11-03 20:34:12',NULL,NULL),(300,37,1,'Concluido','2025-09-17','2025-10-01','2026-11-03 20:34:12',NULL,NULL),(301,34,2,'Concluido','2025-09-08','2025-09-22','2026-11-03 20:34:12',NULL,NULL),(302,36,2,'Concluido','2025-09-05','2025-09-19','2026-11-03 20:34:12',NULL,NULL),(303,43,2,'Concluido','2025-10-16','2025-10-30','2026-11-03 20:34:12',NULL,NULL),(304,33,2,'Concluido','2025-10-03','2025-10-17','2026-11-03 20:34:12',NULL,NULL),(305,34,2,'Concluido','2025-10-26','2025-11-09','2026-11-03 20:34:12',NULL,NULL),(306,36,2,'Concluido','2025-10-05','2025-10-19','2026-11-03 20:34:12',NULL,NULL),(307,42,1,'Concluido','2025-10-27','2025-11-10','2026-11-03 20:34:12',NULL,NULL),(308,42,2,'Concluido','2025-10-28','2025-11-11','2026-11-03 20:34:12',NULL,NULL),(309,43,2,'Concluido','2025-10-26','2025-11-09','2026-11-03 20:34:12',NULL,NULL),(310,38,2,'Concluido','2025-10-07','2025-10-21','2026-11-03 20:34:12',NULL,NULL),(311,42,2,'Concluido','2025-11-15','2025-11-29','2026-11-03 20:34:12',NULL,NULL),(312,39,1,'Concluido','2025-11-25','2025-12-09','2026-11-03 20:34:12',NULL,NULL),(313,35,2,'Concluido','2025-11-21','2025-12-05','2026-11-03 20:34:12',NULL,NULL),(314,35,2,'Concluido','2025-11-10','2025-11-24','2026-11-03 20:34:12',NULL,NULL),(315,32,2,'Concluido','2025-11-13','2025-11-27','2026-11-03 20:34:12',NULL,NULL),(316,39,1,'Concluido','2025-11-13','2025-11-27','2026-11-03 20:34:12',NULL,NULL),(317,43,2,'Concluido','2025-12-28','2026-01-11','2026-11-03 20:34:12',NULL,NULL),(318,33,1,'Concluido','2025-12-24','2026-01-07','2026-11-03 20:34:12',NULL,NULL),(319,40,1,'Concluido','2025-12-05','2025-12-19','2026-11-03 20:34:12',NULL,NULL),(320,39,1,'Concluido','2026-01-11','2026-01-25','2026-11-03 20:34:12',NULL,NULL),(321,42,2,'Concluido','2026-01-16','2026-01-30','2026-11-03 20:34:12',NULL,NULL),(322,40,2,'Concluido','2026-01-12','2026-01-26','2026-11-03 20:34:12',NULL,NULL),(323,41,1,'Concluido','2026-01-15','2026-01-29','2026-11-03 20:34:12',NULL,NULL),(324,38,2,'Concluido','2026-02-18','2026-03-04','2026-11-03 20:34:12',NULL,NULL),(325,39,2,'Concluido','2026-02-15','2026-03-01','2026-11-03 20:34:12',NULL,NULL),(326,37,2,'Concluido','2026-02-26','2026-03-12','2026-11-03 20:34:12',NULL,NULL),(327,30,2,'Concluido','2026-02-09','2026-02-23','2026-11-03 20:34:12',NULL,NULL),(328,41,1,'Concluido','2026-02-22','2026-03-08','2026-11-03 20:34:12',NULL,NULL),(329,38,1,'Concluido','2026-03-05','2026-03-19','2026-11-03 20:34:12',NULL,NULL),(330,35,2,'Concluido','2026-03-13','2026-03-27','2026-11-03 20:34:12',NULL,NULL),(331,32,2,'Concluido','2026-03-26','2026-04-09','2026-11-03 20:34:12',NULL,NULL),(332,36,2,'Concluido','2026-03-12','2026-03-26','2026-11-03 20:34:12',NULL,NULL),(333,40,2,'Concluido','2026-03-08','2026-03-22','2026-11-03 20:34:12',NULL,NULL),(334,33,1,'Concluido','2026-03-19','2026-04-02','2026-11-03 20:34:12',NULL,NULL),(335,37,1,'Concluido','2026-03-04','2026-03-18','2026-11-03 20:34:12',NULL,NULL),(336,38,1,'Concluido','2026-03-14','2026-03-28','2026-11-03 20:34:12',NULL,NULL),(337,36,1,'Concluido','2026-03-03','2026-03-17','2026-11-03 20:34:12',NULL,NULL),(338,37,2,'Concluido','2026-04-04','2026-04-18','2026-11-03 20:34:12',NULL,NULL),(339,35,1,'Concluido','2026-04-25','2026-05-09','2026-11-03 20:34:12',NULL,NULL),(340,39,2,'Concluido','2026-04-28','2026-05-12','2026-11-03 20:34:12',NULL,NULL),(341,41,2,'Concluido','2026-04-25','2026-05-09','2026-11-03 20:34:12',NULL,NULL),(342,38,1,'Concluido','2026-04-23','2026-05-07','2026-11-03 20:34:12',NULL,NULL),(343,35,1,'Concluido','2026-04-05','2026-04-19','2026-11-03 20:34:12',NULL,NULL),(344,43,2,'Concluido','2026-04-04','2026-04-18','2026-11-03 20:34:12',NULL,NULL),(345,42,1,'Concluido','2026-04-21','2026-05-05','2026-11-03 20:34:12',NULL,NULL),(346,36,2,'Concluido','2026-05-24','2026-06-07','2026-11-03 20:34:12',NULL,NULL),(347,32,1,'Concluido','2026-05-08','2026-05-22','2026-11-03 20:34:12',NULL,NULL),(348,33,2,'Concluido','2026-05-01','2026-05-15','2026-11-03 20:34:12',NULL,NULL),(349,36,1,'Concluido','2026-05-19','2026-06-02','2026-11-03 20:34:12',NULL,NULL),(350,37,1,'Concluido','2026-05-22','2026-06-05','2026-11-03 20:34:12',NULL,NULL),(351,33,2,'Concluido','2026-05-15','2026-05-29','2026-11-03 20:34:12',NULL,NULL),(352,43,2,'Concluido','2026-05-21','2026-06-04','2026-11-03 20:34:12',NULL,NULL),(353,35,1,'Concluido','2026-06-05','2026-06-19','2026-11-03 20:34:12',NULL,NULL),(354,39,1,'Concluido','2026-06-23','2026-07-07','2026-11-03 20:34:12',NULL,NULL),(355,30,1,'Concluido','2026-06-02','2026-06-16','2026-11-03 20:34:12',NULL,NULL),(356,33,1,'Concluido','2026-06-15','2026-06-29','2026-11-03 20:34:12',NULL,NULL),(357,39,2,'Concluido','2026-07-22','2026-08-05','2026-11-03 20:34:12',NULL,NULL),(358,32,1,'Concluido','2026-07-13','2026-07-27','2026-11-03 20:34:12',NULL,NULL),(359,31,2,'Concluido','2026-08-20','2026-09-03','2026-11-03 20:34:12',NULL,NULL),(360,39,1,'Concluido','2026-08-08','2026-08-22','2026-11-03 20:34:12',NULL,NULL),(361,41,2,'Concluido','2026-08-12','2026-08-26','2026-11-03 20:34:12',NULL,NULL),(362,41,1,'Concluido','2026-09-03','2026-09-17','2026-11-03 20:34:12',NULL,NULL),(363,40,2,'Concluido','2026-09-09','2026-09-23','2026-11-03 20:34:12',NULL,NULL),(364,34,1,'Concluido','2026-09-28','2026-10-12','2026-11-03 20:34:12',NULL,NULL),(365,30,2,'Concluido','2026-09-01','2026-09-15','2026-11-03 20:34:12',NULL,NULL),(366,40,1,'Concluido','2026-09-04','2026-09-18','2026-11-03 20:34:12',NULL,NULL),(367,43,1,'Concluido','2026-09-23','2026-10-07','2026-11-03 20:34:12',NULL,NULL),(368,42,1,'Concluido','2026-09-27','2026-10-11','2026-11-03 20:34:13',NULL,NULL),(369,42,2,'Concluido','2026-09-19','2026-10-03','2026-11-03 20:34:13',NULL,NULL),(370,34,2,'Concluido','2026-10-19','2026-11-02','2026-11-03 20:34:13',NULL,NULL),(371,39,1,'Concluido','2026-10-08','2026-10-22','2026-11-03 20:34:13',NULL,NULL),(372,39,2,'Concluido','2026-10-20','2026-11-03','2026-11-03 20:34:13',NULL,NULL),(373,39,1,'Concluido','2026-10-08','2026-10-22','2026-11-03 20:34:13',NULL,NULL),(374,37,1,'Concluido','2026-10-13','2026-10-27','2026-11-03 20:34:13',NULL,NULL),(375,33,1,'Activo','2026-10-13','2026-10-27','2026-11-03 20:34:13',NULL,NULL),(376,32,2,'Concluido','2026-10-09','2026-10-23','2026-11-03 20:34:13',NULL,NULL),(377,30,2,'Activo','2026-10-30','2026-11-13','2026-11-03 20:34:13',NULL,NULL),(378,36,2,'Activo','2026-10-25','2026-11-08','2026-11-03 20:34:13',NULL,NULL),(379,34,1,'Activo','2026-10-15','2026-10-29','2026-11-03 20:34:13',NULL,NULL),(380,38,2,'Activo','2026-10-17','2026-10-31','2026-11-03 20:34:13',NULL,NULL),(381,39,2,'Activo','2026-10-20','2026-11-03','2026-11-03 20:34:13',NULL,NULL),(382,40,2,'Activo','2026-10-23','2026-11-06','2026-11-03 20:34:13',NULL,NULL),(383,1,3,'Activo','2026-11-03','2026-11-17','2026-11-03 21:03:52',NULL,NULL),(384,1,3,'Concluido','2026-11-03','2026-11-17','2026-11-03 21:12:33',NULL,NULL);
/*!40000 ALTER TABLE `prestamos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `devolucions`
--

DROP TABLE IF EXISTS `devolucions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `devolucions` (
  `Codigo_Devolucion` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Codigo_Bibliotecario` int(10) unsigned NOT NULL,
  `Fecha_Devolucion` date NOT NULL DEFAULT curdate(),
  `Observaciones` text DEFAULT NULL,
  `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_modificacion` varchar(50) DEFAULT NULL,
  `motivo_modificacion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`Codigo_Devolucion`),
  KEY `devolucions_codigo_bibliotecario_foreign` (`Codigo_Bibliotecario`),
  CONSTRAINT `devolucions_codigo_bibliotecario_foreign` FOREIGN KEY (`Codigo_Bibliotecario`) REFERENCES `bibliotecarios` (`Codigo_Bibliotecario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=372 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devolucions`
--

LOCK TABLES `devolucions` WRITE;
/*!40000 ALTER TABLE `devolucions` DISABLE KEYS */;
INSERT INTO `devolucions` VALUES (1,2,'2026-11-03','Prueba de ciclo via API','2026-11-03 19:27:27',NULL,NULL),(247,1,'2025-01-28',NULL,'2026-11-03 20:34:11',NULL,NULL),(248,2,'2025-01-31','Devolución en mostrador','2026-11-03 20:34:11',NULL,NULL),(249,1,'2025-01-26',NULL,'2026-11-03 20:34:11',NULL,NULL),(250,1,'2025-03-09',NULL,'2026-11-03 20:34:11',NULL,NULL),(251,1,'2025-03-05',NULL,'2026-11-03 20:34:11',NULL,NULL),(252,1,'2025-02-26',NULL,'2026-11-03 20:34:11',NULL,NULL),(253,2,'2025-02-07',NULL,'2026-11-03 20:34:11',NULL,NULL),(254,1,'2025-03-24','Devolución en mostrador','2026-11-03 20:34:11',NULL,NULL),(255,2,'2025-04-02',NULL,'2026-11-03 20:34:11',NULL,NULL),(256,2,'2025-03-16',NULL,'2026-11-03 20:34:11',NULL,NULL),(257,2,'2025-04-11',NULL,'2026-11-03 20:34:11',NULL,NULL),(258,1,'2025-04-04',NULL,'2026-11-03 20:34:11',NULL,NULL),(259,2,'2025-03-24',NULL,'2026-11-03 20:34:11',NULL,NULL),(260,1,'2025-04-09','Devolución en mostrador','2026-11-03 20:34:11',NULL,NULL),(261,1,'2025-03-11',NULL,'2026-11-03 20:34:11',NULL,NULL),(262,1,'2025-04-30','Devolución en mostrador','2026-11-03 20:34:11',NULL,NULL),(263,2,'2025-04-12',NULL,'2026-11-03 20:34:11',NULL,NULL),(264,1,'2025-05-01','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(265,2,'2025-05-09','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(266,1,'2025-05-11',NULL,'2026-11-03 20:34:12',NULL,NULL),(267,1,'2025-04-15','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(268,1,'2025-05-01',NULL,'2026-11-03 20:34:12',NULL,NULL),(269,2,'2025-04-23','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(270,1,'2025-05-07',NULL,'2026-11-03 20:34:12',NULL,NULL),(271,1,'2025-05-28',NULL,'2026-11-03 20:34:12',NULL,NULL),(272,1,'2025-05-31',NULL,'2026-11-03 20:34:12',NULL,NULL),(273,2,'2025-05-25',NULL,'2026-11-03 20:34:12',NULL,NULL),(274,1,'2025-05-17','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(275,2,'2025-06-14',NULL,'2026-11-03 20:34:12',NULL,NULL),(276,1,'2025-05-24',NULL,'2026-11-03 20:34:12',NULL,NULL),(277,1,'2025-06-09',NULL,'2026-11-03 20:34:12',NULL,NULL),(278,2,'2025-05-21',NULL,'2026-11-03 20:34:12',NULL,NULL),(279,1,'2025-07-09',NULL,'2026-11-03 20:34:12',NULL,NULL),(280,2,'2025-06-30',NULL,'2026-11-03 20:34:12',NULL,NULL),(281,2,'2025-06-30',NULL,'2026-11-03 20:34:12',NULL,NULL),(282,1,'2025-06-28',NULL,'2026-11-03 20:34:12',NULL,NULL),(283,1,'2025-06-26',NULL,'2026-11-03 20:34:12',NULL,NULL),(284,1,'2025-07-06',NULL,'2026-11-03 20:34:12',NULL,NULL),(285,2,'2025-08-02',NULL,'2026-11-03 20:34:12',NULL,NULL),(286,2,'2025-09-15',NULL,'2026-11-03 20:34:12',NULL,NULL),(287,2,'2025-09-09',NULL,'2026-11-03 20:34:12',NULL,NULL),(288,2,'2025-09-05',NULL,'2026-11-03 20:34:12',NULL,NULL),(289,1,'2025-09-26',NULL,'2026-11-03 20:34:12',NULL,NULL),(290,2,'2025-10-15',NULL,'2026-11-03 20:34:12',NULL,NULL),(291,2,'2025-09-26','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(292,1,'2025-10-13','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(293,1,'2025-10-18',NULL,'2026-11-03 20:34:12',NULL,NULL),(294,1,'2025-09-15',NULL,'2026-11-03 20:34:12',NULL,NULL),(295,1,'2025-10-03','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(296,2,'2025-09-14',NULL,'2026-11-03 20:34:12',NULL,NULL),(297,2,'2025-09-13','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(298,2,'2025-11-01','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(299,2,'2025-10-20',NULL,'2026-11-03 20:34:12',NULL,NULL),(300,2,'2025-11-08',NULL,'2026-11-03 20:34:12',NULL,NULL),(301,2,'2025-10-26',NULL,'2026-11-03 20:34:12',NULL,NULL),(302,1,'2025-11-14',NULL,'2026-11-03 20:34:12',NULL,NULL),(303,2,'2025-11-20','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(304,2,'2025-11-12','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(305,2,'2025-10-13',NULL,'2026-11-03 20:34:12',NULL,NULL),(306,2,'2025-11-29',NULL,'2026-11-03 20:34:12',NULL,NULL),(307,1,'2025-12-06',NULL,'2026-11-03 20:34:12',NULL,NULL),(308,2,'2025-12-01',NULL,'2026-11-03 20:34:12',NULL,NULL),(309,2,'2025-11-22',NULL,'2026-11-03 20:34:12',NULL,NULL),(310,2,'2025-11-25',NULL,'2026-11-03 20:34:12',NULL,NULL),(311,1,'2025-11-17',NULL,'2026-11-03 20:34:12',NULL,NULL),(312,2,'2026-01-19','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(313,1,'2026-01-14',NULL,'2026-11-03 20:34:12',NULL,NULL),(314,1,'2025-12-10',NULL,'2026-11-03 20:34:12',NULL,NULL),(315,1,'2026-01-15','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(316,2,'2026-02-03',NULL,'2026-11-03 20:34:12',NULL,NULL),(317,2,'2026-01-20','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(318,1,'2026-01-25','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(319,2,'2026-03-02',NULL,'2026-11-03 20:34:12',NULL,NULL),(320,2,'2026-03-06',NULL,'2026-11-03 20:34:12',NULL,NULL),(321,2,'2026-03-21',NULL,'2026-11-03 20:34:12',NULL,NULL),(322,2,'2026-02-15',NULL,'2026-11-03 20:34:12',NULL,NULL),(323,1,'2026-03-15',NULL,'2026-11-03 20:34:12',NULL,NULL),(324,1,'2026-03-13',NULL,'2026-11-03 20:34:12',NULL,NULL),(325,2,'2026-04-04',NULL,'2026-11-03 20:34:12',NULL,NULL),(326,2,'2026-04-01',NULL,'2026-11-03 20:34:12',NULL,NULL),(327,2,'2026-03-24',NULL,'2026-11-03 20:34:12',NULL,NULL),(328,2,'2026-03-31',NULL,'2026-11-03 20:34:12',NULL,NULL),(329,1,'2026-03-31','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(330,1,'2026-03-20',NULL,'2026-11-03 20:34:12',NULL,NULL),(331,1,'2026-04-03',NULL,'2026-11-03 20:34:12',NULL,NULL),(332,1,'2026-03-22',NULL,'2026-11-03 20:34:12',NULL,NULL),(333,2,'2026-04-18',NULL,'2026-11-03 20:34:12',NULL,NULL),(334,1,'2026-05-03',NULL,'2026-11-03 20:34:12',NULL,NULL),(335,2,'2026-05-05',NULL,'2026-11-03 20:34:12',NULL,NULL),(336,2,'2026-05-15',NULL,'2026-11-03 20:34:12',NULL,NULL),(337,1,'2026-05-09',NULL,'2026-11-03 20:34:12',NULL,NULL),(338,1,'2026-04-27','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(339,2,'2026-04-19','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(340,1,'2026-05-10',NULL,'2026-11-03 20:34:12',NULL,NULL),(341,2,'2026-06-10',NULL,'2026-11-03 20:34:12',NULL,NULL),(342,1,'2026-05-12',NULL,'2026-11-03 20:34:12',NULL,NULL),(343,2,'2026-05-17',NULL,'2026-11-03 20:34:12',NULL,NULL),(344,1,'2026-05-24','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(345,1,'2026-06-11',NULL,'2026-11-03 20:34:12',NULL,NULL),(346,2,'2026-05-29','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(347,2,'2026-06-07',NULL,'2026-11-03 20:34:12',NULL,NULL),(348,1,'2026-06-18',NULL,'2026-11-03 20:34:12',NULL,NULL),(349,1,'2026-07-02',NULL,'2026-11-03 20:34:12',NULL,NULL),(350,1,'2026-06-14',NULL,'2026-11-03 20:34:12',NULL,NULL),(351,1,'2026-06-24',NULL,'2026-11-03 20:34:12',NULL,NULL),(352,2,'2026-08-14','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(353,1,'2026-07-20','Devolución en mostrador','2026-11-03 20:34:12',NULL,NULL),(354,2,'2026-08-27',NULL,'2026-11-03 20:34:12',NULL,NULL),(355,1,'2026-08-19',NULL,'2026-11-03 20:34:12',NULL,NULL),(356,2,'2026-08-24',NULL,'2026-11-03 20:34:12',NULL,NULL),(357,1,'2026-09-25',NULL,'2026-11-03 20:34:12',NULL,NULL),(358,2,'2026-09-15',NULL,'2026-11-03 20:34:12',NULL,NULL),(359,1,'2026-10-13',NULL,'2026-11-03 20:34:12',NULL,NULL),(360,2,'2026-09-15',NULL,'2026-11-03 20:34:12',NULL,NULL),(361,1,'2026-09-28',NULL,'2026-11-03 20:34:12',NULL,NULL),(362,1,'2026-10-11',NULL,'2026-11-03 20:34:12',NULL,NULL),(363,1,'2026-10-16',NULL,'2026-11-03 20:34:13',NULL,NULL),(364,2,'2026-09-29',NULL,'2026-11-03 20:34:13',NULL,NULL),(365,2,'2026-11-01',NULL,'2026-11-03 20:34:13',NULL,NULL),(366,1,'2026-10-15',NULL,'2026-11-03 20:34:13',NULL,NULL),(367,2,'2026-10-25',NULL,'2026-11-03 20:34:13',NULL,NULL),(368,1,'2026-10-28','Devolución en mostrador','2026-11-03 20:34:13',NULL,NULL),(369,1,'2026-10-24',NULL,'2026-11-03 20:34:13',NULL,NULL),(370,2,'2026-10-13',NULL,'2026-11-03 20:34:13',NULL,NULL),(371,3,'2026-11-03',NULL,'2026-11-03 21:12:33',NULL,NULL);
/*!40000 ALTER TABLE `devolucions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_prestamos`
--

DROP TABLE IF EXISTS `detalle_prestamos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_prestamos` (
  `Codigo_Detalle` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Codigo_Prestamo` int(10) unsigned NOT NULL,
  `Codigo_Ejemplar` int(10) unsigned NOT NULL,
  `EstadoEntrega` enum('Buen estado','Dañado','Perdido') DEFAULT NULL,
  `Codigo_Devolucion` int(10) unsigned DEFAULT NULL,
  `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_modificacion` varchar(50) DEFAULT NULL,
  `motivo_modificacion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`Codigo_Detalle`),
  KEY `detalle_prestamos_codigo_prestamo_foreign` (`Codigo_Prestamo`),
  KEY `detalle_prestamos_codigo_ejemplar_foreign` (`Codigo_Ejemplar`),
  KEY `detalle_prestamos_codigo_devolucion_foreign` (`Codigo_Devolucion`),
  CONSTRAINT `detalle_prestamos_codigo_devolucion_foreign` FOREIGN KEY (`Codigo_Devolucion`) REFERENCES `devolucions` (`Codigo_Devolucion`),
  CONSTRAINT `detalle_prestamos_codigo_ejemplar_foreign` FOREIGN KEY (`Codigo_Ejemplar`) REFERENCES `ejemplars` (`Codigo_Ejemplar`),
  CONSTRAINT `detalle_prestamos_codigo_prestamo_foreign` FOREIGN KEY (`Codigo_Prestamo`) REFERENCES `prestamos` (`Codigo_Prestamo`)
) ENGINE=InnoDB AUTO_INCREMENT=587 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_prestamos`
--

LOCK TABLES `detalle_prestamos` WRITE;
/*!40000 ALTER TABLE `detalle_prestamos` DISABLE KEYS */;
INSERT INTO `detalle_prestamos` VALUES (1,1,1,'Buen estado',1,'2026-11-03 19:27:27',NULL,NULL),(383,252,93,'Buen estado',247,'2026-11-03 20:34:11',NULL,NULL),(384,253,309,'Perdido',248,'2026-11-03 20:34:11',NULL,NULL),(385,254,227,'Buen estado',249,'2026-11-03 20:34:11',NULL,NULL),(386,255,238,'Buen estado',250,'2026-11-03 20:34:11',NULL,NULL),(387,255,235,'Buen estado',250,'2026-11-03 20:34:11',NULL,NULL),(388,256,147,'Buen estado',251,'2026-11-03 20:34:11',NULL,NULL),(389,256,310,'Buen estado',251,'2026-11-03 20:34:11',NULL,NULL),(390,257,250,'Buen estado',252,'2026-11-03 20:34:11',NULL,NULL),(391,257,210,'Buen estado',252,'2026-11-03 20:34:11',NULL,NULL),(392,258,149,'Buen estado',253,'2026-11-03 20:34:11',NULL,NULL),(393,258,313,'Buen estado',253,'2026-11-03 20:34:11',NULL,NULL),(394,259,243,'Buen estado',254,'2026-11-03 20:34:11',NULL,NULL),(395,259,244,'Buen estado',254,'2026-11-03 20:34:11',NULL,NULL),(396,260,302,'Dañado',255,'2026-11-03 20:34:11',NULL,NULL),(397,260,234,'Buen estado',255,'2026-11-03 20:34:11',NULL,NULL),(398,261,15,'Buen estado',256,'2026-11-03 20:34:11',NULL,NULL),(399,262,107,'Buen estado',257,'2026-11-03 20:34:11',NULL,NULL),(400,262,127,'Buen estado',257,'2026-11-03 20:34:11',NULL,NULL),(401,263,46,'Buen estado',258,'2026-11-03 20:34:11',NULL,NULL),(402,263,182,'Buen estado',258,'2026-11-03 20:34:11',NULL,NULL),(403,264,165,'Buen estado',259,'2026-11-03 20:34:11',NULL,NULL),(404,265,160,'Buen estado',260,'2026-11-03 20:34:11',NULL,NULL),(405,265,310,'Buen estado',260,'2026-11-03 20:34:11',NULL,NULL),(406,266,105,'Buen estado',261,'2026-11-03 20:34:11',NULL,NULL),(407,267,75,'Perdido',262,'2026-11-03 20:34:11',NULL,NULL),(408,268,173,'Buen estado',263,'2026-11-03 20:34:11',NULL,NULL),(409,269,194,'Dañado',264,'2026-11-03 20:34:12',NULL,NULL),(410,269,4,'Dañado',264,'2026-11-03 20:34:12',NULL,NULL),(411,270,278,'Buen estado',265,'2026-11-03 20:34:12',NULL,NULL),(412,270,137,'Buen estado',265,'2026-11-03 20:34:12',NULL,NULL),(413,271,191,'Buen estado',266,'2026-11-03 20:34:12',NULL,NULL),(414,271,134,'Buen estado',266,'2026-11-03 20:34:12',NULL,NULL),(415,272,28,'Perdido',267,'2026-11-03 20:34:12',NULL,NULL),(416,273,29,'Buen estado',268,'2026-11-03 20:34:12',NULL,NULL),(417,274,156,'Buen estado',269,'2026-11-03 20:34:12',NULL,NULL),(418,274,20,'Buen estado',269,'2026-11-03 20:34:12',NULL,NULL),(419,275,108,'Dañado',270,'2026-11-03 20:34:12',NULL,NULL),(420,275,140,'Buen estado',270,'2026-11-03 20:34:12',NULL,NULL),(421,276,204,'Buen estado',271,'2026-11-03 20:34:12',NULL,NULL),(422,276,280,'Buen estado',271,'2026-11-03 20:34:12',NULL,NULL),(423,277,29,'Buen estado',272,'2026-11-03 20:34:12',NULL,NULL),(424,278,80,'Buen estado',273,'2026-11-03 20:34:12',NULL,NULL),(425,279,68,'Buen estado',274,'2026-11-03 20:34:12',NULL,NULL),(426,279,138,'Buen estado',274,'2026-11-03 20:34:12',NULL,NULL),(427,280,261,'Buen estado',275,'2026-11-03 20:34:12',NULL,NULL),(428,280,307,'Buen estado',275,'2026-11-03 20:34:12',NULL,NULL),(429,281,87,'Buen estado',276,'2026-11-03 20:34:12',NULL,NULL),(430,281,220,'Buen estado',276,'2026-11-03 20:34:12',NULL,NULL),(431,282,141,'Buen estado',277,'2026-11-03 20:34:12',NULL,NULL),(432,283,94,'Buen estado',278,'2026-11-03 20:34:12',NULL,NULL),(433,283,90,'Buen estado',278,'2026-11-03 20:34:12',NULL,NULL),(434,284,98,'Buen estado',279,'2026-11-03 20:34:12',NULL,NULL),(435,285,180,'Buen estado',280,'2026-11-03 20:34:12',NULL,NULL),(436,285,44,'Buen estado',280,'2026-11-03 20:34:12',NULL,NULL),(437,286,188,'Buen estado',281,'2026-11-03 20:34:12',NULL,NULL),(438,286,246,'Dañado',281,'2026-11-03 20:34:12',NULL,NULL),(439,287,306,'Buen estado',282,'2026-11-03 20:34:12',NULL,NULL),(440,288,261,'Buen estado',283,'2026-11-03 20:34:12',NULL,NULL),(441,288,131,'Buen estado',283,'2026-11-03 20:34:12',NULL,NULL),(442,289,279,'Buen estado',284,'2026-11-03 20:34:12',NULL,NULL),(443,289,95,'Buen estado',284,'2026-11-03 20:34:12',NULL,NULL),(444,290,250,'Buen estado',285,'2026-11-03 20:34:12',NULL,NULL),(445,291,156,'Buen estado',286,'2026-11-03 20:34:12',NULL,NULL),(446,292,256,'Dañado',287,'2026-11-03 20:34:12',NULL,NULL),(447,292,116,'Buen estado',287,'2026-11-03 20:34:12',NULL,NULL),(448,293,158,'Buen estado',288,'2026-11-03 20:34:12',NULL,NULL),(449,294,275,'Buen estado',289,'2026-11-03 20:34:12',NULL,NULL),(450,295,64,'Buen estado',290,'2026-11-03 20:34:12',NULL,NULL),(451,295,217,'Buen estado',290,'2026-11-03 20:34:12',NULL,NULL),(452,296,63,'Buen estado',291,'2026-11-03 20:34:12',NULL,NULL),(453,296,1,'Buen estado',291,'2026-11-03 20:34:12',NULL,NULL),(454,297,103,'Buen estado',292,'2026-11-03 20:34:12',NULL,NULL),(455,297,190,'Buen estado',292,'2026-11-03 20:34:12',NULL,NULL),(456,298,17,'Buen estado',293,'2026-11-03 20:34:12',NULL,NULL),(457,299,191,'Buen estado',294,'2026-11-03 20:34:12',NULL,NULL),(458,300,222,'Buen estado',295,'2026-11-03 20:34:12',NULL,NULL),(459,300,96,'Buen estado',295,'2026-11-03 20:34:12',NULL,NULL),(460,301,29,'Buen estado',296,'2026-11-03 20:34:12',NULL,NULL),(461,302,221,'Buen estado',297,'2026-11-03 20:34:12',NULL,NULL),(462,302,108,'Buen estado',297,'2026-11-03 20:34:12',NULL,NULL),(463,303,140,'Buen estado',298,'2026-11-03 20:34:12',NULL,NULL),(464,304,168,'Buen estado',299,'2026-11-03 20:34:12',NULL,NULL),(465,304,130,'Buen estado',299,'2026-11-03 20:34:12',NULL,NULL),(466,305,78,'Buen estado',300,'2026-11-03 20:34:12',NULL,NULL),(467,305,295,'Buen estado',300,'2026-11-03 20:34:12',NULL,NULL),(468,306,170,'Perdido',301,'2026-11-03 20:34:12',NULL,NULL),(469,306,299,'Buen estado',301,'2026-11-03 20:34:12',NULL,NULL),(470,307,85,'Buen estado',302,'2026-11-03 20:34:12',NULL,NULL),(471,307,60,'Buen estado',302,'2026-11-03 20:34:12',NULL,NULL),(472,308,14,'Buen estado',303,'2026-11-03 20:34:12',NULL,NULL),(473,308,134,'Buen estado',303,'2026-11-03 20:34:12',NULL,NULL),(474,309,15,'Buen estado',304,'2026-11-03 20:34:12',NULL,NULL),(475,310,57,'Buen estado',305,'2026-11-03 20:34:12',NULL,NULL),(476,310,86,'Buen estado',305,'2026-11-03 20:34:12',NULL,NULL),(477,311,105,'Dañado',306,'2026-11-03 20:34:12',NULL,NULL),(478,311,251,'Perdido',306,'2026-11-03 20:34:12',NULL,NULL),(479,312,162,'Dañado',307,'2026-11-03 20:34:12',NULL,NULL),(480,313,262,'Buen estado',308,'2026-11-03 20:34:12',NULL,NULL),(481,313,33,'Buen estado',308,'2026-11-03 20:34:12',NULL,NULL),(482,314,273,'Buen estado',309,'2026-11-03 20:34:12',NULL,NULL),(483,314,110,'Buen estado',309,'2026-11-03 20:34:12',NULL,NULL),(484,315,55,'Buen estado',310,'2026-11-03 20:34:12',NULL,NULL),(485,315,5,'Buen estado',310,'2026-11-03 20:34:12',NULL,NULL),(486,316,119,'Buen estado',311,'2026-11-03 20:34:12',NULL,NULL),(487,316,188,'Buen estado',311,'2026-11-03 20:34:12',NULL,NULL),(488,317,308,'Buen estado',312,'2026-11-03 20:34:12',NULL,NULL),(489,317,57,'Buen estado',312,'2026-11-03 20:34:12',NULL,NULL),(490,318,216,'Buen estado',313,'2026-11-03 20:34:12',NULL,NULL),(491,319,163,'Dañado',314,'2026-11-03 20:34:12',NULL,NULL),(492,319,237,'Perdido',314,'2026-11-03 20:34:12',NULL,NULL),(493,320,250,'Perdido',315,'2026-11-03 20:34:12',NULL,NULL),(494,321,254,'Buen estado',316,'2026-11-03 20:34:12',NULL,NULL),(495,321,46,'Perdido',316,'2026-11-03 20:34:12',NULL,NULL),(496,322,175,'Buen estado',317,'2026-11-03 20:34:12',NULL,NULL),(497,322,71,'Buen estado',317,'2026-11-03 20:34:12',NULL,NULL),(498,323,213,'Buen estado',318,'2026-11-03 20:34:12',NULL,NULL),(499,323,95,'Buen estado',318,'2026-11-03 20:34:12',NULL,NULL),(500,324,256,'Buen estado',319,'2026-11-03 20:34:12',NULL,NULL),(501,325,274,'Buen estado',320,'2026-11-03 20:34:12',NULL,NULL),(502,325,4,'Buen estado',320,'2026-11-03 20:34:12',NULL,NULL),(503,326,132,'Dañado',321,'2026-11-03 20:34:12',NULL,NULL),(504,327,244,'Buen estado',322,'2026-11-03 20:34:12',NULL,NULL),(505,327,241,'Buen estado',322,'2026-11-03 20:34:12',NULL,NULL),(506,328,259,'Dañado',323,'2026-11-03 20:34:12',NULL,NULL),(507,329,289,'Buen estado',324,'2026-11-03 20:34:12',NULL,NULL),(508,330,249,'Buen estado',325,'2026-11-03 20:34:12',NULL,NULL),(509,330,113,'Buen estado',325,'2026-11-03 20:34:12',NULL,NULL),(510,331,59,'Buen estado',326,'2026-11-03 20:34:12',NULL,NULL),(511,332,26,'Buen estado',327,'2026-11-03 20:34:12',NULL,NULL),(512,333,115,'Dañado',328,'2026-11-03 20:34:12',NULL,NULL),(513,333,135,'Buen estado',328,'2026-11-03 20:34:12',NULL,NULL),(514,334,285,'Buen estado',329,'2026-11-03 20:34:12',NULL,NULL),(515,334,91,'Perdido',329,'2026-11-03 20:34:12',NULL,NULL),(516,335,144,'Dañado',330,'2026-11-03 20:34:12',NULL,NULL),(517,335,177,'Buen estado',330,'2026-11-03 20:34:12',NULL,NULL),(518,336,96,'Buen estado',331,'2026-11-03 20:34:12',NULL,NULL),(519,337,73,'Buen estado',332,'2026-11-03 20:34:12',NULL,NULL),(520,337,230,'Buen estado',332,'2026-11-03 20:34:12',NULL,NULL),(521,338,130,'Buen estado',333,'2026-11-03 20:34:12',NULL,NULL),(522,338,134,'Perdido',333,'2026-11-03 20:34:12',NULL,NULL),(523,339,218,'Buen estado',334,'2026-11-03 20:34:12',NULL,NULL),(524,340,314,'Perdido',335,'2026-11-03 20:34:12',NULL,NULL),(525,340,285,'Buen estado',335,'2026-11-03 20:34:12',NULL,NULL),(526,341,257,'Buen estado',336,'2026-11-03 20:34:12',NULL,NULL),(527,341,107,'Buen estado',336,'2026-11-03 20:34:12',NULL,NULL),(528,342,287,'Buen estado',337,'2026-11-03 20:34:12',NULL,NULL),(529,343,38,'Buen estado',338,'2026-11-03 20:34:12',NULL,NULL),(530,343,153,'Perdido',338,'2026-11-03 20:34:12',NULL,NULL),(531,344,150,'Buen estado',339,'2026-11-03 20:34:12',NULL,NULL),(532,345,205,'Buen estado',340,'2026-11-03 20:34:12',NULL,NULL),(533,346,25,'Buen estado',341,'2026-11-03 20:34:12',NULL,NULL),(534,346,205,'Buen estado',341,'2026-11-03 20:34:12',NULL,NULL),(535,347,300,'Buen estado',342,'2026-11-03 20:34:12',NULL,NULL),(536,348,34,'Buen estado',343,'2026-11-03 20:34:12',NULL,NULL),(537,349,114,'Buen estado',344,'2026-11-03 20:34:12',NULL,NULL),(538,349,258,'Dañado',344,'2026-11-03 20:34:12',NULL,NULL),(539,350,102,'Buen estado',345,'2026-11-03 20:34:12',NULL,NULL),(540,351,187,'Buen estado',346,'2026-11-03 20:34:12',NULL,NULL),(541,352,315,'Buen estado',347,'2026-11-03 20:34:12',NULL,NULL),(542,353,197,'Buen estado',348,'2026-11-03 20:34:12',NULL,NULL),(543,354,286,'Buen estado',349,'2026-11-03 20:34:12',NULL,NULL),(544,354,292,'Buen estado',349,'2026-11-03 20:34:12',NULL,NULL),(545,355,83,'Buen estado',350,'2026-11-03 20:34:12',NULL,NULL),(546,356,112,'Buen estado',351,'2026-11-03 20:34:12',NULL,NULL),(547,356,207,'Buen estado',351,'2026-11-03 20:34:12',NULL,NULL),(548,357,194,'Dañado',352,'2026-11-03 20:34:12',NULL,NULL),(549,357,238,'Buen estado',352,'2026-11-03 20:34:12',NULL,NULL),(550,358,51,'Buen estado',353,'2026-11-03 20:34:12',NULL,NULL),(551,358,192,'Buen estado',353,'2026-11-03 20:34:12',NULL,NULL),(552,359,221,'Buen estado',354,'2026-11-03 20:34:12',NULL,NULL),(553,359,202,'Buen estado',354,'2026-11-03 20:34:12',NULL,NULL),(554,360,26,'Buen estado',355,'2026-11-03 20:34:12',NULL,NULL),(555,360,115,'Buen estado',355,'2026-11-03 20:34:12',NULL,NULL),(556,361,54,'Dañado',356,'2026-11-03 20:34:12',NULL,NULL),(557,361,164,'Buen estado',356,'2026-11-03 20:34:12',NULL,NULL),(558,362,171,'Buen estado',357,'2026-11-03 20:34:12',NULL,NULL),(559,363,274,'Buen estado',358,'2026-11-03 20:34:12',NULL,NULL),(560,363,26,'Buen estado',358,'2026-11-03 20:34:12',NULL,NULL),(561,364,167,'Buen estado',359,'2026-11-03 20:34:12',NULL,NULL),(562,365,202,'Buen estado',360,'2026-11-03 20:34:12',NULL,NULL),(563,365,123,'Dañado',360,'2026-11-03 20:34:12',NULL,NULL),(564,366,197,'Buen estado',361,'2026-11-03 20:34:12',NULL,NULL),(565,367,212,'Buen estado',362,'2026-11-03 20:34:12',NULL,NULL),(566,368,247,'Buen estado',363,'2026-11-03 20:34:13',NULL,NULL),(567,368,127,'Buen estado',363,'2026-11-03 20:34:13',NULL,NULL),(568,369,12,'Buen estado',364,'2026-11-03 20:34:13',NULL,NULL),(569,370,254,'Perdido',365,'2026-11-03 20:34:13',NULL,NULL),(570,371,205,'Dañado',366,'2026-11-03 20:34:13',NULL,NULL),(571,371,278,'Buen estado',366,'2026-11-03 20:34:13',NULL,NULL),(572,372,244,'Buen estado',367,'2026-11-03 20:34:13',NULL,NULL),(573,373,137,'Buen estado',368,'2026-11-03 20:34:13',NULL,NULL),(574,374,7,'Buen estado',369,'2026-11-03 20:34:13',NULL,NULL),(575,375,249,NULL,NULL,'2026-11-03 20:34:13',NULL,NULL),(576,376,241,'Buen estado',370,'2026-11-03 20:34:13',NULL,NULL),(577,376,60,'Buen estado',370,'2026-11-03 20:34:13',NULL,NULL),(578,377,18,NULL,NULL,'2026-11-03 20:34:13',NULL,NULL),(579,378,258,NULL,NULL,'2026-11-03 20:34:13',NULL,NULL),(580,379,257,NULL,NULL,'2026-11-03 20:34:13',NULL,NULL),(581,380,112,NULL,NULL,'2026-11-03 20:34:13',NULL,NULL),(582,380,43,NULL,NULL,'2026-11-03 20:34:13',NULL,NULL),(583,381,69,NULL,NULL,'2026-11-03 20:34:13',NULL,NULL),(584,382,136,NULL,NULL,'2026-11-03 20:34:13',NULL,NULL),(585,383,199,NULL,NULL,'2026-11-03 21:03:52',NULL,NULL),(586,384,1,'Buen estado',371,'2026-11-03 21:12:33',NULL,NULL);
/*!40000 ALTER TABLE `detalle_prestamos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `multas`
--

DROP TABLE IF EXISTS `multas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `multas` (
  `Codigo_Multa` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Codigo_Detalle` int(10) unsigned NOT NULL,
  `Monto` decimal(8,2) NOT NULL DEFAULT 0.00,
  `Motivo` enum('Mora','Daño','Perdida') NOT NULL DEFAULT 'Mora',
  `Estado_Pago` enum('Pendiente','Pagado') NOT NULL DEFAULT 'Pendiente',
  `Fecha_Emision` date NOT NULL DEFAULT curdate(),
  `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_modificacion` varchar(50) DEFAULT NULL,
  `motivo_modificacion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`Codigo_Multa`),
  KEY `multas_codigo_detalle_foreign` (`Codigo_Detalle`),
  CONSTRAINT `multas_codigo_detalle_foreign` FOREIGN KEY (`Codigo_Detalle`) REFERENCES `detalle_prestamos` (`Codigo_Detalle`)
) ENGINE=InnoDB AUTO_INCREMENT=348 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `multas`
--

LOCK TABLES `multas` WRITE;
/*!40000 ALTER TABLE `multas` DISABLE KEYS */;
INSERT INTO `multas` VALUES (232,384,10.00,'Perdida','Pagado','2025-01-31','2026-11-03 20:34:11',NULL,NULL),(233,386,1.00,'Mora','Pagado','2025-03-09','2026-11-03 20:34:11',NULL,NULL),(234,387,1.00,'Mora','Pagado','2025-03-09','2026-11-03 20:34:11',NULL,NULL),(235,390,0.75,'Mora','Pagado','2025-02-26','2026-11-03 20:34:11',NULL,NULL),(236,391,0.75,'Mora','Pagado','2025-02-26','2026-11-03 20:34:11',NULL,NULL),(237,396,2.00,'Daño','Pagado','2025-04-02','2026-11-03 20:34:11',NULL,NULL),(238,399,1.00,'Mora','Pagado','2025-04-11','2026-11-03 20:34:11',NULL,NULL),(239,400,1.00,'Mora','Pagado','2025-04-11','2026-11-03 20:34:11',NULL,NULL),(240,401,0.25,'Mora','Pagado','2025-04-04','2026-11-03 20:34:11',NULL,NULL),(241,402,0.25,'Mora','Pagado','2025-04-04','2026-11-03 20:34:11',NULL,NULL),(242,407,0.75,'Mora','Pagado','2025-04-30','2026-11-03 20:34:11',NULL,NULL),(243,407,10.00,'Perdida','Pagado','2025-04-30','2026-11-03 20:34:11',NULL,NULL),(244,409,2.00,'Daño','Pendiente','2025-05-01','2026-11-03 20:34:12',NULL,NULL),(245,410,2.00,'Daño','Pagado','2025-05-01','2026-11-03 20:34:12',NULL,NULL),(246,413,2.00,'Mora','Pagado','2025-05-11','2026-11-03 20:34:12',NULL,NULL),(247,414,2.00,'Mora','Pagado','2025-05-11','2026-11-03 20:34:12',NULL,NULL),(248,415,10.00,'Perdida','Pagado','2025-04-15','2026-11-03 20:34:12',NULL,NULL),(249,416,0.75,'Mora','Pendiente','2025-05-01','2026-11-03 20:34:12',NULL,NULL),(250,417,0.50,'Mora','Pagado','2025-04-23','2026-11-03 20:34:12',NULL,NULL),(251,418,0.50,'Mora','Pagado','2025-04-23','2026-11-03 20:34:12',NULL,NULL),(252,419,2.00,'Daño','Pagado','2025-05-07','2026-11-03 20:34:12',NULL,NULL),(253,421,1.75,'Mora','Pagado','2025-05-28','2026-11-03 20:34:12',NULL,NULL),(254,422,1.75,'Mora','Pagado','2025-05-28','2026-11-03 20:34:12',NULL,NULL),(255,424,2.25,'Mora','Pendiente','2025-05-25','2026-11-03 20:34:12',NULL,NULL),(256,427,2.50,'Mora','Pagado','2025-06-14','2026-11-03 20:34:12',NULL,NULL),(257,428,2.50,'Mora','Pagado','2025-06-14','2026-11-03 20:34:12',NULL,NULL),(258,431,0.75,'Mora','Pagado','2025-06-09','2026-11-03 20:34:12',NULL,NULL),(259,432,1.00,'Mora','Pendiente','2025-05-21','2026-11-03 20:34:12',NULL,NULL),(260,433,1.00,'Mora','Pagado','2025-05-21','2026-11-03 20:34:12',NULL,NULL),(261,434,0.75,'Mora','Pagado','2025-07-09','2026-11-03 20:34:12',NULL,NULL),(262,438,2.00,'Daño','Pagado','2025-06-30','2026-11-03 20:34:12',NULL,NULL),(263,444,1.50,'Mora','Pendiente','2025-08-02','2026-11-03 20:34:12',NULL,NULL),(264,445,2.25,'Mora','Pagado','2025-09-15','2026-11-03 20:34:12',NULL,NULL),(265,446,1.25,'Mora','Pagado','2025-09-09','2026-11-03 20:34:12',NULL,NULL),(266,446,2.00,'Daño','Pagado','2025-09-09','2026-11-03 20:34:12',NULL,NULL),(267,447,1.25,'Mora','Pagado','2025-09-09','2026-11-03 20:34:12',NULL,NULL),(268,450,1.00,'Mora','Pagado','2025-10-15','2026-11-03 20:34:12',NULL,NULL),(269,451,1.00,'Mora','Pagado','2025-10-15','2026-11-03 20:34:12',NULL,NULL),(270,452,1.25,'Mora','Pendiente','2025-09-26','2026-11-03 20:34:12',NULL,NULL),(271,453,1.25,'Mora','Pagado','2025-09-26','2026-11-03 20:34:12',NULL,NULL),(272,454,2.50,'Mora','Pagado','2025-10-13','2026-11-03 20:34:12',NULL,NULL),(273,455,2.50,'Mora','Pagado','2025-10-13','2026-11-03 20:34:12',NULL,NULL),(274,456,2.50,'Mora','Pagado','2025-10-18','2026-11-03 20:34:12',NULL,NULL),(275,458,0.50,'Mora','Pagado','2025-10-03','2026-11-03 20:34:12',NULL,NULL),(276,459,0.50,'Mora','Pagado','2025-10-03','2026-11-03 20:34:12',NULL,NULL),(277,463,0.50,'Mora','Pagado','2025-11-01','2026-11-03 20:34:12',NULL,NULL),(278,464,0.75,'Mora','Pagado','2025-10-20','2026-11-03 20:34:12',NULL,NULL),(279,465,0.75,'Mora','Pagado','2025-10-20','2026-11-03 20:34:12',NULL,NULL),(280,468,1.75,'Mora','Pagado','2025-10-26','2026-11-03 20:34:12',NULL,NULL),(281,468,10.00,'Perdida','Pagado','2025-10-26','2026-11-03 20:34:12',NULL,NULL),(282,469,1.75,'Mora','Pendiente','2025-10-26','2026-11-03 20:34:12',NULL,NULL),(283,470,1.00,'Mora','Pagado','2025-11-14','2026-11-03 20:34:12',NULL,NULL),(284,471,1.00,'Mora','Pagado','2025-11-14','2026-11-03 20:34:12',NULL,NULL),(285,472,2.25,'Mora','Pagado','2025-11-20','2026-11-03 20:34:12',NULL,NULL),(286,473,2.25,'Mora','Pagado','2025-11-20','2026-11-03 20:34:12',NULL,NULL),(287,474,0.75,'Mora','Pendiente','2025-11-12','2026-11-03 20:34:12',NULL,NULL),(288,477,2.00,'Daño','Pagado','2025-11-29','2026-11-03 20:34:12',NULL,NULL),(289,478,10.00,'Perdida','Pagado','2025-11-29','2026-11-03 20:34:12',NULL,NULL),(290,479,2.00,'Daño','Pendiente','2025-12-06','2026-11-03 20:34:12',NULL,NULL),(291,488,2.00,'Mora','Pendiente','2026-01-19','2026-11-03 20:34:12',NULL,NULL),(292,489,2.00,'Mora','Pendiente','2026-01-19','2026-11-03 20:34:12',NULL,NULL),(293,490,1.75,'Mora','Pagado','2026-01-14','2026-11-03 20:34:12',NULL,NULL),(294,491,2.00,'Daño','Pagado','2025-12-10','2026-11-03 20:34:12',NULL,NULL),(295,492,10.00,'Perdida','Pagado','2025-12-10','2026-11-03 20:34:12',NULL,NULL),(296,493,10.00,'Perdida','Pendiente','2026-01-15','2026-11-03 20:34:12',NULL,NULL),(297,494,1.00,'Mora','Pagado','2026-02-03','2026-11-03 20:34:12',NULL,NULL),(298,495,1.00,'Mora','Pagado','2026-02-03','2026-11-03 20:34:12',NULL,NULL),(299,495,10.00,'Perdida','Pagado','2026-02-03','2026-11-03 20:34:12',NULL,NULL),(300,501,1.25,'Mora','Pendiente','2026-03-06','2026-11-03 20:34:12',NULL,NULL),(301,502,1.25,'Mora','Pendiente','2026-03-06','2026-11-03 20:34:12',NULL,NULL),(302,503,2.25,'Mora','Pagado','2026-03-21','2026-11-03 20:34:12',NULL,NULL),(303,503,2.00,'Daño','Pagado','2026-03-21','2026-11-03 20:34:12',NULL,NULL),(304,506,1.75,'Mora','Pagado','2026-03-15','2026-11-03 20:34:12',NULL,NULL),(305,506,2.00,'Daño','Pagado','2026-03-15','2026-11-03 20:34:12',NULL,NULL),(306,508,2.00,'Mora','Pagado','2026-04-04','2026-11-03 20:34:12',NULL,NULL),(307,509,2.00,'Mora','Pagado','2026-04-04','2026-11-03 20:34:12',NULL,NULL),(308,512,2.25,'Mora','Pagado','2026-03-31','2026-11-03 20:34:12',NULL,NULL),(309,512,2.00,'Daño','Pagado','2026-03-31','2026-11-03 20:34:12',NULL,NULL),(310,513,2.25,'Mora','Pagado','2026-03-31','2026-11-03 20:34:12',NULL,NULL),(311,515,10.00,'Perdida','Pagado','2026-03-31','2026-11-03 20:34:12',NULL,NULL),(312,516,0.50,'Mora','Pagado','2026-03-20','2026-11-03 20:34:12',NULL,NULL),(313,516,2.00,'Daño','Pagado','2026-03-20','2026-11-03 20:34:12',NULL,NULL),(314,517,0.50,'Mora','Pagado','2026-03-20','2026-11-03 20:34:12',NULL,NULL),(315,518,1.50,'Mora','Pagado','2026-04-03','2026-11-03 20:34:12',NULL,NULL),(316,519,1.25,'Mora','Pagado','2026-03-22','2026-11-03 20:34:12',NULL,NULL),(317,520,1.25,'Mora','Pagado','2026-03-22','2026-11-03 20:34:12',NULL,NULL),(318,522,10.00,'Perdida','Pagado','2026-04-18','2026-11-03 20:34:12',NULL,NULL),(319,524,10.00,'Perdida','Pendiente','2026-05-05','2026-11-03 20:34:12',NULL,NULL),(320,526,1.50,'Mora','Pagado','2026-05-15','2026-11-03 20:34:12',NULL,NULL),(321,527,1.50,'Mora','Pagado','2026-05-15','2026-11-03 20:34:12',NULL,NULL),(322,528,0.50,'Mora','Pagado','2026-05-09','2026-11-03 20:34:12',NULL,NULL),(323,529,2.00,'Mora','Pagado','2026-04-27','2026-11-03 20:34:12',NULL,NULL),(324,530,2.00,'Mora','Pagado','2026-04-27','2026-11-03 20:34:12',NULL,NULL),(325,530,10.00,'Perdida','Pagado','2026-04-27','2026-11-03 20:34:12',NULL,NULL),(326,531,0.25,'Mora','Pendiente','2026-04-19','2026-11-03 20:34:12',NULL,NULL),(327,532,1.25,'Mora','Pagado','2026-05-10','2026-11-03 20:34:12',NULL,NULL),(328,533,0.75,'Mora','Pagado','2026-06-10','2026-11-03 20:34:12',NULL,NULL),(329,534,0.75,'Mora','Pendiente','2026-06-10','2026-11-03 20:34:12',NULL,NULL),(330,536,0.50,'Mora','Pagado','2026-05-17','2026-11-03 20:34:12',NULL,NULL),(331,538,2.00,'Daño','Pagado','2026-05-24','2026-11-03 20:34:12',NULL,NULL),(332,539,1.50,'Mora','Pagado','2026-06-11','2026-11-03 20:34:12',NULL,NULL),(333,541,0.75,'Mora','Pagado','2026-06-07','2026-11-03 20:34:12',NULL,NULL),(334,548,2.25,'Mora','Pagado','2026-08-14','2026-11-03 20:34:12',NULL,NULL),(335,548,2.00,'Daño','Pagado','2026-08-14','2026-11-03 20:34:12',NULL,NULL),(336,549,2.25,'Mora','Pendiente','2026-08-14','2026-11-03 20:34:12',NULL,NULL),(337,556,2.00,'Daño','Pagado','2026-08-24','2026-11-03 20:34:12',NULL,NULL),(338,558,2.00,'Mora','Pagado','2026-09-25','2026-11-03 20:34:12',NULL,NULL),(339,561,0.25,'Mora','Pagado','2026-10-13','2026-11-03 20:34:12',NULL,NULL),(340,563,2.00,'Daño','Pagado','2026-09-15','2026-11-03 20:34:12',NULL,NULL),(341,564,2.50,'Mora','Pagado','2026-09-28','2026-11-03 20:34:12',NULL,NULL),(342,565,1.00,'Mora','Pagado','2026-10-11','2026-11-03 20:34:12',NULL,NULL),(343,566,1.25,'Mora','Pagado','2026-10-16','2026-11-03 20:34:13',NULL,NULL),(344,567,1.25,'Mora','Pagado','2026-10-16','2026-11-03 20:34:13',NULL,NULL),(345,569,10.00,'Perdida','Pendiente','2026-11-01','2026-11-03 20:54:16',NULL,NULL),(346,570,2.00,'Daño','Pendiente','2026-10-15','2026-11-03 20:34:13',NULL,NULL),(347,573,1.50,'Mora','Pendiente','2026-10-28','2026-11-03 20:34:13',NULL,NULL);
/*!40000 ALTER TABLE `multas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `passkeys`
--

DROP TABLE IF EXISTS `passkeys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `passkeys` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `credential_id` varchar(255) NOT NULL,
  `credential` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`credential`)),
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `passkeys_credential_id_unique` (`credential_id`),
  KEY `passkeys_user_id_index` (`user_id`),
  CONSTRAINT `passkeys_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `passkeys`
--

LOCK TABLES `passkeys` WRITE;
/*!40000 ALTER TABLE `passkeys` DISABLE KEYS */;
/*!40000 ALTER TABLE `passkeys` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-03 15:35:55
-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: sistema_biblioteca
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-03 15:35:56
