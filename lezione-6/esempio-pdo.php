<?php 

    // Provo a connettermi al database e a eseguire la query, in caso vengano generati errori li catturo con la catch
    try {
        // Connessione al database
        $database = new PDO("mysql:host=localhost;dbname=csc_database", "root", "");
        // Imposto l'attributo del PDO (PHP Data Object) per la gestione degli errori tramite eccezioni
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparo la query
            
            // Query: articoli con nome dell'autore
            // $query = $database->prepare("SELECT title, subtitle, content, date, nicename AS author FROM articoli JOIN utenti ON author_id = utenti.ID");

            // Query: articoli di Jane Smith con nome dell'autore
            $query = $database->prepare("SELECT title, subtitle, content, date, nicename AS author FROM articoli JOIN utenti ON author_id = utenti.ID WHERE author_id = 2");

        // Eseguo la query
        $query->execute();

        // Recupero i risultati sotto forma di array associativo
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        // Ciclo i risultati e li stampo a video 
        foreach ($result as $row) {
            // Converto la data nel formato desiderato
            $outputDate = date("j F y", strtotime($row['date']));
            ?>
                <div class="post">
                    <span class="post-date"><?php echo $outputDate;?></span>
                    <h2 class="post-title"><?php echo $row['title'];?></h2>
                    <h3 class="post-subtitle"><?php echo $row['subtitle'];?></h3>
                    <p class="post-content"><?php echo $row['content'];?></p>
                    <p class="post-author">by <?php echo $row['author'];?></p>
                </div>
            <?php
        }

    } catch (PDOException $e) {
        // In caso venga lanciata un'eccezione la catturo e la gestisco mostrando un messaggio di errore
        echo "Errore del database: " . $e->getMessage();
    }

?>


