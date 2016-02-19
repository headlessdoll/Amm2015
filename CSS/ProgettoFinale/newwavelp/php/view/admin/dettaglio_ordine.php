<h2>Dettaglio ordine n°<?=$ordine->getId()?></h2>

<h4>Dati cliente</h4>
<ul>
    <li><strong>Nome:</strong> <?= $cliente->getNome() ?></li>
    <li><strong>Cognome:</strong> <?= $cliente->getCognome() ?></li>
    <li><strong>Telefono:</strong> <?= $cliente->getTelefono() ?></li>
    <li><strong>Indirizzo:</strong> via <?= $cliente->getVia() ?>, <?= $cliente->getCivico() ?> - <?= $cliente->getCap() ?> <?= $cliente->getCitta() ?></li>
</ul>
    <table>

            <tr>
                <th >Album</th>
                <th>Autore</th>                
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
                <th>Domicilio</th>
                <th>Prezzo Consegna</th>                
                <th>Prezzo Album</th>
                <th>Prezzo Totale</th>                     
            </tr>       
            <tr>
                <td><?= OrdineFactory::instance()->getValoreOrario($ordine->getOrario()) ?></td>           
                <td><? if($ordine->getDomicilio() == "si"){?>si<? } else {?>no<? } ?></td>            
                <td><? if($ordine->getDomicilio() == "si"){?>5€<? } else {?>0€<? } ?></td>
                <td><?= Album_ordineFactory::instance()->getPrezzoParziale($ordine) . "€ "?></td>                 
                <td><?= OrdineFactory::instance()->getPrezzoTotale($ordine) . "€ "?></td>                 
            </tr>
    </table>
