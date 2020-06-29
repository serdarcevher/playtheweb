let timeInterval;

let minutesLabel = document.getElementById("minutes");
let secondsLabel = document.getElementById("seconds");
let totalSeconds = 0;
let totalTime = 0;

function setTime()
{
    ++totalSeconds;
    if(totalSeconds == totalTime) {
        clearInterval(timeInterval);
    }
    secondsLabel.innerHTML = pad(totalSeconds%60);
    minutesLabel.innerHTML = pad(parseInt(totalSeconds/60));
}

function resetTime() {
    clearInterval(timeInterval);
    totalSeconds = 0;
    totalTime = 0;
    secondsLabel.innerHTML = '00';
    minutesLabel.innerHTML = '00';
}

function pad(val)
{
    let valString = val + "";
    if (valString.length < 2) {
        return "0" + valString;
    }
    return valString;
}