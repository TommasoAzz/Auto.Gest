<?php
    require_once "classes.php";
    Session::open();
    $userToUnset=Session::get("utente");
    $id=Session::get("ID_Persona");
    $login=Session::get("login");
    unset($userToUnset,$id,$login);
    Session::close();
    header("Location: /");
?>