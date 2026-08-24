<?php

function connect($flag = TRUE) {

    $servername = getenv("DB_HOST") ?: "mysql";
    $username = getenv("DB_USER") ?: "restaurant";
    $password = getenv("DB_PASSWORD") ?: "";
    $dbName = getenv("DB_NAME") ?: "res_booking";

    if ($flag) {
        $conn = new mysqli(
            $servername,
            $username,
            $password,
            $dbName
        );
    } else {
        $conn = new mysqli(
            $servername,
            $username,
            $password
        );
    }

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

?>