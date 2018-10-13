<?php
/**
    Classe per la gestione dell'utente,
    sia esso studente, responsabile di un corso o admin
    N.B: Deve essere allocato, di conseguenza,
    nella classe: $this->attributo
                    $this->metodo()
    fuori classe: $nomeIstanzaDellaClasse->attributo
                    $nomeIstanzaDellaClasse->metodo()
*/
class Utente extends AutoGestDB {
    //attributi
    private $id; //int - 5 cifre - chiave primaria
    private $nome; //string - 30 caratteri - nome della persona
    private $cognome; //string - 30 caratteri - cognome della persona
    public $classe; //string - 1 carattere - classe della persona (da 1 a 5, A, E, P)
    private $giornoIscritto; //int - 1 cifra - (0 a n) n=numero giorni evento
    private $oraIscritta; //int - 1 cifra - (0 a n) n=numero ore giornata
    private $livello; //int - 1 cifra - (1 a 3) 1=utente, 2=responsabile, 3=amministratore

    //metodi
    //--costruttore
    public function __construct($id=0, $n="", $c="", $classe=null, $gi=0, $oi=0, $l=1) {
        $this->setID($id);
        $this->setNome($n);
        $this->setCognome($c);
        $this->classe = $classe;
        $this->setGiornoIscritto($gi);
        $this->setOraIscritta($oi);
        $this->setLivello($l);
    }
    
    private function preparaDato($dato, $campoDB, $tipo="stringa") {
        if(strtolower($tipo) === "stringa") {
            $val = trim($dato);

            if(strlen($val) <= self::Persone[$campoDB])
                //condizione rispettata anche per val. default (e va bene così)
                return $val;
            else
                return "";
        } elseif(strtolower($tipo) === "intero") {
            $val = intval($dato);

            if($val <= self::Persone[$campoDB])
                //condizione rispettata anche per val. default (e va bene così)
                return $val; 
            else
                return 0;
        }
    }

    //--assegnazione dei dati
    public function setID($id) {
        $this->id = $this->preparaDato($id, "ID_Persona", "intero");
    }

    public function setNome($n) {
        $this->nome = $this->preparaDato($n, "Nome");
    }

    public function setCognome($c) {
        $this->cognome = $this->preparaDato($c, "Cognome");
    }

    public function setGiornoIscritto($g) {
        $this->giornoIscritto = $this->preparaDato($g, "GiornoIscritto", "intero");
    }
    
	public function setOraIscritta($o) {
        $this->oraIscritta = $this->preparaDato($o, "OraIscritta", "intero");
    }

    public function setLivello($l) {
        $this->livello = $this->preparaDato($l, "Livello", "intero");
    }

    //--recupero dei dati
    public function getID() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }
    
    public function getCognome() {
        return $this->cognome; 
    }

    public function getGiornoIscritto() {
        return $this->giornoIscritto;
    }

    public function getOraIscritta() {
        return $this->oraIscritta;
    }

    public function getLivello() {
        return $this->livello;
    }
}
?>
