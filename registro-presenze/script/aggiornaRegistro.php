<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
Session::open();

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST" || !GlobalVar::issetPOST("aggiornamenti")) header("Location: ../../");
$status = json_decode(GlobalVar::POST("aggiornamenti"));

for($i = 0, $l = sizeof($status); $i < $l; $i++)
    $control[$i] = $db->queryDB("UPDATE RegPresenze SET Presenza = " . $status[$i][1] . " WHERE ID_Iscrizione=".$status[$i][0]);

$problemi_zero = true;
for($i = 0, $l = sizeof($control); $i < $l && $problemi_zero; $i++) if(!$control[$i]) $problemi_zero = false;

echo ($problemi_zero) ? "registro-aggiornato" : "registro-non-aggiornato";
