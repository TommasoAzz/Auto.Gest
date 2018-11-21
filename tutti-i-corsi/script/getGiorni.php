<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST") header("Location: ../../");
$dateEvento = getDateEvento($db);

if($dateEvento === "errore_db_date_evento") echo $dateEvento;
else echo json_encode($dateEvento);
