<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD")==="POST") {
    $giorno = intval(GlobalVar::POST("giorno"));
    $ora = intval(GlobalVar::POST("ora"));

    $listaCorsi = getDatiCorsi($db);
    
    if($listaCorsi === "errore_db_lista_corsi") echo $listaCorsi;
    else {
        $corsiGgHh = [];

        for($i = 0, $l = sizeof($listaCorsi), $sc_counter = 0; $i < $l; $i++) { //$sc_counter è contatore per le righe di $corsiGgHh, completamente diverso da $i
            $corso = $listaCorsi[$i];
            $id = intval($corso['ID_Corso']);

            $sc = getSessioniCorso($db, $id, $giorno, $ora); //se restituisce "errore_db_sessione_corso" è perché il corso di ID=$id non ha sessioni nel giorno=$giorno all'ora=$ora

            if($sc !== "errore_db_sessione_corso") {
                $corsiGgHh[$sc_counter] = array(
                    "Nome" => $corso['Nome'],
                    "Aula" => $corso['Aula'],
                    "Durata" => intval($corso['Durata']),
                    "PostiTotali" => intval($corso['MaxPosti']),
                    "PostiRimasti" => intval($sc[0]['PostiRimasti']) //$sc[0] è l'unica riga di risultato ottenuta da getSessioniCorso(), dato che ho inserito il parametro $giorno e $ora
                );
                $sc_counter++;
            }          
        }

        if(sizeof($corsiGgHh) === 0) echo "errore_db_sessione_corso"; //non è stata trova nessuna sessione corso disponibile per nessun corso presente in $listaCorsi
        else {
            $jsonData = json_encode($corsiGgHh);
            echo $jsonData;
        }
    }
} else {
    header("Location: ../../");
}
?>
