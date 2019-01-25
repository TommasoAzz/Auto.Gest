<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST") header("Location: ../../");

$iscrittiAltreAttivita = iscrittiAltreAttivita($db);

if($iscrittiAltreAttivita === "errore_db_iscritti_altre_attivita") echo $iscrittiAltreAttivita;
else echo json_encode($iscrittiAltreAttivita);
