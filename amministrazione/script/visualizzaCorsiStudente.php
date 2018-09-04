<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
    $ID_Persona=GlobalVar::getPost("ID");

    $returnVal="";
    try {
        $numGiorni=getNumGiorni($db);
        
        if($numGiorni === "errore_db_giorni") throw new Exception($numGiorni); //restituisco il messaggio di errore
        
        $corsi_giorno=array();
        
        for($i=0;$i<$numGiorni;$i++) {
            $corsi_giorno[$i]=getCorsiGiorno($db,$ID_Persona,$i+1);
            if($corsi_giorno[$i] === "errore_db_corsi_iscritti_giorno") throw new Exception($corsi_giorno[$i]);
        }

        //formato array restituito: $corsi_giorno[giorno-1][ora-1]["campo_tabella_db"]
        $returnVal=json_encode($corsi_giorno);
    } catch(Exception $e) {
        $returnVal=$e;
    }

    echo $returnVal;
} else {
    header("Location: ../");
}
?>
