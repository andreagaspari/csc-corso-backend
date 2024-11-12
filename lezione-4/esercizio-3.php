<?php
    /**
     * Esercizio 3: Crea un modulo con un elenco di selezioni (checkbox) e 
     * mostra un feedback nel momento in cui l'utente invia le sue scelte. 
     * Imposta un cookie per ricordare le scelte fatte dall'utente durante l'ultima visita.
     */

    // Se sono stati impostati dei cookie in precedenza
    if ($_COOKIE) {
        // Inizializzo le variabili pomodoro e mozzarella in base ai valori salvati
        $pomodoro = (array_key_exists("pomodoro", $_COOKIE) && $_COOKIE['pomodoro'] == "true") ? true : false;
        $mozzarella = (array_key_exists("mozzarella", $_COOKIE) && $_COOKIE['mozzarella'] == "true") ? true : false;
    }
    // Se è stato compilato il modulo
    if ($_GET) {
        // Inizializzo / Aggiorno le variabili in base alla selezione
        $pomodoro = (array_key_exists("pomodoro", $_GET)) ? true : false;
        $mozzarella = array_key_exists("mozzarella", $_GET);
    }

    // Inizializzo il gusto
    if ($pomodoro && $mozzarella) {
        $gusto = "margherita";
    } elseif ($pomodoro) {
        $gusto = "rossa";
    } else if ($mozzarella) {
        $gusto = "bianca";
    } else if (array_key_exists("result", $_GET)) {   
        $gusto = "fornarina";
    }

    // Se è stato scelto il gusto, salvo la scelta impostando i cookie
    if ($gusto) {
        setcookie("pomodoro", ($pomodoro) ? "true" : "false", time() + (60 * 60 * 24 * 7));
        setcookie("mozzarella", ($mozzarella) ? "true" : "false", time() + (60 * 60 * 24 * 7));
    }
?>

<html>
    <body>
        <p>Quali ingredienti vuoi sulla pizza?</p>
        <form method="get" action="">
            <input type="checkbox" name="pomodoro" id="pomodoro" value="pomodoro" <?php echo ($pomodoro) ? "checked" : ""; ?>/><label for="pomodoro">Pomodoro</label><br/>
            <input type="checkbox" name="mozzarella" id="mozzarella" value="mozzarella" <?php echo ($mozzarella) ? "checked" : ""; ?>/><label for="mozzarella">Mozzarella</label><br/>
            <input type="hidden" name="result" value="true" />
            <button type="submit">Invia</button>
        </form>
        <?php
            // Se è stato scelto un gusto, lo stampo
            if ($gusto) {
                ?>
                    <p>Ciao, <?php echo $_SESSION['username']; ?>.</p>
                    <p>Hai scelto la pizza <?php echo $gusto; ?>.</p>
                <?php
            }
        ?>  
    </body>
</html>