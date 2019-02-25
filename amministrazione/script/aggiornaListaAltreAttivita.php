<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST" || !GlobalVar::issetPOST("aA")) header("Location: ../../");

$aA = $db->escape(GlobalVar::POST("aA"));

$modificaEff = $db->queryDB("UPDATE AltreAttivita SET Lista='".$aA."' WHERE ID=1");

echo ($modificaEff) ? "modifica-effettuata" : "modifica-non-effettuata";