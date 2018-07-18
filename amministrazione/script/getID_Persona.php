<?php
require_once "../../connettiAlDB.php";
require_once "../../caricaClassi.php";
include_once "../../getInfo.php";
require_once "../../funzioni.php";
Session::open();
$info=Session::get("info");
$db=Session::get("db");
$utente=Session::get("utente");

if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
    $nome=GlobalVar::getPost("nome");
    $cognome=GlobalVar::getPost("cognome");
    $q='SELECT ID_Persona,Classe,Sezione,Indirizzo FROM Persone P INNER JOIN Classi C ON P.ID_Classe=C.ID_Classe WHERE Nome LIKE "'.$nome.'" AND Cognome LIKE "'.$cognome.'"';
    $res=$db->qikQuery($q); //ritornato un array

    $jsonData=json_encode($res);
    echo $jsonData;
} else {
    header("Location: ../");
}
?>
