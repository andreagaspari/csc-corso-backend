<?php
    /**
     * Pagina che permette di inserire, aggiornare o eliminare un articolo del blog
     */

    if ($_POST && $_POST['title'] && $_POST['url'] && $_POST['author_id']) {
    
        if ($_POST['post_id']) {

            // Modifica articolo esistente
            $articolo = array(
                "post_id"   => $_POST['post_id'],
                "title"     => $_POST['title'],
                "subtitle"  => $_POST['subtitle'],
                "content"   => $_POST['content'],
                "url"       => $_POST['url'],
                "author_id" => $_POST['author_id']
            );
        
            try {
                // Connessione al database
                $database = new PDO("mysql:host=localhost;dbname=csc_database", "root", "");
                // Imposto l'attributo del PDO (PHP Data Object) per la gestione degli errori tramite eccezioni
                $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Preparo la query
                $query = $database->prepare("UPDATE articoli SET title = :title, subtitle = :subtitle, content = :content, url = :url, author_id = :author_id WHERE articoli.ID = :post_id");

                $query->bindParam(":title", $database->quote($articolo['title']), PDO::PARAM_STR);
                $query->bindParam(":subtitle", $database->quote($articolo['subtitle']), PDO::PARAM_STR);
                $query->bindParam(":content", $database->quote($articolo['content']), PDO::PARAM_STR);
                $query->bindParam(":url", $database->quote($articolo['url']), PDO::PARAM_STR);
                $query->bindParam(":author_id", $articolo['author_id'], PDO::PARAM_INT);
                $query->bindParam(":post_id", $articolo['post_id'], PDO::PARAM_INT);

                $query->execute();

                http_response_code(200);
                echo json_encode([
                    "message" => "Articolo modificato correttamente",
                    "post_id" => $articolo['post_id']
                ]);
                header("refresh:5;url=" . "../articolo.php?post_id=" .$articolo['post_id']);
            
            } catch (PDOException $e) {
                // Rispondo con un errore, mostrando il messaggio
                http_response_code(500);
                echo json_encode(["message" => "Errore del database." . $e->getMessage()]);
                
            }
        } else {
            // Creazione nuovo articolo
            $date = new DateTime();

            $articolo = array(
                "title"     => $_POST['title'],
                "subtitle"  => $_POST['subtitle'],
                "content"   => $_POST['content'],
                "date"      => $date->format("Y-m-d"),
                "url"       => $_POST['url'],
                "author_id" => $_POST['author_id']
            );
        
            try {
                // Connessione al database
                $database = new PDO("mysql:host=localhost;dbname=csc_database", "root", "");
                // Imposto l'attributo del PDO (PHP Data Object) per la gestione degli errori tramite eccezioni
                $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Preparo la query
                $query = $database->prepare("INSERT INTO articoli (title, subtitle, content, date, url, author_id) VALUES (:title, :subtitle, :content, :date, :url, :author_id)");

                $query->bindParam(":title", $database->quote($articolo['title']), PDO::PARAM_STR);
                $query->bindParam(":subtitle", $database->quote($articolo['subtitle']), PDO::PARAM_STR);
                $query->bindParam(":content", $database->quote($articolo['content']), PDO::PARAM_STR);
                $query->bindParam(":date", $articolo['date'], PDO::PARAM_STR);
                $query->bindParam(":url", $database->quote($articolo['url']), PDO::PARAM_STR);
                $query->bindParam(":author_id", $articolo['author_id'], PDO::PARAM_INT);

                $query->execute();

                http_response_code(200);
                echo json_encode([
                    "message" => "Articolo inserito correttamente",
                    "post_id" => $database->lastInsertId()
                ]);

                header("refresh:5;url=" . "../articolo.php?post_id=" .$database->lastInsertId());
            } catch (PDOException $e) {
                // Rispondo con un errore, mostrando il messaggio
                http_response_code(500);
                echo json_encode(["message" => "Errore del database."]);
            }
        }
    } elseif ($_GET && $_GET['post_id'] && $_GET['action'] == "delete") {
        // Cancellazione articolo
        try {
            // Connessione al database
            $database = new PDO("mysql:host=localhost;dbname=csc_database", "root", "");
            // Imposto l'attributo del PDO (PHP Data Object) per la gestione degli errori tramite eccezioni
            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Preparo la query
            $query = $database->prepare("DELETE FROM articoli WHERE articoli.ID = :post_id");
            $query->bindParam(":post_id", $_GET['post_id'], PDO::PARAM_INT);

            $query->execute();
            
            http_response_code(200);
            echo json_encode([
                "message" => "Articolo eliminato"
            ]);

            header("refresh:5;url=" . "../articoli.php");

        } catch (PDOException $e) {
            // Rispondo con un errore, mostrando il messaggio
            http_response_code(500);
            echo json_encode(["message" => "Errore del database." . $e->getMessage()]);
        }

    } else {
        http_response_code(400);
        echo json_encode(["message" => "Alcuni dati obbligatori non sono stati forniti."]);
    }

?>