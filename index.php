<!-- login page -->
<?php
    session_start();
    if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
        // already logged in
        header("Location: welcome.php");
        exit;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Login Page</title>
    </head>
    <body>
        <h1>Login Page</h1>
        <?php
            if (isset($_GET['problem'])) {
                switch($_GET['problem']) {
                    case 'invalidUserEmail':
                        $errorMsg = "Incorrect user email";
                        break;
                    case 'invalidUserPassword':
                        $errorMsg = "Incorrect user password";
                        break;
                    case 'notLoggedIn':
                        $errorMsg = "User not logged in";
                        break;
                }
                if (isset($errorMsg)) {
                    echo '<font color="red">Error: ' . $errorMsg . "</font>";
                }
            }
        ?>
        <form action="check_login.php" method="post">
            <p>
                <label for="email">Email</label>
                <input type="text" name="email" id="email">
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
                <input type="submit" name="loginButton" value="login" id="loginButton">
            </p>
        </form>
    </body>
</html>