<?php
require_once "../../caricaClassi.php";
Session::open();
$utente=Session::get("utente");
unset($utente);
Session::close();
header("Location: ../../");
