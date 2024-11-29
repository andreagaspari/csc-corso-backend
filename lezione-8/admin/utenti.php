<?php

/**
 * Recupera un elenco di utenti dal blog.
 */

    header("Content-Type: application/json");

    if ($_POST) {
        
        if ($_POST['author_id']) {
            // Modifica utente
            // [...]
        } else {
            // Creazione utente
            // [...]
        }

    } elseif ($_GET['action'] = "delete") {
        // Cancellazione utente

        // [...]
    } else {

        // Lettura dal DB
        try {

            // Connessione al database
            $database = new PDO("mysql:host=localhost;dbname=csc_database", "root", "");
            // Imposto l'attributo del PDO (PHP Data Object) per la gestione degli errori tramite eccezioni
            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Preparo la query
            $query = $database->prepare("SELECT utenti.ID AS author_id, nicename FROM utenti");

            // Eseguo la query
            $query->execute();
            
            // Recupero i risultati sotto forma di array associativo
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            // Rispondo con successo alla richiesta
            http_response_code(200);
            echo json_encode($result);

        } catch (PDOException $e) {

            // Rispondo con un errore, mostrando il messaggio
            http_response_code(500);
            echo json_encode(["message" => "Errore del database: " . $e->getMessage()]);
        }

    }

?>