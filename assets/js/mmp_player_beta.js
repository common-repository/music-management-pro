/**
 * MMP Player
 * version 1.2.0
 * This is a global object for controlling music and playing it, and it runs on its own variables.
 */

let jQ = jQuery;
let CurrentPlayer = null;
let CurrentAudio = null;
let AudioController = null;

const MMP = {
    currentAudio: {},
    currentPlayer: null,
    status: false,
    uiKit: {},
    audio: new Audio()
};

/**
 * Set current value
 *
 * @param player
 * @param audio
 */
MMP.setCurrent = function (player, audio) {

    MMP.currentPlayer = player;
    CurrentPlayer = MMP.currentAudio[player];
    CurrentAudio = MMP.getAudio(audio);

}

/**
 * Play Audio
 */
MMP.play = function () {

    MMP.audio.src = CurrentAudio.src['320'];
    MMP.audio.currentTime = CurrentAudio.playTime;
    MMP.audio.load();
    MMP.audio.play();
    MMP.Controller();

}

/**
 * Pause Audio
 */
MMP.pause = function () {

    MMP.audio.pause();
    clearInterval(AudioController);

}

/**
 * Create Interval
 *
 * @constructor
 */
MMP.Controller = function () {

    AudioController = setInterval(MMP.Interval, 1000);

}

/**
 * In Interval
 *
 * @constructor
 */
MMP.Interval = function () {

    // Time audio
    CurrentAudio.playTime = MMP.audio.currentTime;

    // Ended
    if (MMP.audio.ended) {

        CurrentAudio.playTime = 0;
        MMP.pause();

        console.log(MMP.next());

    }

}

/**
 * Get index audio
 */
MMP.getIndexAudio = function () {

    let id = CurrentAudio.id;
    let _index = null;

    jQ.each(CurrentPlayer.item, function (index, value) {

        if (value.id === id) _index = index;

    });

    return _index;

}

/**
 * Next audio
 */
MMP.next = function () {

    let indexAudio = MMP.getIndexAudio(CurrentAudio.id);
    let nextAudio = CurrentPlayer.item[indexAudio + 1];

    if (!nextAudio) nextAudio = CurrentPlayer.item[0];

    nextAudio.playTime = 0;
    CurrentAudio = nextAudio;
    return nextAudio;

}

/**
 * Prev audio
 */
MMP.prev = function () {

    let indexAudio = MMP.getIndexAudio(CurrentAudio.id);
    let nextAudio = CurrentPlayer.item[indexAudio - 1];

    if (!nextAudio) nextAudio = CurrentPlayer.item[CurrentPlayer.item.length - 1];

    nextAudio.playTime = 0;
    CurrentAudio = nextAudio;
    return nextAudio;

}

/**
 * Shuffle audio
 */
MMP.shuffle = function () {

    let getLength = CurrentPlayer.item.length;
    let index = Math.floor((Math.random() * getLength - 1) + 1);
    let audio = CurrentPlayer.item[index];

    CurrentAudio = audio;
    return audio;

}

/**
 * Get Audio Data
 *
 * @param audio
 * @return Object|Null
 */
MMP.getAudio = function (audio) {

    let out = null;
    jQ.each(CurrentPlayer.item, function (index, value) {

        if (value.id === audio) out = value;

    });

    return out;

}

/**
 * Pretty print code
 *
 * @param string
 * @param pad
 * @param length
 * @return {string}
 */
MMP.str_pad_left = function (string, pad, length) {
    return (new Array(length + 1).join(pad) + string).slice(-length);
}

/**
 * Format time
 *
 * @param time
 * @return {string}
 */
MMP.timeFormat = function (time = MMP.audio.currentTime) {

    let minutes = Math.floor(time / 60);
    let seconds = Math.floor(time - minutes * 60);
    // let hours = Math.floor(sec / 3600);

    return (MMP.str_pad_left(minutes, '0', 2) + ':' + MMP.str_pad_left(seconds, '0', 2));

}

/**
 * Get percent time
 *
 * @return {string}
 */
MMP.percent = function () {

    return ((MMP.audio.currentTime * 100) / MMP.audio.duration).toFixed(2)

}