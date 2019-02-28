<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";
Session::open();
$utente = Session::get("utente");

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST") header("Location: ../../");

if(isset($utente)) echo $utente->getID();
else echo "errore_sessione_utente";