<?php
    session_start();
    include "functions.php";
    
    if(!isset($_SESSION['userEmail'])){
        include("needAdmin.html");
        echo "<META HTTP-EQUIV='REFRESH' CONTENT='0;URL=close.php'>";
    }else{
        $date = $_REQUEST['date'];
        $hour = $_REQUEST['hour'];
        $sql = "SELECT courtId FROM court WHERE isAvailable = 1 AND courtId NOT IN (SELECT courtId from booking WHERE status != 'CANCELLED' AND day = '" . $date . "' AND hour = '" . $hour . "') LIMIT 1";
        $conection = connectDataBase();

        if(selectSQL($conection, $sql, $result)) {
            foreach ($result as $court) {
                $courtJSON = array(
                    'courtId' => $court['courtId']
                );
            }
            
            header('Content-Type: application/json');
            $jsonResult = json_encode($courtJSON);

            print_r($jsonResult);
        }
    }   
?>