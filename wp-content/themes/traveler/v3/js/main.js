;(function ($) {
    "use strict";
    let Header = {
        $body: $('body'),
        isValidated: {},
        init: function () {
            let base = this;
            base._mobile_menu(base.$body);
            base._login_signup(base.$body);
            base._toglesidebar();
            base._moreSidebar();
            base._mobileLocation();
            base._toolTip();
            base._toggleSection(base.$body);
            base._reviewListSingle(base.$body);
            base._matchHeight(base.$body);
            base._likeReview();
            base._languageCurrency();
        },
        _languageCurrency: function(){
            $('.select2-languages').on('change',function () {
                var target = $('option:selected', this).data('target');
                if (target) {
                    window.location.href = target;
                }
            });
            $('.select2-currencies').on('change',function () {
                var target = $('option:selected', this).data('target');
                if (target) {
                    window.location.href = target;
                }
            });
        },
        _likeReview: function (){
            $('.st-like-review').on('click',function (e) {
                e.preventDefault();
                var me = $(this);
                var comment_id = me.data('id');
                $.ajax({
                    url: st_params.ajax_url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        action: 'like_review',
                        comment_ID: comment_id
                    },
                    success: function (res) {
                        if (res.status) {
                            $('i', me).toggleClass('fa-thumbs-up fa-thumbs-down');
                            if ($('.booking-item-review-rate').length) {
                                $(me).toggleClass('fa-thumbs-up fa-thumbs-down');
                            }
                            if (typeof res.data.like_count != undefined) {
                                res.data.like_count = parseInt(res.data.like_count);
                                me.parent().find('span').html(res.data.like_count);
                            }
                        }
                    }
                });
            });
        },
        _reviewListSingle: function(body){
            $('.review-list', body).on('click', '.show-more', function (ev) {
                ev.preventDefault();
                var parent = $(this).closest('.comment');
                $(this).css('display', 'none');
                $('.review', parent).slideDown(200);
                $('.show-less', parent).css('display', 'block');
            });
            $('.review-list', body).on('click', '.show-less', function (ev) {
                ev.preventDefault();
                var parent = $(this).closest('.comment');
                $(this).css('display', 'none');
                $('.review', parent).slideUp(200);
                $('.show-more', parent).css('display', 'block');
            });
        },
        _toggleSection: function(body){
            body.on('click', '.toggle-section', function (ev) {
                ev.preventDefault();
                var t = $(this);
                var target = t.data('target');
                $('.fas', t).toggleClass('fa-angle-up fa-angle-down');
                $('[data-toggle-section="' + target + '"]').slideToggle(200);
                $('.has-matchHeight', body).matchHeight();
            });
        },
        _matchHeight: function(body){
            if ($('.has-matchHeight', body).length) {
                $('.has-matchHeight', body).matchHeight();
            }
        },
        _mobileLocation: function(){
            $('.search-form-mobile .dropdown-menu li').on('click',function () {
                var t = $(this);
                var parent = t.closest('.search-form-mobile');
                console.log(t.find('span').text());
                $('input[name="location_id"]', parent).val(t.data('value'));
                $('input[name="location_name"]', parent).val(t.find('span').text());
            });
        },
        _toolTip: function(){
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        },
        _moreSidebar: function(){
            if ($('.btn-more-item').length) {
                $('.btn-more-item').each(function () {
                    var t = $(this);
                    var parent = t.closest('.item-content');
                    if (parent.find('ul li').length > 3) {
                        t.show();
                    }
                    t.on('click', function () {
                        var countLi = parent.find('ul li.hidden').length;
                        var max = 3;
                        if (countLi < 3) {
                            max = countLi;
                        }
                        for (var i = 0; i < max; i++) {
                            parent.find('ul li.hidden').eq(0).removeClass('hidden');
                        }
                        var countLi = parent.find('ul li.hidden').length;
                        if (countLi <= 0) {
                            t.hide();
                        }
                    });
                });
            }
        },
        _toglesidebar: function (body){
            /*Sidebar toggle*/
            if ($('.sidebar-item').length) {
                $('.sidebar-item').each(function () {
                    var t = $(this);
                    if (t.hasClass('open')) {
                        t.find('.item-content').slideUp();
                    }
                });
            }
            $('.sidebar-item .item-title').on('click', function () {
                var t = $(this);
                t.parent().toggleClass('open');
                t.parent().find('.item-content').slideToggle();
            });
        },
        _login_signup: function(body){
            $('#st-login-form form', body).on('submit',function (ev) {
                ev.preventDefault();
                var form = $(this),
                        loader = form.closest('.modal-content').find('.loader-wrapper'),
                        message = $('.message-wrapper', form);
                var data = form.serializeArray();
                data.push({
                    name: 'security',
                    value: st_params._s
                });
                message.html('');
                loader.show();
                $.post(st_params.ajax_url, data, function (respon) {
                    if (typeof respon == 'object') {
                        message.html(respon.message);
                        setTimeout(function () {
                            message.html('');
                        }, 4000);
                        if (respon.status == 1) {
                            setTimeout(function () {
                                window.location.href = respon.redirect;
                            }, 4000);
                        }
                    }
                    loader.hide();
                }, 'json');
            });
            $('#st-register-form form', body).on('submit',function (ev) {
                ev.preventDefault();
                var form = $(this),
                        loader = form.closest('.modal-content').find('.loader-wrapper'),
                        message = $('.message-wrapper', form);
                var data = form.serializeArray();
                data.push({
                    name: 'security',
                    value: st_params._s
                });
                message.html('');
                loader.show();
                $.post(st_params.ajax_url, data, function (respon) {
                    loader.hide();
                    if (typeof respon == 'object') {
                        message.html(respon.message);
                        if (respon.status == 1) {
                            swal({
                                type: 'success',
                                title: respon.message,
                                text: respon.sub_message,
                                showConfirmButton: true,
                                confirmButtonText: respon.closeText,
                                onClose: function () {
                                    $('#st-login-form', body).modal('show');
                                    $('#st-register-form', body).modal('hide');
                                },
                                allowOutsideClick: false
                            });
                        } else {
                            message.html(respon.message);
                            setTimeout(function () {
                                message.html('');
                            }, 4000);
                        }
                    }
                }, 'json');
            });
            $('#st-forgot-form form', body).on('submit',function (ev) {
                ev.preventDefault();
                var form = $(this),
                        loader = form.closest('.modal-content').find('.loader-wrapper'),
                        message = $('.message-wrapper', form);
                var data = form.serializeArray();
                data.push({
                    name: 'security',
                    value: st_params._s
                });
                message.html('');
                loader.show();
                $.post(st_params.ajax_url, data, function (respon) {
                    if (typeof respon == 'object') {
                        message.html(respon.message);
                        setTimeout(function () {
                            message.html('');
                        }, 2000);
                    }
                    loader.hide();
                }, 'json');
            });
        },
        _mobile_menu: function (body) {
            var body = $('body');
            $('.toggle-menu').on('click',function (ev) {
                ev.preventDefault();
                toggleBody($('#st-main-menu'));
                $('#st-main-menu').toggleClass('open');
            });
            $('.back-menu').on('click',function (ev) {
                ev.preventDefault();
                toggleBody($('#st-main-menu'));
                $('#st-main-menu').toggleClass('open');
            });
        
            function toggleBody(el) {
                if (el.hasClass('open')) {
                    body.css({
                        'overflow': ''
                    });
                } else {
                    body.css({
                        'overflow': 'hidden'
                    });
                }
            }
        
            $('#st-main-menu .main-menu .menu-item-has-children .fa').on('click',function () {
                if (window.matchMedia("(max-width: 768px)").matches) {
                    $(this).toggleClass('fa-angle-down fa-angle-up');
                    var parent = $(this).closest('.menu-item-has-children');
                    $('>.menu-dropdown',parent).toggle();
        
                }
            });
            body.on('click',function (ev) {
                if ($(ev.target).is('#st-main-menu')) {
                    toggleBody($(ev.target));
                    $('#st-main-menu').toggleClass('open');
                }
            });
        },
    }
    Header.init();
    //Elementor
    let ST_Elementor = {
        $body: $('body'),
        isValidated: {},
        init: function () {
            let base = this;
            base._selectTabServiceList();
            base._addWishlist();
        },
        _addWishlist:function(){
            $(document).on('click', '.service-add-wishlist.login', function (event) {
                event.preventDefault();
                var t = $(this);
                t.addClass('loading');
                $.ajax({
                    url: st_params.ajax_url,
                    type: "POST",
                    data: {action: "st_add_wishlist", data_id: t.data('id'), data_type: t.data('type')},
                    dataType: "json",
                }).done(function (html) {
                    if (html.status == 'true') {
                        if (html.added == 'true') {
                            t.addClass('added');
                        } else {
                            t.removeClass('added');
                        }
                        t.attr('title', html.title);
                    }
                    t.removeClass('loading');
                })
            });
        },
        _selectTabServiceList: function(){
            $('.st-list-service').each(function () {
                var t = $(this);
                var dataTabShowVal = $('.st-list-dropdown .header', t).data('value');
                $('.multi-service-wrapper .tab-content.' + dataTabShowVal, t).show();
                
            });
            $('.st-list-dropdown').each(function () {
                var t = $(this);
                var parent = t.closest('.st-list-service');
                var currentTabList = t.find('.header').data('value');
                $('.list', t).find('li[data-value="' + currentTabList + '"]').hide();
                $('.header', t).on('click',function () {
                    $('.list', t).toggle();
                });
                $('.list li', t).on('click',function () {
                    var me = $(this);
                    console.log(t);
                    $('.list li', t).removeClass('active');
                    me.addClass('active');
                    var dataS = me.data('value');
                    var dataArg = me.data('arg');
                    var datastyleitem = me.data('styleitem');
                    var dataSName = me.text();
                    $('.header span', t).text(dataSName);
                    $('.header', t).attr('data-value', dataS);
                    me.parent().hide();
                    $.ajax({
                        url: st_params.ajax_url,
                        type: "GET",
                        data: {
                            'action': "st_list_of_service_"+dataS,
                            'dataArg': dataArg,
                            'datastyleitem': datastyleitem,
                        },
                        dataType: "json",
                        beforeSend: function () {
                            parent.find('.map-content-loading').css('z-index', 99);
                            parent.find('.map-content-loading').show();
                            
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                        },
                        success: function (res) {
                            parent.find('.map-content-loading').hide();
                        },
                        complete: function (xhr, status) {
                            
                            if (xhr.responseJSON) {
                                
                                parent.find('.multi-service-wrapper').html(xhr.responseJSON.html).hide().fadeIn(1500);
                                
                                $('.multi-service-wrapper .tab-content', parent).hide();
                                $('.multi-service-wrapper .tab-content.' + dataS, parent).show();
                                setTimeout(function () {
                                    $('.multi-service-wrapper .tab-content .has-matchHeight', parent).matchHeight({remove: true});
                                    $('.multi-service-wrapper .tab-content .has-matchHeight', parent).matchHeight();
                                }, 1000);
                                $('.list li', t).show();
                                $('.list', t).find('li[data-value="' + dataS + '"]').hide();
                            }
                            $('.st-service-slider').each(function () {
                                $(this).owlCarousel({
                                    loop: false,
                                    items: 4,
                                    margin: 20,
                                    responsiveClass: true,
                                    dots: false,
                                    responsive: {
                                        0: {
                                            items: 1,
                                            nav: false,
                                            margin: 15,
                                            dots: true,
                                        },
                                        576: {
                                            items: 2,
                                            nav: false,
                                            margin: 15,
                                            dots: true,
                                        },
                                        992: {
                                            items: 3,
                                            nav: true,
                                        },
                                        1200: {
                                            items: 4,
                                            nav: true,
                                        }
                                    }
                                });
                            });
                        }
                    });
                })
                $(document).on('mouseup',function (e) {
                    var container = t;
                    if (!container.is(e.target) && container.has(e.target).length === 0) {
                        container.find('.list').hide();
                    }
                });
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
    ST_Elementor.init();
})(jQuery);

function stKeyupsmartSearch(event){
    // Declare variables
    var input, filter, ul, li, a, i, txtValue;
    input = event.value.toUpperCase();
    filter = event.value.toUpperCase();
    parent = event.closest(".destination-search");
    ul = parent.getElementsByTagName('ul')[0];
    li = ul.getElementsByTagName('li');

    // Loop through all list items, and hide those who don't match the search query
    for (i = 0; i < li.length; i++) {
        //a = li[i].getElementsByTagName("a")[0];
        txtValue =  li[i].textContent ||  li[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}

//Social Login
jQuery(function ($) {
    var startApp = function () {
        var key = st_social_params.google_client_id;
        gapi.load('auth2', function () {
            auth2 = gapi.auth2.init({
                client_id: key,
                cookiepolicy: 'single_host_origin',
            });
            attachSignin(document.getElementById('st-google-signin2'));
            attachSignin(document.getElementById('st-google-signin3'));
        });
    };

    if (typeof window.gapi != 'undefined') {
        startApp();
    }

    function attachSignin(element) {
        auth2.attachClickHandler(element, {},
                function (googleUser) {
                    var profile = googleUser.getBasicProfile();
                    startLoginWithGoogle(profile);

                }, function (error) {
            console.log(JSON.stringify(error, undefined, 2));
        });
    }

    function startLoginWithGoogle(profile) {
        if (typeof window.gapi.auth2 == 'undefined')
            return;
        sendLoginData({
            'channel': 'google',
            'userid': profile.getId(),
            'username': profile.getName(),
            'useremail': profile.getEmail(),
        });
    }

    function startLoginWithFacebook(btn) {
        btn.addClass('loading');

        FB.getLoginStatus(function (response) {
            if (response.status === 'connected') {
                sendLoginData({
                    'channel': 'facebook',
                    'access_token': response.authResponse.accessToken
                });

            } else {
                FB.login(function (response) {
                    if (response.authResponse) {
                        sendLoginData({
                            'channel': 'facebook',
                            'access_token': response.authResponse.accessToken
                        });

                    } else {
                        alert('User cancelled login or did not fully authorize.');
                    }
                }, {
                    scope: 'email',
                    return_scopes: true
                });
            }
        });
    }

    function sendLoginData(data) {
        data._s = st_params._s;
        data.action = 'traveler.socialLogin';
        var parent_login = $(".login-regiter-popup");
        $.ajax({
            data: data,
            type: 'post',
            dataType: 'json',
            url: st_params.ajax_url,
            beforeSend: function () {
                parent_login.find('.map-loading').html('<div class="st-loader"></div>');
                parent_login.find('.map-loading').css('z-index', 99);
                parent_login.find('.map-loading').show();
                
            },
            success: function (rs) {
                handleSocialLoginResult(rs);
            },
            error: function (e) {

                alert('Can not login. Please try again later');
            }
        })
    }

    function handleSocialLoginResult(rs) {
        if (rs.reload)
            window.location.reload();
        if (rs.message)
            alert(rs.message);
    }

    $('.st_login_social_link').on('click', function () {
        var channel = $(this).data('channel');

        switch (channel) {
            case "facebook":
                startLoginWithFacebook($(this));
                break;
        }
    })

    /* Fix social login popup */
    function popupwindow(url, title, w, h) {
        var left = (screen.width / 2) - (w / 2);
        var top = (screen.height / 2) - (h / 2);
        return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    }

    $('.st_login_social_link').on('click', function () {
        var href = $(this).attr('href');
        if ($(this).hasClass('btn_login_tw_link'))
            popupwindow(href, '', 600, 450);
        return false;
    });
    /* End fix social login popup */

    //Filter mobile
    $('.toolbar-action-mobile .btn-from-to').on('click', function (e) {
        e.preventDefault();
        window.scrollTo({
            top: '46',
        });
        $('.sidebar-filter').fadeIn();
        $('.top-filter').fadeIn();
        $('.sidebar-filter .sidebar-search-form').show();
        $('.sidebar-filter .sidebar-item-wrapper').hide();
        $('.sidebar-filter .form-date-search').hide();
        $('html, body').css({overflow: 'hidden'});
    });
    $('.toolbar-action-mobile .btn-filter').on('click', function (e) {
        e.preventDefault();
        window.scrollTo({
            top: '46',
        });
        $('.sidebar-filter').fadeIn();
        $('.top-filter').fadeIn();
        $('.sidebar-filter .sidebar-item-wrapper').fadeIn();
        $('.sidebar-filter .sidebar-search-form').hide();
        $('html, body').css({overflow: 'hidden'});
    });
    $('.toolbar  .btn-sort').on('click', function (e) {
        e.preventDefault();
        $('.sort-menu-mobile').fadeIn();
    });
    $('.toolbar  .btn-map').on('click', function (e) {
        e.preventDefault();
        window.scrollTo({
            top: '46',
        });
        $('.page-half-map .col-right').show();
        $('.full-map .full-map-item').show();
        $('html, body').css({overflow: 'hidden'});
    });
    $('.sidebar-filter .close-filter').on('click', function () {
        $(this).closest('.sidebar-filter').fadeOut(function () {
            $('html, body').css({overflow: 'auto'});
        });
    });
    $('.top-filter .close-filter').on('click', function () {
        $(this).closest('.top-filter').fadeOut(function () {
            $('html, body').css({overflow: 'auto'});
        });
    });
    $('.sort-menu-mobile .close-filter').on('click', function () {
        $(this).closest('.sort-menu-mobile').fadeOut();
    });
    $('.page-half-map .close-half-map').on('click', function () {
        $(this).closest('.col-right').hide();
        $('html, body').css({overflow: 'auto'});
        if ($('#btn-show-map-mobile').length) {
            $('#btn-show-map-mobile').prop('checked', false);
        }
    });
    $('.full-map .close-map').on('click', function () {
        $(this).closest('.full-map').hide();
        $('html, body').css({overflow: 'auto'});
    });
    $(window).on('resize',function () {
        if (window.matchMedia('(min-width: 768px)').matches) {
            if ($('.full-map-item').length) {
                if (!$('.full-map-item').is(':visible')) {
                    $('.full-map-item').attr('style', '');
                }
            }
            if ($('.st-hotel-result .sidebar-filter').length) {
                if (!$('.st-hotel-result .sidebar-filter').is(':visible')) {
                    $('.st-hotel-result .sidebar-filter').attr('style', '');
                }
            }
            if ($('.st-hotel-result .top-filter').length) {
                if (!$('.st-hotel-result .top-filter').is(':visible')) {
                    $('.st-hotel-result .top-filter').attr('style', '');
                }
            }
        }
        if (window.matchMedia('(min-width: 992px)').matches) {
            if ($('.page-half-map .col-right').length) {
                if (!$('.page-half-map .col-right').is(':visible') && $('#btn-show-map').is(':checked')) {
                    $('.page-half-map .col-right').attr('style', '');
                }
            }
        }
        if (window.matchMedia('(max-width: 991px)').matches) {
            if ($('.page-half-map .col-right').length) {
                if ($('.page-half-map .col-right').is(':visible')) {
                    $('.page-half-map .col-right').attr('style', '');
                }
            }
            if ($('.page-half-map .col-left').length) {
                if ($('.page-half-map .col-left').is(':visible')) {
                    $.fn.getNiceScroll && $('.page-half-map .col-left').getNiceScroll().remove();
                }
            }
        }
    });
});

var mapStyles = {
    'silver': [
        {
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#f5f5f5"
                }
            ]
        },
        {
            "elementType": "labels.icon",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#616161"
                }
            ]
        },
        {
            "elementType": "labels.text.stroke",
            "stylers": [
                {
                    "color": "#f5f5f5"
                }
            ]
        },
        {
            "featureType": "administrative.land_parcel",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#bdbdbd"
                }
            ]
        },
        {
            "featureType": "poi",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#eeeeee"
                }
            ]
        },
        {
            "featureType": "poi",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#757575"
                }
            ]
        },
        {
            "featureType": "poi.park",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#e5e5e5"
                }
            ]
        },
        {
            "featureType": "poi.park",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#9e9e9e"
                }
            ]
        },
        {
            "featureType": "road",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#ffffff"
                }
            ]
        },
        {
            "featureType": "road.arterial",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#757575"
                }
            ]
        },
        {
            "featureType": "road.highway",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#dadada"
                }
            ]
        },
        {
            "featureType": "road.highway",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#616161"
                }
            ]
        },
        {
            "featureType": "road.local",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#9e9e9e"
                }
            ]
        },
        {
            "featureType": "transit.line",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#e5e5e5"
                }
            ]
        },
        {
            "featureType": "transit.station",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#eeeeee"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#c9c9c9"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#9e9e9e"
                }
            ]
        }
    ],
    'retro': [
        {
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#ebe3cd"
                }
            ]
        },
        {
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#523735"
                }
            ]
        },
        {
            "elementType": "labels.text.stroke",
            "stylers": [
                {
                    "color": "#f5f1e6"
                }
            ]
        },
        {
            "featureType": "administrative",
            "elementType": "geometry.stroke",
            "stylers": [
                {
                    "color": "#c9b2a6"
                }
            ]
        },
        {
            "featureType": "administrative.land_parcel",
            "elementType": "geometry.stroke",
            "stylers": [
                {
                    "color": "#dcd2be"
                }
            ]
        },
        {
            "featureType": "administrative.land_parcel",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#ae9e90"
                }
            ]
        },
        {
            "featureType": "landscape.natural",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#dfd2ae"
                }
            ]
        },
        {
            "featureType": "poi",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#dfd2ae"
                }
            ]
        },
        {
            "featureType": "poi",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#93817c"
                }
            ]
        },
        {
            "featureType": "poi.park",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "color": "#a5b076"
                }
            ]
        },
        {
            "featureType": "poi.park",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#447530"
                }
            ]
        },
        {
            "featureType": "road",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#f5f1e6"
                }
            ]
        },
        {
            "featureType": "road.arterial",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#fdfcf8"
                }
            ]
        },
        {
            "featureType": "road.highway",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#f8c967"
                }
            ]
        },
        {
            "featureType": "road.highway",
            "elementType": "geometry.stroke",
            "stylers": [
                {
                    "color": "#e9bc62"
                }
            ]
        },
        {
            "featureType": "road.highway.controlled_access",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#e98d58"
                }
            ]
        },
        {
            "featureType": "road.highway.controlled_access",
            "elementType": "geometry.stroke",
            "stylers": [
                {
                    "color": "#db8555"
                }
            ]
        },
        {
            "featureType": "road.local",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#806b63"
                }
            ]
        },
        {
            "featureType": "transit.line",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#dfd2ae"
                }
            ]
        },
        {
            "featureType": "transit.line",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#8f7d77"
                }
            ]
        },
        {
            "featureType": "transit.line",
            "elementType": "labels.text.stroke",
            "stylers": [
                {
                    "color": "#ebe3cd"
                }
            ]
        },
        {
            "featureType": "transit.station",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#dfd2ae"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "color": "#b9d3c2"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#92998d"
                }
            ]
        }
    ],
    'dark': [
        {
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#212121"
                }
            ]
        },
        {
            "elementType": "labels.icon",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#757575"
                }
            ]
        },
        {
            "elementType": "labels.text.stroke",
            "stylers": [
                {
                    "color": "#212121"
                }
            ]
        },
        {
            "featureType": "administrative",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#757575"
                }
            ]
        },
        {
            "featureType": "administrative.country",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#9e9e9e"
                }
            ]
        },
        {
            "featureType": "administrative.land_parcel",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "administrative.locality",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#bdbdbd"
                }
            ]
        },
        {
            "featureType": "poi",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#757575"
                }
            ]
        },
        {
            "featureType": "poi.park",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#181818"
                }
            ]
        },
        {
            "featureType": "poi.park",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#616161"
                }
            ]
        },
        {
            "featureType": "poi.park",
            "elementType": "labels.text.stroke",
            "stylers": [
                {
                    "color": "#1b1b1b"
                }
            ]
        },
        {
            "featureType": "road",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "color": "#2c2c2c"
                }
            ]
        },
        {
            "featureType": "road",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#8a8a8a"
                }
            ]
        },
        {
            "featureType": "road.arterial",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#373737"
                }
            ]
        },
        {
            "featureType": "road.highway",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#3c3c3c"
                }
            ]
        },
        {
            "featureType": "road.highway.controlled_access",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#4e4e4e"
                }
            ]
        },
        {
            "featureType": "road.local",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#616161"
                }
            ]
        },
        {
            "featureType": "transit",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#757575"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#000000"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#3d3d3d"
                }
            ]
        }
    ],
    'night': [
        {
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#242f3e"
                }
            ]
        },
        {
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#746855"
                }
            ]
        },
        {
            "elementType": "labels.text.stroke",
            "stylers": [
                {
                    "color": "#242f3e"
                }
            ]
        },
        {
            "featureType": "administrative.locality",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#d59563"
                }
            ]
        },
        {
            "featureType": "poi",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#d59563"
                }
            ]
        },
        {
            "featureType": "poi.park",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#263c3f"
                }
            ]
        },
        {
            "featureType": "poi.park",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#6b9a76"
                }
            ]
        },
        {
            "featureType": "road",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#38414e"
                }
            ]
        },
        {
            "featureType": "road",
            "elementType": "geometry.stroke",
            "stylers": [
                {
                    "color": "#212a37"
                }
            ]
        },
        {
            "featureType": "road",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#9ca5b3"
                }
            ]
        },
        {
            "featureType": "road.highway",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#746855"
                }
            ]
        },
        {
            "featureType": "road.highway",
            "elementType": "geometry.stroke",
            "stylers": [
                {
                    "color": "#1f2835"
                }
            ]
        },
        {
            "featureType": "road.highway",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#f3d19c"
                }
            ]
        },
        {
            "featureType": "transit",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#2f3948"
                }
            ]
        },
        {
            "featureType": "transit.station",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#d59563"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#17263c"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#515c6d"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "labels.text.stroke",
            "stylers": [
                {
                    "color": "#17263c"
                }
            ]
        }
    ],
    'aubergine': [
        {
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#1d2c4d"
                }
            ]
        },
        {
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#8ec3b9"
                }
            ]
        },
        {
            "elementType": "labels.text.stroke",
            "stylers": [
                {
                    "color": "#1a3646"
                }
            ]
        },
        {
            "featureType": "administrative.country",
            "elementType": "geometry.stroke",
            "stylers": [
                {
                    "color": "#4b6878"
                }
            ]
        },
        {
            "featureType": "administrative.land_parcel",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#64779e"
                }
            ]
        },
        {
            "featureType": "administrative.province",
            "elementType": "geometry.stroke",
            "stylers": [
                {
                    "color": "#4b6878"
                }
            ]
        },
        {
            "featureType": "landscape.man_made",
            "elementType": "geometry.stroke",
            "stylers": [
                {
                    "color": "#334e87"
                }
            ]
        },
        {
            "featureType": "landscape.natural",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#023e58"
                }
            ]
        },
        {
            "featureType": "poi",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#283d6a"
                }
            ]
        },
        {
            "featureType": "poi",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#6f9ba5"
                }
            ]
        },
        {
            "featureType": "poi",
            "elementType": "labels.text.stroke",
            "stylers": [
                {
                    "color": "#1d2c4d"
                }
            ]
        },
        {
            "featureType": "poi.park",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "color": "#023e58"
                }
            ]
        },
        {
            "featureType": "poi.park",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#3C7680"
                }
            ]
        },
        {
            "featureType": "road",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#304a7d"
                }
            ]
        },
        {
            "featureType": "road",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#98a5be"
                }
            ]
        },
        {
            "featureType": "road",
            "elementType": "labels.text.stroke",
            "stylers": [
                {
                    "color": "#1d2c4d"
                }
            ]
        },
        {
            "featureType": "road.highway",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#2c6675"
                }
            ]
        },
        {
            "featureType": "road.highway",
            "elementType": "geometry.stroke",
            "stylers": [
                {
                    "color": "#255763"
                }
            ]
        },
        {
            "featureType": "road.highway",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#b0d5ce"
                }
            ]
        },
        {
            "featureType": "road.highway",
            "elementType": "labels.text.stroke",
            "stylers": [
                {
                    "color": "#023e58"
                }
            ]
        },
        {
            "featureType": "transit",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#98a5be"
                }
            ]
        },
        {
            "featureType": "transit",
            "elementType": "labels.text.stroke",
            "stylers": [
                {
                    "color": "#1d2c4d"
                }
            ]
        },
        {
            "featureType": "transit.line",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "color": "#283d6a"
                }
            ]
        },
        {
            "featureType": "transit.station",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#3a4762"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#0e1626"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#4e6d70"
                }
            ]
        }
    ]
};
function customControlGoogleMap(mapEl, map) {
    //==== Top Right area
    var topRightArea = document.createElement('div');
    topRightArea.className = 'google-control-top-right-area';
    var controlFullScreen = document.createElement('div');
    controlFullScreen.className = 'google-control-fullscreen google-custom-control';
    controlFullScreen.innerHTML = '<img src="' + st_list_map_params.icon_full_screen + '" alt="Full Screen"/>';
    topRightArea.appendChild(controlFullScreen);
    var controlCloseFullScreen = document.createElement('div');
    controlCloseFullScreen.className = 'google-control-closefullscreen google-custom-control hide';
    controlCloseFullScreen.innerHTML = '<img src="' + st_list_map_params.icon_close + '" alt="Full Screen"/>';
    topRightArea.appendChild(controlCloseFullScreen);
    var controlMyLocation = document.createElement('div');
    controlMyLocation.className = 'google-control-mylocation google-custom-control';
    controlMyLocation.innerHTML = '<img src="' + st_list_map_params.icon_my_location + '" alt="Full Screen"/>';
    topRightArea.appendChild(controlMyLocation);
    var controlStyles = document.createElement('div');
    controlStyles.className = 'google-control-styles google-custom-control';
    controlStyles.innerHTML = '<img src="' + st_list_map_params.icon_my_style + '" alt="Full Screen"/><div class="google-control-dropdown"><div class="item">Silver</div><div class="item">Retro</div><div class="item">Dark</div><div class="item">Night</div><div class="item">Aubergine</div></div>';
    topRightArea.appendChild(controlStyles);
    //==== Bottom Right area
    var bottomRightArea = document.createElement('div');
    bottomRightArea.className = 'google-control-bottom-right-area';
    var controlZoomIn = document.createElement('div');
    controlZoomIn.className = 'google-control-zoomin google-custom-control';
    controlZoomIn.innerHTML = '<img src="' + st_list_map_params.icon_zoom_in + '" alt="Full Screen"/>';
    bottomRightArea.appendChild(controlZoomIn);
    var controlZoomOut = document.createElement('div');
    controlZoomOut.className = 'google-control-zoomout google-custom-control';
    controlZoomOut.innerHTML = '<img src="' + st_list_map_params.icon_zoom_out + '" alt="Full Screen"/>';
    bottomRightArea.appendChild(controlZoomOut);
    map.controls[google.maps.ControlPosition.RIGHT_TOP].push(topRightArea);
    map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(bottomRightArea);
    controlFullScreen.addEventListener('click', function () {
        controlFullScreen.classList.add('hide');
        controlCloseFullScreen.classList.remove('hide');
        var element = map.getDiv();
        if (element.requestFullscreen) {
            element.requestFullscreen();
        }
        if (element.webkitRequestFullScreen) {
            element.webkitRequestFullScreen();
        }
        if (element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
        }
    });
    controlCloseFullScreen.addEventListener('click', function () {
        controlFullScreen.classList.remove('hide');
        controlCloseFullScreen.classList.add('hide');
        if (document.exitFullscreen)
            document.exitFullscreen();
        else if (document.webkitExitFullscreen)
            document.webkitExitFullscreen();
        else if (document.mozCancelFullScreen)
            document.mozCancelFullScreen();
        else if (document.msExitFullscreen)
            document.msExitFullscreen();
    });
    controlMyLocation.addEventListener('click', function () {
        if (navigator.geolocation)
            navigator.geolocation.getCurrentPosition(function (pos) {
                var latlng = new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude);
                map.setCenter(latlng);
                new google.maps.Marker({
                    position: latlng,
                    icon: mapEl.data().icon,
                    map: map
                });
            }, function (error) {
                console.log('Can not get your Location');
            });
    });
    controlZoomIn.addEventListener('click', function () {
        var current = map.getZoom();
        map.setZoom(current + 1);
    });
    controlZoomOut.addEventListener('click', function () {
        var current = map.getZoom();
        map.setZoom(current - 1);
    });
    controlStyles.addEventListener('click', function () {
        controlStyles.querySelector('.google-control-dropdown').classList.toggle('show');
    });
    var dropdownStyles = controlStyles.querySelector('.google-control-dropdown');
    var items = dropdownStyles.querySelectorAll('.item');
    for (var i = 0; i < items.length; i++) {
        items[i].addEventListener('click', function () {
            var style = this.textContent.toLowerCase();
            if (mapStyles[style]) {
                map.setOptions({styles: mapStyles[style]});
            }
        });
    }
}

jQuery(function($) {
    document.querySelectorAll(".select-number-passenger  .st-number  .plus").forEach((input) => input.addEventListener("click", calculate_add));
    document.querySelectorAll(".select-number-passenger  .st-number  .minus").forEach((input) => input.addEventListener("click", calculate_minus));
    function calculate_add(){
        var num_item = $(this).closest('.select-number-passenger');
        var num = num_item.find('.st-input-number').val();
        var max_val = num_item.find('.st-input-number').data('max');
        var value_num = parseInt(num)+1;
        num_item.find('.st-input-number').val(value_num);
        num_item.find('strong.num').text(value_num);

    }
    function calculate_minus(){
        var num_item = $(this).closest('.select-number-passenger');
        var num = num_item.find('.st-input-number').val();
        var min_val = num_item.find('.st-input-number').data('min');
        if(parseInt(num)>min_val){
            var value_num = parseInt(num)-1;
            num_item.find('.st-input-number').val(value_num);
            num_item.find('strong.num').text(value_num);
        }

    }
})