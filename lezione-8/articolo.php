<?php

    /** Pagina che permette di inserire, modificare o 
     * cancellare un articolo del blog */

    // Recupero l'elenco degli utenti
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, "http://localhost/csc-corso-backend/lezione-8/admin/utenti.php");
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/json"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $utenti = json_decode($response, true);

    if ($_GET['post_id']) {
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, "http://localhost/csc-corso-backend/lezione-7/articoli.php?post_id=".$_GET['post_id']);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Accept: application/json"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $articolo = json_decode($response, true);

        foreach ($articolo[0] as $chiave => $valore) {
            if (str_starts_with($valore, "'") && str_ends_with($valore, "'")) {
                $articolo[0][$chiave] = substr($valore, 1, -1);
            }
        }
    }
?>
<html>
    <head>

    </head>
    <body>
        <main>
            <h2><?php echo (isset($articolo)) ? "Modifica " : "Crea ";?> articolo</h2>
            <form action="admin/articoli.php" method="post">
                <p>
                    <label for="title">Titolo</label>
                    <br/>
                    <input type="text" name="title" id="title" required value="<?php echo (isset($articolo)) ? $articolo[0]['title'] : '';?>">
                </p>
                <p>
                    <label>Sottotitolo</label>
                    <br/>
                    <input type="text" name="subtitle" value="<?php echo (isset($articolo)) ? $articolo[0]['subtitle'] : '';?>" />
                </p>
                <p>
                    <label>Slug</label>
                    <br/>
                    <input type="text" name="url" required value="<?php echo (isset($articolo)) ? $articolo[0]['url'] : '';?>"/>
                </p>
                <p>
                    <label>Contenuto</label>
                    <br/>
                    <textarea name="content"><?php echo (isset($articolo)) ? $articolo[0]['content'] : '';?></textarea>
                </p>
                <p>
                    <label>Autore</label>
                    <br/>
                    <select name="author_id" required>
                        <option value="">Seleziona un autore...</option>
                        <?php
                            foreach($utenti as $autore) {
                                ?>
                                    <option value="<?php echo $autore['author_id'];?>" <?php echo (isset($articolo) && $autore['author_id'] == $articolo[0]['author_id']) ? "selected" : ""; ?>><?php echo $autore['nicename'];?></option>
                                <?php
                            }
                        ?>
                    </select>
                </p>
                <p>
                    <?php
                        if (isset($articolo)) {
                            ?>
                                <input type="hidden" name="post_id" value="<?php echo $articolo[0]['post_id'];?>" />
                            <?php
                        }
                    ?>
                    <input type="submit" value="Invia"/>
                </p>
            </form>
        </main>
    </body>
</html>