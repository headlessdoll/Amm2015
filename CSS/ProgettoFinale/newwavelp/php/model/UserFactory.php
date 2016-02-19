<?php

include_once 'User.php';
include_once 'Admin.php';
include_once 'Cliente.php';
include_once 'Db.php';


/**
 * Classe per la creazione degli utenti del sistema
 *
 * @author Davide Spano
 */
class UserFactory {

    private static $singleton;

    private function __constructor() {
        
    }

    /**
     * Restiuisce un singleton per creare utenti
     * @return \UserFactory
     */
    public static function instance() {
        if (!isset(self::$singleton)) {
            self::$singleton = new UserFactory();
        }

        return self::$singleton;
    }

    /**
     * Carica un utente tramite username e password
     * @param string $username
     * @param string $password
     * @return \User|\Admin|\Cliente
     */
    public function caricaUtente($username, $password) {


        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[loadUser] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        // cerco prima nella tabella clienti
        $query = "SELECT * FROM clienti WHERE  username =  ? AND  password =  ?";
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[loadUser] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }

        if (!$stmt->bind_param('ss', $username, $password)) {
            error_log("[loadUser] impossibile" .
                    " effettuare il binding in input ");
            $mysqli->close();
            return null;
        }

        $admin = self::caricaClienteDaStmt($stmt);
        if (isset($admin)) {
            // ho trovato uno studente
            $mysqli->close();
            return $admin;
        }

        // ora cerco un addetto agli ordini
        $query = "select * from admin where username = ? and password = ?";

        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[loadUser] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }

        if (!$stmt->bind_param('ss', $username, $password)) {
            error_log("[loadUser] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $admin = self::caricaAdminDaStmt($stmt);
        if (isset($admin)) {
            // ho trovato un docente
            $mysqli->close();
            return $admin;
        }
    }

    
    private function caricaClienteDaStmt(mysqli_stmt $stmt) {

        if (!$stmt->execute()) {
            error_log("[caricaClienteDaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['admin_id'], 
                $row['admin_username'], 
                $row['admin_password'],                
                $row['admin_nome'], 
                $row['admin_cognome'], 
                $row['admin_via'],
                $row['admin_civico'],
                $row['admin_cap'],
                $row['admin_citta'],
                $row['admin_telefono']);
        
        if (!$bind) {
            error_log("[caricaClienteDaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        if (!$stmt->fetch()) {
            return null;
        }

        $stmt->close();

        return self::creaClienteDaArray($row);
    }
    /**
     * Restituisce un array con i addetti agli ordini presenti nel sistema
     * @return array
     */
    public function &getListaClienti() {
        $clienti = array();
        $query = "select * from clienti";
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getListaClienti] impossibile inizializzare il database");
            $mysqli->close();
            return $clienti;
        }
        $result = $mysqli->query($query);
        if ($mysqli->errno > 0) {
            error_log("[getListaClienti] impossibile eseguire la query");
            $mysqli->close();
            return $clienti;
        }

        while ($row = $result->fetch_array()) {
            $clienti[] = self::creaClienteDaArray($row);
        }
        //togliere?
        $mysqli->close();
        return $clienti;
    }

    /**
     * Crea un cliente da una riga del db
     * @param type $row
     * @return \Cliente
     */
    public function creaClienteDaArray($row) {
        $admin = new Cliente();
        $admin->setId($row['admin_id']); 
        $admin->setUsername($row['admin_username']);
        $admin->setPassword($row['admin_password']);        
        $admin->setNome($row['admin_nome']);    
        $admin->setCognome($row['admin_cognome']);
        $admin->setVia($row['admin_via']);
        $admin->setCivico($row['admin_civico']);
        $admin->setCitta($row['admin_citta']);                  
        $admin->setCap($row['admin_cap']);
        $admin->setTelefono($row['admin_telefono']);        
        $admin->setRuolo(User::Cliente);

        return $admin;
    }
    
    /**
     * Restituisce la lista degli clienti presenti nel sistema
     * @return array
     */
    public function &getListaAdmin() {
        $admin = array();
        $query = "select * from admin ";
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getListaAdmin] impossibile inizializzare il database");
            $mysqli->close();
            return $admin;
        }
        $result = $mysqli->query($query);
        if ($mysqli->errno > 0) {
            error_log("[getListaAdmin] impossibile eseguire la query");
            $mysqli->close();
            return $admin;
        }

        while ($row = $result->fetch_array()) {
            $admin[] = self::creaAdminDaArray($row);
        }

        return $admin;
    }




    /**
     * Crea un addetto ordini da una riga del db
     * @param type $row
     * @return \admin
     */
    public function creaAdminDaArray($row) {
        $admin = new admin();
        $admin->setId($row['admin_id']);
        $admin->setNome($row['admin_nome']);
        $admin->setCognome($row['admin_cognome']);
        $admin->setVia($row['admin_via']);
        $admin->setCivico($row['admin_civico']);
        $admin->setCitta($row['admin_citta']);                  
        $admin->setCap($row['admin_cap']);
        $admin->setTelefono($row['admin_telefono']);
        $admin->setRuolo(User::admin);
        $admin->setUsername($row['admin_username']);
        $admin->setPassword($row['admin_password']);

        return $admin;
    }

    /**
     * Salva i dati relativi ad un utente sul db
     * @param User $user
     * @return il numero di righe modificate
     */
    public function salva(User $user) {
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[salva] impossibile inizializzare il database");
            $mysqli->close();
            return 0;
        }

        $stmt = $mysqli->stmt_init();
        $count = 0;
        switch ($user->getRuolo()) {
            case User::Cliente:
                $count = $this->salvaCliente($user, $stmt);
                break;
            case User::Admin:
                $count = $this->salvaAdmin($user, $stmt);
        }

        $stmt->close();
        $mysqli->close();
        return $count;
    }

    /**
     * Rende persistenti le modifiche all'anagrafica di uno studente sul db
     * @param Cliente $s lo studente considerato
     * @param mysqli_stmt $stmt un prepared statement
     * @return int il numero di righe modificate
     */
    private function salvaCliente(Cliente $c, mysqli_stmt $stmt) {
        $query = " UPDATE clienti SET 
                    password = ?,
                    nome = ?,
                    cognome = ?,
                    via = ?,
                    civico = ?,
                    citta = ?,
                    cap = ?,
                    telefono = ?
                    WHERE clienti.id = ?";
        
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[salvaCliente] impossibile" .
                    " inizializzare il prepared statement");
            return 0;
        }

        if (!$stmt->bind_param('ssssissii',
                $c->getPassword(),
                $c->getNome(),
                $c->getCognome(),
                $c->getVia(), 
                $c->getCivico(),
                $c->getCitta(),
                $c->getCap(),
                $c->getTelefono(),
                $c->getId())) {
            error_log("[salvaCliente] impossibile" .
                    " effettuare il binding in input 2");
            return 0;
        }

        if (!$stmt->execute()) {
            error_log("[caricaIscritti] impossibile" .
                    " eseguire lo statement");
            return 0;
        }

        return $stmt->affected_rows;
    }
    
    /**
     * Rende persistenti le modifiche all'anagrafica di un docente sul db
     * @param Admin $d il docente considerato
     * @param mysqli_stmt $stmt un prepared statement
     * @return int il numero di righe modificate
     */
    private function salvaAdmin(admin $d, mysqli_stmt $stmt) {
        $query = " update admin set 
                    password = ?,
                    nome = ?,
                    cognome = ?,
                    via = ?,
                    civico = ?,
                    citta = ?,
                    cap = ?,
                    telefono = ?,
                    where admin.id = ?
                    ";
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[salvaCliente] impossibile" .
                    " inizializzare il prepared statement");
            return 0;
        }

        if (!$stmt->bind_param('ssssissii', 
                $d->getPassword(), 
                $d->getNome(), 
                $d->getCognome(), 
                $d->getVia(), 
                $d->getCivico(),
                $d->getCitta(),
                $d->getCap(),
                $d->getTelefono(),
                $d->getId())) {
            error_log("[salvaCliente] impossibile" .
                    " effettuare il binding in input");
            return 0;
        }

        if (!$stmt->execute()) {
            error_log("[caricaIscritti] impossibile" .
                    " eseguire lo statement");
            return 0;
        }

        return $stmt->affected_rows;
    }

    /**
     * Carica un docente eseguendo un prepared statement
     * @param mysqli_stmt $stmt
     * @return null
     */
    private function caricaAdminDaStmt(mysqli_stmt $stmt) {

        if (!$stmt->execute()) {
            error_log("[caricaAdminDaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['admin_id'], 
                $row['admin_username'], 
                $row['admin_password'],                
                $row['admin_nome'], 
                $row['admin_cognome'], 
                $row['admin_via'],
                $row['admin_civico'],
                $row['admin_cap'],
                $row['admin_citta'],
                $row['admin_telefono']);
        if (!$bind) {
            error_log("[caricaAdminDaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        if (!$stmt->fetch()) {
            return null;
        }

        $stmt->close();

        return self::creaAdminDaArray($row);
    }
    
    /**
     * Cerca un utente per id
     * @param int $id
     * @return  un oggetto Cliente nel caso sia stato trovato,
     * NULL altrimenti
     */
    public function cercaUtentePerId($id, $role) {
        $intval = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if (!isset($intval)) {
            return null;
        }
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[cercaUtentePerId] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        switch ($role) {
            case User::Cliente:
                $query = "select  * from clienti where id = ?";
                $stmt = $mysqli->stmt_init();
                $stmt->prepare($query);
                if (!$stmt) {
                    error_log("[cercaUtentePerId] impossibile" .
                            " inizializzare il prepared statement");
                    $mysqli->close();
                    return null;
                }

                if (!$stmt->bind_param('i', $intval)) {
                    error_log("[cercaUtentePerId] impossibile" .
                            " effettuare il binding in input");
                    $mysqli->close();
                    return null;
                }

                return self::caricaClienteDaStmt($stmt);
                break;

            case User::Admin:
                $query = "select * from admin where id = ?";

                $stmt = $mysqli->stmt_init();
                $stmt->prepare($query);
                if (!$stmt) {
                    error_log("[cercaUtentePerId] impossibile" .
                            " inizializzare il prepared statement");
                    $mysqli->close();
                    return null;
                }

                if (!$stmt->bind_param('i', $intval)) {
                    error_log("[loadUser] impossibile" .
                            " effettuare il binding in input");
                    $mysqli->close();
                    return null;
                }

                $toRet =  self::caricaAdminDaStmt($stmt);
                $mysqli->close();
                return $toRet;
                break;

            default: return null;
        }
                
    }
    
    /*
    * @param $id id del cliente da ricercare
    * @return dati del cliente corrispondenti all'id considerato
    */    
    public function getClientePerId($id) {
       $admin = array();
        $query = "SELECT * FROM clienti WHERE clienti.id = ? ";          
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getClientePerId] impossibile inizializzare il database");
            $mysqli->close();
            return $admin;
        }

        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getClientePerId] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return $admin;
        }

        if (!$stmt->bind_param('i', $id)) {
            error_log("[getClientePerId] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return $admin;
        } 
        
        $admin = self::caricaClienteDaStmt($stmt);

        $mysqli->close();
        return $admin;        
                
    }
}

?>
