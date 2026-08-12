<?php
/**
 * Theme Setup & Core Includes
 */

// 1. DEFINE ASSETS_VERSION (Prevents fatal error in lib/setup.php)
if ( ! defined( 'ASSETS_VERSION' ) ) {
    define( 'ASSETS_VERSION', '1.0.0' );
}

// 2. CORE THEME INCLUDES
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

foreach ($theme_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);


/**
 * Dynamic Tessitura Events Renderer
 * Fetches upcoming performances, filters for On Sale status (ID = 1),
 * extracts WebContents image & title override by owner hierarchy, and renders top 2 cards.
 */
function render_tessitura_homepage_events() {
    if ( ! function_exists( 'wpgetapi_endpoint' ) ) {
        return '<p class="no-events">WPGetAPI is not installed or active.</p>';
    }

    // Step 1: Fetch performances via TXN/Performances
    $performances = wpgetapi_endpoint( 'tessitura', 'perf_summary', array( 'debug' => false ) );

    if ( empty( $performances ) || ! is_array( $performances ) || isset( $performances['ErrorPath'] ) ) {
        return '<p class="no-events">No upcoming events found.</p>';
    }

    $today_timestamp = strtotime( 'today' );
    $valid_performances = array();

    // Step 2: Filter for upcoming dates & Status = On Sale (Status ID = 1)
    foreach ( $performances as $perf ) {
        $raw_date = ! empty( $perf['Date'] ) ? $perf['Date'] : ( $perf['FirstPerformanceDate'] ?? '' );
        if ( empty( $raw_date ) ) {
            continue;
        }

        $perf_time = strtotime( $raw_date );
        
        // Ensure event is today or future
        if ( $perf_time < $today_timestamp ) {
            continue;
        }

        // Check Status: Status > ID == 1 or Status > Description == "On Sale"
        $status_id   = $perf['Status']['Id'] ?? $perf['Status']['ID'] ?? null;
        $status_desc = strtolower( $perf['Status']['Description'] ?? '' );

        if ( $status_id == 1 || $status_desc === 'on sale' ) {
            $perf['_timestamp'] = $perf_time;
            $valid_performances[] = $perf;
        }
    }

    if ( empty( $valid_performances ) ) {
        return '<p class="no-events">No active upcoming events found.</p>';
    }

    // Sort chronologically ascending (closest upcoming event first)
    usort( $valid_performances, function( $a, $b ) {
        return $a['_timestamp'] - $b['_timestamp'];
    });

    // Take top 2 upcoming events for the homepage
    $two_events = array_slice( $valid_performances, 0, 2 );

    ob_start();
    echo '<div class="homepage-tessitura-events-grid">';

    foreach ( $two_events as $event ) {
        $perf_id = $event['Id'] ?? $event['ID'] ?? '';

        // Default values from Performance object
        $title = $event['ProductionSeason']['Description'] ?? $event['Description'] ?? 'MuseumLab Event';
        $image_url = '';
        $raw_date  = $event['Date'] ?? $event['FirstPerformanceDate'] ?? '';
        $formatted_date = $raw_date ? date( 'M j', strtotime( $raw_date ) ) : '';

        // Dynamic URL pointing to the specific performance ID
        $event_url = ! empty( $perf_id ) 
            ? 'https://secure.pittsburghkids.org/performance/' . esc_attr( $perf_id ) . '?site=mlab'
            : 'https://secure.pittsburghkids.org/?site=mlab';

        // Step 3: Fetch WebContents for image (ID 134) & title override (ID 132)
        if ( $perf_id ) {
            $web_contents = wpgetapi_endpoint( 
                'tessitura', 
                'web_contents', 
                array( 
                    'debug' => false,
                    'query_variables' => 'productionElementIds=' . $perf_id . '&contentTypeIds=131,132,134&showAll=true'
                ) 
            );

            if ( ! empty( $web_contents ) && is_array( $web_contents ) && ! isset( $web_contents['ErrorPath'] ) ) {
                $best_image_owner = 999;
                $best_title_owner = 999;

                foreach ( $web_contents as $content ) {
                    $type_id    = $content['Type']['Id'] ?? $content['Type']['ID'] ?? null;
                    $owner_type = $content['Owner']['Type'] ?? 99;
                    $value      = $content['Value'] ?? '';

                    if ( empty( $value ) ) {
                        continue;
                    }

                    // ContentType 134 = Image URL (Prefer lowest Owner Type: 0 > 1 > 2...)
                    if ( $type_id == 134 && $owner_type < $best_image_owner ) {
                        $image_url = $value;
                        $best_image_owner = $owner_type;
                    }

                    // ContentType 132 = Title Override (Prefer lowest Owner Type: 0 > 1 > 2...)
                    if ( $type_id == 132 && $owner_type < $best_title_owner ) {
                        $title = $value;
                        $best_title_owner = $owner_type;
                    }
                }
            }
        }

        // Render Card matching homepage layout with dynamic event links
        ?>
        <div class="homepage-event-card">
            <?php if ( ! empty( $image_url ) ) : ?>
                <div class="homepage-event-image">
                    <a href="<?php echo esc_url( $event_url ); ?>" target="_blank" rel="noopener noreferrer">
                        <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" />
                    </a>
                </div>
            <?php endif; ?>
            <div class="homepage-event-content">
                <?php if ( $formatted_date ) : ?>
                    <span class="homepage-event-date"><?php echo esc_html( $formatted_date ); ?></span>
                <?php endif; ?>
                <a href="<?php echo esc_url( $event_url ); ?>" class="homepage-event-title-link" target="_blank" rel="noopener noreferrer">
                    <?php echo esc_html( $title ); ?>
                </a>
            </div>
        </div>
        <?php
    }

    echo '</div>';

    return ob_get_clean();
}

add_shortcode( 'homepage_tessitura_events', 'render_tessitura_homepage_events' );