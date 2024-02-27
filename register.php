<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <?php
        include("functions.php");
        if(!empty($_POST)){
            $connect = connectDataBase();
            $password = md5($_POST['userPassword']);

            $insertSQL = "INSERT INTO user (email, password, isActive, isAdmin) VALUES ('" . $_POST['userEmail'] . "', '" . $password . "', 1, 0);";
            updateSQL($connect, $insertSQL);
            echo '<meta http-equiv="refresh" content="0;url=index.php?register">';
        } else {
    ?>
    <div id="background"></div>
    <header style="justify-contne">
        <img class="leftTopLogo" src="src/img/logo.svg" alt="Padel Logo">
        <h1>Register</h1>
        <h1 style="color: white; margin-right: 0.5rem;">Go back</h1>
        <a href='index.php'><img class="logout" src="src/img/logout.png" alt="Logout"></a>
    </header>
    <div id="loginDiv">
        <form action="#" method="POST">
            <div>
                <div class='imageContainer'><img src="src/img/email.png" alt="Email:"/></div>
                <input type="email" class="loginInput" name="userEmail" id="userEmail" placeholder="Email Address">
            </div>
            <div>
                <div class='imageContainer'><img src="src/img/password.png" alt="Password:"/></div>
                <input type="password" class="loginInput" name="userPassword" id="userPassword" placeholder="Password">
            </div>
            <input type="submit" class="loginButton" value="REGISTER">
        </form>
    </div>
    <?php
        }
    ?>
</body>
</html>