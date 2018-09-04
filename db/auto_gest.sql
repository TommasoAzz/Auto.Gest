-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Set 04, 2018 alle 14:27
-- Versione del server: 10.1.25-MariaDB
-- Versione PHP: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `auto_gest`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `altreattivita`
--

CREATE TABLE `altreattivita` (
  `ID` int(11) NOT NULL,
  `Lista` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contiene le attività non considerate Corsi';

--
-- Dump dei dati per la tabella `altreattivita`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `classi`
--

CREATE TABLE `classi` (
  `ID_Classe` int(3) NOT NULL COMMENT 'Identificativo della classe',
  `Classe` varchar(1) CHARACTER SET utf8 NOT NULL COMMENT 'Numero progressivo della classe (1-2-3-4-5)',
  `Sezione` varchar(1) CHARACTER SET utf8 NOT NULL COMMENT 'Sezione della classe (A-B-C, ecc)',
  `Indirizzo` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT 'Indirizzo del corso di studi della classe (informatico, turistico, economico, ecc.)'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='USARE I BACKSLASH';

--
-- Dump dei dati per la tabella `classi`
--

INSERT INTO `classi` (`ID_Classe`, `Classe`, `Sezione`, `Indirizzo`) VALUES
(1, 'E', 'E', 'ESTERNO'),
(2, 'P', 'P', 'PERSONALE');

-- --------------------------------------------------------

--
-- Struttura della tabella `corsi`
--

CREATE TABLE `corsi` (
  `ID_Corso` int(3) NOT NULL COMMENT 'Identificativo del corso',
  `Nome` varchar(60) CHARACTER SET utf8 NOT NULL COMMENT 'Nome del corso',
  `Aula` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT 'Luogo dove si tiene il corso',
  `Durata` smallint(1) UNSIGNED NOT NULL COMMENT 'Durata in numero ore del corso',
  `MaxPosti` smallint(4) UNSIGNED NOT NULL COMMENT 'Numero di posti massimo'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='USARE I BACKSLASH';

--
-- Dump dei dati per la tabella `corsi`
--
INSERT INTO `corsi` (`ID_Corso`, `Nome`, `Aula`, `Durata`,`MaxPosti`) VALUES
(1, 'Altre attività', 'Ovunque', 1, 300);

-- --------------------------------------------------------

--
-- Struttura della tabella `dateevento`
--

CREATE TABLE `dateevento` (
  `ID_DataEvento` int(1) NOT NULL,
  `Giorno` varchar(2) NOT NULL,
  `Mese` varchar(10) NOT NULL,
  `Anno` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `dateevento`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `infoevento`
--

CREATE TABLE `infoevento` (
  `ID` int(11) NOT NULL,
  `Titolo` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT 'Titolo dell evento',
  `Durata` int(1) NOT NULL COMMENT 'Numero giorni dell evento',
  `PeriodoSvolgimento` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'Data dell evento (gg/mm/aaaa-gg/mm/aaaa)',
  `NomeContatto1` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Nome della persona da contattare per problemi, 1',
  `LinkContatto1` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'Link della persona da contattare per problemi, 1',
  `NomeContatto2` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Nome della persona da contattare per problemi, 2',
  `LinkContatto2` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'Link della persona da contattare per problemi, 2',
  `NomeContatto3` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Nome della persona da contattare per problemi, 3',
  `LinkContatto3` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'Link della persona da contattare per problemi, 3',
  `Istituto` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Poche info sull''istituto (nome, paese)'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='USARE I BACKSLASH';

--
-- Dump dei dati per la tabella `infoevento`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `iscrizioni`
--

CREATE TABLE `iscrizioni` (
  `ID_Iscrizione` int(6) NOT NULL COMMENT 'Identificativo della iscrizione',
  `ID_Studente` int(5) NOT NULL COMMENT 'Identificativo dello studente',
  `ID_SessioneCorso` int(6) NOT NULL COMMENT 'Identificativo del corso'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='USARE I BACKSLASH';

-- --------------------------------------------------------

--
-- Struttura della tabella `persone`
--

CREATE TABLE `persone` (
  `ID_Persona` int(5) NOT NULL COMMENT 'Identificatore elemento',
  `Nome` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT 'Nome studente',
  `Cognome` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT 'Cognome studente',
  `ID_Classe` int(3) NOT NULL COMMENT 'Identificativo della classe',
  `Pwd` varchar(32) CHARACTER SET utf8 NOT NULL COMMENT 'Password studente',
  `GiornoIscritto` tinyint(1) UNSIGNED NOT NULL COMMENT '1=iscritto totale giorno 1, 2=iscritto totale giorno 2, n=iscritto totale giorno n. Se n=max(n) non si puo piu iscrivere',
  `OraIscritta` tinyint(1) UNSIGNED NOT NULL COMMENT 'Ora in cui è arrivato ad iscriversi l''utente',
  `Livello` tinyint(1) UNSIGNED NOT NULL COMMENT '1=studente 2=responsabile_corso 3=admin'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='USARE I BACKSLASH';

--
-- Dump dei dati per la tabella `persone`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `regpresenze`
--

CREATE TABLE `regpresenze` (
  `ID_RegPresenze` int(10) NOT NULL COMMENT 'Identificativo della presenza',
  `ID_Iscrizione` int(6) NOT NULL COMMENT 'Identificativo dell iscrizione',
  `Presenza` tinyint(1) NOT NULL COMMENT '0=assente, 1=presente, 2=ritardo'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='USARE I BACKSLASH';

-- --------------------------------------------------------

--
-- Struttura della tabella `sessionicorsi`
--

CREATE TABLE `sessionicorsi` (
  `ID_SessioneCorso` int(6) NOT NULL COMMENT 'Identificativo della sessione del corso',
  `Giorno` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Giorni in cui si svolge il corso',
  `Ora` varchar(1) CHARACTER SET utf8 NOT NULL COMMENT 'Ora di inizio della sessione di corso',
  `PostiRimasti` smallint(4) UNSIGNED NOT NULL COMMENT 'Numero di posti rimasti nella sessione del corso',
  `ID_Corso` int(3) NOT NULL COMMENT 'Identificativo del corso',
  `ID_Responsabile` int(5) NOT NULL COMMENT 'Chiave esterna del responsabile del corso'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='USARE I BACKSLASH';

--
-- Dump dei dati per la tabella `sessionicorsi`
--
-- --------------------------------------------------------

--
-- Struttura della tabella `tentativilogin`
--

CREATE TABLE `tentativilogin` (
  `ID` int(11) NOT NULL,
  `ID_Persona` int(5) NOT NULL,
  `Tempo` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `altreattivita`
--
ALTER TABLE `altreattivita`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `classi`
--
ALTER TABLE `classi`
  ADD PRIMARY KEY (`ID_Classe`);

--
-- Indici per le tabelle `corsi`
--
ALTER TABLE `corsi`
  ADD PRIMARY KEY (`ID_Corso`),
  ADD UNIQUE KEY `Nome` (`Nome`);

--
-- Indici per le tabelle `dateevento`
--
ALTER TABLE `dateevento`
  ADD PRIMARY KEY (`ID_DataEvento`);

--
-- Indici per le tabelle `infoevento`
--
ALTER TABLE `infoevento`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `iscrizioni`
--
ALTER TABLE `iscrizioni`
  ADD PRIMARY KEY (`ID_Iscrizione`);

--
-- Indici per le tabelle `persone`
--
ALTER TABLE `persone`
  ADD PRIMARY KEY (`ID_Persona`);

--
-- Indici per le tabelle `regpresenze`
--
ALTER TABLE `regpresenze`
  ADD PRIMARY KEY (`ID_RegPresenze`);

--
-- Indici per le tabelle `sessionicorsi`
--
ALTER TABLE `sessionicorsi`
  ADD PRIMARY KEY (`ID_SessioneCorso`);

--
-- Indici per le tabelle `tentativilogin`
--
ALTER TABLE `tentativilogin`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `classi`
--
ALTER TABLE `classi`
  MODIFY `ID_Classe` int(3) NOT NULL AUTO_INCREMENT COMMENT 'Identificativo della classe', AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT per la tabella `corsi`
--
ALTER TABLE `corsi`
  MODIFY `ID_Corso` int(3) NOT NULL AUTO_INCREMENT COMMENT 'Identificativo del corso', AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT per la tabella `iscrizioni`
--
ALTER TABLE `iscrizioni`
  MODIFY `ID_Iscrizione` int(6) NOT NULL AUTO_INCREMENT COMMENT 'Identificativo della iscrizione', AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT per la tabella `persone`
--
ALTER TABLE `persone`
  MODIFY `ID_Persona` int(5) NOT NULL AUTO_INCREMENT COMMENT 'Identificatore elemento', AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT per la tabella `regpresenze`
--
ALTER TABLE `regpresenze`
  MODIFY `ID_RegPresenze` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Identificativo della presenza', AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT per la tabella `sessionicorsi`
--
ALTER TABLE `sessionicorsi`
  MODIFY `ID_SessioneCorso` int(6) NOT NULL AUTO_INCREMENT COMMENT 'Identificativo della sessione del corso', AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT per la tabella `tentativilogin`
--
ALTER TABLE `tentativilogin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
