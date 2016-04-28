<?php
    session_start();
    // This is the meat...if you are not logged in, you don't have access to this page
    if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
        header("Location: index.php?problem=notLoggedIn");
        exit;
    }
    $name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Welcome Page</title>
    </head>
    <body>
        <h1>Welcome <?php echo $name; ?></h1>
        <form action="check_logout.php" method="post">
        <h3>Email Id: <?php echo $_SESSION['email']; ?></h3>
            <p>
                <input type="submit" name="logoutButton" value="logout">
            </p>
        </form>
    </body>
</html>
