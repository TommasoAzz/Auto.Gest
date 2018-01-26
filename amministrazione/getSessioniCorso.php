<?php
require_once "../classes.php";
    if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
        Session::open();
        require_once "../connectToDB.php";
        $db=Session::get("db");
        $nomeC=GlobalVar::getPost("nomeCorso");

        $query="SELECT ID_Corso AS id,Durata AS d,Aula AS a,MaxPosti AS pt FROM Corsi WHERE Nome='".$nomeC."'";
        $datiCorso=$db->qikQuery($query); //ritornato un array
        
        $query2="SELECT Giorno AS g,Ora AS o, PostiRimasti AS pr, ID_SessioneCorso AS id_sc FROM SessioniCorsi WHERE ID_Corso=".intval($datiCorso[0]["id"])." ORDER BY Giorno,Ora";
        $datiSessioniCorso=$db->qikQuery($query2);

        $datiDaInviare[0]=json_encode($datiCorso);
        $datiDaInviare[1]=json_encode($datiSessioniCorso);

        $jsonData=json_encode($datiDaInviare);
        echo $jsonData;  
    } else {
        header("Location: /");
    }
?>