<?php
require_once('init.php');

use Sahtepetrucci\PlayTheWeb\Handler;
$handler = new Handler();
$base = '//' . $_SERVER['HTTP_HOST'] . rtrim(strtok($_SERVER['REQUEST_URI'], '?'), '/') . '/';
$u = $_GET['u'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>PlayTheWeb - Hear the sound of web!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="og:title" content="Semi-random music generator using HTML source codes">
    <meta name="twitter:title" content="Semi-random music generator using HTML source codes">
    <meta property="og:description" content="Each CSS class in the HTML source code becomes a note in a composition">
    <meta property="twitter:description" content="Each CSS class in the HTML source code becomes a note in a composition">
    <meta property="og:image" content="<?=$base?>assets/preview.png">
    <meta property="twitter:image" content="<?=$base?>assets/preview.png">

    <link rel="stylesheet" href="<?=$base?>assets/element.css?v=7">
    <link rel="stylesheet" href="<?=$base?>assets/spinners.css">
    <link rel="stylesheet" href="<?=$base?>assets/awesomplete.css">
</head>
<body>
    <!--<a id="github-logo-link" href="https://github.com/serdarcevher/playtheweb" target="_blank" id="github-link" alt="Go to Github repository" title="Go to Github repository">
        <img src="<?=$base?>assets/github-mark.png"  />
    </a>-->
    <div id="container">
        <h1>PlayTheWeb</h1>
        <h2>
            Semi-random music generator using HTML source codes
        </h2>

        <form action="javascript:void(0);">

            <input id="source" class="awesomplete" 
                   type="text" 
                   placeholder="Enter any URL (e.g. google.com)" 
                   value="<?=htmlspecialchars($u)?>"
                   autocapitalize="none"
                   data-minchars="1" 
                   data-list="<?=implode(",", $handler->data->urls)?>"
                   />

            <div style="display:inline-block">
                <button type="submit" id="start" class="action-button" onClick="startPlaying()">Play</button>
                <button type="button" id="stop" class="action-button" onClick="stopPlaying()">Stop</button>

                <button type="button" id="random" class="action-button" onClick="playRandomSite()">Random Site</button>
            </div>

            <div id="music-container"></div>

            <div style="display:inline-block;margin-top:10px;">

                <div id="song-state">

                    <div id="song-info"></div>

                    <span><label id="minutes">00</label>:<label id="seconds">00</label> / </span>
                    <span id="total-time-span">00:00</span>
                </div>

                <input type="range" min="0" max="1" step="0.1" value="0.3" class="slider" id="gainRange" onChange="handleGainChange(this.value)" />
            </div>

            <div style="display:inline-block;margin-top:10px;">
                <select id="tone-selector" class="selector" name="tone" onChange="reflectToneAndModeChange()">
                    <option value="">Change Tone</option>
                    <option value="">Auto</option>
                    <?php
                    foreach ($handler->data->notes as $note):
                        ?><option value="<?=$note?>"><?=$note?></option><?php
                    endforeach;
                    ?>
                </select>

                <select id="mode-selector" class="selector" name="mode" onChange="reflectToneAndModeChange()">
                    <option value="">Change Mode</option>
                    <option value="">Auto</option>
                    <?php
                    foreach ($handler->data->modes as $mode):
                        ?><option value="<?=$mode?>"><?=$mode?></option><?php
                    endforeach;
                    ?>
                </select>
            </div>

            <br /><br />

            <div style="font-style:italic;font-size:13px;">
                Generated composition <span style="color:green">will be the same</span> as long as the inputted site's <span style="color:green">code remains the same.</span>
            </div>
            <br /><br />
            <a id="github-text-link" href="https://github.com/serdarcevher/playtheweb" target="_blank">How does it work? <img src="<?=$base?>assets/github-mark.png" alt="See the code in Github" style="width:16px;height:16px"  /></a>

        </form>

    </div>

<script>
    let base = '<?=$base?>';
    let urls = <?=json_encode($handler->data->urls)?>;
</script>
<script src="<?=$base?>assets/time.js"></script>
<script src="<?=$base?>assets/functions.js?v=3"></script>
<script src="<?=$base?>assets/awesomplete.min.js"></script>

</body>
</html>