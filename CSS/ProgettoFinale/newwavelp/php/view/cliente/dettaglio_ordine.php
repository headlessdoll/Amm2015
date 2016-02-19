<h2>Dettaglio ordine n°<?=$ordine->getId()?> del <?=substr($ordine->getData(),0,10)?></h2>

    <table>

            <tr>
                <th >Album</th>               
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
                <th>Prezzo Domicilio</th>                
                <th>Prezzo Album</th>
                <th>Prezzo Totale</th>                     
            </tr>       
            <tr>          
                <td><? if($ordine->getDomicilio() == "si"){?>si<? } else {?>no<? } ?></td>            
                <td><? if($ordine->getDomicilio() == "si"){?>5€<? } else {?>0€<? } ?></td>
                <td><?= Album_ordineFactory::instance()->getPrezzoParziale($ordine) . "€ "?></td>                 
                <td><?= OrdineFactory::instance()->getPrezzoTotale($ordine) . "€ "?></td>                 
            </tr>
    </table>


