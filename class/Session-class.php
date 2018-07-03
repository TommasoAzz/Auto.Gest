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
    private static $nomeSessione='autogest-session';
    private static $secure=false; //true per HTTPS, false per HTTP
    private static $httponly=true;

    //metodi
    //--costruttore (vuoto)
    private function __construct() {}

    //--per l'inizializzazione
    private static function init() {
        if(!self::$initialized) {
            self::$initialized=true;
        }
    }

    //--per l'apertura/chiusura della sessione
    public static function open() {
        self::init();
        if(!isset($_SESSION)) {
            ini_set('session.use_only_cookies',1);

            $c_param=session_get_cookie_params();
            session_set_cookie_params($c_param["lifetime"],$c_param["path"],$c_param["domain"],self::$secure,self::$httponly);

            session_name(self::$nomeSessione);
            session_start();
            session_regenerate_id();
        }
    }

    public static function close() {
        unset($_SESSION);

        $c_param=session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $c_param["path"], $c_param["domain"], $c_param["secure"], $c_param["httponly"]);

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
        if(self::$initialized && isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            return false;
        }
    }

    //--per controllare se una variabile di sessione esiste
    public static function is_set($key) {
        self::init();
        if(self::$initialized) {
            return isset($_SESSION[$key]);
        } else {
            return false;
        }
    }
    
    //--per controllare se connessione e' http o https
    public static function is_secure() {
        return self::$secure;
    }
}
?>