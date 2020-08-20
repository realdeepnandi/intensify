<?php
session_start();
require '../vendor/autoload.php';

$accessToken = $_SESSION['accessToken'];
$refreshToken = $_SESSION['refreshToken'];
$session = new SpotifyWebAPI\Session(
    'client id',
    'Secrect id',
    'Redirect url'
);
if ($accessToken) {
    $session->setAccessToken($accessToken);
    $session->setRefreshToken($refreshToken);
} else {
    $session->refreshAccessToken($refreshToken);
}
$options = [

    'auto_refresh' => true,
];
$api = new SpotifyWebAPI\SpotifyWebAPI($options, $session);
$api->setSession($session);
$currentTrack = $api->getMyCurrentTrack();
// print_r($currentTrack->item->album->artists[0]->external_urls->spotify);
$current_flag = 0;
if (isset($currentTrack)) {
    $current_artist_url = $currentTrack->item->album->artists[0]->external_urls->spotify;
    $current_song_artist = $currentTrack->item->album->artists[0]->name;
    $current_song_url = $currentTrack->item->external_urls->spotify;
    $current_song = $currentTrack->item->name;
    $current_flag = 1;
}

$device = $api->getMyDevices();
$device_flag = 0;
if (count($device->devices)) {
    $deviceId = $device->devices[0];
    $device_name = $device->devices[0]->name;
    $device_type = $device->devices[0]->type;
    $device_volume = $device->devices[0]->volume_percent;
    $device_flag = 1;
}





$me = $api->me();
$me_image = $me->images[0]->url;
// print_r($me->images[0]->url);
$tracks = $api->getMySavedTracks();

// echo "name : " . $me->display_name;
// echo "<br><img src='" . $me->images[0]->url . "'>";
$newAccessToken = $session->getAccessToken();
$newRefreshToken = $session->getRefreshToken();
$data = '';
if ($current_flag == 1 && $device_flag == 1) {

    $data .= " Currently listening to <br><a target=\"_blank\" href=" . $current_song_url . " class=\"alert-link\">" . $current_song . "</a> by <a target=\"_blank\" href=\"" . $current_artist_url . "\" class=\"alert-link\">" . $current_song_artist . "</a>";
    $data .= "<br>";
    $data .= $device_name . " ($device_type)";
} else {
    $data = 'Nothing is being played';
}

echo $data;
