let ctx;
let oscillator;
let gainNode;
let musicContainer = document.getElementById('music-container');

let timeouts = [];
let isPlaying = false;
let startButton = document.getElementById('start');
let stopButton = document.getElementById('stop');

function startPlaying(song) {
    window.AudioContext = window.AudioContext || window.webkitAudioContext;
    ctx = new AudioContext();
    oscillator = ctx.createOscillator();
    gainNode = ctx.createGain();

    oscillator.connect(gainNode);
    gainNode.connect(ctx.destination);
    gainNode.gain.value = 0.1;
    oscillator.type = 'sine';

    oscillator.frequency.value = 440;
    oscillator.start(0);
    //oscillator.connect(ctx.destination);

    setTimeout(function(){
        oscillator.stop(0);
    },1000);

    stopButton.style.display = 'block';
    startButton.style.display = "none";
    isPlaying = true;
    let i = 0;

    song.composition.forEach(element => {

        timeouts[i] = setTimeout(function(){

            if (isPlaying) {
                let item = "<div class=\"item\" style=\"width: " + element.duration + "\">" + element.word + "</div>";
                musicContainer.innerHTML+= item;

                musicContainer.scrollTop = musicContainer.scrollHeight;

                /*
                oscillator.stop(0);
                oscillator.frequency.value = element.frequency;
                oscillator.start(0);
                */      
            }

        }, element.waitFor);

        //console.log(i + ' added');
        i++;
        //console.log(element);
    });
}

function stopPlaying() {
    oscillator.stop();

    isPlaying = false;
    clearTimeouts();
    document.getElementById('music-container').innerHTML = '';

    stopButton.style.display = 'none';
    startButton.style.display = "block";
}

async function play(item) {

}

function clearTimeouts() {
    timeouts.forEach(timeout => {
        clearTimeout(timeouts[timeout]);
    });
}

function toggleButtons() {

}