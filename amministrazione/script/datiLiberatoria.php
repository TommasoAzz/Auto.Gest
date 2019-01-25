<?php
require_once "../../connettiAlDB.php";
require_once "../../caricaClassi.php";
require_once "../../funzioni.php";
Session::open();
$admin = Session::get("utente");

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST")  header("Location: ../../");

// non vengono create funzioni per questo script qui
$id_persona = intval(GlobalVar::POST("idP"));
$id_sc = intval(GlobalVar::POST("idS"));

$infoSC = $db->queryDB("SELECT C.Nome, SC.Ora, SC.Giorno FROM Corsi C INNER JOIN SessioniCorsi SC ON C.ID_Corso=SC.ID_Corso WHERE SC.ID_SessioneCorso=$id_sc"); //ritornato un array
$studente = inizializzaUtente($db, $id_persona);

$date_evento = getDateEvento($db);

if(!$infoSC || !$datiUtente || $date_evento === "errore_db_date_evento") echo "errore_db_dati_liberatoria";
else {
    $sc = $infoSC[0];
    $id_dataevento = intval($sc["Giorno"]) - 1;

    $datiLiberatoria = array(
        "NomeCorso" => $sc['Nome'],
        "Ora" => intval($sc['Ora']),
        "Giorno" => $date_evento[$id_dataevento]["Giorno"],
        "Mese" => $date_evento[$id_dataevento]["Mese"],
        "NomeStud" => $studente->getNome(),
        "CognomeStud" => $studente->getCognome(),
        "ClasseStud" => $studente->classe->getClasse() . "°" . $studente->classe->getSezione() . " " . $studente->classe->getIndirizzo(),
        "NomeAdmin" => $admin->getNome(),
        "CognomeAdmin" => $admin->getCognome()
    );

    echo json_encode($datiLiberatoria);
}

