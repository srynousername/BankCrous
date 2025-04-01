-- MySQL dump 10.13  Distrib 8.0.41, for Linux (x86_64)
--
-- Host: localhost    Database: bankcrous
-- ------------------------------------------------------
-- Server version	8.0.41-0ubuntu0.24.04.1

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
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `idUtilisateur` int NOT NULL,
  PRIMARY KEY (`idUtilisateur`),
  CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (3),(4);
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `autoriser`
--

DROP TABLE IF EXISTS `autoriser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `autoriser` (
  `idUtilisateur` int NOT NULL,
  `idUtilisateur_1` int NOT NULL,
  `idUtilisateur_2` int NOT NULL,
  `autorisation` tinyint(1) DEFAULT NULL,
  `typeGestion` char(3) DEFAULT NULL,
  PRIMARY KEY (`idUtilisateur`,`idUtilisateur_1`,`idUtilisateur_2`),
  KEY `idUtilisateur_1` (`idUtilisateur_1`),
  KEY `idUtilisateur_2` (`idUtilisateur_2`),
  CONSTRAINT `autoriser_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `client` (`idUtilisateur`),
  CONSTRAINT `autoriser_ibfk_2` FOREIGN KEY (`idUtilisateur_1`) REFERENCES `admin` (`idUtilisateur`),
  CONSTRAINT `autoriser_ibfk_3` FOREIGN KEY (`idUtilisateur_2`) REFERENCES `productowner` (`idUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `autoriser`
--

LOCK TABLES `autoriser` WRITE;
/*!40000 ALTER TABLE `autoriser` DISABLE KEYS */;
INSERT INTO `autoriser` VALUES (1,3,5,1,'G01'),(2,4,5,1,'G02');
/*!40000 ALTER TABLE `autoriser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client` (
  `idUtilisateur` int NOT NULL,
  `numSiren` char(9) NOT NULL,
  `raisonSociale` char(20) DEFAULT NULL,
  PRIMARY KEY (`idUtilisateur`),
  CONSTRAINT `client_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` VALUES (1,'012345678','User1'),(2,'123456789','User2');
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `impaye`
--

DROP TABLE IF EXISTS `impaye`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `impaye` (
  `idTransaction` int NOT NULL,
  `numImpaye` char(5) DEFAULT NULL,
  `libelleImpaye` char(20) DEFAULT NULL,
  PRIMARY KEY (`idTransaction`),
  CONSTRAINT `impaye_ibfk_1` FOREIGN KEY (`idTransaction`) REFERENCES `transaction` (`idTransaction`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `impaye`
--

LOCK TABLES `impaye` WRITE;
/*!40000 ALTER TABLE `impaye` DISABLE KEYS */;
INSERT INTO `impaye` VALUES (16,'IMP01','Facture non payée'),(17,'IMP02','Retard de paiement'),(18,'IMP03','Facture contestée'),(19,'IMP04','Paiement incomplet'),(20,'IMP05','Facture non reçue'),(21,'IMP06','Retard de paiement'),(22,'IMP07','Facture non payée'),(23,'IMP08','Paiement incomplet'),(24,'IMP09','Facture contestée'),(25,'IMP10','Facture non reçue'),(26,'IMP11','Retard de paiement'),(27,'IMP12','Facture non payée'),(28,'IMP13','Paiement incomplet'),(29,'IMP14','Facture contestée'),(30,'IMP15','Facture non reçue');
/*!40000 ALTER TABLE `impaye` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productowner`
--

DROP TABLE IF EXISTS `productowner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productowner` (
  `idUtilisateur` int NOT NULL,
  PRIMARY KEY (`idUtilisateur`),
  CONSTRAINT `productowner_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productowner`
--

LOCK TABLES `productowner` WRITE;
/*!40000 ALTER TABLE `productowner` DISABLE KEYS */;
INSERT INTO `productowner` VALUES (5);
/*!40000 ALTER TABLE `productowner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `remise`
--

DROP TABLE IF EXISTS `remise`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `remise` (
  `numRemise` char(10) NOT NULL,
  `dateRemise` date DEFAULT NULL,
  `montantRemise` decimal(5,2) DEFAULT NULL,
  `deviseRemise` char(3) DEFAULT NULL,
  `sensRemise` char(1) DEFAULT NULL,
  PRIMARY KEY (`numRemise`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `remise`
--

LOCK TABLES `remise` WRITE;
/*!40000 ALTER TABLE `remise` DISABLE KEYS */;
INSERT INTO `remise` VALUES ('REM001','2023-01-01',100.00,'EUR','+'),('REM002','2023-02-01',150.50,'EUR','-'),('REM003','2023-03-01',75.25,'EUR','+'),('REM004','2023-04-01',50.00,'EUR','+'),('REM005','2023-05-01',60.75,'EUR','-'),('REM006','2023-06-01',80.00,'EUR','+'),('REM007','2023-07-01',35.25,'EUR','-'),('REM008','2023-08-01',120.50,'EUR','+'),('REM009','2023-09-01',40.00,'EUR','-'),('REM010','2023-10-01',70.00,'EUR','+'),('REM011','2023-11-01',55.50,'EUR','-'),('REM012','2023-12-01',95.75,'EUR','+'),('REM013','2024-01-01',25.00,'EUR','-'),('REM014','2024-02-01',110.00,'EUR','+'),('REM015','2024-03-01',30.50,'EUR','-'),('REM016','2024-04-01',85.00,'EUR','+'),('REM017','2024-05-01',45.25,'EUR','-'),('REM018','2024-06-01',100.00,'EUR','+');
/*!40000 ALTER TABLE `remise` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solde`
--

DROP TABLE IF EXISTS `solde`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solde` (
  `idSolde` int NOT NULL AUTO_INCREMENT,
  `dateSolde` date DEFAULT NULL,
  `montantTotal` decimal(5,2) DEFAULT NULL,
  `deviseSolde` char(3) DEFAULT NULL,
  `idUtilisateur` int NOT NULL,
  PRIMARY KEY (`idSolde`),
  KEY `idUtilisateur` (`idUtilisateur`),
  CONSTRAINT `solde_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `client` (`idUtilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solde`
--

LOCK TABLES `solde` WRITE;
/*!40000 ALTER TABLE `solde` DISABLE KEYS */;
INSERT INTO `solde` VALUES (1,'2023-01-01',200.00,'EUR',1),(2,'2023-02-01',300.50,'EUR',2),(3,'2023-04-01',250.00,'EUR',1),(4,'2023-04-02',350.50,'EUR',2),(5,'2023-05-01',220.00,'EUR',1),(6,'2023-05-02',320.50,'EUR',2),(7,'2023-06-01',240.00,'EUR',1),(8,'2023-06-02',340.50,'EUR',2),(9,'2023-07-01',260.00,'EUR',1),(10,'2023-07-02',360.50,'EUR',2),(11,'2023-08-01',280.00,'EUR',1),(12,'2023-08-02',380.50,'EUR',2),(13,'2023-09-01',210.00,'EUR',1),(14,'2023-09-02',310.50,'EUR',2),(15,'2023-10-01',230.00,'EUR',1),(16,'2023-10-02',330.50,'EUR',2),(17,'2023-11-01',270.00,'EUR',1);
/*!40000 ALTER TABLE `solde` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction`
--

DROP TABLE IF EXISTS `transaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaction` (
  `idTransaction` int NOT NULL AUTO_INCREMENT,
  `dateTransaction` date DEFAULT NULL,
  `montantTransaction` decimal(5,2) DEFAULT NULL,
  `deviseTransaction` char(3) DEFAULT NULL,
  `sensTransaction` char(1) DEFAULT NULL,
  `numCarte` char(16) DEFAULT NULL,
  `reseau` char(2) DEFAULT NULL,
  `numAutorisation` char(6) DEFAULT NULL,
  `numRemise` char(10) NOT NULL,
  `idUtilisateur` int NOT NULL,
  PRIMARY KEY (`idTransaction`),
  KEY `numRemise` (`numRemise`),
  KEY `idUtilisateur` (`idUtilisateur`),
  CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`numRemise`) REFERENCES `remise` (`numRemise`),
  CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`idUtilisateur`) REFERENCES `client` (`idUtilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction`
--

LOCK TABLES `transaction` WRITE;
/*!40000 ALTER TABLE `transaction` DISABLE KEYS */;
INSERT INTO `transaction` VALUES (11,'2023-01-02',50.00,'EUR','+','1234567890123456','VS','123456','REM001',1),(12,'2023-01-03',75.50,'EUR','-','9876543210987654','MC','654321','REM001',2),(13,'2023-02-02',30.25,'EUR','+','1111111111111111','VS','111111','REM002',1),(14,'2023-02-03',20.50,'EUR','-','2222222222222222','MC','222222','REM002',2),(15,'2023-03-02',90.75,'EUR','+','3333333333333333','VS','333333','REM003',1),(16,'2023-04-01',45.00,'EUR','+','4444333322221111','VS','444444','REM004',1),(17,'2023-04-02',60.75,'EUR','-','5555666677778888','MC','555555','REM004',2),(18,'2023-05-01',80.00,'EUR','+','6666555544443333','VS','666666','REM005',1),(19,'2023-05-02',35.25,'EUR','-','7777888899990000','MC','777777','REM005',2),(20,'2023-06-01',120.50,'EUR','+','8888999900001111','VS','888888','REM006',1),(21,'2023-06-02',40.00,'EUR','-','9999000011112222','MC','999999','REM006',2),(22,'2023-07-01',70.00,'EUR','+','1111222233334444','VS','111111','REM007',1),(23,'2023-07-02',55.50,'EUR','-','2222333344445555','MC','222222','REM007',2),(24,'2023-08-01',95.75,'EUR','+','3333444455556666','VS','333333','REM008',1),(25,'2023-08-02',25.00,'EUR','-','4444555566667777','MC','444444','REM008',2),(26,'2023-09-01',110.00,'EUR','+','5555666677778888','VS','555555','REM009',1),(27,'2023-09-02',30.50,'EUR','-','6666777788889999','MC','666666','REM009',2),(28,'2023-10-01',85.00,'EUR','+','7777888899990000','VS','777777','REM010',1),(29,'2023-10-02',45.25,'EUR','-','8888999900001111','MC','888888','REM010',2),(30,'2023-11-01',100.00,'EUR','+','9999000011112222','VS','999999','REM011',1);
/*!40000 ALTER TABLE `transaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utilisateur` (
  `idUtilisateur` int NOT NULL AUTO_INCREMENT,
  `login` char(10) DEFAULT NULL,
  `motDePasse` varchar(64) DEFAULT NULL,
  `typeUtilisateur` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`idUtilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateur`
--

LOCK TABLES `utilisateur` WRITE;
/*!40000 ALTER TABLE `utilisateur` DISABLE KEYS */;
INSERT INTO `utilisateur` VALUES (1,'user1','0a041b9462caa4a31bac3567e0b6e6fd9100787db2ab433d96f6d178cabfce90','client'),(2,'user2','6025d18fe48abd45168528f18a82e265dd98d421a7084aa09f61b341703901a3','client'),(3,'admin1','25f43b1486ad95a1398e3eeb3d83bc4010015fcc9bedb35b432e00298d5021f7','admin'),(4,'admin2','1c142b2d01aa34e9a36bde480645a57fd69e14155dacfab5a3f9257b77fdc8d8','admin'),(5,'po1','c1d2b5e41aafe7faf34719d8d0fc05755728a52446af41329d72f188642d95d5','productOwner');
/*!40000 ALTER TABLE `utilisateur` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-05 11:06:26
