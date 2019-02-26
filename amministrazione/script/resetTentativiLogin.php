<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST" && !GlobalVar::issetPOST("ID")) header("Location: ../../");

$ID_Persona = intval(GlobalVar::POST("ID"));

//SCRIPT DI RESET DEI TENTATIVI DI LOGIN DI UN UTENTE

if($db->queryDB("DELETE FROM TentativiLogin WHERE ID_Persona=$ID_Persona")) echo "reset-effettuato";
else echo "errore_db_delete_tentativi";