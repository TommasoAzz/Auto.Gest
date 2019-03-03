<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST" && !GlobalVar::issetPOST("username_utente")) header("Location: ../../");

if(GlobalVar::POST("username_utente") !== NULL) {
    $result = usernameEsistente($db, $db->escape(GlobalVar::POST("username_utente")));
    echo !$result ? "true" : "false";
}