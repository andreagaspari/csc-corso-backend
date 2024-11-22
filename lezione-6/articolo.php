<?php
/**
 * Pagina che mostra un singolo articolo del blog, completo 
 * delle informazioni dell'autore (Nome, Cognome e Indirizzo mail)
 */


if ($_GET['post_id']) {
    $post_id = $_GET['post_id'];
} 

// Connessione al database
$db = new mysqli("localhost", "root", "", "csc_database");

// Verifico se la connessione restituisce errori
if ($db->connect_error) {
    // Termino l'esecuzione del programma
    die("Connessione fallita: " . $db->connect_error);
} 

// Eseguo la query sul database
    // Query: articolo singolo con dati utente
    $query = $db->prepare("SELECT title, subtitle, content, date, author_id, name, surname, mail FROM articoli JOIN utenti ON author_id = utenti.ID WHERE articoli.ID = ?");

    if (!$query) {
        die("Errore: " . $db->error);
    }

    $query->bind_param("i", $post_id);    

    $query->execute();

    $result = $query->get_result();

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
                <p class="post-author">by 
                    <a href="<?php echo 'articoli.php?author_id=' . $row['author_id'];?>">
                        <?php echo $row['name'] . " " . $row['surname'] . " (" . $row['mail'] . ")";?></p>
                    </a> 
            </div>
        <?php
    }

// Chiudo la query e la connessione al database
$query->close();
$db->close();