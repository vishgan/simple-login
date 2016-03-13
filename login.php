<?php

extract($_POST);

$userPasswords = array(
    'vishwas' => '123456',
    'vishesh' => 'abcdef',
);

if (!isset($userPasswords[$user_name]) || $userPasswords[$user_name] !== $user_password) {
    die('bad credentials');
}

session_start();
$_SESSION['user_name'] = $user_name;
$_SESSION['user_password'] = $user_password;
$_SESSION['logged_in'] = 1;
