<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";
Session::open();
$info = Session::get("info");
if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST" || !(GlobalVar::issetPOST("ID") && GlobalVar::issetPOST("Pwd"))) header("Location: ../../");

$ID = intval(GlobalVar::POST("ID"));
$nuovapwd = GlobalVar::POST("Pwd");

$cambioEff = cambioPasswordUtente_Admin($db, $ID, $nuovapwd);

echo $cambioEff ? "cambio-effettuato" : "cambio-non-effettuato";