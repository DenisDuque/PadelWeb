<?php
function loginRedirect() {
    echo '<meta http-equiv="refresh" content="0;url=user.php">';
}

function connectDataBase() {
    $host = 'localhost';
    $dbName = 'padel';
    $dbUsername = 'root';
    $dbPass = '';

    $connect = mysqli_connect($host, $dbUsername, $dbPass, $dbName);

    if($connect == false){
        echo mysqli_connect_error();
    }

    return $connect;
}

function getBookingsByMonth($month) {
    $sql = "SELECT * FROM booking WHERE day LIKE '*-".$month."-*' AND email = '".$_SESSION['userEmail']."' AND status LIKE '*PAID'";
    $connect = connectDataBase();

    $result = mysqli_query($connect, $sql);

    return $result;
}
?>