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
    <header>
        <img class="leftTopLogo" src="src/img/logo.svg" alt="Padel Logo"/>
        <h1>Calendar of Bookings</h1>
        <a href='close.php'><img class="logout" src="src/img/logout.png" alt="Logout"/></a>
    </header>

    <main>
        <div class="datePicker">
            <button class="changeMonth" id="prv"><img id="lastMonth" src="src/img/arrow.png" alt="Last Month"/></button>
            <h2 id="month">January</h2>
            <button class="changeMonth" id="nxt"><img id="nextMonth" src="src/img/arrow.png" alt="Next Month"/></button>
            <h3 id="year">2023</h3>
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


        </div>
    </main>
    
</body>
</html>