<?php
    /**
     * REST API per l'oggetto utente
     */
    header("Content-Type: application/json");

    require_once('basic_authentication.php');
    require_once('token_auth_check.php');

    switch ( $_SERVER['REQUEST_METHOD'] ) {
        case 'GET': // Recupera elementi 

            if (isset($_GET['id'])) { 
                // Recupera un elemento per ID
                try {
                    // Preparo DB
                    $database = new PDO("mysql:host=localhost;dbname=csc_database", "root", "");
                    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Preparo la query
                    $query = $database->prepare("SELECT * FROM utenti WHERE ID = :id");
                    $query->bindParam(":id", $_GET['id'], PDO::PARAM_INT);
                    
                    // Eseguo la query ed estraggo i risultati
                    $query->execute();
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);

                    if (!$result) {
                        http_response_code(404); // Codice di risposta HTTP per risorsa non trovata
                        echo json_encode([
                            'status' => 404,
                            'message' => 'Utente non trovato.'
                        ]);
                    } else {
                        // Rispondo con successo, mostrando 1 o più risultati
                        http_response_code(200);
                        echo json_encode([
                            'status' => 200,
                            'data'   => $result
                        ]);
                    }


                } catch (PDOException $e) {
                    // Rispondo con un errore, mostrando il messaggio
                    http_response_code(500);
                    echo json_encode(["message" => "Errore del database." . $e->getMessage()]);
                }
            } else {
                // Recupera tutti gli elementi
                try {
                    // Preparo DB
                    $database = new PDO("mysql:host=localhost;dbname=csc_database", "root", "");
                    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Preparo la query
                    $query = $database->prepare("SELECT * FROM utenti");
                    
                    // Eseguo la query ed estraggo i risultati
                    $query->execute();
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);

                    // Rispondo con successo, mostrando 0 o più risultati
                    http_response_code(200);
                    echo json_encode($result);

                } catch (PDOException $e) {
                    // Rispondo con un errore, mostrando il messaggio
                    http_response_code(500);
                    echo json_encode(["message" => "Errore del database." . $e->getMessage()]);
                }
            }

        break;
        case 'POST': // Aggiunge elementi 
            if (basic_authentication_login()) {
                try {
                    // Preparo DB
                    $database = new PDO("mysql:host=localhost;dbname=csc_database", "root", "");
                    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    if (!$_POST['username']) {
                        http_response_code(400); // Codice di risposta HTTP per "Richiesta non valida"
                        echo json_encode(["messaggio" => "Richiesta non valida: campo username assente."]);
                    } else {

                        // Preparo la query
                        $query = $database->prepare("INSERT INTO utenti (username, password, mail, nicename, name, surname) VALUES (:username, :password, :mail, :nicename, :name, :surname)");
                        $query->bindParam(":username", $_POST['username'], PDO::PARAM_STR);
                        $query->bindParam(":password", password_hash($_POST['password'], PASSWORD_DEFAULT), PDO::PARAM_STR);
                        $query->bindParam(":mail", $_POST['mail'], PDO::PARAM_STR);
                        $query->bindParam(":nicename", $_POST['nicename'], PDO::PARAM_STR);
                        $query->bindParam(":name", $_POST['name'], PDO::PARAM_STR);
                        $query->bindParam(":surname", $_POST['surname'], PDO::PARAM_STR);

                        // Eseguo la query e recupero l'ID dell'utente appena creato
                        if ($query->execute()) {
                            http_response_code(201); // Codice di risposta HTTP per "Risorsa creata"
                            echo json_encode([
                                "messaggio" => "Utente creato con successo",
                                "id"        => $database->lastInsertId()
                            ]);
                        } else {
                            echo json_encode(["message" => "Errore nella creazione dell'utente"]);
                        }
                    }
                } catch (PDOException $e) {
                    // Rispondo con un errore, mostrando il messaggio
                    http_response_code(500);
                    echo json_encode(["message" => "Errore del database. " . $e->getMessage()]);
                }
            } else {
                http_response_code(401); // Codice di risposta HTTP per utente non loggato
                echo json_encode(["message" => "Utente non autenticato."]); 
            }

        break;
        case 'PUT': // Aggiorna elementi

            try {

                // Preparo DB
                $database = new PDO("mysql:host=localhost;dbname=csc_database", "root", "");
                $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $input = json_decode(file_get_contents("php://input"), true);   
                /*  Struttura di $input
                    array(
                        "name" => "Nuovo name,
                        "mail" => "Nuova mail
                    )
                */
                $validParams = array("username");
                
                $queryStr = "UPDATE utenti SET ";

                foreach($input as $param => $value) {
                    switch ($param) {
                        case "username":
                        case "password":
                        case "mail":
                        case "nicename":
                        case "name":
                        case "surname":
                            $queryStr .= $param ." = :". $param . " "; // Es. name = :name
                    }
                }

                $queryStr .= " WHERE id = :id";

                $query = $database->prepare($queryStr);
                $query->bindParam(":id", $_GET['id'], PDO::PARAM_INT);

                foreach($input as $param => $value) {
                    switch ($param) {
                        case "username":
                        case "password":
                        case "mail":
                        case "nicename":
                        case "name":
                        case "surname":
                            $query->bindParam(".".$param, $value, PDO::PARAM_STR);
                    }
                }

                // Eseguo la query e recupero l'ID dell'utente appena creato
                if ($query->execute()) {
                    echo json_encode(["messaggio" => "Utente aggiornato con successo"]);
                } else {
                    echo json_encode(["message" => "Errore nell'aggiornamento dell'utente"]);
                }

            } catch (PDOException $e) {
                // Rispondo con un errore, mostrando il messaggio
                http_response_code(500);
                echo json_encode(["message" => "Errore del database. " . $e->getMessage()]);
            }

        break;
        case 'DELETE': // Cancella elementi
            if (token_auth_check()) {
                try {
                
                    // Preparo DB
                    $database = new PDO("mysql:host=localhost;dbname=csc_database", "root", "");
                    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                    // Preparo la query
                    $query = $database->prepare("DELETE FROM utenti WHERE id = :id");
                    $query->bindParam(":id", $_GET['id'], PDO::PARAM_INT);
    
                    // Eseguo la query e rispondo
                    if ($query->execute()) {
                        echo json_encode(["messaggio" => "Utente eliminato con successo"]);
                    } else {
                        echo json_encode(["message" => "Errore nella eliminazione dell'utente"]);
                    }
    
                } catch (PDOException $e) {
                    // Rispondo con un errore, mostrando il messaggio
                    http_response_code(500);
                    echo json_encode(["message" => "Errore del database. " . $e->getMessage()]);
                }
            } else {
                http_response_code(401); // Codice di risposta HTTP per utente non loggato
                echo json_encode(["message" => "Utente non autenticato."]); 
            }
        break;
        default:
            http_response_code(405); // Codice di risposta HTTP per metodo non supportato
            echo json_encode(['message' => 'Richiesta non supportata']);
        break;
    }

?>