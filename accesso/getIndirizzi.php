<?php
require_once "../caricaClassi.php";
require_once "../connettiAlDB.php";
require_once "../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST") header("Location: ../");

$indirizzi = getIndirizzi($db);

if($indirizzi === "errore_db_indirizzi") echo $indirizzi;
else echo json_encode($indirizzi);
