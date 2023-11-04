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

function getBookingsByUser() {
    $sql = "SELECT *
    FROM booking
    WHERE email = '" . $_SESSION['userEmail'] . "' AND day >= CURDATE()";

    $connect = connectDataBase();

    $result = mysqli_query($connect, $sql);

    return $result;
}

function selectSQL($connection, $sql, &$result) {
    $resultOk = false;

    try {
        $consulta = mysqli_query($connection, $sql);
        if ($consulta) {
            $resultOk = true;
            
            $result = mysqli_fetch_all($consulta, MYSQLI_ASSOC);
        }
        else throw new Exception(mysqli_sql_exception());
    } catch (Exception $e) {    
        echo "<p>Error en la consulta SQL</p>";
        errorMsg($e);
    }

    return $resultOk;
}

function createBookingDiv($booking) {
    $day = $booking['day'];
    $hour = $booking['hour'];
    $courtId = $booking['courtId'];
    $id = "booking" . $booking['bookingId'];
    $currDate = new DateTime($day);
    $dayName = $currDate->format('l');
    $dayNum = ltrim($currDate->format('d'), '0');
    $monthName = $currDate->format('F');

    $hourFormatted = preg_replace('/^0/', '', $hour);
    echo "<div class='booking'>";
    echo "<p><strong>$dayName $dayNum</strong></p>";
    echo "<p><strong>$monthName</strong></p>";
    echo "<p>At $hourFormatted</p>";
    echo "<p>Court $courtId</p>";
    echo "<button id='$id'>Cancel</button>";
    echo "</div>";
}
?>