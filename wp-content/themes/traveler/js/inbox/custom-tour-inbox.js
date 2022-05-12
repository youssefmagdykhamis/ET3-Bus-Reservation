jQuery(function ($) {
    var parentDiv = $('.st-inbox-form-book');
    var requestRunning = false;
    var xhr;
    $('[data-toggle="tooltip"]').tooltip();
    $(document).ajaxStop(function () {
        $('.overlay-form').fadeOut(500);
    });
    //@since 2.0.0
    /**
     * Load select starttime ajax have post
     * */
    if($('#check_in').length) {
        var st_data_checkin = $('#check_in').val();
        var st_data_checkout = $('#check_out').val();
        if (st_data_checkin != st_data_checkout) {
            $('input#check_out').parent().parent().show();
        } else {
            $('input#check_out').parent().parent().hide();
        }
        var tour_type = $('input[name="type_tour"]').val();
        if (tour_type == 'daily_tour') {
            var st_data_checkout = $('#check_in').val();
            $('input#check_out').parent().hide();
        }
        if (st_data_checkin != '') {
            var st_data_tour_id = $('#starttime_hidden_load_form').data('tourid');
            var st_data_starttime = $('#starttime_hidden_load_form').data('starttime');
            if (st_data_starttime != "" && typeof st_data_starttime !== 'undefined') {
                if(st_data_checkout == '')
                    st_data_checkout = st_data_checkin;
                ajaxSelectStartTime(st_data_tour_id, st_data_checkin, st_data_checkout, st_data_starttime);
            }
        }
    }
    if ($('#select-a-tour').length) {
        $('#select-a-tour').on('click', function() {
            $('.check-in-out-input', parentDiv).trigger('click');
        });
    }
    var date_wrapper = $('.date-wrapper', parentDiv),
        check_in_input = $('input[name="check_in"]', parentDiv),
        check_out_input = $('input[name="check_out"]', parentDiv),
        check_in_out_input = $('.check-in-out-input', parentDiv),
        check_in_render = $('.check-in-render', parentDiv),
        check_out_render = $('.check-out-render', parentDiv),
        sts_checkout_label = $('.sts-tour-checkout-label', parentDiv),
        availabilityDate = $(this).data('availability-date');

    var customClass = $('.date-wrapper', parentDiv).data('custom-class') || '';

    var options = {
        singleDatePicker: true,
        showCalendar: false,
        sameDate: true,
        autoApply: true,
        disabledPast: true,
        dateFormat: 'DD/MM/YYYY',
        enableLoading: true,
        showEventTooltip: true,
        classNotAvailable: ['disabled', 'off'],
        disableHightLight: true,
        customClass: customClass,
        fetchEvents: function (start, end, el, callback) {
            var events = [];
            if (el.flag_get_events) {
                return false;
            }
            el.flag_get_events = true;
            el.container.find('.loader-wrapper').show();
            var data = {
                action: check_in_out_input.data('action'),
                start: start.format('YYYY-MM-DD'),
                end: end.format('YYYY-MM-DD'),
                tour_id: check_in_out_input.data('post-id'),
                security: st_params._s
            };

            $.post(st_params.ajax_url, data, function (respon) {
                if (typeof respon === 'object') {
                    if (typeof respon.events === 'object') {
                        events = respon.events;
                    } else {
                        events = respon;
                    }
                } else {
                    console.log('Can not get data');
                }
                callback(events, el);
                el.flag_get_events = false;
                el.container.find('.loader-wrapper').hide();
            }, 'json');
        }
    };

    $.fn.daterangepicker && $('.check-in-out-input', parentDiv)
        .daterangepicker(options, function (start, end, label, elmDate) {
            let checkIn = start.format(st_params.date_format_calendar.toUpperCase());
            let checkOut = end.format(st_params.date_format_calendar.toUpperCase());
            $('input#check_in').val(checkIn);
            $('input#check_out').val(checkOut)
            if (start.format(parentDiv.data('format')).toString() == end.format(parentDiv.data('format')).toString()) {
                sts_checkout_label.hide();
            } else {
                sts_checkout_label.show();
            }
            if (typeof elmDate !== 'undefined' && elmDate !== false) {
                if (elmDate.target.classList.contains('has_starttime')) {
                    ajaxSelectStartTime(check_in_out_input.data('post-id'), checkIn, checkOut, '');
                } else {
                    $('.st_tour_starttime option').remove();
                    $('#starttime_box').hide();
                }
            }

        });
    function ajaxSelectStartTime(tour_id, check_in, check_out, select_starttime) {
        var sparent = $('.package-info-wrapper');
        var overlay = $('.overlay-form', sparent);
        overlay.hide();
        xhr = $.ajax({
            url: st_params.ajax_url,
            dataType: 'json',
            type: 'post',
            data: {
                action: 'st_get_starttime_tour_frontend',
                tour_id: tour_id,
                check_in: check_in,
                check_out: check_out
            },
            beforeSend: function () {
                overlay.show();
                parentDiv.addClass('loading');
            },
            success: function (doc) {
                if (doc['data'] != null && doc['data'].length > 0) {
                    $('#starttime_tour option').remove();
                    $('#starttime_box').show();
                    var te = '';
                    for (i = 0; i < doc['data'].length; i++) {
                        var op_disable = '';
                        if (doc['check'][i] == '-1') {
                            if (doc['data'][i] == select_starttime) {
                                te += '<option value="' + doc['data'][i] + '" selected ' + op_disable + '>' + doc['data'][i] + '</option>';
                            } else {
                                te += '<option value="' + doc['data'][i] + '" ' + op_disable + '>' + doc['data'][i] + '</option>';
                            }
                        } else {
                            if (doc['check'][i] == '0') {
                                //op_disable = 'disabled="disabled"';
                                if (doc['data'][i] == select_starttime) {
                                    te += '<option value="' + doc['data'][i] + '" selected ' + op_disable + '>' + doc['data'][i] + ' ( ' + st_params.no_vacancy + ' )' + '</option>';
                                } else {
                                    te += '<option value="' + doc['data'][i] + '" ' + op_disable + '>' + doc['data'][i] + ' ( ' + st_params.no_vacancy + ' )' + '</option>';
                                }
                            } else {
                                if (doc['data'][i] == select_starttime) {
                                    if (doc['check'][i] == '1') {
                                        te += '<option value="' + doc['data'][i] + '" selected ' + op_disable + '>' + doc['data'][i] + ' ( ' + st_params.a_vacancy + ' )' + '</option>';
                                    } else {
                                        te += '<option value="' + doc['data'][i] + '" selected ' + op_disable + '>' + doc['data'][i] + ' ( ' + doc['check'][i] + ' ' + st_params.more_vacancy + ' )' + '</option>';
                                    }
                                } else {
                                    if (doc['check'][i] == '1') {
                                        te += '<option value="' + doc['data'][i] + '" ' + op_disable + '>' + doc['data'][i] + ' ( ' + st_params.a_vacancy + ' )' + '</option>';
                                    } else {
                                        te += '<option value="' + doc['data'][i] + '" ' + op_disable + '>' + doc['data'][i] + ' ( ' + doc['check'][i] + ' ' + st_params.more_vacancy + ' )' + '</option>';
                                    }
                                }
                            }
                        }
                    }
                    $('#starttime_tour option').remove();
                    $('#starttime_tour').append(te);
                    overlay.hide();
                    parentDiv.removeClass('loading');
                } else {
                    $('#starttime_box').hide();
                    overlay.hide();
                }
                requestRunning = false;
            },
        });
        requestRunning = true;
    }
});
