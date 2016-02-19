<h2>Informazioni</h2>
<p> 
     Benvenuto <?= $user->getNome()." ". $user->getCognome()?>.
</p>

<?
if(!isset($_SESSION['pagina'])) $_SESSION['pagina'] = 'home.php';
switch ($_SESSION['pagina']) {
    case 'home.php':?>
        <p>
            Seleziona una voce dal menù.
        </p>
       <?break;
    case 'gestione_ordini.php':?>
        <p>
            Elenco degli ordini richiesti oggi e non ancora pagati. 
        </p>
        <p>
            E' possibile visionare informazioni aggiuntive su ogni ordine non ancora pagato.
        </p>        
       <?break;   
    case 'ricerca_ordini.php':?>
        <p>
            Ricerca tutti gli ordini per data.
        </p>
       <?break;
    case 'dettaglio_ordine.php':?>
        <p>
            Dettaglio dei prezzi ed elenco album.
        </p>
       <?break;   
    case 'anagrafica.php':?>
        <p>
            I dati anagrafici presenti in questa pagina e non sono modificabili.
        </p>        
       <?break;    
   default:
       break;   
}
?>