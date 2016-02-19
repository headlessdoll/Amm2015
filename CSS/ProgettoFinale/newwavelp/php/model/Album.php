<?php


class Album {
    
    private $id;
    
    private $nome;
    
    private $autore;
    
    private $prezzo;

    
   public function __construct() {
        
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $intVal = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if (!isset($intVal)) {
            return false;
        }
        $this->id = $intVal;
    }
    
    public function setNome($nome) {
        $this->nome = $nome;
        return true;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setAutore($autore) {
        $this->autore = $autore;
        return true;
    }

    public function getAutore() {
        return $this->autore;
    }
    
    public function getPrezzo() {
        return $this->prezzo;
    }

    public function setPrezzo($prezzo) {
        $this->prezzo = $prezzo;
    }
    
}
?>