<h2>Catalogo</h2>

<div class="input-form">

<table>
    <tr>
        <th>Nome</th>
        <th>Autore</th>    
        <th>Prezzo</th>            
    </tr>
    
<form action="cliente/ordina" method="post">
    

    <?foreach ($album as $album) {
        ?>
        <tr>
            <td class="col-small"><?= $album->getNome() ?></td>
            <td class="col-large"><?= $album->getAutore() ?></td>
            <td class="col-small"><input type="number" name=<?= $album->getId() ?> maxlength="2" size="2" min="0" max="10"></td>            
            <td class="col-small"><?= $album->getPrezzo() . "€ "?></td>           
        </tr>
    <? } ?>
</table>

    <label for="spedizione">Spedizione</label>
        <select name="spedizione" id="spedizione">
                <option value="si">si</option>
                <option value="no">no</option>
        </select>  
                   
    </select>     
    <button type="submit" name="cmd" value="procedi_ordine">Procedi</button>

    
</div> 
    
