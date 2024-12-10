<?php
    /**
     * Gestione dell'autenticazione tramite Token: ad ogni accesso viene generato un nuovo token e
     * salvato nel database.
     */

    /**
     * API che assegna il token dopo aver verificato la validità dei parametri di accesso
     */
    if ($_POST['username'] && $_POST['password']) {
        try {

            // Preparo DB
            $database = new PDO("mysql:host=localhost;dbname=csc_database", "root", "");
            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = $database->prepare("SELECT id, username, password FROM utenti WHERE username = :username");
            $query->bindParam(":username", $_POST['username'], PDO::PARAM_STR);

            $results = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($results && password_verify($_POST['password'], $results[0]['password'])) {
                $token = bin2hex(random_bytes(32));
                $expiration = date('Y-m-d H:i:s', time() + 3600); // Imposta la scadenza del token ad un'ora (3600 secondi)
              
                // Preparo DB
                $database = new PDO("mysql:host=localhost;dbname=csc_database", "root", "");
                $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $database->prepare("UPDATE utenti SET token = :token, token_expiration = :token_expiration WHERE id = :id");
                $query->bindParam(":id", $results[0]['id'], PDO::PARAM_INT);
                $query->bindParam(":token", $token, PDO::PARAM_STR);
                $query->bindParam(":token_expiration", $token_expiration, PDO::PARAM_STR);

            } else {
                http_response_code(401); // Codice di risposta HTTP per utente non loggato
                echo json_encode(["message" => "Utente non autenticato."]); 
            }

        } catch (PDOException $e) {
            // Rispondo con un errore, mostrando il messaggio
            http_response_code(500);
            echo json_encode(["message" => "Errore del database." . $e->getMessage()]);
        }
    } else {
        http_response_code(400); // Codice di risposta HTTP per "Richiesta non valida"
        echo json_encode(["messaggio" => "Richiesta non valida: campo username o password assenti."]);
    }
?>