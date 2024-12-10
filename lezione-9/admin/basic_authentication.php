<?php
    /**
     * Gestione dell'autenticazione base: ad ogni richiesta invio anche username e password per autenticarmi.
     */

    /**
     * Funzione che verifica la validità dei parametri di accesso
     */
    function basic_authentication_login() {
        if ($_SERVER['PHP_AUTH_USER']) {
            $user = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];
    
            // Preparo DB
            $database = new PDO("mysql:host=localhost;dbname=csc_database", "root", "");
            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = $database->prepare("SELECT username, password FROM utenti WHERE username = :username");
            $query->bindParam(":username", $user, PDO::PARAM_STR);

            $query->execute();
    
            $user = $query->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                return true;
            }
        }

        return false;
    }

?>