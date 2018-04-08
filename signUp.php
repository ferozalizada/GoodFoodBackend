<?php

include_once("dbconnection.php");
// echo "Hi this is the post variable";
// print_r($_POST);
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)){
    $_POST = json_decode(file_get_contents('php://input'), true);
    // echo "Submit is clicked!";

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $userName = $_POST['userName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $type = $_POST['type'];
    $reputation = $_POST['reputation'];

    if(empty(firstName) || empty(lastName) || empty(userName) || empty(password) || empty(type)){
        header("Location: ./singUp.php?signup=empty");
        echo 'Empty fields';
        exit();
    }
    else{
        $sql = "SELECT * FROM RATER WHERE USERID = '$userName' OR EMAIL = '$email' ;";
        $queryResult = pg_query($db,$sql);
        $checkRows = pg_num_rows($queryResult);
        if($checkRows > 0){
            echo "User already exits!";
            header("Location: ./signUp.php?signup=userTaken");
            // log back to the user the error
            exit();
        }
        else{
            // hash the password
            //NEED TO ADD A PASSWORD FIELD
            $hashedValue = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO RATER (USERID, EMAIL, NAME, JOIN_DATE, TYPE, REPUTATION, PASSWORD) 
            VALUES('$userName', '$email', '$firstName . $lastName', NOW(), '$type', 1, '$hashedValue');";
            pg_query($db, $sql) or die ("Can't add the user");
            echo "User added to the db successfully on the backend";
            header("Location: ./signUp.php?signUp=success");
            exit();
        }

    }
}

?>