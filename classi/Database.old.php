<?php

/*
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
    private $db_name; //nome del database a cui connettersi

    //--per gestione DB (eseguire query, controllo query, risultati query)
    private $conn; //oggetto del database

    //--per gestire query
    private $lastQuery; //oggetto dell'ultima query eseguita
    private $lastQueryResult; //array dei risultati dell'ultima query eseguita

    //metodi
    //--costruttore
    public function __construct($host, $user, $psw, $dbname) {
        $this->hostname = $host;
        $this->username = $user;
        $this->password = $psw;
        $this->db_name = $dbname;
        $this->conn = null;
        $this->lastQuery = null;
        $this->lastQueryResult = array();
    }

    //--per il controllo (privati)
    private function checkConnection() {
        if(!isset($this->conn)) $this->connect();

        if($this->conn->connect_errno > 0) {
            $msg_errore="<p>C'è stato un errore nella connessione al database [<strong>".$this->conn->connect_error."</strong>].</p>";
            die($msg_errore);
        }

        return true;
    }

    //--per la connessione al database
    public function connect() {
        if(!isset($this->conn)) {
            $this->conn = new mysqli($this->hostname, $this->username, $this->password, $this->db_name);
            if($this->checkConnection()) {
                $this->conn->autocommit(TRUE);
            }
        }
    }

    public function checkQuery() {
        if($this->lastQuery) {
            return true;
        } else {
            return false;
        }
    }

    public function getAffectedRows() {
        if(!isset($this->conn)) $this->connect();

        return $this->conn->affected_rows; //restituisce il numero delle righe affette dalla query
    }

    //--per inviare/annullare le query eseguite (se autocommit e' false)
    public function commitQueries() {
        if(!isset($this->conn)) $this->connect();

        $this->conn->commit();
    }

    public function rollbackQueries() {
        if(!isset($this->conn)) $this->connect();

        $this->conn->rollback();
    }

    public function freeResult() {
        $this->lastQuery->free();
        $this->lastQueryResult = array();
    } //Svuota lastQuery

    //--per gestire le query (inviarle e visualizzare risultati)
    public function sendQuery($string) {
        if(!isset($this->conn)) $this->connect();

        if(isset($this->lastQuery)) $this->freeResult();

        $this->lastQuery = $this->conn->query($string);

        if($this->lastQuery && $this->getAffectedRows() > 0) {
            $i=0;
            while($row = $this->lastQuery->fetch_assoc()) {
                $this->lastQueryResult[$i] = $row;
                $i++;
            }
        } else {
            return false;
        }
    }
	
    public function queryDB($string) {
        if(!isset($this->conn)) $this->connect();

        $this->sendQuery($string);
        
        if($this->checkQuery() && $this->getAffectedRows() > 0) {
            if(sizeof($this->lastQueryResult) > 0) { //la query ha restituito un risultato
                return $this->lastQueryResult;
            } else { //la query ha semplicemente modificato il DB (es: INSERT, UPDATE, DELETE)
                return true;
            }
        } else {
            return false; //non ha avuto alcun risultato la query
        }
    }
    
    public function disconnect() {
        if(!isset($this->conn)) $this->connect();

        $this->conn->close();
    }
}

?>
