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


/* ==========================================================================
   Tessitura API - Upcoming Homepage Events Bridge
   ========================================================================== */

if ( ! function_exists( 'mlab_get_upcoming_tessitura_events' ) ) {
    function mlab_get_upcoming_tessitura_events() {
        // 1. Return cached events if transient exists
        $cached_events = get_transient( 'mlab_homepage_events' );
        if ( false !== $cached_events && ! empty( $cached_events ) ) {
            return $cached_events;
        }

        // 2. Query Tessitura starting from today
        $today     = date( 'Y-m-d' );
        $api_url   = 'https://secure.pittsburghkids.org/api/tessitura/perf_summary?PerformanceStartDate=' . $today;
        $raw_items = array();

        $request = wp_remote_get( $api_url, array( 'timeout' => 15 ) );

        if ( ! is_wp_error( $request ) ) {
            $body = wp_remote_retrieve_body( $request );
            $data = json_decode( $body, true );

            if ( is_array( $data ) ) {
                if ( isset( $data['PerformanceSummary'] ) ) {
                    $data = $data['PerformanceSummary'];
                }
                if ( isset( $data['Id'] ) ) {
                    $raw_items[] = $data;
                } else {
                    $raw_items = $data;
                }
            }
        }

        // Fallback query via WPGetAPI if direct endpoint returned empty
        if ( empty( $raw_items ) && function_exists( 'wpgetapi_endpoint' ) ) {
            $response = wpgetapi_endpoint( 'tessitura', 'perf_summary', array( 'query_variables' => 'PerformanceStartDate=' . $today ) );
            
            if ( is_array( $response ) && isset( $response['body'] ) ) {
                $data = is_string( $response['body'] ) ? json_decode( $response['body'], true ) : $response['body'];
            } else {
                $data = is_string( $response ) ? json_decode( $response, true ) : $response;
            }

            if ( is_array( $data ) ) {
                if ( isset( $data['PerformanceSummary'] ) ) {
                    $data = $data['PerformanceSummary'];
                }
                if ( isset( $data['Id'] ) ) {
                    $raw_items[] = $data;
                } else {
                    $raw_items = (array) $data;
                }
            }
        }

        // 3. Process and Normalise Event Items
        $events          = array();
        $placeholder_img = get_stylesheet_directory_uri() . '/assets/images/placeholder.jpg';

        if ( ! empty( $raw_items ) && is_array( $raw_items ) ) {
            foreach ( $raw_items as $perf ) {
                if ( ! is_array( $perf ) ) continue;

                $perf_id = isset( $perf['Id'] ) ? $perf['Id'] : '';
                $prod_id = isset( $perf['ProductionId'] ) ? $perf['ProductionId'] : ( isset( $perf['PackageId'] ) ? $perf['PackageId'] : '0' );

                if ( ! $perf_id ) continue;

                // Title Fallbacks
                $title = '';
                if ( ! empty( $perf['Description'] ) ) {
                    $title = $perf['Description'];
                } elseif ( ! empty( $perf['Title'] ) ) {
                    $title = $perf['Title'];
                } elseif ( ! empty( $perf['Name'] ) ) {
                    $title = $perf['Name'];
                } else {
                    $title = 'Upcoming Workshop';
                }

                // Date Fallbacks
                $date_raw = '';
                if ( ! empty( $perf['PerformanceDateTime'] ) ) {
                    $date_raw = $perf['PerformanceDateTime'];
                } elseif ( ! empty( $perf['Date'] ) ) {
                    $date_raw = $perf['Date'];
                } elseif ( ! empty( $perf['StartDateTime'] ) ) {
                    $date_raw = $perf['StartDateTime'];
                } else {
                    $date_raw = date( 'Y-m-d' );
                }

                // Check for Web Content Overrides in WordPress
                $event_image = '';
                if ( function_exists( 'mlab_get_web_contents' ) ) {
                    $web_contents = mlab_get_web_contents( $perf_id );

                    if ( ! empty( $web_contents['title_override'] ) ) {
                        $title = $web_contents['title_override'];
                    }

                    if ( ! empty( $web_contents['image'] ) ) {
                        $event_image = $web_contents['image'];
                    } elseif ( ! empty( $web_contents['media_url'] ) ) {
                        $event_image = $web_contents['media_url'];
                    } elseif ( ! empty( $web_contents['featured_image'] ) ) {
                        $event_image = $web_contents['featured_image'];
                    }
                }

                $link = "https://secure.pittsburghkids.org/" . esc_attr( $prod_id ) . "/" . esc_attr( $perf_id );

                if ( ! isset( $events[ $perf_id ] ) ) {
                    $events[ $perf_id ] = array(
                        'id'        => $perf_id,
                        'title'     => $title,
                        'date'      => $date_raw,
                        'timestamp' => strtotime( $date_raw ),
                        'image'     => ! empty( $event_image ) ? $event_image : $placeholder_img,
                        'link'      => $link,
                    );
                }
            }
        }

        // 4. Sort Chronologically Ascending and Store Top 2
        if ( ! empty( $events ) ) {
            usort( $events, function( $a, $b ) {
                return $a['timestamp'] - $b['timestamp'];
            });

            $events = array_slice( $events, 0, 2 );

            // Cache for 1 hour
            set_transient( 'mlab_homepage_events', $events, HOUR_IN_SECONDS );
        }

        return $events;
    }
}