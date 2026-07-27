<?php

$theme_includes = [
  'lib/theme_support.php',
  'lib/aq_resizer.php',
  'lib/acf_layout.php',
  'lib/acf_customization.php',
  'lib/custom_post_types.php',
  'lib/breadcrumbs.php',
  'lib/custom_toolbar.php',
  'lib/hex2rgba.php',
  'lib/shortcodes.php',
  'lib/setup.php',
  'lib/widgets.php',
];

foreach ( $theme_includes as $file ) {
  if ( ! $filepath = locate_template( $file ) ) {
    trigger_error( sprintf( __( 'Error locating %s for inclusion' ), $file ), E_USER_ERROR );
  }
  require_once $filepath;
}
unset( $file, $filepath );

/* Existing Site Filters */
function my_excerpt_length( $length ) { return 8; }
add_filter( 'excerpt_length', 'my_excerpt_length' );

add_filter( 'autoptimize_filter_css_replacetag', function($tag) { return array("</head>", "before"); }, 10, 1 );

function limit_text( $text, $limit ) {
  if ( str_word_count( $text, 0 ) > $limit ) {
    $words = str_word_count( $text, 2 );
    $pos = array_keys( $words );
    $text = substr( $text, 0, $pos[ $limit ] ) . '...';
  }
  return $text;
}

function num_posts_archive_podcast( $query ) {
  if ( ! is_admin() && $query->is_archive( 'podcast' ) && $query->is_main_query() ) {
    $query->set( 'posts_per_page', 3 );
  }
  return $query;
}
add_filter( 'pre_get_posts', 'num_posts_archive_podcast' );

function set_posts_per_page( $query ) {
  if ( ( is_post_type_archive( 'series' ) || is_post_type_archive( 'prize' ) ) && ! is_admin() && $query->is_main_query() ) {
    $query->set( 'posts_per_page', 9999 );
  }
  return $query;
}
add_action( 'pre_get_posts', 'set_posts_per_page' );

add_filter( 'get_the_archive_title', function ( $title ) {
  if ( $title == 'Archives' ) { $title = 'News'; }
  return $title;
} );

function sa_excerpt_length( $length ) { return 20; }
add_filter( 'excerpt_length', 'sa_excerpt_length' );

function sa_excerpt_more( $more ) { return '...'; }
add_filter( 'excerpt_more', 'sa_excerpt_more' );

define( 'ASSETS_VERSION', '1.0.4' );

function webp_upload_mimes( $existing_mimes ) {
  $existing_mimes['webp'] = 'image/webp';
  return $existing_mimes;
}
add_filter( 'mime_types', 'webp_upload_mimes' );

/**
 * [ml_upcoming_events]
 * Updated: PHP handles data, JS handles images to prevent crashes.
 */
function mlab_display_upcoming_events($atts) {
    if ( ! function_exists( 'wpgetapi_endpoint' ) ) return '';

    $response = wpgetapi_endpoint( 'tessitura', 'perf_summary', array( 
        'query_variables' => 'productionSeasonId=218' 
    ) );

    $performances = $response['PerformanceSummary'] ?? $response['PerformanceSummaries'] ?? $response;
    if ( empty($performances) || !is_array($performances) ) return '';
    if ( isset($performances['Id']) ) $performances = array($performances);

    $unique_events = array();
    foreach ( $performances as $perf ) {
        $title = $perf['Description'] ?? '';
        $date  = $perf['PerformanceDateTime'] ?? '';
        if ( $date && strtotime($date) >= time() && !isset($unique_events[$title]) ) {
            $unique_events[$title] = [ 'id' => $perf['Id'] ?? '', 'title' => $title, 'date' => $date ];
        }
        if ( count($unique_events) >= 2 ) break;
    }

    ob_start(); ?>
    <style>
        .ml-events-grid { display: flex; gap: 20px; flex-wrap: wrap; margin: 20px 0; }
        .ml-tile { 
            flex: 1; min-width: 300px; min-height: 380px; position: relative; 
            background: #222 center/cover no-repeat; border-radius: 4px; overflow: hidden; 
            display: block; text-decoration: none !important;
            background-image: url('https://museumlab.org/wp-content/uploads/2023/05/mlab-placeholder.jpg');
        }
        .ml-tile-date { 
            position: absolute; top: 0; left: 0; background: #18fa14; 
            padding: 10px 15px; font-weight: 800; color: #000; z-index: 5; 
            text-transform: uppercase; font-size: 13px;
        }
        .ml-tile-overlay { 
            position: absolute; inset: 0; 
            background: linear-gradient(transparent 50%, rgba(0,0,0,0.8)); 
            display: flex; align-items: flex-end; padding: 25px; 
        }
        .ml-tile-title { 
            color: #fff !important; margin: 0; font-size: 17px; 
            text-transform: lowercase; line-height: 1.1; font-family: GothamSSmMedium_Web, sans-serif; position: absolute; left: 8px; bottom: 8px;
        }
    </style>

    <div class="ml-events-grid">
        <?php foreach ( $unique_events as $event ) : 
            $link = "https://secure.pittsburghkids.org/0/" . $event['id'] . "/?site=mlab";
        ?>
            <a href="<?php echo esc_url($link); ?>" 
               class="ml-tile js-ml-event-tile" 
               data-remote-url="<?php echo esc_url($link); ?>">
                <div class="ml-tile-date"><?php echo date("F j", strtotime($event['date'])); ?></div>
                <div class="ml-tile-overlay">
                    <h3 class="ml-tile-title"><?php echo esc_html($event['title']); ?></h3>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
    <?php return ob_get_clean();
}
add_shortcode( 'ml_upcoming_events', 'mlab_display_upcoming_events' );