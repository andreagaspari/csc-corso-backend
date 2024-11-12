<?php

    /** Esercizio 1 - Crea un modulo di contatto che raccoglie 
     * Nome, E-mail e Messaggio e invia una mail al gestore del sito web
     */
    
    if ($_POST) {

        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $msg = $_POST['messaggio'];

        $to      = 'info@immaginificio.com';
        $subject = 'Hai ricevuto una richiesta dal sito';
        $message = 'Hai ricevuto una richiesta da ' . $nome .' ('.$email.'): \n'.$msg;
        $headers = 'From: noreply@immaginificio.com' . "\r\n" .
            'Reply-To: '. $email . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        if (mail($to, $subject, $message, $headers)) {
            echo "Il messaggio è stato inviato con successo.";
        } else {
            echo "Il messaggio non è stato inviato.";
        }

    } else {
        ?>

            <html>
                <body>
                    <form method="post" action="">
                        <input type="text" name="nome" placeholder="Nome"/>
                        <input type="email" name="email" placeholder="Email"/>
                        <textarea name="messaggio" placeholder="Messaggio"></textarea>
                        <button type="submit">Invia</button>
                    </form>
                </body>
            </html>

        <?php
    }
