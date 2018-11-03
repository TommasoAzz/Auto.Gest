<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD")==="POST") {
    $giorno = GlobalVar::POST("giorno");
    $ora = GlobalVar::POST("ora");

    $listaCorsi = getDatiCorsi($db);
    
    if($listaCorsi === "errore_db_lista_corsi") echo $listaCorsi;
    else {
        /* selezionare solo righe con $giorno e $ora */
        $corsiGgHH = [];

        for($i = 0, $l = sizeof($listaCorsi); $i < $l; $i++) 
            if($listaCorsi[$i]['Giorno'] == $giorno && $listaCorsi[$i]['Ora'] == $ora)
                $corsiGgHh[$i] = $listaCorsi[$i];

        $jsonData = json_encode($corsiGgHh);
        echo $jsonData;
    }
} else {
    header("Location: ../../");
}
?>
