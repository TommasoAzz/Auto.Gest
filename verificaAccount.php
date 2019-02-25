<?php
require_once "caricaClassi.php";
require_once "connettiAlDB.php";
require_once "funzioni.php";
require_once "getInfo.php";
Session::open();
$info = Session::get("info");

if(GlobalVar::SERVER("REQUEST_METHOD") !== "GET" || !(GlobalVar::issetGET("mail") && GlobalVar::issetGET("hashattivazione"))) header("Location: /");

$mail = $db->escape(GlobalVar::GET("mail"));
$activation_hash = $db->escape(GlobalVar::GET("hashattivazione"));

$accountPresente = $db->queryDB("SELECT ID_Persona, PrimoAccessoEffettuato FROM Persone WHERE HashAttivazioneProfilo = '" . $activation_hash  . "' AND Mail = '" . $mail . "'");

if(!$accountPresente) die("<p>C'è stato un errore nell'elaborazione della richiesta.</p>");

$id_persona = $accountPresente[0]["ID_Persona"];
$pae = $accountPresente[0]["PrimoAccessoEffettuato"];

if($pae > 0) die("<p>Hai già effettuato la verifica dell'account. Clicca <a href='" . getURL("/") . "' qui per tornare alla homepage.</p>");

$aggiornamentoProfilo = $db->queryDB("UPDATE Persone SET PrimoAccessoEffettuato = 1 WHERE ID_Persona = $id_persona");

if(!$aggiornamentoProfilo) die("<p>C'è stato un errore nell'aggiornamento del profilo collegato all'indirizzo mail: " . $mail . ".</p>");

echo "<p>Hai confermato il tuo profilo di " . $info['titolo'] . ". Clicca <a href='" . getURL("/") . "'>qui</a> per tornare alla homepage.</p>";