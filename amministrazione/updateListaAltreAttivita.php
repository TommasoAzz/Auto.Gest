<?php
require_once "../classes.php";
if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
    Session::open();
    require_once "../connectToDB.php";
    $db=Session::get("db");
    $aA=GlobalVar::getPost("aA");

    $query="UPDATE AltreAttivita SET Lista='".$aA."' WHERE ID=1";
    $modificaEff=$db->queryDB($query); 

    if($modificaEff) {
        echo "modifica-effettuata";
    } else {
        echo "modifica-non-effettuata";
    }

} else {
    header("Location: /");
}
?>