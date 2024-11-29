<?php

    /** Pagina che visualizza un elenco di articoli con la possibilità di inserirne di nuovi,
     *  modificarli o cancellarli
     */

    
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://localhost/csc-corso-backend/lezione-7/articoli.php");
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/json"
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $articoli = json_decode($response, true);
?>
<html>
    <head>

    </head>
    <body>
        <main>
            <h2>Articoli</h2>
            <a href="articolo.php">Crea nuovo</a>
            <table>
                <tr>
                    <th>
                        Titolo
                    </th>
                    <th>
                        Data
                    </th>
                    <th>
                        Operazioni
                    </th>
                </tr>
                <?php
                    foreach ($articoli as $articolo) {
                        ?>
                            <tr>
                                <td><?php echo $articolo['title'];?></td>
                                <td><?php echo $articolo['date'];?></td>
                                <td>
                                    <a href="<?php echo "articolo.php?post_id=" . $articolo['post_id'];?>">Modifica</a> - 
                                    <a href="<?php echo "admin/articoli.php?post_id=" .$articolo['post_id'] . "&action=delete";?>">Cancella</a>
                                </td>
                            </tr>
                        <?php
                    }
                ?>
            </table>
        </main>
    </body>
</html>