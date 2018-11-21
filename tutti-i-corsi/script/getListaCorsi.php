<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST") header("Location: ../../");
$giorno = intval(GlobalVar::POST("giorno"));
$ora = intval(GlobalVar::POST("ora"));

$listaSessioniCorsi = getCorsiDisponibili($db, $giorno, $ora);

if($listaSessioniCorsi === "errore_db_lista_corsi") echo $listaSessioniCorsi;
else echo json_encode($listaSessioniCorsi);
