<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST" || !GlobalVar::issetPOST("ID")) header("Location: ../../");

$id = intval(GlobalVar::POST("ID"));

$regPresenze = getRegistroPresenzeSessioneCorso($db, $id);

if($regPresenze === "errore_db_registro_presenze") echo $regPresenze;
else echo json_encode($regPresenze);
