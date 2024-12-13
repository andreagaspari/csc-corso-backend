<?php
/**
 * Interfaccia per la selezione, creazione, modifica ed eliminazione dell'Utente
 */

require_once("../inc/config.php");

// Indico che gestirò i dati in formato json
header('Content-Type: application/json');

// Gestione delle richieste e inoltro ai vari handler
switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        handleGet();
        break;
    case 'POST':
        handlePost();
        break;
    case 'DELETE':
        handleDelete();
        break;
    default:
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Metodo non supportato'
        ]);
}

/**
 * Gestore delle chiamate di tipo GET. 
 * Supporta il filtro per ID e username.
 */
function handleGet() {

    try {
    
        // Connessione al database
        $database = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
        // Imposto l'attributo del PDO (PHP Data Object) per la gestione degli errori tramite eccezioni
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Inizializzo la stringa per la query al database
        $queryStr = "SELECT * FROM users WHERE 1 = 1";

        // Preparo il contenitore dei filtri
        $filters = array();

        // Inserisco il filtro per id all'interno dell'array se presente
        if (isset($_GET['id'])) {
            $filters[] = array(
                "param_name" => ":id",
                "value" => $_GET['id'],
                "type" => PDO::PARAM_INT
            );

            // Aggiungo la clausola per la ricerca per id all'interno della query
            $queryStr .= " AND id = :id";
        }

        /*
            // Controllo alternativo: verifico se esiste la chiave 'username' all'interno dell'array e che non sia vuota
            if (array_key_exists('username', $_GET) && $_GET['username'] != '') {
        */
        if (isset($_GET['username'])) {
            $filters[] = array(
                "param_name" => ":username",
                "value" => $_GET['username'],
                "type"  => PDO::PARAM_STR
            );

            $queryStr .= " AND username = :username";
        }

        // Preparo la query per il database
        $query = $database->prepare($queryStr);

        // Aggiungo i valori dei parametri dei filtri
        foreach($filters as $filter) {
            $query->bindParam($filter['param_name'], $filter['value'], $filter['type']);
        }

        // Eseguo la query
        $query->execute();
            
        // Recupero i risultati sotto forma di array associativo
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        // Stampo i risultato della query con successo
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "data"  => $result
        ]);

    } catch (PDOException $e) {
        // Rispondo con un errore, mostrando il messaggio se DEBUG è attivo
        http_response_code(500);
        echo json_encode([
           "success" => false,
           "message" => "Errore del database" . (DEBUG_MODE) ? ': '.$e->getMessage() : '.'
       ]);
    }

}

/**
 * Gestore delle chiamate di tipo POST.
 * Se è specificato un id aggiorna l'utente corrispondente,
 * altrimenti crea un nuovo utente.
 */
function handlePost() {
    try {
        
        // Connessione al database
        $database = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
        // Imposto l'attributo del PDO (PHP Data Object) per la gestione degli errori tramite eccezioni
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verifico se è presente l'ID utente (se sì faccio UPDATE, se no INSERT)
        if (isset($_POST['id'])) {
            // Aggiorno i dati dell'utente (se esiste), se presenti

            if (isset($_POST['username']) || isset($_POST['password'])) {
               
                // Preparo la stringa per la query
                $queryStr = "UPDATE users SET ";

                // Inizializzo l'array dei parametri da aggiornare e della query
                $paramValues = array();
                $paramQueries = array();

                // Inserisco il parametro username all'interno dell'array se presente
                if (isset($_POST['username'])) {
                    $paramValues[] = array(
                        "param_name" => ":username",
                        "value" => $_POST['username'],
                        "type" => PDO::PARAM_STR
                    );
                    $paramQueries[] = "username = :username";
                }

                // Inserisco il parametro password all'interno dell'array se presente
                if (isset($_POST['password'])) {
                    // Check di sicurezza sulla password (1 Maiuscola, 1 minuscola, 1 numero, 1 carattere speciale e minimo 8 caratteri)
                    $password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/";
                    if (preg_match($password_regex, $_POST['password'])) {
                        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

                        $paramValues[] = array(
                            "param_name" => ":password",
                            "value" => $password_hash,
                            "type" => PDO::PARAM_STR
                        );
                        $paramQueries[] = "password = :password";
                    } else {
                        // Se la password non è sufficientemente sicura, ritorno l'errore (400 - Bad Request)
                        http_response_code(400);
                        echo json_encode([
                            "success" => false,
                            "message" => "Errore nella richiesta: la password non è sicura"
                        ]);
                        return;
                    }
                }

                // Da array("username = :username", "password = :password") a stringa "username = :username, password =:password"
                $queryStr .= implode(", ", $paramQueries);

                // Completo la stringa per la query con la clausola WHERE id = :id
                $queryStr .= " WHERE id = :id";

                // Preparo la query
                $query = $database->prepare($queryStr);

                // Aggiungo il parametro id
                $query->bindParam(":id", $_POST['id']);

                // Aggiungo gli altri parametri (username e password)
                foreach ($paramValues as $parameter) {
                    $query->bindParam($parameter['param_name'], $parameter['value'], $parameter['type']);
                }

                if ($query->execute()) {
                    http_response_code(200);
                    echo json_encode([
                        "success" => true,
                        "message" => "Utente aggiornato con successo"
                    ]);
                } else {
                    // Se l'aggiornamento non modifica nessun elemento, ritorno errore (400 - Bad Request)
                    http_response_code(400);
                    echo json_encode([
                        "success" => false,
                        "message" => "Nessun utente da aggiornare"
                    ]);
                }
        
            } else {
                // Se la richiesta non ha alemno un dato da aggiornare, ritorno l'errore (400 - Bad Request)
                http_response_code(400);
                echo json_encode([
                    "success" => false,
                    "message" => "Errore nella richiesta: dati mancanti"
                ]);
            }   

        } else {
            // Aggiungo un nuovo utente al database

            // Verifico la presenza dei dati obbligatori
            if (isset($_POST['username']) && isset($_POST['password'])) {

                // Check di sicurezza sulla password (1 Maiuscola, 1 minuscola, 1 numero, 1 carattere speciale e minimo 8 caratteri)
                $password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/";
                if (preg_match($password_regex, $_POST['password'])) {
                    // Cifro la password
                    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

                    // Preparo la query per il database (inserimento di un nuovo utente)
                    $query = $database->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
                    
                    // Imposto i parametri
                    $query->bindParam(":username", $_POST['username'], PDO::PARAM_STR);
                    $query->bindParam(":password", $password_hash, PDO::PARAM_STR);

                    // Eseguo la query e recupero l'ID dell'utente appena creato
                    if ($query->execute()) {
                        http_response_code(201); // Codice di risposta HTTP per "Risorsa creata"
                        echo json_encode([
                            "success" => true,
                            "data"    => array(
                                "id" => $database->lastInsertId()
                            )
                        ]);
                    } else {
                        // Se la query non viene eseguita restituisco un messaggio di errore (500 - Internal Server Error)
                        http_response_code(500);
                        echo json_encode([
                            "success" => false,
                            "message" => "Errore nella creazione dell'utente"
                        ]);
                    }
                } else {
                    // Se la password non è sufficientemente sicura, ritorno l'errore (400 - Bad Request)
                    http_response_code(400);
                    echo json_encode([
                        "success" => false,
                        "message" => "Errore nella richiesta: la password non è sicura"
                    ]);
                }

            } else {
                // Se la richiesta non ha tutti i dati obbligatori, ritorno l'errore (400 - Bad Request)
                http_response_code(400);
                echo json_encode([
                    "success" => false,
                    "message" => "Errore nella richiesta: dati mancanti"
                ]);
            }
        }

    } catch (PDOException $e) {
        // Rispondo con un errore, mostrando il messaggio se DEBUG è attivo
        http_response_code(500);
        echo json_encode([
           "success" => false,
           "message" => "Errore del database" . (DEBUG_MODE) ? ': '.$e->getMessage() : '.'
       ]);
    }
}

/**
 * Gestore delle chiamate di tipo DELETE. 
 * Elimina l'utente se presente
 */
function handleDelete() {
    if (isset($_GET['id'])) {
        try {
    
            // Connessione al database
            $database = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
            // Imposto l'attributo del PDO (PHP Data Object) per la gestione degli errori tramite eccezioni
            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     
             // Preparo la query
             $query = $database->prepare("DELETE FROM users WHERE id = :id");
             $query->bindParam(":id", $_GET['id'], PDO::PARAM_INT);
     
             // Eseguo la query
             if ($query->execute()) {
                 // Cancellazione eseguita con successo
                 http_response_code(200);
                 echo json_encode([
                     "success" => true,
                     "message" => "Utente eliminato con successo"
                 ]);
             } else {
                 // Cancellazione non riuscita, ritorno errore (400 - Bad Request)
                 http_response_code(400);
                 echo json_encode([
                     "success" => false,
                     "message" => "Nessun utente da eliminare"
                 ]);
             }
     
         } catch (PDOException $e) {
             // Rispondo con un errore, mostrando il messaggio se DEBUG è attivo
             http_response_code(500);
             echo json_encode([
                "success" => false,
                "message" => "Errore del database" . (DEBUG_MODE) ? ': '.$e->getMessage() : '.'
            ]);
         }
    } else {
        // Se la richiesta non ha tutti l'id utente, ritorno l'errore (400 - Bad Request)
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Errore nella richiesta: dati mancanti"
        ]);
    }
}

?>