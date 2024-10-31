jQuery(document).ready(function ($) {

    let Player = null;
    let PlayerId = null;
    let AudioId = null;
    let PlayerInterval = null;
    let Progress = false;

    /** Play & Pause */
    $(document).on('click', '.mmp-controller-content .mmp-button#play', function () {

        /** Variable */
        let _ = $(this);
        let active = _.hasClass('active');

        GetPlayer(_); // Get Current player
        MMP.setCurrent(PlayerId, AudioId); // Set current value

        $('.mmp-button#play').removeClass('active');
        clearInterval(PlayerInterval);

        if (active) { // Pause

            _.removeClass('active');
            MMP.pause();

        } else { // Play

            _.addClass('active');
            MMP.play();
            StartInterval();
            PlayCounter();

        }

    });

    $(document).on('click', '.mmp-list-content .mmp-button#play', function () {

        GetPlayer($(this));
        MMP.setCurrent(PlayerId, $(this).attr('data-music-id')); // Set current value

        Player.find('.mmp-controller-content .mmp-button#play').addClass('active');
        Player.attr('data-current-audio', $(this).attr('data-music-id'));
        MMP.play();
        StartInterval();
        PlayCounter();
        ResetData();

    });

    /** Next */
    $(document).on('click', '.mmp-button#next', function () {

        GetPlayer($(this)); // Get Current player
        MMP.setCurrent(PlayerId, AudioId); // Set current value

        let next = MMP.next();
        MMP.play();
        StartInterval();
        PlayCounter();
        ResetData();

        Player.attr('data-current-audio', next.id);
        Player.find('.mmp-button#play').addClass('active');

    });

    /** Prev */
    $(document).on('click', '.mmp-button#prev', function () {

        GetPlayer($(this)); // Get Current player
        MMP.setCurrent(PlayerId, AudioId); // Set current value

        let prev = MMP.prev();
        MMP.play();
        StartInterval();
        PlayCounter();
        ResetData();

        Player.attr('data-current-audio', prev.id);
        Player.find('.mmp-button#play').addClass('active');

    });

    /** Prev */
    $(document).on('click', '.mmp-button#loop', function () {

        GetPlayer($(this)); // Get Current player
        MMP.setCurrent(PlayerId, AudioId); // Set current value
        $(this).removeClass('this loop');

        switch (CurrentPlayer.loop) {
            case -1:

                $(this).addClass('loop');
                CurrentPlayer.loop = 0;

                break;
            case 0:

                $(this).addClass('this');
                CurrentPlayer.loop = 1;

                break;
            case 1:
                CurrentPlayer.loop = -1;
                break;
        }

    });

    /** Shuffle */
    $(document).on('click', '.mmp-button#shuffle', function () {

        GetPlayer($(this)); // Get Current player
        MMP.setCurrent(PlayerId, AudioId); // Set current value

        if ($(this).hasClass('active')) {

            $(this).removeClass('active');
            CurrentPlayer.shuffle = false;

        } else {

            $(this).addClass('active');
            CurrentPlayer.shuffle = true;

        }

    });

    /** Like */
    $(document).on('click', '.mmp-button#like', function () {

        let i = $(this);
        let action = 'MMP_Like';

        if (i.hasClass('liked')) action = 'MMP_Remove_Like';

        let ajaxData = {
            action: action,
            nonce: mmp_localize.Nonce,
            id: i.attr('data-music-id'),
        }

        $.ajax({
            type: "POST",
            url: mmp_localize.AjaxUrl,
            data: ajaxData,
            success: function (resp) {

                if (i.hasClass('liked')) i.removeClass('liked');
                else i.addClass('liked');

            },
            timeout: 10000
        });

    });

    /**
     * Follow
     */
    $(document).on('click', '.mmp-follow', function () {

        let i = $(this);

        let ajaxData = {
            action: 'MMP_Follow',
            nonce: mmp_localize.Nonce,
            id: i.attr('data-artist-id'),
        }

        $.ajax({
            type: "POST",
            url: mmp_localize.AjaxUrl,
            data: ajaxData,
            success: function (resp) {

                console.log(i.hasClass('following'));

                if (i.hasClass('following')) {

                    i.removeClass('following');
                    i.html(mmp_localize.follow);

                } else {

                    i.addClass('following');
                    i.html(mmp_localize.unfollow);

                }

            },
            timeout: 10000
        });

    });

    /**
     * Progress mousedown
     */
    $('.mmp-progress').on('mousedown', function () {

        let player = $(this).parents('.mmp_player');
        if (MMP.currentPlayer !== player.attr('id')) {

            alert('Please play the music first.');
            return false;

        }

        GetPlayer($(this)); // Get Current player
        MMP.setCurrent(PlayerId, AudioId); // Set current value

        Progress = true;

    });

    /**
     * Progress mouseup
     */
    $('.mmp-progress').on('mouseup', function () {

        let player = $(this).parents('.mmp_player');

        Progress = false;
        MMP.audio.volume = 1;
        player.find('.mmp-progress-hover').css({
            transition: 'all 0.2s cubic-bezier(0.85, 0.01, 0.16, 0.93)'
        });

    });

    /**
     * Mouse move
     */
    $('.mmp-progress').on('mousemove', function (e) {

        /** Check enable move */
        if (!Progress) return false;

        let _ = $(this);
        let player = _.parents('.mmp_player');
        let percent = (e.offsetX * 100) / _.width();
        let time = (percent * CurrentAudio.duration) / 100;

        player.find('.mmp-progressed').css({
            width: percent + '%'
        });

        player.find('.mmp-progress-hover').css({
            transition: 'none',
            left: percent + '%'
        });

        MMP.audio.volume = 0;
        MMP.audio.currentTime = time;
        CurrentAudio.playTime = time;
        player.find('.mmp-start-time').html(MMP.timeFormat());

    });

    function StartInterval() {

        PlayerInterval = setInterval(function () {

            /** Disable interval for enable progress */
            if (Progress) return false;

            /** Set time */
            Player.find('.mmp-end-time').html(MMP.timeFormat(CurrentAudio.duration));
            Player.find('.mmp-start-time').html(MMP.timeFormat());

            /** Set position progressed|Hover|buffered */
            Player.find('.mmp-progressed').css({
                "width": MMP.percent() + '%'
            });

            Player.find('.mmp-progress-hover').css({
                "left": MMP.percent() + '%'
            });

            Player.find('.mmp-preload').animate({
                width: ((MMP.audio.buffered.end(0) * 100) / CurrentAudio.duration).toFixed(2).toString() + '%'
            }, 900, 'swing');

            /** Check end audio */
            if (MMP.audio.ended) {

                /** Check loop|on|pause */
                switch (CurrentPlayer.loop) {
                    case -1:

                        MMP.pause();
                        _.removeClass('active');
                        clearInterval(PlayerInterval);

                        break;
                    case 0:

                        let current = null;

                        if (CurrentPlayer.shuffle) current = MMP.shuffle();
                        else current = MMP.next();

                        Player.attr('data-current-audio', current.id);
                        MMP.play();

                        break;
                    case 1:

                        CurrentAudio.playTime = 0;
                        MMP.play();

                        break;
                }

            }


        }, 1000);

    }

    function GetPlayer(Object) {

        Player = Object.parents('.mmp_player');
        PlayerId = Player.attr('id');
        AudioId = Player.attr('data-current-audio');

    }

    function PlayCounter() {

        if (CurrentAudio.play_status) return false;

        let ajaxData = {
            action: 'MMP_Play',
            nonce: mmp_localize.Nonce,
            id: CurrentAudio.real_id,
        }

        $.ajax({
            type: "POST",
            url: mmp_localize.AjaxUrl,
            data: ajaxData,
            success: function () {
                CurrentAudio.play_status = true;
            },
            timeout: 10000
        });

    }

    function ResetData() {

        Player.find('.mmp-detail-content .mmp-cover-main, .mmp-detail-content .mmp-cover-shadow').attr('style', 'background:url(' + CurrentAudio.cover + ')');
        Player.find('.mmp-detail-content .mmp-title').html(CurrentAudio.name);
        Player.find('.mmp-detail-content .mmp-genres').html(CurrentAudio.genres);
        Player.find('.mmp-detail-content .mmp-progressed, .mmp-detail-content .mmp-preload').css({
            width: '0'
        });
        Player.find('.mmp-detail-content .mmp-progress-hover').css({
            left: '0'
        });

    }

});