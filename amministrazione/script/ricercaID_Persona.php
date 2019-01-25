<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST") header("Location: ../../");

$nome = $db->escape(GlobalVar::POST("nome"));
$cognome = $db->escape(GlobalVar::POST("cognome"));

$dati = getDatiPersona($db, $nome, $cognome);

if($dati === "errore_db_dati_persona") echo $dati;
else echo json_encode($dati);