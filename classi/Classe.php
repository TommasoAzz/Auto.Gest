<?php
/**
    Classe per la gestione delle informazioni di una classe di un istituto
    N.B: Necessita allocazione, di conseguenza,
    nella classe:   $this->attributo
                    $this->metodo()
    fuori classe:   $nome_oggetto->attributo
                    $nome_oggetto->metodo()
*/
 
class Classe extends AutoGestDB {
    //attributi
    private $id; //identificativo della classe
    private $classe; //numero progressivo della classe (1-2-3-4-5) o altri caratteri in casi speciali
    private $sezione; //sezione della classe (A-B-C, ecc) 
    private $indirizzo; //indirizzo del corso di studi della classe (informatico, turistico, economico, ecc.) 

    //metodi
    //--costruttore
    public function __construct($id=0, $cl="", $sez="", $ind="") {
        $this->setID($id);
        $this->setClasse($cl);
        $this->setSezione($sez); //forse $aula
        $this->setIndirizzo($ind);
    }

    private function preparaDato($dato, $campoDB, $tipo="stringa") {
        if(strtolower($tipo) === "stringa") {
            $val = trim($dato);

            if(strlen($val) <= self::Classi[$campoDB])
                //condizione rispettata anche per val. default (e va bene così)
                return $val;
            else
                return "";
        } elseif(strtolower($tipo) === "intero") {
            $val = intval($dato);

            if($val <= self::Classi[$campoDB])
                //condizione rispettata anche per val. default (e va bene così)
                return $val; 
            else
                return 0;
        }
    }

    public function setID($id) {
        $this->id = $this->preparaDato($id, "ID_Classe", "intero");
    }

    public function setClasse($cl) {
        $this->classe = $this->preparaDato($cl, "Classe");
    }

    public function setSezione($sez) {
        $this->sezione = $this->preparaDato($sez, "Sezione");
    }

    public function setIndirizzo($ind) {
        $this->indirizzo = $this->preparaDato($ind, "Indirizzo");
    }

    public function getID() {
        return $this->id;
    }

    public function getClasse() {
        return $this->classe;
    }

    public function getSezione() {
        return $this->sezione;
    }

    public function getIndirizzo() {
        return $this->indirizzo;
    }
}
?>