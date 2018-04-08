<?php
    header('Access-Control-Allow-Origin: *');

    if (isset($_post['submit'])){
        include_once('dbconnection.php');


        // $firstName = ($db, $_POST[$firstName]);
        // $lastName = ($db, $_POST[$lastName]);
        // $userName = ($db, $_POST[$userName]);
        // $email = ($db, $_POST[$email]);
        // $type = ($db, $_POST[$type]);
        // $password = ($db, $_POST[$password]);
        // $passwordConfirmation = ($db, $_POST[$passwordConfirmation]);



        echo $firstName;
        //Check if any field is empty
        // boolean $isEmpty = empty($firstName) || empty($lastName) || empty($userName) || empty($email) || empty($type) || empty($password) || empty($passwordConfirmation);
        // if($isEmpty)){
        //     header ('Location: addUser?addUser=empty');
        //     exit();
        // }
        // else{
            //     //check if the charachters are valid
        //     if(use preg_match){

        //     }
        // }

    }
    else{
        echo "Cant sign up else statement";
        header('Location: addUser');
        exit();
    }
    include("dbconnection.php");
    
    // "Access-Control-Allow-Headers": "Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        // header("Access-Control-Allow-Methods" : "GET,POST,PUT,DELETE,OPTIONS");

    


?>