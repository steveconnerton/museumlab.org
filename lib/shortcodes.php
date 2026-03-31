<?php

/*
 * shortcode to create a sitemap
 * optional attributes -- exclude: comma separated list of page ids to exclude
 * example: [sitemap exclude="3,10,12"]
 */
function sitemap_shortcode( $atts ) {
  $all_pages = wp_list_pages( 'title_li=&echo=0&exclude=' . $atts['exclude'] );

  return '<ul class="sitemap">' . $all_pages . '</ul>';
}

add_shortcode( 'sitemap', 'sitemap_shortcode' );

/**
 * @param array $atts
 *
 * @return string
 */
function sa_btn_shortcode( $atts = array() ) {
  $file   = get_bloginfo( 'template_url' ) . '/images/' . $atts['arrow'];
  $file   = file_get_contents( $file );
  $target = '';
  if ( isset( $atts['target'] ) && $atts['target'] == 'new' ) {
    $target = 'target="_blank"';
  }

  return '
<a class=" btn ' . $atts['class'] . ' btn__arrow" href="' . $atts['url'] . '"  ' . $target . '>' . $atts['title'] . ' <span class="arrow__icon">
    ' . $file . '
    </span>
</a>';
}

add_shortcode( 'sa_btn', 'sa_btn_shortcode' );


/**
 * @param array $atts
 *
 * @return string
 */
function sa_header_shortcode( $atts = array() ) {

  $tag   = ( $atts['tag'] ) ?? 'h2';
  $class = ( $atts['class'] ) ?? '';
  $class .= ' heading__wrapper__text';
  $url   = ( isset( $atts['url'] ) ) ? 'href="' . $atts['url'] . '"' : '';

  return '
<div class="heading__wrapper">
   <' . $tag . ' ' . $url . ' class="' . $class . '">' . $atts['title'] . '</' . $tag . '>

</div>';
}

add_shortcode( 'ob_heading', 'sa_header_shortcode' );


function sa_donate_shortcode() {
  return '
<div class="donate">
<form class="donate-form" action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="business" value="lorib@sunbeltnetwork.com"/><input type="hidden" name="cmd" value="_donations"/><input type="hidden" name="item_name" value="Faith &amp; Gratitude"/><input type="hidden" name="item_number" value="Empowering Cancer Patients Through Your Gift.  Thank you!"/><input type="hidden" name="currency_code" value="USD"/><button class="donate-button  btn btn__primary btn__arrow">Donate Now <span class="arrow__icon">
    <svg width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M7 7L0 0L4.45455 7L0 14L7 7Z" fill="#7FABC5"></path>
</svg>

    </span></button><img alt="" width="1" height="1" src="https://tower-etc.digital.vistaprint.com/paypal/donatePixel.gif"/></form></div>';
}

add_shortcode( 'sa_donate_btn', 'sa_donate_shortcode' );

/**
 * event image
 * @return string
 */
function event_image( $atts = array() ) {
  $image = ( $atts['image'] ) ?? '';
  if ( $image ) {
    return '<p style="text-align: center"><img src="' . $image . '" style="width: 100px"/></p>';
  }

  return $atts['title'];
}

add_shortcode( 'event_image', 'event_image' );


/**
 * show vids
 * @return string
 */
function ml_instafeeds()
{
  $html = '';
  $json = ml_get_feeds();
  $html .= '<div class="instagram_gallery">';
  foreach ($json as $item) {
    $url = $item->mediaUrl;
    if ($item->mediaType == 'VIDEO') {
      $url = $item->thumbnailUrl;
    }
    $html .= '<a target="_blank" rel="noreferrer" href="' . $item->permalink . '"><img src="' . $url . '" alt="Museum Lab"/></a>';
  }
  $html .= '</div>';
  return $html;
}

/**
 * get feeds
 * @return mixed
 */
function ml_get_feeds()
{

  $date = date('H');
  if ($date % 3 == 0) {
    $url = 'https://feeds.behold.so/2R01ZBx6wIu4MfSBvWQv';
    $json = file_get_contents($url);
    update_option('ml_instafeeds', $json);
  }
  $json = get_option('ml_instafeeds');
  $json = json_decode($json);
  return $json;
}

add_shortcode('ml_instafeeds', 'ml_instafeeds');

