"use strict";

/*
* ----------------------------------------------------------------------------------------
    Template Name: WizTheme
    Template URI: WizTheme
    Description: 
    Author: mahedi amin
    Author URI: WizTheme
    Version: 1.0.0



    -------------INDEX----------------
   01.Mobile Menu
   02.Sticky Menu
   03.All Owl Slider
   04.Offcanvas
   05. Counter
   06. scroll up
   07. Magnify active
   08. Preloder Active
   09. WOW Active
   10. PROGRESSBAR ACTIVATION
   11. Tooltip activation
   12. ui activation
   13. Filter Acitve
   14. Select Two Activation
   15. Paralx Init
   16.Indiactor Testimonials
   17.countdown
   18.number Increment
   19.product zoom

* ----------------------------------------------------------------------------------------
*/
(function ($) {
  'use strict';

  jQuery(document).on("ready", function () {
    /*---------------====================
    01.Mobile Menu 
    ================-------------------*/
    $(".menu-item-has-children > a").on("click", function () {
      var element = $(this).parent("li");

      if (element.hasClass("open")) {
        element.removeClass("open");
        element.find("li").removeClass("open");
        element.find("ul").slideUp(500, "linear");
      } else {
        element.addClass("open");
        element.children("ul").slideDown();
        element.siblings("li").children("ul").slideUp();
        element.siblings("li").removeClass("open");
        element.siblings("li").find("li").removeClass("open");
        element.siblings("li").find("ul").slideUp();
      }
    }); // menu menu active link

    $(".main-menu ul li").on("click", function () {
      $(".main-menu ul li").removeClass("active");
      $(this).addClass("active");
    }); // mobile menu click

    $(".menu-click").on("click", function () {
      $(".main-menu > ul").toggleClass('show');
      return false;
    });
    $('a.smooth-scroll').on("click", function (e) {
      var anchor = $(this);
      $('html, body').stop().animate({
        scrollTop: $(anchor.attr('href')).offset().top - 70
      }, 1200, 'easeInOutExpo');
      e.preventDefault();
    });
    $('body').scrollspy({
      target: '.navbar-collapse',
      offset: 195
    });
    $(".navbar-toggler").on('click', function () {
      $(".navbar-toggler").toggleClass("cg");
    });
    $('.open-creatac').on("click", function () {
      $(".rt-modal-input.two , .rt-modal-headr.two").addClass("show-cac");
      $(".rt-modal-input.one, .rt-modal-headr.one").addClass("hide-cac");
    });
    $(".rt-one-page-menu ul > li.nav-item > a.nav-link").on('click', function () {
      $(".navbar-collapse").removeClass("show");
      $(".navbar-toggler").removeClass("cg");
    });
    $(".open-cart-opt").on("click", function () {
      $(".rt-cart-box").toggleClass("active");
    });
    /*---------------====================
    02.Sticky Menu
    ================-------------------*/

    $(window).on('scroll', function () {
      var scroll = $(window).scrollTop();

      if (scroll < 200) {
        $(".rt-sticky").removeClass("rt-sticky-active fadeInDown animated");
      } else {
        $(".rt-sticky").addClass("rt-sticky-active fadeInDown animated");
      }
    });
    $(".rt-search-open").on("click", function () {
      $(".rt-hidden-search").addClass("rt-search-active");
    });
    $(".rt-search-close").on("click", function () {
      $(".rt-hidden-search").removeClass("rt-search-active");
    });
    /*---------------====================
    03.All Owl Slider
    ================-------------------*/

    if (document.getElementById("instragramslider_active")) {
      var getInstraDtata = document.getElementById("instragramslider_active");
      var getuserid = getInstraDtata.getAttribute("data-instra-id");
      var gettoken = getInstraDtata.getAttribute("data-instra-token");
      var feedFooter = new Instafeed({
        get: 'user',
        userId: getuserid,
        // your user id
        accessToken: gettoken,
        // your access token
        sortBy: 'most-liked',
        template: '<a class="instra-item" href="{{link}}" target="_blank"><img  src="{{image}}" /> <div class="instra-hover"><i class="icofont-link"></i></div></a>',
        target: getInstraDtata,
        limit: 9,
        resolution: 'low_resolution',
        after: function after() {
          $(getInstraDtata).owlCarousel({
            loop: true,
            margin: 0,
            nav: false,
            autoplay: true,
            autoplayTimeout: 3000,
            responsive: {
              210: {
                items: 1
              },
              320: {
                items: 1
              },
              479: {
                items: 1
              },
              768: {
                items: 1
              },
              980: {
                items: 1
              },
              1199: {
                items: 1
              }
            }
          });
        }
      });
      feedFooter.run();
    } // slider active 


    if ($('.rt-slider-active').length > 0) {
      $('.rt-slider-active').owlCarousel({
        margin: 0,
        loop: true,
        nav: true,
        //autoplay: true,
        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        autoplayTimeout: 9000,
        //mouseDrag: false,
        responsive: {
          0: {
            items: 1
          }
        }
      });
    }

    if ($('.rt-gallery-post-active').length > 0) {
      $('.rt-gallery-post-active').owlCarousel({
        margin: 0,
        loop: true,
        nav: true,
        dots: false,
        //autoplay: true,
        navText: ['<i class="icofont-caret-left"></i>', '<i class="icofont-caret-right"></i>'],
        mouseDrag: false,
        responsive: {
          0: {
            items: 1
          }
        }
      });
    } // testimonials


    if ($('.testimoninal-active-1').length > 0) {
      $('.testimoninal-active-1').owlCarousel({
        margin: 30,
        loop: true,
        dots: true,
        mouseDrag: true,
        responsive: {
          210: {
            items: 1
          },
          320: {
            items: 1
          },
          479: {
            items: 1
          },
          768: {
            items: 2
          },
          980: {
            items: 2
          },
          1199: {
            items: 2
          }
        }
      });
    } // slider animation


    if ($(".rt-slider-active").length > 0) {
      //Owl-Carousel Translate Function
      var heroSlider = $(".rt-slider-active");
      heroSlider.on('translate.owl.carousel', function () {
        var layer = $("[data-animation]");
        layer.each(function () {
          var animation_name = $(this).data('animation');
          $(this).removeClass('animated ' + animation_name).css('opacity', '1');
        });
      }); //Owl-Carousel Delay Function

      $("[data-delay]").each(function () {
        var animation_delay = $(this).data('delay');
        $(this).css('animation-delay', animation_delay);
      }); //Owl-Carousel Duration Function

      $("[data-duration]").each(function () {
        var animation_duration = $(this).data('duration');
        $(this).css('animation-duration', animation_duration);
      }); //Owl-Carousel Translated Function

      heroSlider.on('translated.owl.carousel', function () {
        var layer = heroSlider.find('.owl-item.active').find("[data-animation]");
        layer.each(function () {
          var animation_name = $(this).data('animation');
          $(this).addClass('animated ' + animation_name).css('opacity', '1');
        });
      });
    }

    $('.main-product').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      fade: true,
      asNavFor: '.product-thumbs',
      arrows: true,
      prevArrow: "<span  class='arrow_left'><i class='icofont-rounded-left'></i></span>",
      nextArrow: "<span  class='arrow_right'><i class='icofont-rounded-right'></i></span>"
    });
    $('.product-thumbs').slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      asNavFor: '.main-product',
      dots: false,
      focusOnSelect: true,
      responsive: [{
        breakpoint: 1199.98,
        settings: {
          slidesToShow: 3
        }
      }, {
        breakpoint: 767.98,
        settings: {
          slidesToShow: 2
        }
      }]
    }); // app interface active

    if ($(".app-interface-active").length > 0) {
      $('.app-interface-active').owlCarousel({
        loop: true,
        margin: 10,
        mouseDrag: true,
        center: true,
        responsive: {
          210: {
            items: 1
          },
          320: {
            items: 1
          },
          479: {
            items: 1
          },
          768: {
            items: 2
          },
          980: {
            items: 4
          },
          1199: {
            items: 5
          }
        }
      });
    }
    /*---------------====================
    04.Offcanvas
    ================-------------------*/


    $(".open-offcanvasmenu").on("click", function () {
      $(".rt-sidnav, .rt-overlay").addClass("active");
      $("body").addClass("sidenavOpen");
    });
    $(".sidenav-close, .rt-overlay").on("click", function () {
      $(".rt-sidnav, .rt-overlay").removeClass("active");
      $("body").removeClass("sidenavOpen");
    });
    $(".rt-sidnav, .rt-cart-box").overlayScrollbars({//className: "os-theme-light",
    });
    /*---------------====================
      05.Counter
      ================-------------------*/

    $('.counter').counterUp({
      delay: 10,
      time: 1000
    });
    /*---------------====================
      06.scroll up
      ================-------------------*/

    $.scrollUp({
      scrollText: '<i class="flaticon-right-arrow"></i>'
    });
    /*---------------====================
    07.Magnify active
    ================-------------------*/

    $('.playVideo').magnificPopup({
      type: 'iframe',
      removalDelay: 300,
      mainClass: 'mfp-fade'
    });
    $('.img-popup').magnificPopup({
      type: 'image',
      gallery: {
        enabled: true
      }
    });
    /*---------------====================
      08.Preloder Active
      ================-------------------*/

    jQuery(window).load(function () {
      jQuery(".rt-preloder").fadeOut(300);
    });
    /*---------------====================
    09.WOW Active
    ================-------------------*/

    if ($('.wow').length) {
      var wow = new WOW({
        boxClass: 'wow',
        // animated element css class (default is wow)
        animateClass: 'animated',
        // animation css class (default is animated)
        offset: 0,
        // distance to the element when triggering the animation (default is 0)
        //mobile: false, // trigger animations on mobile devices (default is true)
        live: true // act on asynchronously loaded content (default is true)

      });
      wow.init();
    }
    /*
      ==========================================
     10. PROGRESSBAR ACTIVATION
      ==========================================
      */


    $(".progress").each(function () {
      var e = $(this).attr("data-percent"),
          t = $(this).prev(".progress-title"),
          a = $(this).children(".progress-title");
      t.length > 0 ? t.css("width", e) : a.length > 0 && a.css("width", e), $(this).appear(function () {
        $(this).find(".progress-bar").animate({
          width: e
        }, 500);
      });
    });
    /*
            ==========================================
         11. Tooltip activation
          ==========================================
          */

    $('[data-toggle="tooltip"]').tooltip();
    tippy.setDefaults({
      arrow: true,
      delay: 40,
      placement: 'top',
      animation: 'perspective'
    });
    /*
        ==========================================
     12. ui activation
      ==========================================
      */

    $(".rt-date-picker").datepicker();
    $(".slider-range").slider({
      range: true,
      min: 40,
      max: 600,
      values: [160, 500],
      slide: function slide(event, ui) {
        $("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
      }
    });
    $(".amount").val("$" + $(".slider-range").slider("values", 0) + " - $" + $(".slider-range").slider("values", 1));
    /*---------------====================
            13. Filter Acitve
            ================-------------------*/

    $('.grid').imagesLoaded(function () {
      var $grid = $('.grid').isotope({
        itemSelector: '.grid-item',
        percentPosition: true,
        masonry: {
          // use outer width of grid-sizer for columnWidth
          columnWidth: 1
        }
      });
    });
    $('.filter-list').on('click', 'li', function () {
      $('.filter-list li').removeClass('active');
      $(this).addClass('active');
      var filterValue = $(this).attr('data-filter');
      $('.grid').isotope({
        filter: filterValue
      });
      $(window).trigger('resize');
    });
    /*
      ======== 14.Select Two Activation =========
    */

    $('.rt-selectactive').select2({
      minimumResultsForSearch: Infinity
    });
    /*
      ======== 15.Paralx Init =========
    */

    function initparallax() {
      var a = {
        Android: function Android() {
          return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function BlackBerry() {
          return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function iOS() {
          return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function Opera() {
          return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function Windows() {
          return navigator.userAgent.match(/IEMobile/i);
        },
        any: function any() {
          return a.Android() || a.BlackBerry() || a.iOS() || a.Opera() || a.Windows();
        }
      };
      var trueMobile = a.any();

      if (null == trueMobile) {
        var b = new Scrollax();
        b.reload();
        b.init();
      }
    }

    initparallax();
    $(".single-rt-banner").mousemove(function (e) {
      parallaxIt(e, ".star-move", 150);
      parallaxIt(e, ".mockup-img-1", -60);
      parallaxIt(e, ".main_mbl_mockup_img", -40);
      parallaxIt(e, ".sub_mockup_img1", 50);
      parallaxIt(e, ".sub_mockup_img2", -60);
      parallaxIt(e, ".sub_mockup_img3", 40);
      parallaxIt(e, ".sub_mockup_img4", -60);
    });
    $(".move-parents").mousemove(function (e) {
      parallaxIt2(e, ".star-move2", 140);
    });

    function parallaxIt(e, target, movement) {
      var $this = $(".single-rt-banner");
      var relX = e.pageX - $this.offset().left;
      var relY = e.pageY - $this.offset().top;
      TweenMax.to(target, 1, {
        x: (relX - $this.width() / 2) / $this.width() * movement,
        y: (relY - $this.height() / 2) / $this.height() * movement
      });
    }

    function parallaxIt2(e, target, movement) {
      var $this = $(".move-parents");
      var relX = e.pageX - $this.offset().left;
      var relY = e.pageY - $this.offset().top;
      TweenMax.to(target, 1, {
        x: (relX - $this.width() / 2) / $this.width() * movement,
        y: (relY - $this.height() / 2) / $this.height() * movement
      });
    }
    /*
      ======== 16.Indiactor Testimonials =========
    */


    $(".testimonail-indacator-parents > li").on("mouseover", function () {
      $("li").removeClass("active-testi");
      $(this).addClass("active-testi");
    });
    /*
    ======== 17.countdown =========
    */

    if ($(".simply-countdown-one").length > 0) {
      simplyCountdown('.simply-countdown-one', {
        year: "2020",
        month: "7",
        day: "04",
        words: {
          //words displayed into the countdown
          days: 'days',
          hours: 'hours',
          minutes: 'minutes',
          seconds: 'seconds'
        },
        plural: false,
        //use plurals
        zeroPad: true
      });
    }
    /*
    ======== 18.number Increment =========
    */


    var incrementPlus;
    var incrementMinus;
    var buttonPlus = $(".cart-qty-plus");
    var buttonMinus = $(".cart-qty-minus");
    var incrementPlus = buttonPlus.on("click", function () {
      var $n = $(this).parent(".button-container").parent(".rt-increment-container").find(".qty");
      $n.val(Number($n.val()) + 1);
    });
    var incrementMinus = buttonMinus.on("click", function () {
      var $n = $(this).parent(".button-container").parent(".rt-increment-container").find(".qty");
      var amount = Number($n.val());

      if (amount > 0) {
        $n.val(amount - 1);
      }
    });
    /*
    ======== 19.product zoom =========
    */

    $('.singleMianProduct').zoom();
  });
})(jQuery);