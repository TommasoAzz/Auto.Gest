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

class User {
    //attributi
    private $id; //int - 5 cifre - chiave primaria
    private $nome; //string - 30 caratteri - nome della persona
    private $cognome; //string - 30 caratteri - cognome della persona
    private $classe; //string - 1 carattere - classe della persona (da 1 a 5, A, E, P)
    private $sezione; //string - 1 carattere - sezione della persona (A, B, C, E, P)
    private $indirizzo; //string - 20 caratteri - indirizzo della classe+sezione della persona (tecnico, economico, ragioneria, ecc.)
    private $giornoIscritto; //int - 1 cifra - (0 a n) n=numero giorni evento
    private $oraIscritta; //int - 1 cifra - (0 a n) n=numero ore giornata
    private $livello; //int - 1 cifra - (1 a 3) 1=utente, 2=responsabile, 3=amministratore
    
    //metodi
    //--costruttore
    public function __construct() {
        $this->id=0;
        $this->nome="";
        $this->cognome="";
        $this->classe="";
        $this->sezione="";
        $this->indirizzo="";
        $this->giornoIscritto=0;
        $this->oraIscritta=0;
        $this->livello=1;
    }

    //--assegnazione dei dati recuperandoli da DB
    public function initUser($db,$id) {
        $query="SELECT * FROM Persone P INNER JOIN Classi C ON P.ID_Classe=C.ID_Classe WHERE ID_Persona=$id";
        $richiesta=$db->queryDB($query);

        if(!$richiesta) return false;

        $this->id               = intval($richiesta[0]["ID_Persona"]);
        $this->nome             = $richiesta[0]["Nome"];
        $this->cognome          = $richiesta[0]["Cognome"];
        $this->classe           = $richiesta[0]["Classe"];
        $this->sezione          = $richiesta[0]["Sezione"]; 
        $this->indirizzo        = $richiesta[0]["Indirizzo"];
        $this->giornoIscritto   = intval($richiesta[0]["GiornoIscritto"]);
        $this->oraIscritta      = intval($richiesta[0]["OraIscritta"]);
        $this->livello          = intval($richiesta[0]["Livello"]);
        return true;

    }
    
    //--assegnazione dei dati
    public function setGiornoIscritto($val) {
        $this->giornoIscritto=$val;
    }
    
	public function setOraIscritta($val) {
		$this->oraIscritta=$val;
    }

    //--recupero dei dati
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }
    
    public function getCognome() {
        return $this->cognome; 
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
