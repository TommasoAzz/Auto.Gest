<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST" || !(GlobalVar::issetPOST("classe") && GlobalVar::issetPOST("sezione") && GlobalVar::issetPOST("indirizzo") && GlobalVar::issetPOST("psw"))) header("Location: ../../");

//reperisco i dati
$cla = $db->escape(GlobalVar::POST("classe"));
$sez = $db->escape(GlobalVar::POST("sezione"));
$ind = $db->escape(GlobalVar::POST("indirizzo"));
$psw = $db->escape(GlobalVar::POST("psw"));

//eseguo la funzione primoAccesso()
$risultatoLogin = primoAccesso($db, $cla, $sez, $ind, $psw); //stringa

if($risultatoLogin === "errore_db_dati_input" || $risultatoLogin === "errore_db_idpersona" || $risultatoLogin === "primo_accesso_effettuato") echo $risultatoLogin;
else {
    echo json_encode($risultatoLogin);
}

