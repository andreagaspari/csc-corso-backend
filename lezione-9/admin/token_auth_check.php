<?php

    /**
     * Gestione dell'autenticazione tramite Token: Controllo la validità del token fornito
     */
    function token_auth_check() {
        $authHeader = $headers['authorization'] ?? null;
        if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {

            $token = $matches[1];

            // Preparo DB
            $database = new PDO("mysql:host=localhost;dbname=csc_database", "root", "");
            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = $database->prepare("SELECT id FROM utenti WHERE token = :token AND token_expiration > NOW()");
            $query->bindParam(":token", $token, PDO::PARAM_STR);

            $query->execute();
    
            $user = $query->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                return $user['id'];
            }
        } 

        return false;
    }