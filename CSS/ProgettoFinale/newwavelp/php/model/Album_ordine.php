<?php


class Album_ordine {
    private $id;
    
    private $quantita;
    
    private $album;
    
    private $ordine;
    
    
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
    
    public function setQuantita($quantita) {
        $int_options = array("options"=> array("min_range"=>0, "max_range"=>10));
        $intVal = filter_var($quantita, FILTER_VALIDATE_INT, $int_options);
        if (!isset($intVal)) {
            return false;
        }        
        $this->quantita = $quantita;
        return true;
    }

    public function getQuantita() {
        return $this->quantita;
    }

    public function getAlbum() {
        return $this->album;
    }

    public function setAlbum($album_id) {
        $this->album = $album_id;
    }
    
    public function getOrdine() {
        return $this->ordine;
    }

    public function setOrdine($ordine_id) {
        $this->ordine = $ordine_id;
    }
      
}

?>