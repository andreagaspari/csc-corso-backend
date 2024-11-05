<?php 
    /**
     * Creare una pagina HTML che mostra la lista di utenti
     * stampando solo l'iniziale del nome
     */
    /* Array di esempio */
    $utenti = array(
        array(
            "nome" => "Mario",
            "cognome" => "Rossi"
        ),
        array(
            "nome" => "Luca",
            "cognome" => "Bianchi"
        ),
        array(
            "nome" => "Paolo",
            "cognome" => "Verdi"
        )
    );
?>
<html>
    <head>
        <title>Esercizio 2</title>
    </head>
    <body>
        <h1>Esercizio 2</h1>
        <p>Creare una pagina HTML che mostra la lista di utenti, stampando solo l'iniziale del nome</p>
        <ul>
            <?php
                foreach($utenti as $utente) {
                    echo "<li>".$utente['nome'][0] . ". " . $utente['cognome']."</li>";
                }
            ?>
        </ul>
    </body>
</html>