<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD")==="POST") header("Location: ../../");

$ID_Persona = intval(GlobalVar::POST("ID"));

$corsi_studente = getCorsiStudente($db, $ID_Persona);

if($corsi_studente === "errore_db_corsi_iscritti_studente") echo $corsi_studente;
else echo json_encode($corsi_studente);