<?php
session_start();
require '../vendor/autoload.php';
if (isset($_SESSION['accessToken']) && isset($_SESSION['refreshToken'])) {
    $accessToken = $_SESSION['accessToken'];
    $refreshToken = $_SESSION['refreshToken'];
    $session = new SpotifyWebAPI\Session(
        'client_id',
        'secret_id',
        'redirect'
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

    // print_r($currentTrack->item->album->artists[0]->external_urls->spotify);






    $me = $api->me();
    $me_image = $me->images[0]->url;
    // print_r($me->images[0]->url);
    $tracks = $api->getMySavedTracks();

    // echo "name : " . $me->display_name;
    // echo "<br><img src='" . $me->images[0]->url . "'>";
    $newAccessToken = $session->getAccessToken();
    $newRefreshToken = $session->getRefreshToken();
} else {
    header("location: ../");
}
?>
<!doctype html>
<html lang="en">

<head>
    <style>
        @font-face {
            font-family: Gotham;
            src: url('../assets/fonts/Gotham-Font/GothamMedium.ttf');
        }

        body {
            background-color: #f2f2f2 !important;
        }
    </style>


    <link rel="stylesheet" href="/assets/css/mdb.min.css">
    <script src="/assets/js/mdb.min.js"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intensify</title>
</head>

<body>

    <nav class="navbar navbar-light justify-content-center " style="background:#000;font-family:Gotham;">
        <div class="text-center">
            <div class="container-fluid">
                <a class="navbar-brand" href="#" style="color:white;font-size:25px">Intensify - Alpha</a>
            </div>
        </div>
    </nav>
    <center>
        <div class="container" style="margin-top:20px;">
            <ul class="nav nav-tabs nav-fill mb-3" id="ex1" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="ex2-tab-1" data-toggle="tab" href="#ex2-tabs-1" role="tab" aria-controls="ex2-tabs-1" aria-selected="true" style="font-family:Gotham;font-size:15px;">Songs</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="ex2-tab-2" data-toggle="tab" href="#ex2-tabs-2" role="tab" aria-controls="ex2-tabs-2" aria-selected="false" style="font-family:Gotham;font-size:15px;">Playlists</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="ex2-tab-3" data-toggle="tab" href="#ex2-tabs-3" role="tab" aria-controls="ex2-tabs-3" aria-selected="false" style="font-family:Gotham;font-size:15px;">Wait</a>
                </li>
            </ul>
            <br>
            <div class="card " style="width: 80%;"><br>
                <center>
                    <?php if (isset($me_image)) { ?>
                        <img style="width:170px;border-radius:50%;" src="<?php echo $me_image; ?>" class="card-img-top" alt="Profile Picture" />
                    <?php } else {
                    ?>
                        <img style="width:170px;border-radius:50%;" src="https://www.freepnglogos.com/uploads/spotify-logo-png/spotify-icon-marilyn-scott-0.png" class="card-img-top" alt="Profile Picture" />
                    <?php
                    } ?>
                </center>
                <div class="card-body">
                    <p class="card-text" style="font-family:Gotham;font-size:15px;">
                        <?php echo "<font size=5>" . $me->display_name . "</font>"; ?>

                        <div style="font-family:Gotham;font-size:15px;" id=data></div>
                    </p>
                </div>
            </div>
            <br><br>

            <div class="tab-content" id="ex2-content">

                <div class="tab-pane fade show active" id="ex2-tabs-1" role="tabpanel" aria-labelledby="ex2-tab-1">
                    <h6 style="font-family:Gotham;font-size:15px;">Your last 50 saved tracks sorted popularity wise</h6>
                    <br>
                    <div class="row row-cols-1 row-cols-md-4 g-4">

                        <?php
                        // print_r($tracks->items[0]->track->album->artists[0]->name);
                        $popular_arr = array();
                        foreach ($tracks->items as $track) {
                            $track = $track->track;
                            $image = $track->album->images[0]->url;
                            $song_name = $track->name;
                            $artist_name = $track->album->artists[0]->name;
                            $popularity = $track->popularity;
                            $arr = array("popularity" => $popularity, "song_name" => $song_name, "image" => $image, "artist_name" => $artist_name);
                            array_push($popular_arr, $arr);
                        }
                        array_multisort(array_column($popular_arr, "popularity"), SORT_DESC, $popular_arr);

                        foreach ($popular_arr as  $value) {
                        ?>
                            <div class="col">
                                <div class="card text-white bg-dark h-100" style='width:80%;'>
                                    <?php echo "<img  src='" . $value['image'] . "' class='card-img-top'>"; ?>

                                    <div class="card-body ">
                                        <h6 class="card-title " style="font-family:Gotham;font-size:15px;"><?php echo $value['song_name']; ?></h6>
                                        <p class="card-text">

                                            <?php echo $value['artist_name']; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                        <?php
                        }


                        ?>

                    </div>
                </div>
                <div class="tab-pane fade" id="ex2-tabs-2" role="tabpanel" aria-labelledby="ex2-tab-2">
                    To be Added
                </div>
                <div class="tab-pane fade" id="ex2-tabs-3" role="tabpanel" aria-labelledby="ex2-tab-3">
                    To be Added
                </div>
            </div>


        </div>
    </center>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            setInterval(fetchSong, 500);
            let x = 0;

            function fetchSong() {
                $.ajax({
                    url: "data.php",
                    beforeSend: function() {
                        if (x == 0) {
                            $("#data").html("Loading...");
                            x++;
                        }
                    },
                    success: function(result) {
                        $("#data").html(result);
                    }
                });
            }
        });
    </script>
    <br>
    <footer class="bg-light text-center text-lg-left">
        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            Made by Deep
        </div>
        <!-- Copyright -->
    </footer>
</body>

</html>