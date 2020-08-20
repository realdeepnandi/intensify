<?php
session_start();
require '../vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
    'client id',
    'Secrect id',
    'Redirect url'
);
$session->requestAccessToken($_GET['code']);

$accessToken = $session->getAccessToken();
$refreshToken = $session->getRefreshToken();

$_SESSION['accessToken'] = $accessToken;
$_SESSION['refreshToken'] = $refreshToken;

header('Location: ../app');
die();
