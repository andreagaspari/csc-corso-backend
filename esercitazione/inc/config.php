<?php
    define("DEBUG_MODE", true);
   
    if (DEBUG_MODE) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    define("DB_HOST", "localhost");
    define("DB_NAME", "csc-backend-esercitazione");
    define("DB_USER", "root");
    define("DB_PASSWORD", "");

?>