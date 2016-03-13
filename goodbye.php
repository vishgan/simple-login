<?php

session_start();
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
    header("Location: welcome.php");
    exit;
}
if (isset($_GET['name'])) {
    $name = $_GET['name'];
} else {
    header("HTTP/1.1 404 Not Found");
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Goodbye</title>
    </head>
    <body>
        <h1>Goodbye <?php echo $name; ?></h1>
        <a href="index.php">Login</a>
    </body>
</html>
