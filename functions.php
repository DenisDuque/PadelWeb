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

function validateUser($email, $password, &$incorrectCredentials) {
    $sql = "SELECT email, name, surname, isActive, isAdmin FROM user WHERE email = '".$email."' AND password = '".$password."'";
        
    $connect = connectDataBase();
    $result = $connect->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if($row['isActive'] == 1) {
            $_SESSION['userEmail'] = $row['email'];

            if($row['isAdmin'] == 0) {
                $completeName = $row['name']." ".$row['surname'];
                $_SESSION['completeName'] = $completeName;
                echo '<meta http-equiv="refresh" content="0;url=user.php">';
            } else {
                // Trying to enter with an Administrator account
                $incorrectCredentials = "Use the desktop app to join as Administrator";
            }
        } else {
            // Trying to login with a non active account
            $incorrectCredentials = "This user has been disabled";
        }
    } else {
        // Trying to login with no matching user entries
        $incorrectCredentials = "Incorrect credentials";
    }

    $connect->close();
}

function getBookingsByMonth($month) {
    $sql = "SELECT * FROM booking WHERE day LIKE '*-".$month."-*' AND email = '".$_SESSION['userEmail']."' AND status LIKE '*PAID'";
    $connect = connectDataBase();

    $result = mysqli_query($connect, $sql);

    return $result;
}
?>