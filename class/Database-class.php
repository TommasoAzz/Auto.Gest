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
    private $lastQueryRes; //array dei risultati dell'ultima query eseguita

    //metodi
    //--costruttore
    public function __construct($host, $un, $psw, $db) {
        $this->hostname = $host;
        $this->username = $un;
        $this->password = $psw;
        $this->db_name = $db;
        $this->lastQueryRes = array();
    }

    //--per il controllo (privati)
    private function checkConnection() {
        if ($this->conn->connect_errno > 0) {
            die("<p>Errore nella connessione al database [" . $this->conn->connect_error . "].");
            return false;
        } else {
            return true;
        }
    }

    public function checkQuery() {
        if ($this->lastQuery) {
            return true;
        } else {
            return false;
        }
    }

    //--per la connessione al database
    public function connect() {
        if ($this->conn == null) {
            $this->conn = new mysqli($this->hostname, $this->username, $this->password, $this->db_name);
            if ($this->checkConnection()) {
                $this->conn->autocommit(TRUE);
            }
        }
    }

    public function close() {
        $this->conn->close();
    }

    public function getConn() {
        return $this->conn;
    }

    //--per gestire le query (inviarle e visualizzare risultati
    public function doQuery($string) {
        $this->lastQuery = $this->conn->query($string);
        if($this->lastQuery && $this->getAffectedRows()!=0) {
            while($row = $this->lastQuery->fetch_assoc()) {
                $this->lastQueryRes = $row;
            }
        } else {
            return false;
        }
    }

    public function getResult($needed_field) {
        if($this->lastQuery !== false) {
            return $this->lastQueryRes[$needed_field];
        }
    }

    public function emptyQuery() {
        $this->lastQuery->free();
    } //Svuota lastQuery
	
    public function qikQuery($string) {
        $this->lastQuery = $this->conn->query($string);
        if($this->lastQuery && $this->getAffectedRows()!=0) {
            $result=array();
            while ($row = $this->lastQuery->fetch_assoc()) {
                $result[] = $row;
            }
            $this->emptyQuery();
            return $result;
        } else {
            return false; //non ha avuto alcun risultato la query
        }
    }
    
    public function sendQuery($string) {
        $this->conn->query($string);
        if($this->getAffectedRows()!==0) return true;
        else return false;
    }

    public function getAffectedRows() {
        return $this->conn->affected_rows; //restituisce il numero delle righe affette dalla query
    }

    //--per inviare/annullare le query eseguite (se autocommit e' false)
    public function sendQueries() {
        $this->conn->commit();
    }

    public function rollbackQueries() {
        $this->conn->rollback();
    }

}

?>
