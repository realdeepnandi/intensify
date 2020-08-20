<?php
session_start();
if (isset($_SESSION['accessToken']) && isset($_SESSION['refreshToken'])) {
    header('Location: app');
}
require_once 'vendor/autoload.php';
if (isset($_POST['submit'])) {
    $session = new SpotifyWebAPI\Session(
        'client id',
        'Secrect id',
        'Redirect url'
    );
    $options = [
        'scope' => [
            'user-library-read',
            'user-read-playback-state'
        ],
    ];

    header('Location: ' . $session->getAuthorizeUrl($options));
    die();
}
?>


<html lang="en">

<head>
    <style>
        @font-face {
            font-family: Gotham;
            src: url('assets/fonts/Gotham-Font/GothamMedium.ttf');
        }
    </style>
    <link rel="stylesheet" href="assets/css/mdb.min.css">
    <script src="assets/js/mdb.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <nav class="navbar navbar-light justify-content-center " style="background:#000;font-family:Gotham;">
        <div class="text-center">
            <div class="container-fluid">
                <a class="navbar-brand" href="#" style="color:white;font-size:25px">Intensify</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class=" d-flex align-items-center justify-content-center" style="height: 60%;">
            <form method=post action="">
                <button name=submit type="submit" class="btn btn-success" style="font-family:gotham;font-size:14px;">Login with Sotify</button>
            </form>
        </div>
    </div>
</body>

</html>