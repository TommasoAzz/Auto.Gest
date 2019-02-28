<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST") header("Location: ../../");

Session::open();

$utente = Session::get("utente");
if(isset($utente)) echo $utente->getID();
else echo "errore_sessione_utente";