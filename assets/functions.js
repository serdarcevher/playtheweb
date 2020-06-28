let ctx;
let gainNode;
let oscillator;
let oscillatorPedal;
let musicContainer = document.getElementById('music-container');

let isPlaying = false;
let startButton = document.getElementById('start');
let stopButton = document.getElementById('stop');

const waitFor = (ms) => new Promise(r => setTimeout(r, ms));

function prepareAudioContext() {
    window.AudioContext = window.AudioContext || window.webkitAudioContext;
    ctx = new AudioContext();
}

async function startPlaying(song) {
    
    prepareAudioContext();
    //oscillator.connect(ctx.destination);

    document.getElementById('music-container').innerHTML = '';
    document.getElementById('mode-info').innerHTML = song.tone + ' ' + song.mode;
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
    gainNode = ctx.createGain();
    gainNode.gain.value = 0.2;
    gainNode.connect(ctx.destination);

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

    let item = "<div class=\"item\" style=\"width: " + element.duration + "\">" + element.word + "</div>";
    musicContainer.innerHTML+= item;
    musicContainer.scrollTop = musicContainer.scrollHeight;
}


function stopPlaying() {
    oscillator.stop();
    oscillatorPedal.stop();
    isPlaying = false;
    stopButton.style.display = 'none';
    startButton.style.display = "inline-block";
    document.getElementById('mode-info').innerHTML = '';
}