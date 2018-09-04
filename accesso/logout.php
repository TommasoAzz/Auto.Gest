<?php
require_once "../caricaClassi.php";
Session::open();
$utente=Session::get("utente");
$login=Session::get("login");
unset($utente,$login);
Session::close();
header("Location: ../");
?>
