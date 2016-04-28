<?php

if (!isset($_GET['code'])) {
    header("HTTP/1.1 404 Not Found");
    exit;
}

session_start();
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
    // already logged in to amlrocks
    header("Location: welcome.php");
    exit;
}

$authorizatioCode = $_GET['code'];

$accessTokenEndpoint = 'https://github.com/login/oauth/access_token';
$postData = array(
    'client_id' => '2e580e970def5a1dd362',
    'client_secret' => '4cc60cf70175a370761055979e971aef8f544f27',
    'code' => $authorizatioCode,
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

// curl_setopt($curl, CURLOPT_PROXY, 'http://10.0.2.2:8888');
// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($curl);

// check for status codes
// other than 200, you might get 401
// github returns 200 without access token if same authorization code is used
curl_close($curl);

$accessTokenData = json_decode($response, true);
$accessToken = $accessTokenData['access_token'];
$accessTokenScopes = $accessTokenData['scope'];

// user authentication endpoint, part of github resource api
$headers = array(
    'Accept: application/json',
    "Authorization: Token {$accessToken}",
    'User-Agent: php-curl'
);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://api.github.com/user');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

// curl_setopt($curl, CURLOPT_PROXY, 'http://10.0.2.2:8888');
// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($curl);
$userData = json_decode($response, true);
// returns 401 if no user agent is given

// now apparently we know user is authenticated by github
$_SESSION['loggedIn'] = true;
$_SESSION['name'] = $userData['login'];
// lets find out user's email address
// you should also check scope of access token

$headers = array(
    'Accept: application/json',
    "Authorization: Token {$accessToken}",
    'User-Agent: php-curl'
);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://api.github.com/user/emails');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

// curl_setopt($curl, CURLOPT_PROXY, 'http://10.0.2.2:8888');
// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

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
