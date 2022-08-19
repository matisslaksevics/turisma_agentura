-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 19, 2022 at 07:50 PM
-- Server version: 8.0.27
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `turisma_agentura`

--

-- --------------------------------------------------------

--
-- Table structure for table `celojumi`
--

CREATE TABLE IF NOT EXISTS `celojumi` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Nosaukums` varchar(64) NOT NULL,
  `SakumaDiena` date NOT NULL,
  `BeiguDiena` date NOT NULL,
  `Kompanija` varchar(64) NOT NULL,
  `Transports` varchar(64) NOT NULL,
  `Valsts` varchar(64) NOT NULL,
  `Pilseta` varchar(64) NOT NULL,
  `Cena1per` float NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Kompanija` (`Kompanija`),
  KEY `Pilseta` (`Pilseta`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf16;

--
-- Dumping data for table `celojumi`
--

INSERT INTO `celojumi` (`ID`, `Nosaukums`, `SakumaDiena`, `BeiguDiena`, `Kompanija`, `Transports`, `Valsts`, `Pilseta`, `Cena1per`) VALUES
(36, 'Autobus uz Rigu', '2022-06-14', '2022-06-29', 'RīgasSatiksme', 'Autobuss', 'Latvija', 'Rīga', 5.99),
(37, 'Vilciens uz Vilnu', '2022-06-20', '2022-07-08', 'PasažieruVilciens', 'Vilciens', 'Lietuva', 'Viļņa', 10.49),
(38, 'Lidmasina uz Varsavu', '2022-06-06', '2022-07-09', 'airBaltic', 'Lidmasina', 'Polija', 'Varsava', 35),
(39, 'Kugis uz Helsinki', '2022-06-21', '2022-07-01', 'Tallink', 'Kugis', 'Somija', 'Helsinki', 20.99),
(40, 'Masina uz Tallinu', '2022-06-20', '2022-06-25', 'Bolt', 'Masina', 'Igaunija', 'Tallina', 11);

-- --------------------------------------------------------

--
-- Table structure for table `ieperk`
--

CREATE TABLE IF NOT EXISTS `ieperk` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Cena` float NOT NULL,
  `PersonuSkaits` int NOT NULL,
  `Celojums` int NOT NULL,
  `Lietotajs` int NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `celojumss` (`Celojums`),
  KEY `lietotajss` (`Lietotajs`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf16;

--
-- Dumping data for table `ieperk`
--

INSERT INTO `ieperk` (`ID`, `Cena`, `PersonuSkaits`, `Celojums`, `Lietotajs`) VALUES
(16, 2.99, 1, 35, 22),
(17, 31.47, 3, 37, 22),
(18, 5.99, 1, 36, 22),
(19, 140, 4, 38, 28),
(20, 20.99, 1, 39, 28),
(21, 11, 1, 40, 28);

-- --------------------------------------------------------

--
-- Table structure for table `kompanijas`
--

CREATE TABLE IF NOT EXISTS `kompanijas` (
  `Nosaukums1` varchar(64) CHARACTER SET utf16 COLLATE utf16_general_ci NOT NULL,
  `Transports` int NOT NULL,
  PRIMARY KEY (`Nosaukums1`),
  KEY `Transports` (`Transports`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `kompanijas`
--

INSERT INTO `kompanijas` (`Nosaukums1`, `Transports`) VALUES
('RīgasSatiksme', 1),
('PasažieruVilciens', 2),
('airBaltic', 3),
('Tallink', 4),
('Bolt', 5);

-- --------------------------------------------------------

--
-- Table structure for table `lietotaji`
--

CREATE TABLE IF NOT EXISTS `lietotaji` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Vards` varchar(32) NOT NULL,
  `Uzvards` varchar(32) NOT NULL,
  `Epasts` varchar(64) NOT NULL,
  `Telefona_numurs` int NOT NULL,
  `Parole` varchar(255) NOT NULL,
  `Loma` varchar(32) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf16;

--
-- Dumping data for table `lietotaji`
--

INSERT INTO `lietotaji` (`ID`, `Vards`, `Uzvards`, `Epasts`, `Telefona_numurs`, `Parole`, `Loma`) VALUES
(22, 'admin2', 'admin', 'client@gmail.com', 2222222, '$2y$10$teoEko58gO1UUaHwXzAGNOqAazvuGgVmjT.KWjZ0QIlWhFXTmiha6', 'admin'),
(28, 'karlis', 'berzins', 'karlis.berzins@gmail.com', 22422423, '$2y$10$6allgDyrmMCiEqAqw1T.ueqduR7Dw75vrq8dGFBETOL5DjEpP06QW', 'client'),
(29, 'janis', 'zibsnis', 'janis.zibsnis@inbox.lv', 22922923, '$2y$10$0ntCrUafGCSCplyhIBPCEunOiaJYZur5V9vpPiJOMajBMEV4IEFt2', 'client'),
(30, 'peteris', 'malle', 'peteris.malle@hotmail.com', 29429422, '$2y$10$SsCTHBM274ypmcKkER59KeaqrzOD52PtKvfThqDk80cmyooNHQMsC', 'client');

-- --------------------------------------------------------

--
-- Table structure for table `pilsetas`
--

CREATE TABLE IF NOT EXISTS `pilsetas` (
  `Nosaukums` varchar(64) NOT NULL,
  `Valsts` int NOT NULL,
  PRIMARY KEY (`Nosaukums`),
  KEY `Valsts` (`Valsts`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `pilsetas`
--

INSERT INTO `pilsetas` (`Nosaukums`, `Valsts`) VALUES
('Rīga', 1),
('Viļņa', 2),
('Tallina', 3),
('Varsava', 4),
('Helsinki', 5);

-- --------------------------------------------------------

--
-- Table structure for table `transporti`
--

CREATE TABLE IF NOT EXISTS `transporti` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Tips` varchar(64) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf16;

--
-- Dumping data for table `transporti`
--

INSERT INTO `transporti` (`ID`, `Tips`) VALUES
(1, 'Autobuss'),
(2, 'Vilciens'),
(3, 'Lidmasina'),
(4, 'Kugis'),
(5, 'Masina');

-- --------------------------------------------------------

--
-- Table structure for table `valstis`
--

CREATE TABLE IF NOT EXISTS `valstis` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Nosaukums` varchar(64) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf16;

--
-- Dumping data for table `valstis`
--

INSERT INTO `valstis` (`ID`, `Nosaukums`) VALUES
(1, 'Latvija'),
(2, 'Lietuva'),
(3, 'Igaunija'),
(4, 'Polija'),
(5, 'Somija');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `celojumi`
--
ALTER TABLE `celojumi`
  ADD CONSTRAINT `celojumi_ibfk_2` FOREIGN KEY (`Kompanija`) REFERENCES `kompanijas` (`Nosaukums1`),
  ADD CONSTRAINT `celojumi_ibfk_3` FOREIGN KEY (`Pilseta`) REFERENCES `pilsetas` (`Nosaukums`);

--
-- Constraints for table `kompanijas`
--
ALTER TABLE `kompanijas`
  ADD CONSTRAINT `kompanijas_ibfk_1` FOREIGN KEY (`Transports`) REFERENCES `transporti` (`ID`);

--
-- Constraints for table `pilsetas`
--
ALTER TABLE `pilsetas`
  ADD CONSTRAINT `pilsetas_ibfk_1` FOREIGN KEY (`Valsts`) REFERENCES `valstis` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
