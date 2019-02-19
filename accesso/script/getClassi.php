<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST") header("Location: ../../");

$ind = $db->escape(GlobalVar::POST("indirizzo"));

$classi = getClassi($db, $ind);

if($classi === "errore_db_classi_istituto") echo $classi;
else echo json_encode($classi);
