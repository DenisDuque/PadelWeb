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
?>