<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padel booking</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="icon" type="image/x-icon" href="src/img/logo.svg">
</head>
<body>
    <?php
    include("functions.php");

    $connect = connectDataBase();
    $sql = "SELECT *
    FROM booking
    WHERE email = '" . $_SESSION['userEmail'] . "' AND day >= CURDATE()";
    if(selectSQL($connect, $sql, $result)){
        print_r($result);
    }
    ?>
</body>
</html>