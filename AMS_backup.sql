/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.14-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: AMS
-- ------------------------------------------------------
-- Server version	10.11.14-MariaDB-0ubuntu0.24.04.1

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
-- Current Database: `AMS`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `AMS` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `AMS`;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT 1,
  `firstname` varchar(255) NOT NULL,
  `secondname` varchar(255) DEFAULT NULL,
  `surname` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `admin_level` enum('super_admin','admin','moderator') DEFAULT 'admin',
  `created_at` datetime DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `phone_number` (`phone_number`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES
(1,1,'System',NULL,'Administrator','+254700000001','sysadmin@auction.co.ke','sysadmin','$2y$10$adminhash001','super_admin','2020-01-01 09:00:00','2024-05-30 14:30:00'),
(2,1,'Admin','James','Wilson','+254700000002','admin.wilson@auction.co.ke','admin_wilson','$2y$10$adminhash002','admin','2020-02-01 10:00:00','2024-05-29 15:45:00'),
(3,1,'Sarah','Jane','Thompson','+254700000003','sarah.thompson@auction.co.ke','sarah_t','$2y$10$adminhash003','admin','2020-03-01 11:00:00','2024-05-28 16:20:00'),
(4,1,'Michael','David','Roberts','+254700000004','michael.roberts@auction.co.ke','michael_r','$2y$10$adminhash004','admin','2020-04-01 12:00:00','2024-05-27 09:15:00'),
(5,1,'Emily','Grace','Davis','+254700000005','emily.davis@auction.co.ke','emily_d','$2y$10$adminhash005','moderator','2020-05-01 13:00:00','2024-05-26 11:30:00'),
(6,1,'David','Paul','Anderson','+254700000006','david.anderson@auction.co.ke','david_a','$2y$10$adminhash006','moderator','2020-06-01 14:00:00','2024-05-25 14:45:00'),
(7,1,'Jennifer',NULL,'Miller','+254700000007','jennifer.miller@auction.co.ke','jennifer_m','$2y$10$adminhash007','moderator','2020-07-01 15:00:00','2024-05-24 10:20:00'),
(8,1,'Robert','John','Taylor','+254700000008','robert.taylor@auction.co.ke','robert_t','$2y$10$adminhash008','admin','2020-08-01 16:00:00','2024-05-23 13:15:00'),
(9,1,'Lisa','Marie','White','+254700000009','lisa.white@auction.co.ke','lisa_w','$2y$10$adminhash009','admin','2020-09-01 17:00:00','2024-05-22 16:30:00'),
(10,1,'Thomas','William','Harris','+254700000010','thomas.harris@auction.co.ke','thomas_h','$2y$10$adminhash010','super_admin','2020-10-01 18:00:00','2024-05-21 09:45:00'),
(11,1,'John','Phil','Kamau','+254700000011','j.kamau@auction.co.ke','j_kamau','kamau123','admin','2026-02-09 11:37:55',NULL),
(12,1,'Taby','','Zila','0720744370','tabzila@auction.co.ke','tabzila','$2y$10$adminhash0055','admin','2026-05-04 12:21:42',NULL);
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auction_bids`
--

DROP TABLE IF EXISTS `auction_bids`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `auction_bids` (
  `bid_id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL,
  `bidder_id` int(11) NOT NULL,
  `amount_bidded` decimal(10,2) NOT NULL,
  `bid_status` enum('active','outbid','winning','withdrawn') DEFAULT 'active',
  `result` enum('won','lost','pending') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`bid_id`),
  KEY `auction_id` (`auction_id`),
  KEY `bidder_id` (`bidder_id`),
  CONSTRAINT `auction_bids_ibfk_1` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`auction_id`),
  CONSTRAINT `auction_bids_ibfk_2` FOREIGN KEY (`bidder_id`) REFERENCES `users` (`UID`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auction_bids`
--

LOCK TABLES `auction_bids` WRITE;
/*!40000 ALTER TABLE `auction_bids` DISABLE KEYS */;
INSERT INTO `auction_bids` VALUES
(1,1,1,2100.00,'outbid','lost','2024-03-01 14:05:00'),
(2,1,2,2300.00,'outbid','lost','2024-03-01 14:10:00'),
(3,1,3,2500.00,'outbid','lost','2024-03-01 14:15:00'),
(4,1,4,2800.00,'outbid','lost','2024-03-01 14:20:00'),
(5,1,5,3200.00,'winning','won','2024-03-01 14:25:00'),
(6,2,6,7000.00,'outbid','lost','2024-03-02 15:05:00'),
(7,2,7,7500.00,'outbid','lost','2024-03-02 15:10:00'),
(8,2,8,8000.00,'outbid','lost','2024-03-02 15:15:00'),
(9,2,9,8500.00,'winning','won','2024-03-02 15:20:00'),
(10,3,10,42000.00,'outbid','lost','2024-03-03 10:05:00'),
(11,3,11,45000.00,'outbid','lost','2024-03-03 10:15:00'),
(12,3,12,48000.00,'outbid','lost','2024-03-03 10:25:00'),
(13,3,13,52000.00,'winning','won','2024-03-03 10:35:00'),
(14,4,14,1300.00,'outbid','lost','2024-03-04 14:05:00'),
(15,4,15,1500.00,'outbid','lost','2024-03-04 14:10:00'),
(16,4,16,1800.00,'winning','won','2024-03-04 14:15:00'),
(17,5,17,11000.00,'outbid','lost','2024-03-05 11:05:00'),
(18,5,18,12500.00,'winning','won','2024-03-05 11:15:00'),
(19,6,19,2600.00,'outbid','lost','2024-03-06 15:05:00'),
(20,6,20,3000.00,'winning','won','2024-03-06 15:10:00'),
(21,11,1,26000.00,'outbid','lost','2024-03-12 10:00:00'),
(22,11,2,28000.00,'winning','won','2024-03-13 14:00:00'),
(23,12,3,40000.00,'winning','won','2024-03-13 11:00:00'),
(24,13,4,480000.00,'winning','won','2024-03-18 09:00:00'),
(25,14,5,14000.00,'winning','won','2024-03-17 15:00:00'),
(26,15,6,32000.00,'winning','won','2024-03-19 10:00:00'),
(27,16,7,35000.00,'winning','won','2024-03-21 14:00:00'),
(28,17,8,22000.00,'winning','won','2024-03-22 11:00:00'),
(29,18,9,1100.00,'winning','won','2024-03-22 16:00:00'),
(30,19,10,700.00,'winning','won','2024-03-23 14:00:00'),
(31,20,11,1800.00,'winning','won','2024-03-24 10:00:00'),
(32,21,12,1400.00,'winning','won','2024-03-25 15:00:00'),
(33,22,13,2800.00,'winning','won','2024-03-26 11:00:00'),
(34,23,14,700.00,'winning','won','2024-03-27 14:00:00'),
(35,24,15,1300.00,'winning','won','2024-03-28 10:00:00'),
(36,25,16,450.00,'winning','won','2024-03-29 15:00:00'),
(37,31,17,350.00,'active','pending','2024-05-25 10:00:00'),
(38,31,18,400.00,'winning','pending','2024-05-26 14:00:00'),
(39,32,19,1300.00,'winning','pending','2024-05-26 11:00:00'),
(40,33,20,500.00,'winning','pending','2024-05-27 15:00:00'),
(41,34,1,350.00,'winning','pending','2024-05-28 10:00:00'),
(42,35,2,2200.00,'winning','pending','2024-05-29 14:00:00'),
(43,36,3,1500.00,'winning','pending','2024-05-30 11:00:00'),
(44,37,4,1000.00,'winning','pending','2024-05-31 15:00:00'),
(45,1,6,2400.00,'outbid','lost','2024-03-01 14:12:00'),
(46,2,7,7200.00,'outbid','lost','2024-03-02 15:08:00'),
(47,3,8,44000.00,'outbid','lost','2024-03-03 10:20:00'),
(48,4,9,1400.00,'outbid','lost','2024-03-04 14:08:00'),
(49,5,10,11500.00,'outbid','lost','2024-03-05 11:10:00'),
(50,6,11,2800.00,'outbid','lost','2024-03-06 15:08:00'),
(51,7,12,6500.00,'winning','won','2024-03-07 14:30:00'),
(52,8,13,13000.00,'winning','won','2024-03-08 10:45:00'),
(53,9,14,22000.00,'winning','won','2024-03-09 09:30:00'),
(54,10,15,7000.00,'winning','won','2024-03-10 14:45:00'),
(55,1,16,2700.00,'outbid','lost','2024-03-01 14:18:00'),
(56,1,17,2900.00,'outbid','lost','2024-03-01 14:22:00'),
(57,2,18,7800.00,'outbid','lost','2024-03-02 15:12:00'),
(58,2,19,8200.00,'outbid','lost','2024-03-02 15:16:00'),
(59,3,20,47000.00,'outbid','lost','2024-03-03 10:28:00'),
(60,3,1,50000.00,'outbid','lost','2024-03-03 10:32:00'),
(61,52,51,60000.00,'active','lost','2026-02-07 00:09:09'),
(62,52,51,7000.00,'active','lost','2026-04-15 16:36:16'),
(63,52,51,7000.00,'active','lost','2026-04-15 16:36:27'),
(64,52,51,70000.00,'active','lost','2026-04-15 17:59:39'),
(65,52,51,100.00,'active','lost','2026-04-15 18:14:37'),
(66,52,51,100.00,'active','lost','2026-04-15 18:14:43'),
(67,52,51,100.00,'active','lost','2026-04-15 18:14:50'),
(68,52,51,100.00,'active','lost','2026-04-15 18:17:12');
/*!40000 ALTER TABLE `auction_bids` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auctions`
--

DROP TABLE IF EXISTS `auctions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `auctions` (
  `auction_id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_name` varchar(255) NOT NULL,
  `auction_code` varchar(50) NOT NULL,
  `auction_type` enum('live','timed') DEFAULT 'timed',
  `item_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('draft','upcoming','ongoing','completed','cancelled') DEFAULT 'draft',
  `created_by_staff` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`auction_id`),
  UNIQUE KEY `auction_code` (`auction_code`),
  UNIQUE KEY `item_id` (`item_id`),
  KEY `created_by_staff` (`created_by_staff`),
  CONSTRAINT `auctions_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `evaluated_items` (`eval_id`),
  CONSTRAINT `auctions_ibfk_3` FOREIGN KEY (`created_by_staff`) REFERENCES `staff` (`staff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auctions`
--

LOCK TABLES `auctions` WRITE;
/*!40000 ALTER TABLE `auctions` DISABLE KEYS */;
INSERT INTO `auctions` VALUES
(1,'Premium Antiques Auction','ANTQ-2024-001','live',1,'2024-03-01 14:00:00','2024-03-01 16:00:00','completed',6,'2024-02-25 10:00:00',NULL),
(2,'Fine Jewelry Collection','JEWL-2024-001','live',2,'2024-03-02 15:00:00','2024-03-02 17:00:00','completed',6,'2024-02-26 11:00:00',NULL),
(3,'Classic Car Auction','CAR-2024-001','live',3,'2024-03-03 10:00:00','2024-03-03 12:00:00','completed',6,'2024-02-27 12:00:00',NULL),
(4,'Art & Paintings Auction','ART-2024-001','live',4,'2024-03-04 14:00:00','2024-03-04 16:00:00','completed',6,'2024-02-28 13:00:00',NULL),
(5,'Luxury Watches Auction','WATCH-2024-001','live',5,'2024-03-05 11:00:00','2024-03-05 13:00:00','completed',6,'2024-02-29 14:00:00',NULL),
(6,'Rare Collectibles Auction','COLL-2024-001','live',6,'2024-03-06 15:00:00','2024-03-06 17:00:00','completed',6,'2024-03-01 15:00:00',NULL),
(7,'Designer Jewelry Auction','JEWL-2024-002','live',7,'2024-03-07 14:00:00','2024-03-07 16:00:00','completed',6,'2024-03-02 16:00:00',NULL),
(8,'Musical Instruments Auction','MUSIC-2024-001','live',8,'2024-03-08 10:00:00','2024-03-08 12:00:00','completed',6,'2024-03-03 09:00:00',NULL),
(9,'Commercial Equipment Auction','COMM-2024-001','live',9,'2024-03-09 09:00:00','2024-03-09 11:00:00','completed',6,'2024-03-04 10:00:00',NULL),
(10,'Office Furniture Auction','OFFICE-2024-001','live',10,'2024-03-10 14:00:00','2024-03-10 16:00:00','completed',6,'2024-03-05 11:00:00',NULL),
(11,'Electronics Timed Auction','ELEC-2024-001','timed',11,'2024-03-11 00:00:00','2024-03-15 23:59:59','completed',6,'2024-03-06 12:00:00',NULL),
(12,'Laptop Computers Auction','LAPTOP-2024-001','timed',12,'2024-03-12 00:00:00','2024-03-16 23:59:59','completed',6,'2024-03-07 13:00:00',NULL),
(13,'Real Estate Auction','RE-2024-001','timed',13,'2024-03-13 00:00:00','2024-03-20 23:59:59','completed',6,'2024-03-08 14:00:00',NULL),
(14,'Wine Collection Auction','WINE-2024-001','timed',14,'2024-03-14 00:00:00','2024-03-18 23:59:59','completed',6,'2024-03-09 15:00:00',NULL),
(15,'Farm Equipment Auction','FARM-2024-001','timed',15,'2024-03-15 00:00:00','2024-03-19 23:59:59','completed',6,'2024-03-10 16:00:00',NULL),
(16,'Hotel Furniture Auction','HTL-2024-001','timed',16,'2024-03-16 00:00:00','2024-03-21 23:59:59','completed',6,'2024-03-11 09:00:00',NULL),
(17,'Restaurant Equipment Auction','REST-2024-001','timed',17,'2024-03-17 00:00:00','2024-03-22 23:59:59','completed',6,'2024-03-12 10:00:00',NULL),
(18,'Mobile Phones Auction','PHONE-2024-001','timed',18,'2024-03-18 00:00:00','2024-03-22 23:59:59','completed',6,'2024-03-13 11:00:00',NULL),
(19,'Designer Fashion Auction','FASH-2024-001','timed',19,'2024-03-19 00:00:00','2024-03-23 23:59:59','completed',6,'2024-03-14 12:00:00',NULL),
(20,'Gaming Equipment Auction','GAME-2024-001','timed',20,'2024-03-20 00:00:00','2024-03-24 23:59:59','completed',6,'2024-03-15 13:00:00',NULL),
(21,'Sports Equipment Auction','SPORT-2024-001','timed',21,'2024-03-21 00:00:00','2024-03-25 23:59:59','completed',6,'2024-03-16 14:00:00',NULL),
(22,'Camera Equipment Auction','CAM-2024-001','timed',22,'2024-03-22 00:00:00','2024-03-26 23:59:59','completed',6,'2024-03-17 15:00:00',NULL),
(23,'Musical Instruments Auction 2','MUSIC-2024-002','timed',23,'2024-03-23 00:00:00','2024-03-27 23:59:59','completed',6,'2024-03-18 16:00:00',NULL),
(24,'Luxury Watches Auction 2','WATCH-2024-002','timed',24,'2024-03-24 00:00:00','2024-03-28 23:59:59','completed',6,'2024-03-19 09:00:00',NULL),
(25,'Perfume Collection Auction','PERF-2024-001','timed',25,'2024-03-25 00:00:00','2024-03-29 23:59:59','completed',6,'2024-03-20 10:00:00',NULL),
(26,'Fitness Equipment Auction','FIT-2024-001','timed',26,'2024-06-01 00:00:00','2024-06-07 23:59:59','upcoming',6,'2024-05-25 11:00:00',NULL),
(27,'Art Supplies Auction','ART-2024-002','timed',27,'2024-06-02 00:00:00','2024-06-08 23:59:59','upcoming',6,'2024-05-26 12:00:00',NULL),
(28,'Antique Collection Auction','ANTQ-2024-002','timed',28,'2024-06-03 00:00:00','2024-06-09 23:59:59','upcoming',6,'2024-05-27 13:00:00',NULL),
(29,'Coin Collection Auction','COIN-2024-001','timed',29,'2024-06-04 00:00:00','2024-06-10 23:59:59','upcoming',6,'2024-05-28 14:00:00',NULL),
(30,'Vintage Camera Auction','CAM-2024-002','timed',30,'2024-06-05 00:00:00','2024-06-11 23:59:59','upcoming',6,'2024-05-29 15:00:00',NULL),
(31,'Sports Memorabilia Auction','SPORT-2024-002','timed',31,'2024-05-20 00:00:00','2024-05-30 23:59:59','ongoing',6,'2024-05-15 16:00:00',NULL),
(32,'Doll Collection Auction','DOLL-2024-001','timed',32,'2024-05-21 00:00:00','2024-05-31 23:59:59','ongoing',6,'2024-05-16 09:00:00',NULL),
(33,'Electric Vehicles Auction','EV-2024-001','timed',33,'2024-05-22 00:00:00','2024-06-01 23:59:59','ongoing',6,'2024-05-17 10:00:00',NULL),
(34,'Designer Accessories Auction','ACC-2024-001','timed',34,'2024-05-23 00:00:00','2024-06-02 23:59:59','ongoing',6,'2024-05-18 11:00:00',NULL),
(35,'Smart Home Auction','SMART-2024-001','timed',35,'2024-05-24 00:00:00','2024-06-03 23:59:59','ongoing',6,'2024-05-19 12:00:00',NULL),
(36,'Drone & Tech Auction','DRONE-2024-001','timed',36,'2024-05-25 00:00:00','2024-06-04 23:59:59','ongoing',6,'2024-05-20 13:00:00',NULL),
(37,'Kitchen Appliances Auction','KITCH-2024-001','timed',37,'2024-05-26 00:00:00','2024-06-05 23:59:59','ongoing',6,'2024-05-21 14:00:00',NULL),
(38,'Musical Instruments Live','MUSIC-2024-003','live',38,'2024-06-10 14:00:00','2024-06-10 16:00:00','upcoming',6,'2024-06-01 15:00:00',NULL),
(39,'Shoe Collection Auction','SHOE-2024-001','timed',39,'2024-06-11 00:00:00','2024-06-17 23:59:59','upcoming',6,'2024-06-02 16:00:00',NULL),
(40,'Commercial Mixer Auction','COMM-2024-002','timed',40,'2024-06-12 00:00:00','2024-06-18 23:59:59','upcoming',6,'2024-06-03 09:00:00',NULL),
(41,'Home Theater Auction','HT-2024-001','timed',41,'2024-06-13 00:00:00','2024-06-19 23:59:59','upcoming',6,'2024-06-04 10:00:00',NULL),
(42,'Fine Wine Auction','WINE-2024-002','live',42,'2024-06-14 15:00:00','2024-06-14 17:00:00','upcoming',6,'2024-06-05 11:00:00',NULL),
(43,'Sports Collectibles Auction','SPORT-2024-003','timed',43,'2024-06-15 00:00:00','2024-06-21 23:59:59','upcoming',6,'2024-06-06 12:00:00',NULL),
(44,'Luxury Bedding Auction','BED-2024-001','timed',44,'2024-06-16 00:00:00','2024-06-22 23:59:59','upcoming',6,'2024-06-07 13:00:00',NULL),
(45,'Photography Equipment Auction','PHOTO-2024-001','timed',45,'2024-06-17 00:00:00','2024-06-23 23:59:59','upcoming',6,'2024-06-08 14:00:00',NULL),
(46,'Antique Furniture Auction','FURN-2024-001','timed',46,'2024-06-18 00:00:00','2024-06-24 23:59:59','upcoming',6,'2024-06-09 15:00:00',NULL),
(47,'Smart Watches Auction','WATCH-2024-003','timed',47,'2024-06-19 00:00:00','2024-06-25 23:59:59','upcoming',6,'2024-06-10 16:00:00',NULL),
(48,'Summer Electronics Auction','ELEC-2024-002','timed',48,'2024-07-01 00:00:00','2024-07-07 23:59:59','draft',6,'2024-06-20 09:00:00',NULL),
(49,'Back to School Auction','SCHOOL-2024-001','timed',49,'2024-07-02 00:00:00','2024-07-08 23:59:59','draft',6,'2024-06-21 10:00:00',NULL),
(50,'Holiday Special Auction','HOLIDAY-2024-001','live',50,'2024-12-15 18:00:00','2024-12-15 20:00:00','draft',6,'2024-06-22 11:00:00',NULL),
(52,'Lenov Monitor Auction','#55547','live',52,'2026-02-07 12:30:00','2026-02-08 12:30:00','upcoming',50,'2026-02-06 15:19:58',NULL);
/*!40000 ALTER TABLE `auctions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consigner_items`
--

DROP TABLE IF EXISTS `consigner_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `consigner_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(255) NOT NULL,
  `item_quantity` int(11) NOT NULL,
  `item_description` varchar(500) DEFAULT NULL,
  `item_category` varchar(100) DEFAULT NULL,
  `item_condition` enum('new','used','refurbished','antique') DEFAULT 'used',
  `consigner_id` int(11) NOT NULL,
  `item_status` enum('pending','under_review','approved','rejected','auctioned','sold','returned') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `image_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  KEY `consigner_id` (`consigner_id`),
  CONSTRAINT `consigner_items_ibfk_1` FOREIGN KEY (`consigner_id`) REFERENCES `users` (`UID`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consigner_items`
--

LOCK TABLES `consigner_items` WRITE;
/*!40000 ALTER TABLE `consigner_items` DISABLE KEYS */;
INSERT INTO `consigner_items` VALUES
(1,'Victorian Antique Chair',1,'19th century mahogany chair with original upholstery','Antique Furniture','antique',21,'approved','2024-01-05 09:00:00',NULL,NULL),
(2,'Original Oil Painting',1,'Landscape painting by local artist, signed','Art','used',21,'approved','2024-01-06 10:00:00',NULL,NULL),
(3,'Diamond Engagement Ring',1,'2-carat diamond ring with platinum setting','Jewelry','used',22,'approved','2024-01-07 11:00:00',NULL,NULL),
(4,'Rare Stamp Collection',1,'Collection of 50 rare stamps from 1800s','Collectibles','antique',22,'approved','2024-01-08 12:00:00',NULL,NULL),
(5,'Vintage Sports Car',1,'1965 Ford Mustang restored to original condition','Vehicles','refurbished',23,'approved','2024-01-09 13:00:00',NULL,NULL),
(6,'Antique Persian Rug',1,'Hand-woven silk rug, 8x10 feet','Home Decor','antique',23,'approved','2024-01-10 14:00:00',NULL,NULL),
(7,'Rolex Submariner Watch',1,'Vintage 1970 Rolex in working condition','Watches','used',24,'approved','2024-01-11 15:00:00',NULL,NULL),
(8,'Signed First Edition Book',1,'First edition signed by author, excellent condition','Books','used',24,'approved','2024-01-12 16:00:00',NULL,NULL),
(9,'Emerald Necklace Set',1,'18K gold necklace with matching earrings','Jewelry','used',25,'approved','2024-01-15 09:00:00',NULL,NULL),
(10,'Antique Grand Piano',1,'Steinway grand piano from 1920s','Musical Instruments','refurbished',25,'approved','2024-01-16 10:00:00',NULL,NULL),
(11,'Commercial Coffee Machine',5,'Industrial espresso machines, new in box','Commercial Equipment','new',26,'approved','2024-01-18 11:00:00',NULL,NULL),
(12,'Office Desk Set',10,'Executive office desks with chairs','Office Furniture','new',26,'approved','2024-01-19 12:00:00',NULL,NULL),
(13,'Designer Sofa Collection',8,'Modern leather sofa sets','Furniture','new',27,'approved','2024-01-22 13:00:00',NULL,NULL),
(14,'LED Television Bulk Lot',20,'55-inch smart TVs, brand new','Electronics','new',28,'approved','2024-01-23 14:00:00',NULL,NULL),
(15,'Laptop Computers',15,'Business grade laptops with warranty','Electronics','new',28,'approved','2024-01-24 15:00:00',NULL,NULL),
(16,'Vacant Land Plot',1,'5-acre commercial plot in industrial area','Real Estate','new',29,'approved','2024-01-25 16:00:00',NULL,NULL),
(17,'Wine Collection',50,'Premium imported wines from various regions','Wine & Spirits','new',30,'approved','2024-01-26 09:00:00',NULL,NULL),
(18,'Farm Equipment',3,'Tractors and harvesters, used but functional','Agricultural','used',26,'approved','2024-01-29 10:00:00',NULL,NULL),
(19,'Hotel Furniture Lot',100,'Complete hotel room furniture sets','Commercial Furniture','used',27,'approved','2024-01-30 11:00:00',NULL,NULL),
(20,'Restaurant Kitchen Equipment',25,'Commercial kitchen appliances','Restaurant Equipment','used',28,'approved','2024-01-31 12:00:00',NULL,NULL),
(21,'iPhone 15 Pro Max',1,'Latest model, 256GB, like new','Mobile Phones','refurbished',31,'approved','2024-02-01 13:00:00',NULL,NULL),
(22,'Designer Handbag',1,'Gucci handbag, authentic, good condition','Fashion','used',31,'approved','2024-02-02 14:00:00',NULL,NULL),
(23,'Gaming Laptop',1,'High-end gaming laptop with RTX 4080','Electronics','used',32,'approved','2024-02-05 15:00:00',NULL,NULL),
(24,'Mountain Bike',1,'Professional mountain bike, carbon frame','Sports Equipment','used',32,'approved','2024-02-06 16:00:00',NULL,NULL),
(25,'Camera Lens Collection',3,'Professional camera lenses for photography','Photography','used',33,'approved','2024-02-07 09:00:00',NULL,NULL),
(26,'Guitar and Amplifier',1,'Electric guitar with amplifier set','Musical Instruments','used',33,'approved','2024-02-08 10:00:00',NULL,NULL),
(27,'Designer Watch',1,'Tag Heuer automatic watch','Watches','used',34,'approved','2024-02-09 11:00:00',NULL,NULL),
(28,'Luxury Perfume Set',5,'Collection of high-end perfumes','Beauty','new',34,'approved','2024-02-12 12:00:00',NULL,NULL),
(29,'Exercise Equipment',1,'Home gym equipment set','Fitness','used',35,'approved','2024-02-13 13:00:00',NULL,NULL),
(30,'Art Supplies Collection',50,'Professional art supplies in bulk','Art Supplies','new',35,'approved','2024-02-14 14:00:00',NULL,NULL),
(31,'Antique Typewriter',1,'Vintage typewriter from 1930s','Antiques','antique',36,'approved','2024-02-15 15:00:00',NULL,NULL),
(32,'Coin Collection',1,'Rare coin collection from different eras','Collectibles','antique',36,'approved','2024-02-16 16:00:00',NULL,NULL),
(33,'Vintage Camera',1,'Leica film camera with lenses','Photography','antique',37,'approved','2024-02-19 09:00:00',NULL,NULL),
(34,'Signed Baseball',1,'Baseball signed by famous player','Sports Memorabilia','used',37,'approved','2024-02-20 10:00:00',NULL,NULL),
(35,'Porcelain Doll Collection',10,'Collectible porcelain dolls in original boxes','Collectibles','new',38,'approved','2024-02-21 11:00:00',NULL,NULL),
(36,'Electric Scooter',1,'High-speed electric scooter','Personal Transportation','used',38,'approved','2024-02-22 12:00:00',NULL,NULL),
(37,'Designer Sunglasses',5,'Brand new designer sunglasses','Fashion Accessories','new',39,'approved','2024-02-23 13:00:00',NULL,NULL),
(38,'Smart Home System',1,'Complete smart home automation system','Home Automation','new',39,'approved','2024-02-26 14:00:00',NULL,NULL),
(39,'Drone with Camera',1,'Professional drone with 4K camera','Electronics','used',40,'approved','2024-02-27 15:00:00',NULL,NULL),
(40,'Cooking Appliance Set',3,'Premium kitchen appliances set','Kitchen Appliances','new',40,'approved','2024-02-28 16:00:00',NULL,NULL),
(41,'Electric Guitar',1,'Fender Stratocaster, excellent condition','Musical Instruments','used',21,'approved','2024-02-29 09:00:00',NULL,NULL),
(42,'Designer Shoes Collection',10,'Brand new designer shoes','Fashion','new',22,'approved','2024-03-01 10:00:00',NULL,NULL),
(43,'Professional Mixer',1,'Commercial food mixer','Commercial Equipment','used',26,'approved','2024-03-04 11:00:00',NULL,NULL),
(44,'Home Theater System',1,'Complete surround sound system','Electronics','used',28,'approved','2024-03-05 12:00:00',NULL,NULL),
(45,'Vintage Wine Collection',12,'Aged wines from French vineyard','Wine & Spirits','antique',30,'approved','2024-03-06 13:00:00',NULL,NULL),
(46,'Sports Memorabilia Collection',20,'Various sports signed items','Sports Memorabilia','used',31,'approved','2024-03-07 14:00:00',NULL,NULL),
(47,'Luxury Bed Set',1,'King size bed with premium mattress','Bedroom Furniture','new',27,'approved','2024-03-08 15:00:00',NULL,NULL),
(48,'Professional Camera',1,'Canon EOS R5 with accessories','Photography','used',33,'approved','2024-03-11 16:00:00',NULL,NULL),
(49,'Antique Jewelry Box',1,'Hand-carved wooden jewelry box','Antiques','antique',34,'approved','2024-03-12 09:00:00',NULL,NULL),
(50,'Smart Watch Collection',5,'Latest smart watches from top brands','Wearables','new',35,'approved','2024-03-13 10:00:00',NULL,NULL),
(51,'Monitor',1,'Lenovo','Electronics','used',51,'approved','2026-02-02 03:53:42','2026-02-05 09:42:43','../uploads/consigned_items/images.jpeg'),
(52,'Monitor',1,'Lenovo','Electronics','used',51,'approved','2026-02-02 03:59:15','2026-02-05 11:10:10','../uploads/consigned_items/images.jpeg');
/*!40000 ALTER TABLE `consigner_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `department` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_name` varchar(255) NOT NULL,
  `department_description` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`department_id`),
  UNIQUE KEY `department_name` (`department_name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `department`
--

LOCK TABLES `department` WRITE;
/*!40000 ALTER TABLE `department` DISABLE KEYS */;
INSERT INTO `department` VALUES
(1,'Executive Management','Senior leadership team'),
(2,'Auction Operations','Day-to-day auction management'),
(3,'Item Evaluation','Item assessment and valuation'),
(4,'Finance & Accounting','Financial processing and accounting'),
(5,'Customer Service','Client support and relations'),
(6,'Marketing & Sales','Promotion and business development'),
(7,'Logistics & Warehouse','Item handling and storage'),
(8,'Information Technology','Technical systems and support'),
(9,'Human Resources','Staff management and development'),
(10,'Legal & Compliance','Legal matters and compliance'),
(11,'Security','Physical and cybersecurity'),
(12,'Business Development','New business acquisition');
/*!40000 ALTER TABLE `department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluated_items`
--

DROP TABLE IF EXISTS `evaluated_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `evaluated_items` (
  `eval_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `evaluator_id` int(11) NOT NULL,
  `evaluation_date` date DEFAULT NULL,
  `condition_rating` enum('excellent','good','fair','poor') DEFAULT 'good',
  `authenticity_status` enum('authentic','replica','questionable') DEFAULT 'authentic',
  `reserve_price` decimal(10,2) NOT NULL,
  `evaluation_notes` text DEFAULT NULL,
  `final_decision` enum('Approved','Rejected','Pending') DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`eval_id`),
  UNIQUE KEY `item_id` (`item_id`),
  KEY `evaluator_id` (`evaluator_id`),
  CONSTRAINT `evaluated_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `consigner_items` (`item_id`),
  CONSTRAINT `evaluated_items_ibfk_2` FOREIGN KEY (`evaluator_id`) REFERENCES `staff` (`staff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evaluated_items`
--

LOCK TABLES `evaluated_items` WRITE;
/*!40000 ALTER TABLE `evaluated_items` DISABLE KEYS */;
INSERT INTO `evaluated_items` VALUES
(1,1,11,'2024-01-10','good','authentic',2000.00,'Original upholstery intact, needs polishing','Approved','2024-01-10 10:00:00'),
(2,2,12,'2024-01-12','excellent','authentic',1200.00,'Well-preserved, frame in good condition','Approved','2024-01-12 11:00:00'),
(3,3,13,'2024-01-15','excellent','authentic',6500.00,'Includes GIA certificate, excellent condition','Approved','2024-01-15 14:00:00'),
(4,4,14,'2024-01-18','good','authentic',2500.00,'Some stamps need better preservation','Approved','2024-01-18 15:00:00'),
(5,5,15,'2024-01-20','excellent','authentic',40000.00,'Engine rebuilt, new tires, excellent paint job','Approved','2024-01-20 09:00:00'),
(6,6,11,'2024-01-22','fair','authentic',4000.00,'Small repairs needed on edges','Approved','2024-01-22 10:30:00'),
(7,7,12,'2024-01-25','good','authentic',10000.00,'Recently serviced, keeping good time','Approved','2024-01-25 11:00:00'),
(8,8,13,'2024-01-28','excellent','authentic',600.00,'Book in protective sleeve, pages intact','Approved','2024-01-28 13:00:00'),
(9,9,14,'2024-02-01','excellent','authentic',6000.00,'Gemstones certified, settings secure','Approved','2024-02-01 14:00:00'),
(10,10,15,'2024-02-03','good','authentic',12000.00,'Tuned recently, some keys need adjustment','Approved','2024-02-03 15:00:00'),
(11,11,11,'2024-02-05','excellent','authentic',20000.00,'Still in original packaging, warranty included','Approved','2024-02-05 09:00:00'),
(12,12,12,'2024-02-07','excellent','authentic',6500.00,'Modern design, ergonomic chairs','Approved','2024-02-07 10:00:00'),
(13,13,13,'2024-02-10','excellent','authentic',10000.00,'Premium leather, still wrapped','Approved','2024-02-10 11:00:00'),
(14,14,14,'2024-02-12','excellent','authentic',25000.00,'Latest models, sealed boxes','Approved','2024-02-12 14:00:00'),
(15,15,15,'2024-02-15','excellent','authentic',38000.00,'Enterprise models with extended warranty','Approved','2024-02-15 15:00:00'),
(16,16,11,'2024-02-18','excellent','authentic',450000.00,'All documents verified, ready for development','Approved','2024-02-18 09:00:00'),
(17,17,12,'2024-02-20','excellent','authentic',12000.00,'Properly stored, original cases','Approved','2024-02-20 10:00:00'),
(18,18,13,'2024-02-22','fair','authentic',28000.00,'Working condition, needs minor repairs','Approved','2024-02-22 11:00:00'),
(19,19,14,'2024-02-25','good','authentic',32000.00,'Lightly used, well maintained','Approved','2024-02-25 14:00:00'),
(20,20,15,'2024-02-28','good','authentic',20000.00,'Clean and functional, some wear visible','Approved','2024-02-28 15:00:00'),
(21,21,11,'2024-03-01','excellent','authentic',1000.00,'Battery health 100%, no scratches','Approved','2024-03-01 09:00:00'),
(22,22,12,'2024-03-03','good','authentic',600.00,'Minor scuffs on hardware','Approved','2024-03-03 10:00:00'),
(23,23,13,'2024-03-05','excellent','authentic',1600.00,'Perfect condition, includes original box','Approved','2024-03-05 11:00:00'),
(24,24,14,'2024-03-07','good','authentic',1200.00,'Recently serviced, tires new','Approved','2024-03-07 14:00:00'),
(25,25,15,'2024-03-10','excellent','authentic',2500.00,'Clean optics, includes cases','Approved','2024-03-10 15:00:00'),
(26,26,11,'2024-03-12','good','authentic',600.00,'Amplifier included, some cosmetic wear','Approved','2024-03-12 09:00:00'),
(27,27,12,'2024-03-15','excellent','authentic',1200.00,'Running accurately, includes box','Approved','2024-03-15 10:00:00'),
(28,28,13,'2024-03-18','excellent','authentic',400.00,'All sealed, authentic products','Approved','2024-03-18 11:00:00'),
(29,29,14,'2024-03-20','fair','authentic',900.00,'Some wear on padding, functional','Approved','2024-03-20 14:00:00'),
(30,30,15,'2024-03-22','excellent','authentic',600.00,'New in packaging, premium brands','Approved','2024-03-22 15:00:00'),
(31,31,11,'2024-03-25','good','authentic',300.00,'All keys working, needs ribbon','Approved','2024-03-25 09:00:00'),
(32,32,12,'2024-03-28','excellent','authentic',1500.00,'Well-preserved, includes display case','Approved','2024-03-28 10:00:00'),
(33,33,13,'2024-04-01','good','authentic',900.00,'Fully functional, minor cosmetic wear','Approved','2024-04-01 11:00:00'),
(34,34,14,'2024-04-03','excellent','authentic',200.00,'Includes certificate of authenticity','Approved','2024-04-03 14:00:00'),
(35,35,15,'2024-04-05','excellent','authentic',1200.00,'Mint condition, original packaging','Approved','2024-04-05 15:00:00'),
(36,36,11,'2024-04-08','excellent','authentic',450.00,'Low mileage, battery good','Approved','2024-04-08 09:00:00'),
(37,37,12,'2024-04-10','excellent','authentic',300.00,'Authentic, with cases','Approved','2024-04-10 10:00:00'),
(38,38,13,'2024-04-12','excellent','authentic',2000.00,'All components included','Approved','2024-04-12 11:00:00'),
(39,39,14,'2024-04-15','good','authentic',1400.00,'Includes extra batteries','Approved','2024-04-15 14:00:00'),
(40,40,15,'2024-04-18','excellent','authentic',900.00,'New in box, warranty','Approved','2024-04-18 15:00:00'),
(41,41,11,'2024-04-20','excellent','authentic',700.00,'Recently set up, plays well','Approved','2024-04-20 09:00:00'),
(42,42,12,'2024-04-22','excellent','authentic',1200.00,'Various sizes, authentic','Approved','2024-04-22 10:00:00'),
(43,43,13,'2024-04-25','good','authentic',900.00,'Used but well-maintained','Approved','2024-04-25 11:00:00'),
(44,44,14,'2024-04-28','good','authentic',1400.00,'Complete set, functional','Approved','2024-04-28 14:00:00'),
(45,45,15,'2024-05-01','excellent','authentic',2400.00,'Properly stored, premium wines','Approved','2024-05-01 15:00:00'),
(46,46,11,'2024-05-03','good','authentic',600.00,'Various items, certificates included','Approved','2024-05-03 09:00:00'),
(47,47,12,'2024-05-06','excellent','authentic',2000.00,'Premium materials, still wrapped','Approved','2024-05-06 10:00:00'),
(48,48,13,'2024-05-08','excellent','authentic',2800.00,'Complete with accessories','Approved','2024-05-08 11:00:00'),
(49,49,14,'2024-05-10','good','authentic',200.00,'Hand-carved, minor repairs needed','Approved','2024-05-10 14:00:00'),
(50,50,15,'2024-05-12','excellent','authentic',900.00,'Latest models, sealed','Approved','2024-05-12 15:00:00'),
(52,52,50,'2026-02-05','excellent','authentic',5000.00,'good','Approved','2026-02-05 11:10:10');
/*!40000 ALTER TABLE `evaluated_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) NOT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `bidder_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `issue_date` date DEFAULT curdate(),
  `due_date` date DEFAULT NULL,
  `status` enum('draft','unpaid','paid','cancelled','overdue') DEFAULT 'unpaid',
  `created_by_staff` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`invoice_id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `payment_id` (`payment_id`),
  KEY `bidder_id` (`bidder_id`),
  KEY `created_by_staff` (`created_by_staff`),
  CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`payment_id`),
  CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`bidder_id`) REFERENCES `users` (`UID`),
  CONSTRAINT `invoices_ibfk_3` FOREIGN KEY (`created_by_staff`) REFERENCES `staff` (`staff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES
(1,'INV-2024-001',1,5,3200.00,320.00,3520.00,'2024-03-02','2024-03-16','paid',20,'2024-03-02 10:35:00'),
(2,'INV-2024-002',2,9,8500.00,850.00,9350.00,'2024-03-03','2024-03-17','paid',20,'2024-03-03 11:20:00'),
(3,'INV-2024-003',3,13,52000.00,5200.00,57200.00,'2024-03-04','2024-03-18','paid',45,'2024-03-04 09:50:00'),
(4,'INV-2024-004',4,16,1800.00,180.00,1980.00,'2024-03-05','2024-03-19','paid',45,'2024-03-05 14:25:00'),
(5,'INV-2024-005',5,18,12500.00,1250.00,13750.00,'2024-03-06','2024-03-20','paid',20,'2024-03-06 10:35:00'),
(6,'INV-2024-006',6,20,3000.00,300.00,3300.00,'2024-03-07','2024-03-21','paid',20,'2024-03-07 11:20:00'),
(7,'INV-2024-007',7,12,6500.00,650.00,7150.00,'2024-03-08','2024-03-22','paid',45,'2024-03-08 15:35:00'),
(8,'INV-2024-008',8,13,13000.00,1300.00,14300.00,'2024-03-09','2024-03-23','paid',45,'2024-03-09 09:25:00'),
(9,'INV-2024-009',9,14,22000.00,2200.00,24200.00,'2024-03-10','2024-03-24','paid',20,'2024-03-10 14:50:00'),
(10,'INV-2024-010',10,15,7000.00,700.00,7700.00,'2024-03-11','2024-03-25','paid',20,'2024-03-11 11:20:00'),
(11,'INV-2024-011',11,2,28000.00,2800.00,30800.00,'2024-03-16','2024-03-30','paid',45,'2024-03-16 10:35:00'),
(12,'INV-2024-012',12,3,40000.00,4000.00,44000.00,'2024-03-17','2024-03-31','paid',45,'2024-03-17 14:25:00'),
(13,'INV-2024-013',13,4,480000.00,48000.00,528000.00,'2024-03-21','2024-04-04','paid',20,'2024-03-21 09:50:00'),
(14,'INV-2024-014',14,5,14000.00,1400.00,15400.00,'2024-03-19','2024-04-02','paid',20,'2024-03-19 15:20:00'),
(15,'INV-2024-015',15,6,32000.00,3200.00,35200.00,'2024-03-20','2024-04-03','paid',45,'2024-03-20 11:35:00'),
(16,'INV-2024-016',16,7,35000.00,3500.00,38500.00,'2024-03-22','2024-04-05','paid',45,'2024-03-22 14:25:00'),
(17,'INV-2024-017',17,8,22000.00,2200.00,24200.00,'2024-03-23','2024-04-06','paid',20,'2024-03-23 10:20:00'),
(18,'INV-2024-018',18,9,1100.00,110.00,1210.00,'2024-03-24','2024-04-07','paid',20,'2024-03-24 15:35:00'),
(19,'INV-2024-019',19,10,700.00,70.00,770.00,'2024-03-25','2024-04-08','paid',45,'2024-03-25 11:20:00'),
(20,'INV-2024-020',20,11,1800.00,180.00,1980.00,'2024-03-26','2024-04-09','paid',45,'2024-03-26 14:25:00'),
(21,'INV-2024-021',21,12,1400.00,140.00,1540.00,'2024-03-27','2024-04-10','paid',20,'2024-03-27 10:35:00'),
(22,'INV-2024-022',22,13,2800.00,280.00,3080.00,'2024-03-28','2024-04-11','paid',20,'2024-03-28 15:20:00'),
(23,'INV-2024-023',23,14,700.00,70.00,770.00,'2024-03-29','2024-04-12','paid',45,'2024-03-29 11:25:00'),
(24,'INV-2024-024',24,15,1300.00,130.00,1430.00,'2024-03-30','2024-04-13','paid',45,'2024-03-30 14:35:00'),
(25,'INV-2024-025',25,16,450.00,45.00,495.00,'2024-03-31','2024-04-14','paid',20,'2024-03-31 10:20:00'),
(26,'INV-2024-026',26,18,400.00,40.00,440.00,'2024-05-27','2024-06-10','unpaid',20,'2024-05-27 14:05:00'),
(27,'INV-2024-027',27,19,1300.00,130.00,1430.00,'2024-05-28','2024-06-11','unpaid',45,'2024-05-28 11:05:00'),
(28,'INV-2024-028',28,20,500.00,50.00,550.00,'2024-05-29','2024-06-12','unpaid',45,'2024-05-29 15:05:00'),
(29,'INV-2024-029',29,1,350.00,35.00,385.00,'2024-05-30','2024-06-13','unpaid',20,'2024-05-30 10:05:00'),
(30,'INV-2024-030',30,2,2200.00,220.00,2420.00,'2024-05-31','2024-06-14','unpaid',20,'2024-05-31 14:05:00'),
(31,'INV-2024-031',31,3,1500.00,150.00,1650.00,'2024-06-02','2024-06-16','draft',45,'2024-06-02 11:05:00'),
(32,'INV-2024-032',32,4,1000.00,100.00,1100.00,'2024-06-03','2024-06-17','draft',45,'2024-06-03 15:05:00'),
(33,'INV-2024-033',33,5,900.00,90.00,990.00,'2024-06-04','2024-06-18','draft',20,'2024-06-04 10:05:00'),
(34,'INV-2024-034',34,6,1500.00,150.00,1650.00,'2024-06-05','2024-06-19','draft',20,'2024-06-05 14:05:00'),
(35,'INV-2024-035',35,7,1200.00,120.00,1320.00,'2024-06-06','2024-06-20','draft',45,'2024-06-06 11:05:00'),
(36,'INV-2024-036',36,8,1800.00,180.00,1980.00,'2024-04-10','2024-04-24','overdue',45,'2024-04-10 14:05:00'),
(37,'INV-2024-037',37,9,3000.00,300.00,3300.00,'2024-04-12','2024-04-26','overdue',20,'2024-04-12 15:05:00'),
(38,'INV-2024-038',38,10,800.00,80.00,880.00,'2024-04-14','2024-04-28','overdue',20,'2024-04-14 09:05:00'),
(39,'INV-2024-039',39,11,2500.00,250.00,2750.00,'2024-04-16','2024-04-30','overdue',45,'2024-04-16 14:05:00'),
(40,'INV-2024-040',40,12,3500.00,350.00,3850.00,'2024-04-18','2024-05-02','overdue',45,'2024-04-18 11:05:00'),
(41,'INV-2024-041',41,13,300.00,30.00,330.00,'2024-04-20','2024-05-04','cancelled',20,'2024-04-20 15:05:00'),
(42,'INV-2024-042',42,14,1200.00,120.00,1320.00,'2024-04-22','2024-05-06','cancelled',20,'2024-04-22 10:05:00'),
(43,'INV-2024-043',43,15,26000.00,2600.00,28600.00,'2024-04-24','2024-05-08','cancelled',45,'2024-04-24 14:05:00'),
(44,'INV-2024-044',44,16,38000.00,3800.00,41800.00,'2024-04-26','2024-05-10','cancelled',45,'2024-04-26 11:05:00'),
(45,'INV-2024-045',45,17,3200.00,320.00,3520.00,'2024-04-28','2024-05-12','cancelled',20,'2024-04-28 15:05:00'),
(46,'INV-2024-046',NULL,18,450.00,45.00,495.00,'2024-05-01','2024-05-15','draft',20,'2024-05-01 10:00:00'),
(47,'INV-2024-047',NULL,19,600.00,60.00,660.00,'2024-05-02','2024-05-16','draft',45,'2024-05-02 11:00:00'),
(48,'INV-2024-048',NULL,20,750.00,75.00,825.00,'2024-05-03','2024-05-17','draft',45,'2024-05-03 12:00:00'),
(49,'INV-2024-049',NULL,21,900.00,90.00,990.00,'2024-05-04','2024-05-18','draft',20,'2024-05-04 13:00:00'),
(50,'INV-2024-050',NULL,22,1200.00,120.00,1320.00,'2024-05-05','2024-05-19','draft',20,'2024-05-05 14:00:00');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `bid_id` int(11) DEFAULT NULL,
  `bidder_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('credit_card','bank_transfer','mobile_money','cash') NOT NULL,
  `payment_status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `processed_by_staff` int(11) DEFAULT NULL,
  `transaction_reference` varchar(100) DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT current_timestamp(),
  `completed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `bid_id` (`bid_id`),
  KEY `bidder_id` (`bidder_id`),
  KEY `processed_by_staff` (`processed_by_staff`),
  CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`bid_id`) REFERENCES `auction_bids` (`bid_id`),
  CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`bidder_id`) REFERENCES `users` (`UID`),
  CONSTRAINT `payment_ibfk_3` FOREIGN KEY (`processed_by_staff`) REFERENCES `staff` (`staff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment`
--

LOCK TABLES `payment` WRITE;
/*!40000 ALTER TABLE `payment` DISABLE KEYS */;
INSERT INTO `payment` VALUES
(1,5,5,3200.00,'bank_transfer','completed',18,'TRX-001-ANTQ','2024-03-02 07:00:00','2024-03-02 10:30:00'),
(2,9,9,8500.00,'credit_card','completed',18,'TRX-002-JEWL','2024-03-03 08:00:00','2024-03-03 11:15:00'),
(3,13,13,52000.00,'bank_transfer','completed',19,'TRX-003-CAR','2024-03-04 06:00:00','2024-03-04 09:45:00'),
(4,16,16,1800.00,'mobile_money','completed',19,'TRX-004-ART','2024-03-05 11:00:00','2024-03-05 14:20:00'),
(5,18,18,12500.00,'credit_card','completed',18,'TRX-005-WATCH','2024-03-06 07:00:00','2024-03-06 10:30:00'),
(6,20,20,3000.00,'mobile_money','completed',19,'TRX-006-COLL','2024-03-07 08:00:00','2024-03-07 11:15:00'),
(7,51,12,6500.00,'bank_transfer','completed',18,'TRX-007-JEWL2','2024-03-08 12:00:00','2024-03-08 15:30:00'),
(8,52,13,13000.00,'credit_card','completed',19,'TRX-008-MUSIC','2024-03-09 06:00:00','2024-03-09 09:20:00'),
(9,53,14,22000.00,'bank_transfer','completed',18,'TRX-009-COMM','2024-03-10 11:00:00','2024-03-10 14:45:00'),
(10,54,15,7000.00,'mobile_money','completed',19,'TRX-010-OFFICE','2024-03-11 08:00:00','2024-03-11 11:15:00'),
(12,23,3,40000.00,'credit_card','completed',19,'TRX-012-LAPTOP','2024-03-17 11:00:00','2024-03-17 14:20:00'),
(13,24,4,480000.00,'bank_transfer','completed',18,'TRX-013-RE','2024-03-21 06:00:00','2024-03-21 09:45:00'),
(14,25,5,14000.00,'credit_card','completed',19,'TRX-014-WINE','2024-03-19 12:00:00','2024-03-19 15:15:00'),
(15,26,6,32000.00,'bank_transfer','completed',18,'TRX-015-FARM','2024-03-20 08:00:00','2024-03-20 11:30:00'),
(16,27,7,35000.00,'credit_card','completed',19,'TRX-016-HTL','2024-03-22 11:00:00','2024-03-22 14:20:00'),
(17,28,8,22000.00,'mobile_money','completed',18,'TRX-017-REST','2024-03-23 07:00:00','2024-03-23 10:15:00'),
(18,29,9,1100.00,'credit_card','completed',19,'TRX-018-PHONE','2024-03-24 12:00:00','2024-03-24 15:30:00'),
(19,30,10,700.00,'mobile_money','completed',18,'TRX-019-FASH','2024-03-25 08:00:00','2024-03-25 11:15:00'),
(20,31,11,1800.00,'credit_card','completed',19,'TRX-020-GAME','2024-03-26 11:00:00','2024-03-26 14:20:00'),
(21,32,12,1400.00,'bank_transfer','completed',18,'TRX-021-SPORT','2024-03-27 07:00:00','2024-03-27 10:30:00'),
(22,33,13,2800.00,'credit_card','completed',19,'TRX-022-CAM','2024-03-28 12:00:00','2024-03-28 15:15:00'),
(23,34,14,700.00,'mobile_money','completed',18,'TRX-023-MUSIC2','2024-03-29 08:00:00','2024-03-29 11:20:00'),
(24,35,15,1300.00,'credit_card','completed',19,'TRX-024-WATCH2','2024-03-30 11:00:00','2024-03-30 14:30:00'),
(25,36,16,450.00,'mobile_money','completed',18,'TRX-025-PERF','2024-03-31 07:00:00','2024-03-31 10:15:00'),
(26,38,18,400.00,'credit_card','pending',NULL,'TRX-026-SPORT2','2024-05-27 11:00:00',NULL),
(27,39,19,1300.00,'bank_transfer','pending',NULL,'TRX-027-DOLL','2024-05-28 08:00:00',NULL),
(28,40,20,500.00,'mobile_money','pending',NULL,'TRX-028-EV','2024-05-29 12:00:00',NULL),
(29,41,1,350.00,'credit_card','pending',NULL,'TRX-029-ACC','2024-05-30 07:00:00',NULL),
(31,55,16,2700.00,'credit_card','failed',18,'TRX-031-FAIL1','2024-03-02 11:30:00',NULL),
(32,56,17,2900.00,'bank_transfer','failed',19,'TRX-032-FAIL2','2024-03-02 11:40:00',NULL),
(33,57,18,7800.00,'mobile_money','failed',18,'TRX-033-FAIL3','2024-03-03 12:30:00',NULL),
(34,58,19,8200.00,'credit_card','refunded',19,'TRX-034-REF1','2024-03-03 12:40:00','2024-03-04 10:00:00'),
(35,59,20,47000.00,'bank_transfer','refunded',18,'TRX-035-REF2','2024-03-04 07:30:00','2024-03-05 09:00:00'),
(36,60,1,50000.00,'credit_card','refunded',19,'TRX-036-REF3','2024-03-04 07:45:00','2024-03-05 10:00:00'),
(37,1,1,2100.00,'mobile_money','completed',18,'TRX-037-BID1','2024-03-01 11:06:00','2024-03-01 14:10:00'),
(39,3,3,2500.00,'bank_transfer','completed',18,'TRX-039-BID3','2024-03-01 11:16:00','2024-03-01 14:20:00'),
(40,4,4,2800.00,'mobile_money','completed',19,'TRX-040-BID4','2024-03-01 11:21:00','2024-03-01 14:25:00'),
(41,6,6,7000.00,'credit_card','completed',18,'TRX-041-BID6','2024-03-02 12:06:00','2024-03-02 15:10:00'),
(42,7,7,7500.00,'bank_transfer','completed',19,'TRX-042-BID7','2024-03-02 12:11:00','2024-03-02 15:15:00'),
(43,8,8,8000.00,'mobile_money','completed',18,'TRX-043-BID8','2024-03-02 12:16:00','2024-03-02 15:20:00'),
(44,10,10,42000.00,'credit_card','completed',19,'TRX-044-BID10','2024-03-03 07:06:00','2024-03-03 10:15:00'),
(45,11,11,45000.00,'bank_transfer','completed',18,'TRX-045-BID11','2024-03-03 07:16:00','2024-03-03 10:25:00'),
(46,12,12,48000.00,'mobile_money','completed',19,'TRX-046-BID12','2024-03-03 07:26:00','2024-03-03 10:35:00'),
(47,14,14,1300.00,'credit_card','completed',18,'TRX-047-BID14','2024-03-04 11:06:00','2024-03-04 14:10:00'),
(48,15,15,1500.00,'bank_transfer','completed',19,'TRX-048-BID15','2024-03-04 11:11:00','2024-03-04 14:15:00'),
(49,17,17,11000.00,'mobile_money','completed',18,'TRX-049-BID17','2024-03-05 08:06:00','2024-03-05 11:15:00'),
(50,19,19,2600.00,'credit_card','completed',19,'TRX-050-BID19','2024-03-06 12:06:00','2024-03-06 15:10:00'),
(51,22,2,28000.00,'credit_card','pending',NULL,'MTET6979R','2026-03-11 07:11:52',NULL),
(52,22,2,28000.00,'credit_card','pending',NULL,'MTET6979R','2026-03-11 07:12:01',NULL);
/*!40000 ALTER TABLE `payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) NOT NULL,
  `role_description` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES
(1,'System Administrator','Full system access and configuration'),
(2,'Auction Manager','Manages auctions and staff assignments'),
(3,'Senior Evaluator','Evaluates high-value items'),
(4,'Junior Evaluator','Evaluates standard items'),
(5,'Chief Auctioneer','Conducts major live auctions'),
(6,'Auctioneer','Conducts standard auctions'),
(7,'Finance Manager','Oversees all financial operations'),
(8,'Payment Processor','Processes customer payments'),
(9,'Settlement Officer','Handles consigner settlements'),
(10,'Customer Support Lead','Manages customer support team'),
(11,'Customer Support Agent','Handles customer inquiries'),
(12,'IT Support Specialist','Technical system support'),
(13,'Marketing Manager','Auction marketing and promotion'),
(14,'Content Writer','Creates auction descriptions'),
(15,'Photographer','Photographs auction items'),
(16,'Logistics Manager','Handles item shipping'),
(17,'Warehouse Supervisor','Manages item storage'),
(18,'Legal Advisor','Legal compliance and contracts'),
(19,'HR Manager','Staff recruitment and management'),
(20,'Data Analyst','Analyzes auction performance'),
(21,'Premium Consigner','VIP consigners with high-value items'),
(22,'Business Consigner','Commercial consigners'),
(23,'Individual Consigner','Individual item consigners'),
(24,'Premium Bidder','VIP bidders with high limits'),
(25,'Business Bidder','Corporate bidders'),
(26,'Individual Bidder','Regular individual bidders'),
(27,'International Bidder','Bidders from other countries'),
(28,'Antique Specialist','Specializes in antique evaluation'),
(29,'Art Specialist','Specializes in art evaluation'),
(30,'Jewelry Specialist','Specializes in jewelry evaluation'),
(31,'Vehicle Specialist','Specializes in vehicle evaluation'),
(32,'Real Estate Specialist','Specializes in property evaluation'),
(33,'Electronics Specialist','Specializes in electronics evaluation'),
(34,'Collectibles Specialist','Specializes in collectibles'),
(35,'Sports Memorabilia Specialist','Specializes in sports items'),
(36,'Wine Specialist','Specializes in wine/spirits evaluation'),
(37,'Furniture Specialist','Specializes in furniture evaluation'),
(38,'Livestock Specialist','Specializes in livestock evaluation'),
(39,'Equipment Specialist','Specializes in industrial equipment'),
(40,'Documentation Officer','Handles item documentation'),
(41,'Authentication Expert','Verifies item authenticity'),
(42,'Insurance Specialist','Handles item insurance'),
(43,'Shipping Coordinator','Coordinates item shipping'),
(44,'Quality Control Officer','Ensures quality standards'),
(45,'Training Coordinator','Staff training and development'),
(46,'Compliance Officer','Ensures regulatory compliance'),
(47,'Security Manager','Physical and digital security'),
(48,'Database Administrator','Manages database systems'),
(49,'Backup Operator','Handles system backups'),
(50,'Reporting Specialist','Creates auction reports'),
(51,'Janitor','Ensures Workplace Environment is clean');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settlement`
--

DROP TABLE IF EXISTS `settlement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `settlement` (
  `settlement_id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_item_id` int(11) NOT NULL,
  `amount_due` decimal(10,2) NOT NULL,
  `commission_rate` decimal(5,2) NOT NULL DEFAULT 15.00,
  `commission_amount` decimal(10,2) NOT NULL,
  `net_amount` decimal(10,2) NOT NULL,
  `settlement_date` date DEFAULT NULL,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `processed_by_staff` int(11) DEFAULT NULL,
  `payment_method` enum('bank_transfer','cheque','mobile_money') DEFAULT NULL,
  `transaction_reference` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`settlement_id`),
  KEY `auction_item_id` (`auction_item_id`),
  KEY `processed_by_staff` (`processed_by_staff`),
  CONSTRAINT `settlement_ibfk_2` FOREIGN KEY (`auction_item_id`) REFERENCES `consigner_items` (`item_id`),
  CONSTRAINT `settlement_ibfk_3` FOREIGN KEY (`processed_by_staff`) REFERENCES `staff` (`staff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settlement`
--

LOCK TABLES `settlement` WRITE;
/*!40000 ALTER TABLE `settlement` DISABLE KEYS */;
INSERT INTO `settlement` VALUES
(1,1,3200.00,15.00,480.00,2720.00,'2024-03-05','completed',20,'bank_transfer','SET-001-ANTQ','2024-03-03 10:00:00',NULL),
(2,3,8500.00,15.00,1275.00,7225.00,'2024-03-06','completed',20,'bank_transfer','SET-002-JEWL','2024-03-04 11:00:00',NULL),
(3,5,52000.00,10.00,5200.00,46800.00,'2024-03-07','completed',20,'cheque','SET-003-CAR','2024-03-05 09:00:00',NULL),
(4,2,1800.00,15.00,270.00,1530.00,'2024-03-08','completed',45,'mobile_money','SET-004-ART','2024-03-06 14:00:00',NULL),
(5,7,12500.00,15.00,1875.00,10625.00,'2024-03-09','completed',45,'bank_transfer','SET-005-WATCH','2024-03-07 10:00:00',NULL),
(6,4,3000.00,15.00,450.00,2550.00,'2024-03-10','completed',20,'mobile_money','SET-006-COLL','2024-03-08 11:00:00',NULL),
(7,9,6500.00,15.00,975.00,5525.00,'2024-03-11','completed',45,'bank_transfer','SET-007-JEWL2','2024-03-09 15:00:00',NULL),
(8,10,13000.00,10.00,1300.00,11700.00,'2024-03-12','completed',20,'cheque','SET-008-MUSIC','2024-03-10 09:00:00',NULL),
(9,11,22000.00,12.00,2640.00,19360.00,'2024-03-13','completed',45,'bank_transfer','SET-009-COMM','2024-03-11 14:00:00',NULL),
(10,12,7000.00,12.00,840.00,6160.00,'2024-03-14','completed',20,'mobile_money','SET-010-OFFICE','2024-03-12 11:00:00',NULL),
(11,14,28000.00,12.00,3360.00,24640.00,'2024-03-19','completed',45,'bank_transfer','SET-011-ELEC','2024-03-17 10:00:00',NULL),
(12,15,40000.00,12.00,4800.00,35200.00,'2024-03-20','completed',20,'cheque','SET-012-LAPTOP','2024-03-18 14:00:00',NULL),
(13,16,480000.00,8.00,38400.00,441600.00,'2024-03-24','completed',45,'bank_transfer','SET-013-RE','2024-03-22 09:00:00',NULL),
(14,17,14000.00,15.00,2100.00,11900.00,'2024-03-22','completed',20,'bank_transfer','SET-014-WINE','2024-03-20 15:00:00',NULL),
(15,18,32000.00,12.00,3840.00,28160.00,'2024-03-23','completed',45,'cheque','SET-015-FARM','2024-03-21 11:00:00',NULL),
(16,19,35000.00,12.00,4200.00,30800.00,'2024-03-25','completed',20,'bank_transfer','SET-016-HTL','2024-03-23 14:00:00',NULL),
(17,20,22000.00,12.00,2640.00,19360.00,'2024-03-26','completed',45,'mobile_money','SET-017-REST','2024-03-24 10:00:00',NULL),
(18,21,1100.00,15.00,165.00,935.00,'2024-03-27','completed',20,'bank_transfer','SET-018-PHONE','2024-03-25 15:00:00',NULL),
(19,22,700.00,15.00,105.00,595.00,'2024-03-28','completed',45,'mobile_money','SET-019-FASH','2024-03-26 11:00:00',NULL),
(20,23,1800.00,15.00,270.00,1530.00,'2024-03-29','completed',20,'bank_transfer','SET-020-GAME','2024-03-27 14:00:00',NULL),
(21,24,1400.00,15.00,210.00,1190.00,'2024-03-30','completed',45,'mobile_money','SET-021-SPORT','2024-03-28 10:00:00',NULL),
(22,25,2800.00,15.00,420.00,2380.00,'2024-03-31','completed',20,'bank_transfer','SET-022-CAM','2024-03-29 15:00:00',NULL),
(23,26,700.00,15.00,105.00,595.00,'2024-04-01','completed',45,'mobile_money','SET-023-MUSIC2','2024-03-30 11:00:00',NULL),
(24,27,1300.00,15.00,195.00,1105.00,'2024-04-02','completed',20,'bank_transfer','SET-024-WATCH2','2024-03-31 14:00:00',NULL),
(25,28,450.00,15.00,67.50,382.50,'2024-04-03','completed',45,'mobile_money','SET-025-PERF','2024-04-01 10:00:00',NULL),
(26,34,400.00,15.00,60.00,340.00,NULL,'pending',NULL,NULL,NULL,'2024-05-28 14:00:00',NULL),
(27,35,1300.00,15.00,195.00,1105.00,NULL,'pending',NULL,NULL,NULL,'2024-05-29 11:00:00',NULL),
(28,36,500.00,15.00,75.00,425.00,NULL,'pending',NULL,NULL,NULL,'2024-05-30 15:00:00',NULL),
(29,37,350.00,15.00,52.50,297.50,NULL,'pending',NULL,NULL,NULL,'2024-05-31 10:00:00',NULL),
(30,38,2200.00,15.00,330.00,1870.00,NULL,'pending',NULL,NULL,NULL,'2024-06-01 14:00:00',NULL),
(31,39,1500.00,15.00,225.00,1275.00,NULL,'processing',20,'bank_transfer','SET-031-DRONE','2024-06-02 11:00:00',NULL),
(32,40,1000.00,15.00,150.00,850.00,NULL,'processing',45,'mobile_money','SET-032-KITCH','2024-06-03 15:00:00',NULL),
(33,41,900.00,15.00,135.00,765.00,NULL,'processing',20,NULL,NULL,'2024-06-04 10:00:00',NULL),
(34,42,1500.00,15.00,225.00,1275.00,NULL,'processing',45,NULL,NULL,'2024-06-05 14:00:00',NULL),
(35,43,1200.00,15.00,180.00,1020.00,NULL,'processing',20,NULL,NULL,'2024-06-06 11:00:00',NULL),
(36,44,1800.00,15.00,270.00,1530.00,'2024-04-10','completed',45,'bank_transfer','SET-036-HT','2024-04-08 14:00:00',NULL),
(37,45,3000.00,15.00,450.00,2550.00,'2024-04-12','completed',20,'cheque','SET-037-WINE2','2024-04-10 15:00:00',NULL),
(38,46,800.00,15.00,120.00,680.00,'2024-04-14','completed',45,'mobile_money','SET-038-SPORT3','2024-04-12 09:00:00',NULL),
(39,47,2500.00,12.00,300.00,2200.00,'2024-04-16','completed',20,'bank_transfer','SET-039-BED','2024-04-14 14:00:00',NULL),
(40,48,3500.00,15.00,525.00,2975.00,'2024-04-18','completed',45,'cheque','SET-040-PHOTO','2024-04-16 11:00:00',NULL),
(41,49,300.00,15.00,45.00,255.00,'2024-04-20','completed',20,'mobile_money','SET-041-FURN','2024-04-18 15:00:00',NULL),
(42,50,1200.00,15.00,180.00,1020.00,'2024-04-22','completed',45,'bank_transfer','SET-042-WATCH3','2024-04-20 10:00:00',NULL),
(43,14,26000.00,12.00,3120.00,22880.00,'2024-04-24','completed',20,'cheque','SET-043-ELEC2','2024-04-22 14:00:00',NULL),
(44,15,38000.00,12.00,4560.00,33440.00,'2024-04-26','completed',45,'bank_transfer','SET-044-SCHOOL','2024-04-24 11:00:00',NULL),
(45,1,3200.00,15.00,480.00,2720.00,'2024-04-28','completed',20,'mobile_money','SET-045-HOLIDAY','2024-04-26 15:00:00',NULL),
(46,31,400.00,15.00,60.00,340.00,NULL,'cancelled',45,NULL,NULL,'2024-04-10 09:00:00',NULL),
(47,32,2000.00,15.00,300.00,1700.00,NULL,'cancelled',20,NULL,NULL,'2024-04-12 14:00:00',NULL),
(48,33,1200.00,15.00,180.00,1020.00,NULL,'cancelled',45,NULL,NULL,'2024-04-14 11:00:00',NULL),
(49,34,300.00,15.00,45.00,255.00,NULL,'cancelled',20,NULL,NULL,'2024-04-16 15:00:00',NULL),
(50,35,1500.00,15.00,225.00,1275.00,NULL,'cancelled',45,NULL,NULL,'2024-04-18 10:00:00',NULL);
/*!40000 ALTER TABLE `settlement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL AUTO_INCREMENT,
  `national_id` int(11) NOT NULL,
  `employee_id` varchar(50) NOT NULL,
  `role_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `secondname` varchar(255) DEFAULT NULL,
  `surname` varchar(255) NOT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `phone_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `hire_date` date NOT NULL,
  `employment_status` enum('active','on_leave','terminated','suspended') DEFAULT 'active',
  `salary` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`staff_id`),
  UNIQUE KEY `national_id` (`national_id`),
  UNIQUE KEY `employee_id` (`employee_id`),
  UNIQUE KEY `phone_number` (`phone_number`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`),
  KEY `role_id` (`role_id`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON UPDATE CASCADE,
  CONSTRAINT `staff_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES
(1,301001,'EMP-MGT-001',1,1,'Robert','James','Smith','CEO','+254711001001','robert.smith@auction.co.ke','robert_s','hash001','2018-06-15','active',450000.00,'2018-06-15 09:00:00',NULL),
(2,301002,'EMP-MGT-002',1,1,'Jennifer','Anne','Johnson','COO','+254711001002','jennifer.j@auction.co.ke','jennifer_j','hash002','2019-01-20','active',380000.00,'2019-01-20 10:00:00',NULL),
(3,301003,'EMP-MGT-003',1,1,'Richard',NULL,'Williams','CFO','+254711001003','richard.w@auction.co.ke','richard_w','hash003','2019-03-10','active',360000.00,'2019-03-10 11:00:00',NULL),
(4,301004,'EMP-MGT-004',1,1,'Patricia','Marie','Jones','HR Director','+254711001004','patricia.j@auction.co.ke','patricia_j','hash004','2019-04-05','active',320000.00,'2019-04-05 12:00:00',NULL),
(5,301005,'EMP-MGT-005',1,1,'John','Michael','Brown','Marketing Director','+254711001005','john.b@auction.co.ke','john_b','hash005','2019-05-12','active',310000.00,'2019-05-12 13:00:00',NULL),
(6,301006,'EMP-AUC-001',2,2,'Mary','Elizabeth','Davis','Auction Director','+254711001006','mary.d@auction.co.ke','mary_d','hash006','2019-06-18','active',290000.00,'2019-06-18 14:00:00',NULL),
(7,301007,'EMP-AUC-002',1,2,'Charles','John','Miller','Operations Manager','+254711001007','charles.m@auction.co.ke','charles_m','hash007','2019-07-22','active',220000.00,'2019-07-22 15:00:00','2026-05-06 10:31:57'),
(8,301008,'EMP-AUC-003',5,2,'Susan','Grace','Wilson','Chief Auctioneer','+254711001008','susan.w@auction.co.ke','susan_w','hash008','2019-08-30','active',250000.00,'2019-08-30 16:00:00',NULL),
(9,301009,'EMP-AUC-004',5,2,'Thomas','David','Moore','Senior Auctioneer','+254711001009','thomas.m@auction.co.ke','thomas_m','hash009','2019-09-14','active',180000.00,'2019-09-14 17:00:00',NULL),
(10,301010,'EMP-AUC-005',5,2,'Barbara',NULL,'Taylor','Auctioneer','+254711001010','barbara.t@auction.co.ke','barbara_t','hash010','2019-10-25','active',160000.00,'2019-10-25 18:00:00',NULL),
(11,301011,'EMP-EVL-001',3,3,'Joseph',NULL,'Anderson','Head of Evaluation','+254711001011','joseph.a@auction.co.ke','joseph_a','hash011','2020-01-15','active',240000.00,'2020-01-15 09:00:00',NULL),
(12,301012,'EMP-EVL-002',3,3,'Margaret','Rose','Thomas','Senior Evaluator','+254711001012','margaret.t@auction.co.ke','margaret_t','hash012','2020-02-10','active',190000.00,'2020-02-10 10:00:00',NULL),
(13,301013,'EMP-EVL-003',4,3,'Christopher','Paul','Jackson','Evaluator','+254711001013','christopher.j@auction.co.ke','chris_j','hash013','2020-03-05','active',150000.00,'2020-03-05 11:00:00',NULL),
(14,301014,'EMP-EVL-004',4,3,'Sarah','Jane','White','Junior Evaluator','+254711001014','sarah.w@auction.co.ke','sarah_w','hash014','2020-04-12','active',120000.00,'2020-04-12 12:00:00',NULL),
(15,301015,'EMP-EVL-005',4,3,'Daniel',NULL,'Harris','Evaluator','+254711001015','daniel.h@auction.co.ke','daniel_h','hash015','2020-05-20','active',155000.00,'2020-05-20 13:00:00',NULL),
(16,301016,'EMP-FIN-001',6,4,'Nancy',NULL,'Martin','Finance Manager','+254711001016','nancy.m@auction.co.ke','nancy_m','hash016','2020-06-15','active',220000.00,'2020-06-15 14:00:00',NULL),
(17,301017,'EMP-FIN-002',7,4,'Paul','Henry','Thompson','Payment Processing Manager','+254711001017','paul.t@auction.co.ke','paul_t','hash017','2020-07-10','active',170000.00,'2020-07-10 15:00:00',NULL),
(18,301018,'EMP-FIN-003',7,4,'Karen',NULL,'Garcia','Payment Processor','+254711001018','karen.g@auction.co.ke','karen_g','hash018','2020-08-05','active',120000.00,'2020-08-05 16:00:00',NULL),
(19,301019,'EMP-FIN-004',7,4,'Mark',NULL,'Martinez','Payment Processor','+254711001019','mark.m@auction.co.ke','mark_m','hash019','2020-09-12','active',118000.00,'2020-09-12 17:00:00',NULL),
(20,301020,'EMP-FIN-005',8,4,'Betty',NULL,'Robinson','Settlement Manager','+254711001020','betty.r@auction.co.ke','betty_r','hash020','2020-10-20','active',175000.00,'2020-10-20 18:00:00',NULL),
(21,301021,'EMP-CUS-001',9,5,'Donald',NULL,'Clark','Customer Service Manager','+254711001021','donald.c@auction.co.ke','donald_c','hash021','2020-11-10','active',150000.00,'2020-11-10 09:00:00',NULL),
(22,301022,'EMP-CUS-002',9,5,'Dorothy','Anne','Rodriguez','Senior Support Agent','+254711001022','dorothy.r@auction.co.ke','dorothy_r','hash022','2020-12-15','active',110000.00,'2020-12-15 10:00:00',NULL),
(23,301023,'EMP-CUS-003',9,5,'Steven',NULL,'Lewis','Support Agent','+254711001023','steven.l@auction.co.ke','steven_l','hash023','2021-01-20','active',90000.00,'2021-01-20 11:00:00',NULL),
(24,301024,'EMP-CUS-004',9,5,'Carol',NULL,'Lee','Support Agent','+254711001024','carol.l@auction.co.ke','carol_l','hash024','2021-02-25','active',88000.00,'2021-02-25 12:00:00',NULL),
(25,301025,'EMP-CUS-005',9,5,'Anthony',NULL,'Walker','Support Agent','+254711001025','anthony.w@auction.co.ke','anthony_w','hash025','2021-03-30','active',87000.00,'2021-03-30 13:00:00',NULL),
(26,301026,'EMP-IT-001',10,8,'Ruth',NULL,'Hall','IT Manager','+254711001026','ruth.h@auction.co.ke','ruth_h','hash026','2021-04-10','active',200000.00,'2021-04-10 14:00:00',NULL),
(27,301027,'EMP-IT-002',10,8,'Kenneth',NULL,'Allen','IT Support Specialist','+254711001027','kenneth.a@auction.co.ke','kenneth_a','hash027','2021-05-15','active',125000.00,'2021-05-15 15:00:00',NULL),
(28,301028,'EMP-IT-003',10,8,'Jessica',NULL,'Young','System Administrator','+254711001028','jessica.y@auction.co.ke','jessica_y','hash028','2021-06-20','active',140000.00,'2021-06-20 16:00:00',NULL),
(29,301029,'EMP-IT-004',10,8,'Brian',NULL,'Hernandez','Network Engineer','+254711001029','brian.h@auction.co.ke','brian_h','hash029','2021-07-25','active',135000.00,'2021-07-25 17:00:00',NULL),
(30,301030,'EMP-IT-005',10,8,'Sharon',NULL,'King','Database Administrator','+254711001030','sharon.k@auction.co.ke','sharon_k','hash030','2021-08-30','active',160000.00,'2021-08-30 18:00:00',NULL),
(31,301031,'EMP-MKT-001',1,6,'George',NULL,'Wright','Marketing Manager','+254711001031','george.w@auction.co.ke','george_w','hash031','2021-09-10','active',180000.00,'2021-09-10 09:00:00',NULL),
(32,301032,'EMP-MKT-002',1,6,'Lisa',NULL,'Scott','Marketing Specialist','+254711001032','lisa.s@auction.co.ke','lisa_s','hash032','2021-10-15','active',130000.00,'2021-10-15 10:00:00',NULL),
(33,301033,'EMP-LOG-001',1,7,'Edward',NULL,'Green','Logistics Manager','+254711001033','edward.g@auction.co.ke','edward_g','hash033','2021-11-20','active',160000.00,'2021-11-20 11:00:00',NULL),
(34,301034,'EMP-LOG-002',1,7,'Sandra',NULL,'Adams','Warehouse Supervisor','+254711001034','sandra.a@auction.co.ke','sandra_a','hash034','2021-12-25','active',120000.00,'2021-12-25 12:00:00',NULL),
(35,301035,'EMP-HR-001',1,9,'Kevin',NULL,'Baker','HR Manager','+254711001035','kevin.b@auction.co.ke','kevin_b','hash035','2022-01-10','active',175000.00,'2022-01-10 13:00:00',NULL),
(36,301036,'EMP-HR-002',1,9,'Amy',NULL,'Nelson','HR Specialist','+254711001036','amy.n@auction.co.ke','amy_n','hash036','2022-02-15','active',125000.00,'2022-02-15 14:00:00',NULL),
(37,301037,'EMP-LEG-001',1,10,'Timothy',NULL,'Carter','Legal Counsel','+254711001037','timothy.c@auction.co.ke','timothy_c','hash037','2022-03-20','active',220000.00,'2022-03-20 15:00:00',NULL),
(38,301038,'EMP-LEG-002',1,10,'Rebecca',NULL,'Mitchell','Legal Assistant','+254711001038','rebecca.m@auction.co.ke','rebecca_m','hash038','2022-04-25','active',140000.00,'2022-04-25 16:00:00',NULL),
(39,301039,'EMP-EVL-006',4,3,'Jason',NULL,'Perez','Junior Evaluator','+254711001039','jason.p@auction.co.ke','jason_p','hash039','2022-05-30','on_leave',110000.00,'2022-05-30 17:00:00',NULL),
(40,301040,'EMP-FIN-006',7,4,'Laura',NULL,'Roberts','Payment Processor','+254711001040','laura.r@auction.co.ke','laura_r','hash040','2022-06-10','active',115000.00,'2022-06-10 18:00:00',NULL),
(41,301041,'EMP-AUC-006',5,2,'Jeffrey',NULL,'Turner','Auctioneer','+254711001041','jeffrey.t@auction.co.ke','jeffrey_t','hash041','2022-07-15','active',145000.00,'2022-07-15 09:00:00',NULL),
(42,301042,'EMP-CUS-006',9,5,'Donna',NULL,'Phillips','Support Agent','+254711001042','donna.p@auction.co.ke','donna_p','hash042','2022-08-20','active',85000.00,'2022-08-20 10:00:00',NULL),
(43,301043,'EMP-IT-006',10,8,'Ryan',NULL,'Campbell','IT Technician','+254711001043','ryan.c@auction.co.ke','ryan_c','hash043','2022-09-25','suspended',95000.00,'2022-09-25 11:00:00',NULL),
(44,301044,'EMP-EVL-007',3,3,'Cynthia',NULL,'Parker','Senior Evaluator','+254711001044','cynthia.p@auction.co.ke','cynthia_p','hash044','2022-10-30','active',185000.00,'2022-10-30 12:00:00',NULL),
(45,301045,'EMP-FIN-007',8,4,'Jacob',NULL,'Evans','Settlement Officer','+254711001045','jacob.e@auction.co.ke','jacob_e','hash045','2022-11-10','active',125000.00,'2022-11-10 13:00:00',NULL),
(46,301046,'EMP-MGT-006',1,1,'Gary',NULL,'Edwards','Strategy Director','+254711001046','gary.e@auction.co.ke','gary_e','hash046','2022-12-15','active',300000.00,'2022-12-15 14:00:00',NULL),
(47,301047,'EMP-AUC-007',5,2,'Shirley',NULL,'Collins','Auctioneer','+254711001047','shirley.c@auction.co.ke','shirley_c','hash047','2023-01-20','active',140000.00,'2023-01-20 15:00:00',NULL),
(48,301048,'EMP-LOG-003',1,7,'Harold',NULL,'Stewart','Shipping Coordinator','+254711001048','harold.s@auction.co.ke','harold_s','hash048','2023-02-25','active',110000.00,'2023-02-25 16:00:00',NULL),
(49,301049,'EMP-FIN-008',7,4,'Deborah',NULL,'Sanchez','Payment Processor','+254711001049','deborah.s@auction.co.ke','deborah_s','hash049','2023-03-30','terminated',105000.00,'2023-03-30 17:00:00',NULL),
(50,301050,'EMP-IT-007',1,8,'Raymoond','','Morris','IT Support','+254711001050','raymond.m@auction.co.ke','raymond_m','hash050','2023-04-10','active',115000.00,'2023-04-10 18:00:00','2026-02-09 08:55:09');
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `UID` int(11) NOT NULL AUTO_INCREMENT,
  `national_id` int(11) NOT NULL,
  `business_id` int(11) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `secondname` varchar(255) DEFAULT NULL,
  `surname` varchar(255) NOT NULL,
  `business_name` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `image_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`UID`),
  UNIQUE KEY `national_id` (`national_id`),
  UNIQUE KEY `phone_number` (`phone_number`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `business_id` (`business_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,100001,5001,24,'James','Alexander','Wilson','Wilson Enterprises','+254711111111','james@wilson.co.ke','james_wilson','$2y$10$abc123','2024-01-15 09:00:00',NULL),
(2,100002,5002,24,'Sophia','Grace','Johnson','Johnson Holdings','+254722222222','sophia@johnson.co.ke','sophia_j','$2y$10$abc124','2024-01-16 10:00:00',NULL),
(3,100003,5003,24,'Michael','David','Brown','Brown Corporation','+254733333333','michael@brown.co.ke','michael_b','$2y$10$abc125','2024-01-17 11:00:00',NULL),
(4,100004,5004,24,'Emma','Rose','Davis','Davis Group','+254744444444','emma@davis.co.ke','emma_davis','$2y$10$abc126','2024-01-18 12:00:00',NULL),
(5,100005,5005,24,'William','Thomas','Miller','Miller Industries','+254755555555','william@miller.co.ke','will_miller','$2y$10$abc127','2024-01-19 13:00:00',NULL),
(6,100006,5006,24,'Olivia','Jane','Taylor','Taylor Ltd','+254766666666','olivia@taylor.co.ke','olivia_t','$2y$10$abc128','2024-01-20 14:00:00',NULL),
(7,100007,5007,24,'Benjamin','James','Anderson','Anderson & Co','+254777777777','ben@anderson.co.ke','ben_a','$2y$10$abc129','2024-01-21 15:00:00',NULL),
(8,100008,5008,24,'Ava','Marie','Thomas','Thomas Enterprises','+254788888888','ava@thomas.co.ke','ava_thomas','$2y$10$abc130','2024-01-22 16:00:00',NULL),
(9,100009,5009,24,'Ethan','Charles','Jackson','Jackson Group','+254799999999','ethan@jackson.co.ke','ethan_j','$2y$10$abc131','2024-01-23 17:00:00',NULL),
(10,100010,5010,24,'Isabella','Kate','White','White Holdings','+254710101010','isabella@white.co.ke','bella_white','$2y$10$abc132','2024-01-24 18:00:00',NULL),
(11,100011,5011,25,'Daniel',NULL,'Harris','Harris Solutions','+254720202020','daniel@harris.co.ke','dan_h','$2y$10$abc133','2024-01-25 09:30:00',NULL),
(12,100012,5012,25,'Mia',NULL,'Martin','Martin Tech','+254730303030','mia@martin.co.ke','mia_m','$2y$10$abc134','2024-01-26 10:30:00',NULL),
(13,100013,5013,25,'Matthew','John','Thompson','Thompson Logistics','+254740404040','matt@thompson.co.ke','matt_t','$2y$10$abc135','2024-01-27 11:30:00',NULL),
(14,100014,5014,25,'Charlotte',NULL,'Garcia','Garcia Imports','+254750505050','charlotte@garcia.co.ke','char_g','$2y$10$abc136','2024-01-28 12:30:00',NULL),
(15,100015,5015,25,'Joseph',NULL,'Martinez','Martinez Exports','+254760606060','joseph@martinez.co.ke','joe_mart','$2y$10$abc137','2024-01-29 13:30:00',NULL),
(16,100016,NULL,26,'Amelia','Louise','Robinson',NULL,'+254770707070','amelia@email.com','amelia_r','$2y$10$abc138','2024-01-30 14:30:00',NULL),
(17,100017,NULL,26,'Samuel',NULL,'Clark',NULL,'+254780808080','samuel@email.com','sam_c','$2y$10$abc139','2024-01-31 15:30:00',NULL),
(18,100018,NULL,26,'Harper',NULL,'Rodriguez',NULL,'+254790909090','harper@email.com','harper_r','$2y$10$abc140','2024-02-01 16:30:00',NULL),
(19,100019,NULL,26,'David','Paul','Lewis',NULL,'+254711121212','david@email.com','david_l','$2y$10$abc141','2024-02-02 17:30:00',NULL),
(20,100020,NULL,26,'Evelyn','Grace','Lee',NULL,'+254722232323','evelyn@email.com','evelyn_l','$2y$10$abc142','2024-02-03 18:30:00',NULL),
(21,100021,5021,21,'Alexander','William','Walker','Walker Antiques','+254733343434','alex@walker.co.ke','alex_w','$2y$10$abc143','2024-02-04 09:00:00',NULL),
(22,100022,5022,21,'Abigail',NULL,'Hall','Hall Galleries','+254744454545','abigail@hall.co.ke','abi_hall','$2y$10$abc144','2024-02-05 10:00:00',NULL),
(23,100023,5023,21,'Henry',NULL,'Allen','Allen Art House','+254755565656','henry@allen.co.ke','henry_a','$2y$10$abc145','2024-02-06 11:00:00',NULL),
(24,100024,5024,21,'Emily','Anne','Young','Young Collectibles','+254766676767','emily@young.co.ke','emily_y','$2y$10$abc146','2024-02-07 12:00:00',NULL),
(25,100025,5025,21,'Jackson',NULL,'King','King Jewelers','+254777686868','jackson@king.co.ke','jack_k','$2y$10$abc147','2024-02-08 13:00:00',NULL),
(26,100026,5026,22,'Lucas',NULL,'Wright','Wright Motors','+254788696969','lucas@wright.co.ke','lucas_w','$2y$10$abc148','2024-02-09 14:00:00',NULL),
(27,100027,5027,22,'Elizabeth','Rose','Scott','Scott Furniture','+254799707070','elizabeth@scott.co.ke','liz_scott','$2y$10$abc149','2024-02-10 15:00:00',NULL),
(28,100028,5028,22,'Sebastian',NULL,'Green','Green Electronics','+254710717171','seb@green.co.ke','seb_g','$2y$10$abc150','2024-02-11 16:00:00',NULL),
(29,100029,5029,22,'Grace',NULL,'Baker','Baker Estates','+254721727272','grace@baker.co.ke','grace_b','$2y$10$abc151','2024-02-12 17:00:00',NULL),
(30,100030,5030,22,'Jack','Henry','Adams','Adams Wines','+254732737373','jack@adams.co.ke','jack_adams','$2y$10$abc152','2024-02-13 18:00:00',NULL),
(31,100031,NULL,23,'Victoria',NULL,'Nelson',NULL,'+254743747474','victoria@email.com','vic_n','$2y$10$abc153','2024-02-14 09:30:00',NULL),
(32,100032,NULL,23,'Ryan',NULL,'Carter',NULL,'+254754757575','ryan@email.com','ryan_c','$2y$10$abc154','2024-02-15 10:30:00',NULL),
(33,100033,NULL,23,'Madison',NULL,'Mitchell',NULL,'+254765767676','madison@email.com','maddy_m','$2y$10$abc155','2024-02-16 11:30:00',NULL),
(34,100034,NULL,23,'Nathan',NULL,'Perez',NULL,'+254776777777','nathan@email.com','nate_p','$2y$10$abc156','2024-02-17 12:30:00',NULL),
(35,100035,NULL,23,'Chloe',NULL,'Roberts',NULL,'+254787787878','chloe@email.com','chloe_r','$2y$10$abc157','2024-02-18 13:30:00',NULL),
(36,100036,NULL,23,'Caleb',NULL,'Turner',NULL,'+254798797979','caleb@email.com','caleb_t','$2y$10$abc158','2024-02-19 14:30:00',NULL),
(37,100037,NULL,23,'Lily',NULL,'Phillips',NULL,'+254709808080','lily@email.com','lily_p','$2y$10$abc159','2024-02-20 15:30:00',NULL),
(38,100038,NULL,23,'Andrew',NULL,'Campbell',NULL,'+254710818181','andrew@email.com','andrew_c','$2y$10$abc160','2024-02-21 16:30:00',NULL),
(39,100039,NULL,23,'Zoe',NULL,'Parker',NULL,'+254721828282','zoe@email.com','zoe_p','$2y$10$abc161','2024-02-22 17:30:00',NULL),
(40,100040,NULL,23,'Dylan',NULL,'Evans',NULL,'+254732838383','dylan@email.com','dylan_e','$2y$10$abc162','2024-02-23 18:30:00',NULL),
(41,100041,5041,25,'Gabriel',NULL,'Edwards','Edwards Trading','+254743848484','gabriel@edwards.co.ke','gab_edwards','$2y$10$abc163','2024-02-24 09:00:00',NULL),
(42,100042,5042,25,'Penelope',NULL,'Collins','Collins Designs','+254754858585','penny@collins.co.ke','penny_c','$2y$10$abc164','2024-02-25 10:00:00',NULL),
(43,100043,5043,25,'Isaac',NULL,'Stewart','Stewart Solutions','+254765868686','isaac@stewart.co.ke','isaac_s','$2y$10$abc165','2024-02-26 11:00:00',NULL),
(44,100044,5044,25,'Hannah',NULL,'Sanchez','Sanchez Imports','+254776878787','hannah@sanchez.co.ke','hannah_s','$2y$10$abc166','2024-02-27 12:00:00',NULL),
(45,100045,5045,25,'Julian',NULL,'Morris','Morris Group','+254787888888','julian@morris.co.ke','julian_m','$2y$10$abc167','2024-02-28 13:00:00',NULL),
(46,100046,5046,25,'Audrey',NULL,'Rogers','Rogers Ltd','+254798898989','audrey@rogers.co.ke','audrey_r','$2y$10$abc168','2024-02-29 14:00:00',NULL),
(47,100047,5047,25,'Christopher',NULL,'Reed','Reed Enterprises','+254709909090','chris@reed.co.ke','chris_r','$2y$10$abc169','2024-03-01 15:00:00',NULL),
(48,100048,5048,25,'Stella',NULL,'Cook','Cook & Co','+254710919191','stella@cook.co.ke','stella_c','$2y$10$abc170','2024-03-02 16:00:00',NULL),
(49,100049,5049,25,'Joshua',NULL,'Morgan','Morgan Industries','+254721929292','josh@morgan.co.ke','josh_m','$2y$10$abc171','2024-03-03 17:00:00',NULL),
(50,100050,5050,25,'Layla',NULL,'Bell','Bell Holdings','+254732939393','layla@bell.co.ke','layla_b','$2y$10$abc172','2024-03-04 18:00:00',NULL),
(51,412323,NULL,1,'John','Alex','Matunda',NULL,'074325727762','jmtunda@mail.com','jmtunda','$2y$10$P7I1UT6NugYWuAp13wjqJ.7gNP8QckUBIAg9oVQSDtylpCDmubsVu','2026-01-14 12:04:58',NULL),
(52,668685,52585,1,'Abel','James','Gathitu','TELECOMAIR ENTERPRICE','+245710000002','agathi@mail.com','abel_gathitu','hash0051','2026-03-10 11:18:20',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-06 12:38:20
