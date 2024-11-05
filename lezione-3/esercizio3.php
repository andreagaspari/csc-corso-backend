<?php 
    /**
     * Creare una pagina HTML che mostra la lista di utenti
     * stampando solo l'iniziale del nome e gli fa gli auguri 
     * se è nato oggi
     */
    /* Array di esempio */
    $utenti = array(
        array(
            "nome" => "Mario",
            "cognome" => "Rossi",
            "data_di_nascita" => "06/10"
        ),
        array(
            "nome" => "Manola",
            "cognome" => "Arduini",
            "data_di_nascita" => "05/11"
        ),
        array(
            "nome" => "Paolo",
            "cognome" => "Verdi",
            "data_di_nascita" => "10/12"
        )
    );
    $data_di_oggi = date("d/m");
?>
<html>
    <head>
        <title>Esercizio 3</title>
    </head>
    <body>
        <h1>Esercizio 3</h1>
        <p>Creare una pagina HTML che mostra la lista di utenti stampando solo l'iniziale del nome e gli fa gli auguri se è nato oggi</p>
        <ul>
            <?php
                foreach($utenti as $utente) {
                    // Volendo posso usare l'operatore ternario
                    // echo "<li>" . (($data_di_oggi == $utente['data_di_nascita']) ? "Auguri " : "") . $utente['nome']."</li>";
                    ?>
                        <li>
                            <?php
                                // Verifico la corrispondenza delle due date
                                if ($data_di_oggi == $utente['data_di_nascita']) {
                                    echo "Auguri " . $utente['nome'];
                                } else {
                                    echo $utente['nome'];
                                }
                            ?>
                        </li>
                    <?php
                }
            ?>
        </ul>
    </body>
</html>