<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";
Session::open();
$utente = Session::get("utente");

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST") header("Location: ../../");

$c_gestiti = getCorsiGestiti($db, $utente->getID());

if($c_gestiti === "errore_db_corsi_gestiti") echo $c_gestiti;
else echo json_encode($c_gestiti);
