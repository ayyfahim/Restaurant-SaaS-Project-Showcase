
(function($) {
  "use strict"; // Start of use strict

  // Tooltip
  $('[data-toggle="tooltip"]').tooltip();


  $('.offer-slider').slick({
      centerMode: true,
      centerPadding: '30px',
      slidesToShow: 2,
      arrows: false,
      responsive: [{
              breakpoint: 768,
              settings: {
                  arrows: false,
                  centerMode: true,
                  centerPadding: '40px',
                  slidesToShow: 2
              }
          },
          {
              breakpoint: 480,
              settings: {
                  arrows: false,
                  centerMode: true,
                  centerPadding: '40px',
                  slidesToShow: 2
              }
          }
      ]
  });


  $('.cat-slider').slick({
      centerMode: true,
      centerPadding: '30px',
      slidesToShow: 4,
      arrows: false,
      responsive: [{
              breakpoint: 768,
              settings: {
                  arrows: false,
                  centerMode: true,
                  centerPadding: '40px',
                  slidesToShow: 4
              }
          },
          {
              breakpoint: 480,
              settings: {
                  arrows: false,
                  centerMode: true,
                  centerPadding: '40px',
                  slidesToShow: 4
              }
          }
      ]
  });


  $('.trending-slider').slick({
      centerMode: true,
      centerPadding: '30px',
      slidesToShow: 1,
      arrows: false,
      responsive: [{
              breakpoint: 768,
              settings: {
                  arrows: false,
                  centerMode: true,
                  centerPadding: '40px',
                  slidesToShow: 2
              }
          },
          {
              breakpoint: 480,
              settings: {
                  arrows: false,
                  centerMode: true,
                  centerPadding: '40px',
                  slidesToShow: 1
              }
          }
      ]
  });


  // Osahan Slider
  $('.osahan-slider').slick({
      centerMode: false,
      slidesToShow: 1,
      arrows: false,
      dots: true
  });

  // osahan-slider-map
  $('.osahan-slider-map').slick({
      centerMode: true,
      centerPadding: '30px',
      slidesToShow: 2,
      arrows: false,
      responsive: [{
              breakpoint: 768,
              settings: {
                  arrows: false,
                  centerMode: true,
                  centerPadding: '40px',
                  slidesToShow: 3
              }
          },
          {
              breakpoint: 480,
              settings: {
                  arrows: false,
                  centerMode: true,
                  centerPadding: '40px',
                  slidesToShow: 3
              }
          }
      ]
  });


  var $main_nav = $('#main-nav');
  var $toggle = $('.toggle');

  var defaultOptions = {
      disableAt: false,
      customToggle: $toggle,
      levelSpacing: 40,
      navTitle: 'Askbootstrap',
      levelTitles: true,
      levelTitleAsBack: true,
      pushContent: '#container',
      insertClose: 2
  };

  // call our plugin
  var Nav = $main_nav.hcOffcanvasNav(defaultOptions);
  
  
    

    });
	




(jQuery); // End of use strict