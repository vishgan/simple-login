<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.0 404 Not Found");
    exit;
}

session_start();
if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
    header("Location: index.php?problem=notLoggedIn");
    exit;
}
$name = $_SESSION['name'];
session_destroy();
header("Location: goodbye.php?name={$name}");
