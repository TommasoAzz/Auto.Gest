<?php
/**
    Classe per la gestione del Database, per gestire la connessione ad esso,
    l'invio di query e la gestione dei risultati
    N.B: Necessita allocazione, di conseguenza,
    nella classe: $this->attributo
                    $this->metodo()
    fuori classe: $nome_oggetto->attributo
                    $nome_oggetto->metodo()
*/

class Database {
    //attributi
    //--per connessione a DB
    private $hostname; //hostname di connessione 
    private $username; //username per la connessione al db
    private $password; //password per la connessione al db
    private $dbName; //nome del database a cui connettersi
    //--per gestione DB (eseguire query, controllo query, risultati query)
    private $conn; //oggetto del database
    //--per gestire query
    private $lastQuery; //oggetto dell'ultima query eseguita
    private $lastQueryRes; //array dei risultati dell'ultima query eseguita

    //metodi
    //--costruttore
    public function __construct($host, $un, $psw, $db) {
        $this->hostname = $host;
        $this->username = $un;
        $this->password = $psw;
        $this->dbName = $db;
        $this->lastQueryRes = array();
    }

    //--per il controllo (privati)
    private function checkConnection() {
        if($this->conn->connect_errno > 0) {
            $msg_errore="<p>Errore nella connessione al database [" . $this->conn->connect_error . "].</p>";
            die($msg_errore);
            return false;
        } else {
            return true;
        }
    }

    public function checkQuery() {
        if($this->lastQuery) {
            return true;
        } else {
            return false;
        }
    }

    //--per la connessione al database
    public function connect() {
        if($this->conn == null) {
            $this->conn = new mysqli($this->hostname, $this->username, $this->password, $this->dbName);
            if($this->checkConnection()) {
                $this->conn->autocommit(true);
            }
        }
    }

    public function disconnect() {
        $this->conn->close();
    }

    public function getAffectedRows() {
        return $this->conn->affected_rows; //restituisce il numero delle righe affette dalla query
    }
    
    //--per gestire le query (inviarle e visualizzare risultati)
    public function escape($string) {
        $string = $this->conn->real_escape_string($string);
        return $string;
    }

    public function freeResult() {
        $this->lastQuery->free();
    } //Svuota lastQuery
	
    public function queryDB($string) {
        $this->lastQuery = $this->conn->query($string);
        if(gettype($this->lastQuery) === "boolean") {
            return $this->lastQuery;
        } elseif($this->getAffectedRows() > 0) {
            $result=array();
            $i=0;
            while($row = $this->lastQuery->fetch_assoc()) {
                $result[$i] = $row;
                $i++;
            }
            $this->freeResult();
            return $result;  
        }
        
        return false;
    }
}
?>