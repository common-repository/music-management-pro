jQuery(document).ready(function ($) {

    // Click on multi select
    $(document).on('click', '.mmp_multi_select', function () {

        let _this = $(this);
        let box = $('<div class="mmp_multi_select_box"></div>');
        let overlay = $('<div class="mmp_multi_select_overlay"></div>');
        let header = $('<div class="mmp_multi_select_header"></div>');
        let search = $('<input class="mmp_multi_select_search" type="text" placeholder="' + MMP.localize.search + '" />');
        let content = $('<div class="mmp_multi_select_content"></div>');
        let controller = $('<div class="mmp_multi_select_controller"></div>');
        let save = $('<div class="mmp_multi_select_save">' + MMP.localize.save + '</div>');
        let cancel = $('<div class="mmp_multi_select_cancel">' + MMP.localize.cancel + '</div>');
        let multi_select = _this.attr('data-multi-select');
        let ajax_action = _this.attr('data-ajax-action');

        let n = $(this).find('input.save').val();
        let default_value = n === '' ? [] : n.split(',');
        let default_text = {};
        let default_image = {};

        header.append(search);
        controller.append(cancel);
        controller.append(save);
        box.append(header);
        box.append(content);
        box.append(controller);
        $('body').append(overlay).append(box);
        search.focus();

        AjaxMultiSelect(ajax_action, '');

        setTimeout(function () {

            box.addClass('active');
            overlay.addClass('active');

        }, 60);

        cancel.on('click', function () {

            box.removeClass('active');
            overlay.removeClass('active');

            setTimeout(function () {

                box.remove();
                overlay.remove();

            }, 190);

        });

        search.on('keyup', function () {

            AjaxMultiSelect(ajax_action, $(this).val());

        });

        save.on('click', function () {

            _this.find('.mmp_multi_select_option').remove();
            _this.find('input.save').val(default_value.join(','));

            $.each(default_value, function (index, value) {

                let image = '';
                if (default_image[value]) {
                    image = '<div class="mmp_multi_select_img" style="background:url(' + default_image[value] + ')"></div>';
                }

                _this.prepend('<div class="mmp_multi_select_option" data-val="' + value + '">' + image +
                    '   <span>' + default_text[value] + '</span>' +
                    '   <i class="icon-x"></i>' +
                    '</div>');

            });

            cancel.trigger('click');

        });

        /**
         * Ajax Multi Select
         *
         * @param action Action ajax
         * @param search Search text
         * @constructor
         */
        function AjaxMultiSelect(action, search) {

            let ajaxData = {
                action: action,
                nonce: MMP.localize.Nonce,
                search: search,
                id: default_value
            }

            $.ajax({
                type: "POST",
                url: MMP.localize.AjaxUrl,
                data: ajaxData,
                success: function (resp) {

                    content.html('');

                    $.each(resp, function (index, value) {

                        let item = $('<div class="mmp_multi_select_option" data-val="63"></div>');
                        let img = $('<div class="mmp_multi_select_img"></div>');
                        let itemContent = $('<span></span>');

                        if (value.image) {

                            img.attr('style', 'background:url(' + value.image + ');');
                            img.attr('data-link', value.image);
                            item.append(img);

                        }

                        if (MMP.inArray(value.value, default_value)) {

                            if (!default_text[value.value]) {
                                default_text[value.value] = value.text;
                            }
                            if (!default_image[value.value] && value.image) {
                                default_image[value.value] = value.image;
                            }
                            item.addClass('active');

                        }

                        item.attr('data-val', value.value);
                        itemContent.html(value.text);

                        item.append(itemContent);
                        content.append(item);

                        item.on('click', function () {

                            let _ = $(this);
                            let id = _.attr('data-val');

                            if (!multi_select) {
                                _.parent().find('.active').removeClass('active');
                                default_value = [];
                                default_text = {};
                                default_image = {};
                            }

                            if (_.hasClass('active')) {

                                _.removeClass('active');
                                default_value = MMP.removeInArray(id, default_value);
                                delete default_text[id];
                                delete default_image[id];

                            } else {

                                _.addClass('active');
                                default_text[id] = _.find('span').html();

                                if (_.find('.mmp_multi_select_img')) {
                                    default_image[id] = _.find('.mmp_multi_select_img').attr('data-link');
                                }

                                default_value.push(id);

                            }

                        });

                    });

                },
                timeout: 10000
            });

        }

    });

    $(document).on('click', '.mmp_multi_select_option i', function (e) {

        // Stop parent event
        e.stopPropagation();

        let _ = $(this).parent();
        let save = _.parent().find('.save');
        let id = _.attr('data-val');
        let values = save.val().split(',');

        _.remove();
        save.val(MMP.removeInArray(id, values).join(','));

    });

});