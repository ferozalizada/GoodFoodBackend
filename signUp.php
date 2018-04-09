<?php

include_once("dbconnection.php");
// echo "Hi this is the post variable";
// print_r($_POST);
header('Access-Control-Allow-Origin: *');
// print_r($_POST);

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $userName = $_POST['userName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $type = $_POST['type'];
    $reputation = $_POST['reputation'];

    if(empty(firstName) || empty(lastName) || empty(userName) || empty(password) || empty(type)){
        die( json_encode(array(
            "status" => "failed",
            "message" => 'Welcome',
            "data" => 'emptyField'
            // "password" => 
    
        )) );
    }
    else{
        $sql = "SELECT * FROM RATER WHERE USERID = '$userName' OR EMAIL = '$email' ;";
        $queryResult = pg_query($db,$sql);
        $checkRows = pg_num_rows($queryResult);
        if($checkRows > 0){
            die( json_encode(array(
                "status" => "taken",
                "message" => 'User Already Take',
                "data" => 'none'
                // "password" => 
        
            )) );
        }
        else{
            $hashedValue = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO RATER (USERID, EMAIL, NAME, JOIN_DATE, TYPE, REPUTATION, PASSWORD) 
            VALUES('$userName', '$email', '$firstName . $lastName', NOW(), '$type', 1, '$hashedValue');";
            pg_query($db, $sql) or die ("Can't add the user");
            die( json_encode(array(
                "status" => "success",
                "message" => 'Welcome',
                "data" => 'none'
                // "password" => 
        
            )) );
        }

    }

?>