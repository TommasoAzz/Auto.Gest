<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST") header("Location: ../../");

$ID = intval(GlobalVar::POST("ID"));
$nuovapwd = cifraPassword(GlobalVar::POST("Pwd"));

echo (cambioPassword($db, $ID, $nuovapwd)) ? "cambio-effettuato" : "cambio-non-effettuato";