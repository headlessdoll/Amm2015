<?php

include_once 'Cliente.php';

class Ordine {
    
    private $id;
    private $domicilio;
    private $prezzo;
    private $stato;
    private $data;
    private $cliente;
    private $admin;
   
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
    
    public function setDomicilio($domicilio) {
        $this->domicilio = $domicilio;
        return true;
    }

    public function getDomicilio() {
        return $this->domicilio;
    }
    
    public function setPrezzo($prezzo) {
        $this->prezzo = $prezzo;
        return true;
    }

    public function getPrezzo() {
        return $this->prezzo;
    }    
    public function getStato() {
        return $this->stato;
    }

    public function setStato($stato) {
        $this->stato = $stato;
    }
    
    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        $this->data = $data;
    }    
    public function getCliente() {
        return $this->cliente;
    }

    public function setCliente($cliente_id) {
        $this->cliente = $cliente_id;
    }
    
    public function setAmin($admin_id) {
        $this->admin = $admin_id;
        return true;
    }

    public function getAdmin() {
        return $this->admin;
    } 
    
    
}