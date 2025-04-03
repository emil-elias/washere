-- phpMyAdmin SQL Dump
-- version 4.9.11
-- https://www.phpmyadmin.net/
--
-- Host: 
-- Erstellungszeit: 03. Apr 2025 um 01:01
-- Server-Version: 8.0.36
-- PHP-Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `dbs12195827`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `washere_countries`
--

CREATE TABLE `washere_countries` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `continent` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `valid` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `washere_countries`
--

INSERT INTO `washere_countries` (`id`, `name`, `continent`, `valid`) VALUES
(1, 'Germany', 'Europe', 1),
(2, 'Netherlands', 'Europe', 1),
(3, 'Denmark', 'Europe', 1),
(4, 'Norway', 'Europe', 1),
(5, 'Sweden', 'Europe', 1),
(6, 'United Kingdom', 'Europe', 1),
(8, 'Belgium', 'Europe', 1),
(9, 'France', 'Europe', 1),
(10, 'Spain', 'Europe', 1),
(11, 'Portugal', 'Europe', 1),
(12, 'Czech Republic', 'Europe', 1),
(13, 'Austria', 'Europe', 1),
(14, 'Slovakia', 'Europe', 1),
(15, 'Italy', 'Europe', 1),
(16, 'Slovenia', 'Europe', 1),
(17, 'India', 'Asia', 0),
(18, 'Thailand', 'Asia', 1),
(19, 'Malaysia', 'Asia', 1),
(20, 'Singapore', 'Asia', 1),
(21, 'Vietnam', 'Asia', 1),
(22, 'Cambodia', 'Asia', 1),
(23, 'Laos', 'Asia', 0),
(24, 'Hong Kong', 'Asia', 1),
(25, 'Japan', 'Asia', 1),
(26, 'Turkey', 'Asia', 0);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `washere_countries`
--
ALTER TABLE `washere_countries`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `washere_countries`
--
ALTER TABLE `washere_countries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
