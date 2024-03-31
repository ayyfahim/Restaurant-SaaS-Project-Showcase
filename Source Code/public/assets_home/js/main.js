(function ($) {
    "user strict";

    $(document).ready(function () {
        // $(this).scrollTop(0);
        
        // Nice Select
        $('.select-bar').niceSelect();
        // Lightcase
        $('.video-popup').magnificPopup({
            type: 'iframe',
        });
        $('.img-popup').magnificPopup({
            type: 'image'
        });

        $("body").each(function () {
            $(this).find(".img-pop").magnificPopup({
                type: "image",
                gallery: {
                    enabled: true
                }
            });
        });

        //MenuBar
        $('.header-bar').on('click', function () {
            $(".menu").toggleClass("active");
            $(".header-bar").toggleClass("active");
            $('.overlay').toggleClass('active');
        });
        $('.overlay').on('click', function () {
            $(".menu").removeClass("active");
            $(".header-bar").removeClass("active");
            $('.overlay').removeClass('active');
        });
        //Menu Dropdown Icon Adding
        $("ul>li>.submenu").parent("li").addClass("menu-item-has-children");
        // drop down menu width overflow problem fix
        $('ul').parent('li').hover(function () {
            var menu = $(this).find("ul");
            var menupos = $(menu).offset();
            if (menupos.left + menu.width() > $(window).width()) {
                var newpos = -$(menu).width();
                menu.css({
                    left: newpos
                });
            }
        });
        $('.menu li a').on('click', function (e) {
            var element = $(this).parent('li');
            if (element.hasClass('active')) {
                element.removeClass('active');
                element.find('li').removeClass('active');
                element.find('ul').slideUp(300, "swing");
            } else {
                element.addClass('active');
                element.children('ul').slideDown(300, "swing");
                element.siblings('li').children('ul').slideUp(300, "swing");
                element.siblings('li').removeClass('active');
                element.siblings('li').find('li').removeClass('active');
                element.siblings('li').find('ul').slideUp(300, "swing");
            }
        })
        // Scroll To Top
        var scrollTop = $(".scrollToTop");
        $(window).on('scroll', function () {
            if ($(this).scrollTop() < 500) {
                scrollTop.removeClass("active");
            } else {
                scrollTop.addClass("active");
            }
        });
        
        //Click event to scroll to top
        $('.scrollToTop').on('click', function () {
            $('html, body').animate({
                scrollTop: 0
            }, 500);
            return false;
        });
        // Header Sticky Here
        var headerOne = $(".header-section");
        $(window).on('scroll', function () {
            if ($(this).scrollTop() < 1) {
                headerOne.removeClass("header-active");
            } else {
                headerOne.addClass("header-active");
            }
        });
        $('.window-warning .lay').on('click', function () {
            $('.window-warning').addClass('inActive');
        })
        $('.seat-plan-wrapper li .movie-schedule .item').on('click', function () {
            $('.window-warning').removeClass('inActive');
        })

        //Odometer
        $(".counter-item").each(function () {
            $(this).isInViewport(function (status) {
                if (status === "entered") {
                    for (var i = 0; i < document.querySelectorAll(".odometer").length; i++) {
                        var el = document.querySelectorAll('.odometer')[i];
                        el.innerHTML = el.getAttribute("data-odometer-final");
                    }
                }
            });
        });


        // Testimonial Slider
        $('.testimonial-slider').owlCarousel({
            loop: true,
            responsiveClass: true,
            nav: true,
            dots: false,
            margin: 30,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            smartSpeed: 2000,
            // animateOut: 'fadeOut',
            // animateIn: 'fadeIn',
            responsive: {
                0: {
                    items: 1,
                }
            }
        });


    });

    // Preloader Js
    $(window).on('load', function () {
        $('.preloader').fadeOut(1000);
        var img = $('.bg_img');
        img.css('background-image', function () {
            var bg = ('url(' + $(this).data('background') + ')');
            return bg;
        });
      
        // Wow js active
        new WOW().init();

    });


})(jQuery);
