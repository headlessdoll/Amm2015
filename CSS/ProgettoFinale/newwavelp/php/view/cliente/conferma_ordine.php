<h2>Riepilogo Ordine</h2>
<form action="cliente/conferma_ordine" method="post">
    
 <?    
    $ordine = OrdineFactory::instance()->getOrdine($ordineId);
    $POs = Album_ordineFactory::instance()->getPOPerIdOrdine($ordine);
    
    if($ordine->getDomicilio() == "si") $domicilioSi = true;
    else $domicilioSi = false;
    

?>  
    <input type="hidden" name="ordineId" value="<?= $ordine->getId() ?>" />
    <table>

            <tr>
                <th>Album</th>             
                <th>Quantita</th>
                <th>Prezzo</th>      
                <th>Prezzo TOT</th>                 
            </tr>     

    <?foreach ($POs as $PO) {
            $album = AlbumFactory::instance()->getAlbumPerId($PO->getAlbum());?>
            <tr>
                <td><?= $album->getNome()?></td>
                <td><?= $PO->getDimensione() ?></td>
                <td><?= $PO->getQuantita() ?></td>
                <td><?= (Album_ordineFactory::instance()->getPrezzoPerAlbum($PO)/$PO->getQuantita()) . "€ "?></td>
                <td><?= Album_ordineFactory::instance()->getPrezzoPerAlbum($PO) . "€ "?></td>                               
                   
            </tr>
    <? } ?>    
             <tr>
                <th>Fascia oraria</th> 
                <th>Domicilio</th>
                <th>Prezzo Domicilio</th>                
                <th>Prezzo Album</th>
                <th>Prezzo Totale</th>                     
            </tr>       
            <tr>
                <td><?= OrdineFactory::instance()->getValoreOrario($ordine->getOrario()) ?></td>
                <td><? if($domicilioSi){?>si<? } else {?>no<? } ?></td>            
                <td><? if($domicilioSi){?>1.5<? } else {?>0<? } ?></td>
                <td><?= Album_ordineFactory::instance()->getPrezzoParziale($ordine) . "€ " ?></td>                 
                <td><?= OrdineFactory::instance()->getPrezzoTotale($ordine) . "€ " ?></td>                 
            </tr>
    </table>        
    <button type="submit" name="cmd" value="conferma_ordine">Conferma</button>
    <button type="submit" name="cmd" value="cancella_ordine">Cancella</button>
    </form>