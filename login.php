<?php
session_start();
include("functions.php");
$incorrectCredentials = "";
if($_POST) {
    $email = $_POST['userEmail'];
    $password = md5($_POST['userPassword']);
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
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Log In</title>
        <link rel="stylesheet" href="css/main.css">
        <link rel="icon" type="image/x-icon" href="src/logo.svg">
    </head>
    <body>
            <div id="loginDiv">
                <img src="src/logo.svg" alt="Padel Logo" class="logo"/>
                <form action="login.php" method="post" enctype="multipart/form-data" name="login">
                    <div>
                        <div class='imageContainer'><img src="src/email.png" alt="Email:"/></div>
                        <input type="email" class="loginInput" name="userEmail" id="userEmail" placeholder="Email Address">
                    </div>
                    <div>
                        <div class='imageContainer'><img src="src/password.png" alt="Password:"/></div>
                        <input type="password" class="loginInput" name="userPassword" id="userPassword" placeholder="Password">
                    </div>
                    <p class="errorLogin"><?php echo $incorrectCredentials; ?></p>
                    <input type="submit" id="loginButton" value="LOGIN" id="loginBtn">
                </form>
            </div>
    </body>
</html>
    