<?php

    /** Esercizio 5:
     * 
     * 0. Verifica se l'utente ha sessione aperta o cookie impostato, se no
         * 1. Chiedi all'utente un nome
         * 2. Recupera il nome dal server (https://dummyjson.com/users/1)
         * 3. Se il nome corrisponde setta la sessione e il cookie con scadenza 7 giorni
     * 
     * 4. Diamo il benvenuto
     */

    // Se non sono impostate ne la variabile di sessione username ne il cookie username
    if (!$_SESSION['username'] && !$_COOKIE['username']) {
        // Se non è stato compilato il modulo
        if (!isset($_POST['username'])) {
            // Mostra il modulo
            ?>
                <form method="post">
                    <input type="text" name="username" placeholder="Nome" />
                    <button type="submit">Invia</button>
                </form>
           <?php
        } else { // Se è stato compilato il modulo
            // Inizializza la risorsa 
            $ch = curl_init();

            // Imposto l'indirizzo di destinazione
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, "https://dummyjson.com/users/1");

            // Eseguo la chiamata e salvo il risultato
            $result = curl_exec($ch);

            // Decodifico il risultato
            $user = json_decode($result, true);

            // Chiudo la risorsa
            curl_close($ch);

            // Se il nome corrisponde
            if ($_POST['username'] == $user['firstName']) {
                // Imposto la sessione e il cookie
                session_start();
                $_SESSION['username'] = $_POST['username'];
                setcookie('username', $_POST['username'], time() + (60*60*24*7));
                // Salvo il nome
                $username = $_POST['username'];
            } else {
                // Altrimenti mostro un messaggio di errore
                ?>
                    <p>Username non valido.</p>
                <?php
            }
        } 
    } else { // Se è impostata la variabile di sessione username o il cookie username
        $username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
    }

    // Se è impostata la variabile username
    if ($username) { 
        ?>
            <p>Benvenuto, <?php echo $username;?></p>
        <?php
    }
?>