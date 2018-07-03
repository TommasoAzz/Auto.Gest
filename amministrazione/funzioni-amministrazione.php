<?php
//richiesta delle attività fuori dalla lista dei corsi a cui alcuni studenti devono partecipare
function getAltreAttivita($db) {
    //query al db
    $lista_aa=$db->queryDB("SELECT Lista FROM AltreAttivita WHERE ID=1");
    $flag_esistenza=$db->queryDB("SELECT COUNT(*) AS Esiste FROM Corsi WHERE Nome='Altre attività'");
    
    //operazione da eseguire se il db non ha restituito valori
    if(!$lista_aa || !$flag_esistenza) return "errore_altre_attivita";
    
    //operazione da eseguire se il db ha restituito qualcosa
    if($flag_esistenza[0]["Esiste"] === 1) {
        $lista_aa=trim($lista_aa[0]["Lista"]);

        if($lista_aa !== "") return $lista_aa;
    }
    
    return "no_altre_attivita";

}
?>