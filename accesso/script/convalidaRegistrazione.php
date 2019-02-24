<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";
require_once "../../getInfo.php";
Session::open();
$info = Session::get("info");

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST") header("Location: ../../");

$registrazione_nome = $db->escape(GlobalVar::POST("registrazione_nome"));
$registrazione_cognome = $db->escape(GlobalVar::POST("registrazione_cognome"));
$mail_utente = $db->escape(GlobalVar::POST("mail_utente"));
$username_utente = $db->escape(GlobalVar::POST("username_utente"));
$password_vecchia_utente = $db->escape(GlobalVar::POST("password_vecchia_utente"));
$password_nuova_utente = $db->escape(GlobalVar::POST("password_nuova_utente"));
$password_nuova2_utente = $db->escape(GlobalVar::POST("password_nuova2_utente"));

//cambiare MD5 assolutamente
$ID_Persona = $db->queryDB("SELECT ID_Persona FROM Persone WHERE Nome = '" . $registrazione_nome . "' AND Cognome = '" . $registrazione_cognome . "' AND Pwd = '" . md5($password_vecchia_utente) . "'");

if(!$ID_Persona) {
   echo "errore_db_corrispondenza_nome_cognome_password"; 
} else if(usernameEsistente($db, $username_utente) || mailEsistente($db, $mail_utente)) {
    echo "errore_db_mail_username_esistenti";
} else if($mail_utente !== filter_var($mail_utente, FILTER_VALIDATE_EMAIL)) {
    echo "errore_db_formato_mail_errato";
} else if($password_nuova_utente !== $password_nuova2_utente) {
    echo "errore_db_corrispondenza_password";
} else {
    $activation_hash = hash('sha256', random_int(PHP_INT_MIN, PHP_INT_MAX));
    $ID_Persona = $ID_Persona[0]['ID_Persona'];
    $aggiornamento = $db->queryDB("UPDATE Persone SET Pwd = '" . password_hash($password_nuova_utente, PASSWORD_DEFAULT) . "', Username = '". $username_utente ."', Mail = '". $mail_utente ."', HashAttivazioneProfilo = '" . $activation_hash . "' WHERE ID_Persona = $ID_Persona");
    
    if($aggiornamento) {
        invioMailConfermaAttivazione($info['titolo'], $registrazione_nome, $registrazione_cognome, $username_utente, $mail_utente, $activation_hash);
        echo "profilo_creato";
    } else echo "errore_db_profilo_non_creato";
}

