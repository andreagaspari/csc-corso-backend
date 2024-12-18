<?php
/**
 * Funzioni di controllo di autenticazione
 */

require_once('config.php');

/**
 * Funzione che verifica se l'utente ha fornito un token valido, restituendo l'id dell'utente.
 * In caso contrario ritorna false.
 */
function verifyToken($database, $token) {
    if (empty($token)) {
        return false;
    }

    $query = $database->prepare("SELECT user_id FROM tokens WHERE token = :token AND token_expiration > NOW()");
    
    // Imposto i parametri
    $query->bindParam(":token", $token, PDO::PARAM_STR);

    $query->execute();

    $result = $query->fetch();

    
    if ($result) {
        $user_id = $result['user_id'];

        // Aggiorno la scadenza a 10 minuti avanti
        $new_token_expiration = date('Y-m-d H:i:s', time() + 600);
                    
        // Preparo la query
        $query = $database->prepare("UPDATE tokens SET token_expiration = :token_expiration WHERE token = :token");

        // Imposto i parametri
        $query->bindParam(":token", $token, PDO::PARAM_STR);
        $query->bindParam(":token_expiration", $new_token_expiration, PDO::PARAM_STR);

        $query->execute();

        return $user_id;
    } else {
        return false;
    }
}

function cleanExpiredTokens($database) {
    $query = $database->prepare("DELETE FROM tokens WHERE token_expiration < NOW()");
    $query->execute();
}
