;(function ($) {
    "use strict";
    //Search form
    let SearchForm = {
        $body: $('body'),
        isValidated: {},
        init: function () {
            let base = this;
            var advFacilities = [];
            base._dropdownselect();
            base._destinationSelect();
            base._dropdownselectDate();
            base._addNextPreInput();
            base._rangeSliderPrice();
            base._chooseTaxonomy(advFacilities);
            base._chooseTaxonomyChecked(advFacilities);
        },
        _chooseTaxonomyChecked: function(advFacilities){
            $('.advance-item.facilities input[type="checkbox"]').each(function () {
                var t = $(this);
                if (t.is(':checked')) {
                    advFacilities.push(t.val());
                }
            });
        },
        _chooseTaxonomy: function (advFacilities){
            $('.advance-item.facilities input[type="checkbox"]').on('change',function () {
                var t = $(this);
                if (t.is(':checked')) {
                    advFacilities.push(t.val());
                } else {
                    var index = advFacilities.indexOf(t.val());
                    if (index > -1) {
                        advFacilities.splice(index, 1);
                    }
                }
                t.closest('.facilities').find('.data_taxonomy').val(advFacilities.join(','));
            });
        },
        _destinationSelect: function (){
            /*Destination selection*/
            $('.field-detination').each(function () {
                var parent = $(this).closest('.border-right');
                if( parent.length < 1){
                    var parent = $(this).closest('.destination-search');
                }
                
                var dropdown_menu = $('.dropdown-menu',parent);
                console.log(parent);
                $('li', dropdown_menu).on('click', function () {
                    
                    var target = $(this).closest('ul.dropdown-menu').attr('aria-labelledby');
                    var focus = parent.find('#' + target);
                    $('.destination', focus).text($(this).find('span').text());
                    $('input[name="location_name"]', focus).val($(this).find('span').text());
                    $('input.location_name', focus).val($(this).find('span').text());
                    $('input[name="location_id"]', focus).val($(this).data('value'));
                    $('input.location_id', focus).val($(this).data('value'));
                    
                });
            });
        },
        _rangeSliderPrice: function(){
             function format_money($money) {
    
                $money = st_number_format($money, st_params.booking_currency_precision, st_params.decimal_separator, st_params.thousand_separator);
                var $symbol = st_params.currency_symbol;
                var $money_string = '';
        
                switch (st_params.currency_position) {
                    case "right":
                        $money_string = $money + $symbol;
                        break;
                    case "left_space":
                        $money_string = $symbol + " " + $money;
                        break;
        
                    case "right_space":
                        $money_string = $money + " " + $symbol;
                        break;
                    case "left":
                    default:
                        $money_string = $symbol + $money;
                        break;
                }
        
                return $money_string;
            }
            function st_number_format(number, decimals, dec_point, thousands_sep) {
                number = (number + '')
                        .replace(/[^0-9+\-Ee.]/g, '');
                var n = !isFinite(+number) ? 0 : +number,
                        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                        s = '',
                        toFixedFix = function (n, prec) {
                            var k = Math.pow(10, prec);
                            return '' + (Math.round(n * k) / k)
                                    .toFixed(prec);
                        };
                // Fix for IE parseFloat(0.55).toFixed(0) = 0;
                s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
                        .split('.');
                if (s[0].length > 3) {
                    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                }
                if ((s[1] || '')
                        .length < prec) {
                    s[1] = s[1] || '';
                    s[1] += new Array(prec - s[1].length + 1)
                            .join('0');
                }
                return s.join(dec);
            }
            $(".price_range").each(function () {
                var t = $(this);
                var min = $(this).data('min');
                var max = $(this).data('max');
                var step = $(this).data('step');
                var value = $(this).val();
                var from = value.split(';');
                var prefix_symbol = $(this).data('symbol');
                var to = from[1];
                from = from[0];
                $(this).ionRangeSlider({
                    min: min,
                    max: max,
                    type: 'double',
                    prefix: prefix_symbol,
                    prettify: false,
                    step: step,
                    onFinish: function (data) {
                        t.trigger('st_ranger_price_change');
                        set_price_range_val(data, $('input[name="price_range"]'));
                        format_price_price_ranger(data);
                    },
                    from: from,
                    to: to,
                    force_edges: true,
                });
            });
            var rangeContainer = $('.sidebar-item.range-slider');
            function format_price_price_ranger(data) {
        
        
                var min = rangeContainer.find('.price_range').data('min');
                var max = rangeContainer.find('.price_range').data('max');
                var convert_price_min = format_money(data.from);
                var convert_price_max = format_money(data.to);
                rangeContainer.find('.irs-from').text(convert_price_min);
                rangeContainer.find('.irs-to').text(convert_price_max);
        
            }
        
            function set_price_range_val(data, element) {
                var exchange = 1;
                var from = Math.round(parseInt(data.from) / exchange);
                var to = Math.round(parseInt(data.to) / exchange);
                var text = from + ";" + to;
                element.val(text);
            }
        },
        _addNextPreInput: function (){
            $('.st-number-wrapper').each(function () {
                var timeOut = 0;
                var t = $(this);
                var input = t.find('.st-input-number');
                input.before('<span class="prev"><svg width="18px" height="2px" viewBox="0 0 18 2" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">\n' +
                        '    <!-- Generator: Sketch 49 (51002) - http://www.bohemiancoding.com/sketch -->\n' +
                        '    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">\n' +
                        '        <g id="Tour_Detail_1" transform="translate(-1180.000000, -1085.000000)" stroke="#5E6D77" stroke-width="1.5">\n' +
                        '            <g id="check-avai" transform="translate(1034.000000, 867.000000)">\n' +
                        '                <g id="adults" transform="translate(0.000000, 184.000000)">\n' +
                        '                    <g id="ico_subtract" transform="translate(147.000000, 35.000000)">\n' +
                        '                        <path d="M0.5,0.038 L15.5,0.038" id="Shape"></path>\n' +
                        '                    </g>\n' +
                        '                </g>\n' +
                        '            </g>\n' +
                        '        </g>\n' +
                        '    </g>\n' +
                        '</svg></span>');
                input.after('<span class="next"><svg width="18px" height="18px" viewBox="0 0 18 18" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">\n' +
                        '    <!-- Generator: Sketch 49 (51002) - http://www.bohemiancoding.com/sketch -->\n' +
                        '    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">\n' +
                        '        <g id="Tour_Detail_1" transform="translate(-1258.000000, -1077.000000)" stroke="#5E6D77" stroke-width="1.5">\n' +
                        '            <g id="check-avai" transform="translate(1034.000000, 867.000000)">\n' +
                        '                <g id="adults" transform="translate(0.000000, 184.000000)">\n' +
                        '                    <g id="ico_add" transform="translate(225.000000, 27.000000)">\n' +
                        '                        <path d="M0.5,8 L15.5,8" id="Shape"></path>\n' +
                        '                        <path d="M8,0.5 L8,15.5" id="Shape"></path>\n' +
                        '                    </g>\n' +
                        '                </g>\n' +
                        '            </g>\n' +
                        '        </g>\n' +
                        '    </g>\n' +
                        '</svg></span>');
                var min = input.data('min');
                var max = input.data('max');
                t.find('span').on("click", function () {
                    var $button = $(this);
                    numberButtonFunc($button);
                });
                t.find('span').on("touchstart", function (e) {
                    $(this).trigger('click');
                    e.preventDefault();
                    var $button = $(this);
                    timeOut = setInterval(function () {
                        // numberButtonFunc($button);
                    }, 150);
                }).on('mouseup mouseleave touchend', function () {
                    clearInterval(timeOut);
                });
                function numberButtonFunc($button) {
                    var oldValue = $button.parent().find("input").val();
                    var container = $button.closest('.form-guest-search');
                    var total = 0;
                    $('input[type="text"]', container).each(function () {
                        total += parseInt($(this).val());
                    });
                    var newVal = oldValue;
                    if ($button.hasClass('next')) {
                        if (total < max) {
                            if (oldValue < max) {
                                newVal = parseFloat(oldValue) + 1;
                            } else {
                                newVal = max;
                            }
                        }
                    } else {
                        if (oldValue > min) {
                            newVal = parseFloat(oldValue) - 1;
                        } else {
                            newVal = min;
                        }
                    }
                    $button.parent().find("input").val(newVal).trigger('change');
                    $('input[name="' + $button.parent().find("input").attr('name') + '"]', '.search-form').trigger('change');
                    $('input[name="' + $button.parent().find("input").attr('name') + '"]', '.form-check-availability-hotel').trigger('change');
                    $('input[name="' + $button.parent().find("input").attr('name') + '"]', '.single-room-form').trigger('change');
                    if (window.matchMedia('(max-width: 767px)').matches) {
                        $('#dropdown-1 label', $button.closest('.field-guest')).hide();
                        $('#dropdown-1 .render', $button.closest('.field-guest')).show();
                    }
                }
            });
        },
        _dropdownselectDate: function(){
            if(!$( 'body' ).hasClass( "single-hotel_room" ) && !$( 'body' ).hasClass( "single-st_hotel" ) && !$( 'body' ).hasClass( "single-st_activity" ) && !$( 'body' ).hasClass( "single-st_tours" )
            && !$( 'body' ).hasClass( "single-st_cars" ) && !$( 'body' ).hasClass( "single-st_rental" )
            ){
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
            }
            
        },
        _dropdownselect: function () {
            $('.form-extra-field').each(function () {
                var parent = $(this).parent('.form-group');
                parent.on('click',function (e) {
                    $(this).find('.arrow').toggleClass('fa-angle-down fa-angle-up');
                });
                
                $('.arrow', parent).on('click',function (e) {
                    var drop_down = $(this).closest('.dropdown');
                    var dropdown_menu = $('[aria-labelledby="' + drop_down.find('.dropdown-toggle').attr('id') + '"]', parent);
                    $('.form-extra-field').find('.dropdown-menu').not(dropdown_menu).slideUp(50);
                    dropdown_menu.slideToggle(50);
                    $(this).toggleClass('fa-angle-down fa-angle-up');
                });
                $('input[name="adult_number"]', parent).on('change',function () {
                    
                    var adults = parseInt($(this).val());
                    var html = adults;
                    if (typeof adults == 'number') {
                        if (adults < 2) {
                            html = adults + ' ' + $('.render .adult', parent).data('text');
                        } else {
                            html = adults + ' ' + $('.render .adult', parent).data('text-multi');
                        }
                    }
                    $('.render .adult', parent).html(html);
                });
                $('input[name="adult_number"]', parent).trigger('change');
                $('input[name="child_number"]', parent).on('change',function () {
                    var children = parseInt($(this).val());
                    var html = children;
                    if (typeof children == 'number') {
                        if (children < 2) {
                            html = children + ' ' + $('.render .children', parent).data('text');
                        } else {
                            html = children + ' ' + $('.render .children', parent).data('text-multi');
                        }
                    }
                    $('.render .children', parent).html(html);
                });
                $('input[name="child_number"]', parent).trigger('change');
            });
        }
    }
    SearchForm.init();
})(jQuery);

