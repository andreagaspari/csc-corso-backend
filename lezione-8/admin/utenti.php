<?php

/**
 * Recupera un elenco di utenti dal blog.
 */

    header("Content-Type: application/json");

    // Preparo la prima parte della query, che verrà eseguita in ogni caso
    $q = "SELECT utenti.ID AS author_id, nicename FROM utenti";

    try {

        // Connessione al database
        $database = new PDO("mysql:host=localhost;dbname=csc_database", "root", "");
        // Imposto l'attributo del PDO (PHP Data Object) per la gestione degli errori tramite eccezioni
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparo la query
        $query = $database->prepare($q);

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

?>