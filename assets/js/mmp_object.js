// Global Variable
let Q = jQuery;

/**
 * MMP Script controller
 *
 * + jQuery
 */
var MMP = {};

/**
 *  Localize
 */
MMP.localize = mmp_localize;

/**
 * Create object alert
 */
MMP.alert = {};

/**
 * Loading
 *
 * @type {{}}
 */
MMP.loading = {};

/**
 * Loading objects
 *
 * @type {{}}
 */
MMP.loading.object = {};

/**
 * Message
 */
MMP.alert.message = MMP.localize.Message;

/**
 * Form
 *
 * @type {{}}
 */
MMP.form = {}

/**
 * Wp
 *
 * @type {{}}
 */
MMP.wp = {};

/**
 * Current page
 *
 * @type {number}
 */
MMP.currentPage = 1;

/**
 * Open Form
 *
 * @data {prefix: string, alertText, data: json, inCancel: fn, InSave: fn}
 */
MMP.form.open = function (data) {

    let form = Q('<div class="mmp_form"></div>');
    let overlay = Q('<div class="mmp_overlay"></div>');
    let content = Q('<div class="mmp_form_content"></div>');
    let controller = Q('<div class="mmp_form_controller"></div>');
    let save = Q('<div class="mmp_form_save">' + MMP.localize.save + '</div>');
    let cancel = Q('<div class="mmp_form_cancel">' + MMP.localize.cancel + '</div>');

    controller.append(cancel);
    controller.append(save);
    form.append(content);
    form.append(controller);
    form.addClass(data.prefix);
    MMP.loading.add('form_loading', form);

    let ajaxData = {
        action: 'Load_Form',
        prefix: data.prefix,
        nonce: MMP.localize.Nonce,
        data: data.data,
    }

    Q.ajax({
        type: "POST",
        url: MMP.localize.AjaxUrl,
        data: ajaxData,
        success: function (resp) {

            content.append(resp);
            MMP.loading.remove('form_loading');

        },
        timeout: 10000
    });

    Q('body').append(form);
    Q('body').append(overlay);

    setTimeout(function () {
        form.addClass('active');
        overlay.addClass('active');
    }, 60);

    // Cancel
    cancel.on('click', function () {

        if (data.inCancel !== undefined) {
            data.inCancel();
        }

        form.removeClass('active');
        overlay.removeClass('active');

        setTimeout(function () {
            form.remove();
            overlay.remove();
        }, 160);

    });

    // Save
    save.on('click', function () {

        if (MMP.form.required()[0]) {
            return false;
        }

        MMP.loading.add('form_save_loading', form);

        let ajaxData = {
            action: 'Save_Form',
            prefix: data.prefix,
            nonce: MMP.localize.Nonce,
            data: data.data,
            fieldsData: MMP.form.data(),
        }

        Q.ajax({
            type: "POST",
            url: MMP.localize.AjaxUrl,
            data: ajaxData,
            success: function (resp) {

                if (data.inSave !== undefined) {
                    data.inSave();
                }

                MMP.loading.remove('form_save_loading');
                MMP.alert.add({
                    title: MMP.localize.success_text,
                    description: data.alertText,
                    icon: {
                        type: 'icon',
                        content: 'icon-check-circle'
                    }
                });

                form.removeClass('active');
                overlay.removeClass('active');

                setTimeout(function () {
                    form.remove();
                    overlay.remove();
                }, 160);

            },
            timeout: 10000
        });

    });

}

/**
 * Check empty fields
 *
 * @returns {[]} Name empty fields
 */
MMP.form.required = function () {

    let Form = Q('.mmp_form');
    let emptyField = [];

    Q.each(Form.find('.mmp_fields.required'), function () {

        let name = Q(this).find('.title').html();
        let value = Q(this).find('input.save').val();

        if (value === '')
            emptyField.push(name);

    });

    if (emptyField[0]) {

        MMP.alert.add({
            title: MMP.localize.error_text,
            description: MMP.localize.required_field + '<b>' + emptyField.join(', ') + '</b>',
            icon: {
                type: 'icon',
                content: 'icon-slash'
            }
        });

    }

    return emptyField;

}

/**
 * Get form data
 *
 * @returns {{}} Return form data
 */
MMP.form.data = function () {

    let form = Q('.mmp_form');
    let data = {};

    form.find('.mmp_fields').each(function () {

        let obj = Q(this).find('.save');
        data[obj.attr('name')] = obj.val();

    });

    return data;

}

/**
 * Ajax search
 *
 * @param prefix Object name
 * @param search Text search
 * @param from Tbl name
 * @param by Column name
 * @param page
 * @param per_page
 * @param fn
 */
MMP.ajaxSearch = function (prefix, search, from, by, page, per_page, fn) {

    let ajaxData = {
        action: 'Search',
        nonce: MMP.localize.Nonce,
        data: {
            prefix: prefix,
            from: from,
            by: by,
            page: page,
            per_page: per_page,
            search: search
        },
    }

    Q.ajax({
        type: "POST",
        url: MMP.localize.AjaxUrl,
        data: ajaxData,
        success: function (resp) {

            fn(resp);

        },
        timeout: 10000
    });

}

/**
 * New version search
 *
 * @param search
 * @param table
 * @param column
 * @param order_by
 * @param limit
 * @param object
 * @param callback
 */
MMP.ajaxSearch2 = function (search, table, column, order_by, limit, object, callback = undefined) {

    let ajaxData = {
        action: 'MMP_Search2',
        nonce: MMP.localize.Nonce,
        search: search,
        table: table,
        column: column,
        order_by: order_by,
        limit: limit,
        object: object
    }

    Q.ajax({
        type: "POST",
        url: MMP.localize.AjaxUrl,
        data: ajaxData,
        success: function (resp) {

            if (callback) callback(resp);

        },
        timeout: 10000
    });

}

/**
 * Create context menu
 *
 * @param data
 */
MMP.contextMenu = function (data = []) {

    Q(document).bind("mousedown", function (e) {

        // If the clicked element is not the menu
        if (!Q(e.target).parents(".mmp_contextmenu").length > 0) {

            // Hide it
            Q(".mmp_contextmenu").remove();

        }

    });

    Q.each(data, function (index, value) {

        Q(document).on('click', '.mmp_more.' + value.type, function (e) {

            let menu = Q('<div class="mmp_contextmenu"></div>');
            let _this = this;
            let _e = e;

            Q('.mmp_contextmenu').remove();

            Q.each(value.items, function (key, val) {

                let item = Q(
                    '<div class="item">' +
                    '   <i class="' + val.icon + '"></i>' +
                    '   <span>' + val.label + '</span>' +
                    '</div>'
                );

                menu.append(item);

                item.on('click', function (e) {

                    val.callback(_this, _e);
                    menu.remove();

                });

            });

            Q('body').append(menu);

            let x = e.pageX;
            let y = e.pageY;
            let screenX = Q(window).width();
            let menuX = menu.width();

            if ((x + menuX + 10) > screenX) {
                x = x - ((x + menuX + 10) - screenX);
            }

            menu.css({
                top: y,
                left: x

            });

        });

    });


}

/**
 * Content
 *
 * @param data {content: obj, ok: fn, cancel: fn}
 */
MMP.liteBox = function (data) {

    let box = Q('<div class="mmp_lite_box"></div>');
    let overlay = Q('<div class="mmp_overlay"></div>');
    let content = Q('<div class="mmp_lite_box_content"></div>');
    let controller = Q('<div class="mmp_lite_box_controller"></div>');
    let ok = Q('<div class="mmp_lite_box_ok">' + MMP.localize.ok + '</div>');
    let cancel = Q('<div class="mmp_lite_box_cancel">' + MMP.localize.cancel + '</div>');

    content.append(data.content);
    box.append(content);

    if (data.cancel) {
        controller.append(cancel);
    }

    if (data.ok) {
        controller.append(ok);
    }

    if (data.ok || data.cancel) {
        box.append(controller);
    }

    Q('body').append(box);
    Q('body').append(overlay);
    setTimeout(function () {
        box.addClass('active');
        overlay.addClass('active');
    }, 160);

    ok.on('click', function () {

        data.ok();

        box.removeClass('active');
        overlay.removeClass('active');
        setTimeout(function () {
            box.remove();
            overlay.remove();
        }, 160);

    });

    cancel.on('click', function () {

        data.cancel();

        box.removeClass('active');
        setTimeout(function () {
            box.remove();
        }, 160);

    });

}

/**
 * Open wordpress media
 *
 * @param multi Multi select, default: disable
 * @param fn Run function after select
 * @param type ['video', 'image', 'music]
 */
MMP.wp.media = function (multi, fn = null, type = []) {

    let upload = wp.media({
        title: 'Choose Image', //Title for Media Box
        multiple: multi, //For limiting multiple image
        library: {
            type: type
        }
    }).on('select', function () {

        let select = upload.state().get('selection');
        let attach = select.first().toJSON();

        if (fn !== null)
            fn(attach);

    }).open();

}

/**
 * Standard time MMP
 *
 * @returns {string} Time
 */
MMP.time = function () {

    let date = new Date();
    let Month = date.getMonth() + 1;
    let Day = date.getDate();
    let Years = date.getFullYear();
    let Hours = date.getHours();
    let Minute = date.getMinutes();
    let AM = (Hours >= 12) ? 'PM' : 'AM';

    return Month + '/' + Day + '/' + Years + ' ' + Hours + ':' + Minute + ' ' + AM;

}

/**
 * Search value in array
 *
 * @param search
 * @param array
 * @returns {boolean}
 */
MMP.inArray = function (search, array) {

    let length = array.length;

    for (let i = 0; i < length; i++) {
        if (array[i] === search) return true;
    }

    return false;

}

/**
 * Remove value in array
 *
 * @param search
 * @param array
 * @returns {boolean}
 */
MMP.removeInArray = function (search, array) {

    let length = array.length;
    let reArray = [];

    for (let i = 0; i < length; i++) {
        if (array[i] !== search) reArray.push(array[i]);
    }

    return reArray;

}

/**
 * Generate uniq id
 *
 * @returns {string}
 */
MMP.uniqId = function () {

    var ts = String(new Date().getTime()), i = 0, out = '';

    for (i = 0; i < ts.length; i += 2) {
        out += Number(ts.substr(i, 2)).toString(36);
    }

    return ('d' + out);

}

/**
 * Remove loading
 *
 * @param object Jquery object
 */
MMP.removeLoading = function (object = false) {

    Q('body').find('.mmp_fix_loading').remove();

}

/**
 * Add loading
 *
 * @param id {string}
 * @param object {jQuery|HTMLElement}
 */
MMP.loading.add = function (id, object) {

    let loading = Q('<div id="' + MMP.uniqId() + '" class="mmp_loading"></div>');
    let content = Q(
        '<div class="content">' +
        '   <img src="' + MMP.localize._IMAGE + '/logo/logo-colored.svg" alt=""/>' +
        '   <div class="text">' + MMP.localize.loading_text + '...</div>' +
        '</div>'
    );

    loading.append(content);
    object.append(loading);
    loading.animate({
        opacity: 1
    }, 200);

    MMP.loading.object[id] = loading;

}

/**
 * Remove loading
 *
 * @param id {string}
 */
MMP.loading.remove = function (id) {

    MMP.loading.object[id].animate({
        opacity: 0
    }, 200);

    setTimeout(function () {

        MMP.loading.object[id].remove();
        delete MMP.loading.object[id];

    }, 350);

}

/**
 * Render message
 */
MMP.alert.render = function () {

    // Object main box
    let box = Q('<div class="mmp-alert-area"></div>');
    let enterCheck = false;

    // Add objects
    Q('body').append(box);

    // Create message
    Q.each(MMP.alert.message, function (index, value) {

        let item = MMP.alert.item(value);
        box.append(item);

    });

    MMP.alert.closeEffect(box.find('.mmp-alert-item'));

    // Mouse hover box
    box.on('mouseenter', function () {

        enterCheck = true;
        setTimeout(function () {

            if (!enterCheck) return false;

            box.addClass('active');
            MMP.alert.openEffect(box.find('.mmp-alert-item'));


        }, 300);

    });

    // Mouse leave box
    box.on('mouseleave', function () {

        enterCheck = false;

        setTimeout(function () {

            if (enterCheck) return false;

            box.removeClass('active');
            MMP.alert.closeEffect(box.find('.mmp-alert-item'));

        }, 100);

    });

}

/**
 * Close effect
 *
 * @param items
 */
MMP.alert.closeEffect = function (items) {

    let startBy = 0;
    let space = 26;

    Q.each(items, function (index, value) {

        let _ = Q(this);
        let height = 0;
        let desc = _.find('.description');

        _.attr('data-index', index);

        _.css({
            'left': space + 'px',
            'right': space + 'px',
            'z-index': items.length - index,
        });

        desc.css({
            'max-height': '36px'
        });
        _.removeClass('open');

        height = Number(_.css('height').match(/\d+/)[0]);

        switch (index) {

            case 0:

                _.animate({
                    'bottom': (startBy + 26) + 'px',
                    'opacity': '1'
                }, 300, 'swing');
                startBy += height + 26;

                break;
            case 1:
            case 2:

                _.animate({
                    'left': (space + (index * 10)) + 'px',
                    'right': (space + (index * 10)) + 'px',
                    'bottom': (startBy - (height - 16)) + 'px',
                    'opacity': '1'
                }, 300, 'swing');
                startBy += 16;

                break;
            default:

                _.animate({
                    'left': (space + 20) + 'px',
                    'right': (space + 20) + 'px',
                    'bottom': (startBy - (height - 16)) + 'px',
                    'opacity': '0'
                }, 300, 'swing');
                startBy += 16;

                break;

        }

    });

}

/**
 * Open effect
 *
 * @param items
 */
MMP.alert.openEffect = function (items) {

    let startBy = 0;
    let space = 26;

    Q.each(items, function (index, value) {

        let _ = Q(this);
        let height = Number(_.css('height').match(/\d+/)[0]);

        _.attr('data-index', index);

        _.animate({
            'left': space + 'px',
            'right': space + 'px',
            'bottom': (startBy + 26) + 'px',
            'opacity': '1'
        }, 300, 'swing');

        startBy += height + 26;

    });

}

/**
 * Add alert
 *
 * @param setting
 */
MMP.alert.add = function (setting) {

    let item = MMP.alert.item(setting);
    Q('.mmp-alert-area').prepend(item);
    MMP.alert.closeEffect(Q('.mmp-alert-item'));

}

/**
 * Create item
 *
 * @param setting
 * @returns {*}
 */
MMP.alert.item = function (setting) {

    let item = Q('<div class="mmp-alert-item"></div>');
    let contentArea = Q('<div class="flx"></div>');
    let buttonArea = Q('<div class="flx"></div>');
    let icon = Q('<div class="icon"></div>');
    let title = Q('<div class="title"></div>');
    let description = Q('<div class="description"></div>');
    let buttonClose = Q('<div class="close"><i class="icon-x"></i></div>');

    item.append(contentArea);
    item.append(buttonArea);

    if (setting.icon.type === 'icon') {
        icon.append('<i class="' + setting.icon.content + '"></i>');
    } else {
        icon.append('<img src="' + setting.icon.content + '" alt="">');
    }

    title.append(icon)
    title.append(setting.title);
    description.html(setting.description);

    contentArea.append(title);
    contentArea.append(description);
    buttonArea.append(buttonClose);

    item.on('click', function () {

        let _ = Q(this);
        let height = Number(_.css('height').match(/\d+/)[0]);
        let desc = _.find('.description');

        if (_.hasClass('open')) {

            desc.animate({
                'maxHeight': '36px'
            }, 300, 'swing', function () {
                MMP.alert.openEffect(Q('.mmp-alert-item'));
            });
            _.removeClass('open');

        } else {

            desc.animate({
                'maxHeight': (height + desc.height()) + 'px'
            }, 300, 'swing', function () {
                MMP.alert.openEffect(Q('.mmp-alert-item'));
            });
            _.addClass('open');

        }

    });

    buttonClose.on('click', function (e) {

        e.stopPropagation();

        item.remove();
        MMP.alert.openEffect(Q('.mmp-alert-item'));

    });

    return item;

}

