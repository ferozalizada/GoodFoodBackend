<?php

include_once("dbconnection.php");

 $itemid = $_POST['itemId'];
 $name = $_POST['name'];
 $type = $_POST['type'];
 $category = $_POST['category'];
 $description = $_POST['description'];
 $restaurantid = $_POST['$restaurantId'];
 $price = $_POST['price'];

 function insertMenuItem( $itemid, $name, $type, $category, $description, $price, $restaurantid , $db){
    $sqlQuery = "INSERT INTO MENUITEM ( itemid, name,  type, category, description, price, restaurantid ) 
    VALUES ('$itemid', '$name', '$type', '$category', '$description', '$price','$restaurantid' );";
                pg_query($db, $sqlQuery) or die ("Can't add the user");
                die( json_encode(array(
                    "status" => "success",
                    "message" => 'Added to the Restaurant Table',
                    "data" => 'none'
                    // "password" => 
    )) );
 }

 function deleteMenuItem($value, $db){
    $sql = " SELECT * FROM MENUITEM WHERE itemid = '$value' or name = '$value';";
    $res = pg_query($db, $sql) or die("Cant run the query");
    if(pg_num_rows($res < 1)){
        die( json_encode(array(
            "status" => "success",
            "message" => 'Added to the Restaurant Table',
            "data" => 'none'
        )) );
    }else{
         $sql = "DELETE from restaurant where name = '$value' or restaurantid ='$value';";
         $res = pg_query($db, $sql) or die ("Can't delete the Restaurant");
         if(pg_num_rows($res < 1)){
             die( json_encode(array(
                 "status" => "success",
                 "message" => 'Added to the Restaurant Table',
                 "data" => 'none'
                 // "password" => 
             )) );
        
         }
     }
 }
 switch ($_POST['method']){
     case 'deleteMenuItem':
     deleteMenuItem($_POST['parameter'], $db);
     break;
     case 'insertMenuItem':
     insertMenuItem($itemid, $name, $type, $category, $description, $price, $restaurantId , $db);
     break;
 }

 ?>