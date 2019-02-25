<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST" || !(GlobalVar::issetPOST("ID") && GlobalVar::issetPOST("Pwd"))) header("Location: ../../");

$ID = intval(GlobalVar::POST("ID"));
$nuovapwd = password_hash(GlobalVar::POST("Pwd"), PASSWORD_DEFAULT);

echo (cambioPassword($db, $ID, $nuovapwd)) ? "cambio-effettuato" : "cambio-non-effettuato";