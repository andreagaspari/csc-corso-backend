<?php 
    /**
     * Creare una pagina HTML che saluta per nome l'utente
     */
    $utente = array(
        "nome" => "Mario",
        "cognome" => "Rossi"
    );
?>
<html>
    <head>
        <title>Esercizio 1</title>
    </head>
    <body>
        <h1>Esercizio 1</h1>
        <p>Creare una pagina HTML che saluta per nome l'utente</p>

        <h2>Buongiorno, <?php echo $utente['nome']; ?></h2>
    </body>
</html>