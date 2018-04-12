<?php
    session_start();
    include_once("dbconnection.php");

    function failLogin($errCode) {
        // http_response_code(401);
        failRequest($errCode);
    }

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        failLogin('wrongMethod');
    }

    if (empty($_POST)) {
        failLogin('emptyFields');
    }

    if (empty($_POST['userName'])) {
        failLogin('noUsername');
    }

    if (empty($_POST['password'])) {
        failLogin('noPassword');
    }

    $userName = $_POST['userName'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM RATER WHERE USERID = '$userName' or email = '$userName';";
    $queryResult = pg_query($db, $sql);
    $checkResult = pg_num_rows($queryResult);

    if ($checkResult < 1) {
        failLogin('noSuchUser');
    }

    $userExists = pg_fetch_assoc($queryResult);

    if (!$userExists) {
        failLogin('invalidID');
    }

    // check if the passwords matches
    if (!password_verify($password, $userExists['password'])) {
        failLogin('invalidPasswordorUserName');
    }

    $_SESSION['userid'] = $userExists['userid'];
    $_SESSION['name'] = $userExists['name'];
    $_SESSION['email'] = $userExists['email'];
    $_SESSION['type'] = $userExists['type'];

    die( json_encode(array(
        "status" => "success",
        "message" => 'Welcome',
        "data" => $_SESSION['userid']
        // "password" => 

    )) );
    // $db.close();
?>
