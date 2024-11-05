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


?>