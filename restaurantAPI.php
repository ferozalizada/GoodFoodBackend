<?php
    include("dbconnection.php");

    $query = "SELECT * FROM RESTAURANT";
    $raters = pg_query($db, $query);
    // $output = "";
    
    $popularRestaurants = json_encode(pg_fetch_all($raters));

    // header('Content-Type: application/json');
    // return ($rater);
    echo $popularRestaurants;

    // echo $output;



class Raters {
    protected $conn;
    protected $result = array();
    function __construct() {
    
    $db = new dbObj();
    $connString =  $db->getConnstring();
    $this->conn = $connString;
    } 
    public function getRaters() {
        $sql = "SELECT * FROM RATER";
        $queryRecords = pg_query($this->conn, $sql) or die("Could not fetch rater's data");
        $result = pg_fetch_all($queryRecords);
        return json_encode($result);
    }
}
?>
