<?php
    /**
     * Esercizio 4 - Leggo i dati degli utenti dal server e li stampo a video
     */

    // Inizializza la risorsa 
    $ch = curl_init();

    // Imposto l'indirizzo di destinazione
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, "https://dummyjson.com/users/1");

    // Eseguo la chiamata e salvo il risultato
    $result = curl_exec($ch);

    $user = json_decode($result, true);

    echo "Benvenuto, ". $user['firstName'] . " " . $user['lastName'];

    // Chiudo la sessione
    curl_close($ch);
?>