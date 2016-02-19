<?php

include_once 'controller/BaseController.php';
include_once 'controller/ClienteController.php';
include_once 'controller/AdminController.php';

FrontController::dispatch($_REQUEST); //punto di accesso

/**classe che controlla il punto di accesso**/

class FrontController{
	
	
	
	public static function dispatch(&$request){
		
		session_start(); //inizio sessione
		if (isset($request["page"])) {
			
			 switch ($request["page"]) {
				 case "login":
                    // la pagina di login e' accessibile a tutti,
                    // la facciamo gestire al BaseController
                    $controller = new BaseController();
                    $controller->handleInput($request);
                    break;
					
					//cliente
					 case 'cliente':
                    // la pagina degli cliente e' accessibile solo al cliente
                    // il controllo viene fatto dal controller apposito
                    $controller = new ClienteController();
                    if (isset($_SESSION[BaseController::role]) &&
                        $_SESSION[BaseController::role] != User::Cliente) {
                        self::write403();
                    }
                    $controller->handleInput($request);
                    break;
					
					//admin
					 // docente
                case 'admin':
                    // la pagina dell'admin e' accessibile solo all'admin
                    // il controllo viene fatto dal controller apposito
                    $controller = new AdminController();
                    if (isset($_SESSION[BaseController::role]) &&
                        $_SESSION[BaseController::role] != User::Admin)  {
                        self::write403();
                    }
                    $controller->handleInput($request);
                    break;

                default:
                    self::write404();
                    break;
            }
        } else {
            self::write404();
        }
    }
	 /**
     * Crea una pagina di errore quando il path specificato non esiste
     */
    public static function write404() {
        // impostiamo il codice della risposta http a 404 (file not found)
        header('HTTP/1.0 404 Not Found');
        $titolo = "File non trovato!";
        $messaggio = "La pagina che hai richiesto non &egrave; disponibile";
        include_once('error.php');
        exit();
    }

    /**
     * Crea una pagina di errore quando l'utente non ha i privilegi 
     * per accedere alla pagina
     */
    public static function write403() {
        // impostiamo il codice della risposta http a 404 (file not found)
        header('HTTP/1.0 403 Forbidden');
        $titolo = "Accesso negato";
        $messaggio = "Non hai i diritti per accedere a questa pagina";
        $login = true;
        include_once('error.php');
        exit();
    }
	
	}
