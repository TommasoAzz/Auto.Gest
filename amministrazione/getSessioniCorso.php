<?php
require_once "../classes.php";
if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
    Session::open();
    require_once "../connectToDB.php";
    $db=Session::get("db");
    $nomeC=GlobalVar::getPost("nomeCorso");

    //query al db
    $q_corso="SELECT ID_Corso AS id,Durata AS d,Aula AS a,MaxPosti AS pt FROM Corsi WHERE Nome='".$nomeC."'";
    $r_corso=$db->queryDB($q_corso); //ritornato un array
    
    //se qualcosa va male
    if(!$r_corso) echo "errore_db_id_corso";

    //query al db
    $q_sessioneCorso="SELECT Giorno AS g,Ora AS o, PostiRimasti AS pr, ID_SessioneCorso AS id_sc FROM SessioniCorsi WHERE ID_Corso=".intval($datiCorso[0]["id"])." ORDER BY Giorno,Ora";
    $r_sessioneCorso=$db->queryDB($q_sessioneCorso);

    //se qualcosa va male
    if(!$r_sessioneCorso) echo "errore_db_sessione_corso";
    
    $datiDaInviare[0]=json_encode($r_corso);
    $datiDaInviare[1]=json_encode($r_sessioneCorso);

    $jsonData=json_encode($datiDaInviare);
    echo $jsonData;  
} else {
    header("Location: /");
}
?>