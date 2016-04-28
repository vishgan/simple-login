<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.0 404 Not Found");
    exit;
}

session_start();

$enteredEmail = $_POST['email'];
$enteredPassword = $_POST['password'];

// mock users database
$users = array(
    'vishwasg123@gmail.com' => array(
        'name' => 'Vishwas',
        'email' => 'vishwasg123@gmail.com',
        'password' => '123456',
    ),
    'vishesh@gmail.com' => array(
        'name' => 'Vishesh',
        'email' => 'vishesh@gmail.com',
        'password' => 'qwerty',
    ),
);

if (isset($users[$enteredEmail]) && $users[$enteredEmail]['password'] === $enteredPassword) {
    // login successful, redirect to welcome page
    $_SESSION['loggedIn'] = true;
    $_SESSION['name'] = $users[$enteredEmail]['name'];
    $_SESSION['email'] = $enteredEmail;
    header("Location: welcome.php");
    exit;
}

$problem = '';
if (!isset($users[$enteredEmail])) {
    $problem = 'invalidUserEmail';
} elseif ($users[$enteredEmail]['password'] !== $enteredPassword) {
    $problem = 'invalidUserPassword';
}
header("Location: index.php?problem={$problem}");
