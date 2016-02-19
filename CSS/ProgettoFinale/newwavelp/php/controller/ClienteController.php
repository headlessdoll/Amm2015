<?php

include_once 'BaseController.php';
include_once basename(__DIR__) . '/../model/Album_ordineFactory.php';
include_once basename(__DIR__) . '/../model/AlbumFactory.php';
include_once basename(__DIR__) . '/../model/OrdineFactory.php';

/**
 * Controller che gestisce la modifica dei dati dell'applicazione relativa ai 
 * Clienti da parte di utenti con ruolo Cliente 
 */
class ClienteController extends BaseController {

    
    /**
     * Costruttore
     */
    public function __construct() {
        parent::__construct();
    }
    

    /**
     * Metodo per gestire l'input dell'utente. 
     * @param type $request la richiesta da gestire
     */
    public function handleInput(&$request) {

        // creo il descrittore della vista
        $vd = new ViewDescriptor();


        // imposto la pagina
        $vd->setPagina($request['page']);

        // gestion dei comandi
        // tutte le variabili che vengono create senza essere utilizzate 
        // direttamente in questo switch, sono quelle che vengono poi lette
        // dalla vista, ed utilizzano le classi del modello

        if (!$this->loggedIn()) {
            // utente non autenticato, rimando alla home

            $this->showLoginPage($vd);
        } else {
            // utente autenticato
            $user = UserFactory::instance()->cercaUtentePerId(
                            $_SESSION[BaseController::user], $_SESSION[BaseController::role]);


            // verifico quale sia la sottopagina della categoria
            // Docente da servire ed imposto il descrittore 
            // della vista per caricare i "pezzi" delle pagine corretti
            // tutte le variabili che vengono create senza essere utilizzate 
            // direttamente in questo switch, sono quelle che vengono poi lette
            // dalla vista, ed utilizzano le classi del modello
            if (isset($request["subpage"])) {
                switch ($request["subpage"]) {

                    //ad ogni pagina viene associato un valore nella variabile $_SESSION['pagina'] per fare in modo che nel menu
                    //a sinistra appaiano informazioni guida differenti a seconda della pagina che si sta visualizzando
                    
                    // modifica dei dati anagrafici per le consegne a domicilio
                    case 'anagrafica':
                        $_SESSION['pagina'] = 'anagrafica.php';   
                        $vd->setSottoPagina('anagrafica');
                        break;

                    // Ordinazione delle Album con scelta dei quantità e dimensioni
                    case 'ordina':                        
                        $_SESSION['pagina'] = 'ordina.php';
                        $album = AlbumFactory::instance()->getAlbum();
                        $orari = OrarioFactory::instance()->getOrari();
                        $vd->setSottoPagina('ordina');
                        break;

                    // visualizzazione degli ordini effettuati precedentemente
                    case 'elenco_ordini':
                        $_SESSION['pagina'] = 'elenco_ordini.php'; 
                        $ordini = OrdineFactory::instance()->getOrdiniPerIdCliente($user);
                        $vd->setSottoPagina('elenco_ordini');
                        break;                    

                    // visualizzaza come raggiungere e i vari contatti della Albumria
                    case 'contatti':
                        $_SESSION['pagina'] = 'contatti.php';  
                        $vd->setSottoPagina('contatti');
                        break;
                    
                    default:
                        $_SESSION['pagina'] = 'home.php';    
                        $vd->setSottoPagina('home');
                        break;
                }
            }



            // gestione dei comandi inviati dall'utente
            if (isset($request["cmd"])) {
                // abbiamo ricevuto un comando
                switch ($request["cmd"]) {

                    // logout
                    case 'logout':
                        $this->logout($vd);
                        break;
                        
                    case 'procedi_ordine':
                        //si verifica che i dati inseriti dall'utente relativamente a quantità e dimensione Album siano nel formato
                        //corretto e in numero accettabile. Successivamente ad ogni tipologia di prodotto viene associato un 
                        //nuovo record nella tabella Album_ordini in cui viene indicato, oltre al numero ordine relativo, anche
                        //la quantità di Album di quel determinato tipo che si vogliono ordinare.
                        $vd->setSottoPagina('conferma_ordine');
                        $msg = array();
                        //carico un array con tutti gli id delle Album ordinabili
                        $idAlbum = AlbumFactory::instance()->getIdAlbum();
                        
                        //verifico se i valori inseriti dall'utente sono corretti e conto quante Album sono state ordinate in totale
                        $nAlbum = $this->validaForm($idAlbum, $request);
                        $flagOrario = false;
                        
                        //creo un nuovo ordine attualmente formato solo dall'id (che è anche l'ulitmo disponibile)
                        $ordine = new Ordine();
                        $ordine->setId(OrdineFactory::instance()->getLastId());
                        $ordineId = $ordine->getId();
                        
                        //se il numero di Album ordinate è accettabile (>0) verifico se la fascia oraria richiesta è a sua volta disponibile
                        //per quel quantitativo di Album. se non lo è assegno quella disponibile piu vicina
                        if($nAlbum){
                                
                            $orari = OrarioFactory::instance()->getOrariSuccessivi($request['orario']);  
                            foreach ($orari as $orario) {
                                if((Album_ordineFactory::instance()->getNAlbumPerOrario($orario->getId())+$nAlbum) <= $orario->getOrdiniDisponibili()){
                                    //var_dump("Album per orario ".Album_ordineFactory::instance()->getNAlbumPerOrario($orario->getId()));
                                    $ordine->setOrario($orario->getId());
                                    $flagOrario = true;
                                    break;
                                }else $ordine->setOrario(NULL);
                            }
                        }
                        else{
                            $msg[]='<li>I valori inseriti non sono validi. Ordine annullato</li>';
                            $vd->setSottoPagina('ordina');    
                            $this->creaFeedbackUtente($msg, $vd, "");
                            $this->showHomeUtente($vd);
                        break;                            
                        }
                        
                   
                        
                    case 'dettaglio':
                        //mi permette di vedere i dettagli relativi a un ordine : elenco Album, quantità, prezzi singoli e totali
                        //e richieste di consegne 
                        $_SESSION['pagina'] = 'dettaglio_ordine.php'; 
                        $ordineId = filter_var($request['ordine'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                        $ordine = OrdineFactory::instance()->getOrdine($ordineId);
                        $POs = Album_ordineFactory::instance()->getPOPerIdOrdine($ordine);
                        $vd->setSottoPagina('dettaglio_ordine');
                        $this->showHomeUtente($vd);
                        break; 
                    
                    case 'conferma_ordine':
                        //dopo il riepilogo dell'ordine il ciente puo' decidere se confermarlo o...
                        $msg = array();
                        $ordineId = $request['ordineId'];                        
                        $this->creaFeedbackUtente($msg, $vd, "Ordine ".$ordineId." creato con successo.");
                        $vd->setSottoPagina('home');
                        $this->showHomeUtente($vd);                        
                        break;
                    
                    case 'cancella_ordine':
                        //...cancella PO e ordine
                        $msg = array();
                        $ordineId = $request['ordineId'];
                        $p = Album_ordineFactory::instance()->cancellaPO($ordineId);
                        $o = OrdineFactory::instance()->cancellaOrdine($ordineId);
                        if ($p && $o) {
                            $this->creaFeedbackUtente($msg, $vd, "Ordine ".$ordineId." cancellato.");
                        }else $this->creaFeedbackUtente('<li>Errore cancellazione</li>', $vd, "");
                        $vd->setSottoPagina('home');
                        $this->showHomeUtente($vd);
                        break;
                        
                    
                    case 'indirizzo':
                        //aggiornamento indirizzo - l'indirizzo viene utilizzato per le consegne a domicilio
                        $msg = array();
                        $this->aggiornaIndirizzo($user, $request, $msg);
                        $this->creaFeedbackUtente($msg, $vd, "Indirizzo aggiornato");
                        $this->showHomeCliente($vd);
                        break;


                    
                    case 'password':
                        // cambio password
                        $msg = array();
                        $this->aggiornaPassword($user, $request, $msg);
                        $this->creaFeedbackUtente($msg, $vd, "Password aggiornata");
                        $this->showHomeCliente($vd);
                        break;


                    default : $this->showHomeUtente($vd);
                }
            } else {
                // nessun comando
                $user = UserFactory::instance()->cercaUtentePerId($_SESSION[BaseController::user], $_SESSION[BaseController::role]);
                $this->showHomeUtente($vd);
            }
            
            
        }

        // includo la vista
        require basename(__DIR__) . '/../view/master.php';
        
        
    }
 
    //funzione che permette di verificare se i valori inseriti nei campi delle quantità di Album da ordinare sono validi
    //e conta quante Album sono state richieste in totale
    private function validaForm($idAlbum , $request) {
         $valide = 0;
         foreach($idAlbum as $idAlbum){
            $quantitaN = filter_var($request[$idAlbum.'normali'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            if (isset($quantitaN) && ($quantitaN != 0)) $valide+=$quantitaN;
            $quantitaG = filter_var($request[$idAlbum.'giganti'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            if (isset($quantitaG) && ($quantitaG != 0)) $valide+=$quantitaG;   
         }
         
         return $valide;
    }

}

?>
