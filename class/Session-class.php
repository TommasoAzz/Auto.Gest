<?php

/**
    Classe per la gestione della Sessione, per gestire l'apertura e chiusura 
    di essa, il settaggio e il reperimento dei dati di sessione
    N.B: Non deve essere allocato, di conseguenza,
    nella classe: self::$attributo
                    self::metodo()
    fuori classe: NomeClasse::$attributo
                    NomeClasse::metodo()
*/

class Session {
    //attributi
    //--per il controllo della inizializzazione
    private static $initialized=false;
    //--per la sicurezza della sessione
    private static $nomeSessione='autogestione-sessione';
    private static $secure=false; //true per HTTPS, false per HTTP
    private static $httponly=true;
    //metodi
    //--costruttore (vuoto)
    private function __construct() {}
    //--per l'inizializzazione
    private static function init() {
        if(self::$initialized) {
            return;
        } else {
            self::$initialized=true;
        }
    }
    //--per l'apertura/chiusura della sessione
    public static function open() {
        self::init();
        if(!isset($_SESSION)) {
            ini_set('session.use_only_cookies',1);
            $paramCookie=session_get_cookie_params();
            session_set_cookie_params($paramCookie["lifetime"],$paramCookie["path"],$paramCookie["domain"],self::$secure,self::$httponly);
            session_name(self::$nomeSessione);
            session_start();
            session_regenerate_id();
        }
    }
    public static function close() {
        unset($_SESSION);
        $param=session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $param["path"], $param["domain"], $param["secure"], $param["httponly"]);
        session_unset();
        session_destroy();
    }
    //--per il set/get dei dati di sessione 
    public static function set($key,$value) {
        self::init();
        if(self::$initialized) {
            $_SESSION[$key]=$value;
        }
    }
    public static function get($key) {
        self::init();
        if(self::$initialized) {
            if(isset($_SESSION[$key])) {
                return $_SESSION[$key];
            }
        }
    }
    //--per controllare se una variabile di sessione esiste
    public static function is_set($key) {
        self::init();
        if(self::$initialized) {
            if(isset($_SESSION[$key])) {
                return true;
            } else {
                return false;
            }
        }
    }
}
?>