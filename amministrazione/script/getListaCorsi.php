<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST") header("Location: ../../");

$corsi = getDatiCorsi($db);

if($corsi === "errore_db_lista_corsi") echo $corsi;
else echo json_encode($corsi);
