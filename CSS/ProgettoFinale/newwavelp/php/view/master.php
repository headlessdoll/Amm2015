<?php
include_once 'ViewDescriptor.php';
include_once  '../Settings.php';


    ?>
    <!DOCTYPE html>
    <!-- 
         pagina master, contiene tutto il layout della applicazione 
         le varie pagine vengono caricate a "pezzi" a seconda della zona
         del layout:
         - logo (header)
         - menu (i tab)
         - leftBar (sidebar sinistra)
         - content (la parte centrale con il contenuto)
         - footer (footer)

          Queste informazioni sono manentute in una struttura dati, chiamata ViewDescriptor
          la classe contiene anche le stringhe per i messaggi di feedback per 
          l'utente (errori e conferme delle operazioni)
    -->
    <html>
        <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8" />
            <title><?= $vd->getTitolo() ?></title>
            <base href="<?= Settings::getApplicationPath() ?>php/"/>
            <link href="../css/style.css" rel="stylesheet" type="text/css" />
            <?php
             
            foreach ($vd->getScripts() as $script) {
                ?>
                <script type="text/javascript" src="<?= $script ?>"></script>
                <?php
            }
            ?>
        </head>
        <body>
            <div id="page">
                <header>
                    <div class="logout">
                        <?php
                        $logo = $vd->getLogoFile();
                        require "$logo";
                        ?>
                    </div>

                <!-- menu -->
                <div id="menu">
                    <?php
                        $menu = $vd->getMenuFile();
                        require "$menu";
                    ?>
                </div> 

                </header>
                <!-- start page -->
                
                <!--  sidebar sinistra -->
                <div id="sidebar1">
                    <ul>
                        <li id="categories">
                            <?php
                            $left = $vd->getLeftBarFile();
                            require "$left";
                            ?>
                        </li>

                    </ul>
                </div>

                <!-- contenuto pagina-->
                <div id="content">
                    <?php
                    if ($vd->getMessaggioErrore() != null) {
                        ?>
                        <div class="error">
                            <div>
                                <?= $vd->getMessaggioErrore();?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                    if ($vd->getMessaggioConferma() != null) {
                        ?>
                        <div class="confirm">
                            <div>
                                <?= $vd->getMessaggioConferma();?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                    $content = $vd->getContentFile();
                    require "$content";
                    ?>


                </div>

                <div class="clear">
                </div>

            </div>
                            <!--  footer -->
            <footer>
                <div id="footer">
                    
                </div>

            </footer>
        </body>
    </html>






