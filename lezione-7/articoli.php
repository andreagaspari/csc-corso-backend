<?php

/**
 * Recupera un elenco di articoli dal blog, 
 * eventualmente filtrati per ID, categoria, autore, ecc...
 */

    header("Content-Type: application/json");

    // Preparo la prima parte della query, che verrà eseguita in ogni caso
    $q = "SELECT articoli.ID AS post_id, title, subtitle, content, url, date, nicename AS author, author_id FROM articoli JOIN utenti ON author_id = utenti.ID WHERE 1 = 1";

    // Gestisco eventuali filtri
    if ($_GET) {
        // Preparo il contenitore dei filtri
        $filters = array();

        // Filtro autore
        if ($_GET['author_id']) {
           
            // Salvo il filtro
            $filters[] = array(
                "param_name" => ":author_id",
                "value" => $_GET['author_id'],
                "type" => PDO::PARAM_INT
            );

            // Aggiungo il filtro alla query
            $q .= " AND author_id = :author_id";
        }

        // Filtro categoria
        if ($_GET['category_id']) {
            
            // Salvo il filtro
            $filters[] = array(
                "param_name" => ":category_id",
                "value" => $_GET['category_id'],
                "type" => PDO::PARAM_INT
            );

            // Aggiungo il filtro alla query
            $q .= " AND articoli.ID IN (SELECT articolo_id FROM categorie_articoli WHERE categoria_id = :category_id)";
        }

        // Filtro articolo
        if ($_GET['post_id']) {

            // Salvo il filtro
            $filters[] = array(
                "param_name" => ":post_id",
                "value" => $_GET['post_id'],
                "type" => PDO::PARAM_INT
            );

            // Aggiungo il filtro alla query
            $q .= " AND articoli.ID = :post_id";
        }
        
    }

    try {

        // Connessione al database
        $database = new PDO("mysql:host=localhost;dbname=csc_database", "root", "");
        // Imposto l'attributo del PDO (PHP Data Object) per la gestione degli errori tramite eccezioni
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparo la query
        $query = $database->prepare($q);

        // Aggiungo i parametri derivati dai filtri
        if (isset($filters)) {
            foreach ($filters as $filter) {
                // Per ogni filtro salvato, imposto il parametro corrispondente
                $query->bindParam($filter['param_name'], $filter['value'], $filter['type']);
            }
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