<?php

    define("DB_HOST", "localhost");
    define("DB_USER", "root");
    define("DB_PASS", "");
    define("DB_NAME", "internshipdb");

    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // check connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
?>
