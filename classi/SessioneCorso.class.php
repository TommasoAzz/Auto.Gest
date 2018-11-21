<?php
/**
    Classe per la gestione di una sessione di un corso, per gestirne i dati.
    N.B: Necessita allocazione, di conseguenza,
    nella classe:   $this->attributo
                    $this->metodo()
    fuori classe:   $nome_oggetto->attributo
                    $nome_oggetto->metodo()
*/

class SessioneCorso extends AutoGestDB {
    //attributi
    private $id; //identificativo della sessione del corso
    private $giorno; //giorno in cui si svolge il corso (numero della giornata)
    private $ora; //ora di inizio della sessione del corso
    private $postiRimasti; //numero di posti rimasti nella sessione del corso
    private $idResponsabile; //chiave esterna del responsabile del corso
    public $corso; //oggetto di classe corso

    //metodi
    //--costruttore
    public function __construct($id=0, $g=0, $o=0, $pr=0, $id_r=0, $corso=null) {
        $this->setID($id);
        $this->setGiorno($g);
        $this->setOra($o);
        $this->setPostiRimasti($pr);
        $this->setIdResponsabile($id_r);
        $this->corso = $corso;
    }

    private function preparaDato($dato, $campoDB, $tipo="stringa") {
        if(strtolower($tipo) === "stringa") {
            $val = trim($dato);

            if(strlen($val) <= self::SessioniCorsi[$campoDB])
                //condizione rispettata anche per val. default (e va bene così)
                return $val;
            else
                return "";
        } elseif(strtolower($tipo) === "intero") {
            $val = intval($dato);

            if($val <= self::SessioniCorsi[$campoDB])
                //condizione rispettata anche per val. default (e va bene così)
                return $val; 
            else
                return 0;
        }
    }

    public function setID($id) {        
        $this->id = $this->preparaDato($id, "ID_SessioneCorso", "intero");
    }

    public function setGiorno($g) {
        $this->giorno = $this->preparaDato($g, "Giorno", "intero");
    }

    public function setOra($o) {
        $this->ora = $this->preparaDato($o, "Ora", "intero");
    }

    public function setPostiRimasti($pr) {
        $this->postiRimasti = $this->preparaDato($pr, "PostiRimasti", "intero");
    }

    public function setIdResponsabile($id_r) {
        $this->idResponsabile = $this->preparaDato($id_r, "ID_Responsabile", "intero");
    }

    public function getID() {
        return $this->id;
    }

    public function getGiorno() {
        return $this->giorno;
    }

    public function getOra() {
        return $this->ora;
    }

    public function getPostiRimasti() {
        return $this->postiRimasti;
    }

    public function getIdResponsabile() {
        return $this->idResponsabile;
    }
}