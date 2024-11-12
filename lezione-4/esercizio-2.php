<?php
    /**
     * Esercizio 2: Crea un modulo con un elenco di selezioni (checkbox) e 
     * mostra un feedback nel momento in cui l'utente invia le sue scelte
     */

    $pomodoro = (array_key_exists("pomodoro", $_GET)) ? true : false;
    $mozzarella = array_key_exists("mozzarella", $_GET);
        
    if ($pomodoro && $mozzarella) {
        $gusto = "margherita";
    } elseif ($pomodoro) {
        $gusto = "rossa";
    } else if ($mozzarella) {
        $gusto = "bianca";
    } else if (array_key_exists("result", $_GET)) {   
        $gusto = "fornarina";
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
            if ($gusto) {
                ?>
                    <p>Hai scelto la pizza <?php echo $gusto; ?>.</p>
                <?php
            }
        ?>  
    </body>
</html>