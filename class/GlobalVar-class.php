<?php

/**
    Classe per la gestione degli array globali, per gestire il recupero di dati da essi
    N.B: Non deve essere allocato, di conseguenza,
    nella classe: self::$attributo
                    self::metodo()
    fuori classe: NomeClasse::$attributo
                    NomeClasse::metodo()
*/

class GlobalVar {
    //attributi
    //--per il controllo della inizializzazione
    private static $initialized=false;
    
    //metodi
    //--costruttore (vuoto)
    private function __construct() {}

    //--per l'inizializzazione
    private static function init() {
        if(!self::$initialized) {
            self::$initialized=true;
        }
    }

    //--per il recupero dati dagli array
    public static function getPost($key) {
        self::init();
        return filter_input(INPUT_POST,$key);
    }

    public static function getGet($key) {
        self::init();
        return filter_input(INPUT_GET,$key);
    }

    public static function getServer($key) {
        self::init();
        return filter_input(INPUT_SERVER,$key);
    }

    public static function getCookie($key) {
        self::init();
        return filter_input(INPUT_COOKIE,$key);
    }
}
?>
