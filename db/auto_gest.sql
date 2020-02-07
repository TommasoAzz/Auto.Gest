-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Feb 28, 2019 alle 17:57
-- Versione del server: 10.1.23-MariaDB-9+deb9u1
-- Versione PHP: 7.0.30-0+deb9u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
-- Struttura della tabella `AltreAttivita`
--

CREATE TABLE `AltreAttivita` (
  `ID` int(11) NOT NULL,
  `Lista` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Contiene le attività non considerate Corsi';

-- --------------------------------------------------------

--
-- Struttura della tabella `Classi`
--

CREATE TABLE `Classi` (
  `ID_Classe` int(3) NOT NULL COMMENT 'Identificativo della classe',
  `Classe` varchar(1) NOT NULL COMMENT 'Numero progressivo della classe (1-2-3-4-5)',
  `Sezione` varchar(1) NOT NULL COMMENT 'Sezione della classe (A-B-C, ecc)',
  `Indirizzo` varchar(30) NOT NULL COMMENT 'Indirizzo del corso di studi della classe (informatico, turistico, economico, ecc.)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='USARE I BACKSLASH';

-- --------------------------------------------------------

--
-- Struttura della tabella `Corsi`
--

CREATE TABLE `Corsi` (
  `ID_Corso` int(3) NOT NULL COMMENT 'Identificativo del corso',
  `Nome` varchar(60) NOT NULL COMMENT 'Nome del corso',
  `Informazioni` varchar(300) DEFAULT NULL COMMENT 'Informazioni relative al corso, se ce ne sono.',
  `Aula` varchar(30) NOT NULL COMMENT 'Luogo dove si tiene il corso',
  `Durata` smallint(1) UNSIGNED NOT NULL COMMENT 'Durata in numero ore del corso',
  `MaxPosti` smallint(4) UNSIGNED NOT NULL COMMENT 'Numero di posti massimo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='USARE I BACKSLASH';

-- --------------------------------------------------------

--
-- Struttura della tabella `DateEvento`
--

CREATE TABLE `DateEvento` (
  `ID_DataEvento` int(1) NOT NULL,
  `Giorno` varchar(2) NOT NULL,
  `Mese` varchar(10) NOT NULL,
  `Anno` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Struttura della tabella `InfoEvento`
--

CREATE TABLE `InfoEvento` (
  `ID` int(11) NOT NULL,
  `Titolo` varchar(30) NOT NULL COMMENT 'Titolo dell evento',
  `Durata` int(1) NOT NULL COMMENT 'Numero giorni dell evento',
  `PeriodoSvolgimento` varchar(255) NOT NULL COMMENT 'Data dell evento (gg/mm/aaaa-gg/mm/aaaa)',
  `AperturaIscrizioni` datetime NOT NULL COMMENT 'Data di apertura delle iscrizioni',
  `NomeContatto1` varchar(100) NOT NULL COMMENT 'Nome della persona da contattare per problemi, 1',
  `LinkContatto1` varchar(255) NOT NULL COMMENT 'Link della persona da contattare per problemi, 1',
  `NomeContatto2` varchar(100) NOT NULL COMMENT 'Nome della persona da contattare per problemi, 2',
  `LinkContatto2` varchar(255) NOT NULL COMMENT 'Link della persona da contattare per problemi, 2',
  `NomeContatto3` varchar(100) NOT NULL COMMENT 'Nome della persona da contattare per problemi, 3',
  `LinkContatto3` varchar(255) NOT NULL COMMENT 'Link della persona da contattare per problemi, 3',
  `Istituto` varchar(100) NOT NULL COMMENT 'Poche info sull''istituto (nome, paese)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='USARE I BACKSLASH';

-- --------------------------------------------------------

--
-- Struttura della tabella `Iscrizioni`
--

CREATE TABLE `Iscrizioni` (
  `ID_Iscrizione` int(6) NOT NULL COMMENT 'Identificativo della iscrizione',
  `ID_Studente` int(5) DEFAULT NULL COMMENT 'Identificativo dello studente',
  `ID_SessioneCorso` int(6) DEFAULT NULL COMMENT 'Identificativo del corso'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='USARE I BACKSLASH';

--
-- Trigger `Iscrizioni`
--
DELIMITER $$
CREATE TRIGGER `AggiornamentoRegistroPresenze` AFTER INSERT ON `Iscrizioni` FOR EACH ROW BEGIN
INSERT INTO RegPresenze (ID_Iscrizione)
SELECT ID_Iscrizione FROM Iscrizioni WHERE ID_SessioneCorso = new.ID_SessioneCorso AND ID_Studente = new.ID_Studente;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `InserimentoIscrizione` BEFORE INSERT ON `Iscrizioni` FOR EACH ROW BEGIN
DECLARE p_rimasti SMALLINT(4);
DECLARE d_corso SMALLINT(1);
DECLARE n_giorno VARCHAR(1);
DECLARE ore_giornata VARCHAR(1);
DECLARE gg_iscritto TINYINT(1);
DECLARE hh_iscritta TINYINT(1);

SELECT PostiRimasti, Giorno INTO p_rimasti, n_giorno FROM SessioniCorsi WHERE ID_SessioneCorso = new.ID_SessioneCorso;
IF(p_rimasti <= 0) THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Non ci sono posti disponibili per la sessione selezionata.';
ELSE
	UPDATE SessioniCorsi SET PostiRimasti = PostiRimasti - 1 WHERE ID_SessioneCorso = new.ID_SessioneCorso;
    	
	SELECT MAX(Ora) INTO ore_giornata FROM SessioniCorsi WHERE Giorno = n_giorno;

	SELECT C.Durata INTO d_corso FROM Corsi C JOIN SessioniCorsi SC ON C.ID_Corso = SC.ID_Corso WHERE SC.ID_SessioneCorso = new.ID_SessioneCorso;
	
	SELECT OraIscritta, GiornoIscritto INTO hh_iscritta, gg_iscritto FROM Persone WHERE ID_Persona = new.ID_Studente;
	
	SET hh_iscritta = hh_iscritta + d_corso;
	IF(hh_iscritta = ore_giornata) THEN
		UPDATE Persone SET OraIscritta = 0, GiornoIscritto = GiornoIscritto + 1 WHERE ID_Persona = new.ID_Studente;
	ELSE
		UPDATE Persone SET OraIscritta = hh_iscritta WHERE ID_Persona = new.ID_Studente;
	END IF;
END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `RimozioneIscrizione` AFTER DELETE ON `Iscrizioni` FOR EACH ROW BEGIN
UPDATE SessioniCorsi SET PostiRimasti = PostiRimasti + 1 WHERE ID_SessioneCorso = old.ID_SessioneCorso;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `Persone`
--

CREATE TABLE `Persone` (
  `ID_Persona` int(5) NOT NULL COMMENT 'Identificatore elemento',
  `Nome` varchar(30) NOT NULL COMMENT 'Nome studente',
  `Cognome` varchar(30) NOT NULL COMMENT 'Cognome studente',
  `ID_Classe` int(3) DEFAULT NULL COMMENT 'Identificativo della classe',
  `Username` varchar(32) DEFAULT NULL COMMENT 'Username proposto o deciso dall''utente in fase di registrazione',
  `Mail` varchar(254) DEFAULT NULL COMMENT 'Mail dell''utente',
  `PrimoAccessoEffettuato` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Flag che segnala se l''utente ha fatto il primo accesso o no (sono due procedimenti di login diversi)',
  `Pwd` varchar(255) NOT NULL COMMENT 'Password studente',
  `HashAttivazioneProfilo` varchar(64) DEFAULT NULL COMMENT 'Hash che viene inviato per mail in modo da confermare l''account',
  `GiornoIscritto` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1=iscritto totale giorno 1, 2=iscritto totale giorno 2, n=iscritto totale giorno n. Se n=max(n) non si puo piu iscrivere',
  `OraIscritta` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Ora in cui è arrivato ad iscriversi l''utente',
  `Livello` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1=studente 2=responsabile_corso 3=admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='USARE I BACKSLASH';

-- --------------------------------------------------------

--
-- Struttura della tabella `RegPresenze`
--

CREATE TABLE `RegPresenze` (
  `ID_RegPresenze` int(10) NOT NULL COMMENT 'Identificativo della presenza',
  `ID_Iscrizione` int(6) DEFAULT NULL COMMENT 'Identificativo dell iscrizione',
  `Presenza` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=assente, 1=presente, 2=ritardo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='USARE I BACKSLASH';

-- --------------------------------------------------------

--
-- Struttura della tabella `SessioniCorsi`
--

CREATE TABLE `SessioniCorsi` (
  `ID_SessioneCorso` int(6) NOT NULL COMMENT 'Identificativo della sessione del corso',
  `Giorno` varchar(1) NOT NULL COMMENT 'Giorni in cui si svolge il corso',
  `Ora` varchar(1) NOT NULL COMMENT 'Ora di inizio della sessione di corso',
  `PostiRimasti` smallint(4) UNSIGNED NOT NULL COMMENT 'Numero di posti rimasti nella sessione del corso',
  `ID_Corso` int(3) DEFAULT NULL COMMENT 'Identificativo del corso',
  `ID_Responsabile` int(5) DEFAULT NULL COMMENT 'Chiave esterna del responsabile del corso'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='USARE I BACKSLASH';

-- --------------------------------------------------------

--
-- Struttura della tabella `TentativiLogin`
--

CREATE TABLE `TentativiLogin` (
  `ID` int(11) NOT NULL,
  `ID_Persona` int(5) NOT NULL,
  `Tempo` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `AltreAttivita`
--
ALTER TABLE `AltreAttivita`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `Classi`
--
ALTER TABLE `Classi`
  ADD PRIMARY KEY (`ID_Classe`);

--
-- Indici per le tabelle `Corsi`
--
ALTER TABLE `Corsi`
  ADD PRIMARY KEY (`ID_Corso`),
  ADD UNIQUE KEY `Nome` (`Nome`);

--
-- Indici per le tabelle `DateEvento`
--
ALTER TABLE `DateEvento`
  ADD PRIMARY KEY (`ID_DataEvento`);

--
-- Indici per le tabelle `InfoEvento`
--
ALTER TABLE `InfoEvento`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `Iscrizioni`
--
ALTER TABLE `Iscrizioni`
  ADD PRIMARY KEY (`ID_Iscrizione`),
  ADD KEY `Iscrizioni_Persone` (`ID_Studente`),
  ADD KEY `Iscrizioni_SessioniCorsi` (`ID_SessioneCorso`);

--
-- Indici per le tabelle `Persone`
--
ALTER TABLE `Persone`
  ADD PRIMARY KEY (`ID_Persona`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD KEY `Persone_Classi` (`ID_Classe`);

--
-- Indici per le tabelle `RegPresenze`
--
ALTER TABLE `RegPresenze`
  ADD PRIMARY KEY (`ID_RegPresenze`),
  ADD KEY `RegPresenze_Iscrizioni` (`ID_Iscrizione`);

--
-- Indici per le tabelle `SessioniCorsi`
--
ALTER TABLE `SessioniCorsi`
  ADD PRIMARY KEY (`ID_SessioneCorso`),
  ADD KEY `SessioniCorsi_Corsi` (`ID_Corso`),
  ADD KEY `SessioniCorsi_Persone` (`ID_Responsabile`);

--
-- Indici per le tabelle `TentativiLogin`
--
ALTER TABLE `TentativiLogin`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `TentativiLogin_Persone` (`ID_Persona`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `Classi`
--
ALTER TABLE `Classi`
  MODIFY `ID_Classe` int(3) NOT NULL AUTO_INCREMENT COMMENT 'Identificativo della classe';
--
-- AUTO_INCREMENT per la tabella `Corsi`
--
ALTER TABLE `Corsi`
  MODIFY `ID_Corso` int(3) NOT NULL AUTO_INCREMENT COMMENT 'Identificativo del corso';
--
-- AUTO_INCREMENT per la tabella `Iscrizioni`
--
ALTER TABLE `Iscrizioni`
  MODIFY `ID_Iscrizione` int(6) NOT NULL AUTO_INCREMENT COMMENT 'Identificativo della iscrizione';
--
-- AUTO_INCREMENT per la tabella `Persone`
--
ALTER TABLE `Persone`
  MODIFY `ID_Persona` int(5) NOT NULL AUTO_INCREMENT COMMENT 'Identificatore elemento';
--
-- AUTO_INCREMENT per la tabella `RegPresenze`
--
ALTER TABLE `RegPresenze`
  MODIFY `ID_RegPresenze` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Identificativo della presenza';
--
-- AUTO_INCREMENT per la tabella `SessioniCorsi`
--
ALTER TABLE `SessioniCorsi`
  MODIFY `ID_SessioneCorso` int(6) NOT NULL AUTO_INCREMENT COMMENT 'Identificativo della sessione del corso';
--
-- AUTO_INCREMENT per la tabella `TentativiLogin`
--
ALTER TABLE `TentativiLogin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `Iscrizioni`
--
ALTER TABLE `Iscrizioni`
  ADD CONSTRAINT `Iscrizioni_Persone` FOREIGN KEY (`ID_Studente`) REFERENCES `Persone` (`ID_Persona`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `Iscrizioni_SessioniCorsi` FOREIGN KEY (`ID_SessioneCorso`) REFERENCES `SessioniCorsi` (`ID_SessioneCorso`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `Persone`
--
ALTER TABLE `Persone`
  ADD CONSTRAINT `Persone_Classi` FOREIGN KEY (`ID_Classe`) REFERENCES `Classi` (`ID_Classe`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `RegPresenze`
--
ALTER TABLE `RegPresenze`
  ADD CONSTRAINT `RegPresenze_Iscrizioni` FOREIGN KEY (`ID_Iscrizione`) REFERENCES `Iscrizioni` (`ID_Iscrizione`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `SessioniCorsi`
--
ALTER TABLE `SessioniCorsi`
  ADD CONSTRAINT `SessioniCorsi_Corsi` FOREIGN KEY (`ID_Corso`) REFERENCES `Corsi` (`ID_Corso`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `SessioniCorsi_Persone` FOREIGN KEY (`ID_Responsabile`) REFERENCES `Persone` (`ID_Persona`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `TentativiLogin`
--
ALTER TABLE `TentativiLogin`
  ADD CONSTRAINT `TentativiLogin_Persone` FOREIGN KEY (`ID_Persona`) REFERENCES `Persone` (`ID_Persona`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
