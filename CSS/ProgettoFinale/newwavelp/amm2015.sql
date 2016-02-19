-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 19, 2016 alle 21:25
-- Versione del server: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `amm2015`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` bigint(20) unsigned NOT NULL,
  `username` varchar(128) COLLATE utf8_bin NOT NULL,
  `password` varchar(128) COLLATE utf8_bin NOT NULL,
  `nome` varchar(128) COLLATE utf8_bin NOT NULL,
  `cognome` varchar(128) COLLATE utf8_bin NOT NULL,
  `via` varchar(128) COLLATE utf8_bin NOT NULL,
  `cap` int(10) NOT NULL,
  `citta` varchar(20) COLLATE utf8_bin NOT NULL,
  `telefono` int(20) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `nome`, `cognome`, `via`, `cap`, `citta`, `telefono`) VALUES
(1, 'admin', '', 'Luca', 'Bianchi', 'Bologna', 111, 'Iglesias', 78133333);

-- --------------------------------------------------------

--
-- Struttura della tabella `album`
--

CREATE TABLE IF NOT EXISTS `album` (
  `id` bigint(20) unsigned NOT NULL,
  `nome` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `autore` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `prezzo` float NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `album`
--

INSERT INTO `album` (`id`, `nome`, `autore`, `prezzo`) VALUES
(1, 'ultravox', 'vienna', 15),
(2, 'depeche mode', 'some great reward', 10),
(3, 'the modern dance', 'pere ubu', 5),
(4, 'forever young', 'alphaville', 12),
(5, 'hunting high and low', 'a-ah', 10),
(6, 'treasure', 'cocteau twins', 5),
(7, 'something for everybody', 'devo', 15),
(8, 'siberia', 'diaframma', 10),
(9, 'rio', 'duran duran', 15),
(10, 'eurythmics', 'in the garden', 10);

-- --------------------------------------------------------

--
-- Struttura della tabella `album_ordini`
--

CREATE TABLE IF NOT EXISTS `album_ordini` (
  `album_id` bigint(20) unsigned NOT NULL,
  `ordine_id` bigint(20) unsigned NOT NULL,
  `id` bigint(20) unsigned NOT NULL,
  `quantita` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `autore`
--

CREATE TABLE IF NOT EXISTS `autore` (
  `id` int(11) NOT NULL,
  `nome` varchar(128) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `clienti`
--

CREATE TABLE IF NOT EXISTS `clienti` (
  `id` bigint(20) unsigned NOT NULL,
  `username` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `nome` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `cognome` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `via` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `civico` int(10) NOT NULL,
  `cap` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `citta` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `telefono` int(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dump dei dati per la tabella `clienti`
--

INSERT INTO `clienti` (`id`, `username`, `password`, `nome`, `cognome`, `via`, `civico`, `cap`, `citta`, `telefono`) VALUES
(1, 'veronica', '', 'Veronica', 'Puddu', 'Bologna', 1, '09016', 'Iglesias', 3401111);

-- --------------------------------------------------------

--
-- Struttura della tabella `ordini`
--

CREATE TABLE IF NOT EXISTS `ordini` (
  `id` bigint(20) unsigned NOT NULL,
  `domicilio` varchar(2) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `prezzo` float unsigned NOT NULL,
  `stato` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `data` datetime NOT NULL,
  `cliente_id` bigint(20) unsigned NOT NULL,
  `admin_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `idUser` int(11) NOT NULL,
  `email` varchar(45) NOT NULL,
  `psw` varchar(45) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `album_ordini`
--
ALTER TABLE `album_ordini`
  ADD PRIMARY KEY (`id`), ADD KEY `album_id` (`album_id`), ADD KEY `ordine_id` (`ordine_id`);

--
-- Indexes for table `autore`
--
ALTER TABLE `autore`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clienti`
--
ALTER TABLE `clienti`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ordini`
--
ALTER TABLE `ordini`
  ADD PRIMARY KEY (`id`), ADD KEY `cliente_id` (`cliente_id`), ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `album`
--
ALTER TABLE `album`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `album_ordini`
--
ALTER TABLE `album_ordini`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `autore`
--
ALTER TABLE `autore`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `clienti`
--
ALTER TABLE `clienti`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ordini`
--
ALTER TABLE `ordini`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `album_ordini`
--
ALTER TABLE `album_ordini`
ADD CONSTRAINT `album_ordini_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `album` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `album_ordini_ibfk_2` FOREIGN KEY (`ordine_id`) REFERENCES `ordini` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `ordini`
--
ALTER TABLE `ordini`
ADD CONSTRAINT `ordini_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clienti` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `ordini_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
