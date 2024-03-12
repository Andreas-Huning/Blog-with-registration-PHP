-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 12. Mrz 2024 um 12:46
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
-- Datenbank: `blog`
--
CREATE DATABASE IF NOT EXISTS `blog` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `blog`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `account`
--

DROP TABLE IF EXISTS `account`;
CREATE TABLE `account` (
  `accountId` int(11) NOT NULL,
  `accountName` varchar(255) NOT NULL,
  `accRoleId` int(11) NOT NULL DEFAULT 1,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `account`
--

INSERT INTO `account` (`accountId`, `accountName`, `accRoleId`, `userId`) VALUES
(1, 'admin', 2, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `accrole`
--

DROP TABLE IF EXISTS `accrole`;
CREATE TABLE `accrole` (
  `accRoleId` int(11) NOT NULL,
  `accRoleName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `accrole`
--

INSERT INTO `accrole` (`accRoleId`, `accRoleName`) VALUES
(1, 'Benutzer'),
(2, 'Admin');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `adr`
--

DROP TABLE IF EXISTS `adr`;
CREATE TABLE `adr` (
  `adrId` int(11) NOT NULL,
  `adrName` varchar(255) DEFAULT NULL,
  `adrStreet` varchar(255) DEFAULT NULL,
  `adrStreetNr` varchar(255) DEFAULT NULL,
  `adrZipCode` varchar(255) DEFAULT NULL,
  `adrCity` varchar(255) DEFAULT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `postId` int(11) NOT NULL,
  `postTitel` varchar(255) NOT NULL,
  `postText` text NOT NULL,
  `postImagePath` varchar(255) NOT NULL,
  `postDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `accountId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `userdata`
--

DROP TABLE IF EXISTS `userdata`;
CREATE TABLE `userdata` (
  `userDataId` int(11) NOT NULL,
  `userDataFirstName` varchar(255) DEFAULT NULL,
  `userDataLastName` varchar(255) DEFAULT NULL,
  `userDataBirthday` varchar(255) DEFAULT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `userdata`
--

INSERT INTO `userdata` (`userDataId`, `userDataFirstName`, `userDataLastName`, `userDataBirthday`, `userId`) VALUES
(1, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `userEmail` varchar(255) NOT NULL,
  `userPassword` varchar(255) NOT NULL,
  `userRegHash` varchar(255) DEFAULT NULL,
  `userRegTimeStamp` timestamp NULL DEFAULT current_timestamp(),
  `userPwdHash` varchar(255) DEFAULT NULL,
  `userPwdTimeStamp` timestamp NULL DEFAULT NULL,
  `userAvatarPath` varchar(255) NOT NULL DEFAULT '../.././public/images/avatar_dummy.png',
  `userStatesId` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`userId`, `userEmail`, `userPassword`, `userRegHash`, `userRegTimeStamp`, `userPwdHash`, `userPwdTimeStamp`, `userAvatarPath`, `userStatesId`) VALUES
(1, 'admin@admin.de', '$2y$10$IdL/rO52TeDm3w9kbBwJ2OxU70z1FQQGSWLGcHugJ1IYzcryrpJBu', NULL, '2024-03-12 11:46:26', NULL, NULL, '../.././public/images/avatar_dummy.png', 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `userstates`
--

DROP TABLE IF EXISTS `userstates`;
CREATE TABLE `userstates` (
  `userStatesId` int(11) NOT NULL,
  `userStatesLabel` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `userstates`
--

INSERT INTO `userstates` (`userStatesId`, `userStatesLabel`) VALUES
(1, 'unregistred'),
(2, 'open'),
(3, 'blocked');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`accountId`),
  ADD KEY `accRoleId` (`accRoleId`),
  ADD KEY `userId` (`userId`);

--
-- Indizes für die Tabelle `accrole`
--
ALTER TABLE `accrole`
  ADD PRIMARY KEY (`accRoleId`);

--
-- Indizes für die Tabelle `adr`
--
ALTER TABLE `adr`
  ADD PRIMARY KEY (`adrId`),
  ADD KEY `userId` (`userId`);

--
-- Indizes für die Tabelle `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`postId`),
  ADD KEY `accountId` (`accountId`);

--
-- Indizes für die Tabelle `userdata`
--
ALTER TABLE `userdata`
  ADD PRIMARY KEY (`userDataId`),
  ADD KEY `userId` (`userId`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD KEY `userStatesId` (`userStatesId`);

--
-- Indizes für die Tabelle `userstates`
--
ALTER TABLE `userstates`
  ADD PRIMARY KEY (`userStatesId`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `account`
--
ALTER TABLE `account`
  MODIFY `accountId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `accrole`
--
ALTER TABLE `accrole`
  MODIFY `accRoleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `adr`
--
ALTER TABLE `adr`
  MODIFY `adrId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `posts`
--
ALTER TABLE `posts`
  MODIFY `postId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `userdata`
--
ALTER TABLE `userdata`
  MODIFY `userDataId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `userstates`
--
ALTER TABLE `userstates`
  MODIFY `userStatesId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`accRoleId`) REFERENCES `accrole` (`accRoleId`),
  ADD CONSTRAINT `account_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);

--
-- Constraints der Tabelle `adr`
--
ALTER TABLE `adr`
  ADD CONSTRAINT `adr_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);

--
-- Constraints der Tabelle `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`accountId`) REFERENCES `account` (`accountId`);

--
-- Constraints der Tabelle `userdata`
--
ALTER TABLE `userdata`
  ADD CONSTRAINT `userdata_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);

--
-- Constraints der Tabelle `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`userStatesId`) REFERENCES `userstates` (`userStatesId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
