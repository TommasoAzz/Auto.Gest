<?php
/**
    Classe per la gestione di un corso, per gestirne le informazioni.
    N.B: Necessita allocazione, di conseguenza,
    nella classe:   $this->attributo
                    $this->metodo()
    fuori classe:   $nome_oggetto->attributo
                    $nome_oggetto->metodo()
*/
 
class Corso extends AutoGestDB {
    //attributi
    private $id; //identificativo del corso
    private $nome; //nome del corso
    private $informazioni; //info sul corso se presenti
    private $aula; //luogo dove si tiene il corso
    private $durata; //durata in numero ore del corso
    private $maxPosti; //numero di posti massimo

    //metodi
    //--costruttore
    public function __construct($id=0, $n="", $i="", $a="", $d=0, $mp=0) {
        $this->setID($id); //identificativo del corso
        $this->setNome($n); //nome del corso 
        $this->setInformazioni($i); //informazioni sul corso
        $this->setAula($a); //luogo dove si tiene il corso 
        $this->setDurata($d); //durata in numero ore del corso
        $this->setMaxPosti($mp); //numero di posti massimo 
    }

    private function preparaDato($dato, $campoDB, $tipo="stringa") {
        if(strtolower($tipo) === "stringa") {
            $val = trim($dato);

            if(strlen($val) <= self::Corsi[$campoDB])
                //condizione rispettata anche per val. default (e va bene così)
                return $val;
            else
                return "";
        } elseif(strtolower($tipo) === "intero") {
            $val = intval($dato);

            if($val <= self::Corsi[$campoDB])
                //condizione rispettata anche per val. default (e va bene così)
                return $val; 
            else
                return 0;
        }
    }

    public function setID($id) {
        $this->id = $this->preparaDato($id, "ID_Corso", "intero");
    }

    public function setNome($n) {
        $this->nome = $this->preparaDato($n, "Nome");
    }

    public function setInformazioni($i) {
        $this->informazioni = $this->preparaDato($i, "Informazioni");
    }

    public function setAula($a) {
        $this->aula = $this->preparaDato($a, "Aula");
    }

    public function setDurata($d) {
        $this->durata = $this->preparaDato($d, "Durata", "intero");
    }

    public function setMaxPosti($mp) {
        $this->maxPosti = $this->preparaDato($mp, "MaxPosti", "intero");
    }

    public function getID() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getInformazioni() {
        return $this->informazioni;
    }

    public function getAula() {
        return $this->aula;
    }

    public function getDurata() {
        return $this->durata;
    }

    public function getMaxPosti() {
        return $this->maxPosti;
    }
}