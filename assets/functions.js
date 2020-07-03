let song;
let ctx;
let gainNode;
let oscillator;
let oscillatorPedal;

let loadingHTML = '<div id="loading" class="spinner">\
                    <div class="rect1"></div>\
                    <div class="rect2"></div>\
                    <div class="rect3"></div>\
                    <div class="rect4"></div>\
                    <div class="rect5"></div>\
                </div>';
let musicContainer = document.getElementById('music-container');
let musicContainerWidth = 10;
let maxDuration = 1;
let itemWidthMultiplier = 1;

let userInputs = document.querySelectorAll('input, button, select');
let isPlaying = false;
let startButton = document.getElementById('start');
let stopButton = document.getElementById('stop');

const waitFor = (ms) => new Promise(r => setTimeout(r, ms));

async function loadSong(url) {    
    loading(true);
    prepareAudioContext();
    await waitFor(500);

    return new Promise(function(resolve, reject) {

        let data = {
            'url': url,
            'tone': document.getElementById('tone-selector').value,
            'mode': document.getElementById('mode-selector').value
        };

        fetch(base + 'load.php', { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json()) // parse response as JSON (can be res.text() for plain response)
        .then(response => {
            // here you do what you want with response
            if (response.error) {
                handleError();
                reject();
            }

            song = response;
            resolve();
        })
        .catch(err => {
            reject(err);
        });
    });
}

function handleError() {
    alert("Something went wrong! Try a different URL.")
    loading(false);
    stopPlaying();
}

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
    document.getElementById('song-info').innerHTML = song.tone + ' ' + song.mode;
    document.getElementById('total-time-span').innerHTML = pad(parseInt(totalTime/60)) + ':' + pad(totalTime%60);
}

async function startPlaying() {
    let url = encodeURIComponent(document.getElementById('source').value);
    if (!url) {
        alert('You have to enter a URL');
        return;
    }

    if (isPlaying) {
        stopPlaying();
    }

    await loadSong(url);
    loading(false);

    calculateSizes();
    displayMeta(song);

    timeInterval = setInterval(setTime, 1000);
    //oscillator.connect(ctx.destination);

    document.getElementById('music-container').innerHTML = '';
    stopButton.style.display = 'inline-block';
    startButton.style.display = "none";
    isPlaying = true;

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
    if (typeof(oscillator) !== "undefined") {
        oscillator.stop();
        oscillatorPedal.stop();     
    }
    isPlaying = false;
    stopButton.style.display = 'none';
    startButton.style.display = "inline-block";
    document.getElementById('song-info').innerHTML = '';

    resetTime();
}

function resetToneAndMode() {
    document.getElementById('tone-selector').value = '';
    document.getElementById('mode-selector').value = '';
}

function handleGainChange(value) {
    console.log(value);
    gainNode.gain.value = value;
}

function loading(bool) {
    userCanInteract(!bool);
    musicContainer.innerHTML = '';
    if (bool) {
        musicContainer.innerHTML = loadingHTML;
    }
}

function userCanInteract(bool) {
    userInputs.forEach(x => x.disabled = !bool);
}

function playRandomSite() {
    if (isPlaying) {
        stopPlaying();
        document.getElementById('mode-selector').value = '';
        document.getElementById('tone-selector').value = '';
    }

    let site = urls[Math.floor(Math.random() * urls.length)];
    document.getElementById('source').value = site;

    startPlaying();
}

function reflectToneAndModeChange() {
    if (isPlaying) {
        startPlaying();
    }
}