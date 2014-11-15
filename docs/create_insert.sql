-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 24, 2014 at 04:10 PM
-- Server version: 5.6.17
-- PHP Version: 5.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `ACTEUR_REALISATEUR`
--

CREATE TABLE IF NOT EXISTS `ACTEUR_REALISATEUR` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOM` varchar(60) NOT NULL,
  `PRENOM` varchar(45) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `ACTEUR_REALISATEUR`
--

INSERT INTO `ACTEUR_REALISATEUR` (`ID`, `NOM`, `PRENOM`) VALUES
(1, 'Scorsese', 'Martin'),
(2, 'Tarantino', 'Quentin'),
(3, 'Eastwood', 'Clint'),
(4, 'Spielberg', 'Steven'),
(5, 'Allen', 'Woody'),
(6, 'Lynch', 'David'),
(7, 'Coppola', 'Sofia'),
(8, 'De Palma', 'Brian'),
(9, 'Fincher', 'David'),
(10, 'Burton', 'Tim'),
(11, 'Affleck', 'Ben'),
(12, 'Holmes', 'Katie'),
(13, 'Kidman', 'Nicole'),
(14, 'Aniston', 'Jennifer'),
(15, 'Cox', 'Courtney'),
(16, 'Depp', 'Johnny'),
(17, 'Pitt', 'Brad'),
(18, 'Di Caprio', 'Leonardo'),
(19, 'Pacino', 'Al'),
(20, 'Willis', 'Bruce'),
(21, 'De Niro', 'Robert'),
(22, 'Costner', 'Kevin'),
(23, 'L. Jackson', 'Samuel'),
(24, 'Baldwin', 'Alec');

-- --------------------------------------------------------

--
-- Table structure for table `FILM`
--

CREATE TABLE IF NOT EXISTS `FILM` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOM` varchar(75) NOT NULL,
  `DATE_FILM` datetime NOT NULL,
  `RESUME` varchar(200) NOT NULL,
  `DATE_CREATION` datetime NOT NULL,
  `REALISATEUR_ID` int(11) NOT NULL,
  `ACTEUR1` int(11) NOT NULL,
  `ACTEUR2` int(11) DEFAULT NULL,
  `ACTEUR3` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `IDX_B4D1539622BD7EF3` (`REALISATEUR_ID`),
  KEY `IDX_B4D1539624A1CBC2` (`ACTEUR1`),
  KEY `IDX_B4D15396BDA89A78` (`ACTEUR2`),
  KEY `IDX_B4D15396CAAFAAEE` (`ACTEUR3`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `FILM`
--

INSERT INTO `FILM` (`ID`, `NOM`, `DATE_FILM`, `RESUME`, `DATE_CREATION`, `REALISATEUR_ID`, `ACTEUR1`, `ACTEUR2`, `ACTEUR3`) VALUES
(1, 'Invictus', '2010-01-13 18:06:34', 'En 1994, l''élection de Nelson Mandela consacre la fin de l''Apartheid, mais l''Afrique du Sud reste une nation profondément divisée sur le plan racial et économique.', '2014-05-04 16:36:22', 3, 16, NULL, NULL),
(2, 'Slumdog Millionaire', '2009-01-14 17:43:09', 'Jamal Malik, 18 ans, orphelin vivant dans les taudis de Mumbai, est sur le point de remporter la somme colossale de 20 millions de roupies lors de la version indienne de l''émission ...', '2014-05-04 16:38:09', 1, 14, NULL, NULL),
(3, 'Casse-tête chinois', '2008-09-09 17:41:15', 'On retrouve Xavier avec Wendy, Isabelle et Martine quinze ans après l''Auberge Espagnole et dix ans après les Poupées Russes. Tout paraissait si simple ...', '2014-05-04 16:39:42', 21, 23, 9, 15),
(4, 'La Ligne verte', '2000-03-01 17:42:35', 'Paul Edgecomb, pensionnaire centenaire d''une maison de retraite, est hanté par ses souvenirs. Gardien-chef du pénitencier de Cold Mountain en 1935, ...', '2014-05-04 16:40:45', 4, 11, 13, NULL),
(5, 'Avatar', '2009-12-16 18:14:32', 'Malgré sa paralysie, Jake Sully, un ancien marine immobilisé dans un fauteuil roulant, est resté un combattant au plus profond de son être. Il est recruté ...', '2014-05-04 16:42:01', 2, 5, 10, NULL),
(6, 'Terminator', '1985-04-24 17:44:17', 'A Los Angeles en 1984, un Terminator, cyborg surgi du futur, a pour mission d''exécuter Sarah Connor, une jeune femme dont l''enfant qui va naître doit sauver l''humanité ...', '2014-05-04 16:43:23', 6, 8, NULL, NULL),
(7, 'Terminator 2 : Le jugement dernier', '1991-10-16 17:40:09', 'En 2029, après leur échec pour éliminer Sarah Connor, les robots de Skynet programment un nouveau Terminator, le T-1000, pour retourner dans le passé et éliminer ...', '2014-05-04 16:44:22', 6, 8, NULL, NULL),
(8, 'Terminator 3 : le Soulèvement des Machines', '2003-08-06 17:45:13', 'Dix ans ont passé depuis "Le Jugement dernier". Désormais âgé de 22 ans, John Connor vit dans l''ombre, sans foyer, sans travail, sans identité. Mais les ...', '2014-05-04 16:46:14', 6, 8, NULL, NULL),
(9, 'Gladiator', '2000-06-20 17:41:59', 'Le général romain Maximus est le plus fidèle soutien de l''empereur Marc Aurele, qu''il a conduit de victoire en victoire avec une bravoure et un dévouement...', '2014-05-04 16:47:12', 10, 15, NULL, NULL),
(10, 'Les Sentiers de la perdition', '2002-09-11 17:42:57', 'En 1930, deux pères : Michael Sullivan, un tueur professionnel au service de la mafia irlandaise dans le Chicago de la Dépression, et Mr. John Rooney, ...', '2014-05-04 16:48:13', 5, 11, 19, NULL),
(11, 'X-Men: Days of Future Past', '2014-05-04 17:46:19', 'Les X-Men envoient Wolverine dans le passé pour changer un évènement historique majeur, qui pourrait impacter mondialement humains et mutants ...', '2014-05-04 16:49:37', 1, 4, 8, 20),
(12, 'X-Men: Le Commencement', '2011-06-01 17:47:03', 'Avant que les mutants n''aient révélé leur existence au monde, et avant que Charles Xavier et Erik Lehnsherr ne deviennent le Professeur X et Magneto ...', '2014-05-04 16:50:32', 1, 8, 14, 20),
(13, 'X-Men Origins: Wolverine', '2009-04-29 17:45:55', 'Ce film nous fait découvrir les origines du plus rebelle des héros Marvel et son histoire avant les évènements de la trilogie X-Men...', '2014-05-04 16:51:24', 1, 8, 16, 22),
(14, 'X-Men l''affrontement final', '2006-05-24 17:45:32', 'Dans le chapitre final de la trilogie X-Men, les mutants affrontent un choix historique et leur plus grand combat... Un "traitement" leur permet ...', '2014-05-04 16:52:20', 1, 10, 5, 12),
(15, 'Qu''est-ce qu''on a fait au bon dieu ?', '2014-05-09 10:07:32', 'Claude Verneuil et sa femme Marie, un couple de la haute bourgeoisie provinciale, se sont toujours efforcés de faire preuve d''ouverture d''esprit, notamment dans l''éducation de leurs quatre filles.', '2014-05-20 21:01:47', 1, 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `LOCATION`
--

CREATE TABLE IF NOT EXISTS `LOCATION` (
  `ID_LOCATION` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_LOCATION` datetime NOT NULL,
  `DATE_RETOUR` datetime DEFAULT NULL,
  `MEDIA_ID` int(11) NOT NULL,
  `PERSONNE_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID_LOCATION`),
  KEY `IDX_98AD158714E107D9` (`MEDIA_ID`),
  KEY `IDX_98AD15871755051A` (`PERSONNE_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `LOCATION`
--

INSERT INTO `LOCATION` (`ID_LOCATION`, `DATE_LOCATION`, `DATE_RETOUR`, `MEDIA_ID`, `PERSONNE_ID`) VALUES
(1, '2014-05-04 17:08:40', NULL, 22, 5),
(2, '2014-05-04 17:08:53', '2014-05-04 17:10:40', 24, 5),
(3, '2014-05-04 17:09:09', '2014-05-24 16:09:52', 13, 2),
(4, '2014-05-04 17:09:37', '2014-05-04 17:10:38', 18, 3),
(5, '2014-05-04 17:10:23', NULL, 42, 4),
(6, '2014-05-04 17:10:34', '2014-05-04 17:10:37', 39, 4),
(7, '2014-05-24 10:09:50', '2014-05-24 10:10:01', 30, 2),
(8, '2014-05-24 10:53:25', '2014-05-24 16:04:05', 39, 2),
(9, '2014-05-24 16:04:00', '2014-05-24 16:04:07', 38, 6),
(10, '2014-05-24 16:04:40', NULL, 50, 6),
(11, '2014-05-24 16:05:17', '2014-05-24 16:05:20', 25, 2),
(12, '2014-05-24 16:08:54', '2014-05-24 16:08:59', 23, 3),
(13, '2014-05-24 16:09:16', '2014-05-24 16:09:19', 26, 5),
(14, '2014-05-24 16:09:49', NULL, 45, 1);

-- --------------------------------------------------------

--
-- Table structure for table `MEDIA`
--

CREATE TABLE IF NOT EXISTS `MEDIA` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TYPE_MEDIA` enum('DVD','BLURAY') DEFAULT NULL,
  `EST_ACTIF` tinyint(1) NOT NULL,
  `NUMERO` int(11) NOT NULL,
  `FILM_ID_FILM` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `search_idx` (`NUMERO`),
  KEY `IDX_9D7863BC445FBD41` (`FILM_ID_FILM`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

--
-- Dumping data for table `MEDIA`
--

INSERT INTO `MEDIA` (`ID`, `TYPE_MEDIA`, `EST_ACTIF`, `NUMERO`, `FILM_ID_FILM`) VALUES
(1, 'DVD', 0, 1, 5),
(2, 'BLURAY', 0, 2, 5),
(3, 'BLURAY', 0, 3, 5),
(4, 'DVD', 1, 4, 5),
(5, 'DVD', 0, 5, 3),
(6, 'BLURAY', 0, 6, 3),
(7, 'DVD', 0, 7, 3),
(8, 'DVD', 0, 8, 3),
(9, 'BLURAY', 0, 9, 9),
(10, 'DVD', 1, 10, 9),
(11, 'BLURAY', 0, 11, 9),
(12, 'DVD', 1, 12, 9),
(13, 'DVD', 1, 13, 1),
(14, 'DVD', 1, 14, 1),
(15, 'DVD', 0, 15, 4),
(16, 'DVD', 0, 16, 4),
(17, 'BLURAY', 0, 17, 4),
(18, 'DVD', 0, 18, 10),
(19, 'BLURAY', 0, 19, 10),
(20, 'BLURAY', 0, 20, 10),
(21, 'DVD', 1, 21, 6),
(22, 'BLURAY', 1, 22, 6),
(23, 'DVD', 1, 23, 7),
(24, 'DVD', 1, 24, 7),
(25, 'BLURAY', 1, 25, 7),
(26, 'BLURAY', 1, 26, 7),
(27, 'BLURAY', 1, 27, 7),
(28, 'DVD', 1, 28, 8),
(29, 'BLURAY', 1, 29, 8),
(30, 'BLURAY', 1, 30, 8),
(31, 'DVD', 1, 31, 8),
(32, 'DVD', 1, 32, 8),
(33, 'DVD', 1, 33, 14),
(34, 'DVD', 1, 34, 14),
(35, 'BLURAY', 1, 35, 14),
(36, 'BLURAY', 1, 36, 14),
(37, 'BLURAY', 1, 37, 13),
(38, 'DVD', 1, 38, 13),
(39, 'BLURAY', 1, 39, 13),
(40, 'DVD', 0, 40, 13),
(41, 'BLURAY', 1, 41, 12),
(42, 'BLURAY', 1, 42, 12),
(43, 'BLURAY', 1, 43, 12),
(44, 'DVD', 1, 44, 12),
(45, 'BLURAY', 1, 45, 1),
(46, 'DVD', 1, 46, 1),
(47, 'BLURAY', 1, 47, 9),
(48, 'DVD', 1, 48, 9),
(49, 'BLURAY', 1, 49, 2),
(50, 'DVD', 1, 50, 2),
(51, 'BLURAY', 1, 51, 9);

-- --------------------------------------------------------

--
-- Table structure for table `PERSONNE`
--

CREATE TABLE IF NOT EXISTS `PERSONNE` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PRENOM` varchar(45) NOT NULL,
  `NOM` varchar(45) NOT NULL,
  `PASSWORD` varchar(45) NOT NULL,
  `COURRIEL` varchar(100) NOT NULL,
  `ADRESSE1` varchar(100) DEFAULT NULL,
  `ADRESSE2` varchar(100) DEFAULT NULL,
  `CODE_POSTAL` varchar(5) NOT NULL,
  `VILLE` varchar(50) NOT NULL,
  `EST_ACTIF` tinyint(1) NOT NULL,
  `EST_EMPLOYE` tinyint(1) NOT NULL,
  `DATE_CREATION` datetime NOT NULL,
  `NUMERO_ADHERENT` varchar(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `courriel_UNIQUE` (`COURRIEL`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `PERSONNE`
--

INSERT INTO `PERSONNE` (`ID`, `PRENOM`, `NOM`, `PASSWORD`, `COURRIEL`, `ADRESSE1`, `ADRESSE2`, `CODE_POSTAL`, `VILLE`, `EST_ACTIF`, `EST_EMPLOYE`, `DATE_CREATION`, `NUMERO_ADHERENT`) VALUES
(1, 'Pierre', 'Martin', '$1$NOuPl5pe$pLgiRsTXFO0YIM/XHcwFb.', 'martin.pierre@free.fr', 'Champs elysées', NULL, '75000', 'Paris', 1, 1, '2014-05-04 16:59:12', '1'),
(2, 'Sophie', 'Marie', '$1$qa5n8pog$8Hn/Ke906sGTAfgjhbtXJ1', 'sophie@gmail.com', 'Boulevard de strasbourg', NULL, '83000', 'Toulon', 1, 0, '2014-05-04 17:00:06', '2'),
(3, 'Jean', 'Norbert', '$1$nYv50gZJ$FJHY/bmK..qu/PFSN7III.', 'jean@wanadoo.fr', 'Boulevard de la paix', NULL, '31000', 'Toulouse', 1, 0, '2014-05-04 17:00:58', '3'),
(4, 'Videoclub', 'Administrateur', '$1$76cfMAR5$fSH4hBlRIY/bJS6tT0nau0', 'admin@video.club', '35eme Avenue', NULL, 'HC345', 'Hong-Kong', 1, 1, '2014-05-04 17:01:41', '4'),
(5, 'Fabrice', 'Rolland', '$1$3pODhHd3$/9YTHVtUryHhI6PkxOZxX/', 'fabrice@yahoo.fr', 'Allee des cerisiers', NULL, '65000', 'Tarbes', 1, 0, '2014-05-04 17:06:41', '5'),
(6, 'Marie-Charlotte', 'Mignonat', '$1$oel.Ujwo$TFYkJSz6OfEWNKpSoZ4jA1', 'mc.mignonat@gmail.com', '27 Avenue Gabriel Peri', NULL, '83390', 'Cuers', 1, 1, '2014-05-24 14:48:00', '6');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `FILM`
--
ALTER TABLE `FILM`
  ADD CONSTRAINT `FK_B4D1539622BD7EF3` FOREIGN KEY (`REALISATEUR_ID`) REFERENCES `ACTEUR_REALISATEUR` (`ID`),
  ADD CONSTRAINT `FK_B4D1539624A1CBC2` FOREIGN KEY (`ACTEUR1`) REFERENCES `ACTEUR_REALISATEUR` (`ID`),
  ADD CONSTRAINT `FK_B4D15396BDA89A78` FOREIGN KEY (`ACTEUR2`) REFERENCES `ACTEUR_REALISATEUR` (`ID`),
  ADD CONSTRAINT `FK_B4D15396CAAFAAEE` FOREIGN KEY (`ACTEUR3`) REFERENCES `ACTEUR_REALISATEUR` (`ID`);

--
-- Constraints for table `LOCATION`
--
ALTER TABLE `LOCATION`
  ADD CONSTRAINT `FK_98AD158714E107D9` FOREIGN KEY (`MEDIA_ID`) REFERENCES `MEDIA` (`ID`),
  ADD CONSTRAINT `FK_98AD15871755051A` FOREIGN KEY (`PERSONNE_ID`) REFERENCES `PERSONNE` (`ID`);

--
-- Constraints for table `MEDIA`
--
ALTER TABLE `MEDIA`
  ADD CONSTRAINT `FK_9D7863BC445FBD41` FOREIGN KEY (`FILM_ID_FILM`) REFERENCES `FILM` (`ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
