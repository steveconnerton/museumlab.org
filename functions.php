<?php

/**
 * MuseumLab Theme Functions
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
 * 3. TESSITURA API HELPER: FETCH CURRENT ACTIVE SEASON ID
 */
if ( ! function_exists( 'mlab_get_active_season_id' ) ) {
    function mlab_get_active_season_id() {
        if ( ! function_exists( 'wpgetapi_endpoint' ) ) {
            return null;
        }

        $cache_key   = 'ml_active_season_id';
        $cached_id   = get_transient( $cache_key );
        if ( $cached_id !== false ) {
            return $cached_id;
        }

        $seasons = wpgetapi_endpoint( 'tessitura', 'get_seasons', array(
            'debug' => false,
        ) );

        if ( empty( $seasons ) || ! is_array( $seasons ) || isset( $seasons['error'] ) ) {
            return null;
        }

        // Standardize response array
        if ( isset( $seasons['Id'] ) ) {
            $seasons = array( $seasons );
        }

        $current_season_id = null;
        $latest_season_id  = null;

        foreach ( $seasons as $season ) {
            if ( ! is_array( $season ) ) continue;

            $season_id  = $season['Id'] ?? null;
            $is_current = $season['IsCurrent'] ?? false;

            if ( $season_id ) {
                $latest_season_id = $season_id;
                if ( $is_current ) {
                    $current_season_id = $season_id;
                    break;
                }
            }
        }

        $active_id = $current_season_id ? $current_season_id : $latest_season_id;

        if ( $active_id ) {
            set_transient( $cache_key, $active_id, DAY_IN_SECONDS );
        }

        return $active_id;
    }
}


/**
 * 4. TESSITURA API HELPER: WEB CONTENTS (Images & Title Overrides)
 */
if ( ! function_exists( 'mlab_get_web_contents' ) ) {
    function mlab_get_web_contents( $performance_id ) {
        if ( ! function_exists( 'wpgetapi_endpoint' ) ) {
            return array( 'image' => '', 'title_override' => '' );
        }

        $cache_key   = 'ml_web_contents_' . $performance_id;
        $cached_data = get_transient( $cache_key );
        if ( $cached_data !== false ) {
            return $cached_data;
        }

        $endpoint_results = wpgetapi_endpoint( 'tessitura', 'web_contents', array(
            'debug'           => false,
            'query_variables' => 'productionElementIds=' . $performance_id . '&contentTypeIds=131,132,134&showAll=true',
        ) );

        if ( empty( $endpoint_results ) || ! is_array( $endpoint_results ) || isset( $endpoint_results['error'] ) ) {
            return array( 'image' => '', 'title_override' => '' );
        }

        if ( isset( $endpoint_results['Id'] ) ) {
            $endpoint_results = array( $endpoint_results );
        }

        $images_by_owner_type = array();
        $titles_by_owner_type = array();

        foreach ( $endpoint_results as $item ) {
            if ( ! is_array( $item ) ) continue;

            $content_type_id = isset( $item['Type']['Id'] ) ? $item['Type']['Id'] : null;
            $owner_type      = isset( $item['Owner']['Type'] ) ? $item['Owner']['Type'] : 99;
            $value           = isset( $item['Value'] ) ? $item['Value'] : '';

            if ( empty( $value ) ) continue;

            if ( $content_type_id == 134 ) {
                if ( ! isset( $images_by_owner_type[ $owner_type ] ) ) {
                    $images_by_owner_type[ $owner_type ] = $value;
                }
            }

            if ( $content_type_id == 132 ) {
                if ( ! isset( $titles_by_owner_type[ $owner_type ] ) ) {
                    $titles_by_owner_type[ $owner_type ] = $value;
                }
            }
        }

        ksort( $images_by_owner_type );
        ksort( $titles_by_owner_type );

        $data = array(
            'image'          => ! empty( $images_by_owner_type ) ? reset( $images_by_owner_type ) : '',
            'title_override' => ! empty( $titles_by_owner_type ) ? reset( $titles_by_owner_type ) : '',
        );

        set_transient( $cache_key, $data, HOUR_IN_SECONDS );
        return $data;
    }
}


/**
 * 5. TESSITURA API HELPER: VERIFY PERFORMANCE ON SALE STATUS
 */
if ( ! function_exists( 'mlab_is_performance_on_sale' ) ) {
    function mlab_is_performance_on_sale( $performance_id ) {
        if ( ! function_exists( 'wpgetapi_endpoint' ) ) {
            return true;
        }

        $perf_details = wpgetapi_endpoint( 'tessitura', 'performance_details', array(
            'debug'         => false,
            'url_variables' => array(
                'id' => $performance_id,
            ),
        ) );

        if ( empty( $perf_details ) || ! is_array( $perf_details ) || isset( $perf_details['error'] ) ) {
            return true;
        }

        $status_id          = isset( $perf_details['Status']['Id'] ) ? $perf_details['Status']['Id'] : null;
        $status_description = strtolower( isset( $perf_details['Status']['Description'] ) ? $perf_details['Status']['Description'] : '' );

        return ( $status_id == 1 || $status_description === 'on sale' );
    }
}


/**
 * 6. MAIN DYNAMIC EVENT SHORTCODE [ml_upcoming_events]
 */
if ( ! function_exists( 'mlab_display_upcoming_events' ) ) {
    function mlab_display_upcoming_events( $atts = array() ) {
        if ( ! function_exists( 'wpgetapi_endpoint' ) ) {
            return '<!-- WPGetAPI Plugin Not Active -->';
        }

        $atts = shortcode_atts( array(
            'season' => '',
        ), $atts, 'ml_upcoming_events' );

        $season_id = ! empty( $atts['season'] ) ? $atts['season'] : mlab_get_active_season_id();
        $query_string = $season_id ? 'productionSeasonId=' . esc_attr( $season_id ) : '';

        $response = wpgetapi_endpoint( 'tessitura', 'perf_summary', array(
            'debug'           => false,
            'query_variables' => $query_string,
        ) );

        $performances = array();
        if ( is_array( $response ) ) {
            if ( isset( $response['PerformanceSummary'] ) ) {
                $performances = $response['PerformanceSummary'];
            } elseif ( isset( $response['PerformanceSummaries'] ) ) {
                $performances = $response['PerformanceSummaries'];
            } else {
                $performances = $response;
            }
        }

        if ( empty( $performances ) || ! is_array( $performances ) ) {
            return '<!-- Tessitura Debug: perf_summary returned empty payload for Season ' . esc_html( $season_id ) . ' -->';
        }

        if ( isset( $performances['Id'] ) ) {
            $performances = array( $performances );
        }

        $unique_events = array();
        $now           = time();

        foreach ( $performances as $perf ) {
            if ( ! is_array( $perf ) ) continue;

            $id          = isset( $perf['Id'] ) ? $perf['Id'] : '';
            $raw_date    = isset( $perf['PerformanceDateTime'] ) ? $perf['PerformanceDateTime'] : '';
            $base_title  = isset( $perf['Description'] ) ? $perf['Description'] : '';

            if ( ! $id ) continue;

            // Robust timestamp conversion
            $perf_time = ! empty( $raw_date ) ? strtotime( $raw_date ) : 0;

            // If performance date passed, skip
            if ( $perf_time < $now ) {
                continue;
            }

            // On-sale check
            if ( function_exists( 'mlab_is_performance_on_sale' ) && ! mlab_is_performance_on_sale( $id ) ) {
                continue;
            }

            $web_contents = function_exists( 'mlab_get_web_contents' ) ? mlab_get_web_contents( $id ) : array();
            $final_title  = ! empty( $web_contents['title_override'] ) ? $web_contents['title_override'] : $base_title;
            $image_url    = ! empty( $web_contents['image'] ) ? $web_contents['image'] : '';

            if ( ! isset( $unique_events[ $final_title ] ) ) {
                $unique_events[ $final_title ] = array(
                    'id'    => $id,
                    'title' => $final_title,
                    'date'  => $raw_date,
                    'image' => $image_url,
                );
            }

            if ( count( $unique_events ) >= 2 ) break;
        }

        if ( empty( $unique_events ) ) {
            return '<!-- Tessitura Debug: All performances filtered out (past dates or not on sale). Season ID: ' . esc_html( $season_id ) . ' -->';
        }

        ob_start();
        ?>
        <?php foreach ( $unique_events as $event ) : 
            $display_month = date( 'M', strtotime( $event['date'] ) );
            $display_day   = date( 'j', strtotime( $event['date'] ) );
            $link          = "https://secure.pittsburghkids.org/0/" . $event['id'] . "/?site=mlab";
            $img_url       = ! empty( $event['image'] ) ? $event['image'] : get_stylesheet_directory_uri() . '/assets/images/placeholder.jpg';
        ?>
            <div class="event">
              <a href="<?php echo esc_url( $link ); ?>" class="event__image">
                <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $event['title'] ); ?>">
                <div class="event__detail">
                  <div class="event__dates">
                    <span><?php echo esc_html( $display_month ); ?> <?php echo esc_html( $display_day ); ?></span>
                  </div>
                  <div class="event__title"><?php echo esc_html( $event['title'] ); ?></div>
                </div>
              </a>
            </div>
        <?php endforeach; ?>
        <?php
        return ob_get_clean();
    }
    add_shortcode( 'ml_upcoming_events', 'mlab_display_upcoming_events' );
}


/**
 * 7. GENERAL ADMISSION CARD SHORTCODE [tessitura_ga_card]
 */
if ( ! function_exists( 'render_tessitura_general_admission_card' ) ) {
    function render_tessitura_general_admission_card() {
        $api_data = wpgetapi_endpoint( 'tessitura', 'get_seasons', array( 'debug' => false ) );

        if ( empty( $api_data ) || ! is_array( $api_data ) ) {
            return '<p>No event data available.</p>';
        }

        $ga_items = array_filter( $api_data, function( $item ) {
            return isset( $item['Production']['Id'] ) && 12 === (int) $item['Production']['Id'];
        } );

        $ga_event = ! empty( $ga_items ) ? reset( $ga_items ) : null;

        if ( ! $ga_event ) {
            return '';
        }

        $title      = ! empty( $ga_event['Fulltext'] ) ? $ga_event['Fulltext'] : $ga_event['Description'];
        $start_date = ! empty( $ga_event['FirstPerformanceDate'] ) ? date( 'F j, Y', strtotime( $ga_event['FirstPerformanceDate'] ) ) : '';
        $end_date   = ! empty( $ga_event['LastPerformanceDate'] ) ? date( 'F j, Y', strtotime( $ga_event['LastPerformanceDate'] ) ) : '';
        
        $image_url  = get_stylesheet_directory_uri() . '/assets/images/general-admission.jpg';

        ob_start();
        ?>
        <div class="museum-event-card">
            <div class="event-image">
                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
            </div>
            <div class="event-details">
                <span class="event-dates"><?php echo esc_html( $start_date . ' – ' . $end_date ); ?></span>
                <h3 class="event-title"><?php echo esc_html( $title ); ?></h3>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    add_shortcode( 'tessitura_ga_card', 'render_tessitura_general_admission_card' );
}