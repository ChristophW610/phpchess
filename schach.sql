-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 
-- Erstellungszeit: 
-- Server-Version: 10.4.28-MariaDB
-- PHP-Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `schach`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `brett3`
--

CREATE TABLE `brett3` (
  `bid` int(11) NOT NULL,
  `sp1` int(11) NOT NULL,
  `sp2` int(11) NOT NULL,
  `dran` int(11) NOT NULL DEFAULT 1,
  `a1` int(11) NOT NULL DEFAULT 8,
  `a2` int(11) NOT NULL DEFAULT 9,
  `a3` int(11) NOT NULL DEFAULT 10,
  `a4` int(11) NOT NULL DEFAULT 12,
  `a5` int(11) NOT NULL DEFAULT 11,
  `a6` int(11) NOT NULL DEFAULT 10,
  `a7` int(11) NOT NULL DEFAULT 9,
  `a8` int(11) NOT NULL DEFAULT 8,
  `a9` int(11) NOT NULL DEFAULT 7,
  `a10` int(11) NOT NULL DEFAULT 7,
  `a11` int(11) NOT NULL DEFAULT 7,
  `a12` int(11) NOT NULL DEFAULT 7,
  `a13` int(11) NOT NULL DEFAULT 7,
  `a14` int(11) NOT NULL DEFAULT 7,
  `a15` int(11) NOT NULL DEFAULT 7,
  `a16` int(11) NOT NULL DEFAULT 7,
  `a17` int(11) NOT NULL DEFAULT 0,
  `a18` int(11) NOT NULL DEFAULT 0,
  `a19` int(11) NOT NULL DEFAULT 0,
  `a20` int(11) NOT NULL DEFAULT 0,
  `a21` int(11) NOT NULL DEFAULT 0,
  `a22` int(11) NOT NULL DEFAULT 0,
  `a23` int(11) NOT NULL DEFAULT 0,
  `a24` int(11) NOT NULL DEFAULT 0,
  `a25` int(11) NOT NULL DEFAULT 0,
  `a26` int(11) NOT NULL DEFAULT 0,
  `a27` int(11) NOT NULL DEFAULT 0,
  `a28` int(11) NOT NULL DEFAULT 0,
  `a29` int(11) NOT NULL DEFAULT 0,
  `a30` int(11) NOT NULL DEFAULT 0,
  `a31` int(11) NOT NULL DEFAULT 0,
  `a32` int(11) NOT NULL DEFAULT 0,
  `a33` int(11) NOT NULL DEFAULT 0,
  `a34` int(11) NOT NULL DEFAULT 0,
  `a35` int(11) NOT NULL DEFAULT 0,
  `a36` int(11) NOT NULL DEFAULT 0,
  `a37` int(11) NOT NULL DEFAULT 0,
  `a38` int(11) NOT NULL DEFAULT 0,
  `a39` int(11) NOT NULL DEFAULT 0,
  `a40` int(11) NOT NULL DEFAULT 0,
  `a41` int(11) NOT NULL DEFAULT 0,
  `a42` int(11) NOT NULL DEFAULT 0,
  `a43` int(11) NOT NULL DEFAULT 0,
  `a44` int(11) NOT NULL DEFAULT 0,
  `a45` int(11) NOT NULL DEFAULT 0,
  `a46` int(11) NOT NULL DEFAULT 0,
  `a47` int(11) NOT NULL DEFAULT 0,
  `a48` int(11) NOT NULL DEFAULT 0,
  `a49` int(11) NOT NULL DEFAULT 1,
  `a50` int(11) NOT NULL DEFAULT 1,
  `a51` int(11) NOT NULL DEFAULT 1,
  `a52` int(11) NOT NULL DEFAULT 1,
  `a53` int(11) NOT NULL DEFAULT 1,
  `a54` int(11) NOT NULL DEFAULT 1,
  `a55` int(11) NOT NULL DEFAULT 1,
  `a56` int(11) NOT NULL DEFAULT 1,
  `a57` int(11) NOT NULL DEFAULT 2,
  `a58` int(11) NOT NULL DEFAULT 3,
  `a59` int(11) NOT NULL DEFAULT 4,
  `a60` int(11) NOT NULL DEFAULT 6,
  `a61` int(11) NOT NULL DEFAULT 5,
  `a62` int(11) NOT NULL DEFAULT 4,
  `a63` int(11) NOT NULL DEFAULT 3,
  `a64` int(11) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Daten für Tabelle `brett3`
--

INSERT INTO `brett3` (`bid`, `sp1`, `sp2`, `dran`, `a1`, `a2`, `a3`, `a4`, `a5`, `a6`, `a7`, `a8`, `a9`, `a10`, `a11`, `a12`, `a13`, `a14`, `a15`, `a16`, `a17`, `a18`, `a19`, `a20`, `a21`, `a22`, `a23`, `a24`, `a25`, `a26`, `a27`, `a28`, `a29`, `a30`, `a31`, `a32`, `a33`, `a34`, `a35`, `a36`, `a37`, `a38`, `a39`, `a40`, `a41`, `a42`, `a43`, `a44`, `a45`, `a46`, `a47`, `a48`, `a49`, `a50`, `a51`, `a52`, `a53`, `a54`, `a55`, `a56`, `a57`, `a58`, `a59`, `a60`, `a61`, `a62`, `a63`, `a64`) VALUES
(1, 0, 0, 1, 8, 9, 10, 12, 11, 10, 9, 8, 7, 7, 7, 7, 7, 7, 7, 7, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 2, 3, 4, 6, 5, 4, 3, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `spieler`
--

CREATE TABLE `spieler` (
  `sid` int(11) NOT NULL,
  `name` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Daten für Tabelle `spieler`
--

INSERT INTO `spieler` (`sid`, `name`) VALUES
(67, 'test');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `brett3`
--
ALTER TABLE `brett3`
  ADD PRIMARY KEY (`bid`);

--
-- Indizes für die Tabelle `spieler`
--
ALTER TABLE `spieler`
  ADD PRIMARY KEY (`sid`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `brett3`
--
ALTER TABLE `brett3`
  MODIFY `bid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT für Tabelle `spieler`
--
ALTER TABLE `spieler`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
