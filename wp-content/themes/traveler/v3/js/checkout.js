;(function ($) {
    "use strict";
    let Header = {
        $body: $('body'),
        isValidated: {},
        init: function () {
            let base = this;
            base._choosepaymentCheckout(base.$body);
            base._toggleDetailInforBooking();
        },
        _toggleDetailInforBooking(){
            $('.info-section .detail button').on('click', function () {
                var parent = $(this).closest('.detail');
                $('.detail-list', parent).slideToggle();
            });
        },
        _choosepaymentCheckout: function(){
            if ($('.payment-form .payment-item').length) {
                $('.payment-form .payment-item').eq(0).find('.st-icheck-item input[type="radio"]').prop('checked', true);
                $('.payment-form .payment-item').eq(0).find('.dropdown-menu').slideDown();
            }
            $('.payment-form .payment-item').each(function (l, i) {
                var parent = $(this);
                $('.st-icheck-item input[type="radio"]', parent).on('change',function () {
                    $('.payment-form .payment-item .dropdown-menu').slideUp();
                    if ($(this).is(':checked')) {
                        if ($('.dropdown-menu', parent).length) {
                            $('.dropdown-menu', parent).slideDown();
                        }
                    }
                });
            });
        }
    }
    Header.init();
})(jQuery);