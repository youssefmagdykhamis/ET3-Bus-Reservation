;(function ($) {
    'use strict';
    //Slider item service
    function st_service_list_slider_element($wrapper) {
        let wrapper_list = $('.st-list-service', $wrapper);
        let imageCarousel = $('.st-list-service .swiper-container', $wrapper);
        let pagination = wrapper_list.attr('data-pagination');
        let navigation = wrapper_list.attr('data-navigation');
        let auto_play = wrapper_list.attr('data-auto-play');
        let delay = wrapper_list.attr('data-delay');
        let loop = wrapper_list.attr('data-loop');
        let option = {
            speed: 400,
            spaceBetween: 20,
            preloadImages: true,
            autoHeight: true,
            effect: wrapper_list.attr('data-effect'),
            breakpoints: {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: wrapper_list.attr('data-slides-per-view'),
                    spaceBetween: 20,
                },
            },
        }
        if(pagination == 'on'){
            option.pagination = {
                el: ".swiper-pagination",
                clickable: true,
            };
        }
        if(navigation == 'on'){
            option.navigation =  {
                nextEl: ".st-button-next",
                prevEl: ".st-button-prev",
            };
        }
        if(auto_play == 'on'){
            if(delay.length > 0){
                option.autoplay = {
                    delay: delay,
                };
            } else {
                option.autoplay = {
                    delay: 2000,
                };
            }
           
        }
        if(loop == 'true'){
            option.loop = true;
        }

        if ( 'undefined' === typeof Swiper ) {
            const asyncSwiper = elementorFrontend.utils.swiper;
           
            new asyncSwiper( imageCarousel, option ).then( ( newSwiperInstance ) => {
                $('.swiper-wrapper .swiper-slide', $wrapper).matchHeight({remove: true});
                var mySwiper = newSwiperInstance;
                $('.swiper-wrapper .swiper-slide', $wrapper).matchHeight();
                
            } );
        } else {
            $('.swiper-wrapper .swiper-slide', $wrapper).matchHeight({remove: true});
            const mySwiper = new Swiper( imageCarousel, option );
            $('.swiper-wrapper .swiper-slide', $wrapper).matchHeight({remove: true});
            $('.swiper-wrapper .swiper-slide', $wrapper).matchHeight();
        }

        
        
        
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/st_list_service.default', function ($wrapper) {
            st_service_list_slider_element($wrapper);
            $('.service-list-wrapper .swiper-wrapper .swiper-slide', $wrapper).matchHeight({remove: true});
            $('.service-list-wrapper .swiper-wrapper .swiper-slide', $wrapper).matchHeight();
        });
    });
    //End Slider item service

    //Slider Image
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/st_sliders.default', function ($wrapper) {
            st_sliders_element($wrapper);
        });
    });
    function st_sliders_element($wrapper){
        let wrapper_list = $('.st-sliders', $wrapper);
        let imageCarousel = $('.st-sliders .swiper-container', $wrapper);
        let pagination = wrapper_list.attr('data-pagination');
        let navigation = wrapper_list.attr('data-navigation');
        let auto_play = wrapper_list.attr('data-auto-play');
        let delay = wrapper_list.attr('data-delay');
        let loop = wrapper_list.attr('data-loop');
        let center_slider = wrapper_list.attr('data-center-slider');
        let option = {
            speed: 400,
            spaceBetween: 20,
            preloadImages: true,
            autoHeight: true,
            centeredSlides: true,
            centeredSlidesBounds: true,
            effect: wrapper_list.attr('data-effect')
        }

        if(center_slider == 'on'){
            option.breakpoints =  {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 'auto',
                    spaceBetween: 20,
                },
            };
        } else {
            option.breakpoints =  {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: wrapper_list.attr('data-slides-per-view'),
                    spaceBetween: 20,
                },
            };
        }
        if(pagination == 'on'){
            option.pagination = {
                el: ".swiper-pagination",
                clickable: true,
            };
        }
        if(navigation == 'on'){
            option.navigation =  {
                nextEl: ".st-button-next",
                prevEl: ".st-button-prev",
            };
        }
        if(auto_play == 'on'){
            if(delay.length > 0){
                option.autoplay = {
                    delay: delay,
                };
            } else {
                option.autoplay = {
                    delay: 2000,
                };
            }
           
        }
        if(loop == 'true'){
            option.loop = true;
        }
        const Swiper = elementorFrontend.utils.swiper;
        const  swipers = new Swiper(imageCarousel,option);
    }
    //End Slider Image

    //Slider Testimonial
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/st_testimonial.default', function ($wrapper) {
            st_testimonial_element($wrapper);
        });
    });

    function st_testimonial_element($wrapper){
        let wrapper_list = $('.st-testimonial', $wrapper);
        let imageCarousel = $('.st-testimonial .swiper-container', $wrapper);
        let pagination = wrapper_list.attr('data-pagination');
        let navigation = wrapper_list.attr('data-navigation');
        let auto_play = wrapper_list.attr('data-auto-play');
        let delay = wrapper_list.attr('data-delay');
        let loop = wrapper_list.attr('data-loop');
        let option = {
            speed: 400,
            spaceBetween: 20,
            preloadImages: true,
            autoHeight: true,
            effect: wrapper_list.attr('data-effect'),
            breakpoints: {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: wrapper_list.attr('data-slides-per-view'),
                    spaceBetween: 20,
                },
            },
        }
        if(pagination == 'on'){
            option.pagination = {
                el: ".swiper-pagination",
                clickable: true,
            };
        }
        if(navigation == 'on'){
            option.navigation =  {
                nextEl: ".st-button-next",
                prevEl: ".st-button-prev",
            };
        }
        if(auto_play == 'on'){
            if(delay.length > 0){
                option.autoplay = {
                    delay: delay,
                };
            } else {
                option.autoplay = {
                    delay: 2000,
                };
            }
           
        }
        if(loop == 'true'){
            option.loop = true;
        }
        const Swiper = elementorFrontend.utils.swiper;
        $('.st-testimonial .swiper-wrapper .swiper-slide', $wrapper).matchHeight({remove: true});
        $('.st-testimonial .swiper-wrapper .swiper-slide', $wrapper).matchHeight();
        const swipers = new Swiper(imageCarousel,option);
        $('.st-testimonial .swiper-wrapper .swiper-slide', $wrapper).matchHeight();
    }
    

})
(jQuery);