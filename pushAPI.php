<?php
include("dbconnection.php");
// include_once("dbconnection.php");

$restaurantId = $_POST['id'];
$name = $_POST['name'];
$type = $_POST['type'];
$url = $_POST['url'];
$picture = $_POST['pictureUrl'];
// print_r($_POST);

 function insertRestaurant($r_id,$name,$type,$url,$picture, $db){

    $sql = "INSERT INTO RESTAURANT(restaurantid, name, type,  url, picture ) 
    VALUES ('$r_id' ,'$name','$type','$url','$picture');";
    pg_query($db, $sql) or die ("Can't add the user");
    die( json_encode(array(
        "status" => "success",
        "message" => 'Welcome',
        "data" => 'none'
        // "password" => 

    )) );
 }
 function deleteRestaurant($value, $db){
    $sql = "SELECT * from restaurant where name = '$value' or restaurantid ='$value';";
               $res = pg_query($db, $sql) or die ("Can't add the user");
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
 function rateRestaurant($mood,$food,$price,$staff, $comment,$restaurantId,$userid,$db){
    //  echo "you are inside the function";
    $sql = "SELECT * FROM RATING WHERE USERID = '$userid' and RESTAURANTID ='$restaurantid';";
    $result = pg_query($db, $sql) or die("couldnt rate");
    if(pg_num_rows($result) < 1){
        if(empty($userid) || empty($restaurantId)){
            die(json_encode(array(
                'status' => 'emptyUser',
                'message' => 'You need to Login to Rate'
            )));
        }
        // echo"You are in side the insertion";
        
         $sql = "INSERT INTO RATING( userid, date, price, food, mood, staff, comments, restaurantid )
         VALUES('$userid', NOW(), '$price', '$food', '$mood', '$staff', '$comment', '$restaurantId');";
         pg_query($db, $sql) or die("Cannot insert part 1");
         die(json_encode(array(
             'status' => 'success',
             'message' => 'Your rate has been insert into the DB'
         )));
    }else{
        $sql = "UPDATE RATING SET date = NOW(), price = '$price', food = '$food', mood='$mood', staff='$staff', comments='$comment'
        WHERE USERID = '$userid' AND RESTAURANTID = '$restaurantid';";
        pg_query($db, $sql) or die("Cannot insert part 2");
        die(json_encode(array(
            'status' => 'success',
            'message' => 'Your rate has been updated in the DB'
        )));
    }
 }

 function fetchAllFood($id, $db){
     if( empty($id)){
         $sql = "SELECT * FROM MENUITEM;";
         pg_query($db, $id) or die("Could not execute");
     }else{
         $sql = "SELECT * FROM MENUITEM WHERE itemid ILIKE '%$id%' or name ILIKE '%$id%';";
         pg_query($db,$id) or die("Could not execute");
         die(json_encode(array(
             'status'=> 'success',
             'meesage'=> 'Found samples'
         )));
     }
 }

 switch ($_POST['method']){
    case 'deleteRestaurant':
    // echo $_POST['method'] . "   ". $_POST['parameter']. 'these are the things';
        deleteRestaurant($_POST['parameter'], $db);
        break;
    case 'insertRestaurant':
        insertRestaurant($restaurantId,$name,$type,$url,$picture, $db);
        break;
    case 'rateRestaurant':
        rateRestaurant($_POST['mood'],$_POST['food'],$_POST['price'],$_POST['staff'],$_POST['comment'],$_POST['restaurantId'],$_POST['userId'],$db);
    
    case 'fetchAllFood':
    fetchAllFood($_POST['parameter'], $db);
}
?>