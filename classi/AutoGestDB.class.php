<?php
/**
    Classe contenente le informazioni sulle dimensioni massime 
    dei campi nel database di Auto.Gest
    N.B: Non deve essere allocato, di conseguenza,
    nella classe:   self::$attributo
                    self::metodo()
    fuori classe:   NomeClasse::$attributo
                    NomeClasse::metodo()
*/
class AutoGestDB {
    const AltreAttivita = array(
        "ID" => 1, //int
        "Lista" => 65535 //string
    );

    const Classi = array(
        "ID_Classe" => 999, //int
        "Classe" => 1, //string
        "Sezione" => 1, //string
        "Indirizzo" => 30 //string
    );

    const Corsi = array(
        "ID_Corso" => 999, //int
        "Nome" => 60, //string
        "Informazioni" => 300, //string
        "Aula" => 30, //string
        "Durata" => 9, //int
        "MaxPosti" => 9999 //int
    );

    const DateEvento = array(
        "ID_DataEvento" => 9, //int
        "Giorno" => 2, //string
        "Mese" => 10, //string
        "Anno" => 4 //string
    );

    const InfoEvento = array(
       "ID" => 1, //int
       "Titolo" => 30, //string
       "Durata" => 9, //int
       "PeriodoSvolgimento" => 255, //string
       "NomeContatto1" => 100, //string
       "LinkContatto1" => 255, //string
       "NomeContatto2" => 100, //string
       "LinkContatto2" => 255, //string
       "NomeContatto3" => 100, //string
       "LinkContatto3" => 255, //string
       "Istituto" => 100 //string
    );

    const Iscrizioni = array(
        "ID_Iscrizione" => 999999, //int
        "ID_Studente" => 99999, //int
        "ID_SessioneCorso" => 999999 //int
    );

    const Persone = array(
        "ID_Persona" => 99999, //int
        "Nome" => 30, //string
        "Cognome" => 30, //string
        "ID_Classe" => 999, //int
        "Username" => 32, //string
        "PrimoAccessoEffettuato" => 1, //int
        "Pwd" => 64, //string
        "GiornoIscritto" => 9, //int
        "OraIscritta" => 9, //int
        "Livello" => 3 //int (1 studente - 2 responsabile corso - 3 amministratore)
    );

    const RegPresenze = array(
        "ID_RegPresenze" => 999999, //int
        "ID_Iscrizione" => 999999, //int
        "Presenza" => 2 //int (0 assente - 1 presente - 2 ritardo)
    );

    const SessioniCorsi = array(
        "ID_SessioneCorso" => 999999, //int
        "Giorno" => 9, //int
        "Ora" => 9, //int
        "PostiRimasti" => 9999, //int
        "ID_Corso" => 999, //int
        "ID_Responsabile" => 99999 //int
    );

    const TentativiLogin = array(
        "ID" => 99999999999, //int
        "ID_Persona" => 99999, //int
        "Tempo" => 30 //string
    );
}