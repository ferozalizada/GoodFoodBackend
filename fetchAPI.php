<?php
    include_once("dbconnection.php");

    function failed($errCode) {
        // http_response_code(401);
        failRequest($errCode);
    }
// -- needs no case sensitivity
    function getRestaurantLocationByID($id, $db){
        $sqlQuery = "SELECT * FROM RESTAURANT AS RES 
        NATURAL INNER JOIN LOCATION AS LOC
        WHERE RES.RESTAURANTID ILIKE '%$id%' or RES.NAME ILIKE  '%$id%';";
        $result = pg_query($db, $sqlQuery) or die("Could not execute the query!...");
        $jsonObj = json_encode(pg_fetch_all($result));
        echo $jsonObj;
    }
    function getMenuOfRestaurant($name, $db){
        $sqlQuery = "SELECT RES.NAME,MI.NAME,MI.TYPE,MI.CATEGORY,MI.DESCRIPTION,MI.PRICE
        FROM MENUITEM AS MI INNER JOIN RESTAURANT AS RES ON MI.RESTAURANTID = RES.RESTAURANTID
        WHERE RES.NAME = '$name'
        ORDER BY MI.CATEGORY;";
        $result = pg_query($db, $sqlQuery) or die("Could not execute the query!...");
        $jsonObj = json_encode(pg_fetch_all($result));
        echo $jsonObj;
    }
    function getTypeDetails($type, $db){
        $sqlQuery = "SELECT RES.NAME,TYPE,HOUR_OPEN,HOUR_CLOSE,STREET_ADDRESS,PHONE_NUMBER,MANAGER_NAME,FIRSTOPEN_DATE
        FROM RESTAURANT AS RES NATURAL INNER JOIN LOCATION AS LOC
        WHERE RES.TYPE = '$type';";
        $result = pg_query($db, $sqlQuery) or die("Could not execute the query!...");
        $jsonObj = json_encode(pg_fetch_all($result));
        echo $jsonObj;
    }
    function getTypeDetailsByCategory($type, $db){//c-1
        $sqlQuery = "SELECT RES.NAME,TYPE,HOUR_OPEN,HOUR_CLOSE,STREET_ADDRESS,PHONE_NUMBER,MANAGER_NAME,FIRSTOPEN_DATE
        FROM RESTAURANT AS RES NATURAL INNER JOIN LOCATION AS LOC
        WHERE RES.TYPE = '$type';";
        $result = pg_query($db, $sqlQuery) or die("Could not execute the query!...");
        $jsonObj = json_encode(pg_fetch_all($result));
        echo $jsonObj;
    }
    function getSpecialsDetail($type, $db){//c-1
        $sqlQuery = "SELECT RES.NAME,MAX(PRICE) AS MAX_PRICE,MANAGER_NAME,HOUR_OPEN,HOUR_CLOSE,HOUR_CLOSE
        FROM MENUITEM AS MI  INNER JOIN RESTAURANT AS RES ON MI.RESTAURANTID = RES.RESTAURANTID 
        INNER JOIN LOCATION AS LOC ON RES.RESTAURANTID = LOC.RESTAURANTID
        GROUP BY RES.NAME,MANAGER_NAME,HOUR_OPEN,HOUR_CLOSE,HOUR_CLOSE;";

        $result = pg_query($db, $sqlQuery) or die("Could not execute the query!...");
        $jsonObj = json_encode(pg_fetch_all($result));
        echo $jsonObj;
    }
    function getAvgMenuItem($type, $db){//c-1
        $sqlQuery = "SELECT RES.TYPE,MI.CATEGORY,round( AVG(PRICE), 2) AS Average_Price
        FROM MENUITEM AS MI  INNER JOIN RESTAURANT AS RES ON MI.RESTAURANTID = RES.RESTAURANTID
        GROUP BY RES.TYPE,MI.CATEGORY;";
        $result = pg_query($db, $sqlQuery) or die("Could not execute the query!...");
        $jsonObj = json_encode(pg_fetch_all($result));
        echo $jsonObj;
    }

    function getRatingsForEach($type, $db){//c-1
        $sqlQuery = "SELECT RATER.NAME,RESTAURANT.NAME,round(AVG(RATING.FOOD+RATING.MOOD+RATING.PRICE+RATING.STAFF)/4, 2) AS average_rating, COUNT(RESTAURANT.RESTAURANTID) as num_reviews
        FROM RESTAURANT INNER JOIN RATING ON RATING.RESTAURANTID = RESTAURANT.RESTAURANTID INNER JOIN RATER on RATING.USERID = RATER.USERID
        GROUP BY RATER.NAME,RESTAURANT.NAME
        order by num_reviews DESC, average_rating DESC;";
        $result = pg_query($db, $sqlQuery) or die("Could not execute the query!...");
        $jsonObj = json_encode(pg_fetch_all($result));
        echo $jsonObj;
    }
    function getRestaurantRatingNotCondition($type, $db){//c-1
        $sqlQuery = "SELECT DISTINCT RES.NAME,RES.TYPE,LOC.PHONE_NUMBER
        FROM RESTAURANT AS RES NATURAL INNER JOIN LOCATION AS LOC INNER JOIN MENUITEM AS MI on RES.RESTAURANTID = MI.RESTAURANTID
        WHERE MI.ITEMID IN (SELECT ITEMID FROM RATINGITEM WHERE (DATE >= '01-01-2015' OR DATE <= '01-31-2015'));";
        $result = pg_query($db, $sqlQuery) or die("Could not execute the query!...");
        $jsonObj = json_encode(pg_fetch_all($result));
        echo $jsonObj;
    }
    function getStaffRatingLowerThanRater($string, $db){//c-1
        $sqlQuery = "SELECT DISTINCT RES.NAME,LOC.HOUR_OPEN,LOC.HOUR_CLOSE
        FROM RESTAURANT AS RES NATURAL INNER JOIN LOCATION AS LOC INNER JOIN RATING AS RA ON RA.RESTAURANTID = RES.RESTAURANTID
        WHERE RA.STAFF <= (SELECT MIN(PRICE) FROM RATING WHERE USERID = '$string')
        AND RA.STAFF <= (SELECT MIN(MOOD) FROM RATING WHERE USERID = '$string')
        AND RA.STAFF <= (SELECT MIN(FOOD) FROM RATING WHERE USERID = '$string')
        AND RA.STAFF <= (SELECT MIN(STAFF) FROM RATING WHERE USERID = '$string');
        ";
        $result = pg_query($db, $sqlQuery) or die("Could not execute the query!...");
        $jsonObj = json_encode(pg_fetch_all($result));
        echo $jsonObj;
    }
    function getHighRatedFoodbyRestaurantType($string, $db){//c-1
        $sqlQuery = "SELECT DISTINCT R1.*,L.*
        FROM RESTAURANT AS R1 INNER JOIN LOCATION L on R1.RESTAURANTID = L.RESTAURANTID INNER JOIN RATING AS RA on RA.RESTAURANTID = R1.RESTAURANTID
        WHERE R1.TYPE = '$string' AND RA.FOOD = (SELECT MAX(FOOD)
        FROM RESTAURANT AS R2 INNER JOIN RATING AS RA1 on R2.RESTAURANTID = RA1.RESTAURANTID
        WHERE R2.TYPE = '$string')
        ORDER BY R1.RESTAURANTID;
        ";
        $result = pg_query($db, $sqlQuery) or die("Could not execute the query!...");
        $jsonObj = json_encode(pg_fetch_all($result));
        echo $jsonObj;
    }

    function getTypePopularity($string, $db){//j
        $sqlQuery = "SELECT TYPE, COUNT(*) AS TOTAL_REVIEWS
        FROM RESTAURANT NATURAL INNER JOIN RATING
        GROUP BY TYPE
        ORDER BY TOTAL_REVIEWS DESC;
        ";
        $result = pg_query($db, $sqlQuery) or die("Could not execute the query!...");
        $jsonObj = json_encode(pg_fetch_all($result));
        echo $jsonObj;
    }

    function getHighestRater($string, $db){//j
        $sqlQuery = "SELECT RAT.NAME,RAT.JOIN_DATE,RAT.REPUTATION,AVG(R.FOOD+R.MOOD)/2 AS AVG_FOOD_MOOD, RES.NAME, R.DATE
        FROM RATER AS RAT INNER JOIN RATING AS R ON RAT.USERID = R.USERID INNER JOIN RESTAURANT AS RES ON R.RESTAURANTID = RES.RESTAURANTID
        GROUP BY RAT.NAME,RAT.JOIN_DATE,RAT.REPUTATION,RES.NAME, R.DATE
        HAVING AVG(R.FOOD+R.MOOD)/2 > 4
        ORDER BY RAT.REPUTATION DESC,RAT.NAME;
        ";
        $result = pg_query($db, $sqlQuery) or die("Could not execute the query!...");
        $jsonObj = json_encode(pg_fetch_all($result));
        echo $jsonObj;
    }
    function getHighestRaterP2($string, $db){//j
        $sqlQuery = "SELECT R.NAME,R.REPUTATION,RES.NAME, RAT.MOOD, RAT.FOOD ,RAT.DATE
        FROM RATING AS RAT INNER JOIN RESTAURANT AS RES ON RAT.RESTAURANTID = RES.RESTAURANTID INNER JOIN RATER R ON RAT.USERID = R.USERID
        WHERE (RAT.USERID IN (SELECT USERID FROM RATING WHERE RAT.FOOD >= 4)
            OR RAT.USERID IN (SELECT USERID FROM RATING WHERE RAT.MOOD >= 4));
        ";
        $result = pg_query($db, $sqlQuery) or die("Could not execute the query!...");
        $jsonObj = json_encode(pg_fetch_all($result));
        echo $jsonObj;
    }

    function getFrequentRater($string, $db){//j
        $sqlQuery = "SELECT R2.NAME,R2.REPUTATION,R3.COMMENTS,M.NAME,R.COMMENTS,M.PRICE
        FROM MENUITEM M INNER JOIN RATINGITEM R on M.ITEMID = R.ITEMID
            INNER JOIN RATER R2 on R.USERID = R2.USERID
            INNER JOIN RATING R3 on R.DATE = R3.DATE
            INNER JOIN RESTAURANT R4 on M.RESTAURANTID = R4.RESTAURANTID
        WHERE R4.RESTAURANTID = '$string' AND R3.USERID IN
          (SELECT Y.USERID
            FROM (SELECT R.USERID, COUNT(RATING.USERID) AS num
            FROM RATING INNER JOIN RESTAURANT ON RATING.RESTAURANTID = RESTAURANT.RESTAURANTID 
            INNER JOIN RATER R on RATING.USERID = R.USERID
            WHERE RESTAURANT.RESTAURANTID = '$string'
            GROUP BY R.USERID
            ORDER BY num DESC
            LIMIT 1) y);
        ";
        $result = pg_query($db, $sqlQuery) or die("Could not execute the query!...");
        $jsonObj = json_encode(pg_fetch_all($result));
        echo $jsonObj;
    }
    function getRatingComparison($string, $db){//j
        $sqlQuery = "SELECT DISTINCT R.NAME,R.EMAIL,R2.PRICE+R2.FOOD+R2.STAFF+R2.MOOD/4 as rating_
        FROM RATER R NATURAL INNER JOIN RATING R2
        WHERE R2.PRICE+R2.FOOD+R2.STAFF+R2.MOOD/4 <
                    (SELECT MAX(R2.PRICE+R2.FOOD+R2.STAFF+R2.MOOD)/4 AS AVG_RATING
                    FROM RATER R NATURAL INNER JOIN RATING R2
                    WHERE R.NAME = '$string'
                    ORDER BY AVG_RATING DESC
                    LIMIT 1);";
        $result = pg_query($db, $sqlQuery) or die("Could not execute the query!...");
        $jsonObj = json_encode(pg_fetch_all($result));
        echo $jsonObj;
    }

    function getDiverseRating($string, $db){//j
        $sqlQuery = "SELECT R.NAME,R.TYPE,R.EMAIL,R2.PRICE,R2.FOOD,R2.MOOD,R2.STAFF,R2.DATE,R3.NAME
        FROM RATER R NATURAL INNER JOIN RATING R2 INNER JOIN RESTAURANT R3 ON R2.RESTAURANTID = R3.RESTAURANTID
        WHERE (SELECT MAX(FOOD) -
                    (SELECT MIN(FOOD)
                        FROM RATING
                        WHERE RATING.USERID = R.USERID
                                    AND R3.RESTAURANTID = RATING.RESTAURANTID)
                     FROM RATING
                     WHERE RATING.USERID = R.USERID
                                 AND R3.RESTAURANTID = RATING.RESTAURANTID) >= 2
        OR (SELECT MAX(PRICE) -
                             (SELECT MIN(PRICE)
                                FROM RATING
                                WHERE RATING.USERID = R.USERID
                                            AND R3.RESTAURANTID = RATING.RESTAURANTID)
                FROM RATING
                WHERE RATING.USERID = R.USERID
                            AND R3.RESTAURANTID = RATING.RESTAURANTID) >= 2
        OR (SELECT MAX(MOOD) -
                             (SELECT MIN(MOOD)
                                FROM RATING
                                WHERE RATING.USERID = R.USERID
                                            AND R3.RESTAURANTID = RATING.RESTAURANTID)
                FROM RATING
                WHERE RATING.USERID = R.USERID
                            AND R3.RESTAURANTID = RATING.RESTAURANTID) >= 2
        OR (SELECT MAX(STAFF) -
                             (SELECT MIN(STAFF)
                                FROM RATING
                                WHERE RATING.USERID = R.USERID
                                            AND R3.RESTAURANTID = RATING.RESTAURANTID)
                FROM RATING
                WHERE RATING.USERID = R.USERID
                            AND R3.RESTAURANTID = RATING.RESTAURANTID) >= 2;";
        $result = pg_query($db, $sqlQuery) or die("Could not execute the query!...");
        $jsonObj = json_encode(pg_fetch_all($result));
        echo $jsonObj;
    }





    
    switch ($_POST['method']){
        case 'getRestaurantLocationByID':
        // echo $_POST['method'] . "   ". $_POST['parameter']. 'these are the things';
            getRestaurantLocationByID(($_POST['parameter']), $db);
            break;
        case 'getMenuOfRestaurant':
            getMenuOfRestaurant($_POST['parameter'], $db);
            break;
        case 'getTypeDetails':
            getTypeDetails($_POST['parameter'], $db);
            break;
        case 'getTypeDetailsByCategory':
            getTypeDetailsByCategory($_POST['parameter'], $db);
            break;
        case 'getSpecialsDetail':
            getSpecialsDetail($_POST['parameter'], $db);
            break;
        case 'getAvgMenuItem':
            getAvgMenuItem($_POST['parameter'], $db);
            break;
        case 'getRatingsForEach':
            getRatingsForEach($_POST['parameter'], $db);
            break;
        case 'getRestaurantRatingNotCondition':
            getRestaurantRatingNotCondition($_POST['parameter'], $db);
            break;
        case 'getStaffRatingLowerThanRater':
            getStaffRatingLowerThanRater($_POST['parameter'], $db);
            break;
        case 'getHighRatedFoodbyRestaurantType':
            getHighRatedFoodbyRestaurantType($_POST['parameter'], $db);
            break;
        case 'getTypePopularity':
            getTypePopularity($_POST['parameter'], $db);
            break;
        case 'getHighestRater':
            getHighestRater($_POST['parameter'], $db);
            break;
        case 'getHighestRaterP2':
            getHighestRaterP2($_POST['parameter'], $db);
            break;
        case 'getFrequentRater':
            getFrequentRater($_POST['parameter'], $db);
            break;
        case 'getRatingComparison':
            getRatingComparison($_POST['parameter'], $db);
            break;
        case 'getDiverseRating':
            getDiverseRating($_POST['parameter'], $db);
            break;
    }
?>