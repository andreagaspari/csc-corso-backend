<?php 
    ini_set("display_errors", 1);
?>

<h1>Codice HTML statico</h1>

<?php
    // Stampa Hello World dentro un tag <p>
    echo "<p>Hello World</p>";
?>

<!-- Stampa Hello World dentro un tag <p> -->
<p><?php echo "Hello World"; ?></p>

<h2>Codice HTML statico</h2>

<?php
    /*  Le operazioni artimetiche predefinite sono le stesse 4 della matematica
        vista a scuola, con in più l'operatore resto che ritorna il resto della 
        divisione tra numeri interi. 
     */
    echo 4 + 3; // Stampa il risultato della somma
    echo 3 - 1; // Stampa il risultato della sottrazione
    echo 2 * 3; // Stampa il risultato della moltiplicazione
    echo 6 / 2; // Stampa il risultato della divisione
    echo 6 % 5; // Stampa il risultato del resto della divisione
    echo "<br>"; // Stampa un a capo

    // Variabili
    $numero = 5; // Dichiarazione e asseganzione di un valore ad una variabile
    $numero = 7; // Assegnazione di un nuovo valore ad una variabile

    // Numeri interi
    $numero_intero = 5; // Dichiarazione e assegnazione di un valore ad una variabile
    echo $numero_intero / 2;
    $numero_intero = 14e5; // Notazione scientifica 1400000

    // Numeri decimali
    $numero_decimale = 5.5; // Dichiarazione e assegnazione di un valore ad una variabile
    $numero = 2.3e-4; // Notazione scientifica 0.00023

    // Notazione esadecimale
    $numero = 0xFF; // 255 in formato esadecimale

    // Stringhe
    $stringa = "Hello World"; // Dichiarazione e assegnazione di un valore ad una variabile
    $strigna = 'Hello World'; // Dichiarazione e assegnazione di un valore ad una variabile

    // Concatenazione tra stringhe
    $stringa = "Hello" . " World"; // Hello World

    // Interpolazione di stringhe
    $user = "Mario";
    echo "Ciao $user"; // Ciao Mario

    // Escape di caratteri
    echo "<span style='color: red;'></span>";
    echo '<span style="color: red;"></span>';
    echo "<span style=\"color: red;\"></span>";
    echo '<span style=\'color: red;\'></span>';

    echo "Ciao " . $numero; 
    // $numero è una variabile di tipo intero con valore 5
    $numero = "Ciao " . $numero;
    // $numero ora è una stringa con valore "Ciao 5"

    // Conversione tra tipi
    $numero = "872gfvhbio";
    $numero = $numero + 1; // 873

    // Booleani
    $boolean = true; // Corrisponde a 1
    $boolean = false; // Corrisponde a 0

    // Array semplice
    $array = array(1, 2.2, "Pluto", 4, false);
    echo $array[2]; // Pluto 

    // Array semplice con sintassi alternativa
    $array = [1, 2, 3, 4, 5]; 

    // Array associativi
    $utente1 = array(
        "nome" => "Mario",
        "cognome" => "Rossi",
        "eta" => 30
    );

    // Stampa di un array associativo
    echo "Ciao, " . $utente["nome"] .  " " . $utente["cognome"];

    // Array multidimensionali
    $utenti = [$utente1, $utente2, $utente3];

    // Condizioni
    if ($numero > 5) {
        echo "Il numero è maggiore di 5";
    } else {
        echo "Il numero è minore o uguale a 5";
    }  

    /*
        Se l'utente è over 30 stampa "Salve", 
        se è tra 18 e 30 stampa "Buongiorno", 
        altrimenti stampa "Ciao"
    */
    if ($utente['eta'] > 30) {
        echo "Salve";
    } elseif ($utente['eta'] >= 18) {
        echo "Buongiorno";
    } else {
        echo "Ciao";
    }

    /* 
        Se l'utente ha un'età compresa tra 18 e 30
        stampa Buongiorno, altrimenti stampa Ciao
     */
    if ($utente["eta"] > 18 && $utente["eta"] < 30) {
        echo "Buongiorno";
    } else {
        echo "Ciao";
    }

    echo "<br />";
    $utente = array(
        "nome" => "Mario",
        "cognome" => "Rossi"
    );

    // La prima condizione è falsa, quindi non controllo la seconda.
    if (array_key_exists("eta", $utente) && $utente["eta"] > 18) {
        echo "Buongiorno";
    }

    // Se l'utente ha più di 30 anni o il suo nome è Mario
    if ($utente["nome"] == "Mario" || $utente["eta"] > 30) {
        echo "Ciao Mario";
    }

    // Controllo l'esistenza delle chiavi prima di leggere i valori
    if ((array_key_exists("nome", $utente) && $utente["nome"] == "Mario") 
        || (array_key_exists("eta", $utente) && $utente["eta"] > 30)) {
        
    }

    // Xor: Solo una delle due condizioni è vera, l'altra è falsa
    if ($utente["nome"] == "Mario" xor $utente["cognome"] == "Bianchi") {

    }

    // Not: Inverte il valore della condizione (es. se NON esiste l'utente)
    if (!$utente) {
       echo "Non ho l'utente da mostrare"; 
    }

    // Confronto per valore
    if ("30" == 30) 
        echo "Vero"; // Il valore è uguale, quindi stampo vero
        else 
    echo "Falso";

    // Confronto per valore e per tipo
    if ("30" === 30)
        echo "Vero";
    else 
        echo "Falso"; // Il valore è uguale ma il tipo è diverso, quindi stampo falso
    
    /* Operatori di confronto
        > Maggiore
        < Minore
        >= Maggiore o uguale
        <= Minore o uguale
        == Uguale
        === Uguale per valore e tipo
        != Diverso
     */

    // Operatore Ternario (CONDIZIONE) ? VERO : FALSO
    echo (array_key_exists("nome", $utente)) ? "Buongiorno, ". $utente['nome'] : "Benvenuto nuovo utente";

    $utenti = array("pippo", "pluto", "paperino");
    
    // Ciclo for
    /*
        Inizializzazione: $i = 0 (solo la prima volta)

        Condizione: $i < count($utenti) (finché $i è minore della lunghezza dell'array)
        Istruzioni: echo $utenti[$i] (Stampo l'elemento dell'array)
        Incremento: $i++ (Incremento il contatore)
    */
    for ($i = 0; $i < count($utenti); $i++) {
        echo $utenti[$i];
    }

    // Cerco l'utente pippo, se esiste lo saluto
    for ($i = 0; $i < count($utenti); $i++) {
        if ($utenti[$i] == "pippo") {
            echo "Ciao pippo";
        }
    }

    // Ciclo while
    /*
        Finchè la condizione è vera ripeti le istruzioni all'interno del ciclo
    */
    $i = 0;
    while ($utenti[$i] != "pippo" && $i < count($utenti)) {
        $i++;
    }

    // Ciclo foreach
    foreach($utenti as $valore) {
        echo $valore;
    }
    
?>