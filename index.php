<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="icon" type="image/x-icon" href="src/img/logo.svg">
    <title>Log In</title>
</head>
<body>
    <?php
        include("functions.php");
        $incorrectCredentials = "a";
        if($_POST) {
            $email = $_POST['userEmail'];
            $password = md5($_POST['userPassword']);
            validateUser($email, $password, $incorrectCredentials);
        }
    ?>
    <div id="loginDiv">
        <img src="src/img/logo.svg" alt="Padel Logo" class="logo"/>
        <?php
            if(connectDataBase()==false){
                echo "<h1 style='color: white; margin-top: 1rem;'>Database down</h1>";
            } else {

            
        ?>
        <form action="index.php" method="post" enctype="multipart/form-data" name="login" autocomplete="off">
            <div>
                <div class='imageContainer'><img src="src/img/email.png" alt="Email:"/></div>
                <input type="email" class="loginInput" name="userEmail" id="userEmail" placeholder="Email Address">
            </div>
            <div>
                <div class='imageContainer'><img src="src/img/password.png" alt="Password:"/></div>
                <input type="password" class="loginInput" name="userPassword" id="userPassword" placeholder="Password">
            </div>
            <?php
                if($incorrectCredentials=="a"){
                    echo "<p class='errorLogin' style='visibility:hidden;'>$incorrectCredentials</p>";
                } else {
                    echo "<p class='errorLogin'>$incorrectCredentials</p>";
                }
            ?>
            <input type="submit" id="loginButton" value="LOGIN">
        </form>
        <?php
            }
        ?>
    </div>
</body>
</html>
    