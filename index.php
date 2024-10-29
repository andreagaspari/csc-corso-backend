<?php
    // Stampa Hello World e va a capo
    echo "Hello World<br/>";

    /*  Le operazioni artimetiche predefinite sono le stesse 4 della matematica
        vista a scuola, con in più l'operatore resto che ritorna il resto della 
        divisione tra numeri interi. 
     */
    echo 4 + 3; // Stampa il risultato della somma
    echo 3 - 1; // Stampa il risultato della sottrazione
    echo 2 * 3; // Stampa il risultato della moltiplicazione
    echo 6 / 2; // Stampa il risultato della divisione
    echo 6 % 5; // Stampa il risultato del resto della divisione

    echo "<br/>"; // Vado a capo
    $risultato = 8 - 4; // Dichiaro una variabile e la inizializzo con il risultato dell'operazione
    
    echo $risultato; // Stampo il contenuto della variabile risultato
    echo "<br/>"; // Vado a capo
   
    echo "8 - 4 = ".$risultato; // Concatenazione tra stringhe
    echo "<br/>"; // Vado a capo
   
    // Con echo posso stampare più stringhe in una volta, separate da ","
    echo "8 - 4 = ", $risultato;
    echo "<br/>"; // Vado a capo
    
    // Con print posso stampare solo una stringa alla volta.
    print "8 - 4 = "; 
    print $risultato;

// Ciao

?>


