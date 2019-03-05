<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";
require_once "../../getInfo.php";
Session::open();
$info = Session::get("info");

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST" || !(GlobalVar::issetPOST("registrazione_nome") && GlobalVar::issetPOST("registrazione_cognome")
&& GlobalVar::issetPOST("username_utente") && GlobalVar::issetPOST("password_vecchia_utente")
&& GlobalVar::issetPOST("password_nuova_utente") && GlobalVar::issetPOST("password_nuova2_utente"))) header("Location: ../../");

$registrazione_nome = $db->escape(GlobalVar::POST("registrazione_nome"));
$registrazione_cognome = $db->escape(GlobalVar::POST("registrazione_cognome"));
$username_utente = $db->escape(GlobalVar::POST("username_utente"));
$password_vecchia_utente = $db->escape(GlobalVar::POST("password_vecchia_utente"));
$password_nuova_utente = $db->escape(GlobalVar::POST("password_nuova_utente"));
$password_nuova2_utente = $db->escape(GlobalVar::POST("password_nuova2_utente"));

//cambiare MD5 assolutamente
$ID_Persona = $db->queryDB("SELECT ID_Persona FROM Persone WHERE Nome = '" . $registrazione_nome . "' AND Cognome = '" . $registrazione_cognome . "' AND Pwd = '" . hash('sha256', $password_vecchia_utente) . "'");

if(!$ID_Persona) {
   echo "errore_db_corrispondenza_nome_cognome_password"; 
} else if(usernameEsistente($db, $username_utente)) {
    echo "errore_db_username_esistente";
} else if($password_nuova_utente !== $password_nuova2_utente) {
    echo "errore_db_corrispondenza_password";
} else {
    $ID_Persona = $ID_Persona[0]['ID_Persona'];
    $aggiornamento = $db->queryDB("UPDATE Persone SET PrimoAccessoEffettuato = 1, Pwd = '" . password_hash($password_nuova_utente, PASSWORD_DEFAULT) . "', Username = '". $username_utente ."' WHERE ID_Persona = $ID_Persona");
    
    echo $aggiornamento ? "profilo_creato" : "errore_db_profilo_non_creato";
}

