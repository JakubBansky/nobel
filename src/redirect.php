<?php

session_start();

require_once 'vendor/autoload.php';
$client = new Google\Client();

$client->setAuthConfig('client_secret.json');

// $redirect_uri = "https://node16.webte.fei.stuba.sk/Z1/redirect.php";
$redirect_uri = "https://localhost/redirect.php";
$client->setRedirectUri($redirect_uri);

$client->addScope("email");
$client->addScope("profile");
$auth_url = $client->createAuthUrl();


header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
exit();
// if (isset($_GET['code'])) {
//     $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
//     $client->setAccessToken($token['access_token']);
//     $oauth = new Google\Service\Oauth2($client);

//     $account_info = $oauth->userinfo->get();

//     $g_fullname = $account_info->name;
//     $g_id = $account_info->id;
//     $g_email = $account_info->email;
//     $g_name = $account_info->givenName;
//     $g_surname = $account_info->familyName;


//     $_SESSION['access_token'] = $token['access_token'];
//     $_SESSION['email'] = $g_email;
//     $_SESSION['id'] = $g_id;
//     $_SESSION['fullname'] = $g_fullname;
//     $_SESSION['name'] = $g_name;
//     $_SESSION['surname'] = $g_surname;

// }

// header('Location: restricted.php');

