<?php

include_once 'Album_ordine.php';
include_once 'Album.php';
include_once 'Ordine.php';

class Album_ordineFactory {

    private static $singleton;

    private function __constructor() {
        
    }

    /**
     * Restiuisce un singleton per creare Modelli
     * @return ModelloFactory
     */
    public static function instance() {
        if (!isset(self::$singleton)) {
            self::$singleton = new Album_ordineFactory();
        }

        return self::$singleton;
    }
    
    /*
     * La funzione crea un nuovo PO
  */
    public function creaPO($idAlbum, $idOrdine, $quantita) {
        $query = "INSERT INTO `album_ordini`(`album_id`, `ordine_id`, `quantita`) VALUES (?, ?, ?)";

        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[creaPO] impossibile inizializzare il database");
            return 0;
        }

        $stmt = $mysqli->stmt_init();

        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[creaPO] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return 0;
        }

        if (!$stmt->bind_param('iiis', $idAlbum, $idOrdine, $quantita)) {
            error_log("[creaPO] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return 0;
        }

       if (!$stmt->execute()) {
  
            error_log("[creaPO] impossibile" .
                    " eseguire lo statement");
            $mysqli->close();
            return 0;
        }
        $mysqli->close();
        return $stmt->affected_rows;
    }

    /*
     * La funzione cancella PO
    * @param $id id dell'ordine considerato
    * @return il numero di righe cancellate
    */    
    public function cancellaPO($id){
        $query = "delete from album_ordini where ordine_id = ?";
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[cancellaPO] impossibile inizializzare il database");
            return false;
        }

        $stmt = $mysqli->stmt_init();

        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[cancellaPO] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return false;
        }

        if (!$stmt->bind_param('i', $id)){
        error_log("[cancellaPO] impossibile" .
                " effettuare il binding in input");
        $mysqli->close();
        return false;
        }

        if (!$stmt->execute()) {
            error_log("[cancellaPO] impossibile" .
                    " eseguire lo statement");
            $mysqli->close();
            return false;
        }

        $mysqli->close();
        return $stmt->affected_rows;        
    }
    

    /*
    * La funzione fornisce il prezzo di un insieme di album
    * @param $PO PO di riferimento
    * @return il prezzo dell'insieme di album
    */    
    public function getPrezzoPerAlbum(Album_ordine $PO){
        $query = "SELECT
                album_ordini.quantita quantita,
                album.prezzo album_prezzo
                
                FROM album_ordini
                JOIN album ON album_ordini.album_id = album.id
                WHERE album_ordini.id = ?";

        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getPrezzoPerAlbum] impossibile inizializzare il database");
            $mysqli->close();
            return false;
        }

        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getPrezzoPerAlbum] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return false;
        }

        if (!$stmt->bind_param('i', $PO->getId())) {
            error_log("[getPrezzoPerAlbum] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return false;
        }

        $prezzo = self::caricaPrezzoPODaStmt($stmt);

        $mysqli->close();
        return $prezzo;         
    
    }

    /*
    * La funzione calcola il prezzo totale dell'ordine senza aggiungere i costi del trasporto a domicilio
    * @param $ordine ordine di riferimento
    * @return prezzo dell'ordine
    */       
    public function getPrezzoParziale(Ordine $ordine){
        
        $query = "SELECT
                album_ordini.quantita quantita,
                album.prezzo album_prezzo
                FROM album_ordini
                JOIN album ON album_ordini.album_id = album.id
                WHERE album_ordini.ordine_id = ?";

        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getPrezzoParziale] impossibile inizializzare il database");
            $mysqli->close();
            return true;
        }

        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getPrezzoParziale] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return false;
        }

        if (!$stmt->bind_param('i', $ordine->getId())) {
            error_log("[getPrezzoParziale] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return false;
        }

        $prezzo = self::caricaPrezzoPODaStmt($stmt);

        $mysqli->close();
        return $prezzo;        
    
   

        $row = array();
        $bind = $stmt->bind_result(
                $row['quantita'],
                $row['album_prezzo']);

        if (!$bind) {
            error_log("[caricaPrezzoPODaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }
        $prezzo = 0;
        while ($stmt->fetch()) {
            if($prezzo += $row['quantita'] * $row['album_prezzo']){
            }else $prezzo += $row['quantita'] * ($row['album_prezzo']+($row['album_prezzo']*$perc));
        }

        $stmt->close();

        return $prezzo;
    }         

    /*
    * @param $id id dell'ordine di riferimento
    * @return la quantità di album relativi all'ordine di riferimento
    */    
    public function getNAlbum($id){
        $query = "SELECT 
            album_ordini.quantita quantita 
            FROM album_ordini 
            WHERE album_ordini.ordine_id = ?";

        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getNAlbum] impossibile inizializzare il database");
            $mysqli->close();
            return true;
        }

        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getNAlbum] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }

        if (!$stmt->bind_param('i', $id)) {
            error_log("[getNAlbum] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }

       if (!$stmt->execute()) {
            error_log("[getNAlbum] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result($row['quantita']);

        if (!$bind) {
            error_log("[getNAlbum] impossibile" .
                    " effettuare il binding in output");
            return null;
        }
        $nalbum = 0;
        while ($stmt->fetch()) {
            $nalbum += $row['quantita'];
        }

        $mysqli->close();
        return $nalbum;                
    }

    /*
    * @param $ordine ordine di riferimento
    * @return un determinato record in cui l'id dell'ordine è uguale a quello dato come riferimento
    */     
    public function getPOPerIdOrdine(Ordine $ordine){
        $po = array();
        $query = "SELECT *             
            FROM album_ordini
            WHERE album_ordini.ordine_id = ?";   
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getPOPerIdOrdine] impossibile inizializzare il database");
            $mysqli->close();
            return 0;
        }

        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getPOPerIdOrdine] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return 0;
        }

        if (!$stmt->bind_param('i', $ordine->getId())) {
            error_log("[getPOPerIdOrdine] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return 0;
        }        
        
        $po = self::caricaPODaStmt($stmt);

        $mysqli->close();
        return $po;        
    }    
    
    public function &caricaPODaStmt(mysqli_stmt $stmt) {
        $po = array();
        if (!$stmt->execute()) {
            error_log("[caricaPODaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['albumId'], 
                $row['ordineId'],
                $row['id'],
                $row['quantita']);

        if (!$bind) {
            error_log("[caricaPODaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        while ($stmt->fetch()) {
            $po[] = self::creaPODaArray($row);
        }

        $stmt->close();

        return $po;
    }                
        
    public function creaPODaArray($row) {
        $po = new Album_ordine();
        $po->setAlbum($row['albumId']);
        $po->setOrdine($row['ordineId']);        
        $po->setId($row['id']);
        $po->setQuantita($row['quantita']);           
        return $po;
    }    
}
?>