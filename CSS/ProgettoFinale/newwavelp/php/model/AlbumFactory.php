<?php

include_once 'Db.php';
include_once 'Album.php';

class AlbumFactory {
    
    private static $singleton;

    private function __constructor() {
        
    }

    /**
     * Restiuisce un singleton per creare Modelli
     */
    public static function instance() {
        if (!isset(self::$singleton)) {
            self::$singleton = new AlbumFactory();
        }

        return self::$singleton;
    }
    
    /*
    * @return tutti gli album all'interno di una tabella
    */
    public function &getAlbum() {

        $album = array();
        $query = "select * from album";
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getAlbum] impossibile inizializzare il database");
            $mysqli->close();
            return 0;
           
        }
        $result = $mysqli->query($query);
        if ($mysqli->errno > 0) {
            error_log("[getMAlbum] impossibile eseguire la query");
            $mysqli->close();
            return 0;
         
        }

        while ($row = $result->fetch_array()) {
            $album[] = self::creaAlbumDaArray($row);
        }

        $mysqli->close();
        return $album;
    }    
    
    private function creaAlbumDaArray($row) {
        $album = new Album();
        $album->setId($row['id']);
        $album->setNome($row['nome']);
        $album->setAutore($row['album']);
        $album->setPrezzo($row['prezzo']);
        return $album;
    }
    
    /*
    * @param $id id album
    * @return album alla quale corrisponde quel determinato id  
    */
    public function getAlbumPerId($id) {
        $album = array();
        $query = "SELECT * from album WHERE id = ?";
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getAlbumPerId] impossibile inizializzare il database");
            $mysqli->close();
            return 0;
        }

        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getAlbumPerId] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return 0;
        }

        if (!$stmt->bind_param('i', $id)) {
            error_log("[getAlbumPerId] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return 0;
        }  
        
        $album = self::creaAlbumDaStmt($stmt);
        
        $mysqli->close();
        return $album;        
    }
    
    public function &creaAlbumDaStmt(mysqli_stmt $stmt) {
        $album = array();
        if (!$stmt->execute()) {
            error_log("[creaAlbumDaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['id'], 
                $row['nome'],
                $row['autore'],
                $row['prezzo']);

        if (!$bind) {
            error_log("[creaAlbumDaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        while ($stmt->fetch()) {
            $album = self::creaAlbumDaArray($row);
        }
        $stmt->close();

        return $album;
    }     
    /*
    * @return solo gli id di tutti gli album
    */
    public function getIdAlbum() {

        $albumId = array();
        $query = "select 
            album.id id 
                from album";
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getIdAlbum] impossibile inizializzare il database");
            $mysqli->close();
            return 0;
           
        }
        $result = $mysqli->query($query);
        if ($mysqli->errno > 0) {
            error_log("[getIdAlbum] impossibile eseguire la query");
            $mysqli->close();
            return 0;
         
        }
        $i = 0;
        while ($row = $result->fetch_array()) {
            $albumId[$i] = $row['id'];
            $i++;
        }

        $mysqli->close();
        return $albumId;
    }  
    
    
    
}

?>