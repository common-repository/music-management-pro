jQuery(document).ready(function ($) {

    /**
     * TODO PUBLIC
     */

    setTimeout(function () {
        MMP.removeLoading();
    }, 500);

    // Render message
    MMP.alert.render();

    $(document).on('keyup', '.mmp_search input', function () {

        let _ = $(this);
        let pagination = $('.mmp_pagination');

        if (_.val() === '') {
            pagination.attr('style', 'display:block;');
        } else {
            pagination.attr('style', 'display:none;');
        }


    });

    MMP.contextMenu([
        {
            type: 'artist',
            items: [
                {
                    icon: 'icon-edit',
                    label: 'Edit',
                    callback: function (_this, _e) {

                        let this_ = $(_this);
                        let id = this_.parents('tr').attr('data-id');

                        MMP.form.open({
                            prefix: 'artists',
                            alertText: MMP.localize.save_artist,
                            data: {
                                id: id
                            },
                            inSave: function () {
                                ResetTableArtist(MMP.currentPage);
                            }
                        });

                    }
                },
                {
                    icon: 'icon-trash-2',
                    label: 'Remove',
                    callback: function (_this, _e) {

                        let this_ = $(_this);
                        let id = this_.parents('tr').attr('data-id');
                        MMP.loading.add('remove_artist', $('body'));

                        let ajaxData = {
                            action: 'Delete_Data',
                            prefix: 'artists',
                            nonce: MMP.localize.Nonce,
                            data: {
                                id: id
                            },
                        }

                        $.ajax({
                            type: "POST",
                            url: MMP.localize.AjaxUrl,
                            data: ajaxData,
                            success: function (resp) {

                                this_.parents('tr').remove();
                                ResetTableArtist(MMP.currentPage);

                                MMP.loading.remove('remove_artist');
                                MMP.alert.add({
                                    title: MMP.localize.success_text,
                                    description: MMP.localize.delete_artist,
                                    icon: {
                                        type: 'icon',
                                        content: 'icon-check-circle'
                                    }
                                });

                            },
                            timeout: 10000
                        });

                    }
                },
                {
                    icon: 'icon-user',
                    label: 'Show Follower',
                    callback: function (_this, _e) {

                        let _ = $(_this);
                        let musicId = _.parents('tr').attr('data-id');
                        let follower = $('<div class="mmp-see-like-follow"></div>');

                        let ajaxData = {
                            action: 'Artist_See_Follower',
                            nonce: MMP.localize.Nonce,
                            id: musicId
                        }

                        $.ajax({
                            type: "POST",
                            url: MMP.localize.AjaxUrl,
                            data: ajaxData,
                            success: function (resp) {

                                follower.html(resp);

                                MMP.liteBox({
                                    content: follower,
                                    ok: function () {
                                    }
                                });

                            },
                            timeout: 10000
                        });

                    }
                }
            ]
        },
        {
            type: 'genres',
            items: [
                {
                    icon: 'icon-edit',
                    label: 'Edit',
                    callback: function (_this, _e) {

                        let this_ = $(_this);
                        let id = this_.parents('tr').attr('data-id');

                        MMP.form.open({
                            prefix: 'genres',
                            alertText: MMP.localize.save_artist,
                            data: {
                                id: id
                            },
                            inSave: function () {
                                ResetTableGenres();
                            }
                        });

                    }
                },
                {
                    icon: 'icon-trash-2',
                    label: 'Remove',
                    callback: function (_this, _e) {

                        let this_ = $(_this);
                        let id = this_.parents('tr').attr('data-id');
                        MMP.loading.add('remove_genres', $('body'));

                        let ajaxData = {
                            action: 'Delete_Data',
                            prefix: 'genres',
                            nonce: MMP.localize.Nonce,
                            data: {
                                id: id
                            },
                        }

                        $.ajax({
                            type: "POST",
                            url: MMP.localize.AjaxUrl,
                            data: ajaxData,
                            success: function (resp) {

                                this_.parents('tr').remove();

                                MMP.loading.remove('remove_genres');
                                MMP.alert.add({
                                    title: MMP.localize.success_text,
                                    description: MMP.localize.delete_genres,
                                    icon: {
                                        type: 'icon',
                                        content: 'icon-check-circle'
                                    }
                                });

                            },
                            timeout: 10000
                        });

                    }
                }
            ]
        },
        {
            type: 'music',
            items: [
                {
                    icon: 'icon-edit',
                    label: 'Edit',
                    callback: function (_this, _e) {

                        let this_ = $(_this);
                        let id = this_.parents('tr').attr('data-id');

                        MMP.form.open({
                            prefix: 'music',
                            alertText: MMP.localize.save_artist,
                            data: {
                                id: id
                            },
                            inSave: function () {
                                ResetTableMusic(MMP.currentPage);
                            }
                        });

                    }
                },
                {
                    icon: 'icon-trash-2',
                    label: 'Remove',
                    callback: function (_this, _e) {

                        let this_ = $(_this);
                        let id = this_.parents('tr').attr('data-id');
                        MMP.loading.add('remove_music', $('body'));

                        let ajaxData = {
                            action: 'Delete_Data',
                            prefix: 'music',
                            nonce: MMP.localize.Nonce,
                            data: {
                                id: id
                            },
                        }

                        $.ajax({
                            type: "POST",
                            url: MMP.localize.AjaxUrl,
                            data: ajaxData,
                            success: function (resp) {

                                this_.parents('tr').remove();
                                ResetTableMusic(MMP.currentPage);

                                MMP.loading.remove('remove_music');
                                MMP.alert.add({
                                    title: MMP.localize.success_text,
                                    description: MMP.localize.delete_music,
                                    icon: {
                                        type: 'icon',
                                        content: 'icon-check-circle'
                                    }
                                });

                            },
                            timeout: 10000
                        });

                    }
                },
                {
                    icon: 'icon-heart',
                    label: 'Show Likes',
                    callback: function (_this, _e) {

                        let _ = $(_this);
                        let musicId = _.parents('tr').attr('data-id');
                        let heart = $('<div class="mmp-see-like-follow"></div>');

                        let ajaxData = {
                            action: 'Music_See_Like',
                            nonce: MMP.localize.Nonce,
                            id: musicId
                        }

                        $.ajax({
                            type: "POST",
                            url: MMP.localize.AjaxUrl,
                            data: ajaxData,
                            success: function (resp) {

                                heart.html(resp);

                                MMP.liteBox({
                                    content: heart,
                                    ok: function () {
                                    }
                                });

                            },
                            timeout: 10000
                        });

                    }
                },
                {
                    icon: 'icon-play',
                    label: 'Preview',
                    callback: function (_this, _e) {

                        let this_ = $(_this);
                        let id = this_.parents('tr').attr('data-id');

                        let box = $('<div class="mmp_music_preview"></div>');
                        let img = $('<div class="image"></div>');
                        let content = $('<div class="content"></div>');
                        let title = $('<div class="title"></div>');
                        let time = $('<div class="time"></div>');
                        let audio = $('<audio controls><source src=""></audio>');

                        content.append(title);
                        content.append(time);
                        content.append(audio);
                        box.append(img);
                        box.append(content);

                        let ajaxData = {
                            action: 'Music_Preview',
                            nonce: MMP.localize.Nonce,
                            data: {
                                id: id
                            },
                        }

                        $.ajax({
                            type: "POST",
                            url: MMP.localize.AjaxUrl,
                            data: ajaxData,
                            success: function (resp) {

                                img.css({'background': 'url(' + resp.image + ')'});
                                title.html(resp.title);
                                time.html(resp.time);
                                audio.find('source').attr('src', resp.url);
                                audio[0].load();

                            },
                            timeout: 10000
                        });

                        MMP.liteBox({
                            content: box,
                            ok: function () {
                            }
                        });

                    }
                }
            ]
        }
    ]);

    /**
     * TODO ARTIST
     */

    // Open music form
    $(document).on('click', '.mmp-open-editor-artist', function () {

        let this_ = $(this);
        let id = this_.parents('tr').attr('data-id');

        MMP.form.open({
            prefix: 'artists',
            alertText: MMP.localize.save_artist,
            data: {
                id: id
            },
            inSave: function () {
                ResetTableArtist(MMP.currentPage);
            }
        });

    });

    // Open artist form
    $(document).on('click', '.add_artist', function () {

        MMP.form.open({
            prefix: 'artists',
            alertText: MMP.localize.save_artist,
            inSave: function () {
                ResetTableArtist();
            }
        });

    });

    // search in artist
    $(document).on('keyup', '.mmp_search.artist input', function () {

        MMP.ajaxSearch2($(this).val(), 'mmp_artist', 'name', 'artist_id', '12', 'MMP_Artist', function (resp) {
            $('.mmp_table tbody').html(resp);
        });

    });

    // Pagination
    $(document).on('click', '.mmp_pagination.mmp_artists a', function () {

        let _ = $(this);
        let page = _.attr('data-page');

        _.parent().find('a').removeClass('active');
        _.addClass('active');
        _.parent().attr('data-current', page);

        MMP.currentPage = page;
        ResetTableArtist(page);

    });

    // Pagination - Next
    $(document).on('click', '.mmp_pagination.mmp_artists i.next', function () {

        let _ = $(this);
        let page = _.parent().attr('data-current');
        let min = _.parent().attr('data-min');
        let max = _.parent().attr('data-max');

        page = Number(page) + 1;

        if (page < min || page > max) {
            return false;
        }

        _.parent().find('a').removeClass('active');
        _.parent().find('a[data-page=' + page + ']').addClass('active');
        _.parent().attr('data-current', page);

        MMP.currentPage = page;
        ResetTableArtist(page);

    });

    // Pagination - Prev
    $(document).on('click', '.mmp_pagination.mmp_artists i.prev', function () {

        let _ = $(this);
        let page = _.parent().attr('data-current');
        let min = _.parent().attr('data-min');
        let max = _.parent().attr('data-max');

        page = Number(page) - 1;

        if (page < min || page > max) {
            return false;
        }

        _.parent().find('a').removeClass('active');
        _.parent().find('a[data-page=' + page + ']').addClass('active');
        _.parent().attr('data-current', page);

        MMP.currentPage = page;
        ResetTableArtist(page);

    });

    /**
     * TODO MUSIC
     */

    // Open music form
    $(document).on('click', '.mmp-open-editor-music', function () {

        let this_ = $(this);
        let id = this_.parents('tr').attr('data-id');

        MMP.form.open({
            prefix: 'music',
            alertText: MMP.localize.save_artist,
            data: {
                id: id
            },
            inSave: function () {
                ResetTableMusic(MMP.currentPage);
            }
        });

    });

    // Open music form
    $(document).on('click', '.add_music', function () {

        MMP.form.open({
            prefix: 'music',
            alertText: MMP.localize.save_music,
            inSave: function () {
                ResetTableMusic();
            }
        });

    });

    // search in artist
    $(document).on('keyup', '.mmp_search.music input', function () {

        MMP.ajaxSearch2($(this).val(), 'mmp_music', 'name', 'music_id', '12', 'MMP_Music', function (resp) {
            $('.mmp_table tbody').html(resp);
        });

    });

    // Pagination
    $(document).on('click', '.mmp_pagination.mmp_music a', function () {

        let _ = $(this);
        let page = _.attr('data-page');

        _.parent().find('a').removeClass('active');
        _.addClass('active');
        _.parent().attr('data-current', page);

        MMP.currentPage = page;
        ResetTableMusic(page);

    });

    // Pagination - Next
    $(document).on('click', '.mmp_pagination.mmp_music i.next', function () {

        let _ = $(this);
        let page = _.parent().attr('data-current');
        let min = _.parent().attr('data-min');
        let max = _.parent().attr('data-max');

        page = Number(page) + 1;

        if (page < min || page > max) {
            return false;
        }

        _.parent().find('a').removeClass('active');
        _.parent().find('a[data-page=' + page + ']').addClass('active');
        _.parent().attr('data-current', page);

        MMP.currentPage = page;
        ResetTableMusic(page);

    });

    // Pagination - Prev
    $(document).on('click', '.mmp_pagination.mmp_music i.prev', function () {

        let _ = $(this);
        let page = _.parent().attr('data-current');
        let min = _.parent().attr('data-min');
        let max = _.parent().attr('data-max');

        page = Number(page) - 1;

        if (page < min || page > max) {
            return false;
        }

        _.parent().find('a').removeClass('active');
        _.parent().find('a[data-page=' + page + ']').addClass('active');
        _.parent().attr('data-current', page);

        MMP.currentPage = page;
        ResetTableMusic(page);

    });

    /**
     * TODO GENRES
     */

    // Open music form
    $(document).on('click', '.mmp-open-editor-genres', function () {

        let this_ = $(this);
        let id = this_.parents('tr').attr('data-id');

        MMP.form.open({
            prefix: 'genres',
            alertText: MMP.localize.save_artist,
            data: {
                id: id
            },
            inSave: function () {
                ResetTableGenres();
            }
        });

    });

    // Open genres form
    $(document).on('click', '.add_genres', function () {

        MMP.form.open({
            prefix: 'genres',
            alertText: MMP.localize.save_music,
            inSave: function () {
                ResetTableGenres();
            }
        });

    });

    // search in genres
    $(document).on('keyup', '.mmp_search.genres input', function () {

        MMP.ajaxSearch2($(this).val(), 'mmp_genres', 'name', 'genres_id', '100', 'MMP_Genres', function (resp) {
            $('.mmp_table tbody').html(resp);
        });

    });

    /**
     * TODO Fields - Default Event
     */

    // image selector
    $(document).on('click', '.mmp_field_image .mmp_select_image', function () {

        let _ = $(this);
        let image = _.parents('.mmp_field_image').find('.image');
        let save = image.parents('.mmp_field_image').find('input.save');

        MMP.wp.media(false, function (target) {

            image.css({
                'background': 'url(' + target.url + ')'
            })
            save.val(target.id);

        }, ['image']);

        _.parent().append(
            '<div class="mmp_button_icon mmp_remove_image">' +
            '     <i class="icon-x"></i>' +
            '</div>'
        );

    });

    // Remove image
    $(document).on('click', '.mmp_field_image .mmp_remove_image', function () {

        let _ = $(this);
        let image = _.parents('.mmp_field_image').find('.image');
        let save = image.parents('.mmp_field_image').find('input.save');

        _.remove();
        image.removeAttr('style');
        save.val('');

    });

    // Background image selector
    $(document).on('click', '.mmp_field_image_background .mmp_select_image', function () {

        let _ = $(this);
        let image = _.parent();
        let save = image.parent().find('input.save');

        MMP.wp.media(false, function (target) {

            image.css({
                'background': 'url(' + target.url + ')'
            })
            save.val(target.id);

        }, ['image']);

        image.append(
            '<div class="mmp_button_icon mmp_remove_image">' +
            '     <i class="icon-x"></i>' +
            '</div>'
        );

    });

    // Remove background image
    $(document).on('click', '.mmp_field_image_background .mmp_remove_image', function () {

        let _ = $(this);
        let image = _.parent();
        let save = image.parent().find('input.save');

        _.remove();
        image.removeAttr('style');
        save.val('');

    });

    /**
     * TODO FIELD AUDIO
     */

    $(document).on('click', '.mmp_audio', function () {

        let _ = $(this);
        let title = _.find('.audio_name');
        let time = _.find('.audio_time');
        let link = _.find('.audio_link');
        let input = _.find('input.save');

        MMP.wp.media(false, function (resp) {

            title.html(resp.filename);
            time.html(resp.fileLength);
            link.val(resp.url);
            input.val(resp.id);
            _.find('.image').css({
                'background': 'url(' + resp.image.src + ')'
            });

            $('.mmp_form .save[name=slug]').val(Slug(resp.title));
            $('.mmp_form .save[name=name]').val(resp.title);
            $('.mmp_form .save[name=description]').val(resp.description);

        }, ['audio']);

    });

    /** Check box field */
    $(document).on('click', '.mmp_fields .mmp_check_box', function () {

        let _ = $(this);
        let input = _.find('input.save');

        if (_.hasClass('active')) {

            _.removeClass('active');
            input.val('0');

        } else {

            _.addClass('active');
            input.val('0');

        }

    });

    /**
     * TODO :: LICENSE AN SUPPORT
     */

    // License keydown check standard char
    $(document).on('keydown', 'input#mmp_license', function (e) {

        let key = e.key;

        if (key === ' ' || ((key < 'a' || key > 'z') && (key < 'A' || key > 'Z') && (key < '0' || key > '9')))
            return false;

    });

    // License check
    $(document).on('click', '.active-license-btn', function () {

        let license = $('input#mmp_license').val();

        let ajaxData = {
            action: 'MMP_License_Check',
            nonce: MMP.localize.Nonce,
            license: license,
        }

        $.ajax({
            type: "POST",
            url: MMP.localize.AjaxUrl,
            data: ajaxData,
            success: function (resp) {

                if (resp.status) {

                    MMP.alert.add({
                        title: MMP.localize.success_text,
                        description: resp.message,
                        icon: {
                            type: 'icon',
                            content: 'icon-check-circle'
                        }
                    });
                    setTimeout(function () {
                        location.reload();
                    }, 500);

                } else {

                    MMP.alert.add({
                        title: MMP.localize.error_text,
                        description: resp.message,
                        icon: {
                            type: 'icon',
                            content: 'icon-slash'
                        }
                    });

                }

            },
            timeout: 10000
        });

    });

    // Support
    $(document).on('click', '.mmp-send-support', function () {

        let title = $('#mmp_support_title').val();
        let description = $('#mmp_support_desc').val();

        if (title === '' || description === '') {

            MMP.alert.add({
                title: MMP.localize.error_text,
                description: MMP.localize.empty_field,
                icon: {
                    type: 'icon',
                    content: 'icon-slash'
                }
            });

            return false;

        }

        let ajaxData = {
            action: 'MMP_Support',
            nonce: MMP.localize.Nonce,
            title: title,
            description: description
        }

        $.ajax({
            type: "POST",
            url: MMP.localize.AjaxUrl,
            data: ajaxData,
            success: function (resp) {

                if (resp.code === '200') {

                    MMP.alert.add({
                        title: MMP.localize.success_text,
                        description: MMP.localize.support_success,
                        icon: {
                            type: 'icon',
                            content: 'icon-check-circle'
                        }
                    });
                    setTimeout(function () {
                        location.reload();
                    }, 500);

                } else {

                    MMP.alert.add({
                        title: MMP.localize.error_text,
                        description: MMP.localize.support_error,
                        icon: {
                            type: 'icon',
                            content: 'icon-slash'
                        }
                    });

                }

            },
            timeout: 10000
        });

    });

    /**
     * TODO GLOBAL FUNCTIONS
     */

    // SLUG
    function Slug(text) {

        let _text = text.toLowerCase().replace(/ /g, '_');
        _text = _text.replace(/[^0-9a-z-_]/gi, '');

        return _text;

    }

    /**
     * Reset artist table
     *
     * @param page Current page
     */
    function ResetTableArtist(page = '1') {

        MMP.ajaxSearch('Artist', '', 'artists', 'full_name', page, '12', function (resp) {

            $('.mmp_table tbody').html(resp);

        });

    }

    /**
     * Reset artist table
     *
     * @param page Current page
     */
    function ResetTableGenres(page = '1') {

        MMP.ajaxSearch('Genres', '', 'genres', 'name', page, '9999999', function (resp) {

            $('.mmp_table tbody').html(resp);

        });

    }

    /**
     * Reset music table
     *
     * @param page Current page
     */
    function ResetTableMusic(page = '1') {

        MMP.ajaxSearch('Music', '', 'musics', 'name', page, '12', function (resp) {

            $('.mmp_table tbody').html(resp);

        });

    }

});