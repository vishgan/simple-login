<?php

if (!isset($_GET['code'])) {
    header("HTTP/1.1 404 Not Found");
    exit;
}

session_start();

// already logged in to amlrocks
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
    header("Location: welcome.php");
    exit;
}

$accessTokenEndpoint = 'https://github.com/login/oauth/access_token';
$clientId = '2e580e970def5a1dd362';
$clientSecret = '4cc60cf70175a370761055979e971aef8f544f27';

$authorizationCode = $_GET['code'];

$postData = array(
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'code' => $authorizationCode,
);

$headers = array(
    'Accept: application/json'
);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $accessTokenEndpoint);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($curl);

$accessTokenData = json_decode($response, true);
// github returns 200 without access token if same authorization code is used
if (isset($accessTokenData['error'])) {
    header('Location: index.php?problem=badAccessTokenResponse');
    exit;
}
$accessToken = $accessTokenData['access_token'];
$accessTokenScopes = $accessTokenData['scope'];
if ($accessTokenScopes !== 'user:email') {
    header('Location: index.php?problem=insufficientPermissions');
    exit;
}

curl_close($curl);

// Great, now I have an "acess" token to play with the github api
// check permissions this token has, can be expired, revoked
// Still, don't know if user is authenticated

// user authentication endpoint, part of github resource api
$userAuthenticationEndpoint = 'https://api.github.com/user';
$headers = array(
    'Accept: application/json',
    "Authorization: Token {$accessToken}",
    'User-Agent: php-curl'
);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $userAuthenticationEndpoint);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($curl);

// now we know user is authenticated by github
$userData = json_decode($response, true);
$_SESSION['loggedIn'] = true;
$_SESSION['name'] = $userData['login'];

// lets find out user's email address

$headers = array(
    'Accept: application/json',
    "Authorization: Token {$accessToken}",
    'User-Agent: php-curl'
);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://api.github.com/user/emails');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($curl);

$emailIds = json_decode($response, true);
foreach ($emailIds as $emailId) {
    if ($emailId['primary']) {
        $userEmailId = $emailId['email'];
        break;
    }
}

$_SESSION['email'] = $userEmailId;

header('Location: welcome.php');
