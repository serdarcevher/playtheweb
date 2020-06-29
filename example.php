<?php
require_once('init.php');

use Sahtepetrucci\PlayTheWeb\Handler;
$handler = new Handler();

$song = $handler->run();
//$song = $handler->run("C", "major");
?>
<html>
<head>
    <link rel="stylesheet" href="assets/element.css">
</head>
<body>
    <div id="container">
        <h1>PlayTheWeb</h1>
        <h2>Semi-random music generator using HTML source codes</h2>

        <input id="source" type="text" placeholder="Enter any publicly available web page URL" />

        <div id="music-container"></div>

        <button id="start" class="action-button" onClick="startPlaying(song)">Start Playing</button>
        <button id="stop" class="action-button" onClick="stopPlaying()">Stop Playing</button>

        <div style="display:inline-block;">
            <select id="tone-selector" class="selector" name="tone">
                <option value="">Select Tone</option>
                <?php
                foreach ($handler->data->notes as $note):
                    ?><option value="<?=$note?>"><?=$note?></option><?php
                endforeach;
                ?>
            </select>

            <select id="mode-selector" class="selector" name="mode">
                <option value="">Select Mode</option>
                <?php
                foreach ($handler->data->modes as $mode):
                    ?><option value="<?=$mode?>"><?=$mode?></option><?php
                endforeach;
                ?>
            </select>

            <div id="song-state">

                <div id="song-info"></div>

                <span><label id="minutes">00</label>:<label id="seconds">00</label> / </span>
                <span id="total-time-span">00:00</span>
            </div>

            <input type="range" min="0" max="1" step="0.1" value="0.3" class="slider" id="gainRange" onChange="handleGainChange(this.value)" />
        </div>

    </div>

</body>
<script src="assets/time.js"></script>
<script src="assets/functions.js"></script>

<script>
    let song = <?=json_encode($song)?>;
</script>
</html>
