let ctx;
let gainNode;
let oscillator;
let oscillatorPedal;

let musicContainer = document.getElementById('music-container');
let musicContainerWidth = 10;
let maxDuration = 1;
let itemWidthMultiplier = 1;

let isPlaying = false;
let startButton = document.getElementById('start');
let stopButton = document.getElementById('stop');

const waitFor = (ms) => new Promise(r => setTimeout(r, ms));

function prepareAudioContext() {
    window.AudioContext = window.AudioContext || window.webkitAudioContext;
    ctx = new AudioContext();

    gainNode = ctx.createGain();
    gainNode.gain.value = 0.3;
    gainNode.connect(ctx.destination);
}

function calculateSizes() {
    musicContainerWidth = musicContainer.offsetWidth;
    maxDuration = Math.max(...song.composition.map(x => x.duration));
    itemWidthMultiplier = musicContainerWidth / maxDuration;

    totalTime = Math.round(song.composition[song.composition.length-1].waitFor / 1000);


    console.log(musicContainerWidth + " (music container width)");
    console.log(maxDuration + " (max duration in ms)");
    console.log(itemWidthMultiplier + "(item width multiplier)");
}

function displayMeta(song) {
    document.getElementById('mode-selector').value = song.mode;
    document.getElementById('tone-selector').value = song.tone;
    document.getElementById('song-info').innerHTML = song.tone + ' ' + song.mode;
    document.getElementById('total-time-span').innerHTML = pad(parseInt(totalTime/60)) + ':' + pad(totalTime%60);
}

async function startPlaying(song) {

    prepareAudioContext();
    calculateSizes();
    displayMeta(song);

    timeInterval = setInterval(setTime, 1000);
    //oscillator.connect(ctx.destination);

    document.getElementById('music-container').innerHTML = '';
    stopButton.style.display = 'inline-block';
    startButton.style.display = "none";
    isPlaying = true;

    await waitFor(500);

    for (let element of song.composition) {

        if (isPlaying) {
            play(song, element);
            await waitFor(element.duration);
            oscillator.disconnect(0);
        }
        oscillatorPedal.disconnect(0);
    }
}

function play(song, element) {
    oscillator = ctx.createOscillator();
    oscillator.type = "sine";
    oscillator.connect(gainNode);

    oscillatorPedal = ctx.createOscillator();
    oscillatorPedal.type = "sine";
    oscillatorPedal.connect(gainNode);

    oscillator.frequency.value = element.frequency;
    oscillatorPedal.frequency.value = song.pedal;
    oscillator.start(0);
    oscillatorPedal.start(0);

    let item = "<div id=\"item-" + element.word + "\" class=\"item " + element.class + "\" style=\"transition-duration: " + element.duration + "ms\">" + element.word + "</div>";
    musicContainer.innerHTML+= item;
    musicContainer.scrollTop = musicContainer.scrollHeight;

    document.getElementById('item-'+element.word).style.width = (Math.round(itemWidthMultiplier * element.duration) - 20) + 'px';
}


function stopPlaying() {
    oscillator.stop();
    oscillatorPedal.stop();
    isPlaying = false;
    stopButton.style.display = 'none';
    startButton.style.display = "inline-block";
    document.getElementById('song-info').innerHTML = '';

    resetTime();
}

function handleGainChange(value) {
    console.log(value);
    gainNode.gain.value = value;
}