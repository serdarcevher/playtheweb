<?php
require_once('init.php');

use Sahtepetrucci\PlayTheWeb\Handler;
$handler = new Handler();

//$song = $handler->run();
$song = $handler->run("C", "major");
?>
<html>
<head>
    <link rel="stylesheet" href="assets/element.css">
</head>
<body>
    <div id="container">
        <h2>
            PlayTheWeb<br />
            <small>Semi-random music generator using HTML source codes</small>
        </h2>
        <div id="music-container">

        </div>

        <button id="start" class="actionButton" onClick="startPlaying(song)">Start Playing</button>
        <button id="stop" class="actionButton" onClick="stopPlaying()">Stop Playing</button>

        <span id="mode-info" style="margin-left:10px;"></span>

    </div>

</body>
<script src="assets/functions.js"></script>

<script>
    let song = <?=json_encode($song)?>;
</script>
</html>
