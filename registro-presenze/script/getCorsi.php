<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
Session::open();
$utente=Session::get("utente");

if(GlobalVar::SERVER("REQUEST_METHOD")==="POST") {
    $q="SELECT Nome,Giorno,Ora,ID_SessioneCorso FROM Corsi C INNER JOIN SessioniCorsi S ON C.ID_Corso=S.ID_Corso WHERE ID_Responsabile=".$utente->getId()." ORDER BY Giorno,Ora";
    $res=$db->queryDB($q); //ritornato un array
    $jsonData=json_encode($res);
    echo $jsonData;
} else {
    header("Location: ../../");
}
?>
