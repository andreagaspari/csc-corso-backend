<?php
    
    // Connessione al database
    $db = new mysqli("localhost", "root", "", "csc_database");

    // Verifico se la connessione restituisce errori
    if ($db->connect_error) {
        // Termino l'esecuzione del programma
        die("Connessione fallita: " . $db->connect_error);
    } 

    // Eseguo la query sul database

        // Query: articoli con nome dell'autore
        //$result = $db->query("SELECT title, subtitle, content, date, nicename AS author FROM articoli JOIN utenti ON author_id = utenti.ID");

        // Query: articoli di Jane Smith con nome dell'autore
        $result = $db->query("SELECT title, subtitle, content, date, nicename AS author FROM articoli JOIN utenti ON author_id = utenti.ID WHERE author_id = 2");

    // Per ogni riga restituita (se non ci sono risultati non entra nel ciclo)
    while ($row = $result->fetch_assoc()) {
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

    // Chiudo la connessione al database
    $db->close();

?>