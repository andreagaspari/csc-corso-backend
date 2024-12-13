<?php
/**
 * Interfaccia per la selezione, creazione, modifica ed eliminazione del token
 */

require_once("../inc/config.php");

// Indico che gestirò i dati in formato json
header('Content-Type: application/json');

// Gestione delle richieste e inoltro ai vari handler
switch($_SERVER['REQUEST_METHOD']) {
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
 * Recupero un token se esiste
 */
/*
function handleGet() {
    // Verifico la presenza del parametro
    if (isses($_GET['user_id'])) {
        try {
            // Connessione al database
            $database = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
            // Imposto l'attributo del PDO (PHP Data Object) per la gestione degli errori tramite eccezioni
            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            // Inizializzo la stringa per la query al database
            $query = $database->prepare("SELECT token FROM users WHERE id = :id");
            
            $query->bindParam(':id', $_GET['user_id']);
    
            // Eseguo la query
            $query->execute();
                
            // Recupero i risultati sotto forma di array associativo
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
            // Stampo i risultato della query con successo
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "token"  => $result
            ]);
            
        } catch (PDOException $e) {
            // Errore di connessione al database (Err. 500 - Internal Server Error)
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Errore del database: " . $e->getMessage()
            ]);
        }
    } else {
        // Se la richiesta non ha tutti i dati, ritorno l'errore (400 - Bad Request)
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Errore nella richiesta: dati mancanti"
        ]);
    }
}*/

/**
 * Gestione delle richieste di login. 
 * Se ho username e password verifico correttezza, genero e restituisco token.
 * Se ho user_id e token valido, aggiorno la scadenza.
 */
function handlePost() {
    try {
        // Connessione al database
        $database = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
        // Imposto l'attributo del PDO (PHP Data Object) per la gestione degli errori tramite eccezioni
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (isset($_POST['username']) && isset($_POST['password'])) {
            // Se ho username e password genero e restituisco il token

            // Preparo la query
            $query = $database->prepare("SELECT id, username, password FROM users WHERE username = :username");
            // Imposto lo username
            $query->bindParam(":username", $_POST['username'], PDO::PARAM_STR);
            
            // Eseguo la query
            $query->execute();

            // Recupero l'utente, se esiste
            $result = $query->fetch(PDO::FETCH_ASSOC);

            // Se result non è vuoto (l'utente esiste), verifico la corrispondenza della password
            if ($result && password_verify($_POST['password'], $result['password'])) {
                // Genero il nuovo token
                $token = bin2hex(random_bytes(32));
                // Imposta la scadenza del token a 10 minuti (600 secondi)
                $expiration = date('Y-m-d H:i:s', time() + 600); 

                // Preparo la query
                $query = $database->prepare("UPDATE users SET token = :token, token_expiration = :token_expiration WHERE id = :id");

                // Imposto i parametri
                $query->bindParam(":id", $result['id'], PDO::PARAM_INT);
                $query->bindParam(":token", $token, PDO::PARAM_STR);
                $query->bindParam(":token_expiration", $expiration, PDO::PARAM_STR);

                // Eseguo la query
                if ($query->execute()) {
                    // Ritorno id utente loggato e token
                    http_response_code(200);
                    echo json_encode([
                        "success" => true,
                        "data" => [
                            "user_id" => $result['id'],
                            "token" => $token
                        ]
                    ]);
                } else {
                    // Errore durante l'esecuzione della query (Err. 500 - Internal Server Error)
                    http_response_code(500);
                    echo json_encode([
                        "success" => false,
                        "message" => "Errore del database."
                    ]); 
                }
            } else {
                http_response_code(401); // Codice di risposta HTTP per utente non loggato
                echo json_encode([
                    "success" => false,
                    "message" => "Utente non autenticato."
                ]); 
            } 
        } elseif (isset($_POST['user_id']) && isset($_POST['token'])) {
            // Se ho user_id e token, verifico la validità dello stesso e in caso positivo aggiorno la scadenza
           
            // Preparo la query
            $query = $database->prepare("SELECT token, token_expiration FROM users WHERE id = :id");
            // Imposto lo username
            $query->bindParam(":id", $_POST['user_id'], PDO::PARAM_INT);
            
            // Eseguo la query
            $query->execute();

            // Recupero l'utente, se esiste
            $result = $query->fetch(PDO::FETCH_ASSOC);

            // Verifico la corrispondenza del token
            if ($result && $result['token'] == $_POST['token']) {
                $token_expiration = new DateTime($result['token_expiration']);
            
                // Se non è stata superata la scadenza, il token è valido
                if ($token_expiration < new DateTime()) {
                    // Aggiorno la scadenza a 10 minuti avanti
                    $new_token_expiration = date('Y-m-d H:i:s', time() + 600);
                   
                    // Preparo la query
                    $query = $database->prepare("UPDATE users SET token_expiration = :token_expiration WHERE id = :id");

                    // Imposto i parametri
                    $query->bindParam(":id", $_POST['user_id'], PDO::PARAM_INT);
                    $query->bindParam(":token_expiration", $new_token_expiration, PDO::PARAM_STR);

                    // Eseguo la query
                    if ($query->execute()) {
                        // Token, utente autorizzato
                        http_response_code(200);
                        echo json_encode([
                            "success" => true,
                            "message" => "Token valido. Countdown resettato."
                        ]);
                    } else {
                        // Errore durante l'esecuzione della query (Err. 500 - Internal Server Error)
                        http_response_code(500);
                        echo json_encode([
                            "success" => false,
                            "message" => "Errore del database."
                        ]); 
                    }
                } else {
                    // Il token è scaduto, rifai la login (Err. 401 - Unauthorized Access)
                    http_response_code(401); 
                    echo json_encode([
                        "success" => false,
                        "message" => "Token scaduto."
                    ]);
                }
            } else {
                // L'utente non esiste o il token non è corretto (Err. 401 - Unauthorized Access)
                http_response_code(401); 
                echo json_encode([
                    "success" => false,
                    "message" => "Utente non autenticato."
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
    } catch(PDOException $e) {
        // Errore di connessione al database (Err. 500 - Internal Server Error)
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Errore del database" . (DEBUG_MODE) ? ': '.$e->getMessage() : '.'
        ]); 
     }
}

/**
 * Gestione delle richieste DELETE.
 * Se ho user_id e token, elimino il token e la sua scadenza.
 */
function handleDelete() {

    try {
        // Connessione al database
        $database = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
        // Imposto l'attributo del PDO (PHP Data Object) per la gestione degli errori tramite eccezioni
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        if (isset($_POST['user_id']) && isset($_POST['token'])) {
            // Se ho user_id e token, verifico l'esistenza e lo elimino
           
            // Preparo la query
            $query = $database->prepare("SELECT token FROM users WHERE id = :id");
            // Imposto lo username
            $query->bindParam(":id", $_POST['user_id'], PDO::PARAM_INT);
            
            // Eseguo la query
            $query->execute();

            // Recupero l'utente, se esiste
            $result = $query->fetch(PDO::FETCH_ASSOC);

            // Verifico la corrispondenza del token
            if ($result && $result['token'] == $_POST['token']) {
                // Preparo la query
                $query = $database->prepare("UPDATE users SET token = NULL, token_expiration = NULL WHERE id = :id");

                // Imposto i parametri
                $query->bindParam(":id", $_POST['user_id'], PDO::PARAM_INT);

                // Eseguo la query
                if ($query->execute()) {
                    // Token eliminato, utente sloggato
                    http_response_code(200);
                    echo json_encode([
                        "success" => true,
                        "message" => "Token valido. Countdown resettato."
                    ]);
                } else {
                    // Errore durante l'esecuzione della query (Err. 500 - Internal Server Error)
                    http_response_code(500);
                    echo json_encode([
                        "success" => false,
                        "message" => "Errore del database."
                    ]); 
                }
            } else {
                // L'utente non esiste o il token non è corretto (Err. 401 - Unauthorized Access)
                http_response_code(401); 
                echo json_encode([
                    "success" => false,
                    "message" => "Utente non autenticato."
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

    } catch(PDOException $e) {
        // Errore di connessione al database (Err. 500 - Internal Server Error)
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Errore del database" . (DEBUG_MODE) ? ': '.$e->getMessage() : '.'
        ]); 
    }  
}

?>