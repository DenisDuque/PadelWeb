<?php
function loginRedirect() {
    echo '<meta http-equiv="refresh" content="0;url=user.php">';
}

function connectDataBase() {
    $host = 'localhost';
    $dbName = 'padel';
    $dbUsername = 'root';
    $dbPass = '';

    try {
        $connect = mysqli_connect($host, $dbUsername, $dbPass, $dbName);
    } catch (Exception $e){
        $connect = false;
    }

    if($connect == false){
        
        return false;
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
    $id = $booking['bookingId'];
    $currDate = new DateTime($day);
    $dayName = $currDate->format('l');
    $dayNum = ltrim($currDate->format('d'), '0');
    $monthName = $currDate->format('F');

    $hourFormatted = preg_replace('/^0/', '', $hour);
    echo "<div class='booking'>";
    echo "<p class='day'><strong>$dayName $dayNum</strong></p>";
    echo "<p class='month'><strong>$monthName</strong></p>";
    echo "<p class='hour'>At $hourFormatted</p>";
    echo "<p class='court'>Court $courtId</p>";
    echo "<button id='$id' class='cancelButton'>Cancel</button>";
    echo "</div>";
}

function clog($str) {
    echo "<script>console.log(`$str`)</script>";
}

function updateSQL($connection, $sql) {
    $executeQuery = $connection->prepare($sql);
    if ($executeQuery === false) {
        return "Error en la preparación de la consulta: " . $connection->error;
    }
    // Ejecuta la consulta y en caso de error te lo dice
    if ($executeQuery->execute() === false) {
        return "Error al insertar el registro: " . $executeQuery->error;
    }
    // Cerrar la conexión a la base de datos y la query
    $executeQuery->close();
    $connection->close();
    return "Update succes!";
}

function insertSQL($connection, $sql) {
    try {
        $executeQuery = $connection->prepare($sql);
        $executeQuery->execute();
        return 0;
    } catch(mysqli_sql_exception $e){
        // 1062 es el código de error para una clave primaria duplicada
        if ($e->getCode() == 1062) {
            return 1062;
        } else {
            $error = $e->getMessage();
            echo "<script>alert('{$error}')</script>";
        }
        return $e->getCode();
    }
}
?>