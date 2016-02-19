<ul>
    <li class="<?= strpos($vd->getSottoPagina(),'home') !== false || $vd->getSottoPagina() == null ? 'current_page_item' : ''?>"><a href="admin/home">Home</a></li>
    <li class="riga"></li>
    <li class="<?= strpos($vd->getSottoPagina(),'anagrafica') !== false ? 'current_page_item' : '' ?>"><a href="admin/anagrafica">Anagrafica</a></li>
    <li class="riga"></li>    
    <li class="<?= strpos($vd->getSottoPagina(),'gestione_ordini') !== false ? 'current_page_item' : '' ?>"><a href="admin/gestione_ordini">Gestione ordini</a></li>
    <li class="riga"></li>
    <li class="<?= strpos($vd->getSottoPagina(),'ricerca_ordini') !== false ? 'current_page_item' : '' ?>"><a href="admin/ricerca_ordini">Ricerca ordini</a></li>
</ul>