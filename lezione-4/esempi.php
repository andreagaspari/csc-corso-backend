<?php
    // Metodo GET: recupera i dati direttamente dall'URL
    $q = $_GET['query'];
    echo $q . " ";

    // Metodo POST: recupera i dati dal corpo della richiesta
    if ($_POST['nome']) {
        $p = $_POST['nome'];
        echo $p;
    } else {
        ?>
            <form action="" method="post">
                <input type="text" name="nome" placeholder="Inserisci il tuo nome" />
                <button type="submit">Invia</button>
            </form>
        <?php
    }

    // Impostazione e lettura di un cookie
    setcookie("nome", "valore", time() + (60));
    echo $_COOKIE["nome"];

    // Avvio la sessione e salvo il nome utente
    session_start();
    $_SESSION["username"] = "Mario";

    // Elimino la sessione in corso
    // session_destroy();    
?>