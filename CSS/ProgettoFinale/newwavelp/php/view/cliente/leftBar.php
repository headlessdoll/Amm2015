<h2>Informazioni</h2>
<p> 
    Benvenuto <?= $user->getNome()." ". $user->getCognome()?>.
</p>
<p>
<?
if(!isset($_SESSION['pagina'])) $_SESSION['pagina'] = 'home.php';
switch ($_SESSION['pagina']) {
    case 'home.php':?>
        <p>
            Seleziona una voce dal menù.
        </p>
       <?break;
    case 'anagrafica.php':?>
        <p>
            Indirizzo: Questo è l'indirizzo a cui verrà consegnato il tuo ordine.   
        </p>
        <p>
            Password: La password può essere modificata in qualsiasi momento.
        </p>
       <?break;    
    case 'ordina.php':?>
        <p>
            Inserire la quantità di album che si vogliono acquistare. L'ordine viene inviato solo dopo la conferma a seguito del riepilogo.
        </p>
        <p>
            *verifica sezione Anagrafica
        </p>        
       <?break;  
    case 'elenco_ordini.php':?>
        <p>
            Elenco degli ordini effettuati.
        </p>
       <?break;   
    case 'dettaglio_ordine.php':?>
        <p>
            Dettaglio dei prezzi ed elenco album relativi all'ordine selezionato.
        </p>
       <?break;      
   default:
       break;
}
?>
</p>