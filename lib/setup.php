<?php

// ==========================================================================
//    LOAD SCRIPTS
// ==========================================================================
function add_scripts()
{
  if (!is_admin()) {
    wp_deregister_script("jquery");
    wp_register_script("jquery", "https://code.jquery.com/jquery-3.6.0.min.js", false, '3.6.4', false);
    wp_enqueue_script("jquery");
    wp_enqueue_script("bootstrap-bundle-js", get_template_directory_uri() . "/js/plugins/bootstrap.bundle.min.js", false, false, true);
    wp_enqueue_script("site-js", get_template_directory_uri() . "/js/site.js", array("jquery"), ASSETS_VERSION, true);
    wp_enqueue_script("slick-js", "//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js", array(), false, true);
    wp_enqueue_script("aos-js", "//unpkg.com/aos@next/dist/aos.js", array(), '1.0.1', false);

    if (is_home() || is_front_page()) {
      //wp_enqueue_script('vierobridal-jquery-instagramFeed-min', get_template_directory_uri() . '/js/plugins/jquery.instagramFeed.p.js', array(), '', true);
    }

  }
}

add_action('wp_enqueue_scripts', 'add_scripts');


// ==========================================================================
//    LOAD STYLES
// ==========================================================================
function add_stylesheets()
{
  if (!is_admin()) {
    wp_enqueue_style("fontawesome-css", "https://use.fontawesome.com/releases/v5.13.0/css/all.css", array(), '5.0.8');
    wp_enqueue_style('ml_root', get_template_directory_uri() . '/css/root.css', array(), ASSETS_VERSION, 'all');
    wp_enqueue_style('master', get_template_directory_uri() . '/css/style.css', array(), ASSETS_VERSION, 'all');
    wp_enqueue_style('ml-css', get_template_directory_uri() . '/style.css', array(), ASSETS_VERSION, 'all');
    wp_enqueue_style("aos-css", "//unpkg.com/aos@2.3.1/dist/aos.css", array(), '1.0.1');
  }
}

add_action('wp_enqueue_scripts', 'add_stylesheets');


// ==========================================================================
//    MENUS
// ==========================================================================
function register_nav()
{

  register_nav_menu('main_nav', __('Main Menu'));
  register_nav_menu('footer_nav', __('Footer Menu'));

  $menus = array(
    array(
      'name' => 'Main Nav',
      'slug' => 'main_nav'
    ),
    array(
      'name' => 'Footer Nav',
      'slug' => 'footer_nav'
    ),


  );

  foreach ($menus as $menu) {

    // Check if the menu exists
    $menu_exists = wp_get_nav_menu_object($menu['name']);

    // If it doesn't exist, let's create it.
    if (!$menu_exists) {

      $menu_id = wp_create_nav_menu($menu['name']);

      $locations = get_theme_mod('nav_menu_locations');
      $locations[$menu['slug']] = $menu_id;
      set_theme_mod('nav_menu_locations', $locations);

    }
  }

}

add_action('init', 'register_nav');

// ==========================================================================
//    WIDGETS
// ==========================================================================
function register_widget_area()
{
  register_sidebar(array(
    'name' => 'Blog Sidebar',
    'id' => 'blog-sidebar',
    'before_widget' => '<div class="sidebar__blog  %2$s ">',
    'after_widget' => '</div>',
    'before_title' => '<h3 class="sidebar__title">',
    'after_title' => '</h3>',
  ));
}

add_action('widgets_init', 'register_widget_area');


// ==========================================================================
//    CUSTOM ADMIN LOGIN LOGO
// ==========================================================================
function custom_login_logo()
{
  echo '<style type="text/css">
    .login h1 a { background-image: url("https://ezradigital.com/wp-content/uploads/2020/05/ezra_digital_logo_login.png"); background-size: 100%; width: 100%;}
    </style>';
}

add_action('login_head', 'custom_login_logo');


/**
 * custom login head url
 * @return string|void
 */
function custom_login_headerurl()
{
  return 'https://ezradigital.com';
}

add_action('login_headerurl', 'custom_login_headerurl');

// ==========================================================================
//  RESET SOME WORDPRESS DEFAULTS
// ==========================================================================

/*
 * when oembed is youtube or vimeo,
 * wrap code in a class to make it responsive.
 * otherwise just return the typical embed code
 */
function custom_oembed_filter($html, $url, $attr, $post_ID)
{
  if (strpos($url, 'youtube') !== false || strpos($url, 'youtu.be') !== false || strpos($url, 'vimeo') !== false) {
    return '<div class="flexible-video">' . $html . '</div>';
  } else {
    return $html;
  }
}

add_filter('embed_oembed_html', 'custom_oembed_filter', 10, 4);

/*
 * by default, images are set to link to the attachment,
 * this resets the default so images are not links
 */
function wpb_imagelink_setup()
{
  $image_set = get_option('image_default_link_type');

  if ($image_set !== 'none') {
    update_option('image_default_link_type', 'none');
  }
}

add_action('admin_init', 'wpb_imagelink_setup', 10);


// ==========================================================================
//  USEFUL FUNCTIONS
// ==========================================================================


/**
 * Limit the number of characters in a string.
 *
 * @param string $value
 * @param int $limit
 * @param string $end
 *
 * @return string
 */
function str_limit($value, $limit = 100, $end = '...')
{
  if (mb_strlen($value) <= $limit) {
    return $value;
  }

  return rtrim(mb_substr($value, 0, $limit, 'UTF-8')) . $end;
}

/**
 * Add columns to books post list
 *
 * @param $columns
 *
 * @return array
 */

function add_testimonial_acf_columns($columns)
{

  return array_merge($columns, array(
    'author_name' => __('Author'),

  ));
}

add_filter('manage_testimonial_posts_columns', 'add_testimonial_acf_columns');

/**
 * Add columns to books post list
 *
 * @param $column
 * @param $post_id
 */
function testimonial_custom_column($column, $post_id)
{
  switch ($column) {
    case 'author_name':
      echo get_post_meta($post_id, 'author_name', true);
      break;
  }
}

add_action('manage_testimonial_posts_custom_column', 'testimonial_custom_column', 10, 2);


add_filter('get_the_archive_title', function ($title) {
  if (is_category()) {
    $title = single_cat_title('', false);
  } elseif (is_tag()) {
    $title = single_tag_title('', false);
  } elseif (is_author()) {
    $title = '<span class="vcard">' . get_the_author() . '</span>';
  } elseif (is_tax()) { //for custom post types
    $title = sprintf(__('%1$s'), single_term_title('', false));
  } elseif (is_post_type_archive()) {
    $title = post_type_archive_title('', false);
  }
  return $title;
});

//add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {

    show_admin_bar(false);

}


/**
 * check if request from mobile
 * @return bool
 */
function ml_is_mobile()
{
  if (!isset($_SERVER['HTTP_USER_AGENT'])) {
    return FALSE;
  }

  $useragent = $_SERVER['HTTP_USER_AGENT'];
  return (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4)));

}


/**
 * Like get_template_part() put lets you pass args to the template file
 * Args are available in the tempalte as $template_args array
 * @param string filepart
 * @param mixed wp_args style argument list
 * https://wordpress.stackexchange.com/questions/176804/passing-a-variable-to-get-template-part
 */
function get_template_part_args($file, $template_args = array(), $cache_args = array())
{
  $template_args = wp_parse_args($template_args);
  $cache_args = wp_parse_args($cache_args);
  if ($cache_args) {
    foreach ($template_args as $key => $value) {
      if (is_scalar($value) || is_array($value)) {
        $cache_args[$key] = $value;
      } else if (is_object($value) && method_exists($value, 'get_id')) {
        $cache_args[$key] = call_user_method('get_id', $value);
      }
    }
    if (($cache = wp_cache_get($file, serialize($cache_args))) !== false) {
      if (!empty($template_args['return']))
        return $cache;
      echo $cache;
      return;
    }
  }
  $file_handle = $file;
  do_action('start_operation', 'hm_template_part::' . $file_handle);
  if (file_exists(get_stylesheet_directory() . '/' . $file . '.php'))
    $file = get_stylesheet_directory() . '/' . $file . '.php';
  elseif (file_exists(get_template_directory() . '/' . $file . '.php'))
    $file = get_template_directory() . '/' . $file . '.php';
  ob_start();
  $return = require($file);
  $data = ob_get_clean();
  do_action('end_operation', 'hm_template_part::' . $file_handle);
  if ($cache_args) {
    wp_cache_set($file, $data, serialize($cache_args), 3600);
  }
  if (!empty($template_args['return']))
    if ($return === false)
      return false;
    else
      return $data;
  echo $data;
}


add_image_size('hero-bg', 1920, 661, true);
add_image_size('hero-bg-2x', 3840, 1222, true);

add_image_size('ci', 887, 537, true);
add_image_size('ci-2x', 1774, 1074, true);

add_image_size('ig', 335, 235, true);
add_image_size('ig-2x', 670, 470, true);

add_image_size('slider', 974, 699, true);
add_image_size('slider-2x', 1948, 1398, true);

add_image_size('video', 549, 316, true);
add_image_size('video-2x', 1098, 632, true);

add_image_size('news', 194, 136, true);
add_image_size('news-2x', 388, 272, true);

add_image_size('team', 263, 178, true);

add_filter('upload_mimes', 'add_file_types_to_uploads');

function add_file_types_to_uploads($file_types)
{
  $new_filetypes = array();
  $new_filetypes['svg'] = 'image/svg+xml';
  $file_types = array_merge($file_types, $new_filetypes);
  return $file_types;
}
