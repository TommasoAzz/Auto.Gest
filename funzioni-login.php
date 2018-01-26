<?php
function login($db,$utente,$cla,$sez,$ind,$postPass) {
    $query="SELECT ID_Persona FROM Persone P INNER JOIN Classi C ON P.ID_Classe=C.ID_Classe "; //lasciare spazio dopo ID_Classe
    $query.="WHERE Classe='".$cla."' AND Sezione='".$sez."' AND Indirizzo='".$ind."' AND Password='".$postPass."'";
    $richiesta=$db->doQuery($query);
    if($db->checkQuery() && $richiesta!==false && $db->getAffectedRows()==1) {
        $id=intval($db->getResult("ID_Persona"));
        $queryEseguita=$utente->initUser($db,$id);
        if($queryEseguita) {
            Session::set("utente",$utente);
            //controllo del login
            $browser=GlobalVar::getServer("HTTP_USER_AGENT"); //browser in uso
            Session::set("ID_Persona",$id);
            Session::set("login",hash('sha512',$postPass.$browser));
            return "utente-esistente";
        } else {
            return "password-errata";
        }
    } else {
        return "errore-generico";
    }
}
?>