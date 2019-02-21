<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";
Session::open();

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST") header("Location: ../../");

//reperisco i dati
$cla = $db->escape(GlobalVar::POST("classe"));
$sez = $db->escape(GlobalVar::POST("sezione"));
$ind = $db->escape(GlobalVar::POST("indirizzo"));
$psw = $db->escape(GlobalVar::POST("psw"));

//eseguo la funzione login()
$risultatoLogin = login($db, $cla, $sez, $ind, $psw); //stringa

if($risultatoLogin !== "utente_esistente") echo $risultatoLogin;
else {
    $utente = Session::get("utente");
    $datiDaRestituire = array(
        "nome" => $utente->getNome(),
        "cognome" => $utente->getCognome(),
        "classe" => $utente->classe->getClasse() . "°" . $utente->classe->getSezione() . " " . $utente->classe->getIndirizzo(),
        "ruolo" => $utente->getLivello() == 1 ? "Studente" : ($utente->getLivello() == 2 ? "Responsabile di corso" : "Amministratore dell'evento")
    );
    echo json_encode($datiDaRestituire);
}

