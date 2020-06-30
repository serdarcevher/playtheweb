<?php
require_once('init.php');

use Sahtepetrucci\PlayTheWeb\Handler;
$handler = new Handler();
$base = '//' . $_SERVER['HTTP_HOST'] . rtrim($_SERVER['REQUEST_URI'], '/') . '/';
?>
<html>
<head>
    <link rel="stylesheet" href="<?=$base?>assets/element.css">
    <link rel="stylesheet" href="<?=$base?>assets/spinners.css">
    <link rel="stylesheet" href="<?=$base?>assets/awesomplete.css">
</head>
<body>
    <div id="container">
        <h1>PlayTheWeb</h1>
        <h2>
            Semi-random music generator using HTML source codes
        </h2>

        <form action="javascript:void(0);">

            <input id="source" class="awesomplete" 
                   type="text" 
                   placeholder="Enter any publicly available web page URL" 
                   value=""
                   data-minchars="1" 
                   data-list="<?=implode(",", $handler->data->urls)?>"
                   />

            <div style="display:inline-block">
                <select id="tone-selector" class="selector" name="tone">
                    <option value="">Automatic Tone Selection</option>
                    <?php
                    foreach ($handler->data->notes as $note):
                        ?><option value="<?=$note?>"><?=$note?></option><?php
                    endforeach;
                    ?>
                </select>

                <select id="mode-selector" class="selector" name="mode">
                    <option value="">Automatic Mode Selection</option>
                    <?php
                    foreach ($handler->data->modes as $mode):
                        ?><option value="<?=$mode?>"><?=$mode?></option><?php
                    endforeach;
                    ?>
                </select>
            </div>

            <div style="font-style:italic;font-size:13px;">
                Generated composition <span style="color:green">will be the same</span> as long as the inputted site's <span style="color:green">code remains the same.</span>
            </div>

            <div id="music-container"></div>

            <button type="submit" id="start" class="action-button" onClick="startPlaying()">Start Playing</button>
            <button type="button" id="stop" class="action-button" onClick="stopPlaying()">Stop Playing</button>
            <div style="display:inline-block;">

                <div id="song-state">

                    <div id="song-info"></div>

                    <span><label id="minutes">00</label>:<label id="seconds">00</label> / </span>
                    <span id="total-time-span">00:00</span>
                </div>

                <input type="range" min="0" max="1" step="0.1" value="0.3" class="slider" id="gainRange" onChange="handleGainChange(this.value)" />
            </div>

        </form>

    </div>

</body>
<script src="<?=$base?>assets/time.js"></script>
<script src="<?=$base?>assets/functions.js"></script>
<script src="<?=$base?>assets/awesomplete.min.js"></script>
</html>
