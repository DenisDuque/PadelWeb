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
    <script src="src/js/userCalendar.js"></script>
</head>
<body>
    <?php
        include("functions.php");
    ?>
    <div id="background"></div>
    <header>
        <img class="leftTopLogo" src="src/img/logo.svg" alt="Padel Logo">
        <h1>Calendar of Bookings</h1>
        <a href='close.php'><img class="logout" src="src/img/logout.png" alt="Logout"></a>
    </header>
    <?php
        if(!isset($_SESSION['userEmail'])){
            session_unset();
            session_destroy();
            echo '<meta http-equiv="refresh" content="0;url=index.php">';
        } else if(connectDataBase()==false){
            echo "<h1 style='color: white; margin-top: 1rem;'>Database down</h1>";
        } else {
    ?>
    <main>
        <div class="datePicker">
            <button class="changeMonth" id="prv"><img id="lastMonth" src="src/img/arrow.png" alt="Last Month"></button>
            <h2 id="month"></h2>
            <button class="changeMonth" id="nxt"><img id="nextMonth" src="src/img/arrow.png" alt="Next Month"></button>
            <h3 id="year"></h3>
        </div>

        <div class="calendarContainer">
            <div id="calendar">
                <?php
                    $days = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
                    for ($i=0; $i < 7; $i++) { 
                        echo "<div class='dayHeader'>";
                        echo "  <p>{$days[$i]}</p>";
                        echo "</div>";
                    }
                    
                ?>
            </div>
        </div>
        <div class="yourBookings">
            <h2>Your bookings</h2>
            <?php
                $connect = connectDataBase();
                $sql = "SELECT * FROM booking WHERE email = '" . $_SESSION['userEmail'] . "' AND day >= CURDATE() AND status != 'CANCELLED'";
                if(selectSQL($connect, $sql, $result)){
                }
            ?>
            <div id="bookingContainer">
                <?php
                    if(empty($result)){
                        echo "<div class='booking' style='place-items: center;'>";
                        echo "<p style='grid-column:1/4; grid-row:1/3;'>Hou have no pendent bookings</p>";
                        echo "</div>";
                    } else {
                        foreach ($result as $booking) {   
                            createBookingDiv($booking);
                        }
                    }
                ?>
            </div>
            <?php
                if(!empty($result)){
                    echo "<button id='expandBookings'>
                            <img src='src/img/arrow.png' alt='expand bookings'>
                        </button>";
                }
            ?>
        </div>
    </main>
    <?php
        }
    ?>
</body>
</html>