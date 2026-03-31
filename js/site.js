/**
 * All functionality regarding the
 * Home page
 */
var $ = jQuery.noConflict();
jQuery(document).ready(function () {
  "use strict";
  isElementExist(".section-slider .slider", initSliderSection);
  isElementExist(".banner-slider .slider", initBannerSlider);
  isElementExist(".js-images-grid", initLoadMore);
  isElementExist(".js-videos-grid", initLoadMoreVideos);
  isElementExist(".banner-video .video", initVideos);
  initMenu();

  $('.hamburger-checkbox').click(function () {
    $('.menu-pane').toggleClass('checked');
    $('body').toggleClass('menu-open');
  })
});

// Helper if element exist then call function
function isElementExist(_el, _cb) {
  var elem = document.querySelector(_el);

  if (document.body.contains(elem)) {
    try {
      _cb();
    } catch (e) {
      console.log(e);
    }
  }
}

function initSliderSection() {
  $(".section-slider .slider").slick({
    dots: false,
    infinite: false,
    speed: 300,
    slidesToShow: 1,
    centerMode: true,
    variableWidth: true,

    responsive: [
      {
        breakpoint: 1120,
        settings: {
          centerMode: false,
          variableWidth: false,
        },
      },
      {
        breakpoint: 666,
        settings: {
          dots: true,
          arrows: false,
          centerMode: false,
          variableWidth: false,
        },
      },
    ],
  });
}

function initBannerSlider() {
  $(".banner-slider .slider").slick({
    dots: true,
    infinite: false,
    speed: 300,
    slidesToShow: 1,
    centerMode: false,
    variableWidth: false,
    responsive: [
      {
        breakpoint: 1120,
        settings: {
          centerMode: false,
          variableWidth: false,
        },
      },
      {
        breakpoint: 666,
        settings: {
          dots: true,
          arrows: false,
          centerMode: false,
          variableWidth: false,
        },
      },
    ],
  });
}

function initLoadMore() {
  size_li = $(".js-images-grid li.images").length;
  x = 2;
  $('.js-images-grid li.images:lt(' + x + ')').addClass('visible');
  $('.js-load-more').click(function (e) {
    e.preventDefault();
    x = (x + 1 <= size_li) ? x + 1 : size_li;
    $('.js-images-grid li.images:lt(' + x + ')').addClass('visible');
    visibleLength = $(".js-images-grid li.visible").length;
    if (size_li == visibleLength) {
      $('.js-load-more').hide();
    }
  });

}

function initLoadMoreVideos() {
  size_liy = $(".js-videos-grid a").length;
  y = 3;
  $('.js-videos-grid a:lt(' + y + ')').addClass('visible');
  $('.js-load-more-videos').click(function (e) {
    e.preventDefault();
    y = (y + 3 <= size_liy) ? y + 3 : size_liy;
    $('.js-videos-grid a:lt(' + y + ')').addClass('visible');
    visibleLengthy = $(".js-videos-grid a.visible").length;
    if (size_liy == visibleLengthy) {
      $('.js-load-more-videos').hide();
    }
  });

}

function initInstagramFeed() {
  $.instagramFeed({
    'proxy_image_url': "/instafeed/instamedia/instamedia.php",
    'username': instgramAccount,
    'container': "#instagram-feed3",
    'display_profile': false,
    'display_biography': false,
    'display_gallery': true,
    'get_data': false,
    'callback': null,
    'styling': true,
    'items': items,
    'items_per_row': items_per_row,
    'margin': 0.3,
    'on_error': console.error
  });
}

function initMenu() {
  jQuery('li.menu-item-has-children').append('<span class="drop-down"></span>');

  //jQuery(window).on('load resize', function () {

  var sWidth = jQuery(window).width();
  if (sWidth <= 991) {
    jQuery('.navbar-toggler').on('click', function () {
      // jQuery(this).siblings('.main-navigation').toggleClass('show');
      //jQuery('body').toggleClass('noscroll');
    });
    jQuery('header ul.sub-menu').hide();
    jQuery('span.drop-down').on('click', function () {
      jQuery(this).parent().children('.sub-menu').slideToggle();
      jQuery(this).toggleClass('drop-down').toggleClass('up');
    });
  }
}

// Initialize video background Progress bar
function initVideos() {

  var videoPlayer = document.querySelector(".banner-video"),
    iframe = videoPlayer.querySelector(".video");
  var player = new Vimeo.Player(iframe);
  $('.js-play-pause').click(function () {
    if($(this).hasClass('play')){
      $(this).removeClass('play');
      $(this).addClass('pause');
      $(this).html('<i class="far fa-play-circle"></i>')
      player.pause();
    }else{
      $(this).addClass('play');
      $(this).removeClass('pause');
      $(this).html('<i class="far fa-pause-circle"></i>')
      player.play();
    }

  })
  /*
  $('.b-play').click(function () {
    player.pause();
  })*/


}


