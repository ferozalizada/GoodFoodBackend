<?php
    // database connection configuration
    header('Access-Control-Allow-Headers: *');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');

    $host = "host = localhost";
    $port = "port = 5435";
    $dbname = "dbname = ferozalizada";
    $credentials = "user = ferozalizada password = ";

    function failRequest($errCode) {
        die(json_encode(array(
            "status" => "error",
            "code" => $errCode
        )));
    }

    $db = pg_connect("$host $port $dbname $credentials");

    if ($db) {
        //select schema from database
        $schema_name = "\"goodfood\"";
        $setpath = pg_query($db, "SET search_path = $schema_name;") or failRequest("connError");
    } else {
        //connection to database successfull
        failRequest("connFailed");
    }
?>
