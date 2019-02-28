<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST" || (!(GlobalVar::issetPOST("ID") && GlobalVar::issetPOST("vecchiaPwd") && GlobalVar::issetPOST("nuovaPwd")))) header("Location: ../../");

$ID = intval(GlobalVar::POST("ID"));
$vecchiapwd = GlobalVar::POST("vecchiaPwd");
$nuovapwd = GlobalVar::POST("nuovaPwd");

echo cambioPasswordUtente($db, $ID, $vecchiapwd, $nuovapwd);