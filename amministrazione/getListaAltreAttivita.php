<?php
    require_once "../classes.php";
    if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
        Session::open();
        require_once "../connectToDB.php";
        $db=Session::get("db");

        // $nomeCorso contiene il nome nel database del corso fittizio "Altre attività"
        $nomeCorso="Altre attività";
        $q='SELECT Cognome, P.Nome AS Nome, Cl.Classe AS Cl, Cl.Sezione AS Sez, Cl.Indirizzo AS Ind, Sc.Giorno AS Gg, Sc.Ora AS Hh FROM Persone AS P, Classi AS Cl, Corsi AS Co, SessioniCorsi AS Sc, Iscrizioni AS I WHERE P.ID_Classe=Cl.ID_Classe AND Sc.ID_Corso=Co.ID_Corso AND I.ID_SessioneCorso=Sc.ID_SessioneCorso AND I.ID_Studente=P.ID_Persona AND Co.Nome="'.$nomeCorso.'" ORDER BY Cognome, Nome, Indirizzo, Classe, Sezione, Ora, Giorno';
        $res=$db->qikQuery($q); //ritornato un array
        $jsonData=json_encode($res);
        echo $jsonData;
    } else {
        header("Location: /");
    }
?>