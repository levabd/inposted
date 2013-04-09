-- MySQL dump 10.13  Distrib 5.5.29, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: inposted
-- ------------------------------------------------------
-- Server version	5.5.29-0ubuntu0.12.10.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `AuthAssignment`
--

DROP TABLE IF EXISTS `AuthAssignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AuthAssignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` int(11) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`),
  KEY `userid` (`userid`),
  CONSTRAINT `AuthAssignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `AuthAssignment_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AuthAssignment`
--

LOCK TABLES `AuthAssignment` WRITE;
/*!40000 ALTER TABLE `AuthAssignment` DISABLE KEYS */;
INSERT INTO `AuthAssignment` VALUES ('User',24,NULL,'N;'),('User',31,NULL,'N;'),('User',34,NULL,'N;'),('User',35,NULL,'N;'),('User',36,NULL,'N;'),('User',50,NULL,'N;');
/*!40000 ALTER TABLE `AuthAssignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AuthItem`
--

DROP TABLE IF EXISTS `AuthItem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AuthItem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AuthItem`
--

LOCK TABLES `AuthItem` WRITE;
/*!40000 ALTER TABLE `AuthItem` DISABLE KEYS */;
INSERT INTO `AuthItem` VALUES ('Admin',2,'',NULL,'N;'),('User',2,'',NULL,'N;');
/*!40000 ALTER TABLE `AuthItem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AuthItemChild`
--

DROP TABLE IF EXISTS `AuthItemChild`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AuthItemChild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `AuthItemChild_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `AuthItemChild_ibfk_2` FOREIGN KEY (`child`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AuthItemChild`
--

LOCK TABLES `AuthItemChild` WRITE;
/*!40000 ALTER TABLE `AuthItemChild` DISABLE KEYS */;
/*!40000 ALTER TABLE `AuthItemChild` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Country`
--

DROP TABLE IF EXISTS `Country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Country` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Country`
--

LOCK TABLES `Country` WRITE;
/*!40000 ALTER TABLE `Country` DISABLE KEYS */;
INSERT INTO `Country` VALUES (2,'ru','Russian Federation'),(3,'ua','Ukraine'),(4,'nl','Netherlands');
/*!40000 ALTER TABLE `Country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Favorites`
--

DROP TABLE IF EXISTS `Favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Favorites` (
  `User_id` int(11) NOT NULL,
  `Post_id` int(11) NOT NULL,
  PRIMARY KEY (`User_id`,`Post_id`),
  KEY `Post_id` (`Post_id`),
  CONSTRAINT `Favorites_ibfk_1` FOREIGN KEY (`User_id`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Favorites_ibfk_2` FOREIGN KEY (`Post_id`) REFERENCES `Post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Favorites`
--

LOCK TABLES `Favorites` WRITE;
/*!40000 ALTER TABLE `Favorites` DISABLE KEYS */;
INSERT INTO `Favorites` VALUES (34,55),(34,62),(35,67);
/*!40000 ALTER TABLE `Favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Hint`
--

DROP TABLE IF EXISTS `Hint`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Hint` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Hint`
--

LOCK TABLES `Hint` WRITE;
/*!40000 ALTER TABLE `Hint` DISABLE KEYS */;
INSERT INTO `Hint` VALUES (1,'Suggestion 1'),(2,'Suggestion 2'),(3,'Hint'),(4,'Cool stuff');
/*!40000 ALTER TABLE `Hint` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Interest`
--

DROP TABLE IF EXISTS `Interest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Interest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(510) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`(255))
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Interest`
--

LOCK TABLES `Interest` WRITE;
/*!40000 ALTER TABLE `Interest` DISABLE KEYS */;
INSERT INTO `Interest` VALUES (39,'Php'),(40,'Yii'),(42,'Football'),(43,'Car'),(45,'Novelty'),(46,'Fashion'),(47,'Diy'),(48,'Movies'),(49,'Entertainment'),(50,'Philosophy'),(51,'Art'),(52,'Picture'),(53,'Auctions'),(54,'Aphorisms'),(55,'Idea'),(56,'Creative'),(57,'Clothing'),(58,'Kiev'),(59,'Beauty'),(60,'Basketball'),(61,'Console'),(62,'Chrome'),(65,'Books'),(72,'Tennis'),(73,'Baseball'),(74,'Badminton'),(75,'Health'),(76,'Secrets'),(77,'Magazine'),(78,'Incredible'),(79,'Concert'),(80,'Music'),(81,'Snowfall');
/*!40000 ALTER TABLE `Interest` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Interest_Parent`
--

DROP TABLE IF EXISTS `Interest_Parent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Interest_Parent` (
  `Interest_id` int(11) NOT NULL,
  `Parent_id` int(11) NOT NULL,
  UNIQUE KEY `Interest_id_Parent_id` (`Interest_id`,`Parent_id`),
  KEY `Parent_id` (`Parent_id`),
  CONSTRAINT `Interest_Parent_ibfk_1` FOREIGN KEY (`Interest_id`) REFERENCES `Interest` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Interest_Parent_ibfk_2` FOREIGN KEY (`Parent_id`) REFERENCES `Interest` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Interest_Parent`
--

LOCK TABLES `Interest_Parent` WRITE;
/*!40000 ALTER TABLE `Interest_Parent` DISABLE KEYS */;
INSERT INTO `Interest_Parent` VALUES (57,46);
/*!40000 ALTER TABLE `Interest_Parent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Interest_Post`
--

DROP TABLE IF EXISTS `Interest_Post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Interest_Post` (
  `Interest_id` int(11) NOT NULL,
  `Post_id` int(11) NOT NULL,
  PRIMARY KEY (`Interest_id`,`Post_id`),
  KEY `Post_id` (`Post_id`),
  CONSTRAINT `Interest_Post_ibfk_1` FOREIGN KEY (`Interest_id`) REFERENCES `Interest` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Interest_Post_ibfk_2` FOREIGN KEY (`Post_id`) REFERENCES `Post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Interest_Post`
--

LOCK TABLES `Interest_Post` WRITE;
/*!40000 ALTER TABLE `Interest_Post` DISABLE KEYS */;
INSERT INTO `Interest_Post` VALUES (43,54),(43,55),(43,56),(46,57),(46,58),(47,58),(48,59),(49,59),(50,60),(51,61),(52,61),(53,61),(50,62),(54,62),(55,62),(46,63),(56,63),(57,63),(51,64),(55,64),(58,64),(51,65),(55,65),(58,65),(48,66),(49,66),(51,66),(51,67),(59,67),(59,90),(75,90),(76,90),(59,91),(75,91),(76,91),(46,92),(56,92),(57,92),(58,93),(77,93),(77,94),(78,94),(58,95),(79,95),(80,95),(59,96),(75,96),(76,96),(46,97),(57,97),(48,98),(51,98),(51,99),(58,99),(48,100),(51,100),(58,101),(81,101),(75,102),(46,103),(57,103),(46,104),(57,104),(51,105),(58,105),(58,106);
/*!40000 ALTER TABLE `Interest_Post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Interest_User`
--

DROP TABLE IF EXISTS `Interest_User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Interest_User` (
  `Interest_id` int(11) NOT NULL,
  `User_id` int(11) NOT NULL,
  PRIMARY KEY (`Interest_id`,`User_id`),
  KEY `User_id` (`User_id`),
  CONSTRAINT `Interest_User_ibfk_1` FOREIGN KEY (`Interest_id`) REFERENCES `Interest` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Interest_User_ibfk_2` FOREIGN KEY (`User_id`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Interest_User`
--

LOCK TABLES `Interest_User` WRITE;
/*!40000 ALTER TABLE `Interest_User` DISABLE KEYS */;
INSERT INTO `Interest_User` VALUES (58,24),(77,24),(79,24),(80,24),(81,24),(43,31),(39,34),(46,34),(47,34),(50,34),(51,34),(56,34),(57,34),(48,35),(49,35),(51,35),(52,35),(53,35),(58,35),(59,35),(50,36),(57,36),(59,50),(75,50),(76,50);
/*!40000 ALTER TABLE `Interest_User` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Post`
--

DROP TABLE IF EXISTS `Post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `User_id` int(11) NOT NULL,
  `dateSubmitted` datetime NOT NULL,
  `content` varchar(500) NOT NULL,
  `htmlContent` text NOT NULL,
  `ip` varchar(15) NOT NULL,
  `moderatedUntil` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `User_id` (`User_id`),
  CONSTRAINT `Post_ibfk_1` FOREIGN KEY (`User_id`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Post`
--

LOCK TABLES `Post` WRITE;
/*!40000 ALTER TABLE `Post` DISABLE KEYS */;
INSERT INTO `Post` VALUES (54,31,'2013-03-06 12:54:12','One of the most powerful and fastest cars in the world is the SSC Ultimate Aero 6.3 V8. The car is so fast that the acceleration time from zero to sixty miles per hour takes just 2.78 seconds!\r\n','One of the most powerful and fastest cars in the world is the SSC Ultimate Aero 6.3 V8. The car is so fast that the acceleration time from zero to sixty miles per hour takes just 2.78 seconds!\r\n','77.87.43.232','0000-00-00 00:00:00'),(55,31,'2013-03-06 13:22:12','Aston Martin in honor of his 100th birthday present exclusive model CC100, which will be released in just two copies. Present new products at the Nurburgring in May. The car will be based on V12 Vantage. ','Aston Martin in honor of his 100th birthday present exclusive model CC100, which will be released in just two copies. Present new products at the Nurburgring in May. The car will be based on V12 Vantage. ','77.87.43.232','0000-00-00 00:00:00'),(56,31,'2013-03-06 13:25:18','Under the Motor Show in Geneva, the German manufacturer introduced a compact new product - a small crossover SUV or hatchback - Opel Adam Rocks. Unlike standard Adam, Rocks has more ground clearance - 15millimeters and 40millimeters extensive track.','Under the Motor Show in Geneva, the German manufacturer introduced a compact new product - a small crossover SUV or hatchback - Opel Adam Rocks. Unlike standard Adam, Rocks has more ground clearance - 15millimeters and 40millimeters extensive track.','77.87.43.232','0000-00-00 00:00:00'),(57,34,'2013-03-06 13:32:35','Fashion photographer Steven Meisel shoots beautiful model Karen Elson for Vogue Italia. Acrobats, clowns and contortionists surround the British model, while she looks stunning in outfits by Blumarine, D&amp;G, Chanel and Louis Vuitton. ','Fashion photographer Steven Meisel shoots beautiful model Karen Elson for Vogue Italia. Acrobats, clowns and contortionists surround the British model, while she looks stunning in outfits by Blumarine, D&amp;G, Chanel and Louis Vuitton. ','77.87.43.232','0000-00-00 00:00:00'),(58,34,'2013-03-06 13:36:30','Candice Swanepoel in advertising Rag &amp; Bone&#039;s DIY.Candice took a few self-portraits in jeans and T-shirts Rag &amp; Bone, walking along the beaches of Brazil and Bora Bora.\r\nhttp:// /go/a3','Candice Swanepoel in advertising Rag &amp; Bone&#039;s DIY.Candice took a few self-portraits in jeans and T-shirts Rag &amp; Bone, walking along the beaches of Brazil and Bora Bora.\r\nhttp:// <a href=\"/go/a3\">link</a>','77.87.43.232','0000-00-00 00:00:00'),(59,35,'2013-03-06 13:42:46','From 14 to 20 March in Kiev theater festival of IRISHFEST: New Cinema of Ireland.','From 14 to 20 March in Kiev theater festival of IRISHFEST: New Cinema of Ireland.','77.87.43.232','0000-00-00 00:00:00'),(60,36,'2013-03-06 13:45:41','When an artist becomes invisible, it ceases to exist. When the artist is for the dreams of others, it becomes by anyone, but not the artist.','When an artist becomes invisible, it ceases to exist. When the artist is for the dreams of others, it becomes by anyone, but not the artist.','77.87.43.232','0000-00-00 00:00:00'),(61,35,'2013-03-06 13:50:12','For only $ 840 bought the Metropolitan Museum sketch artist Death of Socrates by Jacques-Louis David in the auction.','For only $ 840 bought the Metropolitan Museum sketch artist Death of Socrates by Jacques-Louis David in the auction.','77.87.43.232','0000-00-00 00:00:00'),(62,36,'2013-03-06 13:52:37','The most valuable thing in our life is happening to us here and now. The future is as the past - as illusory not to have known us for a memory mechanisms as accurately and clearly as the human soul.','The most valuable thing in our life is happening to us here and now. The future is as the past - as illusory not to have known us for a memory mechanisms as accurately and clearly as the human soul.','77.87.43.232','0000-00-00 00:00:00'),(63,34,'2013-03-06 13:55:58','Paper couture. Collection of paper dresses Matthew Brodie for Madame Magazine Magazine. /go/a4','Paper couture. Collection of paper dresses Matthew Brodie for Madame Magazine Magazine. <a href=\"/go/a4\">link</a>','77.87.43.232','0000-00-00 00:00:00'),(64,36,'2013-03-06 14:04:31','7 September in Kiev will sensational exhibition &quot;The Human Body: see to understand.&quot; Exclusive educational project that covers all aspects of human anatomy, was established in 2005 in the U.S..','7 September in Kiev will sensational exhibition &quot;The Human Body: see to understand.&quot; Exclusive educational project that covers all aspects of human anatomy, was established in 2005 in the U.S..','77.87.43.232','0000-00-00 00:00:00'),(65,36,'2013-03-06 14:04:31','7 September in Kiev will sensational exhibition &quot;The Human Body: see to understand.&quot; Exclusive educational project that covers all aspects of human anatomy, was established in 2005 in the U.S..','7 September in Kiev will sensational exhibition &quot;The Human Body: see to understand.&quot; Exclusive educational project that covers all aspects of human anatomy, was established in 2005 in the U.S..','77.87.43.232','0000-00-00 00:00:00'),(66,35,'2013-03-06 14:07:34','January 25, 2010 to the first position in the ranking released &quot;Avatar&quot;, which on January 30, has brought the world&#039;s 1 billion 917 million dollars, pushing, so the rest of the position down.','January 25, 2010 to the first position in the ranking released &quot;Avatar&quot;, which on January 30, has brought the world&#039;s 1 billion 917 million dollars, pushing, so the rest of the position down.','77.87.43.232','0000-00-00 00:00:00'),(67,35,'2013-03-06 14:11:32','Титул самой красивой женщиной планеты достался Бейонсе, чему она в общем-то не столь уж и рада, ведь сейчас её главное счастье — малышка Блу Айви, которую молодая мама и поблагодарила за звание самой-самой.\r\n','Титул самой красивой женщиной планеты достался Бейонсе, чему она в общем-то не столь уж и рада, ведь сейчас её главное счастье — малышка Блу Айви, которую молодая мама и поблагодарила за звание самой-самой.\r\n','77.87.43.232','0000-00-00 00:00:00'),(90,50,'2013-03-28 14:55:01','Experts conducted extensive study and stated that it was the first milk superfood in the world. For the first time people started to use it about 7,500 years ago.','Experts conducted extensive study and stated that it was the first milk superfood in the world. For the first time people started to use it about 7,500 years ago.','77.87.43.232','0000-00-00 00:00:00'),(91,50,'2013-03-28 14:56:19','Regular consumption of apples prolongs life, as well as fans of the fruit is less likely to suffer lung diseases.','Regular consumption of apples prolongs life, as well as fans of the fruit is less likely to suffer lung diseases.','77.87.43.232','0000-00-00 00:00:00'),(92,34,'2013-03-28 14:58:52','Romantic 60&#039;s are back! One only has to look at the spring collections Louis Vuitton, Michael Kors and Moschino, in which designers have focused on the A-line dresses, graphic quality prints and a positive scale.','Romantic 60&#039;s are back! One only has to look at the spring collections Louis Vuitton, Michael Kors and Moschino, in which designers have focused on the A-line dresses, graphic quality prints and a positive scale.','77.87.43.232','0000-00-00 00:00:00'),(93,24,'2013-03-28 15:03:55','The first issue of the Ukrainian version of the popular Geographic magazine National Geographic. The publisher made ​​Sanoma Media Ukraine, which also publishes Esquire, Harper&#039;s Bazaar, Men `s Health, Cosmopolitan. Magazine circulation - 25 000','The first issue of the Ukrainian version of the popular Geographic magazine National Geographic. The publisher made ​​Sanoma Media Ukraine, which also publishes Esquire, Harper&#039;s Bazaar, Men `s Health, Cosmopolitan. Magazine circulation - 25 000','77.87.43.232','0000-00-00 00:00:00'),(94,36,'2013-03-28 15:07:15','21-year-old Briton Jade Packer naturally had a baby weighing 7.1 kg.\nThe mother bore the baby two weeks, although it, like the doctors did not realize how great is the weight of the newborn, writes The Daily Mail.','21-year-old Briton Jade Packer naturally had a baby weighing 7.1 kg.\nThe mother bore the baby two weeks, although it, like the doctors did not realize how great is the weight of the newborn, writes The Daily Mail.','77.87.43.232','0000-00-00 00:00:00'),(95,24,'2013-03-28 15:11:50','Win concert tickets Boombox\nEvaluation of the new album the musicians and a good time will be on March 30 in the concert hall Stereo Plaza. /go/a6','Win concert tickets Boombox\nEvaluation of the new album the musicians and a good time will be on March 30 in the concert hall Stereo Plaza. <a href=\"/go/a6\">link</a>','77.87.43.232','0000-00-00 00:00:00'),(96,50,'2013-03-28 15:14:57','Most low-calorie foods - it&#039;s vegetables: cucumbers, lettuce, cabbage, fresh cabbage, radish, eggplant, zucchini, green onions, tomatoes, asparagus, carrots, celery, spinach, bell peppers, sorrel.','Most low-calorie foods - it&#039;s vegetables: cucumbers, lettuce, cabbage, fresh cabbage, radish, eggplant, zucchini, green onions, tomatoes, asparagus, carrots, celery, spinach, bell peppers, sorrel.','77.87.43.232','0000-00-00 00:00:00'),(97,34,'2013-03-28 15:17:27','Ukrainian Fashion Games open Georgia 2013.\nApril 26 - May 2, 2013.\nThis year&#039;s session of the Ukrainian Fashion Games take place again in sunny Georgia. But first guests will meet the capital of Georgia - Tbilisi.','Ukrainian Fashion Games open Georgia 2013.\nApril 26 - May 2, 2013.\nThis year&#039;s session of the Ukrainian Fashion Games take place again in sunny Georgia. But first guests will meet the capital of Georgia - Tbilisi.','77.87.43.232','0000-00-00 00:00:00'),(98,35,'2013-03-28 15:20:37','Culturally, &quot;Cinema&quot; Kiev &quot;in the opening of the Festival&quot; The 10th French Spring in Ukraine &quot;will premiere romantic thriller&quot; Moebius &quot;with the support of the French Embassy in Ukraine','Culturally, &quot;Cinema&quot; Kiev &quot;in the opening of the Festival&quot; The 10th French Spring in Ukraine &quot;will premiere romantic thriller&quot; Moebius &quot;with the support of the French Embassy in Ukraine','77.87.43.232','0000-00-00 00:00:00'),(99,36,'2013-03-28 15:25:58','The most ardent fans of TV series will gather April 13 in free space Freud House.Marathon participants will not stop watching the series for the series, season after season, until they were overcome sleep. Most stable participant will receive a 1000$','The most ardent fans of TV series will gather April 13 in free space Freud House.Marathon participants will not stop watching the series for the series, season after season, until they were overcome sleep. Most stable participant will receive a 1000$','77.87.43.232','0000-00-00 00:00:00'),(100,35,'2013-03-28 15:31:36','All of this can be done at your nearest cinema club of our snowy city! This week gives movie-goers such films as the thriller &quot;Funny Games,&quot; drama &quot;Do not shout: Wolves!&quot; Melodrama &quot;Pay It Forward.&quot;','All of this can be done at your nearest cinema club of our snowy city! This week gives movie-goers such films as the thriller &quot;Funny Games,&quot; drama &quot;Do not shout: Wolves!&quot; Melodrama &quot;Pay It Forward.&quot;','77.87.43.232','0000-00-00 00:00:00'),(101,24,'2013-03-28 15:33:47','About snowfall in Kiev shot horror film.\nSymbolic film consists of amateur video Kiev - witness the consequences of falling snow, and the staff of Hollywood horror movies. /go/a7','About snowfall in Kiev shot horror film.\nSymbolic film consists of amateur video Kiev - witness the consequences of falling snow, and the staff of Hollywood horror movies. <a href=\"/go/a7\">link</a>','77.87.43.232','0000-00-00 00:00:00'),(102,50,'2013-03-28 15:36:36','Scientists have confirmed the obvious: reading good for the brain (&quot;Popular Science&quot;, USA). /go/a8','Scientists have confirmed the obvious: reading good for the brain (&quot;Popular Science&quot;, USA). <a href=\"/go/a8\">link</a>','77.87.43.232','0000-00-00 00:00:00'),(103,34,'2013-03-28 15:39:10','March 10 at Ukrainian Fashion Week debut young Italian-Ukrainian brand TROFYMENKO, presenting women&#039;s collection autumn-winter 2013-14. The collection My Modern Love designer inspired Paris 1950.','March 10 at Ukrainian Fashion Week debut young Italian-Ukrainian brand TROFYMENKO, presenting women&#039;s collection autumn-winter 2013-14. The collection My Modern Love designer inspired Paris 1950.','77.87.43.232','0000-00-00 00:00:00'),(104,34,'2013-03-28 15:40:48','Spring fashion in the life of the brand KARAVAY marked by careful work with national elements, which is reflected in the new collection autumn-winter 2013-14. Fashion show will be held March 29 in the renewed space store studio KARAVAY.','Spring fashion in the life of the brand KARAVAY marked by careful work with national elements, which is reflected in the new collection autumn-winter 2013-14. Fashion show will be held March 29 in the renewed space store studio KARAVAY.','77.87.43.232','0000-00-00 00:00:00'),(105,35,'2013-03-28 15:42:42','Assessing the weather conditions, taking into account the effects of the spring snow and a possible &quot;all Kiev flood&quot;, the organizers of the first capital of the flash-mob &quot;pals&quot; event was moved to April 14.','Assessing the weather conditions, taking into account the effects of the spring snow and a possible &quot;all Kiev flood&quot;, the organizers of the first capital of the flash-mob &quot;pals&quot; event was moved to April 14.','77.87.43.232','0000-00-00 00:00:00'),(106,36,'2013-03-28 15:46:29','March 30-31 The Kievan Rus Park invites you to have fun on the soul, to take part in the entertainment program Days of Slavic humor and melt the winter cold good mood. Join the exciting games and contests! You wait medieval jokes and riddles funny.','March 30-31 The Kievan Rus Park invites you to have fun on the soul, to take part in the entertainment program Days of Slavic humor and melt the winter cold good mood. Join the exciting games and contests! You wait medieval jokes and riddles funny.','77.87.43.232','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `Post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PrivateMessage`
--

DROP TABLE IF EXISTS `PrivateMessage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PrivateMessage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `User_id_from` int(11) DEFAULT NULL,
  `User_id_to` int(11) DEFAULT NULL,
  `topic` tinytext NOT NULL,
  `body` text NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `User_id_from` (`User_id_from`),
  KEY `User_id_to` (`User_id_to`),
  CONSTRAINT `PrivateMessage_ibfk_1` FOREIGN KEY (`User_id_from`) REFERENCES `User` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `PrivateMessage_ibfk_2` FOREIGN KEY (`User_id_to`) REFERENCES `User` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PrivateMessage`
--

LOCK TABLES `PrivateMessage` WRITE;
/*!40000 ALTER TABLE `PrivateMessage` DISABLE KEYS */;
/*!40000 ALTER TABLE `PrivateMessage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ShortUrl`
--

DROP TABLE IF EXISTS `ShortUrl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ShortUrl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ShortUrl`
--

LOCK TABLES `ShortUrl` WRITE;
/*!40000 ALTER TABLE `ShortUrl` DISABLE KEYS */;
INSERT INTO `ShortUrl` VALUES (1,'http://google.com'),(2,'http://habrahabr.ru'),(3,'http://www.etoday.ru/2011/11/kendis-sveynpol-v-reklame-rag.php'),(4,'http://www.kulturologia.ru/blogs/171111/15715/'),(5,'http://google.com'),(6,'http://kiev.vgorode.ua/news/165730/?005'),(7,'http://kiev.vgorode.ua/news/166198-o-snehopade-v-kyeve-snialy-fylm-uzhasov'),(8,'http://www.popsci.com/technology/article/2012-09/science-confirms-obvious-reading-literature-good-your-brain'),(9,'http://odin-moy-den.livejournal.com/1156058.html'),(10,'http://odin-moy-den.livejournal.com/1156058.htmlhttp://odin-moy-den.livejournal.com/1156058.html'),(11,'http://odin-moy-den.livejournal.com/1156058.htmlhttp://odin-moy-den.livejournal.com/1156058.htmlhttp://odin-moy-den.livejournal.com/1156058.htmlhttp://odin-moy-den.livejournal.com/1156058.htmlhttp://odin-moy-den.livejournal.com/1156058.htmlhttp://odin-moy-den.livejournal.com/1156058.htmlhttp://odin-moy-den.livejournal.com/1156058.htmlhttp://odin-moy-den.livejournal.com/1156058.htmlhttp://odin-moy-den.livejournal.com/1156058.htmlhttp://odin-moy-den.livejournal.com/1156058.htmlhttp://odin-moy-den.livejournal.com/1156058.htmlhttp://odin-moy-den.livejournal.com/1156058.html');
/*!40000 ALTER TABLE `ShortUrl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Tip`
--

DROP TABLE IF EXISTS `Tip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Tip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Tip`
--

LOCK TABLES `Tip` WRITE;
/*!40000 ALTER TABLE `Tip` DISABLE KEYS */;
/*!40000 ALTER TABLE `Tip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hashedPassword` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `timezone` float(2,1) NOT NULL DEFAULT '0.0' COMMENT 'UTC+{timezone}',
  `reputation` int(11) NOT NULL DEFAULT '0',
  `level` tinyint(4) NOT NULL DEFAULT '0',
  `homepage` varchar(1024) DEFAULT NULL,
  `Country_id` smallint(6) DEFAULT NULL,
  `info` text NOT NULL,
  `birthYear` int(10) unsigned DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `dateCreated` datetime DEFAULT NULL,
  `dateAccessed` datetime DEFAULT NULL,
  `avatar` tinyint(1) NOT NULL DEFAULT '0',
  `enabledHints` tinyint(1) NOT NULL DEFAULT '1',
  `enabledNotifications` tinyint(1) NOT NULL DEFAULT '1',
  `lastHint` int(11) DEFAULT NULL,
  `lastOnline` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nickname` (`nickname`),
  KEY `Country_id` (`Country_id`),
  CONSTRAINT `User_ibfk_1` FOREIGN KEY (`Country_id`) REFERENCES `Country` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` VALUES (24,'$5$rounds=5000$P0XFNRb2xXRg4ALo$LlUhOqwC2Fgpu0Y1D1i4UHbxUz0Q6uY/FLS67/qW3/A','qazwsx@1.com','Olga','qazwsx',0.0,0,0,'',NULL,'',NULL,0,'2013-01-29 18:52:31','2013-03-28 17:32:16',1,1,1,NULL,NULL),(31,'$5$rounds=5000$naHNtQI0JcEQDvmr$5G24DEGwC66HRroInt/BGNvOp8ANWZx5eY9G6DnFHl6','qq@1.com','Alen Delish','Alen Delish',3.5,0,0,'',2,'I love sports cars. I know almost all of them.\r\nI love to read books and listen to music.',NULL,0,'2013-03-04 10:34:33','2013-03-04 13:36:35',1,1,1,NULL,NULL),(34,'$5$rounds=5000$pn3DZQp7doxQgfkv$G7GQmOE3R/Pkb.DDWFYnK8ibdzgRqIyzn0Mk7X1FQFA','ww@1.com','Alica','Alica',0.0,0,0,'',3,'Really love fashion. Tracking all the news and wonder displays well-known brands.',NULL,0,'2013-03-06 15:26:45','2013-04-06 14:49:39',1,0,1,4,'2013-04-06 14:49:44'),(35,'$5$rounds=5000$CNmkvLWYY8pfeGo0$4fChNNu5xwDvRXUNy9cobVFMafMyQ3abwibndi0OK90','aaa@1.com','Bobin','Bob',0.0,0,0,NULL,NULL,'',NULL,0,'2013-03-06 15:37:44','2013-04-06 15:02:36',1,0,1,1,'2013-04-06 15:05:41'),(36,'$5$rounds=5000$LzU1GUwc9RgEbcPO$to3FH82jwHyGUStwR41MTZgsst5wigrwRTaeqZ24bpD','ss@1.com','Merlin Adam','Merlin',0.0,0,0,NULL,2,'',NULL,0,'2013-03-06 15:43:33','2013-04-06 15:05:29',1,0,1,1,'2013-04-06 15:05:49'),(50,'$5$rounds=5000$6Ld9pIYkUzlPQyvq$cb09mS8ufAposmx0G/veig3pg5B.DWv4UVvUNEmC/P/','rrr@1.com','Nika','Nika',0.0,0,0,'',3,'',NULL,0,'2013-03-28 16:47:43','2013-03-28 17:34:04',1,1,1,NULL,NULL);
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Vote`
--

DROP TABLE IF EXISTS `Vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `User_id` int(11) DEFAULT NULL,
  `Post_id` int(11) NOT NULL,
  `type` enum('like','spam','abuse','irrelevant','nonsense','duplicate') NOT NULL DEFAULT 'like',
  PRIMARY KEY (`id`),
  UNIQUE KEY `User_id_Post_id` (`User_id`,`Post_id`),
  KEY `Post_id` (`Post_id`),
  CONSTRAINT `Vote_ibfk_1` FOREIGN KEY (`User_id`) REFERENCES `User` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `Vote_ibfk_2` FOREIGN KEY (`Post_id`) REFERENCES `Post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Vote`
--

LOCK TABLES `Vote` WRITE;
/*!40000 ALTER TABLE `Vote` DISABLE KEYS */;
/*!40000 ALTER TABLE `Vote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_oauth`
--

DROP TABLE IF EXISTS `user_oauth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_oauth` (
  `user_id` int(11) NOT NULL,
  `provider` varchar(45) NOT NULL,
  `identifier` varchar(64) NOT NULL,
  `session_data` text,
  PRIMARY KEY (`provider`,`identifier`),
  UNIQUE KEY `unic_user_id_name` (`user_id`,`provider`),
  KEY `oauth_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_oauth`
--

LOCK TABLES `user_oauth` WRITE;
/*!40000 ALTER TABLE `user_oauth` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_oauth` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-04-09 16:04:49
