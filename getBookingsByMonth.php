<?php
    session_start();
    include "functions.php";
    
    if(!isset($_SESSION['userEmail'])){
        include("needAdmin.html");
        echo "<META HTTP-EQUIV='REFRESH' CONTENT='0;URL=close.php'>";
    }else{
        $year = $_REQUEST['year'];
        $month = $_REQUEST['month'];
        $sql = "SELECT * FROM booking WHERE YEAR(day) = '" . $year . "' AND MONTH(day) = '" . $month . "' AND status != 'CANCELLED'";
        $conection = connectDataBase();

        $bookingArray = array();

        $courtsSQL = "SELECT count(*) FROM court WHERE isAvailable = '1'";
        if(selectSQL($conection, $courtsSQL, $courtsResult)){
            $numOfCourts = $courtsResult[0]['count(*)'];
        }

        if(selectSQL($conection, $sql, $result)) {
            foreach ($result as $booking) {
                $bookingJSON = array(
                    'bookingId' => $booking["bookingId"],
                    'email' => $booking["email"],
                    'courtId' => $booking["courtId"],
                    'day' => $booking["day"],
                    'hour' => $booking["hour"],
                    'status' => $booking["status"],
                    'current' => $_SESSION['userEmail'],
                    'numCourts' => $numOfCourts
                );
                array_push($bookingArray, $bookingJSON);
            }
            header('Content-Type: application/json');
            $jsonResult = json_encode($bookingArray);

            print_r($jsonResult);
        }
    }   
?>