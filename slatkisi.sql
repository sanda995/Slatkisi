-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 13, 2019 at 10:55 AM
-- Server version: 5.7.24
-- PHP Version: 7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `slatkisi`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategorija`
--

DROP TABLE IF EXISTS `kategorija`;
CREATE TABLE IF NOT EXISTS `kategorija` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Naziv` text NOT NULL,
  `Stranica` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `kategorija`
--

INSERT INTO `kategorija` (`ID`, `Naziv`, `Stranica`) VALUES
(39, 'Torte', 'torte.php'),
(84, 'KolaÄi', 'kolaci.php'),
(87, 'Pite', 'pite.php');

-- --------------------------------------------------------

--
-- Table structure for table `komentari`
--

DROP TABLE IF EXISTS `komentari`;
CREATE TABLE IF NOT EXISTS `komentari` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `korisnik_ID` int(10) UNSIGNED NOT NULL,
  `recept_ID` int(10) UNSIGNED NOT NULL,
  `komentar` text NOT NULL,
  `datum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `komentari_recept_fk` (`recept_ID`),
  KEY `komentari_korisnik_fk` (`korisnik_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `komentari`
--

INSERT INTO `komentari` (`ID`, `korisnik_ID`, `recept_ID`, `komentar`, `datum`) VALUES
(5, 13, 18, 'Probala sam ih, odliÄni su i baÅ¡ se brzo prave :)', '2019-02-12 17:41:32'),
(6, 13, 17, 'BaÅ¡ dobro izgleda, moram probati :)', '2019-02-12 17:42:22'),
(7, 17, 17, 'Ja sam pravila, vredi probati!', '2019-02-12 17:44:50'),
(8, 16, 19, 'Sa galetama nema greÅ¡ke :D', '2019-02-12 17:46:48'),
(9, 18, 18, 'Upravo se peku :)', '2019-02-12 17:47:47'),
(10, 22, 16, 'Poslastica iz detinjstva', '2019-02-12 17:50:48'),
(11, 13, 11, 'OboÅ¾avam smokve, ovaj recept je pun pogodak! :)', '2019-02-12 22:19:01'),
(12, 13, 10, 'Vrhunska je!', '2019-02-12 22:20:02'),
(13, 14, 5, 'BaÅ¡ dobro deluje. Trebalo bi da se proba. :D', '2019-02-12 22:21:18'),
(14, 14, 11, 'I ja isto ;)', '2019-02-12 22:22:05'),
(15, 14, 14, 'KolaÄ bez konkurencije', '2019-02-12 22:22:37'),
(16, 15, 14, 'SlaÅ¾em se', '2019-02-12 22:25:30'),
(17, 16, 12, 'Moja deca ih oboÅ¾avaju ', '2019-02-12 22:27:02'),
(18, 18, 12, 'I moja :)', '2019-02-12 22:31:55'),
(19, 21, 6, 'Kraljica kolaÄa :)', '2019-02-12 22:39:54');

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

DROP TABLE IF EXISTS `korisnik`;
CREATE TABLE IF NOT EXISTS `korisnik` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `lozinka` text NOT NULL,
  `mejl` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `korisnik`
--

INSERT INTO `korisnik` (`ID`, `username`, `lozinka`, `mejl`) VALUES
(13, 'sanda995', '12345', 'baljosevicsanda@gmail.com'),
(14, 'mandarina', 'mandarina', 'jmarinkovic@gmail.com'),
(15, 'marko.nenadovic', 'markomarko', 'marko.nenad@gmail.com'),
(16, 'vanja72', 'vanj72', 'vanjanjanja@gmail.com'),
(17, 'viki965', 'viki965', 'violetabaljosevic@gmail.com'),
(18, 'kuvarica', 'kuvarica', 'ivanaaleksic@gmail.com'),
(19, 'nebojsa.art', 'nebojsa.art', 'nesa.stevan@gmail.com'),
(20, 'mafin07', 'mafin07', 'petra.mafin@hotmail.com'),
(21, 'boka_so', 'boka_so', 'bokamarkovic@hotmail.com'),
(22, 'goranbg', 'goranbg', 'ivanovici@hotmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `kor_info`
--

DROP TABLE IF EXISTS `kor_info`;
CREATE TABLE IF NOT EXISTS `kor_info` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `korisnik_ID` int(10) UNSIGNED NOT NULL,
  `ime` text NOT NULL,
  `prezime` text NOT NULL,
  `grad` text,
  `datum_rodjenja` date DEFAULT NULL,
  `datum_registracije` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `slika` text,
  `dodatno` text,
  PRIMARY KEY (`ID`),
  KEY `korisnik_fk` (`korisnik_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `kor_info`
--

INSERT INTO `kor_info` (`ID`, `korisnik_ID`, `ime`, `prezime`, `grad`, `datum_rodjenja`, `datum_registracije`, `slika`, `dodatno`) VALUES
(6, 13, 'Sanda', 'BaljoÅ¡eviÄ‡', 'Å abac', '1995-02-14', '2019-02-11 21:49:41', './users/20190211214941sanda.jpg', 'Studentkinja sam MatematiÄkog fakulteta u Beogradu. U slobodno vreme volim da kuvam. :)'),
(7, 14, 'Jelena', 'MarinkoviÄ‡', 'Beograd', '1988-05-23', '2019-02-11 21:52:41', './users/20190211215241manda.jpg', 'Profesionalna sam kuvarica i uÅ¾ivam u svom poslu. '),
(8, 15, 'Marko', 'NenadoviÄ‡', 'Novi Sad', '1982-01-30', '2019-02-11 21:53:56', './users/unknown.png', ''),
(9, 16, 'Valentina', 'PavloviÄ‡', 'Kraljevo', '1972-04-21', '2019-02-11 21:56:29', './users/20190211215629kuvar.jpg', 'OboÅ¾avam da kuvam za svoju porodicu i prijatelje!'),
(10, 17, 'Violeta', 'BaljoÅ¡eviÄ‡', 'Å abac', '1965-12-30', '2019-02-11 21:58:11', './users/20190211215811ljubi.jpg', ''),
(11, 18, 'Ivana', 'VuÄetiÄ‡', 'Å abac', '1995-12-08', '2019-02-11 21:59:30', './users/unknown.png', ''),
(12, 19, 'NebojÅ¡a', 'StevanoviÄ‡', 'Velika Plana', '1964-08-13', '2019-02-11 22:02:46', './users/20190211220246umetn.jpg', 'Zdravo svima! Volim umetnost i kuvanje, druÅ¾eljubiv sam i nadam se da Ä‡emo razmeniti puno kvalitetnih recepata i korisnih saveta. :)'),
(13, 20, 'Petra', 'NikoliÄ‡', 'ZajeÄar', '1977-02-02', '2019-02-11 22:04:56', './users/20190211220456mafin.jpg', 'Ä†ao, ljubitelji poslastica :)'),
(14, 21, 'Bojana', 'Markovic', 'Sombor', '1988-03-03', '2019-02-11 22:10:25', './users/unknown.png', ''),
(15, 22, 'Goran', 'IvanoviÄ‡', 'Beograd', '1968-05-23', '2019-02-11 22:12:04', './users/unknown.png', 'Profesionalni sam kuvar, a najviÅ¡e volim da spremam poslastice. ');

-- --------------------------------------------------------

--
-- Table structure for table `lajkovi`
--

DROP TABLE IF EXISTS `lajkovi`;
CREATE TABLE IF NOT EXISTS `lajkovi` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `korisnik_ID` int(10) UNSIGNED NOT NULL,
  `recept_ID` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `lajkovi_korisnik_fk` (`korisnik_ID`),
  KEY `lajkovi_recept_fk` (`recept_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lajkovi`
--

INSERT INTO `lajkovi` (`ID`, `korisnik_ID`, `recept_ID`) VALUES
(26, 13, 19),
(27, 13, 17),
(28, 13, 16),
(29, 13, 15),
(30, 14, 17),
(31, 14, 18),
(32, 16, 17),
(33, 16, 19),
(34, 18, 18),
(35, 18, 15),
(36, 15, 17),
(37, 15, 19),
(38, 15, 16),
(39, 19, 17),
(40, 19, 15),
(41, 22, 17),
(42, 22, 18),
(43, 22, 16),
(45, 21, 15),
(46, 21, 16),
(47, 13, 18),
(48, 13, 11),
(49, 13, 14),
(50, 13, 10),
(51, 14, 4),
(52, 14, 14),
(53, 14, 15),
(54, 14, 6),
(55, 14, 7),
(57, 14, 13),
(58, 15, 4),
(59, 15, 13),
(60, 15, 14),
(61, 15, 18),
(62, 15, 9),
(63, 16, 5),
(64, 16, 11),
(65, 16, 14),
(66, 16, 12),
(67, 16, 10),
(68, 16, 8),
(69, 17, 4),
(70, 17, 7),
(71, 17, 14),
(72, 17, 10),
(73, 17, 17),
(74, 18, 4),
(75, 18, 7),
(76, 18, 14),
(77, 18, 10),
(78, 18, 17),
(79, 22, 4),
(80, 22, 7),
(81, 22, 12),
(82, 22, 14),
(83, 22, 10),
(84, 21, 5),
(85, 21, 9),
(86, 21, 14),
(87, 21, 6),
(88, 21, 10),
(89, 21, 17);

-- --------------------------------------------------------

--
-- Table structure for table `recept`
--

DROP TABLE IF EXISTS `recept`;
CREATE TABLE IF NOT EXISTS `recept` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kategorija_ID` int(10) UNSIGNED NOT NULL,
  `korisnik_ID` int(10) UNSIGNED NOT NULL,
  `recept` text NOT NULL,
  `Naziv` text NOT NULL,
  `Slika` text NOT NULL,
  `Datum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `recept_korisnik_fk` (`korisnik_ID`),
  KEY `recept_kategorije_fk` (`kategorija_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recept`
--

INSERT INTO `recept` (`ID`, `kategorija_ID`, `korisnik_ID`, `recept`, `Naziv`, `Slika`, `Datum`) VALUES
(4, 39, 13, './recepti/20190212145429recept13.php', 'ÄŒokoladna torta', './recepti/20190212145429coko..13.PNG', '2019-02-12 14:54:29'),
(5, 39, 13, './recepti/20190212150438recept13.php', 'Kraljica Äokolade', './recepti/20190212150438kralj.13.PNG', '2019-02-12 15:04:38'),
(6, 84, 13, './recepti/20190212151026recept13.php', 'Bajadere', './recepti/20190212151026bajad.13.jpg', '2019-02-12 15:10:26'),
(7, 84, 13, './recepti/20190212151903recept13.php', 'Kokos rolat', './recepti/20190212151903kokos.13.PNG', '2019-02-12 15:19:04'),
(8, 87, 13, './recepti/20190212152633recept13.php', 'Pita sa jabukama i suvim groÅ¾Ä‘em', './recepti/20190212152633pitaj.13.PNG', '2019-02-12 15:26:33'),
(9, 84, 14, './recepti/20190212154002recept14.php', 'Bele kuglice', './recepti/20190212154002kugli.14.PNG', '2019-02-12 15:40:02'),
(10, 87, 14, './recepti/20190212154706recept14.php', 'Arapska pita', './recepti/20190212154706araps.14.PNG', '2019-02-12 15:47:06'),
(11, 84, 14, './recepti/20190212155411recept14.php', 'Jednostavne kuglice', './recepti/20190212155411bombi.14.PNG', '2019-02-12 15:54:12'),
(12, 84, 18, './recepti/20190212165105recept18.php', 'Puslice', './recepti/20190212165104pusli.18.PNG', '2019-02-12 16:51:05'),
(13, 84, 18, './recepti/20190212165523recept18.php', 'ÄŒoko mafini', './recepti/20190212165523mafin.18.PNG', '2019-02-12 16:55:23'),
(14, 84, 15, './recepti/20190212170154recept15.php', 'Rafaelo', './recepti/20190212170154rafae.15.PNG', '2019-02-12 17:01:55'),
(15, 84, 17, './recepti/20190212170923recept17.php', 'Banana mafini', './recepti/20190212170923banan.17.PNG', '2019-02-12 17:09:23'),
(16, 87, 16, './recepti/20190212171632recept16.php', 'Pita sa viÅ¡njama', './recepti/20190212171632visnj.16.PNG', '2019-02-12 17:16:32'),
(17, 87, 19, './recepti/20190212172410recept19.php', 'Pita sa kajsijama', './recepti/20190212172410kajsi.19.PNG', '2019-02-12 17:24:10'),
(18, 84, 20, './recepti/20190212172915recept20.php', 'Mafini sa borovnicama', './recepti/20190212172915borov.20.PNG', '2019-02-12 17:29:15'),
(19, 84, 21, './recepti/20190212173320recept21.php', 'Bakin kolaÄ', './recepti/20190212173320galet.21.PNG', '2019-02-12 17:33:21');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `komentari`
--
ALTER TABLE `komentari`
  ADD CONSTRAINT `komentari_korisnik_fk` FOREIGN KEY (`korisnik_ID`) REFERENCES `korisnik` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `komentari_recept_fk` FOREIGN KEY (`recept_ID`) REFERENCES `recept` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kor_info`
--
ALTER TABLE `kor_info`
  ADD CONSTRAINT `korisnik_fk` FOREIGN KEY (`korisnik_ID`) REFERENCES `korisnik` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lajkovi`
--
ALTER TABLE `lajkovi`
  ADD CONSTRAINT `lajkovi_korisnik_fk` FOREIGN KEY (`korisnik_ID`) REFERENCES `korisnik` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lajkovi_recept_fk` FOREIGN KEY (`recept_ID`) REFERENCES `recept` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recept`
--
ALTER TABLE `recept`
  ADD CONSTRAINT `recept_kategorije_fk` FOREIGN KEY (`kategorija_ID`) REFERENCES `kategorija` (`ID`),
  ADD CONSTRAINT `recept_korisnik_fk` FOREIGN KEY (`korisnik_ID`) REFERENCES `korisnik` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
