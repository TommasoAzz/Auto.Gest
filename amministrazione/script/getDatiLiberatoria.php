<?php
require_once "../../connettiAlDB.php";
require_once "../../caricaClassi.php";
Session::open();
$utente=Session::get("utente");

if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
    $idPersona=GlobalVar::getPost("idP");
    $idSessCorso=GlobalVar::getPost("idS");

    $q="SELECT C.Nome AS Nome, SC.Ora AS Hh, SC.Giorno AS Gg FROM Corsi C INNER JOIN SessioniCorsi SC ON C.ID_Corso=SC.ID_Corso WHERE SC.ID_SessioneCorso=$idSessCorso";
    $q1="SELECT Nome, Cognome FROM Persone WHERE ID_Persona=$idPersona";

    $res=$db->queryDB($q); //ritornato un array
    $res1=$db->queryDB($q1);

    $nGG=$res[0]['Gg'];
    $q2="SELECT Giorno, Mese FROM DateEvento WHERE ID_DataEvento=$nGG";
    $res2=$db->queryDB($q2);

    $dati["NomeCorso"]=$res[0]['Nome'];
    $dati["Ora"]=$res[0]['Hh'];
    $dati["Giorno"]=$res2[0]['Giorno'];
    $dati["Mese"]=$res2[0]['Mese'];
    $dati["NomeStud"]=$res1[0]['Nome'];
    $dati["CognomeStud"]=$res1[0]['Cognome'];
    $dati["NomeLog"]=$utente->getNome();
    $dati["CognomeLog"]=$utente->getCognome();

    $jsonData=json_encode($dati);
    echo $jsonData;
} else {
    header("Location: ../");
}
?>
