/**
 * MMP Player
 * version 1.0.0
 * This is a global object for controlling music and playing it, and it runs on its own variables.
 */

/**
 * Jquery
 *
 * @type {jQuery|HTMLElement}
 */
const Q = jQuery;

/**
 * Global object music management pro
 */
const MMP = {};

/**
 * Player
 *
 * @type {{}}
 */
MMP.player = {};

/**
 * Player Ui Kit
 *
 * @type {{}}
 */
MMP.player.UiKit = {};

/**
 * Audio list
 *
 * @type {{}}
 */
MMP.player.audio = {};

/**
 * Current player and audio
 *
 * @type {{}}
 */
MMP.player.current = {};

/**
 * Current player
 *
 * @type {null}
 */
MMP.player.currentPlayer = null;

/**
 * Set volume
 *
 * @type {number}
 */
MMP.player.volume = 0.5;

/**
 * Current audio id
 *
 * @type {null}
 */
MMP.player.status = false;

/**
 * Interval
 *
 * @type {null}
 */
MMP.player.interval = null;

/**
 * Create audio element
 *
 * @type {HTMLAudioElement}
 */
MMP.player.audioObj = new Audio();

/**
 * Push audio
 *
 * @param audioId
 * @param audioLink
 */
MMP.player.pushAudio = function (playerId, audioId, audioLink) {

    if (!MMP.player.audio[playerId])
        MMP.player.audio[playerId] = {
            audio: [],
            loop: 0,
            volume: 1
        };

    MMP.player.audio[playerId].audio.push({
        id: audioId,
        link: audioLink,
        startTime: 0,
        endTime: 0,
        playTime: 0,
        buffered: 0,
        percentTime: 0,
        percentBuffered: 0,
        endStatus: false
    });

}

/**
 * Remove audio
 *
 * @param audioId
 */
MMP.player.removeAudio = function (playerId, audioId) {

    let i = MMP.player.indexAudio(playerId, audioId);
    if (i) MMP.player.audio[playerId].splice(i, 1);

}

/**
 * Get index audio with audio id
 *
 * @param playerId
 * @param audioId
 * @returns {boolean}
 */
MMP.player.indexAudio = function (playerId, audioId) {

    let i = false;

    Q.each(MMP.player.audio[playerId].audio, function (index, value) {

        if (value.id === audioId) {

            i = index;
            return false;

        }

    });

    return i;

}

/**
 * Clear audio list
 *
 * @param playerId
 */
MMP.player.clearAudio = function (playerId) {

    MMP.player.audio[playerId].audio = [];

}

/**
 * Set audio url
 *
 * @param playerId
 * @param audioId
 */
MMP.player.setAudio = function (playerId, audioId) {

    let i = MMP.player.indexAudio(playerId, audioId);
    let url = MMP.player.audio[playerId].audio[i].link;

    MMP.player.audioObj.src = url;
    MMP.player.load();

}

/**
 * Load audio
 */
MMP.player.load = function () {

    MMP.player.audioObj.load();

}

/**
 * Play current audio
 *
 * @param playerId
 * @param audioId
 * @param fn
 */
MMP.player.play = function (playerId, audioId, fn = false) {

    // Variable
    let i = MMP.player.indexAudio(playerId, audioId);
    let p = MMP.player.audio[playerId];
    let audio = MMP.player.audioObj;
    let a = p.audio[i];
    let l = 0;

    // Check time
    if (MMP.player.current[playerId] === audioId)
        l = a.playTime;

    audio.src = a.link; // Set link audio

    let play = audio.play(); // Play audio

    audio.volume = p.volume; // Set volume on Object audio
    audio.currentTime = l; // Set current time on Object audio

    MMP.player.current[playerId] = audioId; // Set current audio id
    MMP.player.currentPlayer = playerId; // Set current player id
    MMP.player.status = true; // Set status

    MMP.player.UiKit.playButton(playerId);

    // Create interval
    MMP.player.interval = setInterval(function () {

        // Check status for change value progress bar (drag and drop)
        if (!MMP.player.status)
            return false;

        a.buffered = audio.buffered.end(0); // Set buffered value
        a.playTime = audio.currentTime; // Set play time value
        a.endTime = audio.duration; // Set end time value
        a.startTime = 0; // Set start time value
        a.percentTime = ((a.playTime * 100) / a.endTime).toFixed(2); // Set percent play time value
        a.percentBuffered = ((a.buffered * 100) / a.endTime).toFixed(2); // Set percent buffered value

        MMP.player.UiKit.timeText(playerId, a.playTime, a.endTime);
        MMP.player.UiKit.progressBar(playerId, a.percentTime, a.percentBuffered);
        MMP.player.UiKit.loopButton(playerId, p.loop);

        // Audio ended
        if (audio.ended && p.loop === 1) audio.play();
        else if (audio.ended && p.loop === 2) MMP.player.next(playerId, audioId);
        else if (audio.ended) {

            MMP.player.pause();
            a.playTime = 0;

        }

        // Run function
        if (fn) {
            fn();
        }

    }, 1000);

}

/**
 * Pause audio
 */
MMP.player.pause = function () {

    MMP.player.audioObj.pause();
    MMP.player.status = false;
    MMP.player.UiKit.playButton(MMP.player.currentPlayer);

    clearInterval(MMP.player.interval);

}

/**
 * Next audio
 *
 * @param playerId
 * @param audioId
 */
MMP.player.next = function (playerId, audioId) {

    let i = MMP.player.indexAudio(playerId, audioId);
    let l = MMP.player.audio[playerId].audio.length;
    let c = 0;

    if (i < (l - 1)) {
        c = i + 1;
    }

    let a = MMP.player.audio[playerId].audio[c];

    a.playTime = 0;
    MMP.player.play(playerId, a.id);

}

/**
 * Set volume
 *
 * @param playerId
 * @param value {number} 0-100
 */
MMP.player.setVolume = function (playerId, value) {

    let v = value / 100;

    MMP.player.audio[playerId].volume = v;
    MMP.player.audioObj.volume = v;

}

/**
 * Set time
 *
 * @param currentTime
 */
MMP.player.setCurrentTime = function (currentTime) {

    MMP.player.audioObj.currentTime = currentTime;

}

/**
 * Set time with percent
 *
 * @param playerId
 * @param audioId
 * @param percent
 */
MMP.player.setCurrentTimeWithPercent = function (playerId, audioId, percent) {

    let i = MMP.player.indexAudio(playerId, audioId);
    let p = (percent * MMP.player.audio[playerId].audio[i].endTime) / 100;
    MMP.player.setCurrentTime(p);

}

/**
 * Get audio data
 *
 * @param playerId
 * @returns {*}
 */
MMP.player.getCurrentAudio = function (playerId) {

    let audioId = MMP.player.current[playerId];
    let i = MMP.player.indexAudio(playerId, audioId);

    return MMP.player.audio[playerId].audio[i];

}

/**
 * Get player data
 *
 * @param playerId
 * @returns {*}
 */
MMP.player.getCurrentPlayer = function (playerId) {

    return MMP.player.audio[playerId];

}

/**
 * Time Format
 *
 * @param sec
 * @returns {string}
 */
MMP.player.timeFormat = function (sec) {

    let minutes = Math.floor(sec / 60);
    let seconds = Math.floor(sec - minutes * 60);
    // let hours = Math.floor(sec / 3600);

    return (MMP.player.str_pad_left(minutes, '0', 2) + ':' + MMP.player.str_pad_left(seconds, '0', 2));

}

/**
 * Pretty print code
 *
 * @param string
 * @param pad
 * @param length
 * @returns {string}
 */
MMP.player.str_pad_left = function (string, pad, length) {
    return (new Array(length + 1).join(pad) + string).slice(-length);
}

/**
 * Set time html
 *
 * @param parentId
 * @param playTime
 * @param endTime
 */
MMP.player.UiKit.timeText = function (playerId, playTime, endTime) {

    let player = $('#' + playerId);
    let playTimeObj = player.find('.mmp_progress_start_time');
    let endTimeObj = player.find('.mmp_progress_end_time');

    playTimeObj.html(MMP.player.timeFormat(playTime));
    endTimeObj.html(MMP.player.timeFormat(endTime));

}

/**
 * Set value progress
 *
 * @param playerId
 * @param value
 * @param loaded
 */
MMP.player.UiKit.progressBar = function (playerId, value, loaded) {

    let player = $('#' + playerId);
    let progress = player.find('.mmp_progress_bar[for=progress]');

    MMP.progressBar.change(progress, value, loaded)

}

/**
 * Set status loop
 *
 * @param playerId
 * @param status
 */
MMP.player.UiKit.loopButton = function (playerId, status) {

    let player = $('#' + playerId);
    let btn = player.find('.mmp_btn.loop');

    btn.removeClass('disable just-audio enable');

    switch (status) {
        case 0:
            btn.addClass('disable');
            break;
        case 1:
            btn.addClass('just-audio');
            break;
        case 2:
            btn.addClass('enable');
            break;
    }

}

MMP.player.UiKit.playButton = function (playerId) {

    let player = $('#' + playerId);
    let btn = player.find('.mmp_btn.play');

    if (MMP.player.status) {
        btn.html('<i class="icon-pause"></i>');
    } else {
        btn.html('<i class="icon-play"></i>');
    }

}