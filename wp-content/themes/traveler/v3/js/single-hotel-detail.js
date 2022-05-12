;(function ($) {
    "use strict";
    let SingleHotelDetail = {
        $body: $('body'),
        isValidated: {},
        renderHtmlHotel(){
            var data = jQuery('form.hotel-room-booking-form').serializeArray();
            jQuery('.loader-wrapper').hide();
            data.push({
                name: 'security',
                value: st_params._s
            });
            for (var i = 0; i < data.length; i++) {

                if(data[i].name === 'action'){
                    data[i]['value'] = 'st_format_hotel_price';
                }
            };
            jQuery.ajax({
                method: "post",
                dataType: 'json',
                data: data,
                url: st_params.ajax_url,
                beforeSend: function () {
                    jQuery('.loader-wrapper').show();
                    jQuery('div.message-wrapper').html("");
                    jQuery('.message_box').html('');
                },
                success: function (response) {
                    jQuery('.loader-wrapper').hide();
                    if (response) {
                        if (response.price_html) {
                            if (jQuery('.form-head').length > 0) {
                                if(response.price_html){
                                    jQuery('.form-head').html(response.price_html);
                                }
                            }
                            if (jQuery('.hotel-target-book-mobile').length > 0) {
                                jQuery('.hotel-target-book-mobile .price-wrapper').html(response.price_html);
                            }
                            jQuery('.message_box').html('');
                            jQuery('div.message-wrapper').html("");
                        } else {
                            if(response.message){
                                jQuery('.message-wrapper').html('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> '+response.message+ ' </div>');
                            }
                        }
                    }
                }
            });
        },
        init: function () {
            let base = this;
            base._resize(base.$body);
            base._share();
            base._stContent(base.$body);
            base._stReviewSingle();
            base._stMapSingle(base.$body);
            base._stcheckavailabilityHotel(base.$body);
            base._stReviewSingleRoom(base.$body);
            base._stToggleExtra(base.$body);
            base._stSentMessageToOwner();
            base._stSentMailInquiry();
            base._stDateFieldCheckInCheckOut(base.$body);
            base._bookingRoomAjax();
            base._bookingRoomChangeAjax();
            base._dropdownselectDate();
            
        },
        _dropdownselectDate: function(){
            $('.form-date-search', 'body').each(function () {
                var parent = $(this),
                        date_wrapper = $('.date-wrapper', parent),
                        check_in_input = $('.check-in-input', parent),
                        check_out_input = $('.check-out-input', parent),
                        check_in_out = $('.check-in-out', parent),
                        check_in_render = $('.check-in-render', parent),
                        check_out_render = $('.check-out-render', parent);
                var timepicker = parent.data('timepicker');
                if (typeof timepicker == 'undefined' || timepicker == '') {
                    timepicker = false;
                } else {
                    timepicker = true;
                }
                var options = {
                    singleDatePicker: false,
                    sameDate: false,
                    autoApply: true,
                    minDate: new Date(),
                    dateFormat: 'DD/MM/YYYY',
                    customClass: '',
                    widthSingle: 500,
                    onlyShowCurrentMonth: true,
                    timePicker: timepicker,
                    timePicker24Hour: (st_params.time_format == '12h') ? false : true,
                };
                if (typeof locale_daterangepicker == 'object') {
                    options.locale = locale_daterangepicker;
                }
                check_in_out.daterangepicker(options,
                    function (start, end, label) {
                        check_in_input.val(start.format(parent.data('format'))).trigger('change');
                        $('#tp_hotel .form-date-search .check-in-input').val(start.format('YYYY-MM-DD')).trigger('change');
                        check_in_render.html(start.format(parent.data('format'))).trigger('change');
                        check_out_input.val(end.format(parent.data('format'))).trigger('change');
                        $('#tp_hotel .form-date-search .check-out-input').val(end.format('YYYY-MM-DD')).trigger('change');
                        check_out_render.html(end.format(parent.data('format'))).trigger('change');
                        if (timepicker) {
                            check_in_input.val(start.format(parent.data('date-format'))).trigger('change');
                            $('.check-in-input-time', parent).val(start.format(parent.data('time-format'))).trigger('change');
                            check_out_input.val(end.format(parent.data('date-format'))).trigger('change');
                            $('.check-out-input-time', parent).val(end.format(parent.data('time-format'))).trigger('change');
                            $('.check-out-input-time', parent).val(end.format(parent.data('time-format'))).trigger('change');
                        }
                        check_in_out.trigger('daterangepicker_change', [start, end]);
                        if (window.matchMedia('(max-width: 767px)').matches) {
                            $('label', parent).hide();
                            $('.render', parent).show();
                            $('.check-in-wrapper span', parent).show();
                        }
                    }
                );
                
                date_wrapper.on('click',function (e) {
                    check_in_out.trigger('click');
                });
            });
            
        },
        _bookingRoomChangeAjax: function(){
            $( ".hotel-room-booking-form" ).on( "hotel-room-booking-form", function( event ) {
                SingleHotelDetail.renderHtmlHotel();
            });
            if($('.hotel-room-booking-form').length > 0) {
                let flag = false;
                let ci_hotel = 0;
                $('.check-in-input').on('change', function (e) {
                    if (ci_hotel != 0) {
                        SingleHotelDetail.renderHtmlHotel();
                    }
                    ci_hotel++;
                });
                if ($('.field-guest').length > 0) {
                    $('.field-guest').each(function () {
                        $(this).find('.form-control.st-input-number').on('change',function(){
                            SingleHotelDetail.renderHtmlHotel();
                        })
                    });
                }
                if ($('.form-more-extra .extras').length > 0) {
                    $('.form-more-extra .extras li').each(function () {
                        $(this).find('.extra-service-select').on('change',function(){
                            SingleHotelDetail.renderHtmlHotel();
                        })
                    });
                }
                if (flag) {
                    SingleHotelDetail.renderHtmlHotel();
                }
    
            }
        },
        _bookingRoomAjax: function(){
            $('form.hotel-room-booking-form').on('click', 'button.btn-book-ajax', function (e) {
                e.preventDefault();
                var form = $('form.hotel-room-booking-form');
                var data = $('form.hotel-room-booking-form').serializeArray();
                var loadingSubmit = form.find('button[name=submit]');
                $(loadingSubmit).find("i.fa-spin").removeClass("d-none");
                data.push({
                    name: 'security',
                    value: st_params._s
                });
                $('div.message-wrapper').html("");
                $.ajax({
                    url: st_params.ajax_url,
                    method: "post",
                    dataType: 'json',
                    data: data,
                    beforeSend: function () {
                        $('div.message-wrapper').html("");
                    },
                    success: function (res) {
                        $(loadingSubmit).find('i.fa-spin').addClass("d-none");
                        if (res) {
                            if (res.status) {
                                if (res.redirect) {
                                    window.location = res.redirect;
                                }
                            } else {
                                if (res.message) {
                                    $('div.message-wrapper').html(res.message);
                                }
                            }
                        }
                    },
                    error: function (err) {
                        $('div.message-wrapper').html("");
                        $(loadingSubmit).find('i.fa-spin').addClass("d-none");
                    }
                });
            });
        },
        _stDateFieldCheckInCheckOut: function (body){
            $('.form-date-hotel-room', body).each(function () {
                var parent = $(this),
                        date_wrapper = $('.date-wrapper', parent),
                        check_in_input = $('.check-in-input', parent),
                        check_out_input = $('.check-out-input', parent),
                        check_in_out = $('.check-in-out', parent),
                        check_in_render = $('.check-in-render', parent),
                        check_out_render = $('.check-out-render', parent),
                        availabilityDate = $(this).data('availability-date');
                var minimum = check_in_out.data('minimum-day');
                if (typeof minimum !== 'number') {
                    minimum = 0;
                }
                var options = {
                    singleDatePicker: false,
                    autoApply: true,
                    disabledPast: true,
                    dateFormat: 'DD/MM/YYYY',
                    widthSingle: 500,
                    onlyShowCurrentMonth: true,
                    minimumCheckin: minimum,
                    classNotAvailable: ['disabled', 'off'],
                    enableLoading: true,
                    showEventTooltip: true,
                    fetchEvents: function (start, end, el, callback) {
                        var events = [];
                        if (el.flag_get_events) {
                            return false;
                        }
                        el.flag_get_events = true;
                        el.container.find('.loader-wrapper').show();
                        var data = {
                            action: check_in_out.data('action'),
                            start: start.format('YYYY-MM-DD'),
                            end: end.format('YYYY-MM-DD'),
                            post_id: check_in_out.data('room-id'),
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
        
                if (typeof availabilityDate != 'undefined') {
                    options['minDate'] = availabilityDate;
                }
        
                if (typeof locale_daterangepicker == 'object') {
                    options.locale = locale_daterangepicker;
                }
                check_in_out.daterangepicker(options,
                    function (start, end, label) {
                        check_in_input.val(start.format(parent.data('format'))).trigger('change');
                        check_in_render.html(start.format(parent.data('format'))).trigger('change');
                        check_out_input.val(end.format(parent.data('format'))).trigger('change');
                        check_out_render.html(end.format(parent.data('format'))).trigger('change');
                        if(st_params.caculator_price_single_ajax === 'on'){
                            if($('.single-st_rental .single-room-form').length > 0) {
                                date_wrapper.trigger('rental-booking-form');
                            }
                        }
                });
        
        
                date_wrapper.on('click',function (e) {
                    check_in_out.trigger('click');
        
                });
        
                
                check_in_out.on('apply.daterangepicker', function(ev, picker) {
                    if(st_params.caculator_price_single_ajax === 'on'){
                        if($('.single-hotel_room .single-room-form').length > 0) {
                            $('.hotel-room-booking-form').trigger('hotel-room-booking-form');
                        }
                    }
                    
                });
            });
        
        },
        _stSentMailInquiry(){
            $('.form-st-send-mail .st_send-mail-form .sent-email-st').on('click',function (ev) {
                ev.preventDefault();
                var type_service = $("input[name=type_service]").val();
                var name_service = $("input[name=name_service]").val();
                var name_st = $("input[name=name_st]").val();
                var email_st = $("input[name=email_st]").val();
                var phone_st = $("input[name=phone_st]").val();
                var content_st = $("textarea[name=content_st]").val();
                var email_owl = $("input[name=email_owl]").val();
                $('.st-sent-mail-customer .loader-wrapper').show();
                $.ajax({
                    url: st_params.ajax_url,
                    type: "GET",
                    data: {
                        'action': "st_send_email_single_service",
                        'type_service': type_service,
                        'name_service': name_service,
                        'name_st': name_st,
                        'email_st': email_st,
                        'phone_st': phone_st,
                        'content_st': content_st,
                        'email_owl': email_owl
                    },
                    dataType: "json",
                    beforeSend: function () {
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                    },
                    success: function (res) {
        
                    },
                    complete: function (xhr, status) {
        
                        if (xhr.responseJSON.status != 0) {
                            var mess = '<div class="ccv-success"><div class="content-message">' + xhr.responseJSON.message + '</div></div>';
                            $('.form-st-send-mail .st_send-mail-form').html(mess);
                            $('.st-sent-mail-customer .loader-wrapper').hide();
                        } else {
                            var mess = '<div class="alert alert-danger"><button type="button" class="close" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' + xhr.responseJSON.message + '</div>';
                            $('.form-st-send-mail .message-wrapper-sendemail').html(mess);
                            $('.st-sent-mail-customer .loader-wrapper').hide();
                        }
                    }
                });
            });
        },
        _stSentMessageToOwner(){
            $(document).on("ready", function () {
                $('.single .st_ask_question #btn-send-message-owner').on('click',function(e){
                e.preventDefault();
                jQuery(".btn-send-message").trigger('click');
                });
            })
        },
        _stToggleExtra(body){
            $('.form-more-extra', body).each(function () {
                var t = $(this),
                parent = t.closest('.form-more-extra');
                $('.dropdown', parent).on('click',function (ev) {
                    ev.preventDefault();
                    $('.extras', parent).slideToggle(200);
                    $('.arrow', parent).toggleClass('fa-caret-up fa-caret-down');
                });
            });
        },
        _stReviewSingleRoom: function(body){
            $('.review-form .review-items .rates .fa').each(function () {
                var list = $(this).parent(),
                        listItems = list.children(),
                        itemIndex = $(this).index(),
                        parentItem = list.parent();
        
                $(this).on('mouseenter', function () {
                    for (var i = 0; i < listItems.length; i++) {
                        if (i <= itemIndex) {
                            $(listItems[i]).addClass('hovered');
                        } else {
                            break;
                        }
                    }
                    $(this).on('click',function () {
                        for (var i = 0; i < listItems.length; i++) {
                            if (i <= itemIndex) {
                                $(listItems[i]).addClass('selected');
                            } else {
                                $(listItems[i]).removeClass('selected');
                            }
                        }
                        ;
                        parentItem.children('.st_review_stats').val(itemIndex + 1);
                    });
                });
        
                $(this).on('mouseleave', function () {
                    listItems.removeClass('hovered');
                });
            });
            $('.review-form .st-stars .fa').each(function () {
                var list = $(this).parent(),
                        listItems = list.children(),
                        itemIndex = $(this).index(),
                        parentItem = list.parent();
                $(this).on('mouseenter', function () {
                    for (var i = 0; i < listItems.length; i++) {
                        if (i <= itemIndex) {
                            $(listItems[i]).addClass('hovered');
                        } else {
                            break;
                        }
                    }
                    $(this).on('click',function () {
                        for (var i = 0; i < listItems.length; i++) {
                            if (i <= itemIndex) {
                                $(listItems[i]).addClass('selected');
                            } else {
                                $(listItems[i]).removeClass('selected');
                            }
                        }
                        parentItem.children('.st_review_stats').val(itemIndex + 1);
                    });
                });
                $(this).on('mouseleave', function () {
                    listItems.removeClass('hovered');
                });
            });
        },
        _stcheckavailabilityHotel: function(body){
            $('.form-check-availability-hotel', body).on('submit',function (ev) {
                ev.preventDefault();
                var form = $(this),
                        parent = form.parent(),
                        loader = $('.loader-wrapper', parent),
                        message = $('.message-wrapper', form);
                var has_fixed = form.closest('.fixed-on-mobile');
                if (has_fixed.hasClass('open')) {
                    has_fixed.removeClass('open').hide();
                    body.removeClass('st_overflow');
                }
                var data = form.serializeArray();
                data.push({
                    name: 'security',
                    value: st_params._s
                });
                message.html('');
                loader.show();
                $('.st-list-rooms .loader-wrapper').show();
                $.post(st_params.ajax_url, data, function (respon) {
                    if (typeof respon == 'object') {
                        if (respon.message) {
                            message.html(respon.message);
                        }
                        $('.st-list-rooms .fetch').html(respon.html);
                        $('html, body').animate({
                            scrollTop: $('.st-list-rooms', body).offset().top - 150
                        }, 500);
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                    $('.st-list-rooms .loader-wrapper').hide();
                    loader.hide();
                }, 'json');
            });
            body.on('click', '.btn-show-price', function (ev) {
                ev.preventDefault();
                $('.form-check-availability-hotel', body).trigger('submit');
            });

            //Mobile
            $('.hotel-target-book-mobile .btn-mpopup', body).on('click',function (ev) {
                ev.preventDefault();
                $('.fixed-on-mobile', body).toggleClass('open').fadeToggle(300);
                $(body).addClass('st_overflow');
            });
            $('.fixed-on-mobile .close-icon', body).on('click',function (ev) {
                ev.preventDefault();
                $('.fixed-on-mobile', body).toggleClass('open').fadeToggle(300);
                $(body).removeClass('st_overflow');
            });
        },
        _stMapSingle: function(body){
            $('.st-map', body).each(function () {
                var parent = $(this),
                        mapEl = $('.google-map', parent),
                        mapData = mapEl.data('data_show'),
                        lat_center = mapEl.data('lat'),
                        lng_center = mapEl.data('lng'),
                        data_zoom = mapEl.data('zoom'),
                        mapIcon = mapEl.data('icon');
        
                initMapDetail(mapEl, mapData, lat_center, lng_center, data_zoom, mapIcon);
        
            });
            function initMapDetail(mapEl, mapData, mapLat, mapLng, mapZoom, mapIcon) {
                mapData = mapEl.data('data_show');
                if (mapLat.length <= 0)
                    mapLat = mapEl.data('lat');
                if (mapLng.length <= 0)
                    mapLng = mapEl.data('lng');
                if (mapZoom.length <= 0)
                    mapZoom = 16;
                if (mapIcon.length <= 0)
                    mapIcon = mapEl.data('icon');
                var map = new google.maps.Map(document.getElementById('list_map'), {
                    zoom: mapZoom,
                    center: new google.maps.LatLng(mapLat, mapLng),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });
                var infowindow = new google.maps.InfoWindow({
                    maxWidth: 400,
                    maxHeight: 300
                });
                var marker, i;
            
                if (typeof mapData != 'undefined' && Object.keys(mapData).length) {
                    var markers = jQuery.map(mapData, function (location, i) {
                        var icon = {
                            url: mapData[i].icon_mk,
                            size: {
                                width: 40,
                                height: 50
                            },
                            origin: new google.maps.Point(0, 0), // origin
                        };
                        marker = new google.maps.Marker({
                            position: new google.maps.LatLng(parseFloat(mapData[i].lat), parseFloat(mapData[i].lng)),
                            map: map,
                            icon: icon,
                            optimized: false
                        });
            
                        var bounds = new google.maps.LatLngBounds();
                        var loc = new google.maps.LatLng(parseFloat(mapData[i].lat), parseFloat(mapData[i].lng));
                        bounds.extend(loc);
                        var ibOptions = {
                            content: '',
                            disableAutoPan: true
                            , maxWidth: 0
                            , zIndex: null
                            , boxStyle: {
                                padding: "0px 0px 0px 0px",
                            },
                            closeBoxURL: "",
                            cancelBubble: true,
                            infoBoxClearance: new google.maps.Size(1, 1),
                            isHidden: false,
                            pane: "floatPane",
                            enableEventPropagation: true,
                            alignBottom: true
                        };
                        if (i < Object.keys(mapData).length) {
                            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                                return function () {
                                    infowindow.setContent(mapData[i].content_html);
                                    if ((mapData[i].lat != mapLat) && (mapData[i].lng != mapLng)) {
                                        infowindow.open(map, marker);
                                    }
            
                                }
                            })(marker, i));
                        }
            
                    });
                }
            
            }
        },
        _stReviewSingle: function(){
            $('.review-form .review-items .rates i').each(function () {
                var list = $(this).parent(),
                        listItems = list.children(),
                        itemIndex = $(this).index(),
                        parentItem = list.parent();
        
                $(this).on('mouseenter', function () {
                    for (var i = 0; i < listItems.length; i++) {
                        if (i <= itemIndex) {
                            $(listItems[i]).addClass('hovered');
                        } else {
                            break;
                        }
                    }
                    $(this).on('click',function () {
                        for (var i = 0; i < listItems.length; i++) {
                            if (i <= itemIndex) {
                                $(listItems[i]).addClass('selected');
                            } else {
                                $(listItems[i]).removeClass('selected');
                            }
                        }
                        ;
                        parentItem.children('.st_review_stats').val(itemIndex + 1);
                    });
                });
        
                $(this).on('mouseleave', function () {
                    listItems.removeClass('hovered');
                });
            });
            $('.review-form .st-stars i').each(function () {
                var list = $(this).parent(),
                        listItems = list.children(),
                        itemIndex = $(this).index(),
                        parentItem = list.parent();
                $(this).on('mouseenter', function () {
                    for (var i = 0; i < listItems.length; i++) {
                        if (i <= itemIndex) {
                            $(listItems[i]).addClass('hovered');
                        } else {
                            break;
                        }
                    }
                    $(this).on('click',function () {
                        for (var i = 0; i < listItems.length; i++) {
                            if (i <= itemIndex) {
                                $(listItems[i]).addClass('selected');
                            } else {
                                $(listItems[i]).removeClass('selected');
                            }
                        }
                        parentItem.children('.st_review_stats').val(itemIndex + 1);
                    });
                });
                $(this).on('mouseleave', function () {
                    listItems.removeClass('hovered');
                });
            });
        },
        _stContent: function(body){
            //----------- Description Tour-------------------------------

            $('.st-description-more .stt-more').on('click', function () {
                $('.st-description-more').hide();
                $('.st-description-less').show();
            });
            $('.st-description-less .stt-less').on('click', function () {
                $('.st-description-less').hide();
                $('.st-description-more').show();
            });
            $('[data-show-all]', body).each(function () {
                var t = $(this);
                var height = t.data('height');
                t.css('height', height);
            });
            body.on('click', '[data-show-target]', function (ev) {
                ev.preventDefault();
                var target = $(this).data('show-target');
                $('.fa', this).toggleClass('fa-caret-up fa-caret-down');
                if ($('.fa', this).hasClass('fa-caret-up')) {
                    $('.text', this).html($(this).data('text-less'));
                } else {
                    $('.text', this).html($(this).data('text-more'));
                }
                if ($('[data-show-all="' + target + '"]', body).hasClass('open')) {
                    $('[data-show-all="' + target + '"]', body).css({height: $('[data-show-all="' + target + '"]', body).data('height')});
                } else {
                    $('[data-show-all="' + target + '"]', body).css({height: ''});
                }
                $('[data-show-all="' + target + '"]', body).toggleClass('open');
            });
            //----------- End Description Tour---------------------------
        },
        _share:function(){
            $('.shares .social-share').on('click',function (ev) {
                ev.preventDefault();
                $('.shares .share-wrapper').slideToggle(200);
            });
            $(".st-video-popup").each(function () {
                $(this).magnificPopup({
                    type: 'iframe'
                })
            });
            $(document).on('click', '.btn_add_wishlist', function (event) {
                event.preventDefault();
                var $this = $(this);
                $.ajax({
                    url: st_params.ajax_url,
                    type: "POST",
                    data: {action: "st_add_wishlist", data_id: $(this).data('id'), data_type: $(this).data('type')},
                    dataType: "json",
                }).done(function (html) {
                    $this.html(html.icon).attr("data-original-title", html.title)
                })
            });
        },
        _resize:function(body){
            var timeout_fixed_item;
            $(window).on('resize', function () {
                clearTimeout(timeout_fixed_item);
                timeout_fixed_item = setTimeout(function () {
                  
                    $('.st-hotel-content', 'body').each(function () {
                        var t = $(this);
                        $(window).on('scroll',function () {
                            if ($(window).scrollTop() >= 50 && window.matchMedia('(max-width: 991px)').matches) {
                                t.css('display', 'flex');
                            } else {
                                t.css('display', 'none');
                            }
                        });
                    });
                }, 1000);
            }).trigger('resize');
            //Slider gallery single hotel detail
            if (window.matchMedia('(min-width: 992px)').matches) {
                $('.st-gallery', body).each(function () {
                    var parent = $(this);
                    var $fotoramaDiv = $('.fotorama', parent).fotorama({
                        width: parent.data('width'),
                        nav: parent.data('nav'),
                        thumbwidth: '135',
                        thumbheight: '135',
                        allowfullscreen: parent.data('allowfullscreen')
                    });
                    parent.data('fotorama', $fotoramaDiv.data('fotorama'));
                });
            } else {
                $('.st-gallery', body).each(function () {
                    var parent = $(this);
                    if (typeof parent.data('fotorama') !== 'undefined') {
                        parent.data('fotorama').destroy();
                    }
                    var $fotoramaDiv = $('.fotorama', parent).fotorama({
                        width: parent.data('width'),
                        nav: parent.data('nav'),
                        thumbwidth: '80',
                        thumbheight: '80',
                        allowfullscreen: parent.data('allowfullscreen')
                    });
                    parent.data('fotorama', $fotoramaDiv.data('fotorama'));
                });
            }
            if (window.matchMedia('(min-width: 992px)').matches) {
                $('.full-map').show();
            } else {
                $('.full-map').hide();
            }
            if (window.matchMedia('(max-width: 991px)').matches) {
                $('.as').slideDown();
            }

        }
    }
    SingleHotelDetail.init();
})(jQuery);