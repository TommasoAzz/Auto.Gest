<?php
    require_once "../classes.php";
    if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
        Session::open();
        require_once "../connectToDB.php";
        $db=Session::get("db");
        $query="SELECT Lista FROM AltreAttivita WHERE ID=1";
        $query2="SELECT COUNT(*) AS Esiste FROM Corsi WHERE Nome='Altre attività'";
        $res=$db->qikQuery($query);
        $res2=$db->qikQuery($query2);
        if($res !== false && trim($res[0]["Lista"]) !== "" && $res2[0]["Esiste"] !== "0") {
            $altreAttivita=trim($res[0]["Lista"]);
        } else {
            $altreAttivita="no-altre-attivita";
        }
        echo $altreAttivita;
    } else {
        header("Location: /");
    }
?>