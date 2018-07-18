<?php
require_once "../caricaClassi.php";
/*Session::open();
$utente=Session::get("utente");
$id=Session::get("ID_Persona");
$login=Session::get("login");
unset($utente,$id,$login);*/
Session::close();
header("Location: ../");
?>
