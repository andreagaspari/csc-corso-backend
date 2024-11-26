<?php

/**
 * Recupera un elenco di articoli dal blog, 
 * eventualmente filtrati per ID, categoria, autore, ecc...
 */

    header("Content-Type: application/json");

    // Preparo la prima parte della query, che verrà eseguita in ogni caso
    $q = "SELECT articoli.ID AS post_id, title, subtitle, content, date, nicename AS author, author_id FROM articoli JOIN utenti ON author_id = utenti.ID";

    // Gestisco eventuali filtri
    if ($_GET) {
        // Se è presente il filtro categoria
        if ($_GET['category_id']) {
            $category_id = $_GET['category_id'];
            // Aggiungo alla query il filtro per categoria
            $q .= " JOIN categorie_articoli ON articolo_id = articoli.ID WHERE categoria_id = :category_id";
            // Se è presente ANCHE il filtro autore
            if ($_GET['author_id']) {
                $author_id = $_GET['author_id'];
                // Aggiungo alla query il filtro per autore
                $q .= " AND author_id = :author_id";   
            }
        // Se è presente il filtro autore (e NON quello di categoria)
        } elseif ($_GET['author_id']) {
            $author_id = $_GET['author_id'];
            // Aggiungo alla query il filtro autore
            $q .= " WHERE author_id = :author_id";   
        }
    }

    try {

        // Connessione al database
        $database = new PDO("mysql:host=localhost;dbname=csc_database", "root", "");
        // Imposto l'attributo del PDO (PHP Data Object) per la gestione degli errori tramite eccezioni
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparo la query
        $query = $database->prepare($q);

        // Aggiungo opzionalmente i parametri
        if (isset($category_id)) {
            $query->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        }
        if (isset($author_id)) {
            $query->bindParam(':author_id', $author_id, PDO::PARAM_INT);
        }

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